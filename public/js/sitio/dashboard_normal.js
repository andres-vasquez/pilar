/**
 * Created by andresvasquez on 3/18/15.
 */

var arrayColores=[
    {
        color:"#30a5ff",
        highlight: "#62b9fb"
    },
    {
        color: "#ffb53e",
        highlight: "#fac878",
    },
    {
        color: "#1ebfae",
        highlight: "#3cdfce",
    },
    {
        color: "#f9243f",
        highlight: "#f6495f",
    },
    {
        color:"#9C27B0",
        highlight: "#BA68C8"
    },
    {
        color:"#2196F3",
        highlight: "#90CAF9"
    },
    {
        color:"#CDDC39",
        highlight: "#DCE775"
    },
    {
        color:"#FF9800",
        highlight: "#FFB74D"
    },
    {
        color:"#607D8B",
        highlight: "#90A4AE"
    },
    {
        color:"#8BC34A",
        highlight: "#AED581"
    }
];


$(document).ready(function()
{
    var totalNoticias=0;
    var totalAnuncios=0;
    var totalLikes=0;
    var totalExpositores=0;

    llenarLikes= function(){
        $("#numLikes").html("");
        $("#numLikes").append('<i class="fa fa-spinner fa-spin fa-1x"></i>');
        var credencial=$("#credencial").val();

        var url="../pilar/api/v1/expositoreslikes/"+credencial+"/reporte";
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                $("#numLikes").html("");
                if(parseInt(result.intCodigo)==1)
                {
                    totalLikes=parseInt(result.resultado.resultado.total);
                    $("#numLikes").append(totalLikes);

                    var lstVotos=result.resultado.resultado.resultado;
                    var pieData=[];

                    for(var i=0;i<10;i++)
                    {
                        var objColor=arrayColores[i];
                        var objVoto=lstVotos[i];

                        var obj={
                            value: parseInt(objVoto.conteo),
                            color:objColor.color,
                            highlight: objColor.highlight,
                            label: objVoto.expositor_nombre
                        };
                        pieData.push(obj);
                    }

                    var chart4 = document.getElementById("pie-chart").getContext("2d");
                    window.myPie = new Chart(chart4).Pie(pieData, {
                        responsive : true
                    });
                }
                else
                    $("#numLikes").html("0");
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
                $("#numLikes").html("0");
            }
        });
    };

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

    $("#btnParticipantes").click(function(event){
        event.preventDefault();


    });

    //Funcion al inicializar
    llenarLikes();
    llenarNoticias();
    llenarAnuncios();
    llenarExpositores();
});
