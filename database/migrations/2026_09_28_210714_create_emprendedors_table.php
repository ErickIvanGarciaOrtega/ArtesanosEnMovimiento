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
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('foto_perfil')->nullable();
        $table->text('biografia')->nullable();
        $table->string('categoria_principal')->nullable();
        $table->string('facebook')->nullable();
        $table->string('instagram')->nullable();
        $table->string('tiktok')->nullable();
        $table->enum('status', ['activo', 'inactivo'])->default('activo');
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
