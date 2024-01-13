<?php
/**
 * Model : LoanDetails.
 *
 * This file used to handle loan_details table
 *
 * @author Sumesh K V <sumeshvasu@gmail.com>
 *
 * @version 1.0
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'clientid',
        'num_of_payment',
        'first_payment_date',
        'last_payment_date',
        'loan_amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'first_payment_date' => 'datetime:Y-m-d',
        'last_payment_date' => 'datetime:Y-m-d',
    ];
}
