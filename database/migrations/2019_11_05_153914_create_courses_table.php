<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->text('title');
            $table->bigIncrements('id');
            $table->text('faqs')->nullable();
            $table->text('modules')->nullable();
            $table->unsignedBigInteger('author_id');
            $table->double('duration')->nullable();
            $table->text('institution')->nullable();
            $table->text('description')->nullable();
            $table->text('banner_image')->nullable();
            $table->double('price', 12, 2)->nullable();
            $table->text('certification_by')->nullable();
            $table->boolean('is_published')->default(false);
            $table->datetime('published_at')->nullable();
            $table->double('avg_rating')->default(3);
            // $table->unsignedInteger('course_path_id');
            $table->unsignedBigInteger('course_path_id')->nullable();
            $table->unsignedBigInteger('course_path_position')->default(0)->nullable();
            $table->timestamps();

            $table->foreign('course_path_id')
                ->references('id')
                ->on('course_paths')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // $table->foreign('author_id')
            //     ->references('id')
            //     ->on('users')
            //     ->onDelete('cascade')
            //     ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
