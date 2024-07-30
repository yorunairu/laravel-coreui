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
        Schema::table('m_uom', function (Blueprint $table) {
            $table->renameColumn('m_uom', 'name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_uom', function (Blueprint $table) {
            $table->renameColumn('name', 'm_oum');
        });
    }
};
