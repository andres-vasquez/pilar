/**
 * Created by andresvasquez on 4/28/15.
 */

$(document).ready(function () {
    var totalUsuarios;
    var totalSms;
    var totalBalance;
    var totalSmsTotal;

    llenarGrafico = function (ano, mes) {
        var url = "../pilar/ws/SmsMensaje/dashboard/" + ano + "/" + mes;
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

                    for (var i = 0; i < result.length; i++) {
                        labels.push(result[i].fecha);
                        datos.push(result[i].cantidad);
                    }

                    var lineChartData = {
                        labels: labels,
                        datasets: [
                            {
                                label: "Estadisticas mensajes",
                                fillColor: "rgba(48, 164, 255, 0.2)",
                                strokeColor: "rgba(48, 164, 255, 1)",
                                pointColor: "rgba(48, 164, 255, 1)",
                                pointStrokeColor: "#fff",
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(48, 164, 255, 1)",
                                data: datos
                            }
                        ]
                    };
                    if(window.myLine){
                        window.myLine.destroy();
                    }
                    var chart1 = document.getElementById("line-chart").getContext("2d");
                    window.myLine = new Chart(chart1).Line(lineChartData, {
                        responsive: true
                    });
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
            }
        });
    };

    llenarUsuarios = function () {
        $("#numUsuarios").html("");
        $("#numUsuarios").append('<i class="fa fa-spinner fa-spin fa-1x"></i>');
        var credencial = $("#credencial").val();

        var url = "../pilar/ws/SmsUsuarios/cantidad?credencial=" + credencial + "";
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                $("#numUsuarios").html("");
                if (parseInt(result.intCodigo) == 1) {
                    totalUsuarios = parseInt(result.resultado.total);
                    $("#numUsuarios").append(totalUsuarios);
                }
                else
                    $("#numUsuarios").html("0");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
                $("#numUsuarios").html("0");
            }
        });
    };

    llenarMes = function () {
        $("#numMes").html("");
        $("#numMes").append('<i class="fa fa-spinner fa-spin fa-1x"></i>');
        var credencial = $("#credencial").val();

        var url = "../pilar/ws/SmsMensaje/cantidad/0?credencial=" + credencial + "";
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                $("#numMes").html("");
                if (parseInt(result.intCodigo) == 1) {
                    totalSms = parseInt(result.resultado.total);
                    $("#numMes").append(totalSms);
                }
                else
                    $("#numMes").html("0");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
                $("#numMes").html("0");
            }
        });
    };

    llenarTotal = function () {
        $("#numTotal").html("");
        $("#numTotal").append('<i class="fa fa-spinner fa-spin fa-1x"></i>');
        var credencial = $("#credencial").val();

        var url = "../pilar/ws/SmsMensaje/cantidad/1?credencial=" + credencial + "";
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                $("#numTotal").html("");
                if (parseInt(result.intCodigo) == 1) {
                    totalSmsTotal = parseInt(result.resultado.total);
                    $("#numTotal").append(totalSmsTotal);
                }
                else
                    $("#numTotal").html("0");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
                $("#numTotal").html("0");
            }
        });
    };

    $(".mes").click(function (event) {
        event.preventDefault();
        event.stopPropagation();
        var target = $(this).attr("target");
        var ano = d.getFullYear();
        llenarGrafico(ano,parseInt(target));
        $("#btnSelecciondo").html("Seleccionado: " + $(this).html());
        return false;
    });


    //Funcion al inicializar
    llenarUsuarios();
    llenarMes();
    //TODO LlenarBalance
    llenarTotal();

    var d = new Date();
    var mes = d.getMonth();
    var ano = d.getFullYear();
    llenarGrafico(ano, mes + 1);
});
