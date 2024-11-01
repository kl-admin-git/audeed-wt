@extends('template.baseVertical')
@php
$perfilExacto = 1;
$clase="";
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
{
$perfilExacto = 3;
$clase = 'hidden';
}


if(is_null($esResponsableEmpresa) && is_null($esResponsableEstablecimiento))
{
$perfilExacto = 4;
$clase = 'hidden';
}


break;

default:

break;
};
@endphp
@section('css')
    <link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/administracion/establecimientos/main.css') }}">
@endsection

@section('breadcrumb')
    <h3 class="page-title">Establecimientos</h1>
    @endsection

    @section('section')

        <div class="row datosUsuario" idCuentaPrincipal="{{ auth()->user()->cuenta_principal_id }}">
            <div class="col-12">
                <div class="col-lg-12">
                    <div class="row m-b-10">
                        <div class="col-lg-12">
                            <div class="contenedorBuscador">
                                <button type="button" class="btn btn-primary" data-toggle="collapse"
                                    data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"
                                    id="buscar-tour">Buscar <i class="fa" aria-hidden="true"></i></button>
                                {{-- ADMINISTRADOR O R.EMPRESA
                                --}}
                                @if ($perfilExacto == 1 || $perfilExacto == 2)
                                    <button type="button" class="btn btn-primary waves-effect waves-light"
                                        id="crearEstablecimiento">{{ trans('empresasmessages.buttoncrearempresa') }}</button>
                                @endif

                            </div>
                            <div class="col-lg-12 m-t-10">
                                <div class="collapse" id="collapseExample">

                                    <div class="card card-body">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <select class="form-control select2 selectSearch establecimientoSearch">
                                                        <option value="">Buscar por nombre establecimiento</option>
                                                        @foreach ($establecimientos as $itemEstablecimiento)
                                                            <option value="{{ $itemEstablecimiento->nombre }}">
                                                                {{ $itemEstablecimiento->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <select class="form-control select2 selectSearch codigoSearch">
                                                        <option value="">Buscar por código</option>
                                                        @foreach ($establecimientos as $itemCodigo)
                                                            @if (isset($itemCodigo->codigo))
                                                                <option value="{{ $itemCodigo->codigo }}">
                                                                    {{ $itemCodigo->codigo }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <select class="form-control select2 selectSearch correoSearch">
                                                        <option value="">Buscar por correo electrónico</option>
                                                        @foreach ($establecimientos as $itemCorreo)
                                                            @if (isset($itemCorreo->correo))
                                                                <option value="{{ $itemCorreo->correo }}">
                                                                    {{ $itemCorreo->correo }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <select class="form-control select2 selectSearch ciudadSearch">
                                                        <option value="">Buscar por ciudad</option>
                                                        @foreach ($ciudades as $itemCiudad)
                                                            @if (isset($itemCiudad->nombre))
                                                                <option value="{{ $itemCiudad->id }}">
                                                                    {{ $itemCiudad->nombre }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 {{ $clase }} ">
                                                <div class="form-group">
                                                    <select class="form-control select2 selectSearch empresaSearch">
                                                        <option value="">Buscar por empresa</option>
                                                        @foreach ($empresas as $itemEmpresa)
                                                            <option value="{{ $itemEmpresa->id }}">
                                                                {{ $itemEmpresa->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 {{ $clase }} ">
                                                <div class="form-group">
                                                    <select class="form-control select2 selectSearch zonaSearch">
                                                        <option value="">Buscar por zona</option>
                                                        @foreach ($zonas as $zona)
                                                            <option value="{{ $zona->id }}">
                                                                {{ $zona->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <select class="form-control select2 selectSearch responsableSearch">
                                                        <option value="">Buscar por responsable</option>
                                                        @foreach ($usuariosResponsables as $itemUsuario)
                                                            @if (isset($itemUsuario->nombre_completo))
                                                                <option value="{{ $itemUsuario->id }}">
                                                                    {{ $itemUsuario->nombre_completo }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <button type="button"
                                                        class="btn btn-primary waves-effect waves-light buscarBoton"><i
                                                            class="fa fa-search"></i> Buscar</button>
                                                    <button type="button"
                                                        class="btn btn-primary waves-effect waves-light restablecerBoton"><i
                                                            class="mdi mdi-autorenew"></i> Restablecer</button>
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
                        <div class="row contenedorEstablecimientos">
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

        <!--  MODAL CREAR ESTABLECIMIENTO -->
        <div class="modal fade bs-example-modal-lg" id="crearEstablecimientoPopUp" tabindex="-1" role="dialog"
            aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0">Creación de establecimiento</h5>
                        <button type="button" class="close cancelarPopUp" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">

                        <div class="col-lg-12">
                            <div class="card m-b-20">
                                <div class="card-body">

                                    <p class="text-muted m-b-30 font-14">Recuerda que para nosotros es muy importante que
                                        puedas diligenciar toda la información presente aunque algunos campos sean
                                        opcionales</p>

                                    <form class="" id="formularioCreacionEstablecimiento" action="#">
                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Nombre: <span class="requerido">*</span></label>
                                                    <input type="text" class="form-control nombreEstablecimientoPopUp"
                                                        required placeholder="Ingrese el nombre del establecimiento" />
                                                </div>

                                                <div class="form-group">
                                                    <label>Código: </label>
                                                    <input type="text" class="form-control codigoPopUp"
                                                        placeholder="Ingrese el código del establecimiento" />
                                                </div>

                                                <div class="form-group">
                                                    <label>Correo electrónico: </label>
                                                    <input type="email" class="form-control corrreoPopUp"
                                                        data-parsley-error-message="Debes digitar un correo valido"
                                                        data-parsley-type="email"
                                                        placeholder="Ingrese el correo del establecimiento" />
                                                </div>

                                                <div class="form-group">
                                                    <label>Dirección: </label>
                                                    <input type="text" class="form-control direccionPopUp"
                                                        placeholder="Ingrese la dirección del establecimiento" />
                                                </div>
                                                <div class="form-group">
                                                    <label>Teléfono:</label>
                                                    <input type="text" class="form-control input-number" id="telefono"
                                                        placeholder="Número de teléfono">
                                                </div>

                                                <div class="form-group">
                                                    <label>Usuario responsable:</label>
                                                    <select class="form-control select2 selects usuarioPopUp">
                                                        <option value="0">Selecciona el responsable</option>
                                                        @foreach ($usuariosPopUp as $responsable)
                                                            <option value="{{ $responsable->id }}">
                                                                {{ $responsable->nombre_completo }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">

                                                <div class="form-group">
                                                    <label>Empresa: <span class="requerido">*</span></label>
                                                    <select class="form-control select2 selects empresaPopUp">
                                                        <option value="0">Selecciona la empresa</option>
                                                        @foreach ($empresas as $itemEmpresa)
                                                            <option value="{{ $itemEmpresa->id }}">
                                                                {{ $itemEmpresa->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="form-group row">
                                                        <div class="col-12 text-center">
                                                            <li class="errorTextos text-left errorEmpresa hidden">Este campo
                                                                es requerido</li>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Pais:</label>
                                                    <select class="form-control select2 selects paisPopUp">
                                                        <option value="0">Selecciona el Pais</option>
                                                        @foreach ($paises as $itemPais)
                                                            <option value="{{ $itemPais->id }}">{{ $itemPais->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Departamento:</label>
                                                    <select class="form-control select2 selects departamentoPopUp">
                                                        <option value="0">Selecciona el departamento</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Ciudad:</label>
                                                    <select class="form-control select2 selects ciudadPopUp">
                                                        <option value="0">Selecciona el ciudad</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Zona: </label>
                                                    <select class="form-control select2 selects zonaPopUp">
                                                        <option value="0">Selecciona la zona</option>
                                                        @foreach ($zonas as $zona)
                                                            <option value="{{ $zona->id }}">
                                                                {{ $zona->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                            </div>
                                        </div>

                                        <div class="form-group m-b-0">
                                            <div class="contenedorBotonesCreacion">
                                                <button type="button"
                                                    class="btn btn-primary waves-effect waves-light crearEstablecimiento"
                                                    accion="0">Crear establecimiento</button>
                                                <button type="button"
                                                    class="btn btn-secondary waves-effect m-l-5 cancelarPopUp">Cancelar</button>
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
        {{-- MODAL CREAR ESTABLECIMIENTO - FIN --}}

          {{-- MODAL DE USUARIOS --}}
          <div class="modal fade" id="verUsuarios" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Colaboradores</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Perfil</th>
                                    <th scope="col">Cargo</th>
                                </tr>
                            </thead>
                            <tbody style="text-align: center;" id="tdatos">
                                {{-- Agrego datos por Jquery --}}
                            </tbody>
                        </table>
                        <div class="col-lg-12">
                            <nav aria-label="..." class="menu">
                                <ul class="pagination pagination-sm justify-content-center">
    
                                </ul>
                              </nav>
                        </div>
                    </div>
                   
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- FIN MODAL DE USUARIOS --}}

    @endsection

    @section('script')
        <script type="text/javascript"
            src="{{ assets_version('/vertical/assets/js/administracion/establecimientos/main.js') }}"></script>
    @endsection
