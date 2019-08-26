<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTriggerBeforeGoalPlansUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    DB::unprepared('CREATE TRIGGER before_goal_plans_update BEFORE UPDATE ON goal_plans FOR EACH ROW BEGIN SET new.total_time = new.to_time - new.from_time; END;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS before_goal_plans_update;');
    }
}
