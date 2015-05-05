/**
 * Created by andresvasquez on 3/18/15.
 */

$.fn.datepicker.dates['es'] = {
    days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"],
    daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
    daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa", "Do"],
    months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
    monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
};

$(document).ready(function () {
    $('.acciones').css('cursor', 'pointer');

    $("#fini_ganancia,#ffin_ganancia,#fini_mensaje,#ffin_mensaje").datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        autoclose: true
    });

    $('#fini_ganancia').datepicker().on('changeDate', function (selected) {
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('#ffin_ganancia').datepicker('setStartDate', startDate);
    });
    $('#ffin_ganancia').datepicker().on('changeDate', function (selected) {
        FromEndDate = new Date(selected.date.valueOf());
        FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
        $('#fini_ganancia').datepicker('setEndDate', FromEndDate);
    });

    $('#fini_mensaje').datepicker().on('changeDate', function (selected) {
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('#ffin_mensaje').datepicker('setStartDate', startDate);
    });
    $('#ffin_mensaje').datepicker().on('changeDate', function (selected) {
        FromEndDate = new Date(selected.date.valueOf());
        FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
        $('#fini_mensaje').datepicker('setEndDate', FromEndDate);
    });

    $('.acciones').click(function (event) {
        event.preventDefault();
        var id = $(this).attr("id");

        switch (id) {
            case "divExcel":
                $("#formExcel").submit();
                break;
            case "divCsv":
                $("#formCsv").submit();
                break;
            case "btnEditarGanancia":
                $("#ganancia").addClass("hidden");
                $("#txtGanancia").val($("#ganancia").html());
                $("#txtGanancia").removeClass("hidden");
                $("#divGuardarGanancia").removeClass("hidden");
                break;
            case "btnEditarMensaje":
                $("#mensaje_pago").addClass("hidden");
                $("#txtMensaje").val($("#mensaje_pago").html());
                $("#txtMensaje").removeClass("hidden");
                $("#divGuardarMensaje").removeClass("hidden");
                break;
        }
    });


    limpiarCamposGanacia = function () {
        $("#txtGanancia").val("");
        $("#fini_ganancia").val("");
        $("#ffin_ganancia").val("");
        $("#chkIndGanancia").attr('checked', false);
        $("#ganancia").removeClass("hidden");
        $("#txtGanancia").addClass("hidden");
        $("#divGuardarGanancia").addClass("hidden");
    };

    $("#chkIndGanancia").click(function () {
        if ($(this).is(':checked')) {
            $("#ffin_ganancia").val("");
            $("#ffin_ganancia").addClass("disabled");
        }
        else {
            $("#ffin_ganancia").removeClass("disabled");
        }
    });

    $("#btnGuardarGanancia").click(function (event) {
        event.preventDefault();
        var url = "../ws/SmsConfiguracion/editar";

        var fecha_fin;
        if ($("#chkIndGanancia").is(':checked'))
            fecha_fin = "0000-00-00 00:00:00";
        else
            fecha_fin = $("#ffin_ganancia").val();


        if($("#txtGanancia").val()=="")
        {
            alert("Ingrese el valor de retorno al usuario");
            return false;
        }

        if($("#fini_ganancia").val()=="")
        {
            alert("Ingrese la fecha de inicio");
            return false;
        }

        if(fecha_fin=="")
        {
            alert("Ingrese la fecha de finalización o marque indefinido");
            return false;
        }


        var data = {
            "ganancia": $("#txtGanancia").val(),
            "fecha_inicio": $("#fini_ganancia").val(),
            "fecha_fin": fecha_fin
        };

        $("#btnGuardarGanancia").addClass("disabled");
        $.ajax({
            type: "POST",
            url: url,
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                $("#btnGuardarGanancia").removeClass("disabled");
                if (parseInt(result.intCodigo) == 1) {
                    limpiarCamposGanacia();
                    obtenerConfiguraciones();
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
                $("#btnGuardarGanancia").removeClass("disabled");
            }
        });
    });


    limpiarCamposMensaje = function () {
        $("#txtMensaje").val("");
        $("#fini_mensaje").val("");
        $("#ffin_mensaje").val("");
        $("#chkIndMensaje").attr('checked', false);
        $("#mensaje_pago").removeClass("hidden");
        $("#txtMensaje").addClass("hidden");
        $("#divGuardarMensaje").addClass("hidden");
    };

    $("#chkIndMensaje").click(function () {
        if ($(this).is(':checked')) {
            $("#ffin_mensaje").val("");
            $("#ffin_mensaje").addClass("disabled");
        }
        else {
            $("#ffin_mensaje").removeClass("disabled");
        }
    });

    $("#btnGuardarMensaje").click(function (event) {
        event.preventDefault();
        var url = "../ws/SmsConfiguracion/editar";
        var fecha_fin;
        if ($("#chkIndMensaje").is(':checked'))
            fecha_fin = "0000-00-00 00:00:00";
        else
            fecha_fin = $("#ffin_mensaje").val();

        if($("#txtMensaje").val()=="")
        {
            alert("Ingrese el mensaje para el usuario");
            return false;
        }

        if($("#fini_mensaje").val()=="")
        {
            alert("Ingrese la fecha de inicio");
            return false;
        }

        if(fecha_fin=="")
        {
            alert("Ingrese la fecha de finalización o marque indefinido");
            return false;
        }

        var data = {
            "mensaje": $("#txtMensaje").val(),
            "fecha_inicio": $("#fini_mensaje").val(),
            "fecha_fin": fecha_fin
        };

        $("#btnGuardarMensaje").addClass("disabled");
        $.ajax({
            type: "POST",
            url: url,
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                $("#btnGuardarMensaje").removeClass("disabled");
                if (parseInt(result.intCodigo) == 1) {
                    limpiarCamposMensaje();
                    obtenerConfiguraciones();
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
                $("#btnGuardarMensaje").removeClass("disabled");
            }
        });

    });


    $("#formImportar").submit(function (event) {
        event.preventDefault();
        var url = "../ws/SmsMensaje";
        var formData = new FormData($(this)[0]);

        $("#btnImportar").addClass("disabled");
        $("#prgImportar").removeClass("hidden");

        $("#divResultadoImportar").html("");
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function (result) {
                $("#prgImportar").addClass("hidden");
                $("#btnImportar").removeClass("disabled");
                result = JSON.parse(result);
                console.log(JSON.stringify(result));
                if (parseInt(result.intCodigo) == 1) {
                    var objResultado = result.resultado.objResultado;
                    $("#fileCsv").val("");
                    var html = '<br/><br/>';
                    html += '<p><b>Resultado de importación</b></p>';
                    html += '<p>Importados: <b>' + objResultado.insertados + '</b></p>';
                    html += '<p>Existentes: <b>' + objResultado.existentes + '</b></p>';
                    html += '<p>Error:  <b></b>' + objResultado.error + '</p>';
                    $("#divResultadoImportar").html(html);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $("#btnImportar").removeClass("disabled");
                $("#prgImportar").addClass("hidden");
                console.log(XMLHttpRequest + " " + textStatus);
            }
        });
    });

    obtenerConfiguraciones = function () {
        var url = "../ws/SmsConfiguracion";
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                if (parseInt(result.intCodigo) == 1) {
                    var objConfig = result.resultado.config;
                    $("#ultimo_mensaje").html("Último mensaje: " + objConfig.ultimo_mensaje);
                    $("#ganancia").html(objConfig.ganancia);
                    $("#ultimo_ganancia").html("Actualizado: " + objConfig.fecha_inicio);
                    $("#mensaje_pago").html("" + objConfig.mensaje_pago);
                    $("#valido_mensaje").html("Se mostrará hasta: " + objConfig.valido_mensaje);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $("#ultimo_mensaje").html("");
                $("#ganancia").html("");
                $("#ultimo_ganancia").html("");
                $("#mensaje_pago").html("");
                $("#valido_mensaje").html("");
            }
        });
    };


    var idInsertada = 0;
    mensaje = function (tipo) {
        $("#mensaje").html('');
        $("#mensaje").addClass("alert");
        var html = '';
        if (tipo == "ok") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Expositores importados!</strong>';
        }
        else if (tipo == "editada") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Expositor editado!</strong>';
        }
        else if (tipo == "eliminada") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Expositor eliminado!</strong>';
        }
        else {
            $("#mensaje").removeClass("alert-success");
            $("#mensaje").addClass("alert-danger");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Error!</strong> ' + tipo;
        }
        $("#mensaje").html(html);
    };

    obtenerConfiguraciones();
});

function operateFormatter(value, row, index) {
    return [
        '<a class="edit ml10" href="javascript:void(0)" title="Editar">',
        '<i class="glyphicon glyphicon-pencil"></i>',
        '</a> ',
        '<a class="remove ml10" href="javascript:void(0)" title="Eliminar">',
        '<i class="glyphicon glyphicon-remove"></i>',
        '</a>'
    ].join('');
}

window.operateEvents = {
    'click .edit': function (e, value, row, index) {
        /*var id = row.id;
         idEditando = id;
         $('#editarModal').modal('show');
         var url = "../ws/publicidad/" + id;
         $.ajax({
         type: "GET",
         url: url,
         contentType: "application/json; charset=utf-8",
         dataType: "json",
         success: function (result) {
         if (parseInt(result.intCodigo) == 1) {
         var objPublicidad = result.resultado.publicidad[0];
         $("#txtNombre_editar").val(objPublicidad.nombre);
         $("#txtDescripcion_editar").val(objPublicidad.descripcion);
         $("#txtUrlAnuncio_editar").val(objPublicidad.link);
         $('#cmbPrioridad_editar option[value="' + objPublicidad.prioridad + '"]').attr("selected", "selected");
         $(".id_publicidad").val(objPublicidad.id);

         $("img").attr("src", "http://placehold.it/320x47&text=publicidad");

         var objListaImagenes = objPublicidad.imagenes;
         lstImagenesEdit = objListaImagenes;
         for (var i = 0; i < objListaImagenes.length; i++) {
         var obj = objListaImagenes[i];
         var id_imagen_dinamico = obj.tipo + "_" + obj.sizex + "x" + obj.sizey + "_editar";
         var ruta = obj.ruta;
         $("#" + id_imagen_dinamico).attr("src", ruta);
         }


         }
         },
         error: function (XMLHttpRequest, textStatus, errorThrown) {
         $('#eliminarModal').modal('hide');
         $("#btnEliminarPublicidad").removeAttr('disabled');
         console.log(XMLHttpRequest + " " + textStatus);
         $('#editarModal').modal('hide');
         }
         });*/

    },
    'click .remove': function (e, value, row, index) {
        /*var id = row.id;
         $('#eliminarModal').modal('show');
         $("#btnEliminarPublicidad").click(function () {
         $("#btnEliminarPublicidad").attr('disabled', 'disabled');
         var url = "../ws/publicidad/eliminar/" + id;
         $.ajax({
         type: "POST",
         url: url,
         contentType: "application/json; charset=utf-8",
         dataType: "json",
         success: function (result) {
         $('#eliminarModal').modal('hide');
         $("#btnEliminarPublicidad").removeAttr('disabled');
         if (parseInt(result.intCodigo) == 1) {
         mensaje("eliminada");
         $table = $('#tblPublicidad').bootstrapTable('refresh', {
         url: '../api/v1/publicidad/' + $("#credencial").val() + '/sinformato'
         });
         }
         else {
         mensaje(result.resultado.errores);
         }
         },
         error: function (XMLHttpRequest, textStatus, errorThrown) {
         $('#eliminarModal').modal('hide');
         $("#btnEliminarPublicidad").removeAttr('disabled');
         console.log(XMLHttpRequest + " " + textStatus);
         mensaje("error");
         }
         });
         });*/
    }
};

