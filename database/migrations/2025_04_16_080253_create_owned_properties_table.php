<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnedPropertiesTable extends Migration
{
    public function up()
    {
        Schema::create('owned_properties', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsiged();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->morphs('ownable');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('owned_properties');
    }
}