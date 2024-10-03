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
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            $table->string('indicator');
            $table->string('operational_definition')->nullable();
            $table->year('end_year')->nullable();
            $table->boolean('is_primary')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->foreignId('hnrda_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('priority_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('sdg_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('strategic_pillar_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('thematic_area_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('indicator_status_id')->default('1')->constrained(table: 'indicator_statuses');
            $table->foreignId('indicators_group_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicators');
    }
};
