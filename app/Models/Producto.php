<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'emprendedor_id',
        'marca_id',
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'stock',
        'estado',
        'imagen'
    ];

    public function emprendedor()
    {
        return $this->belongsTo(Emprendedor::class);
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}