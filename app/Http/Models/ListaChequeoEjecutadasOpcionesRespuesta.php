<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ListaChequeoEjecutadasOpcionesRespuesta extends Model
{
    protected $table = 'lista_chequeo_ejec_opciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'lista_chequeo_ejec_respuestas_id', 
        'comentario',
        'adjunto',
        'plan_accion_id'
    ];
}
