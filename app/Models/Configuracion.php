<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    // Le decimos a Laravel el nombre exacto de la tabla
    protected $table = 'configuraciones';

    // Los campos que permitiremos llenar
    protected $fillable = [
        'clave',
        'valor'
    ];
}