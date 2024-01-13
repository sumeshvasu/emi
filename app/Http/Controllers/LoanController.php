<?php
/**
 * Controller : LoanController.
 *
 * This file used to handle Loan
 *
 * @author Sumesh K V <sumeshvasu@gmail.com>
 *
 * @version 1.0
 */
namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class LoanController extends Controller
{
    /**
     * Display the loan details page.
     *
     * @param int $emi
     *
     * @return \Illuminate\View\View
     */
    public function index($emi): View
    {
        $loan = [
            'emi' => $emi,
            'emi_table_column' => [],
            'emi_list' => [],
            'loan_details' => [],
        ];

        $loanDetails = DB::table('loan_details')->get();

        if ($emi == 1) {
            $list = [];
            $emiDetails = $this->buildEmi($loanDetails);

            array_unshift($emiDetails['table_fields'], 'clientid');

            $loan['emi_table_column'] = $emiDetails['table_fields'];

            foreach ($emiDetails['emi_list'] as $key => $value) {
                foreach ($emiDetails['table_fields'] as $field) {
                    if (!array_key_exists($field, $value)) {
                        $value[$field] = '0.00';
                    }
                }

                $emiDetails['emi_list'][$key] = $value;
            }

            foreach ($emiDetails['emi_list'] as $key => $value) {
                foreach ($value as $keyRaw => $valueRaw) {
                    $tempList = [];
                    foreach ($emiDetails['table_fields'] as $field) {
                        $tempList[$field] = $emiDetails['emi_list'][$key][$field];
                    }
                }
                $list[$key] = $tempList;
            }

            $this->storeEmi($emiDetails['table_fields'], $list);

            $loan['emi_list'] = $list;

        } else {
            $loan['loan_details'] = $loanDetails;
        }

        return view('loan.details', ['loan' => $loan]);
    }

    /**
     * Build EMI.
     *
     * @param array $loanDetails
     *
     * @return array
     */
    private function buildEmi($loanDetails)
    {
        $tempTableFields = [];
        $emiList = [];

        foreach ($loanDetails as $value) {
            $tempMonthList = $this->getMonthListFromDate($value->first_payment_date, $value->last_payment_date);
            $tempTableFields = array_merge($tempTableFields, $tempMonthList);
        }

        $tableFields = $this->sortDateList($loanDetails, $tempTableFields);

        foreach ($loanDetails as $value) {
            $emi = [];

            $monthList = $this->getMonthListFromDate($value->first_payment_date, $value->last_payment_date);

            $perMonthAmount = round($value->loan_amount / $value->num_of_payment, 2);

            $finalEmiAmont = $perMonthAmount - (($perMonthAmount * $value->num_of_payment) - $value->loan_amount);

            $emi['clientid'] = $value->clientid;

            foreach ($monthList as $field) {
                $emi[$field] = round($perMonthAmount, 2);
            }

            $emi[array_key_last($emi)] = $finalEmiAmont;

            $emiList[] = $emi;
        }

        return [
            'table_fields' => array_unique($tableFields),
            'emi_list' => $emiList,
        ];
    }

    /**
     * Generate Month List From Date.
     *
     * @param string $startDate
     * @param string $endDate
     *
     * @return array
     */
    private function getMonthListFromDate($startDate, $endDate)
    {
        $period = new CarbonPeriod(Carbon::parse($startDate)->startOfMonth(), '1 month', Carbon::parse($endDate)->startOfMonth());

        foreach ($period as $month) {
            $months[] = $month->format('Y_M');
        }

        return $months;
    }

    /**
     * Sort Date List.
     *
     * @param array $loanDetails
     * @param array $tempTableFields
     *
     * @return array
     */
    private function sortDateList($loanDetails, $tempTableFields)
    {
        $months = [];

        foreach ($loanDetails as $value) {
            $period = new CarbonPeriod(Carbon::parse($value->first_payment_date)->startOfMonth(), '1 month', Carbon::parse($value->last_payment_date)->startOfMonth());

            foreach ($period as $month) {
                $months[] = $month->format('d-m-Y');
            }
        }

        array_unique($months);

        usort($months, function ($a, $b) {
            $dateTimestamp1 = strtotime($a);
            $dateTimestamp2 = strtotime($b);

            return $dateTimestamp1 < $dateTimestamp2 ? -1 : 1;
        });

        $minMonth = $months[0];
        $maxMonth = $months[count($months) - 1];

        $period = new CarbonPeriod($minMonth, '1 month', $maxMonth);

        foreach ($period as $month) {
            $wholeMonths[] = $month->format('Y_M');
        }

        array_values(array_unique($wholeMonths));

        foreach ($wholeMonths as $key => $value) {
            if (!in_array($value, $tempTableFields)) {
                unset($wholeMonths[$key]);
            }
        }

        return $wholeMonths;
    }

    /**
     * Store EMI.
     *
     * @param array $tableFields
     * @param array $emiList
     */
    private function storeEmi($tableFields, $emiList)
    {
        $tableName = 'emi_details';

        if (Schema::hasTable($tableName)) {
            Schema::dropIfExists($tableName);
        }

        $this->createEmiTable($tableName, $tableFields);
        $this->storeDataToEmiTable($tableName, $tableFields, $emiList);
    }

    /**
     * Create EMI Table.
     *
     * @param string $tableName
     * @param array $tableFields
     */
    private function createEmiTable($tableName, $tableFields)
    {
        $fields = "clientid INT(11) NOT NULL, ";

        array_shift($tableFields);

        foreach ($tableFields as $key => $value) {
            if (array_key_last($tableFields) != $key) {
                $fields = $fields . $value . " DOUBLE(8,2) NOT NULL, ";
            } else {
                $fields = $fields . $value . " DOUBLE(8,2) NOT NULL";
            }
        }

        $createTableSqlString =
            "CREATE TABLE $tableName ($fields)
                    COLLATE='utf8_general_ci'
                    ENGINE=InnoDB";

        DB::statement($createTableSqlString);
    }

    /**
     * Store Data To Emi Table.
     *
     * @param string $tableName
     * @param array $tableFields
     * @param array $emiList
     */
    private function storeDataToEmiTable($tableName, $tableFields, $emiList)
    {
        $values = '';

        foreach ($emiList as $key => $value) {
            $values = $values . "(";

            foreach ($value as $keyRow => $valueRow) {
                if (array_key_last($value) != $keyRow) {
                    $values = $values . $valueRow . ",";
                } else {
                    $values = $values . $valueRow;
                }
            }

            if (array_key_last($emiList) != $key) {
                $values = $values . "), ";
            } else {
                $values = $values . ")";
            }
        }

        $insertTableSqlString = "INSERT INTO " . $tableName . " (" . implode(',', $tableFields) . ") VALUES " . $values;

        DB::statement($insertTableSqlString);
    }
}
