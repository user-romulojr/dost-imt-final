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
        Schema::create('agency_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title', '50');
            $table->timestamps();
        });

        DB::table('agency_groups')->insert([
            ['title' => 'Advisory Body', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Scientific and Technological Service Institute', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Sectoral Planning Council', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Research and Development Institute', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_groups');
    }
};
