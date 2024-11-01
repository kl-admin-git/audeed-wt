<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Http\Models\Usuario;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;

class DetalleListaChequeo implements FromView,ShouldAutoSize
{
    use Exportable;
    protected $seccionUno,$seccionDos,$seccionTres;
    public function __construct($seccionUno,$seccionDos,$seccionTres)
    {   
        $this->seccionUno = $seccionUno;
        $this->seccionDos = $seccionDos;
        $this->seccionTres = $seccionTres;
        
    }
    public function view(): View
    {
        return view('exports.listaChequeo', [
            'seccionUno' => $this->seccionUno,
            'seccionDos' => $this->seccionDos,
            'seccionTres' => $this->seccionTres
        ]);
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
