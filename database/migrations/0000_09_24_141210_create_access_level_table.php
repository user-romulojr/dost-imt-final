<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('access_levels', function (Blueprint $table) {
            $table->id();
            $table->string('title', '20');
            $table->timestamps();
        });

        DB::table('access_levels')->insert([
            ['title' => 'Executive', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Planning Director', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'System Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Agency Head', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Agency Focal', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_levels');
    }
};
