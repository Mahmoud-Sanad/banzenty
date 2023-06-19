<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanUserPivotTable extends Migration
{
    public function up()
    {
        Schema::create('plan_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id', 'user_id_fk_6799037')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('plan_id');
            $table->foreign('plan_id', 'plan_id_fk_6799037')->references('id')->on('plans')->onDelete('cascade');
        });
    }
}
