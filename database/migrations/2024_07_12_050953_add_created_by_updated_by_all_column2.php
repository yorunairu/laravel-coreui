<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Get all table names from the database
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $tableName = reset($table);

            // Check if the table already has 'created_by' and 'updated_by' columns
            if (!Schema::hasColumn($tableName, 'created_by') && !Schema::hasColumn($tableName, 'updated_by')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->unsignedBigInteger('created_by')->nullable()->after('created_at');
                    $table->unsignedBigInteger('updated_by')->nullable()->after('updated_at');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the columns if you want to rollback
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $tableName = reset($table);

            // Check if the table has 'created_by' and 'updated_by' columns
            if (Schema::hasColumn($tableName, 'created_by') && Schema::hasColumn($tableName, 'updated_by')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('created_by');
                    $table->dropColumn('updated_by');
                });
            }
        }
    }
};
