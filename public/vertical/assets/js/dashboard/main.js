let pagina = 1;

$(document).ready(function () 
{
    // Date Picker
    $('#pickerDesde').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        language: 'es'
    });

    $('#pickerHasta').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        language: 'es'
    });

    // Select2
    $(".select2").select2({});

    IniciarVista(true);
});

function IniciarVista(clickBuscar=false) 
{
    let objetoEnviar =
    {
        serachRealizada: $('.realizadasSearch').val(),
        pagina:pagina
    };

    if($('.realizadasSearch').val() == 3) // SELECCIONA PERIODO
    {
        objetoEnviar['desde'] = $('#pickerDesde').val();
        objetoEnviar['hasta'] = $('#pickerHasta').val();
    }

    $.ajax({
        type: 'POST',
        url: '/dashboard/traerSecciones',
        data: 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            objetoEnviar: objetoEnviar
        },
        cache: false,
        dataType: 'json',
        beforeSend: function() 
        {
            if(clickBuscar)
                CargandoMostrar();
        },
        success: function(data) 
        {
            if(clickBuscar)
                CargandoNoMostrar();
            switch (data.codigoRespuesta) 
            {
                case 202:
                        // SECCIÓN UNO
                        $('.texto_terminadas').html(data.datos.datosSeccionUno.Terminadas);
                        $('.texto_proceso').html(data.datos.datosSeccionUno.Proceso);
                        $('.texto_canceladas').html(data.datos.datosSeccionUno.Canceladas);
                        $('.texto_plan_accion').html(data.datos.datosSeccionUno.planes_accion);
                        // FIN - SECCIÓN UNO

                        // SECCIÓN DOS
                        let stringTablaGeneral = '';
                        $.each(data.datos.datosSeccionDos, function (indexTabla, itemTabla) 
                        { 
                             stringTablaGeneral += `<tr>
                                                        <td>${itemTabla.LISTA_DE_CHEQUEO}</td>
                                                        <td>${itemTabla.EMPRESA}</td>
                                                        <td>${itemTabla.FECHA_REALIZACION}</td>
                                                        <td>${itemTabla.ENTIDAD}</td>
                                                        <td>${itemTabla.EVALUADO}</td>
                                                        <td>${itemTabla.HALLAZGOS}</td>
                                                        <td><span idListaEjecutada="${itemTabla.ID_LISTA_EJECUTADA}" onclick="OnClickRedireccionDetalle(this);" style="cursor:pointer;color:blue;">${itemTabla.ResultadoFinal}</span></td>
                                                    </tr>`;
                        });

                        if(pagina == 1)
                        {
                            if(data.datos.datosSeccionDos.length == 0)
                            {
                                stringTablaGeneral = `<tr>
                                                            <td colspan="7">No tienes listas ejecutadas</td>
                                                      </tr>`;
                            }
    
                            $('.tablaGeneral tbody').html(stringTablaGeneral);
                        }
                        else
                        {
                            if(data.datos.datosSeccionDos.length == 0)
                            {
                                toastr.warning('Actualmente ya no tienes más información para cargar');
                                pagina = pagina - 1;
                                $('.tablaGeneral tfoot').addClass("hidden");
                            }else
                            {
                                $('.tablaGeneral tbody').append(stringTablaGeneral);
                            }
                        }
                        // FIN - SECCIÓN DOS
                    break;
            }
            
        },
        error: function(data) 
        {
            if(clickBuscar)
                CargandoNoMostrar()
        }
    });
}

function OnChangeRealizadasPor() 
{
    if($(this).val() == 3)
    {
        $('.contenedorFechaInicio').removeClass('hidden');
        $('.contenedorFechaFin').removeClass('hidden');
    }
    else
    {
        $('.contenedorFechaInicio').addClass('hidden');
        $('.contenedorFechaFin').addClass('hidden');
        $('#pickerDesde').val('');
        $('#pickerHasta').val('');
    }
}

function OnClickBuscarBoton() 
{
    if($('.realizadasSearch ').val() == 3)
    {
        if($('#pickerDesde').val() == '' || $('#pickerHasta').val() == '')
        {
            toastr.warning('Debes completar el rango de fechas (Fecha inicial - Fecha Final)');
            return;
        }
    }

    IniciarVista(true);
}

function OnClickRedireccionDetalle(e) 
{  
    let idListaEjecutada = $(e).attr('idListaEjecutada');
    let url = window.location.origin + `/listachequeo/detalle/${idListaEjecutada}`;
    window.location.href = url;
}

function OnClickVerMas() 
{
    pagina = pagina + 1;
    IniciarVista(true);   
}

$('.realizadasSearch').on('change',OnChangeRealizadasPor);
$('.buscarBoton').on('click',OnClickBuscarBoton);
$('.verMas').on('click',OnClickVerMas);