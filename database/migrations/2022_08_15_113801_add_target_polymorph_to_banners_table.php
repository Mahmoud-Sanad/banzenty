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
        Schema::table('banners', function (Blueprint $table) {
            $table->tinyInteger('active')->default(1)->after('name');
            $table->smallInteger('order')->nullable()->after('active');
            $table->string('target_type')->nullable()->after('order');
            $table->foreignId('target_id')->nullable()->after('target_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['active', 'order', 'target_type', 'target_id']);
        });
    }
};
