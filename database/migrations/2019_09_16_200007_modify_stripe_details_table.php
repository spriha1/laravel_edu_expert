<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyStripeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stripe_details', function($table) {
           $table->string('code');
           $table->string('stripe_account_id')->nullable($value = true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stripe_details', function($table) {
            $table->dropColumn('code');
            $table->string('stripe_account_id')->change();
        });
    }
}
