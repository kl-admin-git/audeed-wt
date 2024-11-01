<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TipoRespuesta extends Model
{
    protected $table = 'tipo_respuesta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'tipo_respuesta_categoria', 
        'nombre',
        'icono',
        'estado',
        'descripcion'
    ];
}
