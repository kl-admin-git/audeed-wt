@php
    $perfilExacto = 1;
    switch (auth()->user()->perfil_id)
    {
        case 1: // ADMINISTRADOR
            $perfilExacto = 1;
            break;

        case 2: // COLABORADOR
            //VERIFICAR SI ES RESPONSABLE DE EMPRESA
            $esResponsableEmpresa = \DB::table('empresa')->where('usuario_id','=',auth()->user()->id)->first();

            if(!is_null($esResponsableEmpresa))
                $perfilExacto = 2;


            // //VERIFICAR SI ES RESPONSABLE DE ESTABLECIMIENTO
            $esResponsableEstablecimiento = \DB::table('establecimiento')->where('usuario_id','=',auth()->user()->id)->first();
            if(!is_null($esResponsableEstablecimiento))
                $perfilExacto = 3;

            if(is_null($esResponsableEmpresa) && is_null($esResponsableEstablecimiento))
                $perfilExacto = 4;

            break;

        default:

            break;
    };

    $email = auth()->user()->correo;
    //PAGOS PAYU

    //TESTEO
    // $ApiKey="4Vj8eK4rloUd272L48hsrarnUA";
    // $merchantId="508029";
    // $accountId="512321";
    //PRODUCCIÓN
    $ApiKey="O3znOwBaPrw93v8JN3vo3UsdGr";
    $merchantId="876242";
    $accountId="883942";

    $amount=20000;
    $currency="COP";
    $referenceCode='audeed_'.time().rand(1000,3000);
    $signature= md5($ApiKey.'~'.$merchantId.'~'.$referenceCode.'~'.$amount.'~'.$currency);

    $cuentaPrincipal = \DB::table('cuenta_principal')->where('id','=',auth()->user()->cuenta_principal_id)->first();
    if(is_null($cuentaPrincipal->plan_id))
        $suscrito = 0;
    else
        $suscrito = 1;

    $paises = \DB::table('pais')->select('id','indicativo','nombre',
        \DB::raw('CONCAT(indicativo," (",nombre,")") AS CONCATENACION')
    )->get();

    $planesArray = [];
    $planes = \DB::table('plan_parametros AS ppa')
    ->select('ppa.id','ppa.nombre','ppa.valor','ppa.plan_id','p.nombre AS NOMBRE_PLAN','p.valor AS VALOR_PLAN','p.icono')
    ->Join('plan AS p','p.id','=','ppa.plan_id')
    ->get();    

    foreach ($planes as $key => $itemPlan) 
    {
        $planesArray[$itemPlan->plan_id]['NOMBRE_PLAN'] = $itemPlan->NOMBRE_PLAN;
        $planesArray[$itemPlan->plan_id]['VALOR_PLAN'] = $itemPlan->VALOR_PLAN;
        $planesArray[$itemPlan->plan_id]['ID_PLAN'] = $itemPlan->plan_id;
        $planesArray[$itemPlan->plan_id]['ICONO'] = $itemPlan->icono;
        $planesArray[$itemPlan->plan_id]['CARACTERISTICAS'][] = array(
            'nombre' => $itemPlan->nombre,
            'valor' => $itemPlan->valor
        );
    }

    // dd($planesArray);
@endphp
<!DOCTYPE html>
<html>
    <head>
         <!-- Google Tag Manager -->
         <script type="text/javascript">
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-KM9KX2G');
        </script>
        <!-- End Google Tag Manager -->
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>Audiid</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta content="Admin Dashboard" name="description" />
        <meta content="Themesbrand" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        {{-- main_general css --}}
        <link href="{{ assets_version('/vertical/assets/css/main_general/main_general.css') }}" rel="stylesheet" type="text/css" />
        <!-- C3 charts css -->
        <link href="{{ assets_version('/vertical/assets/plugins/c3/c3.min.css') }}" rel="stylesheet" type="text/css" />
        <!--Morris Chart CSS -->
        <link rel="stylesheet" href="{{ assets_version('/vertical/assets/plugins/morris/morris.css') }}">
        <!--Animate CSS -->
        <link href="{{ assets_version('/vertical/assets/plugins/animate/animate.min.css') }}" rel="stylesheet" type="text/css">
        {{-- Select2 --}}
        <link href="{{ assets_version('/vertical/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ assets_version('/vertical/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
        <link href="{{ assets_version('/vertical/assets/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />
        <link href="{{ assets_version('/vertical/assets/plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" />
        <link href="{{ assets_version('/vertical/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
        
        <link href="{{    assets_version('/vertical/assets/js/tour/shepherd.css') }}" rel="stylesheet">
        <link href="{{    assets_version('/vertical/assets/js/tour/tour.css') }}" rel="stylesheet">
        <script type="text/javascript">
            let perfilExacto = {!! json_encode($perfilExacto) !!};
            let estaSuscrito = {!! json_encode($suscrito) !!};
            
            let ApiKey = {!! json_encode($ApiKey) !!};
            let merchantId = {!! json_encode($merchantId) !!};
            let referenceCode = {!! json_encode($referenceCode) !!};
            let currency = {!! json_encode($currency) !!};
            let emailCuentaPrincipal = {!! json_encode($email) !!};
        </script>
        @include('layouts.vertical.head')
        {{-- TOAST --}}
        <link href="{{ assets_version('/vertical/assets/plugins/toastr/css/toastr.css') }}" rel="stylesheet" />
        <script>
            function CargandoMostrar() { $('#status').fadeIn(); $('#preloader').fadeIn('slow'); }
            function CargandoNoMostrar() { $('#status').fadeOut(); $('#preloader').fadeOut('slow'); }
            function CargandoMostrarFooter() { $('.contenedorFooterLoading').removeClass('hidden'); }
            function CargandoNoMostrarFooter() { $('.contenedorFooterLoading').addClass('hidden'); }
        </script>
    </head>
{{-- <body class="fixed-left" oncontextmenu="return false" onkeydown="return false">  --}}
<body class="fixed-left">
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KM9KX2G"height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!-- Loader -->
    <div id="preloader"><div id="status"><div class="spinner"></div></div></div>
    <div id="wrapper">
        @include('layouts.vertical.header')
        <!-- Start right Content here -->
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                @include('layouts.vertical.sidebar')
            </div>
            <div class="wrapper" id="tour-dashboard">
                <div class="container-fluid">
                    @yield('section')
                </div>
            </div>

            @include('layouts.vertical.footer')
        </div>
    </div>

    {{-- <form method="post" id="formularioPayuSuscripcion" action="https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu/"> --}}
    <form method="post" id="formularioPayuSuscripcion" action="https://checkout.payulatam.com/ppp-web-gateway-payu/">
        <input name="merchantId" class="merchantId" type="hidden"  value="{{ $merchantId }}"   >
        <input name="accountId" class="accountId" type="hidden"  value="{{ $accountId }}" >
        <input name="description" class="description" type="hidden"  value="Pago de la suscripción"  >
        <input name="referenceCode" class="referenceCode" type="hidden"  value="{{ $referenceCode }}" >
        <input name="amount" class="amount" id="" type="hidden" value="{{ $amount }}">
        <input name="tax" class="tax" type="hidden"  value="0">
        <input name="taxReturnBase" class="taxReturnBase" type="hidden"  value="0">
        <input name="currency" class="currency" type="hidden"  value="{{ $currency }}">
        <input name="signature" class="signature" type="hidden"  value="{{ $signature }}">
        <input name="test" type="hidden" class="test"  value="0" >
        <input name="buyerEmail" class="buyerEmail" type="hidden"  value="sebaskyy@gmail.com" >
        <input name="responseUrl" class="responseUrl" type="hidden"  value="{{ \Request::root().'/payment/respuestaURL'}}" >
        <input name="confirmationUrl" class="confirmationUrl" type="hidden"  value="{{ \Request::root().'/payment/confirmacionURL'}}" >
        <input name="Submit" type="submit" class="m-t-10 enviarSubmit btn btn-audeed" style="display:none;" value="Suscribirme" >
    </form>

    <!--  MODAL CREAR SUSCRIPCIONES -->
<div class="modal fade bs-example-modal-lg" planActual="{{ ISSET($cuentaPrincipal->plan_id) ? $cuentaPrincipal->plan_id : 0 }}" id="popUpSuscripcion" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-lg-12">
                    <button type="button" class="close iconoCerrarPopUpPlanes hidden" data-dismiss="modal" aria-hidden="true">×</button>
                    <div class="row text-center contenedoresPlanes">

                        @foreach ($planesArray as $itemPlan)
                           
                            <div class="col-lg-3" idTarjeta="{{ $itemPlan['ID_PLAN'] }}">
                                @if ($itemPlan['ID_PLAN'] == 2)
                                    <div class="card plan-card mt-4 background-popular sombra">
                                @else
                                    <div class="card plan-card mt-4 sombra">
                                @endif
                                    <div class="card-body">
                                        <div class="pt-3 pb-3">
                                            <h1><i class="{{ $itemPlan['ICONO'] }} plan-icon"></i></h1>
                                            <h5 class="text-uppercase ">{{ $itemPlan['NOMBRE_PLAN'] }}</h5>
                                        </div>
                                        <div class="row text-center justify-content-center ">
                                            @if ($itemPlan['ID_PLAN'] == 4)
                                                <h5 class="text-uppercase " style="margin:0px">CONTÁCTANOS</h5>
                                            @else
                                                <span class="" style="font-weight: bold;font-size: 20px;">$ </span> 
                                                <h2 class="text-uppercase valorPlan" valor="{{ $itemPlan['VALOR_PLAN'] }} " style="margin:0px">{{ number_format($itemPlan['VALOR_PLAN'],0) }} COP</h2>
                                            @endif
                                        </div>
                                        
                                        <div class="plan-features pb-3 mt-3 text-muted padding-t-b-30">
                                            @foreach ($itemPlan['CARACTERISTICAS'] as $caracteristica)
                                                <p>{{ ($caracteristica['valor'] == 0 ? '' : $caracteristica['valor']).' '.$caracteristica['nombre']}} </p>
                                            @endforeach
                                            @if ($itemPlan['ID_PLAN'] == 1) 
                                                <button idSuscripcionQuemada="{{ $itemPlan['ID_PLAN'] }}" class="m-t-10 suscribirme btn btn-audeed">Suscribirme</button>    
                                            @else
                                                @if ($itemPlan['ID_PLAN'] == 4) 
                                                    <button idSuscripcionQuemada="{{ $itemPlan['ID_PLAN'] }}" class="m-t-10 suscribirmePagoUnico btn btn-audeed">Contáctanos</button>
                                                @else
                                                    <button idSuscripcionQuemada="{{ $itemPlan['ID_PLAN'] }}" class="m-t-10 suscribirmePagoUnico btn btn-audeed">Suscribirme</button>
                                                @endif
                                            @endif
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                           
                        @endforeach
                       
                    </div>
                </div> <!-- end col -->

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- MODAL CREAR SUSCRIPCIONES - FIN --}}


 <!--  MODAL CREDIT CARD -->
 <div class="modal fade bs-example-modal-lg" id="popUpCreditCard" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Información tarjeta de crédito</h5>
                <button type="button" class="close cancelarPopUpCreditCard" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <div class="col-lg-12 row">
                    <div class="container preload col-lg-6">
                        <div class="creditcard">
                            <div class="front">
                                <div id="ccsingle"></div>
                                <svg version="1.1" id="cardfront" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    x="0px" y="0px" viewBox="0 0 750 471" style="enable-background:new 0 0 750 471;" xml:space="preserve">
                                    <g id="Front">
                                        <g id="CardBackground">
                                            <g id="Page-1_1_">
                                                <g id="amex_1_">
                                                    <path id="Rectangle-1_1_" class="lightcolor grey" d="M40,0h670c22.1,0,40,17.9,40,40v391c0,22.1-17.9,40-40,40H40c-22.1,0-40-17.9-40-40V40
                                            C0,17.9,17.9,0,40,0z" />
                                                </g>
                                            </g>
                                            <path class="darkcolor greydark" d="M750,431V193.2c-217.6-57.5-556.4-13.5-750,24.9V431c0,22.1,17.9,40,40,40h670C732.1,471,750,453.1,750,431z" />
                                        </g>
                                        <text transform="matrix(1 0 0 1 60.106 295.0121)" id="svgnumber" class="st2 st3 st4">*** *** **** ****</text>
                                        <text transform="matrix(1 0 0 1 54.1064 428.1723)" id="svgname" class="st2 st5 st6">TU NOMBRE</text>
                                        <text transform="matrix(1 0 0 1 54.1074 389.8793)" class="st7 st5 st8">nombre tarjeta</text>
                                        <text transform="matrix(1 0 0 1 479.7754 388.8793)" class="st7 st5 st8">expiración</text>
                                        <text transform="matrix(1 0 0 1 65.1054 241.5)" class="st7 st5 st8">Número de tarjeta</text>
                                        <g>
                                            <text transform="matrix(1 0 0 1 574.4219 433.8095)" id="svgexpire" class="st2 st5 st9">01/23</text>
                                            <text transform="matrix(1 0 0 1 479.3848 417.0097)" class="st2 st10 st11">VALID</text>
                                            <text transform="matrix(1 0 0 1 479.3848 435.6762)" class="st2 st10 st11">THRU</text>
                                            <polygon class="st2" points="554.5,421 540.4,414.2 540.4,427.9 		" />
                                        </g>
                                        <g id="cchip">
                                            <g>
                                                <path class="st2" d="M168.1,143.6H82.9c-10.2,0-18.5-8.3-18.5-18.5V74.9c0-10.2,8.3-18.5,18.5-18.5h85.3
                                        c10.2,0,18.5,8.3,18.5,18.5v50.2C186.6,135.3,178.3,143.6,168.1,143.6z" />
                                            </g>
                                            <g>
                                                <g>
                                                    <rect x="82" y="70" class="st12" width="1.5" height="60" />
                                                </g>
                                                <g>
                                                    <rect x="167.4" y="70" class="st12" width="1.5" height="60" />
                                                </g>
                                                <g>
                                                    <path class="st12" d="M125.5,130.8c-10.2,0-18.5-8.3-18.5-18.5c0-4.6,1.7-8.9,4.7-12.3c-3-3.4-4.7-7.7-4.7-12.3
                                            c0-10.2,8.3-18.5,18.5-18.5s18.5,8.3,18.5,18.5c0,4.6-1.7,8.9-4.7,12.3c3,3.4,4.7,7.7,4.7,12.3
                                            C143.9,122.5,135.7,130.8,125.5,130.8z M125.5,70.8c-9.3,0-16.9,7.6-16.9,16.9c0,4.4,1.7,8.6,4.8,11.8l0.5,0.5l-0.5,0.5
                                            c-3.1,3.2-4.8,7.4-4.8,11.8c0,9.3,7.6,16.9,16.9,16.9s16.9-7.6,16.9-16.9c0-4.4-1.7-8.6-4.8-11.8l-0.5-0.5l0.5-0.5
                                            c3.1-3.2,4.8-7.4,4.8-11.8C142.4,78.4,134.8,70.8,125.5,70.8z" />
                                                </g>
                                                <g>
                                                    <rect x="82.8" y="82.1" class="st12" width="25.8" height="1.5" />
                                                </g>
                                                <g>
                                                    <rect x="82.8" y="117.9" class="st12" width="26.1" height="1.5" />
                                                </g>
                                                <g>
                                                    <rect x="142.4" y="82.1" class="st12" width="25.8" height="1.5" />
                                                </g>
                                                <g>
                                                    <rect x="142" y="117.9" class="st12" width="26.2" height="1.5" />
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                    <g id="Back">
                                    </g>
                                </svg>
                            </div>
                            <div class="back">
                                <svg version="1.1" id="cardback" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    x="0px" y="0px" viewBox="0 0 750 471" style="enable-background:new 0 0 750 471;" xml:space="preserve">
                                    <g id="Front">
                                        <line class="st0" x1="35.3" y1="10.4" x2="36.7" y2="11" />
                                    </g>
                                    <g id="Back">
                                        <g id="Page-1_2_">
                                            <g id="amex_2_">
                                                <path id="Rectangle-1_2_" class="darkcolor greydark" d="M40,0h670c22.1,0,40,17.9,40,40v391c0,22.1-17.9,40-40,40H40c-22.1,0-40-17.9-40-40V40
                                        C0,17.9,17.9,0,40,0z" />
                                            </g>
                                        </g>
                                        <rect y="61.6" class="st2" width="750" height="78" />
                                        <g>
                                            <path class="st3" d="M701.1,249.1H48.9c-3.3,0-6-2.7-6-6v-52.5c0-3.3,2.7-6,6-6h652.1c3.3,0,6,2.7,6,6v52.5
                                    C707.1,246.4,704.4,249.1,701.1,249.1z" />
                                            <rect x="42.9" y="198.6" class="st4" width="664.1" height="10.5" />
                                            <rect x="42.9" y="224.5" class="st4" width="664.1" height="10.5" />
                                            <path class="st5" d="M701.1,184.6H618h-8h-10v64.5h10h8h83.1c3.3,0,6-2.7,6-6v-52.5C707.1,187.3,704.4,184.6,701.1,184.6z" />
                                        </g>
                                        <text transform="matrix(1 0 0 1 621.999 227.2734)" id="svgsecurity" class="st6 st7">000</text>
                                        <g class="st8">
                                            <text transform="matrix(1 0 0 1 518.083 280.0879)" class="st9 st6 st10">CVV</text>
                                        </g>
                                        <rect x="58.1" y="378.6" class="st11" width="375.5" height="13.5" />
                                        <rect x="58.1" y="405.6" class="st11" width="421.7" height="13.5" />
                                        <text transform="matrix(1 0 0 1 59.5073 228.6099)" id="svgnameback" class="st12 st13">TU NOMBRE</text>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <form action="#" class="col-lg-6" id="formularioPayment">
                        <div class="form-container">
                            <div class="field-container">
                                <label for="nombreTarjeta">Nombre <span class="requerido">*</span></label>
                                <input id="nombreTarjeta" require maxlength="20" type="text">
                            </div>
                            <div class="field-container">
                                <label for="numeroTarjeta">Número tarjeta <span class="requerido">*</span></label>
                                {{-- <span id="generatecard">generate random</span> --}}
                                <input id="numeroTarjeta" require type="text" pattern="[0-9]*" inputmode="numeric">
                                <svg id="ccicon" class="ccicon" width="750" height="471" viewBox="0 0 750 471" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    
                                </svg>
                            </div>
                            <div class="field-container">
                                <label for="fechaExpiracion">Expiración (mm/yy) <span class="requerido">*</span></label>
                                <input id="fechaExpiracion" require type="text" pattern="[0-9]*" inputmode="numeric">
                            </div>
                            <div class="field-container">
                                <label for="codigoDeSeguridad">Código de seguridad <span class="requerido">*</span></label>
                                <input id="codigoDeSeguridad" require type="text" pattern="[0-9]*" inputmode="numeric">
                            </div>

                        </div>
                    
                    </form>

                    <label for="" class="col-lg-12">Información personal  </label>
                    <div class="col-lg-12 row">
                        <div class="field-container col-lg-3">
                            <label for="direccionUsuarioMainPage">Dirección: <span class="requerido">*</span></label>
                            <input id="direccionUsuarioMainPage" require maxlength="20" type="text" class="form-control">
                        </div>

                        <div class="field-container col-lg-3">
                            <label for="identificacionMainPage">Identificación: <span class="requerido">*</span></label>
                            <input id="identificacionMainPage" require maxlength="20" type="text" class="form-control">
                        </div>

                        <div class="field-container col-lg-3">
                            <label for="telefonoMainPage">Teléfono: <span class="requerido">*</span></label>
                            <input id="telefonoMainPage" require type="text" class="input-number form-control" >
                        </div>

                        <div class="field-container col-lg-3">
                            <label for="pais">Pais: <span class="requerido">*</span></label>
                            <select class="form-control select2 paisPopUpMainPage">
                                <option value="0">Selecciona el Pais</option>
                                @foreach ($paises as $itemPais)
                                    <option value="{{ $itemPais->id }}">{{ $itemPais->nombre }}</option>
                                 @endforeach
                            </select>
                        </div>

                        <div class="field-container col-lg-3">
                            <label for="departamento">Departamento: <span class="requerido">*</span></label>
                            <select class="form-control select2 departamentoPopUpMainPage">
                                <option value="0">Selecciona el departamento</option>
                            </select>
                        </div>

                        <div class="field-container col-lg-3">
                            <label for="ciudad">Ciudad: <span class="requerido">*</span></label>
                            <select class="form-control select2 ciudadPopUpMainPage">
                                <option value="0">Selecciona el ciudad</option>
                            </select>
                        </div>
                        
                    </div>
                    <div class="form-group col-lg-12 justify-content-end row">
                            <button type="button" class="btn btn-primary waves-effect waves-light agregarTarjetaMainPage m-r-10" accion="0">Agregar tarjeta</button>
                            <button type="button" class="btn btn-secondary waves-effect m-l-5 cancelarPopUpCreditCard">Cancelar</button>
                    </div>
                </div> <!-- end col -->

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- MODAL CREDIT CARD - FIN --}}


<!--  MODAL VIDEO TOUR -->
<div 
    class="modal fade bs-example-modal-md"  
    id="modal-tour-video" 
    tabindex="-1" role="dialog" 
    aria-labelledby="myLargeModalLabel" 
    aria-hidden="true" 
    data-backdrop="static" d
    ata-keyboard="false"
>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-lg-12">
                    {{-- <button type="button" class="close iconoCerrarPopUpPlanes hidden" data-dismiss="modal" aria-hidden="true">×</button> --}}
                    <div class="row contenedorVideoTour add-tour">
                        
                    </div>
                    <hr class="separacion-color">
                  
                    <div class="row contenedorBotones">
                        <button type="button"  class="btn btn-secondary waves-effect omitir-tour">Omitir</button>
                        <button type="button"  class="btn btn-primary waves-effect waves-light siguiente-tour" >Siguiente</button>
                    </div>
                   
                </div> <!-- end col -->

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- MODAL VIDEO TOUR - FIN --}}


    @include('layouts.vertical.footer-script')
    {{-- TOOLTIP --}}
    <script>$(function () {$('[data-toggle="tooltip"]').tooltip()})</script>

    <script src="{{ assets_version('/vertical/assets/plugins/peity-chart/jquery.peity.min.js') }}"></script>
    <!--C3 Chart-->
    <script type="text/javascript" src="{{ assets_version('/vertical/assets/plugins/d3/d3.min.js') }}"></script>
    <script type="text/javascript" src="{{ assets_version('/vertical/assets/plugins/c3/c3.min.js') }}"></script>
    <!-- Jvector Map js -->
    <script src="{{ assets_version('/vertical/assets/plugins/jvectormap/jquery-jvectormap-2.0.5.min.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/plugins/jvectormap/gdp-data.js') }}"></script>
    <!-- KNOB JS -->
    <script src="{{ assets_version('/vertical/assets/plugins/jquery-knob/excanvas.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/plugins/jquery-knob/jquery.knob.js') }}"></script>
    <!-- Page specific js -->
    <script src="{{ assets_version('/vertical/assets/pages/dashboard.js') }}"></script>
    <!--Morris Chart-->
    <script src="{{ assets_version('/vertical/assets/plugins/morris/morris.min.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/plugins/raphael/raphael-min.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/pages/morris.init.js') }}"></script>
    <!-- Chart JS -->
    <script src="{{assets_version('/vertical/assets/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{assets_version('/vertical/assets/pages/chartjs.init.js') }}"></script>

    <script src="{{assets_version('/vertical/assets/plugins/moment/moment.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ assets_version('/vertical/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ assets_version('/vertical/assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/plugins/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/plugins/datatables/buttons.colVis.min.js') }}"></script>

    {{-- <script src="{{ assets_version('/vertical/assets/js/bootstrap.bundle.min.js') }}"></script> --}}
    <script src="{{ assets_version('/vertical/assets/js/modernizr.min.js') }}"></script>

    <!-- Parsley js -->
    <script type="text/javascript" src="{{ assets_version('/vertical/assets/plugins/parsleyjs/parsley.min.js') }}"></script>

    <script src="{{ assets_version('/vertical/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets_version('/vertical/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/plugins/sweet-alert2/sweetalert2.min.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/pages/sweet-alert.init.js') }}"></script>
    <script src="{{ assets_version('/vertical/assets/js/main_general/main.js') }}"></script>
    {{-- TOASTR --}}
    <script src="{{ assets_version('/vertical/assets/plugins/toastr/js/toastr.min.js') }}"></script>
    {{-- MD5 --}}
    <script src="{{ assets_version('/vertical/assets/plugins/md5/js/md5-min.js') }}"></script>
    {{-- IMASK --}}
    <script src="{{ assets_version('/vertical/assets/plugins/imask/js/imask.min.js') }}"></script>
    <script type="text/javascript">
        var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode:"9208408520a66f144729e375db4413de32b10e8abb3070bd1f67d327dc07c946", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
    </script>

    {{-- <script src="https://cdn.jsdelivr.net/npm/shepherd.js@5.0.1/dist/js/shepherd.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/shepherd.js@7.0.2/dist/js/shepherd.min.js"></script>
    <script text="text/javascript">
        
        let usuarioId = @json(auth()->user()->id);
    </script>
    {{-- <script src="{{ assets_version('/vertical/assets/js/tour/tour.js') }}"></script> --}}
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-172320884-1"></script>
    
    <script type="text/javascript">
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        
        gtag('config', 'UA-172320884-1');
    </script>
</body>
</html>