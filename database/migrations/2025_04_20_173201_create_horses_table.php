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
        Schema::create('horses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('speed')->default(50);
            $table->timestamps();
        });

        \App\Models\Horse::create(['name' => 'Thunderbolt', 'speed' => 81]);
        \App\Models\Horse::create(['name' => 'Shadowfax', 'speed' => 78]);
        \App\Models\Horse::create(['name' => 'Fury', 'speed' => 80]);
        \App\Models\Horse::create(['name' => 'Blaze', 'speed' => 77]);
        \App\Models\Horse::create(['name' => 'Speedster', 'speed' => 79]);
        \App\Models\Horse::create(['name' => 'Stormy', 'speed' => 78]);
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horses');
    }
};
