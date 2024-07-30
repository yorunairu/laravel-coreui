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
            $table->string('bank_guarantee')->nullable()->after('win_lost');
            $table->string('attachment')->nullable()->after('win_lost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_list_tender', function (Blueprint $table) {
            $table->dropColumn('bank_guarantee');
            $table->dropColumn('attachment');
        });
    }
};
