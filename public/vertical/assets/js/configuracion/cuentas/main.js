let paginacion=1;
let totalpaginas = 0;
let inicializacionPaginacion = false;

$(document).ready(function () 
{
    let formulario = $('#formularioActualizarInformacion').parsley();
    formulario.options.requiredMessage = "Este campo es requerido";
    formulario.options.equaltoMessage = "Las contraseña no coinciden";

    IniciarVista();
});

function IniciarVista() 
{
    $.ajax({
        type: 'POST',
        url: '/configuracion/traerInformacionPagos',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            paginacion:paginacion
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
                    $('#tablaPagosRecurrentes tbody').html('');
                    let stringTabla = '';

                    $.each(data.datos.planesPagos, function (indexInArray, item) 
                    { 
                         stringTabla += `<tr>
                                            <td>${item.FECHA}</td>
                                            <td>${item.NOMBRE_PLAN}</td>
                                            <td>${item.PERIODO}</td>
                                            <td>${item.PAGO}</td>
                                            <td>$ ${parseFloat(item.TOTAL).toFixed(0)}</td>
                                        </tr>`;
                    });

                    if(data.datos.planesPagos.length == 0)
                        stringTabla = '<tr><td class="text-center" colspan="5">No tienes registros actualmente</td></tr>';

                    $('#tablaPagosRecurrentes tbody').html(stringTabla);
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

function OnClickCambiarInformacionPersonalActualizar() 
{
    var form = $('#formularioActualizarInformacion');

    form.parsley().validate();    

    if (form.parsley().isValid())
    {
        let nombre = $('#inputNombrePopUp').val();
        let email = $('#inputCorreoPopUp').val();
        let password = $('#passwordPopCtaPrincipal').val();
        

        let objetoEnviar = 
        {
            nombre:nombre,
            correo:email,
            password:password
        }
        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/configuracion/actualizarInformacionPersonal',
            data: objetoEnviar,
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
                        $('.nombreEstatico').html(data.datos.nombre_completo);
                        $('.correoEstatico').html(data.datos.correo);
                        OnClickCerrarPopUp();
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

function OnClickCerrarPopUp() 
{
    $('#actualizarInformacion').modal('hide');
    $('#formularioActualizarInformacion').find('input[type="text"]').val('');
    $('#formularioActualizarInformacion').find('input[type="email"]').val('');
    $('#formularioActualizarInformacion').find('input[type="password"]').val('');
    $('#formularioActualizarInformacion').parsley().reset();
}

function OnClickCambiarInformacionPersonal() 
{
    $('#inputNombrePopUp').val($.trim($('.nombreEstatico').html()));
    $('#inputCorreoPopUp').val($.trim($('.correoEstatico').html()));
    $("#actualizarInformacion").modal('show');
}

function OnClickCambiarCuenta() 
{
    let idPlan = $.trim($('.plan').attr('idPlan'));
    
    switch (idPlan) {
        case '1':
            let control = $('.contenedoresPlanes').find('div[idTarjeta="'+idPlan+'"]');
            $(control).find('.btn-audeed').addClass('hidden');
            break;

        case '2':
        case '3':
        case '4':

            for (let index = idPlan; index > 0; index--) 
            {
                let control = $('.contenedoresPlanes').find('div[idTarjeta="'+index+'"]');
                $(control).find('.btn-audeed').addClass('hidden');
            }

            break;
            
        default:
            break;
    }

    $('.iconoCerrarPopUpPlanes').removeClass('hidden');
    $('#popUpSuscripcion').modal('show');
}

$('.botonCambiarInformacion').on('click',OnClickCambiarInformacionPersonal);
$('.guardarInformacion').on('click',OnClickCambiarInformacionPersonalActualizar);
$('.cerrarActualizacionInformacion').on('click',OnClickCerrarPopUp);
$('.cambiaPlanCuenta').on('click',OnClickCambiarCuenta);

$(document).on('click','.reiniciar-tour',function () {

    $.ajax({
        type: "GET",
        url: `/configuracion/reiniciar-tour/${usuarioId}`,
        // data: "data",
        dataType: "json",
        success: function (response) {
            Swal.fire({
                // title: 'No puedes suscribirte',
                text: response.mensaje,
                type: 'success',
                confirmButtonClass: 'btn btn-success',
                confirmButtonText: 'Aceptar'
            })
            console.log(response)
        }
    });

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