<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Zonas;

class AdministracionZonasController extends Controller
{

    protected $zonas;
    public function __construct(Zonas $zonas)
    {
        $this->zonas = $zonas;
        $this->middleware('auth');
        $this->middleware('isActive');
    }

    public function index(){
        $perfilUsuarioLogueado = \auth()->user()->perfil_id;
        $perfilExacto = 1;
        $clase="";

        switch($perfilUsuarioLogueado){
            case 1: //ADMINISTRADOR
                $perfilExacto = 1;
            break;

            // case 2: // COLABORADOR
            //     //VERIFICAR SI ES RESPONSABLE DE EMPRESA
            //     $esResponsableEmpresa = \DB::table('empresa')->where('usuario_id','=',auth()->user()->id)->first();
                
            //     if(!is_null($esResponsableEmpresa)){ $perfilExacto = 2; }      
    
            //     // //VERIFICAR SI ES RESPONSABLE DE ESTABLECIMIENTO
            //     $esResponsableEstablecimiento = \DB::table('establecimiento')->where('usuario_id','=',auth()->user()->id)->first();
            //     if(!is_null($esResponsableEstablecimiento))
            //     {
            //         $perfilExacto = 3;
            //         $clase = 'hidden';
            //     }
                    
            //     if(is_null($esResponsableEmpresa) && is_null($esResponsableEstablecimiento))
            //     {
            //         $perfilExacto = 4;
            //         $clase = 'hidden';
            //     }
                    
    
            //     break;
            
            default:
                $perfilExacto = $perfilUsuarioLogueado;
                break;
        }
        return view('Admin.administracion_zonas', compact('perfilExacto', 'clase'));
    }

    //Envia los datos para cargarlos al select de los filtros
    public function consultarZonasFiltro(){
        $res = $this->zonas->select('id', 'nombre')->where('estado', '=', '1')
        ->where('cuenta_principal_id', '=', $this->cuentaPrincipal())->get();
        return response()->json([
            "datos" => $res
        ]);
    }

    public function crearZona(Request $request){
     
       $data = [
           'nombre' => $request->get('nombreZona'),
           'descripcion' => $request->get('descripcion'),
           'cuenta_principal_id' => $this->cuentaPrincipal()

       ];
       
       $this->zonas->fill($data);

       if($this->zonas->save()){
            return response()->json([
            'msg'=>'Se creo la zona correctamente.', 
            'status'=>200]);
       }else{
        return response()->json([
            'msg'=>'Ha ocurrido un error al crear la zona', 
            'status'=>201]);

       }


    }

    public function consultarZonas(Request $request){
        $filtros = json_decode( $request->arrayFiltros);
    
        $filtro_array = [];
        $resul = 0;
        foreach($filtros as $key => $value){
            switch($key){
                case 'filtro_zona_id':
                    if($value != '' or $value != null)
                        array_push($filtro_array,['zonas.id', '=', $value]);
                break;

                default:
                $resul = $this->zonas->paginate();
            break;
            }
           
        }

        $zonas = $this->zonas->select(
            'zonas.id as id',
            'zonas.nombre as nombre',
            'zonas.descripcion as descripcion',
            'zonas.estado as estado',
            \DB::raw('count(est.nombre) as establecimientos_cantidad')
        )->leftJoin('establecimiento as est', 'est.zona_id', 'zonas.id')
        ->where('cuenta_principal_id', '=', $this->cuentaPrincipal())->groupBy('zonas.id');

        if(COUNT($filtro_array) != 0)
        {
            $zonas = $zonas->where(function($query) use ($filtro_array)
            {
                foreach ($filtro_array as $keys => $oW) 
                {
                    $query->where($oW[0], '=', $oW[2]);
                }

                return $query;
            });
            
        }
       
        return response()->json([
            'datos' => $zonas->get(),
            'status' => 202
        ]);
    }
    
    //Resultado para la tabla del modal
    public function consultarEstablecimientoZona(Request $request){
        $idZona = $request->get('idZona');


        $zonas = $this->zonas->select(
            'emp.nombre as empresa',
            'est.nombre as establecimiento'
        )->join('establecimiento as est', 'est.zona_id', 'zonas.id')
        ->leftJoin('empresa as emp', 'est.empresa_id', 'emp.id')
        ->where('zonas.id', '=', $idZona)
        ->orderBy('emp.id', 'ASC')->paginate(5);



        return response()->json([
            'datos'=> $zonas, 
            'status'=>200
            ]);
    }

    public function actualizarEstadoZona(Request $request){
        $idZona = $request->get('idZona');
        $estadoActual = $request->get('estadoActual');
  

        $estadoCambiado = 0;
        if($estadoActual == 0)
            $estadoCambiado = 1;
        else if($estadoActual == 1)
            $estadoCambiado = 0;
        
        //$respuestaUpdate = $this->zona->where('id','=',$idZona)
        $respuestaUpdate =  $this->zonas->findOrFail($idZona)
        ->update(
        [
            'estado' => $estadoCambiado
        ]);

        if($respuestaUpdate)
        {
            return response()->json([
                'datos' => 'Estado actualizado.',
                'status' => 201,
                'estadoControl' => $estadoCambiado
            ]);
        }
    }

    private function cuentaPrincipal(){
        $cuentaPrincipal =  auth()->user()->cuenta_principal_id;
        return $cuentaPrincipal;
    }

    public function editarZona(Request $request){
        $idZona = $request->idZona;
        $nombre = $request->nombreZona;
        $descripcion = $request->descripcionZona;
        
        $respuestaUpdate = $this->zonas->findOrFail($idZona)->update([
            'nombre' => $nombre,
            'descripcion' => $descripcion
        ]);

        if($respuestaUpdate){
            return response()->json([
                'msg' => 'Se ha actualizado la zona.',
                'status' => 201
            ]);
        }else{
            return response()->json([
                'msg' => 'Error al actualizar la zona.',
                'status' => 406
            ]);
        }
    }

    public function eliminarZonas(Request $request){
        try{
            $idZona = $request->get('idZona');
            $zona =  $this->zonas->findOrFail($idZona);

            $cantidadEstablecimientos = $this->zonas->select(
                \DB::raw('count(est.nombre) as cantidad')
            )->join('establecimiento as est', 'est.zona_id', 'zonas.id')
            ->where('zonas.id', '=', $idZona)->get();
            $msg = '';
            $status = '';
            if($cantidadEstablecimientos[0]->cantidad == 0){
                $msg = 'Se ha eliminado la zona.';
                $status = 201;
                $zona->delete();
            }else{
                $msg = 'No se puede eliminar la zona debido a que tiene establecimientos relacionados.';
                $status = 406;
            }
            
            return response()->json([
                'msg' => $msg,
                'status' => $status
            ]);
           
            
        }catch(\Exception $ex){
            return response()->json([
                'msg' => $ex,
                'status' => 406
            ]);
        }
       
    }



}