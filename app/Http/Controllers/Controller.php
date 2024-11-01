<?php
/*
|--------------------------------------------------------------------------
| Palabra Biblica de Audeed
|--------------------------------------------------------------------------
|
|   Antes bien, como está escrito:
|    Cosas que ojo no vio, ni oído oyó,
|    Ni han subido en corazón de hombre,
|    Son las que Dios ha preparado para los que le aman
|                                                   1 Corintios 2:9
*/

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $urlImagenesLogo = 'imagenes/logos_empresariales/';
    public $urlImagenesAvatar = 'imagenes/usuarios/';
    public $urlImagenesListaChequeo = 'imagenes/listas_chequeo/';

    public function MensajeRetorno($entity, $code, $customMessage = "")
    {
        $errorCodes = array(
            //Success
            200 => "$entity ha sido creado",
            201 => "$entity ha sido actualizado",
            202 => "$entity encontrado",
            203 => "$entity ha sido eliminado",
            204 => "success custom",
            205 => "success custom",
            206 => "success custom",

            //Failed
            400 => "Datos invalidos",
            401 => "$entity ya está en uso",
            402 => "no se pudo guardar $entity",
            403 => "formato $entity incorrecto",
            404 => "$entity no se encontró",
            405 => "$entity no está disponible",
            406 => "failed custom",
            407 => "failed custom",
            408 => "failed custom",

            //Error server
            900 => "Error en el servidor, no se pudo procesar el llamado - $entity",
        );

        if ($code === 204 || $code === 205 || $code === 206 || $code === 406 || $code === 407 || $code === 408) {
            $errorCodes[$code] = $customMessage;
        }

        return $errorCodes[$code];
    }

    public function FinalizarRetorno($code, $message, $objectSend = null)
    {
        $success = substr((string) $code, 0, 1) === "2" ? 1 : 0;
    
        return response()->json([
            'exitoso' => $success,
            'codigoRespuesta' => $code,
            'mensaje' => $message,
            'datos' => $objectSend
        ]);
    }

    public function CalculoPaginacion($paginacion,$rango=9)
    {      
        $hasta = ($paginacion * $rango);
        $desde = ($hasta - $rango);
    
        return array('desde' => $desde, 'hasta' => $rango);
    }
}
