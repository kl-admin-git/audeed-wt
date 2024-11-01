<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\enviarMensajeEvent;
use App\Http\Models\Cargo;
use App\Http\Models\Usuario;

class AdministracionCargosController extends Controller
{
    protected $cargos,$usuario;
    public function __construct(Cargo $cargos, Usuario $usuario)
    {
        $this->cargos = $cargos;
        $this->usuario = $usuario;

        $this->middleware('auth');
        $this->middleware('isActive');
    }

    public function Index()
    {
        $cargos = $this->cargos
        ->where('cargo.cuenta_principal_id','=',auth()->user()->cuenta_principal_id)->get();
        
        return view('Admin.administracion_cargos',compact('cargos'));
    }

    public function TraerCargos(Request $request)
    {
        $paginacion = $request->get('paginacion');
        $arrayFiltros = json_decode($request->get('arrayFiltros'));

        $cargos = $this->FuncionTraerCargosAdministrador($paginacion,$arrayFiltros);

        return $this->FinalizarRetorno(
            202,
            $this->MensajeRetorno('Datos',202),
            $cargos
        );
    }

    public function CrearCargo(Request $request)
    {
        $nombreCargo = $request->get('nombreCargo');
        
        if ($this->cargos->where([['cargo.nombre','=', $nombreCargo],['cargo.cuenta_principal_id','=',auth()->user()->cuenta_principal_id]])->exists())
        {
            return $this->FinalizarRetorno(
                406,
                $this->MensajeRetorno('',406,'El nombre de cargo ya existe')
            );
        }

        $arrayInsertar = [
            'nombre' => $nombreCargo,
            'cuenta_principal_id' => auth()->user()->cuenta_principal_id
        ];

        $cargos = new $this->cargos;
        $cargos->fill($arrayInsertar);

        if($cargos->save())
        {
            return $this->FinalizarRetorno(
                200,
                $this->MensajeRetorno('El cargo',200)
            );
        }
    }

    public function EditarCargo(Request $request)
    {
        $nombreCargo = $request->get('nombreCargo');
        $idCargo = $request->get('idCargo');
        
        if ($this->cargos->where([
            ['cargo.nombre','=', $nombreCargo],
            ['cargo.id','!=', $idCargo],
            ['cargo.cuenta_principal_id','=',auth()->user()->cuenta_principal_id]
        ])->exists()) 
        {
            return $this->FinalizarRetorno(
                406,
                $this->MensajeRetorno('',406,'El nombre de cargo ya existe')
            );
        }

        $arrayActualizar = [
            'nombre' => $nombreCargo
        ];

        $respuestaUpdate = $this->cargos->where('id','=',$idCargo)->update($arrayActualizar);

        $cargos = $this->cargos
            ->where('cargo.cuenta_principal_id','=',auth()->user()->cuenta_principal_id)->get();

        return $this->FinalizarRetorno(
            201,
            $this->MensajeRetorno('El cargo',201),
            array('nombreCargo' => $nombreCargo, 'cargos' => $cargos)
        );
    }

    public function CambiarEstadoCargo(Request $request)
    {
        $idCargo = $request->get('idCargo');
        $estadoActual = $request->get('estadoActual');

        $estadoCambiado = 0;
        if($estadoActual == 0)
            $estadoCambiado = 1;
        else if($estadoActual == 1)
            $estadoCambiado = 0;

        if ($this->usuario->where([
            ['usuario.cargo_id','=', $idCargo]
        ])->exists()) 
        {
            return $this->FinalizarRetorno(
                406,
                $this->MensajeRetorno('',406,'El cargo se encuentra asignado a un usuario')
            );
        }
        
        $respuestaUpdate = $this->cargos->where('id','=',$idCargo)
        ->update(
        [
            'estado' => $estadoCambiado
        ]);

        if($respuestaUpdate)
        {
            return $this->FinalizarRetorno(
                201,
                $this->MensajeRetorno('El cargo ',201),
                $estadoCambiado
            );
        }
    }

    public function EliminarCargo(Request $request)
    {
        $idCargo = $request->get('idCargo');

        if ($this->usuario->where([
            ['usuario.cargo_id','=', $idCargo]
        ])->exists()) 
        {
            return $this->FinalizarRetorno(
                406,
                $this->MensajeRetorno('',406,'El cargo se encuentra asignado a un usuario')
            );
        }

        $respuesta = $this->cargos->where('id', $idCargo)->delete();

        if($respuesta)
        {
            $cargos = $this->cargos
            ->where('cargo.cuenta_principal_id','=',auth()->user()->cuenta_principal_id)->get();

            return $this->FinalizarRetorno(
                203,
                $this->MensajeRetorno('El cargo ',203),
                $cargos
            );  
        }
        else
        {
            return $this->FinalizarRetorno(
                406,
                $this->MensajeRetorno('',406,'El cargo no pudo eliminarse')
            ); 
        }
        
    }

    public function FuncionTraerCargosAdministrador($paginacion=1,$filtros=[])
    {
        $resultadoLimit = $this->CalculoPaginacion($paginacion);
    
        $desde = $resultadoLimit['desde'];
        $hasta = $resultadoLimit['hasta'];
        $cantidadRegistros = 9;
        $filtro_array = [];

        foreach ($filtros as $key => $filtro) 
        {

            switch ($key) {
                case 'filtro_cargo':
                    if($filtro != '')
                        array_push($filtro_array,['cargo.id', '=', $filtro]);
                    break;

                default:
                    
                    break;
            }
            
        }

        $cargos = $this->cargos
        ->select(
            'cargo.*',
            \DB::raw('IF(cargo.estado = 0,"Inactivo","Activo") AS NOMBRE_ESTADO')
        )
        ->where('cargo.cuenta_principal_id','=',auth()->user()->cuenta_principal_id);
        
        if(COUNT($filtro_array) != 0)
        {
            $cargos = $cargos->where(function($query) use ($filtro_array)
            {
                foreach ($filtro_array as $keys => $oW) 
                {
                    $query->where($oW[0], '=', $oW[2]);
                }

                return $query;
            });
            
        }

        $rango = $cargos->paginate($cantidadRegistros)->lastPage();
        $cargos = $cargos->skip($desde)->take($hasta)->get();

        return array(
            'cantidadTotal' => $rango,
            'cargos' => $cargos
        );
    }
}
