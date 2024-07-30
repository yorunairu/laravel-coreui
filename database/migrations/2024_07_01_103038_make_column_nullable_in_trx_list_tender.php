<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trx_list_tender', function (Blueprint $table) {
            $table->string('win_lost')->nullable()->change();
            $table->string('status_win_lost')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_list_tender', function (Blueprint $table) {
            $table->string('win_lost')->change();
            $table->string('status_win_lost')->change();
        });
    }
};
