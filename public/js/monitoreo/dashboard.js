/**
 * Created by andresvasquez on 11/12/15.
 */

$(document).ready(function()
{
    llenarGrafico = function (ano, mes) {
        var url = "../pilar/ws/drclipling/reportes/dashboard/" + ano + "/" + mes;
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                if (result.length == 0)
                {
                    alert("No tenemos registro de actividad en el mes seleccionado");
                }
                else {
                    var labels = [];
                    var datos = [];

                    var datos1 = [];

                    var resultadoPublicaciones=result.publicaciones;
                    var resultadoAnalisis=result.analisis;

                    for (var i = 0; i < resultadoPublicaciones.length; i++) {
                        labels.push(resultadoPublicaciones[i].fecha);
                        datos.push(resultadoPublicaciones[i].cantidad);
                    }

                    for (var i = 0; i < resultadoAnalisis.length; i++) {
                        datos1.push(resultadoAnalisis[i].cantidad);
                    }

                    var lineChartData = {
                        labels: labels,
                        datasets: [
                            {
                                label: "Estadisticas publicaciones",
                                fillColor: "rgba(48, 164, 255, 0.2)",
                                strokeColor: "rgba(48, 164, 255, 1)",
                                pointColor: "rgba(48, 164, 255, 1)",
                                pointStrokeColor: "#fff",
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(48, 164, 255, 1)",
                                data: datos
                            },
                            {
                                label: "Estadisticas publicaciones",
                                fillColor: "rgba(249, 36, 63, 0.1)",
                                strokeColor: "rgba(249, 36, 63, 1)",
                                pointColor: "rgba(249, 36, 63, 1)",
                                pointStrokeColor: "#fff",
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(249, 36, 63, 1)",
                                data: datos1
                            }
                        ]
                    };

                    var lineChartData1 = {
                        labels: labels,
                        datasets: [

                        ]
                    };

                    if(window.myLine){
                        window.myLine.destroy();
                    }
                    var chart1 = document.getElementById("line-chart").getContext("2d");
                    window.myLine = new Chart(chart1).Line(lineChartData, {
                        responsive: true,
                        datasetFill : true
                    });
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
            }
        });
    };

    $('body').on('click',".mes",function (event) {
        event.preventDefault();
        event.stopPropagation();
        var target = $(this).attr("target");
        var ano = d.getFullYear();
        llenarGrafico(ano,parseInt(target));
        $("#btnSelecciondo").html("Seleccionado: " + $(this).html());
        return false;
    });

    llenarLinksMeses=function()
    {
        var d = new Date();
        var mes = d.getMonth()+1;
        $("#btnSelecciondo").html("Seleccionado: " + literalMeses(mes));
        for(var i=mes;i>mes-5;i--)
            $("#ulMeses").append('<li><a href="#" class="mes" target="'+i+'">'+literalMeses(i)+'</a></li>');
    };

    literalMeses=function(idMes)
    {
        switch (idMes)
        {
            case 1: return "Enero";
            case 2: return "Febrero";
            case 3: return "Marzo";
            case 4: return "Abril";
            case 5: return "Mayo";
            case 6: return "Junio";
            case 7: return "Julio";
            case 8: return "Agosto";
            case 9: return "Septiembre";
            case 10: return "Octubre";
            case 11: return "Noviembre";
            case 12: return "Diciembre";
        }
    };

    var d = new Date();
    var mes = d.getMonth();
    var ano = d.getFullYear();
    llenarGrafico(ano, mes + 1);
    llenarLinksMeses();
});

