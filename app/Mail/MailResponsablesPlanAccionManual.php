<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailResponsablesPlanAccionManual extends Mailable
{
    use Queueable, SerializesModels;

    public $listaDeChequeo,$nombre;
    public function __construct($listaDeChequeo)
    {
        $this->listaDeChequeo = $listaDeChequeo;
    }

    public function build()
    {
        $nombreResultado = $this->NombreResultadoFinalAuditoriaEjecucion($this->listaDeChequeo->id);
        foreach ($nombreResultado as $key => $item) 
        {
            $this->nombre = $item->nombre;
        }
        $address = 'audiid@audiid.co';
        $name = "Audiid";
        return $this
        ->from($address, $name)
        ->subject('Tienes una asignación')
        ->view('Mails.asignacion_responsable_PAM');
    }

    public function NombreResultadoFinalAuditoriaEjecucion($idEjecutada)
    {
        $retorno = \DB::select('SELECT
        lc.nombre,
        SUM(IF((TRUNCATE(((pre.ponderado*res.ponderado)/100),2)) IS NULL,pre.ponderado, (TRUNCATE(((pre.ponderado*res.ponderado)/100),2)))) AS res_final
        FROM lista_chequeo_ejec_respuestas lcer
        INNER JOIN lista_chequeo_ejecutadas lce ON lce.id=lcer.lista_chequeo_ejec_id
        INNER JOIN lista_chequeo lc ON lc.id=lce.lista_chequeo_id
        LEFT JOIN respuesta res ON res.id=lcer.respuesta_id
        INNER JOIN pregunta pre ON pre.id=lcer.pregunta_id
        INNER JOIN categoria cat ON cat.id=pre.categoria_id
        WHERE  lcer.lista_chequeo_ejec_id=:idEjecutada
        ORDER BY cat.id;',['idEjecutada' => $idEjecutada]);
        
        return $retorno;
    }
}
