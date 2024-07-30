<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesToTables extends Command
{
    protected $signature = 'db:add-soft-deletes';
    protected $description = 'Add soft deletes columns to all tables if they do not exist';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get the list of tables in the database
        $tables = DB::select('SHOW TABLES');
        $database = env('DB_DATABASE');

        foreach ($tables as $table) {
            $tableName = $table->{'Tables_in_' . $database};

            // Check if the table already has the columns
            $columns = Schema::getColumnListing($tableName);

            if (!in_array('deleted_at', $columns)) {
                $this->info("Adding 'deleted_at' column to table: $tableName");
                Schema::table($tableName, function (Blueprint $table) {
                    $table->softDeletes();
                });
            }

            if (!in_array('deleted_by', $columns)) {
                $this->info("Adding 'deleted_by' column to table: $tableName");
                Schema::table($tableName, function (Blueprint $table) {
                    $table->unsignedBigInteger('deleted_by')->nullable()->after('deleted_at');
                });
            }
        }

        $this->info('Migration complete.');
    }
}
