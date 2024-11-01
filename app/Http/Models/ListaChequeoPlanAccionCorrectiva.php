<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ListaChequeoPlanAccionCorrectiva extends Model
{
    protected $table = 'accion_correctiva';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'titulo', 
        'descripcion',
        'color',
        'cuenta_principal_id' 
    ];
}
