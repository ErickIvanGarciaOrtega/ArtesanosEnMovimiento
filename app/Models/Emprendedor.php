<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Importante
use Illuminate\Notifications\Notifiable;

class Emprendedor extends Authenticatable
{
    use Notifiable;

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
        'remember_token',
    ];
}