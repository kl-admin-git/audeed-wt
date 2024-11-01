<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\enviarMensajeEvent;
use App\Http\Models\Pais;
use App\Http\Models\VentasPayu;
use App\Http\Models\PlanPagos;
use App\Http\Models\CuentaPrincipal;
use Carbon\Carbon;
use App\Mail\MailConfirmacionPagoPayu;

class PaymentController extends Controller
{
    protected $ventasPayu,$pais,$planPagos,$cuentaPrincipal;
    public function __construct(Pais $pais,VentasPayu $ventasPayu,PlanPagos $planPagos,CuentaPrincipal $cuentaPrincipal)
    {
        $this->pais = $pais;
        $this->ventasPayu = $ventasPayu;
        $this->planPagos = $planPagos;
        $this->cuentaPrincipal = $cuentaPrincipal;
        // $this->middleware('auth');
        // $this->middleware('isActive');
    }

    public function Index()
    {
        $paises = $this->pais->select('id','indicativo','nombre',
            \DB::raw('CONCAT(indicativo," (",nombre,")") AS CONCATENACION')
        )->get();

        return view('Admin.administracion_payment',compact('paises'));
    }

    public function RespuestaPayu(Request $request)
    {

    //   $merchantId = $request->get('merchantId');// "508029"
    //   $merchant_url = $request->get('merchant_url');// "http://pruebaslapv.xtrweb.com"
    //   $orderLanguage = $request->get('orderLanguage');// "es"
    //   $polTransactionState = $request->get('polTransactionState');// "6"
    //   $signature = $request->get('signature');// "f2fad8a9bea6453a3708ed95a86d0c1f"
    //   $risk = $request->get('risk');// null
    //   $polPaymentMethod = $request->get('polPaymentMethod');// "10"
    //   $polPaymentMethodType = $request->get('polPaymentMethodType');// "2"
    //   $installmentsNumber = $request->get('installmentsNumber');// "1"
    //   $lng = $request->get('lng');// "es"
    //   $TX_TAX_ADMINISTRATIVE_FEE = $request->get('TX_TAX_ADMINISTRATIVE_FEE');// ".00"

      $merchant_name = $request->get('merchant_name');// "Test PayU Test comercio"
      $merchant_address = $request->get('merchant_address');// "Av 123 Calle 12"
      $telephone = $request->get('telephone');// "7512354"
      $transactionState = $request->get('transactionState');// "6"
      $lapTransactionState = $request->get('lapTransactionState');// "DECLINED"
      $message = $request->get('message');// "DECLINED"
      $referenceCode = $request->get('referenceCode');// "audeed_15955250812066"
      $reference_pol = $request->get('reference_pol');// "120275099"
      $transactionId = $request->get('transactionId');// "a18daa86-0622-4680-958a-4415b28308ec"
      $description = $request->get('description');// "Test PAYU"
      $trazabilityCode = $request->get('trazabilityCode');// "542797"
      $cus = $request->get('cus');// "542797"
      $extra1 = $request->get('extra1');// null
      $extra2 = $request->get('extra2');// null
      $extra3 = $request->get('extra3');// null
      $polResponseCode = $request->get('polResponseCode');// "4"
      $lapResponseCode = $request->get('lapResponseCode');// "PAYMENT_NETWORK_REJECTED"
      $lapPaymentMethod = $request->get('lapPaymentMethod');// "VISA"
      $lapPaymentMethodType = $request->get('lapPaymentMethodType');// "CREDIT_CARD"
      $TX_VALUE = $request->get('TX_VALUE');// "20000.00"
      $TX_TAX = $request->get('TX_TAX');// ".00"
      $currency = $request->get('currency');// "COP"
      $pseCycle = $request->get('pseCycle');// null
      $buyerEmail = $request->get('buyerEmail');// "sebaskyy@gmail.com"
      $pseBank = $request->get('pseBank');// null
      $pseReference1 = $request->get('pseReference1');// null
      $pseReference2 = $request->get('pseReference2');// null
      $pseReference3 = $request->get('pseReference3');// null
      $authorizationCode = $request->get('authorizationCode');// "RBM414"
      $TX_ADMINISTRATIVE_FEE = $request->get('TX_ADMINISTRATIVE_FEE');// ".00"
      $TX_TAX_ADMINISTRATIVE_FEE_RETURN_BASE = $request->get('TX_TAX_ADMINISTRATIVE_FEE_RETURN_BASE');// ".00"
      $processingDate = $request->get('processingDate');// "2020-07-23"
      
      
      if (!$this->ventasPayu->where('reference_code','=', $referenceCode)->exists())
      {

        $arrayInsertar = [
            'reference_code' => $referenceCode,
            'transaction_state' => $transactionState,
            'pol_response_code' => $polResponseCode,
            'reference_pol' => $reference_pol,
            'tx_value' => $TX_VALUE,
            'tx_tax' => $TX_TAX,
            'buyer_email' => $buyerEmail,
            'processing_date' => $processingDate,
            'currency' => $currency,
            'cus' => $cus,
            'pse_bank' => $pseBank,
            'description' => $description,
            'lap_response_code' => $lapResponseCode,
            'lap_payment_method' => $lapPaymentMethod,
            'lap_payment_type' => $lapPaymentMethodType,
            'lap_transaction_state' => $lapTransactionState,
            'message' => $message,
            'extra1' => $extra1,
            'extra2' => $extra2,
            'extra3' => $extra3,
            'autorization_code' => $authorizationCode,
            'merchant_address' => $merchant_address,
            'merchant_name' => $merchant_name,
            'pse_cycle' => floatval($pseCycle),
            'pse_reference1' => $pseReference1,
            'pse_reference2' => $pseReference2,
            'pse_reference3' => $pseReference3,
            'telephone' => $telephone,
            'transaction_id' => $transactionId,
            'trazability_code' => $trazabilityCode,
            'tx_administrative_fee' => !ISSET($TX_ADMINISTRATIVE_FEE) ? NULL : $TX_ADMINISTRATIVE_FEE,
            'tx_tax_administrative_fee' => !ISSET($TX_TAX_ADMINISTRATIVE_FEE) ? NULL : $TX_TAX_ADMINISTRATIVE_FEE,
            'tx_tax_administrative_fee_return_base' => !ISSET($TX_TAX_ADMINISTRATIVE_FEE_RETURN_BASE) ? NULL : $TX_TAX_ADMINISTRATIVE_FEE_RETURN_BASE,
            'action_code_description' => !ISSET($action_code_description) ? NULL : $action_code_description,
            'cc_holder' => !ISSET($cc_holder) ? NULL : $cc_holder,
            'cc_number' => !ISSET($cc_number) ? NULL : $cc_number,
            'processing_date_time' => (!ISSET($processing_date_time) ? NULL : $processing_date_time),
            'request_number' => !ISSET($request_number) ? NULL : $request_number,
            'id_cuenta_principal' => auth()->user()->cuenta_principal_id
        ];

        $ventasPayu = new $this->ventasPayu;
        $ventasPayu->fill($arrayInsertar);
        
        $ventasPayu->save();

        switch ($lapTransactionState) {
            case 'APPROVED':
                $fechaInicio = Carbon::parse($processingDate);
                $fechaFin = Carbon::parse($processingDate)->addDays(30);
                $fechaInicio = $fechaInicio->format('Y-m-d');
                $fechaFin = $fechaFin->format('Y-m-d');
                $idPlan = EXPLODE('-',$description)[1];
                $tipoPago = 0; //PAGO UNICO
                $tarjeta = NULL;
                $arrayInsertar = [
                    'plan_id' => $idPlan, 
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin,
                    'tipo_pago' => $tipoPago,
                    'cuenta_principal_tarjeta_id' => $tarjeta,
                    'cuenta_principal_id' => auth()->user()->cuenta_principal_id
                ];

                $planPagos = new $this->planPagos;
                $planPagos->fill($arrayInsertar);
                
                if($planPagos->save())
                {
                    $arrayActualizar = [
                        'plan_id' => $idPlan
                    ];
                    
                    $actualizarCuenta = $this->cuentaPrincipal->where('id','=', auth()->user()->cuenta_principal_id)->update($arrayActualizar);
                }

                break;

            case 'DECLINED':
                break;

            case 'ERROR':
                break;

            case 'EXPIRED':
                break;

            case 'PENDING':
                break;
            
            default:
                # code...
                break;
        }

      }
      $mensaje = '';
        
      switch ($lapTransactionState) {
            case 'APPROVED':
                $mensaje = 'Gracias por realizar la compra de la suscripción, te recordarmos que para una mejor usabilidad del producto cada mes debes realizar este procedimiento, cada mes te lo notificaremos por medio de un correo electrónico';
                break;

            case 'DECLINED':
                $mensaje = 'Tu suscripción ha sido rechazada, por favor consulta con tu banco y vuelve a intentarlo nuevamente, recuerda que si tienes problemas con respecto a Audiid, puedes comnunicarte con nuestro servicio de soporte';
                break;

            case 'ERROR':
                $mensaje = 'Tuvimos un problema al realizar la suscripción, hay un error en tu transacción, comunicate con tú banco y vuelve a intentarlo';
                break;

            case 'EXPIRED':
                $mensaje = 'El proceso de transacción ha expirado, por el tiempo de espera de tu banco, comunicate con ellos y vuelve a intentarlo ';
                break;

            case 'PENDING':
                $mensaje = 'Tu suscripción se encuentra pendiente, aún no se ha realizado el pago, te estaremos notificando por medio de un correo electrónico el estado de tu suscripción';
                break;
            
            default:
                # code...
                break;
        }


        //  dd($merchantId);
        return view('Admin.respuesta_payu',compact('mensaje'));
    }

    public function ConfirmacionPayu(Request $request)
    {
        $merchant_name = $request->get('merchant_name');// "Test PayU Test comercio"
        $merchant_address = $request->get('merchant_address');// "Av 123 Calle 12"
        $telephone = $request->get('telephone');// "7512354"
        $transactionState = $request->get('transactionState');// "6"
        $lapTransactionState = $request->get('lapTransactionState');// "DECLINED"
        $message = $request->get('message');// "DECLINED"
        $referenceCode = $request->get('referenceCode');// "audeed_15955250812066"
        $reference_pol = $request->get('reference_pol');// "120275099"
        $transactionId = $request->get('transactionId');// "a18daa86-0622-4680-958a-4415b28308ec"
        $description = $request->get('description');// "Test PAYU"
        $trazabilityCode = $request->get('trazabilityCode');// "542797"
        $cus = $request->get('cus');// "542797"
        $extra1 = $request->get('extra1');// null
        $extra2 = $request->get('extra2');// null
        $extra3 = $request->get('extra3');// null
        $polResponseCode = $request->get('polResponseCode');// "4"
        $lapResponseCode = $request->get('lapResponseCode');// "PAYMENT_NETWORK_REJECTED"
        $lapPaymentMethod = $request->get('lapPaymentMethod');// "VISA"
        $lapPaymentMethodType = $request->get('lapPaymentMethodType');// "CREDIT_CARD"
        $TX_VALUE = $request->get('TX_VALUE');// "20000.00"
        $TX_TAX = $request->get('TX_TAX');// ".00"
        $currency = $request->get('currency');// "COP"
        $pseCycle = $request->get('pseCycle');// null
        $buyerEmail = $request->get('buyerEmail');// "sebaskyy@gmail.com"
        $pseBank = $request->get('pseBank');// null
        $pseReference1 = $request->get('pseReference1');// null
        $pseReference2 = $request->get('pseReference2');// null
        $pseReference3 = $request->get('pseReference3');// null
        $authorizationCode = $request->get('authorizationCode');// "RBM414"
        $TX_ADMINISTRATIVE_FEE = $request->get('TX_ADMINISTRATIVE_FEE');// ".00"
        $TX_TAX_ADMINISTRATIVE_FEE_RETURN_BASE = $request->get('TX_TAX_ADMINISTRATIVE_FEE_RETURN_BASE');// ".00"
        $processingDate = $request->get('processingDate');// "2020-07-23"
        
        
        if ($this->ventasPayu->where('reference_code','=', $referenceCode)->exists())
        {
          $arrayUpdate = [
              'reference_code' => $referenceCode,
              'transaction_state' => $transactionState,
              'pol_response_code' => $polResponseCode,
              'reference_pol' => $reference_pol,
              'tx_value' => $TX_VALUE,
              'tx_tax' => $TX_TAX,
              'buyer_email' => $buyerEmail,
              'processing_date' => $processingDate,
              'currency' => $currency,
              'cus' => $cus,
              'pse_bank' => $pseBank,
              'description' => $description,
              'lap_response_code' => $lapResponseCode,
              'lap_payment_method' => $lapPaymentMethod,
              'lap_payment_type' => $lapPaymentMethodType,
              'lap_transaction_state' => $lapTransactionState,
              'message' => $message,
              'extra1' => $extra1,
              'extra2' => $extra2,
              'extra3' => $extra3,
              'autorization_code' => $authorizationCode,
              'merchant_address' => $merchant_address,
              'merchant_name' => $merchant_name,
              'pse_cycle' => floatval($pseCycle),
              'pse_reference1' => $pseReference1,
              'pse_reference2' => $pseReference2,
              'pse_reference3' => $pseReference3,
              'telephone' => $telephone,
              'transaction_id' => $transactionId,
              'trazability_code' => $trazabilityCode,
              'tx_administrative_fee' => !ISSET($TX_ADMINISTRATIVE_FEE) ? NULL : $TX_ADMINISTRATIVE_FEE,
              'tx_tax_administrative_fee' => !ISSET($TX_TAX_ADMINISTRATIVE_FEE) ? NULL : $TX_TAX_ADMINISTRATIVE_FEE,
              'tx_tax_administrative_fee_return_base' => !ISSET($TX_TAX_ADMINISTRATIVE_FEE_RETURN_BASE) ? NULL : $TX_TAX_ADMINISTRATIVE_FEE_RETURN_BASE,
              'action_code_description' => !ISSET($action_code_description) ? NULL : $action_code_description,
              'cc_holder' => !ISSET($cc_holder) ? NULL : $cc_holder,
              'cc_number' => !ISSET($cc_number) ? NULL : $cc_number,
              'processing_date_time' => (!ISSET($processing_date_time) ? NULL : $processing_date_time),
              'request_number' => !ISSET($request_number) ? NULL : $request_number,
              'id_cuenta_principal' => auth()->user()->cuenta_principal_id
          ];
  
          $respuestaUpdate = $this->ventasPayu->where('reference_code','=',$referenceCode)->update($arrayUpdate);
  
          switch ($lapTransactionState) {
              case 'APPROVED':
                  $fechaInicio = Carbon::parse($processingDate);
                  $fechaFin = Carbon::parse($processingDate)->addDays(30);
                  $fechaInicio = $fechaInicio->format('Y-m-d');
                  $fechaFin = $fechaFin->format('Y-m-d');
                  $idPlan = EXPLODE('-',$description)[1];
                  $tipoPago = 0; //PAGO UNICO
                  $tarjeta = NULL;
                  $arrayInsertar = [
                      'plan_id' => $idPlan, 
                      'fecha_inicio' => $fechaInicio,
                      'fecha_fin' => $fechaFin,
                      'tipo_pago' => $tipoPago,
                      'cuenta_principal_tarjeta_id' => $tarjeta,
                      'cuenta_principal_id' => auth()->user()->cuenta_principal_id
                  ];
  
                  $planPagos = new $this->planPagos;
                  $planPagos->fill($arrayInsertar);
                  
                  if($planPagos->save())
                  {
                      $arrayActualizar = [
                          'plan_id' => $idPlan
                      ];
                      
                      $actualizarCuenta = $this->cuentaPrincipal->where('id','=', auth()->user()->cuenta_principal_id)->update($arrayActualizar);
                  }
                  // CORREO CON DATOS DE INGRESO
                  // $arrayCorreos = ['dev@klaxen.com.co','tic@klaxen.com.co','direcciondigital@audeed.co','gerencia@klaxen.com.co','gerencia@klaxen.com','desarrolloweb@klaxen.com.co',$buyerEmail];
                  $arrayCorreos = ['dev@klaxen.com.co'];
                  $mensaje = 'Te informamos que el proceso de suscripción fue exitoso, tú banco aprobó correctamente la transacción, puedes comenzar a disfrutar tu suscripción con Audeed.';
                  \Mail::to($arrayCorreos)->send(new MailConfirmacionPagoPayu($usuario,$mensaje));
                  break;
  
              case 'DECLINED':
                    // $arrayCorreos = ['dev@klaxen.com.co','tic@klaxen.com.co','direcciondigital@audeed.co','gerencia@klaxen.com.co','gerencia@klaxen.com','desarrolloweb@klaxen.com.co',$buyerEmail];
                    $arrayCorreos = ['dev@klaxen.com.co'];
                    $mensaje = 'Te informamos que el proceso de suscripción no fue exitoso, tú banco rechazó la transacción, por lo que no podremos continuar con tu suscripción. Comunicate con tú banco y vuelve a intentar el procedimiento.';
                    \Mail::to($arrayCorreos)->send(new MailConfirmacionPagoPayu($usuario,$mensaje));
                  break;
  
              case 'ERROR':
              case 'EXPIRED':
                    // $arrayCorreos = ['dev@klaxen.com.co','tic@klaxen.com.co','direcciondigital@audeed.co','gerencia@klaxen.com.co','gerencia@klaxen.com','desarrolloweb@klaxen.com.co',$buyerEmail];
                    $arrayCorreos = ['dev@klaxen.com.co'];
                    $mensaje = 'Te informamos que el proceso de suscripción no fue exitoso, hubo inconvenientes con la transacción o la información de tu tarjeta (Expiración), por lo que no podremos continuar con tu suscripción. Comunicate con tú banco y vuelve a intentar el procedimiento.';
                    \Mail::to($arrayCorreos)->send(new MailConfirmacionPagoPayu($usuario,$mensaje));
                  break;
  
              case 'PENDING':
                  break;
              
              default:
                  # code...
                  break;
          }
  
        }
    }
}
