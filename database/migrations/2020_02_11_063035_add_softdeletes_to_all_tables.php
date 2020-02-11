<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftdeletesToAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('instructors', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('contact_us', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('blog_post_tag', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('course_paths', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('course_categories', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('resources', function (Blueprint $table) {
            $table->softDeletes();
            $table
                ->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('course_batches', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('course_enrollments', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('course_favorites', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('course_sections', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('course_videos', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('user_groups', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('course_video_completions', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('user_group_allocations', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('course_instructors', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('course_categories_allocation', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('course_batch_author', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('instructor_reviews', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('course_reviews', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("users", function ($table) {
            $table->dropSoftDeletes();
        });

        Schema::table("students", function ($table) {
            $table->dropSoftDeletes();
        });

        Schema::table("instructors", function ($table) {
            $table->dropSoftDeletes();
        });

        Schema::table("contact_us", function ($table) {
            $table->dropSoftDeletes();
        });

        Schema::table("tags", function ($table) {
            $table->dropSoftDeletes();
        });

        Schema::table("blog_posts", function ($table) {
            $table->dropSoftDeletes();
        });

        Schema::table("blog_post_tag", function ($table) {
            $table->dropSoftDeletes();
        });

        Schema::table("transactions", function ($table) {
            $table->dropSoftDeletes();
        });

        Schema::table("messages", function ($table) {
            $table->dropSoftDeletes();
        });

        Schema::table("course_paths", function ($table) {
            $table->dropSoftDeletes();
        });

        Schema::table("courses", function ($table) {
            $table->dropSoftDeletes();
        });

        Schema::table("course_categories", function ($table) {
            $table->dropSoftDeletes();
        });

        Schema::table("reviews", function ($table) {
            $table->dropSoftDeletes();
        });

        Schema::table("resources", function ($table) {
            $table->dropSoftDeletes();
        });

        Schema::table("course_batches", function ($table) {
            $table->dropSoftDeletes();
        });

        Schema::table("course_favorites", function ($table) {
            $table->dropSoftDeletes();
        });

        Schema::table("course_enrollments", function ($table) {
            $table->dropSoftDeletes();
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('course_sections', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('course_videos', function (Blueprint $table) {
             $table->dropSoftDeletes();
        });

        Schema::table('user_groups', function (Blueprint $table) {
             $table->dropSoftDeletes();
        });

        Schema::table('course_video_completions', function (Blueprint $table) {
             $table->dropSoftDeletes();
        });

        Schema::table('user_group_allocations', function (Blueprint $table) {
             $table->dropSoftDeletes();
        });

        Schema::table('course_instructors', function (Blueprint $table) {
             $table->dropSoftDeletes();
        });

        Schema::table('course_categories_allocation', function (Blueprint $table) {
             $table->dropSoftDeletes();
        });

        Schema::table('course_batch_author', function (Blueprint $table) {
             $table->dropSoftDeletes();
        });

        Schema::table('instructor_reviews', function (Blueprint $table) {
             $table->dropSoftDeletes();
        });

        Schema::table('course_reviews', function (Blueprint $table) {
             $table->dropSoftDeletes();
        });

        Schema::table('testimonials', function (Blueprint $table) {
             $table->dropSoftDeletes();
        });
    }
}
