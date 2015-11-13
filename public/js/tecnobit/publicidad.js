/**
 * Created by andresvasquez on 3/18/15.
 */
var idEditando = 0;
var lstImagenesEdit = [];

//var plan1="PREMIUM";
//var plan2="PLUS";
//var plan3="BASICO";
//var plan4="ECONOMICO";

$(document).ready(function ()
{
    var idInsertada = 0;
    //Verifica el tamanio de la imagen y el formato
    $(".imagen").change(function (e) {

        var tamano = this.files[0].size;
        if (parseInt(tamano) > 1000000) //1 MB
        {
            alert("Tamaño máximo permitido de 1MB reduzca su imagen por favor");
            $(this).val("");
        }
        else {
            var fileExtension = ['jpeg', 'jpg', 'png'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                alert("Formato incorrecto. Las imágenes deben estar en formato jpeg o png.");
                $(this).val("");
            }
        }
    });


    $("form").submit(function (event)
    {
        //Edita las imagenes
        if ($(this).hasClass("editar"))
        {
            event.preventDefault();

            var _validFileExtensions = [".jpg", ".jpeg", ".png"];

            var id_imagen=0;

            var id_form=$(this).attr("id");
            var tipo=$("#"+id_form+" input[name=tipo]").val();
            var sizex=$("#"+id_form+" input[name=sizex]").val();
            var sizey=$("#"+id_form+" input[name=sizey]").val();

            for(var i=0;i<lstImagenesEdit.length;i++)
                if(lstImagenesEdit[i].tipo==tipo)
                    if(lstImagenesEdit[i].sizex==sizex && lstImagenesEdit[i].sizey==sizey)
                        id_imagen=lstImagenesEdit[i].id;


            var formData = new FormData($(this)[0]);
            if(id_imagen!=0) {
                var url = "../ws/publicidad_imagenes/" + id_imagen;

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (result) {
                        result = JSON.parse(result);
                        if (parseInt(result.intCodigo) == 1) {
                            var ruta = result.resultado.carga.ruta.replace("s3.amazonaws.com/siriustecnobit","siriustecnobit.s3.amazonaws.com");
                            var imagen = result.resultado.carga.id;
                            console.log("URI imagen:" + imagen);
                            $("#" + imagen + "_editar").attr("src", "../"+ruta);
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        $("#divNoticias").html("");
                        console.log(XMLHttpRequest + " " + textStatus);
                    }
                });
            }
            else //Nuevas imagenes
            {
                var url = "../ws/publicidad_imagenes";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (result) {
                        result = JSON.parse(result);
                        console.log(JSON.stringify(result));
                        if (parseInt(result.intCodigo) == 1) {
                            var ruta = result.resultado.carga.ruta.replace("s3.amazonaws.com/siriustecnobit","siriustecnobit.s3.amazonaws.com");
                            var imagen = result.resultado.carga.id;
                            console.log("URI imagen:" + imagen);
                            $("#" + imagen+"_editar").attr("src", "../"+ruta);
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        $("#divNoticias").html("");
                        console.log(XMLHttpRequest + " " + textStatus);
                    }
                });
            }

        }
        else //Nuevo anuncio
        {
            var id = $(this).attr("id");
            //Formulario base de anuncio
            if (id == "formNuevoAnuncio")
            {
                event.preventDefault();

                var datos = {
                    "nombre": $("#txtNombre").val(),
                    "descripcion": $("#txtDescripcion").val(),
                    "link": $("#txtUrlAnuncio").val(),
                    "prioridad": $("#cmbPrioridad").val(),
                    "tipo_publicidad": $("#cmbTipoPublicidad").val(),
                    "plan": $("#cmbPlan").val()
                };


                var tipo_publicidad=$("#cmbTipoPublicidad").val();
                if(tipo_publicidad=="0")
                {
                    alert("Seleccione un tipo de publicidad");
                    return false;
                }

                $("#btnEnviar").attr('disabled', 'disabled');
                $.ajax({
                    type: "POST",
                    url: $(this).attr("action"),
                    data: JSON.stringify(datos),
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function (result) {
                        if (parseInt(result.intCodigo) == 1)
                        {
                            idInsertada = result.resultado.id;

                            mensaje("ok");
                            $("#btnEnviar").removeAttr('disabled');
                            $('.id_publicidad').val(idInsertada);

                            //$("#collapsePublicidad").trigger("click");
                            bloquearCampos();
                            $("#divImagenes").removeClass("hidden");

                            //Muestra oculta segun el tipo de publicidad
                            var tipo_publicidad=$("#cmbTipoPublicidad").val();
                            if(tipo_publicidad=="slider")
                            {
                                $(".banner").hide();
                                $(".slider").show();
                            }
                            else if(tipo_publicidad=="banner")
                            {
                                $(".slider").hide();
                                $(".banner").show();
                            }
                            else
                            {
                                $(".slider").show();
                                $(".banner").show();
                            }
                            window.location.href = "#divImagenes";
                            //limpiarCampos();
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
            else //Formulario de las imagenes
            {
                event.preventDefault();

                var btnSubmit = $(this).find(':submit');
                var htmlCargando='<i class="fa fa-spinner fa-spin"></i> Cargando';
                var htmlCargado='<i class="fa fa-check"></i> Cargado';
                var htmlError='<i class="fa fa-close"></i> Error';

                var _validFileExtensions = [".jpg", ".jpeg", ".png"];

                btnSubmit.html(htmlCargando);
                btnSubmit.attr('disabled', 'disabled');

                var url = "../ws/publicidad_imagenes";
                var formData = new FormData($(this)[0]);

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
                        console.log(JSON.stringify(result));
                        if (parseInt(result.intCodigo) == 1)
                        {
                            var ruta = result.resultado.carga.ruta.replace("s3.amazonaws.com/siriustecnobit","siriustecnobit.s3.amazonaws.com");
                            var imagen = result.resultado.carga.id;
                            console.log("URI imagen:" + imagen);
                            $("#" + imagen).attr("src", "../"+ruta);
                            btnSubmit.html(htmlCargado);
                        }
                        else
                        {
                            btnSubmit.html(htmlError);
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        $("#divNoticias").html("");
                        console.log(XMLHttpRequest + " " + textStatus);
                        btnSubmit.html(htmlError);
                    }
                });
            }
        }
    });

    $("#cmbTipoPublicidad_editar").change(function(event){
        var tipo_publicidad=$(this).val();
        //var plan=$("#cmbPlan_editar").val();


        if(tipo_publicidad=="slider")
        {
            $(".banner").hide();
            $(".slider").show();
        }
        else if(tipo_publicidad=="banner")
        {
            $(".slider").hide();
            $(".banner").show();
        }
        else
        {
            $(".slider").show();
            $(".banner").show();
        }
    });

    bloquearCampos = function () {
        $("#txtNombre").addClass("disabled");
        $("#txtDescripcion").addClass("disabled");
        $("#txtUrlAnuncio").addClass("disabled");
        $("#cmbPrioridad").addClass("disabled");
        $("#cmbTipoPublicidad").addClass("disabled");
        $("#btnEnviar").addClass("disabled");
        $("#btnCancelar").addClass("disabled");
    };

    limpiarCampos = function () {
        $("#txtNombre").val("");
        $("#txtDescripcion").val("");
        $("#txtUrlAnuncio").val("");
        $("#cmbPrioridad").val("3");
        $("#cmbTipoPublicidad").val("slider");
    };

    mensaje = function (tipo) {
        $("#mensaje").html('');
        $("#mensaje").addClass("alert");
        var html = '';
        if (tipo == "ok") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Anuncio agregado!</strong>';
        }
        else if (tipo == "editada") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Anuncio editado!</strong>';
        }
        else if (tipo == "eliminada") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Anuncio eliminado!</strong>';
        }
        else {
            $("#mensaje").removeClass("alert-success");
            $("#mensaje").addClass("alert-danger");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Error!</strong> ' + tipo;
        }
        $("#mensaje").html(html);
    };


    //Guardar editar datos
    $("#btnEditarPublicidad").click(function (event) {
        event.preventDefault();
        var datos = {
            "nombre": $("#txtNombre_editar").val(),
            "descripcion": $("#txtDescripcion_editar").val(),
            "link": $("#txtUrlAnuncio_editar").val(),
            "prioridad": $("#cmbPrioridad_editar").val(),
            "tipo_publicidad": $("#cmbTipoPublicidad_editar").val(),
            "plan": $("#cmbPlan_editar").val()
        };

        var url = "../ws/publicidad/" + idEditando;
        $("#btnEditarNoticia").attr('disabled', 'disabled');
        $.ajax({
            type: "POST",
            url: url,
            data: JSON.stringify(datos),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                if (parseInt(result.intCodigo) == 1) {
                    mensaje("editada");
                    $("#btnEditarNoticia").removeAttr('disabled');
                    $table = $('#tblPublicidad').bootstrapTable('refresh', {
                        url: '../api/v1/publicidad/' + $("#credencial").val() + '/sinformato'
                    });
                }
                else {
                    mensaje(result.resultado.errores);
                    $("#btnEditarNoticia").removeAttr('disabled');
                }
                $('#editarModal').modal('hide');
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
                mensaje("error");
                $("#btnEditarNoticia").removeAttr('disabled');
                $('#editarModal').modal('hide');
            }
        });
    });

    $("#btnVolverCargar").click(function(event){
        location.reload();
    });


    llenarCatalogos = function(agrupador,cmbId)
    {
        var credencial=$("#credencial").val();
        var html='<option value="0"></option>';
        var url="../api/v1/catalogos/"+credencial+"/"+agrupador;
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                if(parseInt(result.intCodigo)==1)
                {
                    var arrCatalogos=result.resultado.catalogos;
                    if(agrupador=="rubros_fipaz")
                        lstRubros=result.resultado.catalogos;

                    for(var i=0;i<arrCatalogos.length;i++)
                        html+='<option value="'+arrCatalogos[i].label+'">'+arrCatalogos[i].label+'</option>';
                    $("#"+cmbId).html(html);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
            }
        });
    };
    //llenarCatalogos("fipaz_planes","cmbPlan");
    //llenarCatalogos("fipaz_planes","cmbPlan_editar");
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
        $('#editarModal').modal('show');
        var url = "../ws/publicidad/" + id;
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                if (parseInt(result.intCodigo) == 1)
                {
                    var objPublicidad = result.resultado.publicidad[0];
                    $("#txtNombre_editar").val(objPublicidad.nombre);
                    $("#txtDescripcion_editar").val(objPublicidad.descripcion);
                    $("#txtUrlAnuncio_editar").val(objPublicidad.link);
                    $('#cmbPrioridad_editar option[value="' + objPublicidad.prioridad + '"]').attr("selected", "selected");
                    $('#cmbTipoPublicidad_editar option[value="' + objPublicidad.tipo_publicidad + '"]').attr("selected", "selected");
                    //$('#cmbPlan_editar option[value="' + objPublicidad.plan + '"]').attr("selected", "selected");
                    $(".id_publicidad").val(objPublicidad.id);

                    $("img").attr("src", "http://placehold.it/320x47&text=publicidad");

                    var tipo_publicidad=objPublicidad.tipo_publicidad;
                    if(tipo_publicidad=="slider")
                    {
                        $(".banner").hide();
                        $(".slider").show();
                    }
                    else if(tipo_publicidad=="banner")
                    {
                        $(".slider").hide();
                        $(".banner").show();
                    }
                    else
                    {
                        $(".slider").show();
                        $(".banner").show();
                    }

                    console.log(JSON.stringify(objPublicidad));

                    var objListaImagenes = objPublicidad.imagenes;
                    lstImagenesEdit = objListaImagenes;
                    for (var i = 0; i < objListaImagenes.length; i++) {
                        var obj = objListaImagenes[i];
                        var id_imagen_dinamico = obj.tipo + "_" + obj.sizex + "x" + obj.sizey + "_editar";
                        var ruta = obj.ruta;
                        $("#" + id_imagen_dinamico).attr("src", "../"+ruta);
                    }


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
    'click .remove': function (e, value, row, index) {
        var id = row.id;
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
        });
    }
};
