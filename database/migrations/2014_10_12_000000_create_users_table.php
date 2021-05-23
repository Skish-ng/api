<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('role')->nullable();
            $table->string('username');
            $table->string('password');
            $table->string('fullname')->nullable();
            $table->string('tagline')->nullable();
            $table->string('dp')->nullable();

            $table->string('tel')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('address')->nullable();

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            $table->text('bio')->nullable();
            $table->text('pricing')->nullable();

            $table->tinyInteger('state');
            $table->text('passport')->nullable();
            $table->text('documents')->nullable();#separated by comma$table->rememberToken();
            $table->text('token');#separated by comma$table->rememberToken();
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
        //Schema::dropIfExists('users');
    }
}
