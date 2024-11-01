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
        format: 'yyyy-mm-dd',
        language: 'es'
    });

    IniciarVista();
});

function IniciarVista() 
{

    // arrayFiltros['filtro_lista_chequeo'] = $('.listaSearch').val();
    arrayFiltros['filtro_realizacion'] = $('#datepicker-autoclose').val();
    // arrayFiltros['filtro_estado'] = $('.estadoSearch').val();
    // arrayFiltros['filtro_entidad'] = $('.entidadSearch ').val();
    // arrayFiltros['filtro_evaluado']=  $('.evaluadoSearch').val();
    // arrayFiltros['filtro_evaluador'] = $('.evaluadorSearch').val();

    $.ajax({
        type: 'POST',
        url: '/informes/verificacion_balanza/GetDataInitVerificacion',
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
            switch (data.responseCode)
            {
                case 202:
                    $('.tableVerificacion tbody').html('');
                    let stringTable = '';

                    $.each(data.data, function (indexInArray, item) 
                    { 
                        stringTable += `<tr>`;
                        stringTable += `<td>${ item.EVALUADO }</td>`; 
                        stringTable += `<td>${ item.DESCRIPCION_EQUIPO }</td>`; 
                        Array.from(item.RESPUESTA).forEach((rta, index) => {
                            let _class = '';
                            if(item.OBSERVACION[index] != '')
                                _class = 'has_comment';
                            
                            stringTable += `<td class="${_class}" onclick="${(_class != "" ? 'OnClickViewRta('+item.RESPUESTA_ID[index]+');' : '')}">${ rta }</td>`;
                        });
                        stringTable += `<td>${item.OBSERVACION_GENERAL}</td>
                                        </tr>`
                    });
                   
                    if(data.data.length == 0)
                    {
                        stringTable = '<tr><td class="text-center" colspan="6">No tienes registros actualmente</td></tr>';
                        $('.fechaRealizacion').html("");
                        $('.diligenciado').html("");
                    }
                    else
                    {
                        $('.fechaRealizacion').html(data.data[0].FECHA_REALIZACION);
                        $('.diligenciado').html(data.data[0].DILIGENCIADO);
                    }

                    $('.tableVerificacion tbody').html(stringTable);
                    // totalpaginas = data.datos.cantidadTotal;
                    // if(!inicializacionPaginacion)
                    //     InicializacionPaginacion($('.pagination'),data.datos.cantidadTotal,paginacion);
                        
                    break;

                case 402:
                    toastr.error(data.message);
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

async function OnClickViewRta(id_rta) 
{
    try 
    {
        let data = new FormData();
        data.append('idRta', id_rta)        
        
        let rs = await fetch(`/informes/verificacion_balanzas/GetDataObsRtaVerificacion`, { method: "POST", body: data, headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }});
        let rd = await rs.json();
        switch (rd.responseCode) 
        {
            case 202:
                    $('.comment').html(rd.data);
                    $('#view-rta-obs').modal('show');
                break;
        
            default:
                break;
        }
    } 
    catch (error) 
    {
        CargandoNoMostrar();
        console.error(`Error al consultar observación respuesta: ${error.message}`);
    }
}

async function OnClickDownloadExcel()
{
    try 
    {
        let data = new FormData();
        data.append('paginacion', paginacion);
        data.append('arrayFiltros', JSON.stringify(arrayFiltros));
        
        CargandoNoMostrar();
        let rs = await fetch(`/informes/verificacion_balanzas/DownloadExcel`, { method: "POST", body: data, headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }});

        let rd = await rs.blob();
        CargandoNoMostrar();
        var url = window.URL.createObjectURL(rd);
        var controlTemporal = document.createElement('a');
        controlTemporal.href = url;
        controlTemporal.download = "verificacion_balanza.xlsx";
        document.body.appendChild(controlTemporal);
        controlTemporal.click();
        controlTemporal.remove();
    } 
    catch (error) 
    {
        CargandoNoMostrar();
        console.error(`Error al descargar excel de la verificacion balanza: ${error.message}`);
    }

}

function OnClickRestablecerBusqueda() 
{
    let url = window.location.origin + '/informes/verificacion_balanzas';
    window.location.href = url;
}

function OnClickBuscarBoton() 
{
    // paginacion = 1;
    // inicializacionPaginacion = false;
    //  $('.pagination').html(`<div class="nav-btn prev"></div>
    //                     <ul class="nav-pages"></ul>
    //                     <div class="nav-btn next"></div>`);
    // pageNum = 0;
    // pageOffset = 0;

    IniciarVista();
}

$('.buscarBoton').on('click',OnClickBuscarBoton);
$('.restablecerBoton').on('click',OnClickRestablecerBusqueda);
$('.download_excel').on('click',OnClickDownloadExcel);


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

// $(document).on('click','.mdi-file-excel',function () {

    
//     let filtros = JSON.stringify(arrayFiltros)
//     $('#filtros_busqueda').val('')
//     $('#filtros_busqueda').val(filtros)
//     $('#descargar-excel').submit()
// });