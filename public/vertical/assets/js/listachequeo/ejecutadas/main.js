let arrayEstados = [];
arrayEstados[0] = { claseEstado: 'danger', nombreClase: 'Cancelada' };
arrayEstados[1] = { claseEstado: 'warning', nombreClase: 'Proceso' };
arrayEstados[2] = { claseEstado: 'primary', nombreClase: 'Terminada' };
let paginacion = 1;
let arrayFiltros = {};

$(document).ready(function () 
{
    // Select2
    $(".select2").select2({});

    IniciarVista(true);
});

function IniciarVista(activarCargando = false)
{
    arrayFiltros['filtro_nombre_auditoria'] = $('.nombreAuditoriaSearch ').val();

    $.ajax({
        type: 'POST',
        url: '/listachequeo/ejecutadas/consultaListasEjecutadas',
        data:
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            paginacion:paginacion,
            arrayFiltros: JSON.stringify(arrayFiltros)
        },
        cache: false,
        dataType: 'json',
        beforeSend: function()
        {
            if(activarCargando)
                CargandoMostrar();
        },
        success: function(data)
        {
            if(activarCargando)
                CargandoNoMostrar();
            switch (data.codigoRespuesta)
            {
                case 202:
                        let stringGeneralTarjetas = '';

                            $.each(data.datos, function (indexInArray, itemTarjeta) 
                            { 
                                stringGeneralTarjetas += ComponenteTarjetasEjecutadas(
                                    itemTarjeta.id,
                                    itemTarjeta.ID_LISTA_CHEQUEO,
                                    itemTarjeta.NOMBRE_LISTA_CHEQUEO,
                                    'Realizada el '+itemTarjeta.FECHA_EJECUCION+' por '+itemTarjeta.NOMBRE_USUARIO_EJECUTO,
                                    itemTarjeta.EVALUADO_A,
                                    itemTarjeta.estado,
                                    arrayEstados[itemTarjeta.estado].claseEstado,
                                    arrayEstados[itemTarjeta.estado].nombreClase
                                    );   
                            });

                        $('.contenedorTarjetaListasEjecutadas').html(stringGeneralTarjetas);

                          ValidarSiTieneDatos($('.contenedorTarjetaListasEjecutadas'));
                          $('[data-toggle="tooltip"]').tooltip();
                    break;

                case 406:
                    toastr.error(data.mensaje);
                    break;

                default:
                    break;
            }

        },
        error: function(data)
        {
            if(activarCargando)
                CargandoNoMostrar();
        }
    });


    return;
}

function CargarEjecutadas() 
{
    arrayFiltros['filtro_nombre_auditoria'] = $('.nombreAuditoriaSearch ').val();

    $.ajax({
        type: 'POST',
        url: '/listachequeo/ejecutadas/consultaListasEjecutadasScroll',
        data: 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            paginacion:paginacion,
            arrayFiltros: JSON.stringify(arrayFiltros)
        },
        cache: false,
        dataType: 'json',
        beforeSend: function() 
        {
            CargandoMostrarFooter();
        },
        success: function(data) 
        {
            CargandoNoMostrarFooter();
            switch (data.codigoRespuesta) 
            {
                case 202:
                    let stringGeneralTarjetas = '';
                    $.each(data.datos, function (indexInArray, itemTarjeta) 
                    { 
                        stringGeneralTarjetas += ComponenteTarjetasEjecutadas(
                            itemTarjeta.id,
                            itemTarjeta.ID_LISTA_CHEQUEO,
                            itemTarjeta.NOMBRE_LISTA_CHEQUEO,
                            'Realizada el '+itemTarjeta.FECHA_EJECUCION+' por '+itemTarjeta.NOMBRE_USUARIO_EJECUTO,
                            itemTarjeta.EVALUADO_A,
                            itemTarjeta.estado,
                            arrayEstados[itemTarjeta.estado].claseEstado,
                            arrayEstados[itemTarjeta.estado].nombreClase,
                            'none'
                            );   
                    });
                                            
                    $(stringGeneralTarjetas).appendTo('.contenedorTarjetaListasEjecutadas').animate({
                        height: "toggle"
                    }, 500, function() {
                        scrollLoad= true;
                    });
                    $('[data-toggle="tooltip"]').tooltip();
                    ValidarSiTieneDatos($('.contenedorTarjetaListasEjecutadas'));
                    break;
            
                default:
                    break;
            }
            
        },
        error: function(data) 
        {
            CargandoNoMostrarFooter();
        }
    });    
}

function ComponenteTarjetasEjecutadas(
    idListaChequeoEjecutada,
    idListaChequeo,
    nombreListaChequeo,
    realizadaPorCuando,
    envaluado,
    estadoId,
    estadoBadge,
    textoBadge,
    display='block') 
{
    let string = ''

    let stringBoton = '';
    let stringBotonMenu = '';
    //LOGICA BOTONES
    if(estadoId == 1) // PROCESO
    {
        stringBoton = `<button class="btn btn-warning pull-right" idListaChequeo="${idListaChequeo}" idListaChequeoEjecutada="${idListaChequeoEjecutada}" onclick="OnClickEjecutadasPagina(this);" idEstado="${estadoId}">Continuar</button>`;
        stringBotonMenu = `<div style="cursor:pointer;" idListaChequeo="${idListaChequeo}" idListaChequeoEjecutada="${idListaChequeoEjecutada}" onclick="OnCancelarAuditoria(this);" class="dropdown-item" ><i class="ion-minus-circled m-r-5"></i>Cancelar</div>`;
    }
    
    if(estadoId == 2) // TERMINADO
    {
        stringBotonMenu = `<div style="cursor:pointer;" onclick="OnClickEnviarDetalleAuditoria(this);" idListaChequeoEjecutada="${idListaChequeoEjecutada}" class="dropdown-item" ><i class="mdi mdi-eye m-r-5"></i>Ver resultados</div>
        <div style="cursor:pointer;" idListaChequeo="${idListaChequeo}" idListaChequeoEjecutada="${idListaChequeoEjecutada}" onclick="OnCancelarAuditoria(this);" class="dropdown-item" ><i class="ion-minus-circled m-r-5"></i>Cancelar</div>`;        
    }
        
    let hiddenMenu="";
    if(stringBotonMenu == '')
        hiddenMenu = 'hidden';

    string = `<div class="col-md-6 col-lg-6 col-xl-4" style="display:${display}" idListaChequeoEjecutada="${idListaChequeoEjecutada}">
                    <div class="mini-stat clearfix bg-white">
                        <div class="">
                            <label class="font-14 counter font-weight-bold mt-0">${nombreListaChequeo}</label>
                            <div class="btn-group m-b-10 menuFlotante ${hiddenMenu}">
                                <button type="button" class="btn dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mdi mdi-dots-vertical" style="color:#4ac18e;font-size: 22px"></span>
                                </button>
                                <div class="dropdown-menu">
                                    ${stringBotonMenu}
                                </div>
                            </div>

                            <label class="font-11 font-weight-normal mt-0 ellipseText">${realizadaPorCuando}</label>
                            <label class="font-11 font-weight-normal mt-0 ellipseText"><span class="font-weight-bold">Evaluando a </span>${envaluado}</label>
                        </div>

                        <div>
                            <p class=" mb-0 m-t-10 text-muted">
                                <span class="badge badge-pill badge-${estadoBadge} badge-custom m-t-10" >${textoBadge}</span>                                
                                ${stringBoton}
                            </p>
                        </div>
                        
                    </div>
                </div>`

    return string;
    
}

function OnClickEjecutadasPagina(control) 
{
    let idListaChequeo = $(control).attr('idListaChequeo');
    let idListaChequeoEjecutada = $(control).attr('idListaChequeoEjecutada');
    let url = window.location.origin + `/listachequeo/ejecucion/${idListaChequeo}/${idListaChequeoEjecutada}`;
    window.location.href = url; 
    
}

function OnClickEnviarDetalleAuditoria(control) 
{
    let idListaChequeoEjecutada = $(control).attr('idListaChequeoEjecutada');
    let url = window.location.origin + `/listachequeo/detalle/${idListaChequeoEjecutada}`;
    window.location.href = url; 
}

function OnCancelarAuditoria(control) 
{
    let idEjecutada = $(control).attr('idListaChequeoEjecutada');
    let idTarjetaTotal = $(control).parents().eq(4);

    $.ajax({
        type: 'POST',
        url: '/listachequeo/ejecutadas/cambiarEstadoACancelada',
        data: 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idEjecutada:idEjecutada
        },
        cache: false,
        dataType: 'json',
        beforeSend: function() 
        {
            CargandoMostrarFooter();
        },
        success: function(data) 
        {
            CargandoNoMostrarFooter();
            switch (data.codigoRespuesta) 
            {
                case 201:
                    toastr.success(data.mensaje);
                    let control = $('.contenedorTarjetaListasEjecutadas').find('div[idlistachequeoejecutada="'+idEjecutada+'"]');

                    let stringTarjeta = '';
                    stringTarjeta += ComponenteTarjetasEjecutadas(
                        data.datos.id,
                        data.datos.ID_LISTA_CHEQUEO,
                        data.datos.NOMBRE_LISTA_CHEQUEO,
                        'Realizada el '+data.datos.FECHA_EJECUCION+' por '+data.datos.NOMBRE_USUARIO_EJECUTO,
                        data.datos.EVALUADO_A,
                        data.datos.estado,
                        arrayEstados[data.datos.estado].claseEstado,
                        arrayEstados[data.datos.estado].nombreClase
                    );  

                    $(control).after(stringTarjeta);

                    $(control).remove();

                    break;
            
                default:
                    break;
            }
            
        },
        error: function(data) 
        {
            CargandoNoMostrarFooter();
        }
    }); 
}

function OnClickBuscarBoton() 
{
    paginacion = 1;
    scrollLoad = true;
    IniciarVista(true);
}

function OnClickRestablecerBusqueda() 
{
    location.reload();
}

$('.buscarBoton').on('click',OnClickBuscarBoton);
$('.restablecerBoton').on('click',OnClickRestablecerBusqueda);

var scrollLoad = true;
$(window).scroll(function() {
   
    if(isMobile.any() != null)
    {
        if( $(window).scrollTop() + window.innerHeight >= document.body.scrollHeight ) { 
            if(scrollLoad)
            {
                scrollLoad = false;
                paginacion = paginacion + 1;
                CargarEjecutadas();
            }
        }
    }else
    {
        if ($(window).scrollTop() >= $(document).height() - $(window).height() - 20) 
        {
            if(scrollLoad)
            {
                scrollLoad = false;
                paginacion = paginacion + 1;
                CargarEjecutadas();
            }
        }
    }
    
});