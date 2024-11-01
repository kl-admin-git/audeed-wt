@extends('template.baseVertical')

@section('css')
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/administracion/empresas/directorio.css') }}">
@endsection

@section('breadcrumb')
<h3 class="page-title">Directorio colaboradores</h1>
@endsection

@section('section')

<div class="row datosUsuario" idCuentaPrincipal="{{ auth()->user()->cuenta_principal_id }}" idEmpresa="{{ Request::segment(4) }}" idUsuario="{{ auth()->user()->id }}">
    <div class="col-12">
        <div class="col-lg-12">
            <div class="row m-b-10">
                <div class="col-lg-12">
                    <div class="contenedorBuscador">
                        <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Buscar  <i class="fa" aria-hidden="true"></i></button> 

                        <a href="{{ route('Admin_Company') }}" class="btn btn-primary waves-effect waves-light">Regresar</a>
                        {{-- <button type="button" class="btn btn-primary waves-effect waves-light" id="crearUsuario">{{ trans('usuariosmessages.buttoncrearusuario') }}</button> --}}
                    </div>
                    <div class="col-lg-12 m-t-10">
                        <div class="collapse" id="collapseExample">
                            
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-lg-3 hidden">
                                            <div class="form-group ">
                                                <select class="form-control select2 selectSearch usuarioSearch">
                                                    <option value="">Buscar por nombre usuario</option>
                                                    @foreach ($usuario as $itemUsuario)
                                                        <option value="{{ $itemUsuario->nombre }}">{{ $itemUsuario->nombre }}</option>
                                                     @endforeach
                                                </select>
                                            </div>
                                        </div>
    
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select class="form-control select2 selectSearch correoSearch">
                                                    <option value="">Buscar por correo electrónico</option>
                                                    @foreach ($usuario as $itemCorreo)
                                                        <option value="{{ $itemCorreo->correo }}">{{ $itemCorreo->correo }}</option>
                                                     @endforeach
                                                </select>
                                            </div>
                                        </div>
    
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select class="form-control select2 selectSearch cargoSearch">
                                                    <option value="">Buscar por cargo</option>
                                                    @foreach ($usuario as $itemCargo)
                                                        @if (!is_null($itemCargo->cargo))
                                                            <option value="{{ $itemCargo->cargo }}">{{ $itemCargo->cargo }}</option>    
                                                        @endif
                                                        
                                                     @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select class="form-control select2 selectSearch paisSearch">
                                                    <option value="">Buscar por pais</option>
                                                    @foreach ($paises as $itemPais)
                                                        <option value="{{ $itemPais->id }}">{{ $itemPais->nombre }}</option>
                                                     @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-primary waves-effect waves-light buscarBoton"><i class="fa fa-search"></i> Buscar</button>
                                                <button type="button" class="btn btn-primary waves-effect waves-light restablecerBoton"><i class="mdi mdi-autorenew"></i> Restablecer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-content-wrapper mb-5">
            <div class="container-fluid">
                <div class="row contenedorUsuario">
                    {{-- CARGADA POR JS --}}
                </div> <!-- end row -->
                <div class="contenedorFooterLoading hidden">
                    <div id="activity">
                        <div class=indicator>
                          <div class="segment"></div>
                          <div class="segment"></div>
                          <div class="segment"></div>
                          <div class="segment"></div>
                          <div class="segment"></div>
                          <div class="segment"></div>
                          <div class="segment"></div>
                          <div class="segment"></div>
                          <div class="segment"></div>
                          <div class="segment"></div>
                          <div class="segment"></div>
                          <div class="segment"></div>
                        </div>
                      </div>
                </div>
            </div><!-- container -->
        </div> <!-- Page content Wrapper -->

    </div> <!-- end col -->
</div> <!-- end row -->

<div id="main_no_data" class="hidden">
    <div class="fof">
            <h1>No hay información para mostrar</h1>
    </div>
</div>

 <!--  MODAL CREAR USUARIO -->
 <div class="modal fade bs-example-modal-lg" id="crearUsuarioPopUp" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">{{ trans('usuariosmessages.modalcrearusuario') }}</h5>
                <button type="button" class="close cancelarPopUp" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body">

                            <p class="text-muted m-b-30 font-14">{{ trans('usuariosmessages.modalsubtitulomensaje') }}</p>

                            <form class="" id="formularioCreacion" action="#">
                                <div class="row">
                                    
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Nombre completo: <span class="requerido">*</span></label>
                                            <input type="text" class="form-control nombrePopUp" required placeholder="Ingresa tú nombre completo"/>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajaperfil') }}: <span class="requerido">*</span></label>
                                            <select class="form-control select2 perfilPopUp">
                                                <option value="">Selecciona el perfil</option>
                                                @foreach ($perfiles as $itemPerfil)
                                                    <option value="{{ $itemPerfil->id }}">{{ $itemPerfil->nombre }}</option>
                                                 @endforeach
                                            </select>
                                            <div class="form-group row">
                                                <div class="col-12 text-center">
                                                    <li class="errorTextos text-left errorPerfil hidden">Este campo es requerido</li>
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajacorreo') }}: <span class="requerido">*</span></label>
                                            <input type="text" class="form-control corrreoPopUp" required  data-parsley-error-message="Debes digitar un correo valido" data-parsley-type="email" placeholder="{{ trans('usuariosmessages.modalcajaplaceholdercorreo') }}"/>
                                        </div>
        
                                        {{-- <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajausuario') }}: <span class="requerido">*</span></label>
                                            <input type="text" class="form-control usuarioPopUp" required placeholder="{{ trans('usuariosmessages.modalcajaplaceholderusuario') }}"/>
                                        </div> --}}

                                        <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajacargo') }}:</label>

                                            <select class="form-control select2 cargoPopUp">
                                                <option value="0">Selecciona el cargo</option>
                                                @foreach ($cargos as $itemCargo)
                                                    <option value="{{ $itemCargo->id }}">{{ $itemCargo->nombre }}</option>
                                                 @endforeach
                                            </select>
                                            
                                        </div>
                                    </div>

                                    <div class="col-lg-6">

                                        {{-- <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajaapellidos') }}: <span class="requerido">*</span></label>
                                            <input type="text" class="form-control apellidosPopUp" required placeholder="{{ trans('usuariosmessages.modalcajaplaceholderapellidos') }}"/>
                                        </div> --}}

                                        <div class="form-group">
                                            <label>Identificación: </label>
                                            <input type="text" class="form-control identificacionPopUp" placeholder="Ingresa tu identificación"/>
                                        </div>
        
                                        <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajatelefono') }}:</label>
                                            <input type="text" class="form-control telefonoPopUp input-number" data-parsley-type="integer" data-parsley-error-message="Debes digitar un número valido" placeholder="{{ trans('usuariosmessages.modalcajaplaceholdertelefono') }}"/>
                                        </div>
        
                                        <div class="form-group">
                                            <label>Establecimiento: <span class="requerido">*</span></label>
                                            <select class="form-control select2 establecimientoPopUp">
                                                <option value="0">Selecciona el establecimiento</option>
                                                @foreach ($establecimientos as $itemEstablecimiento)
                                                    @if (!is_null($itemEstablecimiento))
                                                        <option value="{{ $itemEstablecimiento->id }}">{{ $itemEstablecimiento->nombre }}</option>    
                                                    @endif
                                                 @endforeach
                                            </select>
                                            <div class="form-group row">
                                                <div class="col-12 text-center">
                                                    <li class="errorTextos text-left errorEstablecimiento hidden">Este campo es requerido</li>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Contraseña: <span class="requerido requeridoPassword">*</span></label>
                                            <div>
                                                <input type="password" id="passwordUsuario" class="form-control" required
                                                       placeholder="Contraseña"/>
                                            </div>
                                            <div class="m-t-10">
                                                <input type="password" id="confirmPassword" class="form-control" required
                                                       data-parsley-equalto="#passwordUsuario"
                                                       placeholder="Vuelva a escribir la contraseña"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('usuariosmessages.modalcajafotoperfil') }}:</label>
                                            <input id="avatarUsuario" type="file" data-buttonText="Seleccionar" accept="image/*" class="filestyle" data-buttonname="btn-primary col-lg-12" value="ja">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group m-b-0">
                                    <div class="contenedorBotonesCreacion">
                                        <button type="button" class="btn btn-primary waves-effect waves-light crearUsuario" accion="0">Crear usuario</button>
                                        <button type="button" class="btn btn-secondary waves-effect m-l-5 cancelarPopUp">Cancelar</button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
                </div> <!-- end col -->

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- MODAL CREAR USUARIO - FIN --}}

@endsection

@section('script')
<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/administracion/empresas/directorio.js') }}"></script>
@endsection