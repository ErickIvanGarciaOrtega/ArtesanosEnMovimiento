<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Importante
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emprendedor extends Model
{
    use HasFactory;

    protected $table = 'emprendedores';

    protected $fillable = [
        'user_id',
        'foto_perfil',
        'biografia',
        'categoria_principal',
        'facebook',
        'instagram',
        'tiktok',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function marcas()
    {
        return $this->hasMany(Marca::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}