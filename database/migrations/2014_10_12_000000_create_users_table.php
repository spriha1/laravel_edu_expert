<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->string('firstname', 50);
            $table->string('lastname', 50);
            $table->string('email', 50)->unique();
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->string('email_verification_code', 32);
            $table->tinyInteger('email_verification_status')->default(0);
            $table->tinyInteger('user_reg_status')->default(0);
            $table->tinyInteger('block_status')->default(0);
            $table->tinyInteger('login_status')->default(0);
            $table->unsignedInteger('user_type_id');
            $table->tinyInteger('class')->default(1);
            $table->string('date_format', 20)->default('yyyy-mm-dd');
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
