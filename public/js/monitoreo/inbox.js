/**
 * Created by andresvasquez on 11/12/15.
 */

$(document).ready(function()
{

    $("body").on("click", ".item-lista", function(event){
        event.preventDefault();
        event.stopPropagation();

        var id=$(this).attr("id");

        $(".item-lista" ).each(function( index ) {
            if($(this).attr("id")==id)
                $(this).addClass("active");
            else
                $(this).removeClass("active");
        });

        $("#cargandoTarea").removeClass("hidden");

    });

    llenarLista=function(estado,inicio,fin)
    {
        for(var i=0;i<=10;i++)
        {
            var activo="";
            if(inicio==0 && i==0)
                activo="active";

            var html='<a href="#" id="'+i+'" class="list-group-item item-lista '+activo+'">';
            html+='<h4 class="list-group-item-heading">Título del elemento de la lista</h4>';
            html+='<p class="list-group-item-text">...</p>';
            html+='</a>';
            $("#lstTareas").append(html);
        }
    };

    llenarLista(1,0,5);
});

