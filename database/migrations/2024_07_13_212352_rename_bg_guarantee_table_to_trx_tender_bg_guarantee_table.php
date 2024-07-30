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
        Schema::table('trx_tender_bg_guarantee', function (Blueprint $table) {
            Schema::rename('trx_bg_guarantees','trx_tender_bg_guarantees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_tender_bg_guarantee', function (Blueprint $table) {
            Schema::rename('trx_tender_bg_guarantees','trx_bg_guarantees');
        });
    }
};
