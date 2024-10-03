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
        Schema::create('indicators_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('agency_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('indicator_status_id')->default('1')->constrained(table: 'indicator_statuses');
            $table->foreignId('agency_head_approver_id')->nullable()->constrained(table: 'users');
            $table->foreignId('planning_director_approver_id')->nullable()->constrained(table: 'users');
            $table->foreignId('executive_approver_id')->nullable()->constrained(table: 'users');
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicators_groups');
    }
};
