<?php

namespace App\Exports;

use App\Http\Models\Usuario;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
class VerificacionBalanzasExports implements  FromView,ShouldAutoSize
{
    use Exportable;

    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $fechaRealizacion = (COUNT($this->data) == 0 ? '' : $this->data[0]['FECHA_REALIZACION']);
        $diligenciado = (COUNT($this->data) == 0 ? '' : $this->data[0]['DILIGENCIADO']);
        
        return view('exports.verificacion_balanzas', [
            'data' => $this->data,
            'fechaRealizacion' => $fechaRealizacion,
            'diligenciado' => $diligenciado
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
