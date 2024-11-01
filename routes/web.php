<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| Palabra Biblica de Audeed
|--------------------------------------------------------------------------
|
|   Antes bien, como está escrito:
|    Cosas que ojo no vio, ni oído oyó,
|    Ni han subido en corazón de hombre,
|    Son las que Dios ha preparado para los que le aman
|                                                   1 Corintios 2:9
*/

Auth::routes();
// SECCIÓN INICIAL LOGIN
Route::get('/', ['as' => 'Login_Ruta', 'uses' => 'Auth\LoginController@Index']);
Route::post('/autenticacion', ['as' => 'Login_Authentication', 'uses' => 'Auth\LoginController@Autenticacion']);
Route::get('logout',['as' => 'Logout', 'uses' => 'Auth\LoginController@logout']);

// SECCIÓN DASHBOARD
Route::get('/dashboard', ['as' => 'Dasboard_Ruta', 'uses' => 'Admin\DashController@Index']);
Route::post('/dashboard/traerSecciones', ['as' => 'Get_Data_One_Section', 'uses' => 'Admin\DashController@DatosSecciones']);


// OLVIDASTE CONTRASEÑA
Route::get('/forgot', ['as' => 'Forgot_Ruta', 'uses' => 'Auth\ForgotPasswordController@Index']);
Route::post('/recuperarPassword', ['as' => 'Recovery_Passwrod', 'uses' => 'Auth\ForgotPasswordController@RecuperarPassword']);
Route::get('/recuperarPassword/{idPassword}', ['as' => 'Recovery_Password_Change', 'uses' => 'Auth\ForgotPasswordController@IndexNuevoPassword']);
Route::post('/cambiarPassword', ['as' => 'Update_Password', 'uses' => 'Auth\ForgotPasswordController@ActualizarPassword']);

// REGISTRAR CUENTA
Route::get('/register', ['as' => 'Register_Ruta', 'uses' => 'Auth\RegisterController@Index']);
Route::post('/registro_cuenta', ['as' => 'Register_Acount', 'uses' => 'Auth\RegisterController@RegistrarCuenta']);
Route::get('/registro_colaborador/{idCuentaPrincipal}/{idListaChequeo}', ['as' => 'Register_New_Users', 'uses' => 'Auth\RegisterController@IndexColaborador']);
Route::post('/registro_colaborador/cambioEmpresas', ['as' => 'Register_Changes_Company', 'uses' => 'Auth\RegisterController@CambioDeEmpresas']);
Route::post('/registro_colaborador/registrarCuentaColaborador', ['as' => 'Register_New_User', 'uses' => 'Auth\RegisterController@RegistrarCuentaColaborador']);

// ADMINISTRACIÓN EMPRESAS
Route::get('/administracion/empresas', ['as' => 'Admin_Company', 'uses' => 'Admin\AdministracionEmpresasController@Index']);
Route::post('/administracion/empresas/descarga-excel-directorio', ['as' => 'Admin_Download_Directory', 'uses' => 'Admin\AdministracionEmpresasController@DescargarExcelDirectorio']);
Route::post('/administracion/empresas/cambioPais', ['as' => 'Admin_Cambio_Pais', 'uses' => 'Admin\AdministracionEmpresasController@CambioPais']);
Route::post('/administracion/empresas/cambioDepartamento', ['as' => 'Admin_Cambio_Departamento', 'uses' => 'Admin\AdministracionEmpresasController@CambioDepartamento']);
Route::post('/administracion/empresas/crearEmpresa', ['as' => 'Admin_Create_Company', 'uses' => 'Admin\AdministracionEmpresasController@CrearEmpresa']);
Route::post('/administracion/empresas/consultaEmpresas', ['as' => 'Admin_Get_Companies', 'uses' => 'Admin\AdministracionEmpresasController@ConsultaEmpresas']);
Route::post('/administracion/empresas/eliminarEmpresa', ['as' => 'Admin_Delete_Companies', 'uses' => 'Admin\AdministracionEmpresasController@EliminarEmpresa']);
Route::post('/administracion/empresas/consultarEmpresaEdicion', ['as' => 'Admin_Get_Company', 'uses' => 'Admin\AdministracionEmpresasController@ConsultaEditarEmpresa']);
Route::post('/administracion/empresas/editarEmpresa', ['as' => 'Admin_Edit_Company', 'uses' => 'Admin\AdministracionEmpresasController@EditarEmpresa']);
Route::post('/administracion/empresas/actualizarEstadoEmpresa', ['as' => 'Admin_Update_State_Company', 'uses' => 'Admin\AdministracionEmpresasController@ActualizarEstadoEmpresa']);
Route::post('/administracion/empresas/consultaEmpresasScroll', ['as' => 'Admin_Get_Page_Companies', 'uses' => 'Admin\AdministracionEmpresasController@TraerEmpresasPaginacion']);

// ADMINISTRACION EMPRESAS DIRECTORIO
Route::get('/administracion/empresas/directorio/{idEmpresa}', ['as' => 'Admin_Company_Directory', 'uses' => 'Admin\AdministracionEmpresasDirectorioController@Index']);
Route::post('/administracion/empresas/directorio/crearUsuario', ['as' => 'Admin_Create_Users', 'uses' => 'Admin\AdministracionEmpresasDirectorioController@CrearUsuario']);
Route::post('/administracion/empresas/directorio/consultaUsuarios', ['as' => 'Admin_Get_Users', 'uses' => 'Admin\AdministracionEmpresasDirectorioController@ConsultaUsuarios']);
Route::post('/administracion/empresas/directorio/eliminarUsuario', ['as' => 'Admin_Delete_User', 'uses' => 'Admin\AdministracionEmpresasDirectorioController@EliminarUsuario']);
Route::post('/administracion/empresas/directorio/consultarUsuarioEdicion', ['as' => 'Admin_Get_User_Edit', 'uses' => 'Admin\AdministracionEmpresasDirectorioController@ConsultaEditarUsuario']);
Route::post('/administracion/empresas/directorio/editarUsuario', ['as' => 'Admin_Update_User', 'uses' => 'Admin\AdministracionEmpresasDirectorioController@ActualizarUsuario']);
Route::post('/administracion/empresas/directorio/actualizarEstadoUsuario', ['as' => 'Admin_Update_State_User', 'uses' => 'Admin\AdministracionEmpresasDirectorioController@ActualizarEstadoUsuario']);
Route::post('/administracion/empresas/directorio/consultaUsuarioScroll', ['as' => 'Admin_Get_Users_Scroll', 'uses' => 'Admin\AdministracionEmpresasDirectorioController@TraerUsuariosPaginacion']);

// ADMINISTRACIÓN ESTABLECIMIENTOS
Route::get('/administracion/establecimiento', ['as' => 'Admin_Establishment', 'uses' => 'Admin\AdministracionEstablecimientosController@Index']);
Route::post('/administracion/establecimiento/crearEstablecimiento', ['as' => 'Admin_Establishment_Create', 'uses' => 'Admin\AdministracionEstablecimientosController@CrearEstablecimiento']);
Route::post('/administracion/establecimiento/consultaEstablecimientos', ['as' => 'Admin_Get_Establishment', 'uses' => 'Admin\AdministracionEstablecimientosController@ConsultarEstablecimientos']);
Route::post('/administracion/establecimiento/actualizarEstadoEstablecimiento', ['as' => 'Admin_Establishment_Update', 'uses' => 'Admin\AdministracionEstablecimientosController@ActualizarEstadoEstablecimiento']);
Route::post('/administracion/establecimiento/eliminarEstablecimiento', ['as' => 'Admin_Establishment_Delete', 'uses' => 'Admin\AdministracionEstablecimientosController@EliminarEstablecimiento']);
Route::post('/administracion/establecimiento/consultarEstablecimientoEdicion', ['as' => 'Admin_Get_Establishment_Edit', 'uses' => 'Admin\AdministracionEstablecimientosController@ConsultaEditarEstablecimiento']);
Route::post('/administracion/establecimiento/editarEmpresa', ['as' => 'Admin_Update_Establishment', 'uses' => 'Admin\AdministracionEstablecimientosController@EditarEstablecimiento']);
Route::post('/administracion/establecimiento/consultaEstablecimientoScroll', ['as' => 'Admin_Get_Page_Establishment', 'uses' => 'Admin\AdministracionEstablecimientosController@TraerEstablecimientosPaginacion']);
Route::post('/administracion/establecimiento/consultaColaboradores', ['as' => 'consulta_colaboradores_establecimiento', 'uses' => 'Admin\AdministracionEstablecimientosController@consultaColaboradores']);

// ADMINISTACION DE ZONAS
Route::get('/administracion/zonas', ['as' => 'admin_zonas', 'uses' => 'Admin\AdministracionZonasController@index']);
Route::post('/administracion/zonas/crear', ['as' => 'crear_zona', 'uses' => 'Admin\AdministracionZonasController@crearZona']);
Route::post('/administracion/zonas/consultarZonas', ['as' => 'consultar_zonas', 'uses' => 'Admin\AdministracionZonasController@consultarZonas']);
Route::post('/administracion/zonas/actualizarEstadoZona', ['as' => 'actualizar_estado_zona', 'uses' => 'Admin\AdministracionZonasController@actualizarEstadoZona']);
Route::post('/administracion/zonas/eliminarZonas', ['as' => 'eliminar_zona', 'uses' => 'Admin\AdministracionZonasController@eliminarZonas']);
Route::post('/administracion/zonas/editarZona', ['as' => 'eliminar_zona', 'uses' => 'Admin\AdministracionZonasController@editarZona']);
Route::get('/administracion/zonas/consultarZonasFiltro', ['as' => 'consultar_zonas', 'uses' => 'Admin\AdministracionZonasController@consultarZonasFiltro']);
Route::post('/administracion/zonas/consultarEstablecimientoZona', ['as' => 'consultar_establecimiento_zona', 'uses' => 'Admin\AdministracionZonasController@consultarEstablecimientoZona']);

// ADMINISTRACIÓN DE ÁREAS
Route::get('/administracion/areas', ['as' => 'admin_areas', 'uses' => 'Admin\AdministracionAreasController@index']);
Route::post('/administracion/areas/crear', ['as' => 'crear_area', 'uses' => 'Admin\AdministracionAreasController@CrearArea']);
Route::post('/administracion/areas/TraerAreas', ['as' => 'traer_areas', 'uses' => 'Admin\AdministracionAreasController@TraerAreas']);
Route::post('/administracion/areas/ConsultarArea', ['as' => 'consultar_area', 'uses' => 'Admin\AdministracionAreasController@ConsultarArea']);
Route::post('/administracion/areas/editar', ['as' => 'editar_area', 'uses' => 'Admin\AdministracionAreasController@EditarArea']);
Route::post('/administracion/areas/EliminarArea', ['as' => 'eliminar_area', 'uses' => 'Admin\AdministracionAreasController@EliminarArea']);
Route::post('/administracion/areas/ActualizarEstadoArea', ['as' => 'actualizar_estado_area', 'uses' => 'Admin\AdministracionAreasController@ActualizarEstadoArea']);
Route::post('/administracion/areas/ConsultarDetalle', ['as' => 'consultar_detalle', 'uses' => 'Admin\AdministracionAreasController@ConsultarDetalle']);

// ADMINISTRACIÓN DE EQUIPOS
Route::get('/administracion/equipos', ['as' => 'admin_equipos', 'uses' => 'Admin\AdministracionEquiposController@index']);
Route::post('/administracion/equipos/TraerEquipos', ['as' => 'traer_equipos', 'uses' => 'Admin\AdministracionEquiposController@TraerEquipos']);
Route::post('/administracion/equipos/crear', ['as' => 'crear_equipo', 'uses' => 'Admin\AdministracionEquiposController@CrearEquipo']);
Route::post('/administracion/equipos/ConsultarEquipo', ['as' => 'consultar_equipo', 'uses' => 'Admin\AdministracionEquiposController@ConsultarEquipo']);
Route::post('/administracion/equipos/editar', ['as' => 'editar_equipo', 'uses' => 'Admin\AdministracionEquiposController@EditarEquipo']);
Route::post('/administracion/equipos/EliminarEquipo', ['as' => 'eliminar_equipo', 'uses' => 'Admin\AdministracionEquiposController@EliminarEquipo']);
Route::post('/administracion/equipos/ActualizarEstadoEquipo', ['as' => 'actualizar_estado_equipo', 'uses' => 'Admin\AdministracionEquiposController@ActualizarEstadoEquipo']);
Route::post('/administracion/equipos/ConsultarDetalle', ['as' => 'consultar_detalle', 'uses' => 'Admin\AdministracionEquiposController@ConsultarDetalle']);

// ADMINISTRACIÓN USUARIO
Route::get('/administracion/usuarios', ['as' => 'Admin_Users', 'uses' => 'Admin\AdministracionUsuariosController@Index']);
Route::post('/administracion/usuarios/crearUsuario', ['as' => 'Admin_Create_Users', 'uses' => 'Admin\AdministracionUsuariosController@CrearUsuario']);
Route::post('/administracion/usuarios/consultaUsuarios', ['as' => 'Admin_Get_Users', 'uses' => 'Admin\AdministracionUsuariosController@ConsultaUsuarios']);
Route::post('/administracion/usuarios/eliminarUsuario', ['as' => 'Admin_Delete_User', 'uses' => 'Admin\AdministracionUsuariosController@EliminarUsuario']);
Route::post('/administracion/usuarios/consultarUsuarioEdicion', ['as' => 'Admin_Get_User_Edit', 'uses' => 'Admin\AdministracionUsuariosController@ConsultaEditarUsuario']);
Route::post('/administracion/usuarios/editarUsuario', ['as' => 'Admin_Update_User', 'uses' => 'Admin\AdministracionUsuariosController@ActualizarUsuario']);
Route::post('/administracion/usuarios/actualizarEstadoUsuario', ['as' => 'Admin_Update_State_User', 'uses' => 'Admin\AdministracionUsuariosController@ActualizarEstadoUsuario']);
Route::post('/administracion/usuarios/consultaUsuarioScroll', ['as' => 'Admin_Get_Users_Scroll', 'uses' => 'Admin\AdministracionUsuariosController@TraerUsuariosPaginacion']);

// ADMINISTRACIÓN PERFILES
Route::get('/administracion/perfiles', ['as' => 'Admin_Profiles', 'uses' => 'Admin\AdministracionPerfilesController@Index']);

// ADMINISTRACIÓN CARGOS
Route::get('/administracion/cargos', ['as' => 'Admin_Charges', 'uses' => 'Admin\AdministracionCargosController@Index']);
Route::post('/administracion/cargos/consultaCargos', ['as' => 'Admin_Get_Charges', 'uses' => 'Admin\AdministracionCargosController@TraerCargos']);
Route::post('/administracion/cargos/crearCargo', ['as' => 'Admin_Create_Charge', 'uses' => 'Admin\AdministracionCargosController@CrearCargo']);
Route::post('/administracion/cargos/editarCargo', ['as' => 'Admin_Update_Charge', 'uses' => 'Admin\AdministracionCargosController@EditarCargo']);
Route::post('/administracion/cargos/cambiarEstado', ['as' => 'Admin_Update_State', 'uses' => 'Admin\AdministracionCargosController@CambiarEstadoCargo']);
Route::post('/administracion/cargos/eliminarCargo', ['as' => 'Admin_Delete_State', 'uses' => 'Admin\AdministracionCargosController@EliminarCargo']);

//------------------------------------------- CLIENTES----------------------------------------------------
// CLIENTES EMPRESAS
Route::get('/clientes/empresas', ['as' => 'Client_Company', 'uses' => 'Admin\ClientesEmpresasController@Index']);
// CLIENTES ESTABLECIMIENTOS
Route::get('/clientes/establecimiento', ['as' => 'Client_Establishment', 'uses' => 'Admin\ClientesEstablecimientosController@Index']);

// CLIENTES USUARIO
Route::get('/clientes/usuarios', ['as' => 'Client_Users', 'uses' => 'Admin\ClientesUsuariosController@Index']);

// CLIENTES PERFILES
Route::get('/clientes/perfiles', ['as' => 'Client_Profiles', 'uses' => 'Admin\ClientesPerfilesController@Index']);


//------------------------------------------- LISTRAS DE CHEQUEO----------------------------------------------------
// LISTAS - MODELOS
Route::get('/listachequeo/modelos', ['as' => 'List_Model', 'uses' => 'Admin\ListaChequeoModelosController@Index']);
Route::post('/listachequeo/modelos/consultaListaModelos', ['as' => 'Models_Get_All', 'uses' => 'Admin\ListaChequeoModelosController@ConsultarModelos']);
Route::post('/listachequeo/modelos/consultaListaModelosScroll', ['as' => 'Models_Get_Scroll', 'uses' => 'Admin\ListaChequeoModelosController@ConsultarModelosScroll']);
Route::post('/listachequeo/modelos/crearListaChequeoDesdeModelo', ['as' => 'Models_Create_List_Check', 'uses' => 'Admin\ListaChequeoModelosController@CreateListModel']);
Route::post('/listachequeo/modelos/asignacion-modelos', ['as' => 'Models_Create_List_Check', 'uses' => 'Admin\ListaChequeoModelosController@asignacionModeloSector']);
Route::get('/listachequeo/modelos/asignacion-modelos/{modeloId}', ['as' => 'Models_Create_List_Check', 'uses' => 'Admin\ListaChequeoModelosController@sectorConModelo']);
Route::post('/listachequeo/modelos/asignacion-modelos-masivo', ['as' => 'Models_Create_List_Check', 'uses' => 'Admin\ListaChequeoModelosController@asignacionMasivaSector']);



// MIS LISTAS
Route::get('/listachequeo/mislistas', ['as' => 'List_MyList', 'uses' => 'Admin\ListaChequeoMisListasController@Index']);
Route::post('/listachequeo/mislistas/crearListaMiLista', ['as' => 'List_MyList_Create', 'uses' => 'Admin\ListaChequeoMisListasController@CrearListaChequeoMia']);
Route::get('/listachequeo/mislistas/{listaChequeo}', ['as' => 'List_Check_Create', 'uses' => 'Admin\ListaChequeoMisListasController@IndexCrearListaChequeo']);
Route::post('/listachequeo/mislistas/actualizarListaChequeo', ['as' => 'List_MyList_Update', 'uses' => 'Admin\ListaChequeoMisListasController@ActualizarListaChequeo']);
Route::post('/listachequeo/mislistas/crearCategoria', ['as' => 'List_Category_Create', 'uses' => 'Admin\ListaChequeoMisListasController@CrearCategoria']);
Route::post('/listachequeo/mislistas/traerCategoriasYPreguntas', ['as' => 'List_Category_Question', 'uses' => 'Admin\ListaChequeoMisListasController@ConsultarCategoriasConSusPreguntas']);
Route::post('/listachequeo/mislistas/eliminarCategoria', ['as' => 'List_Category_Delete', 'uses' => 'Admin\ListaChequeoMisListasController@EliminarCategoria']);
Route::post('/listachequeo/mislistas/editaCategoria', ['as' => 'List_Category_Update', 'uses' => 'Admin\ListaChequeoMisListasController@EditarCategoria']);
Route::post('/listachequeo/mislistas/consultarInformacionStep', ['as' => 'List_Get_Information_Steps', 'uses' => 'Admin\ListaChequeoMisListasController@TraerInformacionSteps']);
Route::post('/listachequeo/mislistas/validarTiposDeRespuesta', ['as' => 'List_Get_Resonses', 'uses' => 'Admin\ListaChequeoMisListasController@TraerRespuestasTipo']);
Route::post('/listachequeo/mislistas/crearPregunta', ['as' => 'List_Create_Quetion', 'uses' => 'Admin\ListaChequeoMisListasController@CrearPregunta']);
Route::post('/listachequeo/mislistas/eliminarPregunta', ['as' => 'List_Delete_Quetion', 'uses' => 'Admin\ListaChequeoMisListasController@EliminarPregunta']);
Route::post('/listachequeo/mislistas/editarPregunta', ['as' => 'List_Get_Question_Only', 'uses' => 'Admin\ListaChequeoMisListasController@ConsultaDetallePreguntaPorIdPregunta']);
Route::post('/listachequeo/mislistas/validarTiposDeRespuestaModoEdicion', ['as' => 'List_Get_Validate_Type', 'uses' => 'Admin\ListaChequeoMisListasController@TraerRespuestasTipoModoEdicion']);
Route::post('/listachequeo/mislistas/actualizarPregunta', ['as' => 'List_Update_Question', 'uses' => 'Admin\ListaChequeoMisListasController@ActualizarPregunta']);
Route::post('/listachequeo/mislistas/guardarEncabezadoListaChequeo', ['as' => 'List_Insert_Head', 'uses' => 'Admin\ListaChequeoMisListasController@InsertarEncabezado']);
Route::post('/listachequeo/mislistas/actualizarConfiguracion', ['as' => 'List_Update_Settings', 'uses' => 'Admin\ListaChequeoMisListasController@ActualizarConfiguracion']);
Route::post('/listachequeo/mislistas/consultaListasDeChequeo', ['as' => 'List_Get_All_List_Check', 'uses' => 'Admin\ListaChequeoMisListasController@ConsultarListasDeChequeo']);
Route::post('/listachequeo/mislistas/eliminarListaChequeo', ['as' => 'List_Delete_List', 'uses' => 'Admin\ListaChequeoMisListasController@EliminarListaChequeo']);
Route::post('/listachequeo/mislistas/consultarInformacionTarjeta', ['as' => 'List_Get_List_Tarjet', 'uses' => 'Admin\ListaChequeoMisListasController@ConsultaLinkInformacionTarjeta']);
Route::post('/listachequeo/mislistas/consultaAuditoriasScroll', ['as' => 'List_Get_List_Tarjet', 'uses' => 'Admin\ListaChequeoMisListasController@ConsultaListaChequeoScroll']);
Route::post('/listachequeo/mislistas/comenzarEjecucionListaChequeo', ['as' => 'List_Excecute_My_List', 'uses' => 'Admin\ListaChequeoMisListasController@EjecutarListaDeChequeo']);
Route::post('/listachequeo/mislistas/actualizarEstadoListaChequeo', ['as' => 'My_List_Change_State', 'uses' => 'Admin\ListaChequeoMisListasController@CambiarEstadoListaChequeo']);
Route::post('/listachequeo/mislistas/actualizarFavoritoListaChequeo', ['as' => 'Update_Start', 'uses' => 'Admin\ListaChequeoMisListasController@CambiarFavorito']);
Route::post('/listachequeo/mislistas/traerListaDeChequeoPrevisualizacion', ['as' => 'Preview_List_Check', 'uses' => 'Admin\ListaChequeoMisListasController@PrevisualizacionListaChequeo']);
Route::post('/listachequeo/mislistas/crearEtiqueta', ['as' => 'Create_Etiqueta', 'uses' => 'Admin\ListaChequeoMisListasController@CrearEtiqueta']);
Route::post('/listachequeo/mislistas/traerEtiquetas', ['as' => 'Traer_Etiquetas', 'uses' => 'Admin\ListaChequeoMisListasController@TraerEtiquetas']);
Route::post('/listachequeo/mislistas/eliminarEtiqueta', ['as' => 'Eliminar_Etiqueta', 'uses' => 'Admin\ListaChequeoMisListasController@EliminarEtiqueta']);
Route::get('/listachequeo/mislistas/validarDuplicar/{id}', 'Admin\ListaChequeoMisListasController@validarDuplicar');

//EJECUCIÓN LISTA DE CHEQUEO 
Route::get('/listachequeo/ejecucion/{idListaEjecucion}/{idChequeoEjecutada}', ['as' => 'Excecute_List_Chequeo', 'uses' => 'Admin\ListaChequeoEjecucionController@Index']);
Route::get('/listachequeo/ejecucion/{idListaAEjecutar}', ['as' => 'Excecute_Link_Url', 'uses' => 'Admin\ListaChequeoEjecucionController@EjecutarPorLinkUrl']);
Route::post('/listachequeo/ejecucion/enlistarListaChequeo', ['as' => 'List_Chequeo_Exc', 'uses' => 'Admin\ListaChequeoEjecucionController@EnlistarListaChequeo']);
Route::post('/listachequeo/ejecucion/agregarRespuestaListaChequeo', ['as' => 'List_Chequeo_Answer', 'uses' => 'Admin\ListaChequeoEjecucionController@AgregarRespuesta']);
Route::post('/listachequeo/ejecucion/agregarComentarioPregunta', ['as' => 'List_Chequeo_Answer_Add_Comment', 'uses' => 'Admin\ListaChequeoEjecucionController@AgregarComentarioRespuesta']);
Route::post('/listachequeo/ejecucion/finalizarListaChequeo', ['as' => 'Finish_List_Check', 'uses' => 'Admin\ListaChequeoEjecucionController@FinalizarListaChequeo']);
Route::post('/listachequeo/ejecucion/guardarFotosTomadas', ['as' => 'Save_Images_Ejecution', 'uses' => 'Admin\ListaChequeoEjecucionController@GuardarImagenesListaEjecucion']);
Route::post('/listachequeo/ejecucion/traerImagenesAuditoria', ['as' => 'Save_Images_Ejecution', 'uses' => 'Admin\ListaChequeoEjecucionController@TraerImagenesGuardadas']);
Route::post('/listachequeo/ejecucion/guardarAdjuntos', ['as' => 'guardar_adjuntos_lce', 'uses' => 'Admin\ListaChequeoEjecucionController@guardarAdjuntos']);
Route::post('/listachequeo/ejecucion/traerAdjuntosAuditoria','Admin\ListaChequeoEjecucionController@traerArchivosAdjuntos');
Route::post('/listachequeo/ejecucion/elimnarArchivoAdjunto','Admin\ListaChequeoEjecucionController@elimnarArchivoAdjunto');
Route::post('/listachequeo/ejecucion/opciones_plan_accion_manual', 'Admin\ListaChequeoEjecucionController@lista_opc_plan_accion_manual');
Route::post('/listachequeo/ejecucion/guardar_plan_accion_manual', 'Admin\ListaChequeoEjecucionController@guardar_plan_accion_manual');
Route::post('/listachequeo/ejecucion/plan_accion_manual/datos', 'Admin\ListaChequeoEjecucionController@traer_datos_plan_accion_manual');

//LISTAS DE CHEQUEO EJECUTADAS
Route::get('/listachequeo/ejecutadas', ['as' => 'List_MyList_Excecuted', 'uses' => 'Admin\ListasChequeoEjecutadasController@Index']);
Route::post('/listachequeo/ejecutadas/consultaListasEjecutadas', ['as' => 'Get_Excuted_List', 'uses' => 'Admin\ListasChequeoEjecutadasController@ConsultaListasEjecutadas']);
Route::post('/listachequeo/ejecutadas/consultaListasEjecutadasScroll', ['as' => 'Get_Excecuted_Scroll', 'uses' => 'Admin\ListasChequeoEjecutadasController@TraerEjecutadasPaginacion']);
Route::post('/listachequeo/ejecutadas/cambiarEstadoACancelada', ['as' => 'Change_Cancel_State', 'uses' => 'Admin\ListasChequeoEjecutadasController@CambiarEstadoACancelada']);


//DETALLE LISTAS DE CHEQUEO
Route::get('/listachequeo/detalle/{idChequeoEjecutada}', ['as' => 'Excecute_List_Chequeo', 'uses' => 'Admin\ListasChequeoEjecutadasController@IndexDetalleListaChequeo']);
Route::GET('/informes/descargar-excel-lista', ['as' => 'descargar_excel_lista_chequeo', 'uses' => 'Admin\ListasChequeoEjecutadasController@descargaListaChequeoExcel']);
Route::post('/listachequeo/detalle/adjuntosConsulta', 'Admin\ListasChequeoEjecutadasController@consultarAdjuntos');
Route::GET('/listachequeo/detalle/descargarAdjunto/{id}', 'Admin\ListasChequeoEjecutadasController@descargarAdjunto');
Route::post('/listachequeo/detalle/cargar_plan_accion_manual', 'Admin\ListasChequeoEjecutadasController@traer_data_plan_accion_manual');

//PLAN DE ACCIÓN MANUAL
Route::get('/listachequeo/planaccion/manual', ['as' => 'Plan_action_manual', 'uses' => 'Admin\ListaChequeoPlanAccionController@IndexPlanAccionManual']);
Route::get('/listachequeo/planaccion/manual/{idPlanAccionEject}', ['as' => 'Plan_action_Filter_Manual', 'uses' => 'Admin\ListaChequeoPlanAccionController@IndexPlanAccionManual']);
Route::post('/listachequeo/planaccion/manual/traerPlanesDeAccion', ['as' => 'Plan_action_Manual_Get', 'uses' => 'Admin\ListaChequeoPlanAccionController@ConsultarPlanesDeAccionManual']);
Route::post('/listachequeo/planaccion/manual/descargar-excel', ['as' => 'descargar_excel_para_plan_accion_manual', 'uses' => 'Admin\ListaChequeoPlanAccionController@descargaExcelPlanAccionManual']);

//PLAN ACCIÓN HALLAZGOS
Route::get('/plan_accion/hallazgos', ['as' => 'plan_accion_hallazgos', 'uses' => 'Admin\ListaChequeoPlanAccionController@IndexHallazgosPlanAccion']);
Route::post('/plan_accion/hallazgos/traerHallazgos', ['as' => 'plan_accion_hallazgos_traer_todos', 'uses' => 'Admin\ListaChequeoPlanAccionController@TraerHallazgos']);
Route::post('/plan_accion/hallazgos/descargar-excel', ['as' => 'descargar_excel_para_plan_accion_hallazgos', 'uses' => 'Admin\ListaChequeoPlanAccionController@descargaExcelHallazgos']);

//PLAN DE ACCIÓN AUTOMÁTICO
Route::get('/listachequeo/planaccion', ['as' => 'Plan_action', 'uses' => 'Admin\ListaChequeoPlanAccionController@Index']);
Route::get('/listachequeo/planaccion/{idPlanAccionEject}', ['as' => 'Plan_action_Filter', 'uses' => 'Admin\ListaChequeoPlanAccionController@Index']);
Route::post('/listachequeo/planaccion/crearAccionCorrectiva', ['as' => 'Plan_Action_Create_Correction', 'uses' => 'Admin\ListaChequeoPlanAccionController@CrearAccionCorrectiva']);
Route::post('/listachequeo/planaccion/traerCorrectivos', ['as' => 'Plan_Action_Get_Correction', 'uses' => 'Admin\ListaChequeoPlanAccionController@TraerCorrectivos']);
Route::post('/listachequeo/planaccion/eliminarAccionCorrectiva', ['as' => 'Plan_Action_Delete_Correction', 'uses' => 'Admin\ListaChequeoPlanAccionController@EliminarCorrectivo']);
Route::post('/listachequeo/planaccion/traerPlanesDeAccion', ['as' => 'Plan_Action_Get_Correction', 'uses' => 'Admin\ListaChequeoPlanAccionController@ConsultarPlanesDeAccion']);
Route::post('/listachequeo/planaccion/asignacionDeCritico', ['as' => 'Plan_Action_Add_Correction', 'uses' => 'Admin\ListaChequeoPlanAccionController@AsignarCriticoPlanAccion']);
Route::post('/listachequeo/planaccion/descargar-excel', ['as' => 'descargar_excel_para_plan_accion', 'uses' => 'Admin\ListaChequeoPlanAccionController@descargaExcelPlanAccion']);

Route::get('/listachequeo/planaccion/seguimiento/{idListaEject}/{idPlanAccion}/{tipoPlanAccion}', 'Admin\ListaChequeoPlanAccionController@vista_seguimiento');
Route::post('/listachequeo/planaccion/seguimiento/guardarSeguimiento', ['as' => 'guardar_seguimiento', 'uses' => 'Admin\ListaChequeoPlanAccionController@guardarSeguimiento']);
Route::post('/listachequeo/planaccion/seguimiento/guardarSeguimientoDetalle', ['as' => 'guardar_seguimiento_detalle', 'uses' => 'Admin\ListaChequeoPlanAccionController@guardarSeguimientoDetalle']);
Route::post('/listachequeo/planaccion/seguimiento/cargar', ['as' => 'cargar_seguimientos', 'uses' => 'Admin\ListaChequeoPlanAccionController@cargarSeguimientos']);
Route::get('/listachequeo/planaccion/seguimiento/descargarAdjuntoSeguimiento/{id}', 'Admin\ListaChequeoPlanAccionController@descargarAdjuntoSeguimiento');

//SUSCRIPCIONES
Route::post('/suscripciones/seleccionarPlan', ['as' => 'Select_Plan', 'uses' => 'Admin\SuscripcionesController@SeleccionarPlan']);
Route::post('/suscripciones/agregarTarjeta', ['as' => 'Add_Credit_Card', 'uses' => 'Admin\SuscripcionesController@AgregarTarjetaCredito']);
Route::post('/suscripciones/suscribirPlan', ['as' => 'Plan_Subscription', 'uses' => 'Admin\SuscripcionesController@CrearSuscripcionEntreClienteYPlan']);
Route::post('/suscripciones/removeTarjetaCredito', ['as' => 'Remove_Credit_Card', 'uses' => 'Admin\SuscripcionesController@EliminarTarjetaCredito']);
Route::post('/suscripciones/validarSuscripcion', ['as' => 'Validate_Credit_Card', 'uses' => 'Admin\SuscripcionesController@SuscripcionValidacion']);
Route::post('/suscripciones/enviarCorreoContactanos', ['as' => 'Contact_Us', 'uses' => 'Admin\SuscripcionesController@Contactanos']);


//MEDOTODOS DE PAGO
Route::get('/payment/tarjetas', ['as' => 'Payment', 'uses' => 'Admin\PaymentController@Index']);
Route::post('/payment/tarjetas', ['as' => 'Payment_Cards', 'uses' => 'Admin\PaymentController@CargarTarjetasCredito']);

//PAYU REDIRECCIONAMIENTO
Route::get('/payment/respuestaURL', ['as' => 'Payment_Respuesta', 'uses' => 'Admin\PaymentController@RespuestaPayu']);
Route::post('/payment/confirmacionURL', ['as' => 'Payment_Confirmation', 'uses' => 'Admin\PaymentController@ConfirmacionPayu']);

//INFORMES
Route::get('/informes/ejecutadas', ['as' => 'Reports_Excecuted', 'uses' => 'Admin\InformesController@Index']);
Route::post('/informes/traerInformeEjecutadas', ['as' => 'Reports_Get_Excecuted', 'uses' => 'Admin\InformesController@TraerInformacionInformeEjecutadas']);
Route::GET('/informes/descargar-excel', ['as' => 'descargar_excel', 'uses' => 'Admin\InformesController@descargaExcel']);

//INFORMES - DOTACIÓN Y PRÁCTICAS HIGIENICAS
Route::get('/informes/dotacion_practicas', ['as' => 'dotacion_practicas', 'uses' => 'Admin\InformesController@IndexDotacionPracticasHigienicas']);
Route::post('/informes/dotacion/GetDataInit', ['as' => 'get_data_init', 'uses' => 'Admin\InformesController@GetDataInit']);
Route::post('/informes/dotacion/GetDataObsRta', ['as' => 'get_data_obs_rta', 'uses' => 'Admin\InformesController@GetDataObsRta']);
Route::post('/informes/dotacion/DownloadExcel', ['as' => 'download_excel_dotacion', 'uses' => 'Admin\InformesController@DownloadExcel']);

//INFORMES - VERIFICACIÓN DE BALANZAS
Route::get('/informes/verificacion_balanzas', ['as' => 'verificacion_balanzas', 'uses' => 'Admin\InformesController@IndexVerificacionBalanzas']);
Route::post('/informes/verificacion_balanza/GetDataInitVerificacion', ['as' => 'Get_Data_Init_Verificacion', 'uses' => 'Admin\InformesController@GetDataInitVerificacion']);
Route::post('/informes/verificacion_balanzas/GetDataObsRtaVerificacion', ['as' => 'Get_Data_Obs_Rta_Verificacion', 'uses' => 'Admin\InformesController@GetDataObsRtaVerificacion']);
Route::post('/informes/verificacion_balanzas/DownloadExcel', ['as' => 'download_excel_verificacion', 'uses' => 'Admin\InformesController@DownloadExcelVerificacion']);

//INFORMES - TEMPERATURA EQUIPOS DE FRIO
Route::get('/informes/equipos_frios', ['as' => 'equipos_frios', 'uses' => 'Admin\InformesController@IndexEquiposFrio']);
Route::post('/informes/equipos_frios/GetDataInitTemperatura', ['as' => 'Get_Data_Init_Temperatura', 'uses' => 'Admin\InformesController@GetDataInitTemperatura']);
Route::post('/informes/equipos_frios/GetDataObsRtaTemperatura', ['as' => 'Get_Data_Obs_Rta_Temperatura', 'uses' => 'Admin\InformesController@GetDataObsRtaTemperatura']);
Route::post('/informes/equipos_frios/DownloadExcel', ['as' => 'download_excel_temperatura', 'uses' => 'Admin\InformesController@DownloadExcelTemperatura']);


//Cumpimiento Lista
Route::get('/informes/cumplimientoLista', ['as' => 'index_cumplimiento_lista', 'uses' => 'Admin\InformesController@indexCumplimientoLista']);
Route::post('/informes/cumplimientoLista/consultaPromedioFinal', ['as' => 'consulta_promedio_final_cumplimiento_lista', 'uses' => 'Admin\InformesController@consultaPromedioFinal']);





//CUENTA
Route::get('/configuracion/cuenta', ['as' => 'Account_Settings', 'uses' => 'Admin\AccountSettingsController@Index']);
Route::post('/configuracion/actualizarInformacionPersonal', ['as' => 'Account_Settings_Update', 'uses' => 'Admin\AccountSettingsController@ActualizarInformacionPersonal']);
Route::post('/configuracion/traerInformacionPagos', ['as' => 'Account_Settings_Pays', 'uses' => 'Admin\AccountSettingsController@TraerPagos']);

Route::post('/configuracion/omitir-tour', ['as' => 'omitir_tour', 'uses' => 'Admin\AccountSettingsController@omitirTour']);

Route::get('/configuracion/omitir-tour/{usuario_id}', ['as' => 'omitir_tour_buscar', 'uses' => 'Admin\AccountSettingsController@activarTour']);
Route::get('/configuracion/reiniciar-tour/{usuario_id}', ['as' => 'reiniciar_tour', 'uses' => 'Admin\AccountSettingsController@reiniciaTour']);
//