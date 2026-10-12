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
    Schema::create('seccion_webs', function (Blueprint $table) {
        $table->id();
        $table->string('seccion'); // 'inicio', 'quienes_somos', 'contacto'
        $table->string('clave');   // 'banner_titulo', 'mision', 'telefono_whatsapp'
        $table->text('valor')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seccion_webs');
    }
};
