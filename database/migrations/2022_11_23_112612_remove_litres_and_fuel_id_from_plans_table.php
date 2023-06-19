<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('litres');
            $table->smallInteger('period')->default(1)->change();
        });

        Schema::table('plan_user', function (Blueprint $table) {
            $table->renameColumn('litres', 'remaining');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('subscription_litres', 'from_subscription');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('litres');
            $table->string('period')->change();
        });

        Schema::table('plan_user', function (Blueprint $table) {
            $table->renameColumn('remaining', 'litres');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('from_subscription', 'subscription_litres');
        });
    }
};
