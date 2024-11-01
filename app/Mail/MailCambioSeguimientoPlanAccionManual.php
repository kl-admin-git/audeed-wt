<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailCambioSeguimientoPlanAccionManual extends Mailable
{
    use Queueable, SerializesModels;

    public $estadoString,$idUsuario,$nombreUsuario;
    public function __construct($estadoString,$idUsuario)
    {
        $this->estadoString = $estadoString;
        $this->idUsuario = $idUsuario;
    }

    public function build()
    {
        $usuario = \DB::table('usuario')->where('id','=',$this->idUsuario)->first();
        $this->nombreUsuario = $usuario->nombre_completo;

        $address = 'audiid@audiid.co';
        $name = "Audiid";
        return $this
        ->from($address, $name)
        ->subject('Cambio en seguimiento (plan acción)')
        ->view('Mails.seguimiento_plan_accion');
    }
}
