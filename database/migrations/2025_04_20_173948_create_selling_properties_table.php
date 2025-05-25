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
        Schema::create('sold_properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->string('address');
            $table->integer('size');
            $table->decimal('price', 10, 2);
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->string('sale_method');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->string('seller_type'); 
            $table->foreignId('seller_id')->nullable()->constrained('users')->onDelete('set null'); 
            $table->boolean('is_active')->default(true); 
            $table->json('images')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sold_properties');
    }
};
