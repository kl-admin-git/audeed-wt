$(document).ready(function () 
{
    InicializacionPaginacion($('.pagination'),1,1);
});





function OnClickVerUsuarioAsignados() 
{
    $('#visualizarUsuarioAsignado').modal('show');
}



// PAGINACION
var pageNum = 0, pageOffset = 0;
function InicializacionPaginacion(baseElement, pages, pageShow) 
{
    _initNav(baseElement,pageShow,pages);
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
        if($(e.target).is('.nav-btn')){
        var toPage = $(this).hasClass('prev') ? pageNum-1 : pageNum+1;
        }else{
        var toPage = $(this).index(); 
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