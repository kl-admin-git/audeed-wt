<?php

namespace App\Exports;

use App\Http\Models\Usuario;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
class InformeDetalleExports implements  FromView,ShouldAutoSize
{
    use Exportable;

    protected $filtros;
    public function __construct($filtros)
    {
        $this->filtros = $filtros;
    }


    public function view(): View
    {
        $filtro_array = [];

        foreach ($this->filtros as $key => $filtro)
        {
            switch ($key)
            {
                case 'filtro_lista_chequeo':
                    if($filtro != '')
                        array_push($filtro_array,['lc.id', '=', $filtro]);
                    break;

                case 'filtro_inicio':
                    if($filtro != '')
                        array_push($filtro_array,['lce.created_at', '=', Carbon::createFromFormat('d/m/Y', $filtro)->format('Y-m-d'), Carbon::createFromFormat('d/m/Y', $filtros->filtro_fin)->format('Y-m-d')]);
                    break;

                case 'filtro_estado':
                    if($filtro != '')
                        array_push($filtro_array,['lce.estado', '=', $filtro]);
                    break;

                default:
                    break;
            }
        }

        $data = \DB::table('lista_chequeo_ejecutadas as lce')->select([
                'lce.id as ID_AUDITORIA',
                'lc.nombre as AUDITORIA',
                \DB::raw("DATE_FORMAT(lce.created_at, '%d %M %Y %h:%m:%s %p') as FECHA_INICIO"),
                \DB::raw("DATE_FORMAT(lce.finished_at, '%d %M %Y %h:%m:%s %p') as FECHA_FINAL"),
                \DB::raw("TIMESTAMPDIFF(MINUTE, lce.created_at, lce.finished_at) as TIEMPO_TOTAL"),
                \DB::raw("IF(lce.direccion IS NULL, '', lce.direccion) as DIRECCION"),
                'lce.latitud',
                'lce.longitud',
                \DB::raw("(CASE
                            WHEN lce.estado = 0 THEN 'Cancelada'
                            WHEN lce.estado = 1 THEN 'Proceso'
                            WHEN lce.estado = 2 THEN 'Terminada'
                          END) as ESTADO"),
                \DB::raw("(CASE
                            WHEN lc.entidad_evaluada = 1 THEN 'Empresa'
                            WHEN lc.entidad_evaluada = 2 THEN 'Establecimiento'
                            WHEN lc.entidad_evaluada = 3 THEN 'Usuario'
                            WHEN lc.entidad_evaluada = 4 THEN 'Areas'
                            WHEN lc.entidad_evaluada = 5 THEN 'Equipos'
                            ELSE 'Error'
                          END) as ENTIDAD_EVALUADA"),
                \DB::raw("(CASE
                            WHEN lc.entidad_evaluada = 1 THEN (SELECT semp.nombre FROM empresa semp WHERE semp.id = lce.evaluado_id)
                            WHEN lc.entidad_evaluada = 2 THEN (SELECT sest.nombre FROM establecimiento sest WHERE sest.id = lce.evaluado_id)
                            WHEN lc.entidad_evaluada = 3 THEN (SELECT susu.nombre_completo FROM usuario susu WHERE susu.id = lce.evaluado_id)
                            WHEN lc.entidad_evaluada = 4 THEN (SELECT ars.nombre FROM areas ars WHERE ars.id = lce.evaluado_id)
                            WHEN lc.entidad_evaluada = 5 THEN (SELECT eqs.nombre FROM equipos eqs WHERE eqs.id = lce.evaluado_id)
                            ELSE 'Error'
                          END) as EVALUADO"),
                'usu.nombre_completo as EVALUADOR',
                \DB::raw("(SELECT SUM(IF(
                              (TRUNCATE(((pre.ponderado * res.ponderado) / 100), 2)) IS NULL,
                              pre.ponderado,
                              (TRUNCATE(((pre.ponderado * res.ponderado) / 100), 2))
                            )) as res_final
                          FROM lista_chequeo_ejec_respuestas lcer
                          INNER JOIN lista_chequeo_ejecutadas lces ON lces.id = lcer.lista_chequeo_ejec_id
                          LEFT JOIN respuesta res ON res.id = lcer.respuesta_id
                          INNER JOIN pregunta pre ON pre.id = lcer.pregunta_id
                          INNER JOIN categoria cat ON cat.id = pre.categoria_id
                          WHERE lcer.lista_chequeo_ejec_id = lce.id
                          ORDER BY cat.id) as RESULTADO_FINAL")
            ])
            ->join('lista_chequeo as lc', 'lce.lista_chequeo_id', '=', 'lc.id')
            ->join('usuario as us', 'lc.usuario_id', '=', 'us.id')
            ->join('usuario as usu', 'lce.usuario_id', '=', 'usu.id')
            ->where('lce.estado', 2);

        switch (auth()->user()->perfil_id)
        {
            case 1: // ADMINISTRADOR
                $data = $data->where('us.cuenta_principal_id','=',auth()->user()->cuenta_principal_id);
                break;

            case 2: // COLABORADOR
                //VERIFICAR SI ES RESPONSABLE DE EMPRESA
                $esResponsableEmpresa = $this->FuncionParaSaberSiEsResponsableEmpresa(auth()->user()->id);

                if(!is_null($esResponsableEmpresa))
                    $data = $data->where('emp.id','=',$esResponsableEmpresa->id);

                //VERIFICAR SI ES RESPONSABLE DE ESTABLECIMIENTO
                $esResponsableEstablecimiento = $this->FuncionParaSaberSiEsResponsableEstablecimiento(auth()->user()->id);

                if(!is_null($esResponsableEstablecimiento))
                    $data = $data->where('est.id','=',$esResponsableEstablecimiento->id);

                if(is_null($esResponsableEmpresa) && is_null($esResponsableEstablecimiento))
                    $data = $data->where('lce.usuario_id','=',auth()->user()->id);

                break;

            default:

                break;
        };

        if(COUNT($filtro_array) != 0)
        {
            $data = $data->where(function($query) use ($filtro_array)
            {
                // $contador = 0;
                foreach ($filtro_array as $keys => $oW)
                {
                    if($oW[3] == NULL)
                        $query->where($oW[0], '=', $oW[2]);
                    else
                        $query->whereBetween($oW[0], [$oW[2],$oW[3]]);
                }

                return $query;
            });
        }
        else
            $data = $data->where([['lc.favorita','=', 1],['lce.estado', '=', 2]]);

        $data = $data->get();

        //AGREGAR ENCABEZADOS NUEVOS DE CADA PREGUNTA
        foreach($data as $key_data => $value_data)
        {
            $id_check_list = $value_data->ID_AUDITORIA;
            $sub_data = \DB::select(
                \DB::raw("SELECT
                pr.nombre AS PREGUNTA,
                IF(lcer.respuesta_abierta IS NULL, re.valor_personalizado, lcer.respuesta_abierta) AS RESPUESTA_COLOCADA
                FROM lista_chequeo_ejec_respuestas lcer
                INNER JOIN pregunta pr ON lcer.pregunta_id = pr.id
                INNER JOIN respuesta re ON lcer.respuesta_id = re.id
                INNER JOIN lista_chequeo_ejecutadas lces ON lcer.lista_chequeo_ejec_id = lces.id
                WHERE lces.id = $id_check_list;"));

            $quantity = 1;
            foreach($sub_data as $key_sub_data => $value_sub_data)
            {
                $property_question = "PREGUNTA_$quantity";
                $property_answer = "RESPUESTA_$quantity";
                $data[$key_data]->$property_question = $value_sub_data->PREGUNTA;
                $data[$key_data]->$property_answer = $value_sub_data->RESPUESTA_COLOCADA;

                $quantity++;
            }
        }

        return view('exports.informe_detalle', [
            'data' => $data
        ]);
    }



    public function FuncionParaSaberSiEsResponsableEmpresa($idUsuario)
    {
        $esResponsableEmpresa = \DB::table('empresa')->where('usuario_id','=',$idUsuario)->first();

        return $esResponsableEmpresa;
    }

    public function FuncionParaSaberSiEsResponsableEstablecimiento($idUsuario)
    {
        $esResponsableEstablecimiento = \DB::table('establecimiento')->where('usuario_id','=',$idUsuario)->first();

        return $esResponsableEstablecimiento;
    }
}
