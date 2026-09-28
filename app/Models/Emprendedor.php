<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Emprendedor extends Authenticatable
{
    use HasFactory;

    protected $table = 'emprendedores';

    protected $fillable = [
        'nombre_completo',
        'username',
        'password',
        'telefono',
        'marca_o_giro',
        'estado',
    ];

    protected $hidden = [
        'password',
    ];
}