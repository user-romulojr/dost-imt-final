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
        Schema::create('success_indicators', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->integer('target')->nullable();
            $table->integer('accomplished')->nullable();
            $table->foreignId('major_final_output_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('success_indicators');
    }
};
