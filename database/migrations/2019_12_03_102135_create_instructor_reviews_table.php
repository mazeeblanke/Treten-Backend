<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstructorReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructor_reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('review_text');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('course_batch_id');
            $table->unsignedBigInteger('enrollee_id');
            $table->unsignedBigInteger('author_id');
            $table->double('rating', 8, 2);
            $table->boolean('approved')->default(true);
            $table->timestamps();

            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('course_batch_id')
                ->references('id')
                ->on('course_batches')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('enrollee_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('author_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instructor_reviews');
    }
}
