let paginacion=1;
let totalpaginas = 0;
let inicializacionPaginacion = false;
let arrayFiltros = {};
let arrayEstados = [];
arrayEstados[0] = { claseEstado: 'danger', nombreClase: 'Cancelada' };
arrayEstados[1] = { claseEstado: 'warning', nombreClase: 'Proceso' };
arrayEstados[2] = { claseEstado: 'primary', nombreClase: 'Terminada' };
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

    arrayFiltros['filtro_lista_chequeo'] = $('.listaSearch').val();
    arrayFiltros['filtro_realizacion'] = $('#datepicker-autoclose').val();
    arrayFiltros['filtro_estado'] = $('.estadoSearch').val();
    arrayFiltros['filtro_entidad'] = $('.entidadSearch ').val();
    arrayFiltros['filtro_evaluado']=  $('.evaluadoSearch').val();
    arrayFiltros['filtro_evaluador'] = $('.evaluadorSearch').val();

    $.ajax({
        type: 'POST',
        url: '/informes/traerInformeEjecutadas',
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
                    $('#tablaInformesEjecutadas tbody').html('');
                    let stringTabla = '';

                    $.each(data.datos.informeEjecutadas, function (indexInArray, item) 
                    { 
                        let stringDireccion = '';
                        if(item.DIRECCION != '')
                            stringDireccion = `<td style="cursor:pointer;" class="text-primary" onclick="OnClickMapa(this);" latitud="${(item.latitud == undefined ? '' : item.latitud)}" longitud="${(item.longitud == undefined ? '' : item.longitud)}">${item.DIRECCION}</td>`;
                        else
                            stringDireccion = `<td latitud="${(item.latitud == undefined ? '' : item.latitud)}" longitud="${(item.longitud == undefined ? '' : item.longitud)}">${item.DIRECCION}</td>`;

                         stringTabla += `<tr>
                                            <td>${item.lista_chequeo}</td>
                                            <td>${item.FECHA_REALIZACION}</td>
                                            ${ stringDireccion }
                                            <td><span class="badge badge-${arrayEstados[item.ID_ESTADO].claseEstado}">${arrayEstados[item.ID_ESTADO].nombreClase}</span></td>
                                            <td>${item.empresa}</td>
                                            <td>${item.entidad_evaluada}</td>
                                            <td>${(item.evaluado == undefined ? '' : item.evaluado)}</td>
                                            <td>${item.evaluador}</td>
                                            <td>${(item.resultado_final == undefined ? '' : item.resultado_final)}</td>
                                        </tr>   `;
                                        
                    });
                   
                    if(data.datos.informeEjecutadas.length == 0)
                        stringTabla = '<tr><td class="text-center" colspan="9">No tienes registros actualmente</td></tr>';

                    $('#tablaInformesEjecutadas tbody').html(stringTabla);
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
    let url = window.location.origin + '/informes/ejecutadas';
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


function OnClickMapa(control) 
{
    let long = parseFloat($(control).attr('longitud'));
    let lat = parseFloat($(control).attr('latitud'));
    let direccion = $.trim($(control).html());
    
    initMap(lat,long,direccion);
    $("#modalViewMap").modal('show');
}

function initMap(lat= -25.363,lng = 131.044, texto="") 
{
    var myLatLng = {lat: lat, lng: lng};

    // Create a map object and specify the DOM element
    // for display.
    var map = new google.maps.Map(document.getElementById('map'), {
      center: myLatLng,
      zoom: 16
    });

    // Create a marker and set its position.
    var marker = new google.maps.Marker({
      map: map,
      position: myLatLng,
      title: texto
    });
    
}

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

/// descarga de excel

$(document).on('click','.mdi-file-excel',function () {

    
    let filtros = JSON.stringify(arrayFiltros)
    $('#filtros_busqueda').val('')
    $('#filtros_busqueda').val(filtros)
    $('#descargar-excel').submit()
});