<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Equipos extends Model
{
    protected $table = 'equipos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'nombre',
        'descripcion',
        'estado',
        'cuenta_principal_id',
        'empresa_id',
        'establecimiento_id'
    ];
}
