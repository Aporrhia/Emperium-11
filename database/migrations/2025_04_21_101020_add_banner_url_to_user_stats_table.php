<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_stats', function (Blueprint $table) {
            $table->string('banner')->nullable()->after('avatar');
        });
    }

    public function down()
    {
        Schema::table('user_stats', function (Blueprint $table) {
            $table->dropColumn('banner');
        });
    }
};
