<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class PreguntaOpcionRespuesta extends Model
{
    protected $table = 'pregunta_preguntarespuestaopcion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'pregunta_id', 
        'pregunta_respuesta_opcion' 
    ];
}
