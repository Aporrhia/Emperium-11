<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoordinatesToApartmentsAndHouses extends Migration
{
    public function up()
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->decimal('latitude', 9, 6)->nullable()->after('images');
            $table->decimal('longitude', 9, 6)->nullable()->after('latitude');
        });

        Schema::table('houses', function (Blueprint $table) {
            $table->decimal('latitude', 9, 6)->nullable()->after('images');
            $table->decimal('longitude', 9, 6)->nullable()->after('latitude');
        });
    }

    public function down()
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });

        Schema::table('houses', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
}