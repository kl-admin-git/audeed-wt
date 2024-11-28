<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\enviarMensajeEvent;
use App\Http\Models\Areas;
use App\Http\Models\Empresa;
use App\Http\Models\Establecimiento;

class AdministracionAreasController extends Controller
{
    protected $areas, $empresa, $establecimiento;
    public function __construct(
        Areas $areas,
        Empresa $empresa,
        Establecimiento $establecimiento
    )
    {
        $this->areas = $areas;
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

        $areasFiltro = $this->areas
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

        

        return view('Admin.administracion_areas', compact('perfilExacto','areasFiltro','empresas','establecimientos'));
    }

    public function CrearArea(Request $request)
    {
        $nombreArea = $request->get('nombreArea');
        $descripcion = $request->get('descripcion');
        $id_empresa = $request->get('id_empresa');
        $id_establecimiento = $request->get('id_establecimiento');

        //VALIDACIÓN SI EXISTE EL NOMBRE - EN ESTE CASO NO EXISTE PORQUE PUEDEN A VR VARIAS CON EL MISMO NOMBRE PERO DIFERENTE ESTABLECIMIENTO

        $newArea = new Areas();
        $newArea->fill([
            'nombre' => $nombreArea,
            'descripcion' => $descripcion,
            'empresa_id' => ($id_empresa == '' ? NULL : $id_empresa),
            'establecimiento_id' => ($id_establecimiento == '' ? NULL : $id_establecimiento),
            'cuenta_principal_id' => auth()->user()->cuenta_principal_id
        ]);

        if($newArea->save())
        {
            $recent = $this->FuncionTraerAreaPorId($newArea->id);
            
            return response()->json([
                'success' => 1,
                'responseCode' => 200,
                'message' => 'Área creada correctamente.',
                'data' => $recent
            ]);
        }
        else
        {
            return response()->json([
                'success' => 1,
                'responseCode' => 400,
                'message' => 'El área no se pudo crear, comunícate con el administrador.',
                'data' => null
            ]);
        }
    }

    public function EditarArea(Request $request)
    {
        $nombreArea = $request->get('nombreArea');
        $descripcion = $request->get('descripcion');
        $idArea = $request->get('id_area');
        $id_empresa = $request->get('id_empresa');
        $id_establecimiento = $request->get('id_establecimiento');
        
        //VALIDACIÓN SI EXISTE EL NOMBRE - EN ESTE CASO NO EXISTE PORQUE PUEDEN A VR VARIAS CON EL MISMO NOMBRE PERO DIFERENTE ESTABLECIMIENTO
        $arrayUpdate = [
            'nombre' => $nombreArea,
            'descripcion' => $descripcion,
            'empresa_id' => ($id_empresa == '' ? NULL : $id_empresa),
            'establecimiento_id' => ($id_establecimiento == '' ? NULL : $id_establecimiento),
        ];

        $updated = $this->areas->where('id','=',$idArea)->update($arrayUpdate);

        if($updated)
        {
            $recent = $this->FuncionTraerAreaPorId($idArea);
            
            return response()->json([
                'success' => 1,
                'responseCode' => 200,
                'message' => 'Área actualizada correctamente.',
                'data' => $recent
            ]);
        }
        else
        {
            return response()->json([
                'success' => 1,
                'responseCode' => 400,
                'message' => 'El área no se pudo actualizar, comunícate con el administrador.',
                'data' => null
            ]);
        }
    }

    public function EliminarArea(Request $request)
    {
        $idArea = $request->get('id_area');

        //VALIDAR TRAZABILIDAD EN ESTABLECIMIENTO Y EMPRESA
        $area = $this->areas->findOrFail($idArea);

        $deleted = $area->delete();

        if($deleted)
        {
            return response()->json([
                'success' => 1,
                'responseCode' => 200,
                'message' => 'Área eliminada correctamente.',
                'data' => null
            ]);
        }
        else
        {
            return response()->json([
                'success' => 1,
                'responseCode' => 400,
                'message' => 'El área no se pudo eliminar, comunícate con el administrador.',
                'data' => null
            ]);
        }
    }

    public function ActualizarEstadoArea(Request $request)
    {
        $idArea = $request->get('id_area');
        $estadoActual = $request->get('estado_actual');

        $arrayUpdate = [
            'estado' => ($estadoActual == 1 ? 0 : 1)
        ];

        $updated = $this->areas->where('id','=',$idArea)->update($arrayUpdate);

        if($updated)
        {
            $recent = $this->FuncionTraerAreaPorId($idArea);
            
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

    public function FuncionTraerAreaPorId($idArea)
    {
        $recent = Areas::select(
            'areas.id as ID_AREA',
            'areas.nombre as NOMBRE_AREA',
            \DB::raw('IF(areas.descripcion IS NULL, "", areas.descripcion) as DESCRIPCION_AREA'),
            'areas.estado',
            \DB::raw('IF(areas.estado = 1, "Activo", "Inactivo") as ESTADO_TEXTO'),
            \DB::raw('IF(areas.empresa_id IS NULL, "", areas.empresa_id) AS ID_EMPRESA'),
            \DB::raw('IF(areas.establecimiento_id IS NULL, "", areas.establecimiento_id) AS ID_ESTABLECIMIENTO'),
            \DB::raw('(CASE
                        WHEN areas.empresa_id IS NOT NULL THEN CONCAT("Empresa ", (SELECT ems.nombre FROM empresa ems WHERE ems.id = areas.empresa_id))
                        WHEN areas.establecimiento_id IS NOT NULL THEN CONCAT("Establecimiento ", (SELECT ests.nombre FROM establecimiento ests WHERE ests.id = areas.establecimiento_id))
                        ELSE "No tiene asignación"
                       END
                      ) AS ASIGNACION'),
            \DB::raw('(SELECT 0) AS CANTIDAD_AREAS_ESTABLECIMIENTOS'),
            \DB::raw('(SELECT 0) AS CANTIDAD_AREAS_EMPRESAS')
        )
        ->where('id', '=', $idArea)
        ->first();

        return $recent;
    }

    public function TraerAreas(Request $request)
    {
        $filtros = json_decode($request->get('filtros'));
        $paginacion = $request->get('paginacion');
        
        $resultado = $this->FuncionTraerAreasPorPaginacion($paginacion,$filtros);
       
        return response()->json([
            'success' => 1,
            'responseCode' => 202,
            'message' => 'Áreas encontradas.',
            'data' => $resultado
        ]);
    }

    public function FuncionTraerAreasPorPaginacion($paginacion=1,$filtros=[])
    {
        $resultadoLimit = $this->CalculoPaginacion($paginacion);

        $desde = $resultadoLimit['desde'];
        $hasta = $resultadoLimit['hasta'];
        $cantidadRegistros = 9;
        $filtro_array = [];
        
        foreach ($filtros as $key => $filtro) 
        {
            switch ($key) {
                case 'filtro_area_id':
                    if($filtro != '')
                        array_push($filtro_array,['areas.id', '=', $filtro]);
                    break;

                default:
                    
                    break;
            }
        }

        $areas = $this->areas
        ->select(
            'areas.id as ID_AREA',
            'areas.nombre as NOMBRE_AREA',
            \DB::raw('IF(areas.descripcion IS NULL, "", areas.descripcion) as DESCRIPCION_AREA'),
            'areas.estado',
            \DB::raw('IF(areas.estado = 1, "Activo", "Inactivo") as ESTADO_TEXTO'),
            \DB::raw('(CASE
                        WHEN areas.empresa_id IS NOT NULL THEN CONCAT("Empresa ", (SELECT ems.nombre FROM empresa ems WHERE ems.id = areas.empresa_id))
                        WHEN areas.establecimiento_id IS NOT NULL THEN CONCAT("Establecimiento ", (SELECT ests.nombre FROM establecimiento ests WHERE ests.id = areas.establecimiento_id))
                        ELSE "No tiene asignación"
                       END
                      ) AS ASIGNACION'), 
            \DB::raw('(SELECT 0) AS CANTIDAD_AREAS_ESTABLECIMIENTOS'),
            \DB::raw('(SELECT 0) AS CANTIDAD_AREAS_EMPRESAS')
        )
        ->where('cuenta_principal_id', '=', auth()->user()->cuenta_principal_id);

        if(COUNT($filtro_array) != 0)
        {
            $areas = $areas->where(function($query) use ($filtro_array)
            {
                foreach ($filtro_array as $keys => $oW) 
                {
                    $query->where($oW[0], '=', $oW[2]);
                }

                return $query;
            });
        }

        $rango = $areas->paginate($cantidadRegistros)->lastPage();
        $areas = $areas->skip($desde)->take($hasta)->get();

        return [
            'areas' => $areas,
            'rango' => $rango
        ];
    }

    public function ConsultarArea(Request $request)
    {
        $idArea = $request->get('id_area');

        return response()->json([
            'success' => 1,
            'responseCode' => 202,
            'message' => 'Data found.',
            'data' => $this->FuncionTraerAreaPorId($idArea)
        ]);

    }

    public function ConsultarDetalle(Request $request)
    {
        $idArea = $request->get('id_area');
        $accion = $request->get('accion');
        $empresas = [];
        $establecimientos = [];
        if($accion == 0) //CONSULTAR EMPRESAS ASOCIADAS AL ÁREA
        {
            $empresas = $this->empresa
            ->select('empresa.nombre AS NOMBRE')
            ->Join('areas AS ar', 'empresa.area_id', '=', 'ar.id')
            ->where('area_id', '=', $idArea)
            ->get();
        }
        else //MOSTRAR ESTABLECIMIENTOS ASOCIADAS AL ÁREA
        {
            $establecimientos = $this->establecimiento
            ->select('establecimiento.nombre AS NOMBRE')
            ->Join('areas AS ar', 'establecimiento.area_id', '=', 'ar.id')
            ->where('area_id', '=', $idArea)
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