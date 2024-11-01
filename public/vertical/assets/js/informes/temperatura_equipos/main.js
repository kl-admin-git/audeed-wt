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
        url: '/informes/equipos_frios/GetDataInitTemperatura',
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

                    //INICIALIZAR TABLA
                    $('.tableTemperatura thead').html(`
                    <tr>
                        <th colspan="10" style="background-color: #003366; color:#fff">FORMATO TEMPERATURAS EQUIPOS DE FRIO</th>
                    </tr>
                    <tr>
                        <th colspan="10" style="background-color: #003366; color:#fff">PROGRAMA: MUESTREO</th>
                    </tr>
                    <tr>
                        <th colspan="5" style="text-align: initial;"><b>Semana:</b> <span class="semanaInforme"></span></th>
                        <th colspan="5" style="text-align: initial;"><b>Diligenciado:</b><span class="Diligenciado"></span></th>
                    </tr>

                    <tr>
                        <th colspan="6"></th>
                    </tr>`);
                    
                    Array.from(Object.keys(data.data)).forEach((category,key) => {
                        $('.tableTemperatura thead').append(` <tr>
                            <th style="background-color: #003366; color:#fff">${category}</th>
                            <th style="background-color: #003366; color:#fff">Código</th>
                            <th style="background-color: #003366; color:#fff">Lunes</th>
                            <th style="background-color: #003366; color:#fff">Martes</th>
                            <th style="background-color: #003366; color:#fff">Miércoles</th>
                            <th style="background-color: #003366; color:#fff">Jueves</th>
                            <th style="background-color: #003366; color:#fff">Viernes</th>
                            <th style="background-color: #003366; color:#fff">Sábado</th>
                            <th style="background-color: #003366; color:#fff">Domingo</th>
                            <th style="background-color: #003366; color:#fff">Observaciones</th>
                        </tr>`);

                        Array.from(Object.keys(data.data[category])).forEach((question, qKey) => {
                            
                            $('.tableTemperatura thead').append(`<tr>
                                <th style="">${(question.split('-')[1])}</th>
                                <th style=""> </th>
                                
                                <th class="${(data.data[category][question]['lunes'] == undefined ? '' : (data.data[category][question]['lunes'][0].obs == '' ? '' : 'has_obs_general') )}" idRta="${(data.data[category][question]['lunes'] == undefined ? '' : data.data[category][question]['lunes'][0].id_respuesta)}">
                                    ${(data.data[category][question]['lunes'] == undefined ? "" : data.data[category][question]['lunes'][0].respuesta)}
                                </th>

                                <th class="${(data.data[category][question]['martes'] == undefined ? '' : (data.data[category][question]['martes'][0].obs == '' ? '' : 'has_obs_general') )}" idRta="${(data.data[category][question]['martes'] == undefined ? '' : data.data[category][question]['martes'][0].id_respuesta)}">
                                    ${(data.data[category][question]['martes'] == undefined ? "" : data.data[category][question]['martes'][0].respuesta)}
                                </th>

                                <th class="${(data.data[category][question]['miércoles'] == undefined ? '' : (data.data[category][question]['miércoles'][0].obs == '' ? '' : 'has_obs_general') )}" idRta="${(data.data[category][question]['miércoles'] == undefined ? '' : data.data[category][question]['miércoles'][0].id_respuesta)}">
                                    ${(data.data[category][question]['miércoles'] == undefined ? "" : data.data[category][question]['miércoles'][0].respuesta)}
                                </th>

                                <th class="${(data.data[category][question]['jueves'] == undefined ? '' : (data.data[category][question]['jueves'][0].obs == '' ? '' : 'has_obs_general') )}" idRta="${(data.data[category][question]['jueves'] == undefined ? '' : data.data[category][question]['jueves'][0].id_respuesta)}">
                                    ${(data.data[category][question]['jueves'] == undefined ? "" : data.data[category][question]['jueves'][0].respuesta)}
                                </th>

                                <th class="${(data.data[category][question]['viernes'] == undefined ? '' : (data.data[category][question]['viernes'][0].obs == '' ? '' : 'has_obs_general') )}" idRta="${(data.data[category][question]['viernes'] == undefined ? '' : data.data[category][question]['viernes'][0].id_respuesta)}">
                                    ${(data.data[category][question]['viernes'] == undefined ? "" : data.data[category][question]['viernes'][0].respuesta)}
                                </th>

                                <th class="${(data.data[category][question]['sábado'] == undefined ? '' : (data.data[category][question]['sábado'][0].obs == '' ? '' : 'has_obs_general') )}" idRta="${(data.data[category][question]['sábado'] == undefined ? '' : data.data[category][question]['sábado'][0].id_respuesta)}">
                                    ${(data.data[category][question]['sábado'] == undefined ? "" : data.data[category][question]['sábado'][0].respuesta)}
                                </th>

                                <th class="${(data.data[category][question]['domingo'] == undefined ? '' : (data.data[category][question]['domingo'][0].obs == '' ? '' : 'has_obs_general') )}" idRta="${(data.data[category][question]['domingo'] == undefined ? '' : data.data[category][question]['domingo'][0].id_respuesta)}">
                                    ${(data.data[category][question]['domingo'] == undefined ? "" : data.data[category][question]['domingo'][0].respuesta)}
                                </th>

                                <th style=""></th>
                            </tr>`);
                        });
                    });
                    
                    $('.has_obs_general').on('click', OnClickViewRta);
                   
                    if(data.data.length == 0)
                    {
                        $('.tableTemperatura thead').html('<tr><td class="text-center" colspan="10">No tienes registros actualmente</td></tr>');
                        $('.semanaInforme').html("");
                        $('.Diligenciado').html("");
                    }
                    else
                    {
                        $('.semanaInforme').html(` ${data.aditional.SEMANA_DEL}`);
                        $('.Diligenciado').html(` ${data.aditional.DILIGENCIADO}`);
                    }

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

async function OnClickViewRta() 
{
    try 
    {
        let idRta = $(this).attr('idRta');
        let data = new FormData();
        data.append('idRta', idRta)        
        
        let rs = await fetch(`/informes/equipos_frios/GetDataObsRtaTemperatura`, { method: "POST", body: data, headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }});
        let rd = await rs.json();
        switch (rd.responseCode) 
        {
            case 202:
                    if(rd.data == '')
                    {
                        toastr.warning(`Esta respuesta no tiene una observación`);
                        return;
                    }

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
        let rs = await fetch(`/informes/equipos_frios/DownloadExcel`, { method: "POST", body: data, headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }});

        let rd = await rs.blob();
        CargandoNoMostrar();
        var url = window.URL.createObjectURL(rd);
        var controlTemporal = document.createElement('a');
        controlTemporal.href = url;
        controlTemporal.download = "temperatura_frios.xlsx";
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
    let url = window.location.origin + '/informes/equipos_frios';
    window.location.href = url;
}

function OnClickBuscarBoton() 
{
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