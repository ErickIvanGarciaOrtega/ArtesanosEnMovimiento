<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('emprendedores', function (Blueprint $table) {
            // Agregamos la columna para la imagen, permitiendo que sea nula
            $table->string('imagen')->nullable()->after('biografia');
        });
    }

    public function down()
    {
        Schema::table('emprendedores', function (Blueprint $table) {
            $table->dropColumn('imagen');
        });
    }
};
