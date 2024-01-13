<?php
/**
 * Create Loan Details Table
 * Migration script for table 'loan_details'.
 *
 * @author Original Sumesh KV <sumeshvasu@gmail.com>
 *
 * @version 1.0
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loan_details', function (Blueprint $table) {
            $table->integer('clientid');
            $table->integer('num_of_payment');
            $table->date('first_payment_date');
            $table->date('last_payment_date');
            $table->float('loan_amount', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_details');
    }
};
