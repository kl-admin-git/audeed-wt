@extends('layouts.vertical.master-without-nav')

@section('css')
<link href="{{ assets_version('/vertical/assets/plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" />
<link href="{{ assets_version('/vertical/assets/css/main_general/main_general.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets_version('/vertical/assets/css/recuperar_password/recuperar.css') }}" rel="stylesheet" type="text/css" />

@endsection

@php
    try 
    {
        $decrypted = decrypt(Request::segment(2));
    } catch (DecryptException $e) 
    {
        
    }
    
@endphp

@section('content')
<!-- Begin page -->
<div class="accountbg"></div>
        <div class="wrapper-page">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center m-0">
                        <a href="index.html" class="logo logo-admin"><img src="{{ URL::asset('/vertical/assets/images/only_name_audiid.png') }}" height="30" alt="logo"></a>
                    </h3>
                    <div class="p-3">
                        <h4 class="text-muted font-18 m-b-5 text-center">{{ trans('forgotmessages.resetpassword') }}</h4>
                        <p class="text-muted text-center">Ingresa tu nueva contraseña y verifícala nuevamente</p>
                        <form class="form-horizontal m-t-30" action="" id="formRecuperar">
                            <div class="form-group">
                                <label>Contraseña:</label>
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
                            <div class="form-group row m-t-20">
                                <div class="col-12 text-right">
                                    <button class="btn btn-primary w-md waves-effect waves-light guardarCambios" type="button">Restablecer contraseña</button>
                                </div>
                            </div>
                            <div class="form-group row m-t-20">
                                <div class="col-12 text-center">
                                    <li class="errorTextos hidden"></li>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="m-t-40 text-center">
                <p class="text-white">{{ trans('forgotmessages.remember') }} <a href="{{route('Login_Ruta')}}" class="font-500 font-14 text-white font-secondary">{{ trans('forgotmessages.loginhere') }}</a> </p>
                <p class="text-white">© {{date('Y')}} Audiid <i class="mdi mdi-heart" style="color:#26ae9c"></i></p>
            </div>
        </div>
        
@endsection
   
@section('script')
<!-- Parsley js -->
<script type="text/javascript" src="{{ assets_version('/vertical/assets/plugins/parsleyjs/parsley.min.js') }}"></script>
<script src="{{ assets_version('/vertical/assets/plugins/sweet-alert2/sweetalert2.min.js') }}"></script>

<script type="text/javascript">
    let idUsuario = {!! json_encode($decrypted) !!};
</script>

<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/recuperar_password/recuperar.js') }}"></script>
@endsection

