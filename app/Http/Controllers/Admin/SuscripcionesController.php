<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\enviarMensajeEvent;
use App\Http\Models\Empresa;
use App\Http\Models\Establecimiento;
use App\Http\Models\ListaChequeo;
use App\Http\Models\CuentaPrincipal;
use App\Http\Models\CuentaPrincipalTarjeta;
use App\Http\Models\Plan;
use App\Http\Models\VentasPayu;
use App\Http\Models\Usuario;
use App\Mail\MailContactanosSuscripcion;


class SuscripcionesController extends Controller
{
    protected  $empresa,$establecimiento,$cuentaPrincipal,$cuentaPrincipalTarjeta,$url,$hostHeadRequest,$authorization,$ventasPayu;
    public function __construct(
        Empresa $empresa,
        Establecimiento $establecimiento,
        ListaChequeo $listaChequeo,
        CuentaPrincipal $cuentaPrincipal,
        CuentaPrincipalTarjeta $cuentaPrincipalTarjeta,
        Plan $plan,
        VentasPayu $ventasPayu,
        Usuario $usuario
    )
    {
        $this->empresa = $empresa;
        $this->establecimiento = $establecimiento;
        $this->listaChequeo = $listaChequeo;
        $this->cuentaPrincipal = $cuentaPrincipal;
        //PRUEBAS
        $this->url = 'https://sandbox.api.payulatam.com/payments-api/rest/v4.9/';
        $this->hostHeadRequest = 'sandbox.api.payulatam.com';
        $this->authorization = 'cFJSWEtPbDhpa01tdDl1OjRWajhlSzRybG9VZDI3Mkw0OGhzcmFyblVB';

        //PRODUCCIÓN
        // $this->url = 'https://api.payulatam.com/payments-api/rest/v4.9/';
        // $this->hostHeadRequest = 'api.payulatam.com';
        // $this->authorization = 'dGwwYVJLMEhUWFkyT2lVOk8zem5Pd0JhUHJ3OTN2OEpOM3ZvM1VzZEdy';

        $this->cuentaPrincipalTarjeta = $cuentaPrincipalTarjeta;
        $this->plan = $plan;
        $this->ventasPayu = $ventasPayu;
        $this->usuario = $usuario;

        $this->middleware('auth');
        $this->middleware('isActive');
    }

    public function SeleccionarPlan(Request $request)
    {
        $idSuscripcion = $request->get('idSuscripcion');
        switch ($idSuscripcion) 
        {
            case '1': //GRATIS
                $arrayActualizar = [
                    'plan_id' => $idSuscripcion
                ];
                
                $actualizarCuenta = $this->cuentaPrincipal->where('id','=', auth()->user()->cuenta_principal_id)->update($arrayActualizar);
                
                return $this->FinalizarRetorno(
                    206,
                    $this->MensajeRetorno('',206, "Suscripción existosa")
                );

                break;

            case '2': //BÁSICO
                $cuentaPrincipal = $this->cuentaPrincipal->where('id','=', auth()->user()->cuenta_principal_id)->first();
                //VALIDAR SI TIENE TARJETA DE CRÉDITO ASIGNADA
                if(is_null($cuentaPrincipal->cuenta_principal_tarjeta_id)) //NO TIENE TARJETA ASIGNADA
                {
                    return $this->FinalizarRetorno(
                        204,
                        $this->MensajeRetorno('',204, "No tienes tarjeta de crédito asignada")
                    );

                }else // SI TIENE TARJETA
                {
                    // INFORMACIÓN PARA PAGOS
                    $informacionUsuarioAudeed = $this->FuncionParaSaberSiExisteONoEnAudeed(auth()->user()->cuenta_principal_id);

                    //CONSULTA DE PLAN ESCOGIDO (ID PARA PAYU)
                    $informacionPlan = $this->plan->where('id','=',$idSuscripcion)->first();    
                    
                    $craecionSuscripcion = $this->FuncionCrearSuscripcionEntreClienteYPlan(
                        $informacionUsuarioAudeed->id_customer,
                        $informacionUsuarioAudeed->token,
                        $informacionPlan->descripcion,
                        '');

                    dd($craecionSuscripcion);

                } 
                
                break;

            case '3': //AVANZADO
                
                break;

            case '4': //EXPERTO
                
                break;
            
            default:
                
                break;
        }
    }

    public function CrearPlanSuscripcion(Request $request)
    {
            $arrayPlanes = [];
            array_push($arrayPlanes,array('name' => 'PLAN_VALUE','value' => '20000','currency' => 'COP'));
            // array_push($arrayPlanes,array('name' => 'PLAN_TAX','value' => '20000','currency' => 'COP'));
            // array_push($arrayPlanes,array('name' => 'PLAN_TAX_RETURN_BASE','value' => '20000','currency' => 'COP'));

            $configuracionPlanes = array(
                'accountId' => '512321',
                'planCode' => 'plan_audeed_masivo_001',
                'description' => 'PLAN MENSUAL AUDIID',
                'interval' => 'MONTH', // DAY, WEEK, MONTH y YEAR
                'intervalCount' => '1',
                'maxPaymentsAllowed' => '0',
                'maxPaymentAttempts' => '2',
                'paymentAttemptsDelay' => '2',
                'maxPendingPayments' => '1',
                'trialDays' => '0',
                'additionalValues' => $arrayPlanes
            );

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => ($this->url.'plans'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($configuracionPlanes),
            CURLOPT_HTTPHEADER => array(
                "Host: sandbox.api.payulatam.com",
                "Content-Type: application/json; charset=utf-8",
                "Accept: application/json",
                "Accept-Language: es",
                "Authorization: Basic cFJSWEtPbDhpa01tdDl1OjRWajhlSzRybG9VZDI3Mkw0OGhzcmFyblVB"
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            dd($response);

    }

    public function EliminarTarjetaCredito(Request $request)
    {
        //SE TRAE POR UNA CONSULTA LOS DATOS DEL USUARIO PARA SU ELIMINACIÓN
        $idUsuarioPayu = 'e0660a960dql';
        $tokenTarjetaCredito = '944a28f4-3b01-4f85-98da-84cb1c3630ea';

        $respuesta = $this->FuncionEliminarTarjeta($idUsuarioPayu,$tokenTarjetaCredito);
        
        dd($respuesta); //TRUE- FALSE
    }

    public function AgregarTarjetaCredito(Request $request)
    {
        $objetoRecibido = json_decode($request->get('objetoEnviar'));
        
        //SABER SI EXISTE EN LA BD PARA REVISAR SI EXISTE EN PAYU
        $tieneUsuarioEnAudeed = $this->FuncionParaSaberSiExisteONoEnAudeed(auth()->user()->cuenta_principal_id);

        if(is_null($tieneUsuarioEnAudeed)) // NO EXISTE EN LA BD POR ENDE EN PAYU TAMPOCO
        {
            $cuentaPrincipal = $this->cuentaPrincipal->where('id','=', auth()->user()->cuenta_principal_id)->first();

            $idCustomerPayu = $this->FuncionCrearClientePayu($objetoRecibido->nombreTarjeta,$cuentaPrincipal->correo_electronico);
            if(!is_null($idCustomerPayu)) // RETORNA CORRECTAMENTE EL ID DE PAYU CUSTOMER
            {
                $idToken = $this->FuncionAgregarTarjeta(
                    $idCustomerPayu,
                    $objetoRecibido->nombreTarjeta,
                    $objetoRecibido->identificacion,
                    $objetoRecibido->numeroTarjeta,
                    $objetoRecibido->fechaExpiracion,
                    $objetoRecibido->tipoTarjeta,
                    '000000',
                    $objetoRecibido->ciudad,
                    $objetoRecibido->telefono,
                    $objetoRecibido->pais,
                    $objetoRecibido->departamento,
                    $objetoRecibido->direccion
                );

                if(!ISSET($idToken->type)) // SI LA RESPUESTA ES EL TOKEN
                {
                    $tipoTarjeta = 0;
                    switch ($objetoRecibido->tipoTarjeta) 
                    {
                        case 'visa':
                            $tipoTarjeta = 1;
                            break;
                        
                        case 'mastercard':
                            $tipoTarjeta = 2;
                            break;

                        case 'maestro':
                            $tipoTarjeta = 3;
                            break;

                        case 'american express':
                            $tipoTarjeta = 4;
                            break;

                        default:
                            $tipoTarjeta = 0;
                            break;
                    }

                    $numeroTarjetaCifrada = "**** **** **** ".SUBSTR($objetoRecibido->numeroTarjeta,15);

                    $arrayInsertar = [
                        'tipo_tarjeta' => 1, // SI ES DEBITO O CRÉDITO
                        'tipo_tarjeta_2' => $tipoTarjeta,
                        'nombre_tarjeta' => $objetoRecibido->nombreTarjeta,
                        'identificacion' => $objetoRecibido->identificacion,
                        'numero' => $numeroTarjetaCifrada,
                        'token' => $idToken,
                        'id_customer' => $idCustomerPayu
                    ];
            
                    $cuentaPrincipalTarjeta = new $this->cuentaPrincipalTarjeta;
                    $cuentaPrincipalTarjeta->fill($arrayInsertar);
                    
                    if($cuentaPrincipalTarjeta->save())
                    {
                        $respuestaUpdate = $this->cuentaPrincipal->where('id','=',auth()->user()->cuenta_principal_id)
                        ->update(
                        [
                            'cuenta_principal_tarjeta_id' => $cuentaPrincipalTarjeta->id
                        ]);
                        
                        return $this->FinalizarRetorno(
                            206,
                            $this->MensajeRetorno('',206, "Tarjeta agregada correctamente")
                        );
                    }
                }
                else // SI EXISTIÓ UN PROBLAMA CON LA TARJETA
                {
                    return $this->FinalizarRetorno(
                        406,
                        $this->MensajeRetorno('',406, $idToken->description)
                    );
                }
            }

        }
        else // SI EXISTE EN LA BASE DE DATOS DE AUDEED
        {
            $idToken = $this->FuncionAgregarTarjeta( //SE AGREGA NUEVAMENTE LA TARJETA DE CRÉDITO
                $tieneUsuarioEnAudeed->id_customer,
                $objetoRecibido->nombreTarjeta,
                $objetoRecibido->identificacion,
                $objetoRecibido->numeroTarjeta,
                $objetoRecibido->fechaExpiracion,
                $objetoRecibido->tipoTarjeta,
                '000000',
                $objetoRecibido->ciudad,
                $objetoRecibido->telefono,
                $objetoRecibido->pais,
                $objetoRecibido->departamento,
                $objetoRecibido->direccion
            );

            if(!ISSET($idToken->type)) // SI LA RESPUESTA ES EL TOKEN
            {

                $tipoTarjeta = 0;
                switch ($objetoRecibido->tipoTarjeta) 
                {
                    case 'visa':
                        $tipoTarjeta = 1;
                        break;
                    
                    case 'mastercard':
                        $tipoTarjeta = 2;
                        break;

                    case 'maestro':
                        $tipoTarjeta = 3;
                        break;

                    case 'american express':
                        $tipoTarjeta = 4;
                        break;

                    default:
                        $tipoTarjeta = 0;
                        break;
                }

                $numeroTarjetaCifrada = "**** **** **** ".SUBSTR($objetoRecibido->numeroTarjeta,15);

                $arrayActualizar = [
                    'tipo_tarjeta' => 1, // SI ES DEBITO O CRÉDITO
                    'tipo_tarjeta_2' => $tipoTarjeta,
                    'nombre_tarjeta' => $objetoRecibido->nombreTarjeta,
                    'identificacion' => $objetoRecibido->identificacion,
                    'numero' => $numeroTarjetaCifrada,
                    'token' => $idToken
                ];
        
                $respuestaUpdate = $this->cuentaPrincipalTarjeta->where('id_customer','=',$tieneUsuarioEnAudeed->id_customer)->update($arrayActualizar);

                $eliminado = $this->FuncionEliminarTarjeta(
                    $tieneUsuarioEnAudeed->id_customer,
                    $tieneUsuarioEnAudeed->token
                );
    
                if($eliminado) // SI SE ELIMINO DE PAYU CORRECTAMENTE LA TARJETA DE CRÉDITO
                {
                    return $this->FinalizarRetorno(
                        206,
                        $this->MensajeRetorno('',206, "Tarjeta agregada correctamente")
                    );
                }
                else
                {
                    return $this->FinalizarRetorno(
                        406,
                        $this->MensajeRetorno('',406, $eliminado)
                    );
                }

            }
            else // SI EXISTIÓ UN PROBLAMA CON LA TARJETA
            {
                return $this->FinalizarRetorno(
                    406,
                    $this->MensajeRetorno('',406, $idToken->description)
                );
            }

        }
    }

    public function FuncionParaSaberSiExisteONoEnAudeed($idCuentaPrincipal)
    {
        $idCustomer = $this->cuentaPrincipal
        ->select('cpt.*')
        ->Join('cuenta_principal_tarjeta AS cpt','cuenta_principal.cuenta_principal_tarjeta_id','=','cpt.id')
        ->where('cuenta_principal.id','=',$idCuentaPrincipal)
        ->first();
        
        return $idCustomer;
    }
    
    public function FuncionParaSaberSiExisteONoEnUsuarioEnPayu($idUsuarioPayu)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->url."customers/".$idUsuarioPayu,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Host: sandbox.api.payulatam.com",
            "Content-Type: application/json; charset=utf-8",
            "Accept: application/json",
            "Accept-Language: es",
            "Authorization: Basic cFJSWEtPbDhpa01tdDl1OjRWajhlSzRybG9VZDI3Mkw0OGhzcmFyblVB"
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $respuesta = json_decode($response);
        if(ISSET($respuesta->type)) // ES POR QUE EL USUARIO NO EXISTE EN LA BD DE PAYU
            return false;
        else // ES PÖR QUE SI EXISTE EN LA BD DE PAYU EL USUARIO
            return true;
    }

    public function FuncionAgregarTarjeta(
            $idUsuarioPayu,
            $nombre,
            $documento,
            $numeroTarjeta,
            $expiracion,
            $tipoTarjeta,
            $codigoPostal,
            $ciudad,
            $telefono,
            $pais,
            $departamento,
            $direccion
        )
    {
        $numeroTarjeta = str_replace(" ","",$numeroTarjeta);
        $month = explode('/',$expiracion)[0];
        $year = explode('/',$expiracion)[1];

        $pais = strtoupper(substr($pais, 0,2));

        $datosTarjeta = array(
            "name"=> $nombre,
            "document"=> $documento,
            "number"=> $numeroTarjeta,
            "expMonth"=> $month,
            "expYear"=> '20'.$year,
            "type"=> strtoupper($tipoTarjeta),
            "address"=> [
                "line1"=> $direccion,
                "line2"=> "",
                "line3"=> "",
                "postalCode"=> $codigoPostal,
                "city"=> $ciudad,
                "state"=> $departamento,
                "country"=> $pais,
                "phone"=> $telefono
            ]
        );
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => ($this->url."customers/".$idUsuarioPayu."/creditCards"),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($datosTarjeta),
        CURLOPT_HTTPHEADER => array(
            "Host: ".$this->hostHeadRequest,
            "Content-Type: application/json; charset=utf-8",
            "Accept: application/json",
            "Accept-Language: es",
            "Authorization: Basic ".$this->authorization
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $respuesta = json_decode($response);

        if(ISSET($respuesta->type)) // ES POR QUE EL USUARIO NO EXISTE EN LA BD DE PAYU
            return $respuesta;
        else // ES PÖR QUE SI EXISTE EN LA BD DE PAYU EL USUARIO
            return $respuesta->token;
        
    }

    public function FuncionEliminarTarjeta(
            $idUsuarioPayu,
            $tokenCreditCard
        )
    {
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
        CURLOPT_URL => ($this->url."customers/".$idUsuarioPayu."/creditCards/".$tokenCreditCard),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "DELETE",
        CURLOPT_HTTPHEADER => array(
            "Host: ".$this->hostHeadRequest,
            "Content-Type: application/json; charset=utf-8",
            "Accept: application/json",
            "Accept-Language: es",
            "Authorization: Basic ".$this->authorization
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $respuesta = json_decode($response);
        
        if(ISSET($respuesta->type)) // ES POR QUE ALGO PASO CON LA ELIMINACIÓN DE LA TARJETA
            return false;
        else // ES POR QUE SE LOGRÓ ELIMINAR CORRECTAMENTE LA TARJETA
            return $respuesta->description;
    }

    public function FuncionCrearClientePayu(
        $nombre,
        $correoElectronico
    )
    {
        $datosUsuario = array(
            "fullName" => $nombre,
            "email" => $correoElectronico
        );
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => ($this->url."customers"),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($datosUsuario),
        CURLOPT_HTTPHEADER => array(
            "Host: ".$this->hostHeadRequest,
            "Content-Type: application/json; charset=utf-8",
            "Accept: application/json",
            "Accept-Language: es",
            "Authorization: Basic ".$this->authorization
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $respuesta = json_decode($response);
        if(ISSET($respuesta->type)) // ES POR QUE ALGO PASO CON LA CREACIÓN DEL CLIENTE
        {
            return NULL;
        }
        else // ES POR QUE SE LOGRÓ CREAR CORRECTAMENTE EL USUARIO RETORNA EL ID CUSTOMER
        {
            return $respuesta->id;
        }
            
    }

    public function FuncionCrearSuscripcionEntreClienteYPlan(
        $idUsuarioPayu,
        $tokenTarjetaCreditoPayu,
        $codigoPlanPayu,
        $textoExtra
    )
    {
        $suscripcion = array(
            "quantity" => 1,
            "immediatePayment" => true,
            "customer" => [
                "id" => $idUsuarioPayu,
                "creditCards" => [
                    [
                        "token" => $tokenTarjetaCreditoPayu
                    ]
                ]
            ],
            "plan" => [
                "planCode" => $codigoPlanPayu
            ],
            "extra1" => $textoExtra,
            "notifyUrl" => 'https://app.audeed.app/payment/respuestaURL'
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => ($this->url."subscriptions"),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($suscripcion),
        CURLOPT_HTTPHEADER => array(
            "Host: ".$this->hostHeadRequest,
            "Content-Type: application/json; charset=utf-8",
            "Accept: application/json",
            "Accept-Language: es",
            "Authorization: Basic ".$this->authorization
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        dd($response);
    }

    //PAGO UNICO
    public function SuscripcionValidacion(Request $request)
    {
        $respuesta = $this->FuncionParaSaberSiPuedeSuscribirse();
        if($respuesta)
        {
            return $this->FinalizarRetorno(
                202,
                $this->MensajeRetorno('Datos',202)
            );
        }else
        {
            return $this->FinalizarRetorno(
                206,
                $this->MensajeRetorno('',206, "No puedes realizar la suscripción, ya tienes un pago pendiente por realizarse")
            );
        }
    }

    public function Contactanos(Request $request)
    {
        $this->FuncionEnviarCorreoContactanos();

        return $this->FinalizarRetorno(
            206,
            $this->MensajeRetorno('',206,'Nos contáctaremos contigo lo más pronto posible')
        );
    }

    public function FuncionEnviarCorreoContactanos()
    {
        $usuario = $this->usuario->where('id','=',auth()->user()->id)->first();

        // $arrayCorreos = ['dev@klaxen.com.co','tic@klaxen.com.co','direcciondigital@audeed.co','gerencia@klaxen.com.co','gerencia@klaxen.com','desarrolloweb@klaxen.com.co'];
        $arrayCorreos = ['dev@klaxen.com.co'];
        \Mail::to($arrayCorreos)->send(new MailContactanosSuscripcion($usuario));
    }

    public function FuncionParaSaberSiPuedeSuscribirse()
    {
        $ultimoRegistro = $this->ventasPayu->where('id_cuenta_principal','=',auth()->user()->cuenta_principal_id)->orderBy('id', 'desc')->first();
        if(ISSET($ultimoRegistro))
        {
            if($ultimoRegistro->transaction_state != 4 && $ultimoRegistro->transaction_state != 6 && $ultimoRegistro->transaction_state != 104 && $ultimoRegistro->transaction_state != 5)
            {
                // PENDIENTE
                return false;
            }
        }
        
        return true;
    }

}
