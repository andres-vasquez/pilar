/**
 * Created by andresvasquez on 3/18/15.
 */
$(document).ready(function()
{
    var totalNoticias=0;


    //Funcion para desplegar noticias
    llenarNoticias = function(){
        $("#numNoticias").html("");
        $("#numNoticias").append('<i class="fa fa-spinner fa-spin fa-1x"></i>');
        var credencial=$("#credencial").val();

        var url="pilar/api/v1/noticias/"+credencial+"/";
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                $("#numNoticias").html("");
                if(parseInt(result.intCodigo)==1)
                {
                    totalNoticias=parseInt(result.resultado.total);
                    $("#numNoticias").append(totalNoticias);
                }
                else
                    $("#numNoticias").html("0");
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $("#numNoticias").html("0");
            }
        });
    };



    //Funcion al inicializar
    llenarNoticias();
});
