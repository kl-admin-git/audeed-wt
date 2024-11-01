<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class PlanDeAccionAutomatico extends Model
{
    protected $table = 'plan_accion_automatico';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'plan_accion_id',
        'plan_accion_descripcion'
    ];
}
