<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('name');
            $table->integer('litres');
            $table->decimal('price', 15, 2);
            $table->string('period');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
