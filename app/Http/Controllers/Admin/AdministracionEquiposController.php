<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\enviarMensajeEvent;
use App\Http\Models\Equipos;
use App\Http\Models\Empresa;
use App\Http\Models\Establecimiento;

class AdministracionEquiposController extends Controller
{
    protected $equipos, $empresa, $establecimiento;
    public function __construct(
        Equipos $equipos,
        Empresa $empresa,
        Establecimiento $establecimiento
    )
    {
        $this->equipos = $equipos;
        $this->empresa = $empresa;
        $this->establecimiento = $establecimiento;

        $this->middleware('auth');
        $this->middleware('isActive');
    }

    public function Index()
    {
        $perfilUsuarioLogueado = \auth()->user()->perfil_id;
        $perfilExacto = 1;
        $clase="";

        switch($perfilUsuarioLogueado)
        {
            case 1: //ADMINISTRADOR
                $perfilExacto = 1;
            break;
            default:
                $perfilExacto = $perfilUsuarioLogueado;
                break;
        }

        $equiposFiltro = $this->equipos
        ->select('id', 'nombre')
        ->where('cuenta_principal_id', '=', auth()->user()->cuenta_principal_id)
        ->get();

        $empresas = $this->empresa
        ->where([
            ['estado', '=', 1],
            ['cuenta_principal_id', '=', auth()->user()->cuenta_principal_id]
        ])
        ->get();

        $establecimientos = $this->establecimiento
        ->select('establecimiento.*')
        ->Join('empresa AS em', 'establecimiento.empresa_id','=','em.id')
        ->where([
            ['establecimiento.estado', '=', 1],
            ['em.cuenta_principal_id', '=', auth()->user()->cuenta_principal_id]
        ])
        ->get();

        return view('Admin.administracion_equipos', compact('perfilExacto','equiposFiltro', 'empresas', 'establecimientos'));
    }

    public function CrearEquipo(Request $request)
    {
        $nombreEquipo = $request->get('nombreEquipo');
        $descripcion = $request->get('descripcion');
        $id_empresa = $request->get('id_empresa');
        $id_establecimiento = $request->get('id_establecimiento');
        //VALIDACIÓN SI EXISTE EL NOMBRE - EN ESTE CASO NO EXISTE PORQUE PUEDEN A VR VARIAS CON EL MISMO NOMBRE PERO DIFERENTE ESTABLECIMIENTO

        $newEquipo = new Equipos();
        $newEquipo->fill([
            'nombre' => $nombreEquipo,
            'descripcion' => $descripcion,
            'empresa_id' => ($id_empresa == '' ? NULL : $id_empresa),
            'establecimiento_id' => ($id_establecimiento == '' ? NULL : $id_establecimiento),
            'cuenta_principal_id' => auth()->user()->cuenta_principal_id            
        ]);

        if($newEquipo->save())
        {
            $recent = $this->FuncionTraerEquipoPorId($newEquipo->id);
            
            return response()->json([
                'success' => 1,
                'responseCode' => 200,
                'message' => 'Equipo creada correctamente.',
                'data' => $recent
            ]);
        }
        else
        {
            return response()->json([
                'success' => 1,
                'responseCode' => 400,
                'message' => 'El equipo no se pudo crear, comunícate con el administrador.',
                'data' => null
            ]);
        }
    }

    public function EditarEquipo(Request $request)
    {
        $nombreEquipo = $request->get('nombreEquipo');
        $descripcion = $request->get('descripcion');
        $idEquipo = $request->get('id_equipo');
        $id_empresa = $request->get('id_empresa');
        $id_establecimiento = $request->get('id_establecimiento');

        //VALIDACIÓN SI EXISTE EL NOMBRE - EN ESTE CASO NO EXISTE PORQUE PUEDEN A VR VARIAS CON EL MISMO NOMBRE PERO DIFERENTE ESTABLECIMIENTO
        $arrayUpdate = [
            'nombre' => $nombreEquipo,
            'descripcion' => $descripcion,
            'empresa_id' => ($id_empresa == '' ? NULL : $id_empresa),
            'establecimiento_id' => ($id_establecimiento == '' ? NULL : $id_establecimiento),
        ];

        $updated = $this->equipos->where('id','=',$idEquipo)->update($arrayUpdate);

        if($updated)
        {
            $recent = $this->FuncionTraerEquipoPorId($idEquipo);
            
            return response()->json([
                'success' => 1,
                'responseCode' => 200,
                'message' => 'Equipo actualizado correctamente.',
                'data' => $recent
            ]);
        }
        else
        {
            return response()->json([
                'success' => 1,
                'responseCode' => 400,
                'message' => 'El equipo no se pudo actualizar, comunícate con el administrador.',
                'data' => null
            ]);
        }
    }

    public function EliminarEquipo(Request $request)
    {
        $idEquipo = $request->get('id_equipo');

        //VALIDAR TRAZABILIDAD EN ESTABLECIMIENTO Y EMPRESA
        $equipo = $this->equipos->findOrFail($idEquipo);

        $deleted = $equipo->delete();

        if($deleted)
        {
            return response()->json([
                'success' => 1,
                'responseCode' => 200,
                'message' => 'Equipo eliminado correctamente.',
                'data' => null
            ]);
        }
        else
        {
            return response()->json([
                'success' => 1,
                'responseCode' => 400,
                'message' => 'El equipo no se pudo eliminar, comunícate con el administrador.',
                'data' => null
            ]);
        }
    }

    public function ActualizarEstadoEquipo(Request $request)
    {
        $idEquipo = $request->get('id_equipo');
        $estadoActual = $request->get('estado_actual');

        $arrayUpdate = [
            'estado' => ($estadoActual == 1 ? 0 : 1)
        ];

        $updated = $this->equipos->where('id','=',$idEquipo)->update($arrayUpdate);

        if($updated)
        {
            $recent = $this->FuncionTraerEquipoPorId($idEquipo);
            
            return response()->json([
                'success' => 1,
                'responseCode' => 200,
                'message' => 'Estado actualizado correctamente.',
                'data' => $recent
            ]);
        }
        else
        {
            return response()->json([
                'success' => 1,
                'responseCode' => 400,
                'message' => 'El estado no se pudo actualizar, comunícate con el administrador.',
                'data' => null
            ]);
        }
    }

    public function FuncionTraerEquipoPorId($idEquipo)
    {
        $recent = Equipos::select(
            'equipos.id as ID_EQUIPO',
            'equipos.nombre as NOMBRE_EQUIPO',
            \DB::raw('IF(equipos.descripcion IS NULL, "", equipos.descripcion) as DESCRIPCION_EQUIPO'),
            'equipos.estado',
            \DB::raw('IF(equipos.empresa_id IS NULL, "", equipos.empresa_id) AS ID_EMPRESA'),
            \DB::raw('IF(equipos.establecimiento_id IS NULL, "", equipos.establecimiento_id) AS ID_ESTABLECIMIENTO'),
            \DB::raw('IF(equipos.estado = 1, "Activo", "Inactivo") as ESTADO_TEXTO'),
            \DB::raw('(CASE
                        WHEN equipos.empresa_id IS NOT NULL THEN CONCAT("Empresa ", (SELECT ems.nombre FROM empresa ems WHERE ems.id = equipos.empresa_id))
                        WHEN equipos.establecimiento_id IS NOT NULL THEN CONCAT("Establecimiento ", (SELECT ests.nombre FROM establecimiento ests WHERE ests.id = equipos.establecimiento_id))
                        ELSE "No tiene asignación"
                       END
                      ) AS ASIGNACION'),
            \DB::raw('(SELECT 0) AS CANTIDAD_EQUIPOS_ESTABLECIMIENTOS'),
            \DB::raw('(SELECT 0) AS CANTIDAD_EQUIPOS_EMPRESAS')
        )
        ->where('id', '=', $idEquipo)
        ->first();

        return $recent;
    }

    public function TraerEquipos(Request $request)
    {
        $filtros = json_decode($request->get('filtros'));
        $paginacion = $request->get('paginacion');
        
        $resultado = $this->FuncionTraerEquiposPorPaginacion($paginacion,$filtros);
       
        return response()->json([
            'success' => 1,
            'responseCode' => 202,
            'message' => 'Equipos encontrados.',
            'data' => $resultado
        ]);
    }

    public function FuncionTraerEquiposPorPaginacion($paginacion=1,$filtros=[])
    {
        $resultadoLimit = $this->CalculoPaginacion($paginacion);

        $desde = $resultadoLimit['desde'];
        $hasta = $resultadoLimit['hasta'];
        $cantidadRegistros = 9;
        $filtro_array = [];
        
        foreach ($filtros as $key => $filtro) 
        {
            switch ($key) {
                case 'filtro_equipo_id':
                    if($filtro != '')
                        array_push($filtro_array,['equipos.id', '=', $filtro]);
                    break;

                default:
                    
                    break;
            }
        }

        $equipos = $this->equipos
        ->select(
            'equipos.id as ID_EQUIPO',
            'equipos.nombre as NOMBRE_EQUIPO',
            \DB::raw('IF(equipos.descripcion IS NULL, "", equipos.descripcion) as DESCRIPCION_EQUIPO'),
            'equipos.estado',
            \DB::raw('IF(equipos.estado = 1, "Activo", "Inactivo") as ESTADO_TEXTO'),
            \DB::raw('(CASE
                        WHEN equipos.empresa_id IS NOT NULL THEN CONCAT("Empresa ", (SELECT ems.nombre FROM empresa ems WHERE ems.id = equipos.empresa_id))
                        WHEN equipos.establecimiento_id IS NOT NULL THEN CONCAT("Establecimiento ", (SELECT ests.nombre FROM establecimiento ests WHERE ests.id = equipos.establecimiento_id))
                        ELSE "No tiene asignación"
                       END
                      ) AS ASIGNACION'),
            \DB::raw('(SELECT 0) AS CANTIDAD_EQUIPOS_ESTABLECIMIENTOS'),
            \DB::raw('(SELECT 0) AS CANTIDAD_EQUIPOS_EMPRESAS')
        )
        ->where('cuenta_principal_id', '=', auth()->user()->cuenta_principal_id);

        if(COUNT($filtro_array) != 0)
        {
            $equipos = $equipos->where(function($query) use ($filtro_array)
            {
                foreach ($filtro_array as $keys => $oW) 
                {
                    $query->where($oW[0], '=', $oW[2]);
                }

                return $query;
            });
        }

        $rango = $equipos->paginate($cantidadRegistros)->lastPage();
        $equipos = $equipos->skip($desde)->take($hasta)->get();

        return [
            'equipos' => $equipos,
            'rango' => $rango
        ];
    }

    public function ConsultarEquipo(Request $request)
    {
        $idEquipo = $request->get('id_equipo');

        return response()->json([
            'success' => 1,
            'responseCode' => 202,
            'message' => 'Data found.',
            'data' => $this->FuncionTraerEquipoPorId($idEquipo)
        ]);

    }

    public function ConsultarDetalle(Request $request)
    {
        $idEquipo = $request->get('id_equipo');
        $accion = $request->get('accion');
        $empresas = [];
        $establecimientos = [];
        if($accion == 0) //CONSULTAR EMPRESAS ASOCIADAS AL ÁREA
        {
            $empresas = $this->empresa
            ->select('empresa.nombre AS NOMBRE')
            ->Join('equipos AS eq', 'empresa.equipo_id', '=', 'eq.id')
            ->where('equipo_id', '=', $idEquipo)
            ->get();
        }
        else //MOSTRAR ESTABLECIMIENTOS ASOCIADAS AL ÁREA
        {
            $establecimientos = $this->establecimiento
            ->select('establecimiento.nombre AS NOMBRE')
            ->Join('equipos AS eq', 'establecimiento.equipo_id', '=', 'eq.id')
            ->where('equipo_id', '=', $idEquipo)
            ->get();
        }

        return response()->json([
            'success' => 1,
            'responseCode' => 202,
            'message' => 'Detalle encontrado',
            'data' => [
                'empresas' => $empresas,
                'establecimientos' => $establecimientos
            ]
        ]);
    }
}