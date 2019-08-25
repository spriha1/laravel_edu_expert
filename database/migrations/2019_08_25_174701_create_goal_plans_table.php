<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goal_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->text('goal');
            $table->integer('from_time');
            $table->integer('to_time')->default('0');
            $table->integer('on_date');
            $table->integer('total_time')->default('0');
            $table->tinyInteger('check_status')->default('0');
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
        Schema::dropIfExists('goal_plans');
    }
}
