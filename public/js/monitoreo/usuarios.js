/**
 * Created by andresvasquez on 11/12/15.
 */

$(document).ready(function()
{
    $("#formNuevoUsuario").submit(function(event){
        event.preventDefault();
        event.stopPropagation();


        if($("#txtPassword").val()!=$("#txtPassword2").val())
        {
            alert("Las contraseñas ingresadas no coinciden");
            return;
        }

        var datos = {
            "nombre_completo": $("#txtNombre").val(),
            "email": $("#txtEmail").val(),
            "password": encriptar($("#txtPassword").val()),
            "celular": $("#txtCelular").val(),
            "imei": $("#txtImei").val()
        };

        $("#btnEnviar").attr('disabled', 'disabled');
        $.ajax({
            type: "POST",
            url: "../ws/drclipling/usuarios",
            data: JSON.stringify(datos),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                console.log(JSON.stringify(result));
                if (parseInt(result.intCodigo) == 1) {
                    mensaje("ok");
                    $("#btnEnviar").removeAttr('disabled');
                    $("#collapse").trigger("click");
                    window.location.href = "#";
                    limpiarCampos();
                    $table = $('#tblUsuarios').bootstrapTable('refresh', {
                        url: '../ws/drclipling/usuarios_sinformato'
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

    limpiarCampos=function(){
        $("#txtNombre").val("");
        $("#txtPassword").val("");
        $("#txtPassword2").val("");
        $("#txtEmail").val("");
        $("#txtCelular").val("");
        $("#txtImei").val("");
    };

    encriptar=function(password){
        var hash = CryptoJS.MD5(password);
        return CryptoJS.MD5(hash.toString().substring(0,10)).toString();
    };

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
        alert("Disponible en la una siguiente versión");
    },
    'click .remove': function (e, value, row, index) {
        var id = row.id;
        $('#eliminarModal').modal('show');
        $("#btnEliminarPublicidad").click(function () {
            alert("Disponible en la una siguiente versión");
        });
    }
};

