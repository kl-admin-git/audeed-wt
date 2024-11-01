<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class PreguntaRespuestaOpciones extends Model
{
    protected $table = 'pregunta_respuesta_opcion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'nombre', 
        'icono',
        'estado'
    ];
}
