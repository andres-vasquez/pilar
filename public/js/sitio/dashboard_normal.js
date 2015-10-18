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
        highlight: "#fac878"
    },
    {
        color: "#1ebfae",
        highlight: "#3cdfce"
    },
    {
        color: "#f9243f",
        highlight: "#f6495f"
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

    //Funcion para desplegar noticias
    llenarContadores = function(elemento,metodo,agrupador){
        $("#"+elemento).html("");
        $("#"+elemento).append('<i class="fa fa-spinner fa-spin fa-1x"></i>');
        var credencial=$("#credencial").val();
        var url="../pilar/api/v1/"+credencial+"/cantidad/"+metodo+"/"+agrupador;
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                $("#"+elemento).html("");
                if (parseInt(result.intCodigo) == 1) {
                    var total = parseInt(result.resultado.total);
                    $("#"+elemento).append(total);
                }
                else
                    $("#"+elemento).html("0");
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $("#"+elemento).html("0");
            }
        });
    };


    $("#btnParticipantes").click(function(event){
        event.preventDefault();
        window.open('ferias/participantes','_blank');
    });

    $("#btnNotificaciones").click(function(event){
        event.preventDefault();

        $("#btnNotificaciones").attr("disabled","disabled");
        $("#btnNotificaciones").addClass("disabled");

        if($("#txtMensaje").val()!="")
        {
            var datos={
                "mensaje":$("#txtMensaje").val(),
                "credencial":$("#credencial").val()
            };

            var url="../pilar/api/v1/gcm/envio";
            $.ajax({
                type: "POST",
                url: url,
                data: JSON.stringify(datos),
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function(result)
                {
                    result=JSON.parse(result);
                    $("#btnNotificaciones").removeAttr("disabled");
                    $("#btnNotificaciones").removeClass("disabled");

                    if(result.success==1)
                    {
                        $("#txtMensaje").val("")
                        alert("Notificación enviada")
                    }
                    else
                        alert("Notificación no enviada")

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#btnNotificaciones").removeAttr("disabled");
                    $("#btnNotificaciones").removeClass("disabled");
                    alert("Notificación no enviada")
                }
            });
        }
        else
            alert("Ingrese su mensaje para enviar como notificación");

    });



//Funcion al inicializar
    llenarContadores("numNoticias","noticias","vacio");
    llenarContadores("numEventos","eventos","vacio");
    llenarContadores("numExpositores","expositores","vacio");
    llenarContadores("numOfertas","ofertas","vacio");
    llenarContadores("numPublicidad","publicidad","slider");
    llenarContadores("numBanner","publicidad","banner");
    llenarContadores("txtParticipantes","concurso","banner");

});
