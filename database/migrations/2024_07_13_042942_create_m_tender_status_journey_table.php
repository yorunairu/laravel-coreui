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
        Schema::create('m_tender_status_journeys', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('order')->nullable();
            $table->string('is_non_direct')->nullable();
            $table->string('is_direct')->nullable();
            $table->string('is_non_tender')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_tender_status_journeys');
    }
};
