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
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location');
            $table->decimal('price', 10, 2);
            $table->string('type')->default('apartment');
            $table->string('images')->nullable();
            $table->timestamps();
        });

        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location');
            $table->decimal('price', 10, 2);
            $table->string('type')->default('house');
            $table->string('images')->nullable();
            $table->timestamps();
        });

        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location');
            $table->decimal('price', 10, 2);
            $table->string('type')->default('office');
            $table->string('images')->nullable();
            $table->timestamps();
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location');
            $table->decimal('price', 10, 2);
            $table->string('type')->default('warehouse');
            $table->string('images')->nullable();
            $table->timestamps();
        });

        Schema::create('bunkers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location');
            $table->decimal('price', 10, 2);
            $table->string('type')->default('bunker');
            $table->string('images')->nullable();
            $table->timestamps();
        });

        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location');
            $table->decimal('price', 10, 2);
            $table->string('type')->default('facility');
            $table->string('images')->nullable();
            $table->timestamps();
        });

        Schema::create('transport', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('class');
            $table->string('manufacturer');
            $table->string('type')->default('transport');
            $table->string('images')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
        Schema::dropIfExists('houses');
        Schema::dropIfExists('offices');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('bunkers');
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('transport');
    }
};