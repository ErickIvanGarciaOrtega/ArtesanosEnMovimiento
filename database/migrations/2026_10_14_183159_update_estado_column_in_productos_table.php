<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Cambiamos el tipo a VARCHAR(50) por defecto 'activo' para evitar problemas de truncado
            $table->string('estado', 50)->default('activo')->change();
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('estado')->change();
        });
    }
};