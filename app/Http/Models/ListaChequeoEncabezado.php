<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ListaChequeoEncabezado extends Model
{
    protected $table = 'lista_chequeo_encabezado';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'fecha', 
        'entidad_evaluada_opcion',
        'lista_chequeo_id' 
    ];
}
