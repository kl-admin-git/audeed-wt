<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ListaChequeoEjecutadasFotos extends Model
{
    protected $table = 'lista_chequeo_ejec_fotos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'foto', 
        'lista_chequeo_ejec_respuestas'
    ];
}
