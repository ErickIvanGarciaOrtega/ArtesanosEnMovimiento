<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoImagen extends Model
{
    use HasFactory;
    
    protected $fillable = ['producto_id', 'ruta'];

    // NUEVO: Agregar esta relación para que la imagen sepa a qué producto pertenece
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}