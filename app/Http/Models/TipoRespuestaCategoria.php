<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TipoRespuestaCategoria extends Model
{
    protected $table = 'tipo_respuesta_categoria';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'nombre', 
        'tipo' 
    ];
}
