<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Establecimiento extends Model
{
    protected $table = 'establecimiento';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'nombre', 
        'codigo',
        'correo',
        'telefono',
        'direccion',
        'ciudad_id',
        'empresa_id',
        'usuario_id',
        'estado',
        'zona_id'
    ];
}
