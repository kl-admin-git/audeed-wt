@extends('template.baseVertical')

@section('css')
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/administracion/empresas/main.css') }}">
@endsection

@section('breadcrumb')
<h3 class="page-title">Empresas</h1>
@endsection

@section('section')


<div class="row datosUsuario" idCuentaPrincipal="{{ auth()->user()->cuenta_principal_id }}">
    <div class="col-12">
        <div class="col-lg-12">
            <div class="row m-b-10">
                <div class="col-lg-12">
                    <div class="contenedorBuscador">
                        <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" id="buscar-tour">Buscar  <i class="fa" aria-hidden="true"></i></button> 

                        <button type="button" class="btn btn-primary waves-effect waves-light" id="crearEmpresa">{{ trans('empresasmessages.buttoncrearempresa') }}</button>
                    </div>
                    <div class="col-lg-12 m-t-10">
                        <div class="collapse" id="collapseExample">
                            
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select class="form-control select2 selectSearch empresaSearch">
                                                    <option value="">Buscar por empresa</option>
                                                    @foreach ($empresas as $itemEmpresa)
                                                        <option value="{{ $itemEmpresa->nombre }}">{{ $itemEmpresa->nombre }}</option>
                                                     @endforeach
                                                </select>
                                            </div>
                                        </div>
    
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select class="form-control select2 selectSearch nitSearch">
                                                    <option value="">Buscar por NIT</option>
                                                    @foreach ($empresas as $itemEmpresaNit)
                                                        @if (ISSET($itemEmpresaNit->identificacion))
                                                            <option value="{{ $itemEmpresaNit->identificacion }}">{{ $itemEmpresaNit->identificacion }}</option>    
                                                        @endif
                                                     @endforeach
                                                </select>
                                            </div>
                                        </div>
    
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select class="form-control select2 selectSearch direccionSearch">
                                                    <option value="">Buscar por dirección</option>
                                                    @foreach ($empresas as $itemEmpresaDireccion)
                                                    @if (ISSET($itemEmpresaDireccion->direccion))
                                                        <option value="{{ $itemEmpresaDireccion->direccion }}">{{ $itemEmpresaDireccion->direccion }}</option>
                                                    @endif
                                                     @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select class="form-control select2 selectSearch paisSearch">
                                                    <option value="">Buscar por pais</option>
                                                    @foreach ($paises as $itemPaisSearch)
                                                        <option value="{{ $itemPaisSearch->id }}">{{ $itemPaisSearch->nombre }}</option>
                                                     @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select class="form-control select2 selectSearch responsableSearch">
                                                    <option value="">Buscar por responsable</option>
                                                    @foreach ($usuariosResponsables as $itemUsuario)
                                                    @if (ISSET($itemUsuario->nombre_completo))
                                                        <option value="{{ $itemUsuario->id }}">{{ $itemUsuario->nombre_completo }}</option>
                                                    @endif
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
                <div class="row contenedorEmpresas">
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

 <!--  MODAL CREAR EMPRESA -->
 <div class="modal fade bs-example-modal-lg" id="crearEmpresaPopUp" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">{{ trans('empresasmessages.modalcrearempresa') }}</h5>
                <button type="button" class="close cancelarPopUp" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body">

                            <p class="text-muted m-b-30 font-14">{{ trans('empresasmessages.modalsubtitulomensaje') }}</p>

                            <form class="" id="formularioCreacionEmpresa" action="#">
                                <div class="row">
                                    
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ trans('empresasmessages.modalcajanombreempresa') }}: <span class="requerido">*</span></label>
                                            <input type="text" class="form-control nombreEmpresaPopUp" required placeholder="{{ trans('empresasmessages.modalcajaplaceholderempresa') }}"/>
                                        </div>
        
                                        <div class="form-group">
                                            <label>{{ trans('empresasmessages.modalcajanit') }}: </label>
                                            <input type="text" class="form-control nitPopUp"  placeholder="{{ trans('empresasmessages.modalcajaplaceholdernit') }}"/>
                                        </div>
        
                                        <div class="form-group">
                                            <label>{{ trans('empresasmessages.modalcajacorreo') }}: </label>
                                            <input type="email" class="form-control corrreoPopUp"  data-parsley-error-message="Debes digitar un correo valido" data-parsley-type="email" placeholder="{{ trans('empresasmessages.modalcajaplaceholdercorreo') }}"/>
                                        </div>
        
                                        <div class="form-group">
                                            <label>{{ trans('empresasmessages.modalcajadireccion') }}: </label>
                                            <input type="text" class="form-control direccionPopUp"  placeholder="{{ trans('empresasmessages.modalcajaplaceholdireccion') }}"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Teléfono:</label>
                                            <input type="text" class="form-control input-number" id="telefono" placeholder="Número de teléfono">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Sector: <span class="requerido">*</span></label>
                                            <select class="form-control select2 selects sectorPopUp">
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
                                        
                                        <div class="form-group">
                                            <label>{{ trans('empresasmessages.modalcajapais') }}:</label>
                                            <select class="form-control select2 selects paisPopUp">
                                                <option value="0">Selecciona el Pais</option>
                                                @foreach ($paises as $itemPais)
                                                    <option value="{{ $itemPais->id }}">{{ $itemPais->nombre }}</option>
                                                 @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('empresasmessages.modalcajadepartamento') }}:</label>
                                            <select class="form-control select2 selects departamentoPopUp">
                                                <option value="0">Selecciona el departamento</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('empresasmessages.modalcajaciudad') }}:</label>
                                            <select class="form-control select2 selects ciudadPopUp">
                                                <option value="0">Selecciona el ciudad</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Usuario responsable:</label>
                                            <select class="form-control select2 select2Simple usuarioPopUp">
                                                <option value="0">Selecciona el responsable</option>
                                                @foreach ($usuariosPopUp as $responsable)
                                                    <option value="{{ $responsable->id }}">{{ $responsable->nombre_completo }}</option>
                                                 @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('empresasmessages.modalcajalogoempresa') }}:</label>
                                            <input id="logoEmpresarial" type="file" data-buttonText="Seleccionar" accept="image/*" class="filestyle" data-buttonname="btn-primary col-lg-12" value="ja">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group m-b-0">
                                    <div class="contenedorBotonesCreacion">
                                        <button type="button" class="btn btn-primary waves-effect waves-light crearEmpresa" accion="0">Crear empresa</button>
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
{{-- MODAL CREAR EMPRESA - FIN --}}

@endsection

@section('script')
<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/administracion/empresas/main.js') }}"></script>
@endsection