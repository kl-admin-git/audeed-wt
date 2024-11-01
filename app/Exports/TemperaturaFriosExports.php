<?php

namespace App\Exports;

use App\Http\Models\Usuario;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class TemperaturaFriosExports implements  FromView,ShouldAutoSize
{
    use Exportable;

    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $semana = $this->data['SEMANA_DEL'];
        $diligenciado = $this->data['DILIGENCIADO'];
        
        return view('exports.temperatura_frios', [
            'data' => $this->data['data'],
            'semana' => $semana,
            'diligenciado' => $diligenciado
        ]);
    }
}
