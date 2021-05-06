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
            $table->string('role');
            $table->string('username');
            $table->string('fullname');
            $table->string('tagline');
            $table->string('dp');

            $table->string('tel');
            $table->string('whatsapp');
            $table->string('address');

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('bio');
            $table->string('pricing');

            $table->text('state');
            $table->text('passport');
            $table->text('documents');#separated by comma$table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
