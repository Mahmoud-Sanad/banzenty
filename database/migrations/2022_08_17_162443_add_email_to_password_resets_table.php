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
        Schema::table('password_resets', function (Blueprint $table) {
            $table->string('email')->index()->after('id');
            $table->dropColumn('id');
            $table->dropConstrainedForeignId('user_id');
            $table->dropUnique(['token']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('password_resets', function (Blueprint $table) {
            $table->id()->after('email');
            $table->dropColumn('email');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unique('token');
        });
    }
};
