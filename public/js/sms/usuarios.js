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
            html += '<strong>¡Usuario creado!</strong>';
        }
        else if (tipo == "editada") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Usuario editado!</strong>';
        }
        else if (tipo == "eliminada") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Usuario eliminado!</strong>';
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

$("#txtDigitos").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl+A, Command+A
        (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) ||
            // Allow: home, end, left, right, down, up
        (e.keyCode >= 35 && e.keyCode <= 40)) {
        // let it happen, don't do anything
        return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});


$("#formNuevoUsuario").submit(function (event) {
    event.preventDefault();
    event.stopPropagation();

    if ($("#txtNombre").val().trim() == "") {
        alert("Ingrese el nombre");
        return false;
    }

    if ($("#txtDigitos").val().trim() == "") {
        alert("Ingrese los 5 dígitos de su celular");
        return false;
    }

    if ($("#txtEmail").val().trim() == "") {
        alert("Ingrese su email de verifación");
        return false;
    }

    var clabe = $("#txtClabe").val();
    if ($("#txtClabe").val() == "")
        clabe = "000000000000000000";


    var datos = {
        "credencial": $("#credencial").val(),
        "nombre": $("#txtNombre").val(),
        "nombre_deposito": $("#txtNombreDeposito").val(),
        "digitos_celular": $("#txtDigitos").val(),
        "email": $("#txtEmail").val(),
        "clabe": clabe
    };

    $("#btnEnviar").attr('disabled', 'disabled');
    $.ajax({
        type: "POST",
        url: $(this).attr("action"),
        data: JSON.stringify(datos),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (result) {
            console.log(JSON.stringify(result));
            if (parseInt(result.intCodigo) == 1) {
                mensaje("ok");
                alert("Su código de usuario es: " + result.resultado.smsusuario.username.toUpperCase());
                $("#btnEnviar").removeAttr('disabled');
                $("#collapseNuevo").trigger("click");
                window.location.href = "#";
                limpiarCampos();
                $table = $('#tblUsuarios').bootstrapTable('refresh', {
                    url: '../ws/SmsUsuarios_sinformato'
                });
            }
            else {
                mensaje(result.resultado.errores);
                window.location.href = "#";
                $("#btnEnviar").removeAttr('disabled');
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest + " " + textStatus);
            mensaje("error");
        }
    });

});

limpiarCampos = function () {
    $("#txtNombre").val("");
    $("#txtNombreDeposito").val("");
    $("#txtDigitos").val("");
    $("#txtEmail").val("");
    $("#txtClabe").val("");
};


$("#btnCancelar").click(function (event) {
    event.preventDefault();
    event.stopPropagation();
    $("#collapseNuevo").trigger("click");
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
        var id = row.id;
        idEditando = id;
        //Limpiar campos
        $("#txtNombre_editar").val("");
        $("#txtNombreDeposito_editar").val("");
        $("#txtDigitos_editar").val("");
        $("#txtEmail_editar").val("");
        $("#txtClabe_editar").val("");
        $("#txtCelular_editar").val("");

        var url = "../ws/SmsUsuarios/" + idEditando;
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result)
            {
                if (parseInt(result.intCodigo) == 1)
                {
                    var smsusuario=result.resultado.smsusuario;
                    $("#txtNombre_editar").val(smsusuario.nombre);
                    $("#txtNombreDeposito_editar").val(smsusuario.nombre_deposito);
                    $("#txtDigitos_editar").val(smsusuario.digitos_celular);
                    $("#txtEmail_editar").val(smsusuario.email);
                    $("#txtClabe_editar").val(smsusuario.clabe);
                    $("#txtCelular_editar").val(smsusuario.celular);
                    $('#editarModal').modal('show');
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
            }
        });

        $("#btnEditarUsuario").click(function (event) {
            event.preventDefault();
            event.stopPropagation();

            if ($("#txtNombre_editar").val().trim() == "") {
                alert("Ingrese el nombre");
                return false;
            }

            if ($("#txtDigitos_editar").val().trim() == "") {
                alert("Ingrese los 5 dígitos de su celular");
                return false;
            }

            if ($("#txtEmail_editar").val().trim() == "") {
                alert("Ingrese su email de verifación");
                return false;
            }

            var clabe = $("#txtClabe_editar").val();
            if ($("#txtClabe_editar").val() == "")
                clabe = "000000000000000000";

            var datos = {
                "nombre": $("#txtNombre_editar").val(),
                "nombre_deposito": $("#txtNombreDeposito_editar").val(),
                "digitos_celular": $("#txtDigitos_editar").val(),
                "email": $("#txtEmail_editar").val(),
                "clabe": clabe
            };

            $("#btnEditarUsuario").attr('disabled', 'disabled');
            var url = "../ws/SmsUsuarios/" + idEditando;
            $.ajax({
                type: "POST",
                url: url,
                data: JSON.stringify(datos),
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (result) {
                    $("#btnEditarUsuario").removeAttr('disabled');
                    $('#editarModal').modal('hide');
                    if (parseInt(result.intCodigo) == 1) {
                        mensaje("editada");
                        $table = $('#tblUsuarios').bootstrapTable('refresh', {
                            url: '../ws/SmsUsuarios_sinformato'
                        });
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest + " " + textStatus);
                    mensaje("error");
                    $("#btnEditarUsuario").removeAttr('disabled');
                }
            });
        });
    },
    'click .remove': function (e, value, row, index) {
        var id = row.id;
        $('#eliminarModal').modal('show');
        $("#btnEliminarUsuario").click(function () {
            $("#btnEliminarUsuario").attr('disabled', 'disabled');
            var url = "../ws/SmsUsuarios/eliminar/" + id;
            $.ajax({
                type: "POST",
                url: url,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (result) {
                    $('#eliminarModal').modal('hide');
                    $("#btnEliminarUsuario").removeAttr('disabled');
                    if (parseInt(result.intCodigo) == 1) {
                        mensaje("eliminada");
                        $table = $('#tblUsuarios').bootstrapTable('refresh', {
                            url: '../ws/SmsUsuarios_sinformato'
                        });
                    }
                    else {
                        mensaje(result.resultado.errores);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $('#eliminarModal').modal('hide');
                    $("#btnEliminarUsuario").removeAttr('disabled');
                    console.log(XMLHttpRequest + " " + textStatus);
                    mensaje("error");
                }
            });
        });
    }
};

