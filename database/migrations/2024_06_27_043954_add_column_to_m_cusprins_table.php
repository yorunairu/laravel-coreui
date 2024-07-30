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
        Schema::table('m_cusprins', function (Blueprint $table) {
            $table->text('remaks')->nullable()->after('type');
            $table->enum('status', [1])->default(1)->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_cusprins', function (Blueprint $table) {
            $table->softDeletes('remakes');
            $table->softDeletes('status');
        });
    }
};
