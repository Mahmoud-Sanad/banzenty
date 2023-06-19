<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRewardsTable extends Migration
{
    public function up()
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('cascade');
            $table->json('name');
            $table->json('description')->nullable();
            $table->integer('points');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
