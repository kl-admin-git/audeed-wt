$(document).ready(function () 
{
    let formulario = $('#formularioRegistrar').parsley();
    formulario.options.requiredMessage = "Este campo es requerido";
    formulario.options.equaltoMessage = "Las contraseña no coinciden";
    $('.select2').select2();
    $('.empresaControl').change();
});

function CargandoMostrar() { $('#status').fadeIn(); $('#preloader').fadeIn('slow'); }
function CargandoNoMostrar() { $('#status').fadeOut(); $('#preloader').fadeOut('slow'); }

function OnClickFormulario(e) 
{ 
    e.preventDefault();
    $('.errorEmpresa').addClass('hidden');
    $('.errorEstablecimiento').addClass('hidden');

    var form = $('#formularioRegistrar');
    form.parsley().validate();    

    if (form.parsley().isValid())
    {
        if($('.empresaControl').val() == 0)
        {
            $('.errorEmpresa').removeClass('hidden');
            return;
        }
        else
            $('.errorEmpresa').addClass('hidden');

        if($('.establecimientoControl').val() == 0)
        {
            $('.errorEstablecimiento').removeClass('hidden');
            return;
        }
        else
            $('.errorEstablecimiento').addClass('hidden');


        let nombreCompleto = $('#inputNombreCompleto').val();
        let email = $('#inputCorreo').val();
        let password = $('#paswordRegistro').val();
        let empresa = $('.empresaControl ').val();
        let establecimiento = $('.establecimientoControl').val();

        let objetoEnviar = 
        {
            _token: $('meta[name="csrf-token"]').attr('content'),
            nombreCompleto:nombreCompleto,
            email:email,
            password:password,
            empresa:empresa,
            establecimiento:establecimiento,
            idCuentaPrincipal:idCuentaPrincipal,
            idListaChequeo:idListaChequeo
        }
        
        $.ajax({
            type: 'POST',
            url: '/registro_colaborador/registrarCuentaColaborador',
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
                    case 200:
                        let url = window.location.origin + '/listachequeo/ejecucion/'+data.datos.idListaChequeo+'/'+data.datos.idListaEjecutada;
                        window.location.href = url;
                        break;

                    case 400:
                    case 406:                        
                        $('.errorServirdor').html(data.mensaje).removeClass('hidden');
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

function OnChangeEmpresas()
{
    let idEmpresa = $('.empresaControl').val();

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        url: '/registro_colaborador/cambioEmpresas',
        data: {
            idEmpresa:idEmpresa
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
                    // CARGA ESTABLECIMIENTOS
                    $('.establecimientoControl').html('');

                    $('.establecimientoControl')
                    .append($("<option></option>")
                    .attr("value",0)
                    .text('Selecciona el establecimiento')); 

                    $.each(data.datos, function (key, value) 
                    { 
                        $('.establecimientoControl')
                        .append($("<option></option>")
                        .attr("value",value.id)
                        .text(value.nombre)); 
                    });

                    $('.select2').select2();

                    break;

                case 404:

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

$('.registrarme').on('click',OnClickFormulario);
$('.empresaControl').on('change',OnChangeEmpresas);