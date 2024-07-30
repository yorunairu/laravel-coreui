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
        Schema::table('m_terms', function (Blueprint $table) {
            // $table->dropColumn('category');
            $table->string('is_rfq')->after('type');
            $table->string('is_quo')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_terms', function (Blueprint $table) {
            // $table->string('category');
            $table->dropColumn('is_rfq');
            $table->dropColumn('is_quo');
        });
    }
};
