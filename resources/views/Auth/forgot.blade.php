@extends('layouts.vertical.master-without-nav')

@section('css')
<link href="{{ assets_version('/vertical/assets/plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" />
<link href="{{ assets_version('/vertical/assets/css/main_general/main_general.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets_version('/vertical/assets/css/recuperar_password/main.css') }}" rel="stylesheet" type="text/css" />

@endsection

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
                        <p class="text-muted text-center">{{ trans('forgotmessages.subresetpassword') }}</p>
                        <form class="form-horizontal m-t-30" action="" id="formRecuperar">
                            <div class="form-group">
                                <label for="useremail">{{ trans('forgotmessages.email') }}</label>
                                <input type="text" class="form-control" id="email" required data-parsley-error-message="Debes digitar un correo valido" data-parsley-type="email" placeholder="Ingrese su correo electrónico">
                            </div>
                            <div class="form-group row m-t-20">
                                <div class="col-12 text-right">
                                    <button class="btn btn-primary w-md waves-effect waves-light recuperarPassword" type="button">Restablecer</button>
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

<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/recuperar_password/main.js') }}"></script>
@endsection

