<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Http\Models\Usuario;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;

use Carbon\Carbon;

class PlanAccionHallazgosExports implements FromView,ShouldAutoSize
{
    use Exportable;
    protected $filtros,$idUsuario;
    public function __construct($filtros,$idUsuario)
    {   
        $this->filtros = $filtros;
        $this->idUsuario = $idUsuario;
    }
    public function view(): View
    {
        $filtro_array = [];
        $whereRaw = 0;
        $whereRawEvaluado = 0;
        foreach ($this->filtros as $key => $filtro) 
        {

            switch ($key) {
                case 'filtro_realizacion':
                    if($filtro != '')
                    {
                        $dato = Carbon::createFromFormat('d/m/Y', $filtro);
                        $dato = $dato->format('Y-m-d');
                        array_push($filtro_array,['lce.fecha_realizacion', '=', $dato]);
                    }
                    break;

                case 'filtro_lista_chequeo':
                    if($filtro != '')
                        array_push($filtro_array,['lc.id', '=', $filtro]);
                    break;

                case 'filtro_evaluado':
                    if($filtro != '')
                        // array_push($filtro_array,['lc.entidad_evaluada', '=', $filtro]);
                        $whereRawEvaluado = $filtro;
                    break;

                case 'filtro_evaluador':
                    if($filtro != '')
                        array_push($filtro_array,['us.id', '=', $filtro]);
                    break;

                case 'filtro_codigo':
                    if($filtro != '')
                        array_push($filtro_array,['lcep.id', '=', $filtro]);
                    break;

                case 'filtro_empresa':
                    if($filtro != '')
                        $whereRaw = $filtro;
                    break;

                default:
                    
                    break;
            }
            
        }

        $traerPlanAcciones = \DB::table('plan_accion_manu_det')
        ->select(
            'lcep.id AS CODIGO_PLAN_ACCION',
            \DB::raw('IF((SELECT pass.estado FROM plan_accion_seguimiento pass WHERE pass.plan_accion_id = pa.id ORDER BY pass.id DESC LIMIT 1) IS NULL, "Abierto",
            (CASE
                WHEN (SELECT pass.estado FROM plan_accion_seguimiento pass WHERE pass.plan_accion_id = pa.id ORDER BY pass.id DESC LIMIT 1)=1 THEN "Abierto"
                WHEN (SELECT pass.estado FROM plan_accion_seguimiento pass WHERE pass.plan_accion_id = pa.id ORDER BY pass.id DESC LIMIT 1)=2 THEN "En proceso"
                WHEN (SELECT pass.estado FROM plan_accion_seguimiento pass WHERE pass.plan_accion_id = pa.id ORDER BY pass.id DESC LIMIT 1)=3 THEN "Cerrado"
                ELSE "Error"
            END)) AS ESTADO'),
            'lceo.id AS ID_EJECT_OPCIONES',
            \DB::raw('DATE_FORMAT(lce.fecha_realizacion,"%d de %M %Y %h %i %p") AS FECHA_REALIZACION'),
            'pa.id AS ID_PLAN_ACCION',
            \DB::raw('IF(pa.tipo_pa=1,"Automático","Manual") AS TIPO_PLAN_ACCION'),
            'lc.nombre',
            \DB::raw('(CASE
                        WHEN lc.entidad_evaluada=1 THEN (SELECT semp.nombre FROM empresa semp WHERE semp.id=lce.evaluado_id)
                        WHEN lc.entidad_evaluada=2 THEN (SELECT semp.nombre FROM establecimiento sest 
                                                        INNER JOIN empresa semp ON semp.id = sest.empresa_id WHERE sest.id=lce.evaluado_id)
                        WHEN lc.entidad_evaluada=3 THEN (SELECT semp.nombre FROM usuario susu
                                                        INNER JOIN establecimiento sesta ON sesta.id = susu.establecimiento_id
                                                        INNER JOIN empresa semp ON semp.id = sesta.empresa_id
                                                        WHERE susu.id=lce.evaluado_id)
                        WHEN lc.entidad_evaluada=4 THEN (SELECT ars.nombre FROM areas ars WHERE ars.id=lce.evaluado_id)
                        WHEN lc.entidad_evaluada=5 THEN (SELECT eqs.nombre FROM equipos eqs WHERE eqs.id=lce.evaluado_id)
                        ELSE "Error"
                    END) as EMPRESA'),
            \DB::raw('(CASE
            WHEN lc.entidad_evaluada=1 THEN (SELECT semp.nombre FROM empresa semp WHERE semp.id=lce.evaluado_id)
                WHEN lc.entidad_evaluada=2 THEN (SELECT sest.nombre FROM establecimiento sest WHERE sest.id=lce.evaluado_id)
                WHEN lc.entidad_evaluada=3 THEN (SELECT susu.nombre_completo FROM usuario susu WHERE susu.id=lce.evaluado_id)
                WHEN lc.entidad_evaluada=4 THEN (SELECT ars.nombre FROM areas ars WHERE ars.id=lce.evaluado_id)
                WHEN lc.entidad_evaluada=5 THEN (SELECT eqs.nombre FROM equipos eqs WHERE eqs.id=lce.evaluado_id)
                ELSE "Error"
            END) as evaluado'),
            'us.nombre_completo AS evaluador',
            'lce.id as ejecutada_id',
            'pre.id as pregunta_id',
            'pre.nombre as pregunta',
            \DB::raw("IF(lceo.comentario IS NULL, 'Sin observaciones', lceo.comentario)AS OBSERVACION"),
            \DB::raw('IF(res.valor_personalizado IS NULL, "No aplica",res.valor_personalizado) as respuesta'),
            'pa.tipo_pa as tipo_plan_accion'
        )
        ->Join('lista_chequeo_ejec_respuestas AS lcer','lcer.id','=','plan_accion_manu_det.lista_cheq_ejec_respuesta_id')
        ->Join('lista_chequeo_ejecutadas AS lce','lce.id','=','lcer.lista_chequeo_ejec_id')
        ->Join('lista_chequeo AS lc','lc.id','=','lce.lista_chequeo_id')
        ->Join('usuario AS us','us.id','=','lce.usuario_id')
        ->Join('establecimiento AS esta','esta.id','=','us.establecimiento_id')
        ->Join('empresa AS empe','empe.id','=','esta.empresa_id')
        ->Join('pregunta AS pre','pre.id','=','lcer.pregunta_id')
        ->Join('respuesta AS res','res.id','=','lcer.respuesta_id')
        ->Join('lista_chequeo_ejec_opciones AS lceo','lceo.lista_chequeo_ejec_respuestas_id','=','lcer.id')
        ->Join('lista_chequeo_ejec_planaccion AS lcep','lcep.lista_chequeo_ejec_opciones','=','lceo.id')
        ->Join('plan_accion as pa', 'pa.id', '=', 'lceo.plan_accion_id')
        ->where([
            ['plan_accion_manu_det.respuesta','=', $this->idUsuario],
            ['lce.estado','=',2],
            ['plan_accion_manu_det.plan_accio_man_opc_id','=',8]
        ])
        ->orderBy('lce.fecha_realizacion','DESC');

        if(COUNT($filtro_array) != 0)
        {
            $traerPlanAcciones = $traerPlanAcciones->where(function($query) use ($filtro_array)
            {
                // $contador = 0;
                foreach ($filtro_array as $keys => $oW) 
                {
                    // if( $contador == 0)
                    //     $query->where($oW[0], '=', $oW[2]);
                    // else
                    // {
                    //     $query->orWhere($oW[0], '=', $oW[2]);
                    // }
                    $query->where($oW[0], '=', $oW[2]);

                    // $contador = $contador + 1;
                }

                return $query;
            });
            
        }

        if($whereRaw != 0)
        {
            $traerPlanAcciones = $traerPlanAcciones->whereRaw('(CASE
                    WHEN lc.entidad_evaluada=1 THEN (SELECT semp.id FROM empresa semp WHERE semp.id=lce.evaluado_id)
                    WHEN lc.entidad_evaluada=2 THEN (SELECT semp.id FROM establecimiento sest 
                                                    INNER JOIN empresa semp ON semp.id = sest.empresa_id WHERE sest.id=lce.evaluado_id)
                    WHEN lc.entidad_evaluada=3 THEN (SELECT semp.id FROM usuario susu
                                                    INNER JOIN establecimiento sesta ON sesta.id = susu.establecimiento_id
                                                    INNER JOIN empresa semp ON semp.id = sesta.empresa_id
                                                    WHERE susu.id=lce.evaluado_id)
                    WHEN lc.entidad_evaluada=4 THEN (SELECT ars.id FROM areas ars WHERE ars.id=lce.evaluado_id)
                    WHEN lc.entidad_evaluada=5 THEN (SELECT eqs.id FROM equipos eqs WHERE eqs.id=lce.evaluado_id)
                    ELSE "Error"
                END) = ?',[$whereRaw]);

        }

        if($whereRawEvaluado != 0)
        {
            $traerPlanAcciones = $traerPlanAcciones->whereRaw('(CASE
                    WHEN lc.entidad_evaluada=1 THEN (SELECT semp.id FROM empresa semp WHERE semp.id=lce.evaluado_id)
                        WHEN lc.entidad_evaluada=2 THEN (SELECT sest.id FROM establecimiento sest WHERE sest.id=lce.evaluado_id)
                        WHEN lc.entidad_evaluada=3 THEN (SELECT susu.id FROM usuario susu WHERE susu.id=lce.evaluado_id)
                        WHEN lc.entidad_evaluada=4 THEN (SELECT ars.id FROM areas ars WHERE ars.id=lce.evaluado_id)
                        WHEN lc.entidad_evaluada=5 THEN (SELECT eqs.id FROM equipos eqs WHERE eqs.id=lce.evaluado_id)
                        ELSE "Error"
                    END) = ?',[$whereRawEvaluado]);
        }

        $traerPlanAcciones = $traerPlanAcciones->get();

        return view('exports.planAccion',["planAccion" => $traerPlanAcciones]);
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
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W100'; // All headers
                // $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('A1:B1')->getAlignment()->setWrapText(true);
         
            $styleArray = [
                'font' => [
                    'name' => 'Arial',
                    'size' => 14,
                    'bold' => true,
                    'color' => [
                        'argb' => 'FFFFFFFF'
                     ]
                ]
            ];
            $event->sheet->getDelegate()->getStyle(':G8')->applyFromArray($styleArray);

            // // Set first row to height 20
            // $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(20);

            // // Set A1:D4 range to wrap text in cells
            // $event->sheet->getDelegate()->getStyle('A1:D4')
            //     ->getAlignment()->setWrapText(true);
           
            }
        ];
    }


}
