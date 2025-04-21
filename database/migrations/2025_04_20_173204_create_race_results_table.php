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
        Schema::create('race_results', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('race_id')->unsiged();
            $table->foreign('race_id')->references('id')->on('races')->onDelete('cascade');

            $table->unsignedBigInteger('horse_id')->unsiged();
            $table->foreign('horse_id')->references('id')->on('horses')->onDelete('cascade');

            $table->integer('position');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('race_results');
    }
};
