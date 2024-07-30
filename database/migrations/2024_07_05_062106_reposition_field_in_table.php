<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RepositionFieldInTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trx_tender_logs', function (Blueprint $table) {
            // Move the field next to the description field
            $table->string('trx_tender_id')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trx_tender_logs', function (Blueprint $table) {
            // If you need to rollback, remove the field
            $table->dropColumn('trx_tender_id');
        });
    }
}

