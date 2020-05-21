<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseBatchesAuthorsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_batch_author', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('course_batch_id')->nullable();
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('course_id');
            $table->boolean('active')->default(true);
            $table->text('timetable')->nullable();
            $table->timestamps();

            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            $table->foreign('author_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            $table->foreign('course_batch_id')
                ->references('id')
                ->on('course_batches')
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
        Schema::dropIfExists('course_batch_author');
    }
}