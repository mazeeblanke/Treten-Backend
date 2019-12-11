<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastNameFirstNameProfilePicOtherNamePhoneNumberUserableIdUserableType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->text('profile_pic')->nullable();
            $table->string('other_name', 255)->nullable();
            $table->string('phone_number', 255)->nullable();
            $table->text('title')->nullable();
            $table->unsignedBigInteger('userable_id')->nullable();
            $table->text('userable_type')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->string('name')->nullable();
        });
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'profile_pic',
                'other_name',
                'phone_number',
                'userable_id',
                'title',
                'userable_type',
            ]);
        });
    }
}
