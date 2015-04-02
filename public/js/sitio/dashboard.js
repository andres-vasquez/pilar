/**
 * Created by andresvasquez on 3/18/15.
 */
$(document).ready(function()
{
    var totalNoticias=0;
    var totalAnuncios=0;
    var totalLikes=0;
    var totalExpositores=0;


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

    llenarAnuncios = function(){
        $("#numAnuncios").html("");
        $("#numAnuncios").append('<i class="fa fa-spinner fa-spin fa-1x"></i>');
        var credencial=$("#credencial").val();

        var url="../pilar/api/v1/publicidad/"+credencial+"/sinformato";
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                $("#numAnuncios").html("");
                try
                {
                    totalAnuncios=result.length;
                    $("#numAnuncios").append(totalAnuncios);
                }
                catch(e){
                    $("#numAnuncios").html("0");
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $("#numAnuncios").html("0");
            }
        });
    };

    llenarExpositores = function(){
        $("#numExpositores").html("");
        $("#numExpositores").append('<i class="fa fa-spinner fa-spin fa-1x"></i>');
        var credencial=$("#credencial").val();

        var url="../pilar/api/v1/expositores/"+credencial+"/sinformato";
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                $("#numExpositores").html("");
                try
                {
                    totalExpositores=result.length;
                    $("#numExpositores").append(totalExpositores);
                }
                catch(e){
                    $("#numExpositores").html("0");
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $("#numExpositores").html("0");
            }
        });
    };

    //Funcion al inicializar
    llenarNoticias();
    llenarAnuncios();
    llenarExpositores();
});
