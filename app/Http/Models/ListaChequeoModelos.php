<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ListaChequeoModelos extends Model
{
    protected $table = 'modelo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'nombre', 
        'descripcion',
        'imagen',
        'lista_chequeo_id',
        'sector_id',
        'estado'
    ];

    public function verificacionSector()
    {
        $cuentaUsuario = auth()->user()->cuenta_principal_id;

        $sector = \DB::table('cuenta_principal')->select('sector_id')->where('id','=',$cuentaUsuario)->first();
        
        return $sector->sector_id;
    }

    public function administradorPlataforma()
    {
        $isAdmin = \DB::table('cuenta_principal')->select('admin')->where('id','=',auth()->user()->cuenta_principal_id)->first();
        
        if ($isAdmin->admin === 1) {
           return true;
        }else{
            return false;
        }
    }
}
