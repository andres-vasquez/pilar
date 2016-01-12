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

        if($("#cmbPerfil").val()=="0")
        {
            alert("Seleccione un perfil");
            return;
        }

        if($("#cmbPerfil").val()=="1")//Usuarios de la App movil
        {
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
        }
        else//Usuario de pilar
        {
            var tags="";
            try
            {
                var cmbEtiquetas=$("#cmbDia").val();
                for(var i=0;i<lstTagsSeleccionados.length;i++)
                    tags+=lstTagsSeleccionados[i]+",";
            }catch (ex){}
            tags=tags.substring(0,tags.length-1);


            var perfil_id=0;
            switch (parseInt($("#cmbPerfil").val()))
            {
                case 2: perfil_id=18; break;
                case 3: perfil_id=19; break;
                case 4: perfil_id=20; break;
            }

            var datos = {
                "nombre": $("#txtNombre").val(),
                "email": $("#txtEmail").val(),
                "password": encriptar($("#txtPassword").val()),
                "perfil_id":perfil_id,
                "args":tags
            };

            $("#btnEnviar").attr('disabled', 'disabled');
            $.ajax({
                type: "POST",
                url: "../ws/pilar/drclipling/usuarios",
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
        }
    });

    $("#cmbPerfil").change(function()
    {
        if($(this).val()=="0" || $(this).val()=="2" || $(this).val()=="4")
        {
            $("#divCelular,#divImei").addClass("hidden");
            $("#txtCelular").val("");
            $("#txtImei").val("");
            $("#divTags").addClass("hidden");
            $("#cmbTags").html("").trigger("chosen:updated");
        }
        else if($(this).val()=="1")//Researcher
        {
            $("#divCelular,#divImei").removeClass("hidden");
            $("#divTags").addClass("hidden");
            $("#cmbTags").html("").trigger("chosen:updated");
        }
        else if($(this).val()=="3")//Cliente
        {
            $("#divCelular,#divImei").addClass("hidden");
            $("#divTags").removeClass("hidden");
            $(".chosen-select").chosen();
            $(".chosen-container").css("width", "100%");
            llenarTags();
        }
    });

    limpiarCampos=function(){
        $("#txtNombre").val("");
        $("#txtPassword").val("");
        $("#txtPassword2").val("");
        $("#txtEmail").val("");
        $("#txtCelular").val("");
        $("#txtImei").val("");
        $("#cmbTags").html("").trigger("chosen:updated");
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


    llenarTags=function()
    {
        var html='';
        var url="../ws/drclipling/tags";
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                if(parseInt(result.intCodigo)==1) {

                    var objTags = result.resultado.drclippingtags;
                    for(var i=0;i<objTags.length;i++)
                        html+='<option value="'+objTags[i].id+'">'+objTags[i].nombre+'</option>';


                    $(".chosen-select").chosen();
                    $(".chosen-container").css("width", "100%");
                    $("#cmbTags").html(html).chosen({
                        create_option_text:"Agregar tag:",
                        no_results_text:"No se encontraron resultados",
                        create_option: function(term) {
                            var chosen = this;
                            chosen.append_option({
                                value: 'nuevo_' + term,
                                text: term
                            });
                        }
                    });


                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
            }
        });

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
        var perfil=row.perfil;

        $('#editarModal').modal('show');

        $("#chkRestablecer").change(function(){
            if($(this).is(':checked'))
            {

            }
            else
            {

            }
        });


        $("#btnEditarUsuario").click(function (event) {
            event.preventDefault();
            event.stopPropagation();



        });


    },
    'click .remove': function (e, value, row, index) {
        var id = row.id;
        var perfil=row.perfil;

        $('#eliminarModal').modal('show');
        $("#btnEliminarUsuario").click(function (event) {
            event.preventDefault();
            event.stopPropagation();

            if(perfil=="Researcher")
            {
                var url="../ws/drclipling/usuarios/eliminar/"+id;
                $.ajax({
                    type: "POST",
                    url: url,
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function(result)
                    {
                        if(parseInt(result.intCodigo)==1) {
                            mensaje("eliminada");
                            $table = $('#tblUsuarios').bootstrapTable('refresh', {
                                url: '../ws/drclipling/usuarios_sinformato'
                            });
                        }
                        else
                        {
                            mensaje("Error al eliminar usuario");
                        }
                        $('#eliminarModal').modal('hide');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log(XMLHttpRequest + " "+textStatus);
                    }
                });
            }
            else
            {
                var url="../ws/usuario/eliminar/"+id;
                $.ajax({
                    type: "POST",
                    url: url,
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function(result)
                    {
                        if(parseInt(result.intCodigo)==1) {
                            mensaje("eliminada");
                            $table = $('#tblUsuarios').bootstrapTable('refresh', {
                                url: '../ws/drclipling/usuarios_sinformato'
                            });
                        }
                        else
                        {
                            mensaje("Error al eliminar usuario");
                        }
                        $('#eliminarModal').modal('hide');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log(XMLHttpRequest + " "+textStatus);
                    }
                });
            }
        });
    }
};

