<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCascadeToMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            // $table->unsignedBigInteger('sender_id')->change();
            // $table->unsignedBigInteger('receiver_id')->change();

            $this->changeColumnType('messages','sender_id','BIGINT UNSIGNED');
            $this->changeColumnType('messages','receiver_id','BIGINT UNSIGNED');

            $table
                ->foreign('sender_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table
                ->foreign('receiver_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function changeColumnType($table, $column, $newColumnType) {
        DB::statement("ALTER TABLE $table MODIFY COLUMN $column $newColumnType");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $this->changeColumnType('messages','sender_id','INT UNSIGNED');
            $this->changeColumnType('messages','receiver_id','INT UNSIGNED');
        });
    }
}
