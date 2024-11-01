$(document).ready(function () 
{
    let formulario = $('#formularioRegistrar').parsley();
    formulario.options.requiredMessage = "Este campo es requerido";
    formulario.options.equaltoMessage = "Las contraseña no coinciden";
    $('.select2').select2();
    $('.sectorControl').select2({dropdownAutoWidth : true});
});

function CargandoMostrar() { $('#status').fadeIn(); $('#preloader').fadeIn('slow'); }
function CargandoNoMostrar() { $('#status').fadeOut(); $('#preloader').fadeOut('slow'); }

function OnClickFormulario(e) 
{ 
    e.preventDefault();
    $('.errorServer').addClass('hidden');
    var form = $('#formularioRegistrar');

    form.parsley().validate();    

    if (form.parsley().isValid())
    {
        if($('.sectorControl').val() == 0)
        {
            $('.errorSector').removeClass('hidden');
            return;
        }
        else
            $('.errorSector').addClass('hidden');

        let email = $('#inputCorreo').val();
        let paisCodigo = ($('#telefono').val() == '' ? '' : $('#paisCode').val());
        let telefono = $('#telefono').val();
        let password = $('#paswordRegistro').val();
        let sector = $('.sectorControl').val();

        let objetoEnviar = 
        {
            correo:email,
            paisCodigo:paisCodigo,
            telefono:telefono,
            password:password,
            sector:sector
        }
        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/registro_cuenta',
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
                        let url = window.location.origin + '/informes/cumplimientoLista';
                        window.location.href = url;
                        break;

                    case 400:
                    case 406:                        
                        $('.errorServer').html(data.mensaje).removeClass('hidden');
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

$('.registrarme').on('click',OnClickFormulario);