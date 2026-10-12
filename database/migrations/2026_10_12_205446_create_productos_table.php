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
    Schema::create('productos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('emprendedor_id')->constrained('emprendedores')->onDelete('cascade');
        $table->foreignId('marca_id')->nullable()->constrained('marcas')->onDelete('set null');
        $table->foreignId('categoria_id')->nullable()->constrained('categorias')->onDelete('set null');
        $table->string('nombre');
        $table->text('descripcion');
        $table->decimal('precio', 10, 2);
        $table->string('imagen')->nullable();
        $table->integer('stock')->default(0);
        $table->enum('estado', ['publicado', 'borrador'])->default('publicado');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
