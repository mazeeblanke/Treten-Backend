<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_id');
            $table->string('name'); // a combination of first name and last name
            $table->string('email');
            $table->string('description')->nullable();
            $table->string('amount');
            $table->string('transaction_id');
            $table->string('user_id');
            $table->string('course_id');
            $table->string('course_batch_id');
            $table->string('authentication_code')->nullable();
            $table->string('status');
            $table->text('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
