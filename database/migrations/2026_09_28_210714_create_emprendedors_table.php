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
    Schema::create('emprendedores', function (Blueprint $table) {
        $table->id();
        $table->string('nombre_completo');
        $table->string('username')->unique(); // Usuario para iniciar sesión
        $table->string('password');           // Contraseña cifrada
        $table->string('telefono');           // Número para WhatsApp
        $table->string('marca_o_giro');       // Nombre de la marca o giro artesanal
        $table->boolean('estado')->default(true); // Activo (1) o Inactivo (0)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emprendedors');
    }
};
