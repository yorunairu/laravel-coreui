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
        Schema::table('trx_tenders', function (Blueprint $table) {
            $table->string('is_bb_bond')->nullable()->after('currency_id');
            $table->string('is_pb_bond')->nullable()->after('currency_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_tenders', function (Blueprint $table) {
            $table->dropColumn('is_bb_bond');
            $table->dropColumn('is_pb_bond');
        });
    }
};
