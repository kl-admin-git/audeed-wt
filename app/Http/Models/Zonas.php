<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Zonas extends Model
{
    protected $table = 'zonas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 
        'descripcion',
        'estado',
        'cuenta_principal_id'
    ];
}
