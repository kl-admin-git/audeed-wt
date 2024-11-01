@extends('layouts.vertical.master-without-nav')

@section('css')
<link href="{{ assets_version('/vertical/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets_version('/vertical/assets/css/main_general/main_general.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets_version('/vertical/assets/css/registrar/main.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets_version('/vertical/assets/plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" />
<link href="{{ assets_version('/vertical/assets/plugins/animate/animate.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@php
    
    if(\Request::has('correo')){
        $correo = \Request::get('correo');
    }else{
        $correo = "";
    }
@endphp
@section('content')
    <!-- Begin page -->
        <div class="accountbg"></div>
        <div class="wrapper-page">

            <div class="card">
                <div class="card-body">
                    <h3 class="text-center m-0">
                        <a href="index" class="logo logo-admin"><img src="{{ URL::asset('/vertical/assets/images/only_name_audiid.png') }}" height="30" alt="logo"></a>
                    </h3>

                    <div class="p-3">
                        <h4 class="text-muted font-18 m-b-5 text-center">{{ trans('registermessages.register') }}</h4>
                        <p class="text-muted text-center">{{ trans('registermessages.subregister') }}</p>

                        <form class="form-horizontal m-t-30" id="formularioRegistrar">

                            <div class="form-group">
                                <label for="inputCorreo">{{ trans('registermessages.email') }}: <span class="requerido">*</span></label>
                                <input type="email" class="form-control" id="inputCorreo" required data-parsley-error-message="Debes digitar un correo valido" data-parsley-type="email" placeholder="Ingrese su correo electrónico" value="{{$correo}}">
                            </div>

                            <div class="form-group">
                                <label>Contraseña: <span class="requerido">*</span></label>
                                <div>
                                    <input type="password" id="paswordRegistro" class="form-control" required
                                    data-parsley-minlength="8" data-parsley-minlength-message = "El valor es muy corto. Debería tener 8 caracteres o más." placeholder="Contraseña"/>
                                </div>
                                <div class="m-t-10">
                                    <input type="password" class="form-control" required
                                           data-parsley-equalto="#paswordRegistro" data-parsley-minlength="8" data-parsley-minlength-message = "El valor es muy corto. Debería tener 8 caracteres o más."
                                           placeholder="Vuelva a escribir la contraseña"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Sector: <span class="requerido">*</span></label>
                                <select class="form-control select2 sectorControl">
                                    <option value="0">Selecciona el sector</option>
                                    @foreach ($sectores as $itemSector)
                                        <option value="{{ $itemSector->id }}">{{ $itemSector->nombre }}</option>
                                    @endforeach
                                </select>
                                <div class="form-group row">
                                    <div class="col-12 text-center">
                                        <li class="errorTextos text-left errorSector hidden">Este campo es requerido</li>
                                    </div>
                                </div>
                            </div>

                            <label>Teléfono:</label>
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <select class="form-control select2" id="paisCode">
                                            @foreach ($paises as $itemPais)
                                                <option value="{{ $itemPais->indicativo }}">{{ $itemPais->CONCATENACION }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-9">
                                    <div class="form-group">
                                        <input type="text" class="form-control input-number" id="telefono" placeholder="Número de celular">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row m-t-20">
                                <div class="col-12 text-center">
                                    <li class="errorTextos errorServer hidden"></li>
                                </div>
                            </div>

                            <div class="form-group row m-t-20">
                                <div class="col-12 text-right">
                                    <button class="btn btn-primary w-md waves-effect waves-light registrarme">{{ trans('registermessages.registerbutton') }}</button>
                                </div>
                            </div>    
                            
                            <div class="form-group m-t-10 mb-0 row">
                                <div class="col-12 m-t-20">
                                    <p class="font-14 text-muted mb-0">{{ trans('registermessages.terms') }} <a href="#">{{ trans('registermessages.termsuse') }}</a></p>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

            <div class="m-t-40 text-center">
                <p class="text-white">{{ trans('registermessages.already') }} <a href="{{route('Login_Ruta')}}" class="font-500 font-14 text-white font-secondary"> {{ trans('registermessages.login') }} </a> </p>
                <p class="text-white">© {{date('Y')}} Audiid <i class="mdi mdi-heart" style="color:#26ae9c"></i></p>
            </div>


        
        </div>

<!--  MODAL MENSAJE  -->
<div class="modal fade bs-example-modal-lg" id="modal-mensaje" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0"> !Bienvenido!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p class="m-b-30">
                    Estás a un paso de finalizar el proceso de registro, ingresa contraseña, sector y número de contacto para terminar.
                </p>
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- MODAL MENSAJE --}}

@endsection

@section('script')
<!-- Parsley js -->
<script type="text/javascript" src="{{ assets_version('/vertical/assets/plugins/parsleyjs/parsley.min.js') }}"></script>

<script src="{{ assets_version('/vertical/assets/js/main_general/main.js') }}"></script>
<script src="{{ assets_version('/vertical/assets/plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/registrar/main.js') }}"></script>
<script src="{{ assets_version('/vertical/assets/plugins/sweet-alert2/sweetalert2.min.js') }}"></script>
<script type="text/javascript">
    var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode:"9208408520a66f144729e375db4413de32b10e8abb3070bd1f67d327dc07c946", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
</script>

<script type="text/javascript">
    let correo = @json($correo);
    $(document).ready(function () {
        console.log(correo)
        if (correo !== '') {
            // $('#modal-mensaje').modal('show')
            Swal.fire({
                title: 'Bienvenido !!',
                text: 'Estás a un paso de finalizar el proceso de registro, ingresa contraseña, sector y número de contacto para terminar.',
                imageUrl: '/vertical/assets/images/logo_new_2023.png',
                confirmButtonText:'<i class="fa fa-thumbs-up"></i> Continuar',
                confirmButtonClass: "btn-success",
            }) 
        }
    });
</script>
@endsection


