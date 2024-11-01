let paginacion=1;
let totalpaginas = 0;
let inicializacionPaginacion = false;
let arrayFiltros = [];
$(document).ready(function () 
{
    // Select2
    $(".select2").select2({});
    // Date Picker
    $('#datepicker-autoclose').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        language: 'es'
    });

    IniciarVista();
});

function IniciarVista() 
{
    arrayFiltros['filtro_realizacion'] = $('#datepicker-autoclose').val();
    arrayFiltros['filtro_lista_chequeo'] = $('.listaSearch').val();
    arrayFiltros['filtro_evaluado']=  $('.evaluadoSearch').val();
    arrayFiltros['filtro_evaluador'] = $('.evaluadorSearch').val();
    arrayFiltros['filtro_empresa'] = $('.empresaSearch').val();

    $.ajax({
        type: 'POST',
        url: '/plan_accion/hallazgos/traerHallazgos',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            paginacion:paginacion,
            arrayFiltros: JSON.stringify(arrayFiltros)
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
                case 202:
                    $('#tablaPlanAccion tbody').html('');
                    let stringTabla = '';

                    $.each(data.datos.planesAccion, function (indexInArray, item) 
                    { 
                        let stringBadge = ''
                        if(item.tipo_plan_accion == 1){
                            stringBadge = `<span class="badge badge-danger">Automatico</span>`
                        }else if(item.tipo_plan_accion == 2){
                            stringBadge = `<span class="badge badge-dark">Manual</span>`
                        }

                         stringTabla += `<tr idAsignacion="${item.ID_EJECT_OPCIONES}">
                                            <td>${item.CODIGO_PLAN_ACCION}</td>
                                            <td>${item.FECHA_REALIZACION}</td>
                                            <td>${item.nombre}</td>
                                            <td>${item.EMPRESA}</td>
                                            <td>${(item.evaluado == undefined ? '' : item.evaluado)}</td>
                                            <td>${item.evaluador}</td>
                                            <td>${item.pregunta}</td>
                                            <td>${item.respuesta}</td>
                                            <td>${item.OBSERVACION}</td>
                                            <td>${item.ESTADO}</td>
                                            <td>${stringBadge}</td>
                                            <td>
                                                <a href="/listachequeo/planaccion/seguimiento/${item.ejecutada_id}/${item.ID_PLAN_ACCION}/${item.tipo_plan_accion}">Seguimiento</a> 
                                            </td>
                                        </tr>   `;
                                        
                    });

                    if(data.datos.planesAccion.length == 0)
                        stringTabla = '<tr><td class="text-center" colspan="12">No tienes registros actualmente</td></tr>';

                    $('#tablaPlanAccion tbody').html(stringTabla);
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
            CargandoNoMostrar();
        }
    });
}

function OnClickRestablecerBusqueda() 
{
    let url = window.location.origin + '/plan_accion/hallazgos';
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

$('.buscarBoton').on('click',OnClickBuscarBoton);
$('.restablecerBoton').on('click',OnClickRestablecerBusqueda);

/// DESCARGAR EL EXCEL
$(document).on('click','.mdi-file-excel',function () 
{
    let filtros = JSON.stringify(arrayFiltros);
    $('#filtros_busqueda').val('');
    $('#filtros_busqueda').val(filtros);
    $('#descargar-excel-planAccion').submit();
});

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