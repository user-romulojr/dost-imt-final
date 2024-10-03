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
        Schema::create('indicator_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('title', '30');
            $table->timestamps();
        });

        DB::table('indicator_statuses')->insert([
            ['title' => 'Draft', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Pending - Agency Head', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Pending - Planning Director', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Pending - Executive', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Approved', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicator_statuses');
    }
};
