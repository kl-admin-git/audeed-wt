<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionEjecucion extends Model
{
    protected $table = 'lista_chequeo_configuracion_ejecucion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'link', 
        'frecuencia_ejecucion',
        'cant_ejecucion',
        'lista_chequeo_id',
    ];
}
