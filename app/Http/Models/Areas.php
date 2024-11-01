<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
    protected $table = 'areas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'nombre',
        'descripcion',
        'estado',
        'cuenta_principal_id',
        'empresa_id',
        'establecimiento_id'
    ];
}
