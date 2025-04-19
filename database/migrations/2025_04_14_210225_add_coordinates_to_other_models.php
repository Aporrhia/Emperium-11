<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoordinatesToOtherModels extends Migration
{
    public function up()
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->decimal('latitude', 9, 3)->nullable()->after('images');
            $table->decimal('longitude', 9, 3)->nullable()->after('latitude');
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->decimal('latitude', 9, 3)->nullable()->after('images');
            $table->decimal('longitude', 9, 3)->nullable()->after('latitude');
        });

        Schema::table('bunkers', function (Blueprint $table) {
            $table->decimal('latitude', 9, 3)->nullable()->after('images');
            $table->decimal('longitude', 9, 3)->nullable()->after('latitude');
        });

        Schema::table('facilities', function (Blueprint $table) {
            $table->decimal('latitude', 9, 3)->nullable()->after('images');
            $table->decimal('longitude', 9, 3)->nullable()->after('latitude');
        });
    }

    public function down()
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });

        Schema::table('bunkers', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });

        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
}