let arrayEstados = [];
arrayEstados[0] = { claseEstado: 'danger', nombreClase: 'Inactivo' };
arrayEstados[1] = { claseEstado: 'primary', nombreClase: 'Activo' };
let paginacion = 1;
let arrayFiltros = {};
let rango = 0;

$(document).ready(async function () 
{
    let formulario = $('#formularioCreacionAreas').parsley();
    formulario.options.requiredMessage = "Este campo es requerido";
    // Select2
    $(".select2").select2();
    await IniciarVista(true);
    $('.menu').hide()
}); 

async function IniciarVista(activarCargando = false) 
{
    try 
    {
        let selectValor = $('.areasSearch').val();
        arrayFiltros['filtro_area_id'] = selectValor

        let data = new FormData();
        data.append('filtros', JSON.stringify(arrayFiltros));
        data.append('paginacion', paginacion);

        if(activarCargando)
            CargandoMostrar();

        let rs = await fetch(`/administracion/areas/TraerAreas`, { method: "POST", body: data, 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let rd = await rs.json();
        CargandoNoMostrar();
        switch (rd.responseCode) 
        {
            case 202:
                if(paginacion == 1)
                {
                    let stringGeneralTarjetas = '';
                    Array.from(rd.data.areas).forEach(area => {
                        stringGeneralTarjetas += ComponenteArea(area);
                    });

                    $('.contenedorAreas').html(stringGeneralTarjetas);
                    $('[data-toggle="tooltip"]').tooltip();
                    ValidarSiTieneDatos($('.contenedorAreas'));
                    rango = rd.data.rango;
                }
                else
                {
                    let stringTarjetas = '';                 
                    Array.from(rd.data.areas).forEach(area => {
                        stringTarjetas += ComponenteArea(area, 'none');
                    });
                    
                    $(stringTarjetas).appendTo('.contenedorAreas').animate({
                        height: "toggle"
                    }, 500, function() {
                        if(rango > paginacion)
                            scrollLoad= true;
                    });
                    $('[data-toggle="tooltip"]').tooltip();
                    ValidarSiTieneDatos($('.contenedorAreas'));
                    $(".areasSearch option[value="+ selectValor +"]").attr("selected",true);
                }
                
                break;
        
            default:
                break;
        }

    }
    catch (error) 
    {
        CargandoNoMostrar();
        console.log(`Error al cargar la información inicial: ${error.message}`);        
    }
}

async function OnClickCrearArea(e) 
{
    e.preventDefault();
    var form = $('#formularioCreacionAreas');
    form.parsley().validate();

    if (form.parsley().isValid()) 
    {
        let data = new FormData();
        data.append('nombreArea', $('.nombreAreaPopUp').val());
        data.append('descripcion', $('.descripcionPopUp').val());
        data.append('id_empresa', $('.empresaPopUp').val());
        data.append('id_establecimiento', $('.establecimientoPopUp').val());
       
        try 
        {
            if($(this).attr('accion') != 0)
                data.append('id_area', $(this).attr('idArea'));

            CargandoMostrar();
            let rs = await fetch(`${($(this).attr('accion') == 0 ? '/administracion/areas/crear' : '/administracion/areas/editar')}`, { method: "POST", body: data, 
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let rd = await rs.json();
            CargandoNoMostrar();

            switch (rd.responseCode)
            {
                case 200:
                    OnClickCerrarModalCrearArea();
                    if($(this).attr('accion') == 0)
                    {
                        toastr.success(rd.message);
                        stringTarjetas = ComponenteArea(rd.data);
                        $('.contenedorAreas').append(stringTarjetas);
                        ValidarSiTieneDatos($('.contenedorAreas'));
                    }
                    else
                    {
                        toastr.success(rd.message);
                        let control = $('.contenedorAreas').find('div[idtarjetaarea="' + $(this).attr('idArea') + '"]');
                        $(ComponenteArea(rd.data)).insertAfter(control);
                        $(control).remove();
                    }
                    break;
            
                case 400:
                    toastr.error(rd.message);
                    break;
                default:
                    break;
            }
        } 
        catch (error) 
        {
            CargandoNoMostrar();
            console.log(`Error al crear un área: ${ error.message }`);                
        }
    }
}

async function OnClickEditarArea(control) 
{
    try 
    {
        let TarjetaGeneralControl = $(control).parents().eq(7);
        let idArea = $(TarjetaGeneralControl).attr('idtarjetaarea');
        arrayFiltros['filtro_area_id'] = idArea;

        let data = new FormData();
        data.append('id_area', idArea);
        
        CargandoMostrar();
        let rs = await fetch(`/administracion/areas/ConsultarArea`, { method: "POST", body: data, 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let rd = await rs.json();
        CargandoNoMostrar();
        switch (rd.responseCode)
        {
            case 202:
                $('.nombreAreaPopUp').val(rd.data.NOMBRE_AREA);
                $('.descripcionPopUp').val(rd.data.DESCRIPCION_AREA);
                $('.empresaPopUp').val(rd.data.ID_EMPRESA).change();
                $('.establecimientoPopUp').val(rd.data.ID_ESTABLECIMIENTO).change();

                $('#crearAreaPopUp').find('.modal-title').html('Edición de Áreas');
                $('#crearAreaPopUp').find('.crearArea').html('Actualizar');
                $('#crearAreaPopUp').find('.crearArea').attr('accion', 1);
                $('#crearAreaPopUp').find('.crearArea').attr('idArea', idArea);
                $('#crearAreaPopUp').modal('show');
                break;

            case 404:
                
                break;
        
            default:
                break;
        }
    } 
    catch (error)
    {
        CargandoNoMostrar();
        console.error(`Error al traer información del área: ${error.message}`);        
    }
}

async function OnClickEliminarArea(control) 
{
    let TarjetaGeneralControl = $(control).parents().eq(7);
    let idArea = $(TarjetaGeneralControl).attr('idtarjetaarea');
    
    let respuesta = await Swal.fire({
        title: '¿Estás seguro?',
        text: "Recuerda que luego de eliminar el área no podrás revertir los cambios",
        type: 'warning',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger m-l-10',
        confirmButtonText: 'Si, eliminarlo!',
        cancelButtonText: 'Cancelar'
    });

    if(respuesta.value)
    {
        try 
        {
            let data = new FormData();
            data.append('id_area', idArea);

            CargandoMostrar();
            let rs = await fetch(`/administracion/areas/EliminarArea`, { method: "POST", body: data, 
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let rd = await rs.json();
            CargandoNoMostrar();

            switch (rd.responseCode) 
            {
                case 200:
                    toastr.success(rd.message);   
                    $(TarjetaGeneralControl).remove();  
                    ValidarSiTieneDatos($('.contenedorAreas'));               
                    break;
                case 400:
                    toastr.warning(rd.message);
                    break;
            
                default:
                    break;
            }
        } 
        catch (error) 
        {
            CargandoNoMostrar();
            console.error(`Error al eliminar un área: ${error.message}`);
        }
    }
}

async function OnClickCambiarEstado(control) 
{
    let TarjetaGeneralControl = $(control).parents().eq(7);
    let idArea = $(TarjetaGeneralControl).attr('idtarjetaarea');
    let estadoActual = $(control).attr('idEstado');
    
    try 
    {
        let data = new FormData();
        data.append('id_area', idArea);
        data.append('estado_actual', estadoActual);

        CargandoMostrar();
        let rs = await fetch(`/administracion/areas/ActualizarEstadoArea`, { method: "POST", body: data, 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let rd = await rs.json();
        CargandoNoMostrar();

        switch (rd.responseCode) 
        {
            case 200:
                toastr.success(rd.message);
                let control = $('.contenedorAreas').find('div[idtarjetaarea="' + idArea + '"]');
                $(ComponenteArea(rd.data)).insertAfter(control);
                $(control).remove();
                break;

            case 400:
                toastr.error(rd.message);
                break;
        
            default:
                break;
        }
        
    } 
    catch (error) 
    {
        CargandoNoMostrar();
        console.error(`Error al cambiar el estado: ${error.message}`);
    }
}

async function OnClickVerDetalle(idArea, accion) 
{
    try 
    {
        return;
        let data = new FormData();
        data.append('id_area', idArea);
        data.append('accion', accion);

        CargandoMostrar();
        let rs = await fetch(`/administracion/areas/ConsultarDetalle`, { method: "POST", body: data, 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let rd = await rs.json();
        CargandoNoMostrar();
        switch (rd.responseCode) 
        {
            case 202:
                let dataForeach = (accion == 0 ? rd.data.empresas : rd.data.establecimientos);
                if(dataForeach.length == 0)
                {
                    toastr.warning('Esta área no tiene el detalle seleccionado.')
                    return;
                }

                let stringUl = '';
                Array.from(dataForeach).forEach(element => {
                    stringUl += `<li>${element.NOMBRE}</li>`;
                });
                
                $('.detalle').html(stringUl);
                $('#modal-detalle').modal('show');
                break;

            case 404:
                break;
        
            default:
                break;
        }
        
    } 
    catch (error) 
    {
        CargandoNoMostrar();
        console.error(`Error al consultar el detalle: ${error.message}`);
    }
}

function OnClickCrearAreasPopUp() 
{
    $('#crearAreaPopUp').find('.modal-title').html('Creación de Área');
    $('#crearAreaPopUp').find('.crearArea').html('Crear');
    $('#crearAreaPopUp').find('.crearArea').attr('accion', '0');
    $('#crearAreaPopUp').modal('show');
}

function OnClickCerrarModalCrearArea() 
{
    $('#crearAreaPopUp').modal('hide');
    $('#formularioCreacionAreas').find('input[type="text"]').val('');
    $('.empresaPopUp').val('').change();
    $('.establecimientoPopUp').val('').change();
    $('#formularioCreacionAreas').parsley().reset();
}

function ComponenteArea(data, display='block') 
{
    let stringEstado = '';
    let stringIconos = '';
    let estadoColor = '';

    //valido el estado
    estadoTexto = arrayEstados[data.estado].nombreClase;
    estadoColor = arrayEstados[data.estado].claseEstado;

    if (perfilExacto == 1 || perfilExacto == 2) 
    {
        stringEstado = `<span class="badge badge-pill badge-${estadoColor} badge-custom" onclick="OnClickCambiarEstado(this);" idEstado="${data.estado}">${data.ESTADO_TEXTO}</span>`;
        stringIconos = `<li data-toggle="tooltip" data-placement="top" title="Editar" onclick="OnClickEditarArea(this);" class="mdi mdi-pen"></li>
            <li data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="OnClickEliminarArea(this);" class="mdi mdi-delete"></li>`;
    } 
    else 
    {
        stringEstado = `<span class="badge badge-pill badge-${estadoColor} badge-custom" style="cursor: not-allowed!important;" idEstado="${data.estado}">${data.ESTADO_TEXTO}</span>`;
        stringIconos = `
            <li disabled style="cursor: not-allowed;" class="mdi mdi-pen"></li>
            <li disabled style="cursor: not-allowed;" class="mdi mdi-delete"></li>
        `;
    }
    
    let stringTarjeta = `<div class="col-lg-4" style="display:${display};" idTarjetaArea="${data.ID_AREA}">
                            <div class="card m-b-20">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body contenedorPrincipal col-lg-12">
                                            <h5 class="m-t-10 mb-1 texto-personalizado ellipseText" data-toggle="tooltip" data-placement="left" title="${data.NOMBRE_AREA}">${data.NOMBRE_AREA}</h5>
                                            <p class="text-truncate m-b-5" data-toggle="tooltip" data-placement="top" title="${data.DESCRIPCION_AREA}"><span class="mdi mdi-receipt" data-toggle="tooltip" data-placement="left" title="Descripción"></span> ${data.DESCRIPCION_AREA}</p>
                                            <div class="d-flex">
                                                <a href="#" onclick="OnClickVerDetalle(${data.ID_AREA}, 0);" id="linkAreasEmpresas" idArea="${data.ID_AREA}"><p class=""><span class="mdi mdi-store" data-toggle="tooltip" data-placement="left" title="Empresas"></span> ${ data.ASIGNACION } </p></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="contenedorGeneral">
                                            <div class="col-lg-12 contenedorSubtitulos">
                                                <div class="contenedorIconosAcciones">
                                                    <div class="contenedorEstados mt-2" id="acciones-estado">
                                                        ${ stringEstado }
                                                    </div>

                                                    <div class="contenedorBotonesAcciones mt-3" id="acciones-tour">
                                                        ${stringIconos}
                                                    </div>
                                                    
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>`;

    return stringTarjeta;
}

function OnClickRestablecerBusqueda() 
{
    $('.areasSearch').val('null').change();
    paginacion = 1;
    scrollLoad = true;
    IniciarVista(true);
}

function OnClickBuscarBoton() 
{
    paginacion = 1;
    scrollLoad = true;
    IniciarVista(true);
}

function OnChangeEmpresa() 
{
    if($(this).val() == "") return;
    $('.establecimientoPopUp').val("").change();
}

function OnChangeEstablecimiento() 
{
    if($(this).val() == "") return;
    $('.empresaPopUp').val("").change();
}

var scrollLoad = true;
$(window).scroll(function () 
{
    if (isMobile.any() != null) //ES UN DISPOSITIVO MÓVIL
    {
        if ($(window).scrollTop() + window.innerHeight >= document.body.scrollHeight) {
            if (scrollLoad) 
            {
                scrollLoad = false;
                paginacion = paginacion + 1;
                //CargarEstablecimiento();
                console.log(paginacion)
            }
        }
    } 
    else // NO ES UN DISPOSITIVO MÓVIL
    {
        if ($(window).scrollTop() >= $(document).height() - $(window).height() - 20) 
        {
            if (scrollLoad) 
            {
                scrollLoad = false;
                paginacion = paginacion + 1;
                IniciarVista(false);
            }
        }
    }
});   

$('#button_to_create').on('click', OnClickCrearAreasPopUp);
$('.cancelarPopUpArea').on('click', OnClickCerrarModalCrearArea);
$('.crearArea').on('click', OnClickCrearArea);
$('.restablecerBoton').on('click', OnClickRestablecerBusqueda);
$('.buscarBoton').on('click', OnClickBuscarBoton);
$('.empresaPopUp').on('change', OnChangeEmpresa);
$('.establecimientoPopUp').on('change', OnChangeEstablecimiento);

