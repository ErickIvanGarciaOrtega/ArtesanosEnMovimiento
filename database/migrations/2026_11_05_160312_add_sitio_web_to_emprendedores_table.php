<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('emprendedores', function (Blueprint $table) {
        $table->string('sitio_web')->nullable()->after('tiktok');
    });
}

public function down()
{
    Schema::table('emprendedores', function (Blueprint $table) {
        $table->dropColumn('sitio_web');
    });
}
};
