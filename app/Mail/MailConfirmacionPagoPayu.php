<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Http\Models\Usuario;

class MailConfirmacionPagoPayu extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $usuario,$mensaje;
    public function __construct(Usuario $usuario,$mensaje='')
    {
        $this->usuario = $usuario;
        $this->mensaje = $mensaje;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'audiid@audiid.co';
        $name = "Audiid";
        return $this
        ->from($address, $name)
        ->subject('Confirmación Suscripción')
        ->view('Mails.confirmacion_payu');
    }
}
