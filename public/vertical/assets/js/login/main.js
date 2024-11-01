$(document).ready(function () 
{
    let formulario = $('#formularioLogin').parsley();
    formulario.options.requiredMessage = "Este campo es requerido";
});

function CargandoMostrar() { $('#status').fadeIn(); $('#preloader').fadeIn(); }
function CargandoNoMostrar() { $('#status').fadeOut(); $('#preloader').fadeOut(); }

function OnClickFormulario(e) 
{ 
    e.preventDefault();
    var form = $('#formularioLogin');
    $('.errorTextos').addClass('hidden');
    form.parsley().validate();    

    if (form.parsley().isValid())
    {
        let email = $(form).find('#email').val();
        let password = $(form).find('#userpassword').val();
        let recuerdame = $(form).find('#customControlInline').is(':checked');
        
        $.ajax({
            type: 'POST',
            url: '/autenticacion',
            data: 
            {
                _token: $('meta[name="csrf-token"]').attr('content'),
                email:email,
                password:password,
                recuerdame:recuerdame
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
                        if(data.datos == 1)
                        {
                            let url = window.location.origin + '/informes/cumplimientoLista';
                            window.location.href = url;
                        }
                        else
                        {
                            let url = window.location.origin + '/dashboard';
                            window.location.href = url;
                        }
                        
                        break;

                    case 400:
                        $('.errorTextos').html(data.mensaje).removeClass('hidden');
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
}

$('.iniciarSesion').on('click',OnClickFormulario);