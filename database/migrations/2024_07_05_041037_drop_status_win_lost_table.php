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
        Schema::dropIfExists('status_win_lost');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('status_win_lost', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();

            $table->softDeletes();
        });
    }
};
