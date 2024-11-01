@extends('layouts.vertical.master-without-nav')
@section('css')
<link href="{{ assets_version('/vertical/assets/css/main_general/main_general.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets_version('/vertical/assets/css/login/main.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
 <!-- Begin page -->
        <div class="accountbg"></div>
        <div class="wrapper-page">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center m-0">
                        <a href="{{route('Login_Ruta')}}" class="logo logo-admin"><img src="{{ URL::asset('vertical/assets/images/logo_new_2023.png') }}" height="66" alt="logo"></a>
                    </h3>

                    <div class="p-3">
                        <p class="text-muted text-center">{{ trans('loginmessages.session') }}</p>

                        <form class="form-horizontal m-t-30" id="formularioLogin">

                            <div class="form-group">
                                <label for="email">{{ trans('loginmessages.username') }}</label>
                                <input type="text" class="form-control" id="email" required placeholder="{{ trans('loginmessages.inputusername') }}">
                            </div>

                            <div class="form-group">
                                <label for="userpassword">{{ trans('loginmessages.password') }}</label>
                                <input type="password" class="form-control" id="userpassword" required placeholder="{{ trans('loginmessages.inputpassword') }}">
                            </div>

                            <div class="form-group row m-t-20">
                                <div class="col-12 text-center">
                                    <li class="errorTextos hidden"></li>
                                </div>
                            </div>

                            <div class="form-group row m-t-20">
                                <div class="col-sm-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customControlInline">
                                        <label class="custom-control-label" for="customControlInline">{{ trans('loginmessages.rememberme') }}</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <button class="btn btn-primary w-md waves-effect waves-light iniciarSesion" >{{ trans('loginmessages.login') }}</button>
                                </div>
                            </div>

                            <div class="form-group m-t-10 mb-0 row">
                                <div class="col-12 m-t-20">
                                    <a href="{{route('Forgot_Ruta')}}" class="text-muted"><i class="mdi mdi-lock"></i>{{ trans('loginmessages.forgot') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

            <div class="m-t-40 text-center">
                {{-- <p class="text-white">{{ trans('loginmessages.noaccount') }} <a href="{{route('Register_Ruta')}}" class="font-500 font-14 text-white font-secondary"> {{ trans('loginmessages.register') }} </a> </p> --}}
                <p class="text-white">© {{date('Y')}} Audiid <i class="mdi mdi-heart" style="color:#26ae9c"></i></p>
            </div>

        </div>
@endsection

@section('script')
<script type="text/javascript">
    var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode:"9208408520a66f144729e375db4413de32b10e8abb3070bd1f67d327dc07c946", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
</script>
<!-- Parsley js -->
<script type="text/javascript" src="{{ assets_version('/vertical/assets/plugins/parsleyjs/parsley.min.js') }}"></script>

<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/login/main.js') }}"></script>
@endsection