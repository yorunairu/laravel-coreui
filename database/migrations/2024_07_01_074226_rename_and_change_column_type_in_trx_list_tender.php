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
            $table->renameColumn('win_lost_status', 'status_win_lost_id');
        });

        Schema::table('trx_list_tender', function (Blueprint $table) {
            // Modify the column data type
            $table->unsignedBigInteger('status_win_lost_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_list_tender', function (Blueprint $table) {
            $table->renameColumn('status_win_lost_id', 'win_lost_status');
            $table->string('win_lost_status')->change();
        });
    }
};
