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
        Schema::create('bg_guarantee', function (Blueprint $table) {
            $table->id();
            $table->string('no_guarantee')->nullable();
            $table->string('price')->nullable();
            $table->text('note')->nullable();
            $table->dateTime('time_period')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bg_guarantee');
    }
};
