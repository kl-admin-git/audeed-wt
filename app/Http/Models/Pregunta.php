<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $table = 'pregunta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'nombre', 
        'ponderado',
        'categoria_id',
        'orden_lista',
        'lista_chequeo_id',
        'tipo_respuesta_id',
        'permitir_noaplica'
    ];
}
