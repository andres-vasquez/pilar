/**
 * Created by andresvasquez on 3/18/15.
 */
$(document).ready(function () {
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

