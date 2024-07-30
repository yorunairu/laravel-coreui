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
            $table->string('reviewed_by')->nullable()->after('updated_at');
            $table->string('reviewed_at')->nullable()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_tenders', function (Blueprint $table) {
            $table->dropColumn('reviewed_by');
            $table->dropColumn('reviewed_at');
        });
    }
};
