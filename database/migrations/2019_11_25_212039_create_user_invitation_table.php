<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInvitationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_invitations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->enum('role', [
                'instructor',
                'student',
                'admin'
            ]);
            $table->text('token');
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
        Schema::dropIfExists('user_invitations');
    }
}
