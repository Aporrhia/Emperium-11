<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivacySettingsTable extends Migration
{
    public function up()
    {
        Schema::create('privacy_settings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->unsiged();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->boolean('show_in_tier_list')->default(true);
            $table->boolean('hide_personal_info')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('privacy_settings');
    }
}