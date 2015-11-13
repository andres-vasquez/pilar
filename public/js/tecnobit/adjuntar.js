/**
 * Created by andresvasquez on 3/18/15.
 */
var editando=false;
var idEditando=0;

$(document).ready(function () {
    var idInsertada = 0;
    var lstRubros;

    $("form").submit(function (event) {
        var id = $(this).attr("id");
        event.preventDefault();

        var btnSubmit = $(this).find(':submit');
        var htmlCargando='<i class="fa fa-spinner fa-spin"></i> Cargando';
        var htmlCargado='<i class="fa fa-check"></i> Cargado';
        var htmlError='<i class="fa fa-close"></i> Error';

        if(id=="adjunto")
        {
            var _validFileExtensions = [".jpg", ".jpeg", ".png"];
            var formData = new FormData($(this)[0]);

            btnSubmit.html(htmlCargando);
            btnSubmit.attr('disabled', 'disabled');

            var url = "../ws/funciones/subirAdjuntoAWS";
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (result)
                {
                    btnSubmit.removeAttr('disabled');
                    result = JSON.parse(result);
                    if (parseInt(result.intCodigo) == 1)
                    {

                        var data=result.resultado.data;
                        var ruta=data.ruta;
                        var ruta_aws=data.ruta_aws.replace("s3.amazonaws.com/siriustecnobit","siriustecnobit.s3.amazonaws.com");

                        if(!editando)
                        {
                            $("#ruta").val(ruta);
                            $("#ruta_aws").val(ruta_aws);
                            $("#inputAdjunto").val("");
                        }
                        else
                        {
                            $("#ruta_editar").val(ruta);
                            $("#ruta_aws_editar").val(ruta_aws);
                            $("#inputAdjunto_editar").val("");
                        }

                        btnSubmit.html(htmlCargado);
                    }
                    else
                    {
                        alert("Error al subir la imagen");
                        btnSubmit.html(htmlError);
                    }
                    //console.log(JSON.stringify(result));
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    btnSubmit.removeAttr('disabled');
                    console.log(XMLHttpRequest + " " + textStatus);
                    btnSubmit.html(htmlError);
                }
            });
        }
        else if(id=="formThumbnail")
        {
            var _validFileExtensions = [".jpg", ".jpeg", ".png"];
            var formData = new FormData($(this)[0]);

            btnSubmit.html(htmlCargando);
            btnSubmit.attr('disabled', 'disabled');

            var url = "../ws/funciones/subirAdjuntoAWS";
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (result)
                {
                    btnSubmit.removeAttr('disabled');
                    result = JSON.parse(result);
                    if (parseInt(result.intCodigo) == 1)
                    {

                        var data=result.resultado.data;
                        var ruta_aws=data.ruta_aws.replace("s3.amazonaws.com/siriustecnobit","siriustecnobit.s3.amazonaws.com");

                        if(!editando)
                        {
                            $("#thumbnail").val(ruta_aws);
                            $("#inputAdjuntoThumbnail").val("");
                        }
                        else
                        {
                            /*$("#ruta_editar").val(ruta);
                            $("#ruta_aws_editar").val(ruta_aws);
                            $("#inputAdjunto_editar").val("");*/
                        }

                        btnSubmit.html(htmlCargado);
                    }
                    else
                    {
                        alert("Error al subir la imagen");
                        btnSubmit.html(htmlError);
                    }
                    //console.log(JSON.stringify(result));
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    btnSubmit.removeAttr('disabled');
                    console.log(XMLHttpRequest + " " + textStatus);
                    btnSubmit.html(htmlError);
                }
            });
        }
    });

    $("#btnEnviar").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        if($("#txtTitulo").val()=="")
        {
            alert("Ingrese el tiítulo");
            return;
        }

        if($("#ruta").val()=="" && $("#ruta_aws").val()=="")
        {
            alert("Por favor adjunte su revista");
            return;
        }

        if($("#agrupador").val()=="tecnobit_revista")
        {
            if($("#thumbnail").val()=="0")
            {
                alert("Por favor adjunte una imagen miniatura");
                return;
            }
        }

        var datos= {
            "nombre":$("#txtTitulo").val(),
            "sistema_id":$("#sistema_id").val(),
            "agrupador":$("#agrupador").val(),
            "ruta":$("#ruta").val(),
            "ruta_aws":$("#ruta_aws").val(),
            "thumbnail":$("#thumbnail").val()
        };

        $("#btnEnviar").attr('disabled', 'disabled');
        var url = "../ws/tecnobit/adjuntos";
        $.ajax({
            type: "POST",
            url: url,
            data:  JSON.stringify(datos),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                if(parseInt(result.intCodigo)==1)
                {
                    mensaje("ok");
                    $("#btnEnviar").removeAttr('disabled');
                    $("#collapseNuevo").trigger("click");
                    window.location.href="#";
                    $table = $('#tblRevistas').bootstrapTable('refresh', {
                        url: '../ws/tecnobit/adjuntos/'+$("#credencial").val()+'/'+$("#agrupador").val()
                    });
                }
                else
                {
                    mensaje(result.resultado.errores);
                    window.location.href="#";
                    $("#btnEnviar").removeAttr('disabled');
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
                mensaje("error");
            }
        });

    });



    $("#collapseNuevo").click(function(event){
        editando=false;
        idEditando=0;

        $("#ruta").val("");
        $("#ruta_aws").val("");
    });

    mensaje = function (tipo) {
        $("#mensaje").html('');
        $("#mensaje").addClass("alert");
        var html = '';
        if (tipo == "ok") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Operacioón realizada!</strong>';
        }
        else if (tipo == "nuevo") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Nuevo registro creado!</strong>';
        }
        else if (tipo == "editada") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Registro editado!</strong>';
        }
        else if (tipo == "eliminada") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Registro eliminado!</strong>';
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


//Otro scope
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
    'click .edit': function (e, value, row, index)
    {
        var id = row.id;
        idEditando = id;
        editando=true;

        $('#editarModal').modal('show');
        $("#imagenModal").css("z-index", "1500");

        var url = "../ws/oferta/" + id;
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                if (parseInt(result.intCodigo) == 1)
                {
                    var objOferta = result.resultado.oferta;
                    llenarCatalogosCallback("fipaz_rubros","cmbRubro_editar",objOferta.rubro_id);
                    $("#hdnRubro_editar").val(objOferta.rubro_id);

                    if(objOferta.empresa=="")
                    {
                        $("#chkNoExpositor_editar").removeAttr("checked");
                        $("#cmbExpositor_editar").removeAttr("disabled").removeClass("disabled");
                        $("#txtNombreEmpresa_editar").attr("disabled","true").addClass("disabled");

                        $("#hdnRubro_editar").val($("#cmbRubro_editar option:selected").text());
                        llenarExpositoresCallback($("#cmbRubro_editar").val(),objOferta.expositor_id);
                        $("#hdnExpositor_editar").val(objOferta.expositor_id);
                    }
                    else
                    {
                        $("#chkNoExpositor_editar").removeAttr("checked");
                        $("#cmbExpositor_editar").attr("disabled","true").addClass("disabled");
                        $("#txtNombreEmpresa_editar").removeAttr("disabled","true").removeClass("disabled");
                        $("#txtNombreEmpresa_editar").val(objOferta.empresa);
                    }

                    var wysihtml5Editor = $('#htmlOferta_editar').data("wysihtml5").editor;
                    wysihtml5Editor.setValue(objOferta.html);

                    $("#btnGuardarEditar").click(function (event) {
                        event.preventDefault();
                        var url = "../ws/oferta/"+idEditando;
                        var datos={
                            "rubro":$("#hdnRubro_editar").val(),
                            "rubro_id":$("#cmbRubro_editar").val(),
                            "expositor":$("#hdnExpositor_editar").val(),
                            "expositor_id":$("#cmbExpositor_editar").val(),
                            "empresa":$("#txtNombreEmpresa_editar").val(),
                            "html":$("#htmlOferta_editar").val()
                        };

                        $.ajax({
                            type: "POST",
                            url: url,
                            data:  JSON.stringify(datos),
                            contentType: "application/json; charset=utf-8",
                            dataType: "json",
                            success: function (result)
                            {
                                if (parseInt(result.intCodigo) == 1)
                                {
                                    mensaje("editada");
                                    $("#editarModal").modal("hide");
                                    $table = $('#tblOfertas').bootstrapTable('refresh', {
                                        url: '../api/v1/ofertas/'+$("#credencial").val()+'/sinformato'
                                    });
                                }
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                console.log(XMLHttpRequest + " " + textStatus);
                            }
                        });
                    });
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#eliminarModal').modal('hide');
                $("#btnEliminarPublicidad").removeAttr('disabled');
                console.log(XMLHttpRequest + " " + textStatus);
                $('#editarModal').modal('hide');
            }
        });

    },
    'click .remove': function (e, value, row, index)
    {
        var id = row.id;
        $('#eliminarModal').modal('show');
        $("#btnEliminarOferta").click(function () {
            $("#btnEliminarOferta").attr('disabled', 'disabled');
            var url = "../ws/oferta/eliminar/" + id;
            $.ajax({
                type: "POST",
                url: url,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (result) {
                    $('#eliminarModal').modal('hide');
                    $("#btnEliminarOferta").removeAttr('disabled');
                    if (parseInt(result.intCodigo) == 1) {
                        mensaje("eliminada");
                        $table = $('#tblOfertas').bootstrapTable('refresh', {
                            url: '../api/v1/ofertas/' + $("#credencial").val() + '/sinformato'
                        });
                    }
                    else {
                        mensaje(result.resultado.errores);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $('#eliminarModal').modal('hide');
                    $("#btnEliminarOferta").removeAttr('disabled');
                    console.log(XMLHttpRequest + " " + textStatus);
                    mensaje("error");
                }
            });
        });
    }
};