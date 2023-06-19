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
        Schema::dropIfExists('subscription_station');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('subscription_station', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('plan_user')->onDelete('cascade');
            $table->foreignId('station_id')->constrained('stations');
            $table->timestamps();
        });
    }
};
