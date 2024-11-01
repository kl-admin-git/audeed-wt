let paginacion=1;
let totalpaginas = 0;
let inicializacionPaginacion = false;
let arrayFiltros = {};
let arrayEstados = [];
arrayEstados[0] = { claseEstado: 'danger', nombreClase: 'Inactivo' };
arrayEstados[1] = { claseEstado: 'primary', nombreClase: 'Activo' };

$(document).ready(function () 
{
    // Select2
    $(".select2").select2({});
    
    IniciarVista();
});

function IniciarVista(activarCargando = true) 
{
    arrayFiltros['filtro_cargo'] = $('.cargosSearch').val();

    $.ajax({
        type: 'POST',
        url: '/administracion/cargos/consultaCargos',
        data: {
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
                    $('#tablaCargos tbody').html('');
                    let stringTabla = '';

                    $.each(data.datos.cargos, function (indexInArray, item) 
                    { 
                        stringBadge = `<span style="cursor:pointer;" idEstado="${item.estado}" onclick="OnClickCambiarEstado(this);" class="badge badge-${arrayEstados[item.estado].claseEstado}">${arrayEstados[item.estado].nombreClase}</span>`;

                         stringTabla += `<tr idCargo="${item.id}">
                                            <td>${item.nombre}</td>
                                            <td>${stringBadge}</td>
                                            <td>
                                                <span nombreCargo="${item.nombre}" idCargo="${item.id}" onclick="OnClickEditarCargo(this);" class="mdi mdi-pencil" data-toggle="tooltip" data-placement="top" title="Editar"></span>
                                                <span onclick="OnClickEliminarCargo(this);" class="mdi mdi-delete" data-toggle="tooltip" data-placement="top" title="Eliminar"></span>
                                            </td>
                                        </tr>   `;
                    });

                    if(data.datos.cargos.length == 0)
                        stringTabla = '<tr><td class="text-center" colspan="3">No tienes registros actualmente</td></tr>';

                    $('#tablaCargos tbody').html(stringTabla);
                    $('[data-toggle="tooltip"]').tooltip();
                    totalpaginas = data.datos.cantidadTotal;
                    if(!inicializacionPaginacion)
                        InicializacionPaginacion($('.pagination'),data.datos.cantidadTotal,paginacion);
                        
                    break;

                case 402:
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
}

function OnClickCambiarEstado(control) 
{
    let controlEstado = $(control);
    let TarjetaGeneralControl = $(control).parents().eq(1);
    let idCargo = $(TarjetaGeneralControl).attr('idcargo');
    let estadoActual = $(control).attr('idEstado');
    $.ajax({
        type: 'POST',
        url: '/administracion/cargos/cambiarEstado',
        data: 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            idCargo:idCargo,
            estadoActual:estadoActual
        },
        cache: false,
        dataType: 'json',
        beforeSend: function() 
        {
            CargandoMostrar();
        },
        success: function(data) 
        {
            CargandoNoMostrar();
            switch (data.codigoRespuesta) 
            {
                case 201:
                    $(controlEstado).removeClass('badge-primary').removeClass('badge-danger').addClass('badge-'+arrayEstados[data.datos].claseEstado).html(arrayEstados[data.datos].nombreClase);
                    $(controlEstado).attr('idEstado',data.datos);
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
            CargandoNoMostrar()
        }
    });
}

function OnClickEliminarCargo(control) 
{
    let TarjetaGeneralControl = $(control).parents().eq(1);
    let idCargo = $(TarjetaGeneralControl).attr('idcargo');
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No se podrán revertir los cambios de la eliminación",
        type: 'warning',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger m-l-10',
        confirmButtonText: 'Si, eliminarlo!',
        cancelButtonText: 'Cancelar'
    }).then(function (resultado) {
        console.log(resultado);
        if(resultado.dismiss == 'cancel')
            return;
            $.ajax({
                type: 'POST',
                url: '/administracion/cargos/eliminarCargo',
                data: 
                {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    idCargo:idCargo
                },
                cache: false,
                dataType: 'json',
                beforeSend: function() 
                {
                    CargandoMostrar();
                },
                success: function(data) 
                {
                    CargandoNoMostrar();
                    switch (data.codigoRespuesta) 
                    {
                        case 203:
                            $(TarjetaGeneralControl).remove();
                            Swal.fire(
                                'Eliminado!',
                                data.mensaje,
                                'success'
                            );
                            paginacion = 1;
                            inicializacionPaginacion = false;
                            $('.pagination').html(`<div class="nav-btn prev"></div>
                                                <ul class="nav-pages"></ul>
                                                <div class="nav-btn next"></div>`);
                            pageNum = 0;
                            pageOffset = 0;

                            IniciarVista(false);

                            // CARGA CARGOS
                            $('.cargosSearch').html('');

                            $('.cargosSearch')
                            .append($("<option></option>")
                            .attr("value","")
                            .text('Selecciona el departamento')); 

                            $.each(data.datos, function (key, value) 
                            { 
                                $('.cargosSearch')
                                .append($("<option></option>")
                                .attr("value",value.id)
                                .text(value.nombre)); 
                            });

                            $(".cargosSearch").select2(); 
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
                    CargandoNoMostrar()
                }
            });
        
        
    });    
}

let controlTemporal,padre;
function OnClickEditarCargo(control) 
{
    padre = $(control).parent().parent();
    controlTemporal = $(control).parent().parent().children().eq(0);
    $('#crearEditarCargo').find('.modal-title').html('Edición de cargo');
    $('.guardarCargo').attr('accion',1);
    $('.guardarCargo').attr('idCargo',$(control).attr('idCargo'));
    $('.cargoPopUp').val($(control).attr('nombrecargo'));
    $('#crearEditarCargo').modal('show');
}

function OnClickPopUpCreacionCargo() 
{
    $('#crearEditarCargo').find('.modal-title').html('Creación de cargo');
    $('.guardarCargo').attr('accion',0);
    $('#crearEditarCargo').modal('show');
}

function OnClickCerrarPopUpCreacionCargo() 
{
    $('.cargoPopUp').val('');
    $('#crearEditarCargo').modal('hide');
}

function OnClickCrearCargo() 
{
    if($('.cargoPopUp').val() == '')
    {
        toastr.warning('Debes agregar un nombre para el cargo');
        return;
    }

    let nombreCargo = $('.cargoPopUp').val();

    if($(this).attr('accion') == 0) // CREAR NUEVO CARGO
    {
        $.ajax({
            type: 'POST',
            url: '/administracion/cargos/crearCargo',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                nombreCargo: nombreCargo
            },
            cache: false,
            dataType: 'json',
            beforeSend: function()
            {
                CargandoMostrar();
            },
            success: function(data)
            {
                CargandoNoMostrar();
                switch (data.codigoRespuesta)
                {
                    case 200:
                        toastr.success(data.mensaje);
                        location.reload();
                        break;
    
                    case 400:
                    case 406:
                        toastr.error(data.mensaje);
                        break;
    
                    default:
                        break;
                }
    
            },
            error: function(data)
            {
                CargandoNoMostrar();
            }
        });
    }
    else // EDITAR CARGO
    {
        let idCargo = $(this).attr('idCargo');
        $.ajax({
            type: 'POST',
            url: '/administracion/cargos/editarCargo',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                nombreCargo: nombreCargo,
                idCargo: idCargo
            },
            cache: false,
            dataType: 'json',
            beforeSend: function()
            {
                CargandoMostrar();
            },
            success: function(data)
            {
                CargandoNoMostrar();
                switch (data.codigoRespuesta)
                {
                    case 201:
                        $(controlTemporal).html(data.datos.nombreCargo);
                        $(padre).find('.mdi-pencil').attr('nombreCargo',data.datos.nombreCargo);
                        OnClickCerrarPopUpCreacionCargo();
                        // CARGA CARGOS
                        $('.cargosSearch').html('');

                        $('.cargosSearch')
                        .append($("<option></option>")
                        .attr("value",'')
                        .text('Selecciona el departamento')); 

                        $.each(data.datos.cargos, function (key, value) 
                        { 
                            $('.cargosSearch')
                            .append($("<option></option>")
                            .attr("value",value.id)
                            .text(value.nombre)); 
                        });

                        $(".cargosSearch").select2();
                        break;
    
                    case 400:
                    case 406:
                        toastr.error(data.mensaje);
                        break;
    
                    default:
                        break;
                }
    
            },
            error: function(data)
            {
                CargandoNoMostrar();
            }
        });
    }
}

function OnClickRestablecerBusqueda() 
{
    let url = window.location.origin + '/administracion/cargos';
    window.location.href = url;
}

function OnClickBuscarBoton() 
{
    paginacion = 1;
    inicializacionPaginacion = false;
     $('.pagination').html(`<div class="nav-btn prev"></div>
                        <ul class="nav-pages"></ul>
                        <div class="nav-btn next"></div>`);
    pageNum = 0;
    pageOffset = 0;

    IniciarVista();
}

$('.cerrarCreacionCargo').on('click',OnClickCerrarPopUpCreacionCargo);
$('#crearCargo').on('click',OnClickPopUpCreacionCargo);
$('.guardarCargo').on('click',OnClickCrearCargo);
$('.buscarBoton').on('click',OnClickBuscarBoton);
$('.restablecerBoton').on('click',OnClickRestablecerBusqueda);

// PAGINACION
var pageNum = 0, pageOffset = 0;
function InicializacionPaginacion(baseElement, pages, pageShow) 
{
    $(baseElement).unbind("click");
    _initNav(baseElement,pageShow,pages);
    inicializacionPaginacion = true;
}

function _initNav(baseElement,pageShow,pages)
{
    //create pages
    for(i=1;i<pages+1;i++){
        $((i==1?'<li class="active">':'<li>')+(i)+'</li>').appendTo('.nav-pages', baseElement).css('min-width','4em');
    }

    //calculate initial values
    function ow(e){return e.first().outerWidth()}
    var w = ow($('.nav-pages li', baseElement)),bw = ow($('.nav-btn', baseElement));
    baseElement.css('width',w*(pages <= 5 ? pages : pageShow)+(bw*(pages <= 5 ? 2 : 4))+'px');
    $('.nav-pages', baseElement).css('margin-left',bw+'px');

    //init events
    baseElement.on('click', '.nav-pages li, .nav-btn', function(e){
        if($(e.target).is('.nav-btn'))
        {
            var toPage;
            if($(this).hasClass('prev'))
            {
                toPage = pageNum-1;
                if(toPage >= 0)
                {
                    paginacion = toPage + 1;
                    IniciarVista();
                }
            }
            else
            {
                toPage = pageNum+1;
                if(toPage < totalpaginas)
                {
                    paginacion = toPage + 1;
                    IniciarVista();
                }
            }
        }
        else
        {
            var toPage = $(this).index();
            paginacion = (toPage + 1);
            IniciarVista();
        }
        _navPage(baseElement,pages,toPage,pageShow);
    });
}

function _navPage(baseElement,pages,toPage,pageShow)
{
    var sel = $('.nav-pages li', baseElement), w = sel.first().outerWidth(),
        diff = toPage-pageNum;

    if(toPage>=0 && toPage <= pages-1){
        sel.removeClass('active').eq(toPage).addClass('active');
        pageNum = toPage;
    }else{
        return false;
    }

    if(toPage<=(pages-(pageShow+(diff>0?0:1))) && toPage>=0){
        pageOffset = pageOffset + -w*diff;  
    }else{
        pageOffset = (toPage>0)?-w*(pages-pageShow):0;
    }

    sel.parent().css('left',pageOffset+'px');
}
// PAGINACION - FIN