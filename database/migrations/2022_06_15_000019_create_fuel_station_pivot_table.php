<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuelStationPivotTable extends Migration
{
    public function up()
    {
        Schema::create('fuel_station', function (Blueprint $table) {
            $table->unsignedBigInteger('station_id');
            $table->foreign('station_id', 'station_id_fk_6798327')->references('id')->on('stations')->onDelete('cascade');
            $table->unsignedBigInteger('fuel_id');
            $table->foreign('fuel_id', 'fuel_id_fk_6798327')->references('id')->on('fuels')->onDelete('cascade');
        });
    }
}
