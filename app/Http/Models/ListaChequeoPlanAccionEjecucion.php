<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ListaChequeoPlanAccionEjecucion extends Model
{
    protected $table = 'lista_chequeo_ejec_planaccion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'lista_chequeo_ejec_opciones', 
        'accion_correctiva_id'
    ];
}
