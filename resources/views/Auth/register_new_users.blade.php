@extends('layouts.vertical.master-without-nav')

@section('css')
<link href="{{ assets_version('/vertical/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets_version('/vertical/assets/css/main_general/main_general.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets_version('/vertical/assets/css/registrar_nuevo_usuario/main.css') }}" rel="stylesheet" type="text/css" />
@endsection

@php
    try 
    {
        $idCuentaPrincipal = decrypt(Request::segment(2));
        $idListaChequeo = decrypt(Request::segment(3));

        // $idCuentaPrincipal = Request::segment(2);
        // $idListaChequeo = Request::segment(3);
        // dd($idCuentaPrincipal);

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
                        <a href="index" class="logo logo-admin"><img src="{{ URL::asset('/vertical/assets/images/only_name_audiid.png') }}" height="30" alt="logo"></a>
                    </h3>

                    <div class="p-3">
                        <h4 class="text-muted font-18 m-b-5 text-center">Registro de colaboradores</h4>
                        <p class="text-muted text-center">{{ trans('registermessages.subregister') }}</p>

                        <form class="form-horizontal m-t-30" id="formularioRegistrar">

                            <div class="form-group">
                                <label for="inputCorreo">Nombre completo: <span class="requerido">*</span></label>
                                <input type="text" class="form-control" required id="inputNombreCompleto" placeholder="Ingrese su nombre completo">
                            </div>

                            <div class="form-group">
                                <label for="inputCorreo">{{ trans('registermessages.email') }}: <span class="requerido">*</span></label>
                                <input type="email" class="form-control" id="inputCorreo" required data-parsley-error-message="Debes digitar un correo valido" data-parsley-type="email" placeholder="Ingrese su correo electrónico">
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
                                <label>Empresa: <span class="requerido">*</span></label>
                                <select class="form-control select2 empresaControl">
                                    @foreach ($empresas as $itemEmpresa)
                                        <option value="{{ $itemEmpresa->id }}">{{ $itemEmpresa->nombre }}</option>
                                    @endforeach
                                </select>
                                <div class="form-group row">
                                    <div class="col-12 text-center">
                                        <li class="errorTextos text-left errorEmpresa hidden">Este campo es requerido</li>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Establecimiento: <span class="requerido">*</span></label>
                                <select class="form-control select2 establecimientoControl">    
                                    <option value="0">Seleccione el establecimiento</option>                                
                                    {{-- @foreach ($sectores as $itemSector)
                                        <option value="{{ $itemSector->id }}">{{ $itemSector->nombre }}</option>
                                    @endforeach --}}
                                </select>
                                <div class="form-group row">
                                    <div class="col-12 text-center">
                                        <li class="errorTextos text-left errorEstablecimiento hidden">Este campo es requerido</li>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row m-t-20">
                                <div class="col-12 text-center">
                                    <li class="errorTextos errorServirdor hidden"></li>
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

@endsection

@section('script')
<!-- Parsley js -->
<script type="text/javascript" src="{{ assets_version('/vertical/assets/plugins/parsleyjs/parsley.min.js') }}"></script>

<script src="{{ assets_version('/vertical/assets/js/main_general/main.js') }}"></script>
<script src="{{ assets_version('/vertical/assets/plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    let idCuentaPrincipal = {!! json_encode($idCuentaPrincipal) !!};
    let idListaChequeo = {!! json_encode($idListaChequeo) !!};
</script>
<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/registrar_nuevo_usuario/main.js') }}"></script>

@endsection

