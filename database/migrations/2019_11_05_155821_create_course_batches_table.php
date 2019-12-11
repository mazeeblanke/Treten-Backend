<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_batches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('batch_name');
            $table->date('start_date');
            $table->enum('mode_of_delivery', [
                'on site',
                'on demand',
                'remote',
            ])->default('on site');
            $table->date('end_date')->nullable();
            $table->double('price', 12, 2)->nullable();
            $table->boolean('has_ended')->default(false);
            $table->boolean('class_is_full')->default(false);
            // $table->unsignedInteger('instructor_id');
            $table->unsignedBigInteger('course_id');
            // $table->text('timetable')->nullable();
            $table->timestamps();

            $table
                ->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_batches');
    }
}