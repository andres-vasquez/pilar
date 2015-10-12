/**
 * Created by andresvasquez on 3/18/15.
 */
var editando=false;
var id_editando=0;

$(document).ready(function () {
    var idInsertada = 0;
    var lstRubros;

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

    $("form").submit(function (event) {
        var id = $(this).attr("id");
        event.preventDefault();

        if(id=="formNuevoExpositor")//Nuevo expositor
        {
            var url = "../ws/expositores";
            var formData = new FormData($(this)[0]);

            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (result)
                {
                    result = JSON.parse(result);
                    console.log(JSON.stringify(result));

                    if (parseInt(result.intCodigo) == 1)
                    {
                        mensaje("nuevo");
                        //$("#fileCsv").val("");

                        $("#collapseNuevo").trigger("click");

                        $table = $('#tblExpositores').bootstrapTable('refresh', {
                            url: '../api/v1/expositores/'+$("#credencial").val()+'/sinformato'
                        });
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $("#divNoticias").html("");
                    console.log(XMLHttpRequest + " " + textStatus);
                }
            });
        }
        else if(id=="formImagen" || id=="formImagen_editar")
        {
            event.preventDefault();
            event.stopPropagation();

            var btnSubmit = $(this).find(':submit');
            var htmlCargando='<i class="fa fa-spinner fa-spin"></i> Cargando';
            var htmlCargado='<i class="fa fa-check"></i> Cargado';
            var htmlError='<i class="fa fa-close"></i> Error';

            var _validFileExtensions = [".jpg", ".jpeg", ".png"];
            var formData = new FormData($(this)[0]);

            btnSubmit.html(htmlCargando);
            btnSubmit.attr('disabled', 'disabled');

            var url = "../ws/funciones/subirArchivoAWS";
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
                        var ruta=data.ruta_aws;

                        if(!editando)
                        {
                            $("#hdnRutaImagen").val(ruta);
                            $("#imgExpositor").attr("src", ruta);
                        }
                        else
                        {
                            $("#hdnRutaImagen_editar").val(ruta);
                            $("#imgExpositor_editar").attr("src", ruta);
                        }

                        rutaImagen=ruta;
                        btnSubmit.html(htmlCargado);
                    }
                    else
                    {
                        alert("Error al subir la imagen del evento");
                        btnSubmit.html(htmlError);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    btnSubmit.removeAttr('disabled');
                    console.log(XMLHttpRequest + " " + textStatus);
                    btnSubmit.html(htmlError);
                }
            });
        }
        else //Importar de CSV
        {
            var url = "../ws/expositores/importar";
            var formData = new FormData($(this)[0]);

            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (result) {
                    result = JSON.parse(result);
                    console.log(JSON.stringify(result));
                    if (parseInt(result.intCodigo) == 1)
                    {
                        mensaje("ok");
                        $("#fileCsv").val("");
                        $("#collapseImportar").trigger("click");

                        $table = $('#tblExpositores').bootstrapTable('refresh', {
                            url: '../api/v1/expositores/'+$("#credencial").val()+'/sinformato'
                        });
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $("#divNoticias").html("");
                    console.log(XMLHttpRequest + " " + textStatus);
                }
            });
        }
    });


    $("#cmbRubro").change(function(event){
        for(var i=0;i<lstRubros.length;i++)
         if(lstRubros[i].label==$("#cmbRubro").val())
          $("#hdnRubro").val(lstRubros[i].value);
    });

    $("#cmbRubro_editar").change(function(event){
        for(var i=0;i<lstRubros.length;i++)
            if(lstRubros[i].label==$("#cmbRubro_editar").val())
                $("#hdnRubro_editar").val(lstRubros[i].value);
    });

    $("#btnNuevoExpositor").click(function(event){
        event.preventDefault();

        var url = "../ws/expositores";
        var datos= {
            "nombre":$("#txtNombre").val(),
            "direccion":$("#txtDireccion").val(),
            "pabellon":$("#cmbArea").val(),
            "stand":$("#txtStand").val(),
            "website":$("#txtWebsite").val(),
            "fanpage":$("#txtFacebook").val(),
            "telefono":$("#txtTelefono").val(),
            "fax":$("#txtFax").val(),
            "email":$("#txtEmail").val(),
            "rubro_id":$("#hdnRubro").val(),
            "rubro":$("#cmbRubro").val(),
            "descripcion":$("#txtDescripcion").val(),
            "rubro_especifico":$("#txtRubroEspecifico").val(),
            "ruta_aws":$("#hdnRutaImagen").val()
        };

        $.ajax({
            type: "POST",
            url: url,
            data:  JSON.stringify(datos),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result)
            {
                console.log(JSON.stringify(result));

                if (parseInt(result.intCodigo) == 1)
                {
                    mensaje("nuevo");
                    //$("#fileCsv").val("");

                    $("#collapseNuevo").trigger("click");

                    $table = $('#tblExpositores').bootstrapTable('refresh', {
                        url: '../api/v1/expositores/'+$("#credencial").val()+'/sinformato'
                    });
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $("#divNoticias").html("");
                console.log(XMLHttpRequest + " " + textStatus);
            }
        });
    });

    $("#collapseNuevo").click(function(event){
        idEditando = 0;
        editando=false;
    });


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
        else if (tipo == "nuevo") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Expositor creado!</strong>';
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
                    if(agrupador=="fipaz_rubros")
                        lstRubros=arrCatalogos;

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

    //FIPAZ

    if($("#nombre_sistema").val()=="fipaz")
    {
        llenarCatalogos("fipaz_areas","cmbArea");
        llenarCatalogos("fipaz_rubros","cmbRubro");
    }

});


llenarCatalogosCallback = function(agrupador,cmbId,campo_id)
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


                var valorSeleccionado;

                for(var i=0;i<arrCatalogos.length;i++)
                {
                    html+='<option value="'+arrCatalogos[i].label+'">'+arrCatalogos[i].label+'</option>';
                    if(arrCatalogos[i].label==campo_id)
                    valorSeleccionado=arrCatalogos[i].label;
                }

                $("#"+cmbId).html(html);


                if(agrupador=="fipaz_rubros")
                    $('#cmbRubro_editar option[value="' + valorSeleccionado + '"]').attr("selected", "selected");
                else if(agrupador=="fipaz_areas")
                    $('#cmbArea_editar option[value="' + valorSeleccionado + '"]').attr("selected", "selected");
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest + " "+textStatus);
        }
    });
};

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
    'click .edit': function (e, value, row, index) {
        var id = row.id;
        idEditando = id;
        editando=true;

        $('#editarModal').modal('show');

        var url = "../ws/expositores/" + id;
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                if (parseInt(result.intCodigo) == 1)
                {
                    console.log(JSON.stringify(result));

                    var objExpositor = result.resultado.expositore;
                    llenarCatalogosCallback("fipaz_areas","cmbArea_editar",objExpositor.pabellon);
                    llenarCatalogosCallback("fipaz_rubros","cmbRubro_editar",objExpositor.rubro);

                    $("#hdnRubro_editar").val(objExpositor.rubro_id);
                    $("#imgExpositor_editar").attr("src",objExpositor.ruta_aws);
                    $("#hdnRutaImagen_editar").val(objExpositor.ruta_aws);

                    $("#txtNombre_editar").val(objExpositor.nombre);
                    $("#txtRubroEspecifico_editar").val(objExpositor.rubro_especifico);
                    $("#txtWebsite_editar").val(objExpositor.website);
                    $("#txtFacebook_editar").val(objExpositor.fanpage);
                    $("#txtEmail_editar").val(objExpositor.email);
                    $("#txtDescripcion_editar").val(objExpositor.descripcion);
                    $("#txtStand_editar").val(objExpositor.stand);



                    $("#btnGuardarEditar").click(function (event) {
                        event.preventDefault();
                        var url = "../ws/expositores/"+idEditando;
                        var datos= {
                            "nombre":$("#txtNombre_editar").val(),
                            "direccion":$("#txtDireccion_editar").val(),
                            "pabellon":$("#cmbArea_editar").val(),
                            "stand":$("#txtStand_editar").val(),
                            "website":$("#txtWebsite_editar").val(),
                            "fanpage":$("#txtFacebook_editar").val(),
                            "telefono":$("#txtTelefono_editar").val(),
                            "fax":$("#txtFax_editar").val(),
                            "email":$("#txtEmail_editar").val(),
                            "rubro_id":$("#hdnRubro_editar").val(),
                            "rubro":$("#cmbRubro_editar").val(),
                            "descripcion":$("#txtDescripcion_editar").val(),
                            "rubro_especifico":$("#txtRubroEspecifico_editar").val(),
                            "ruta_aws":$("#hdnRutaImagen_editar").val()
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
                                    $table = $('#tblExpositores').bootstrapTable('refresh', {
                                        url: '../api/v1/expositores/'+$("#credencial").val()+'/sinformato'
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
        $("#btnEliminarExpositor").click(function () {
            $("#btnEliminarExpositor").attr('disabled', 'disabled');
            var url = "../ws/expositores/eliminar/" + id;
            $.ajax({
                type: "POST",
                url: url,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (result) {
                    $('#eliminarModal').modal('hide');
                    $("#btnEliminarExpositor").removeAttr('disabled');
                    if (parseInt(result.intCodigo) == 1) {
                        mensaje("eliminada");
                        $table = $('#tblExpositores').bootstrapTable('refresh', {
                            url: '../api/v1/expositores/' + $("#credencial").val() + '/sinformato'
                        });
                    }
                    else {
                        mensaje(result.resultado.errores);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $('#eliminarModal').modal('hide');
                    $("#btnEliminarExpositor").removeAttr('disabled');
                    console.log(XMLHttpRequest + " " + textStatus);
                    mensaje("error");
                }
            });
        });
    }
};