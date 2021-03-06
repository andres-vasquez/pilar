/**
 * Created by andresvasquez on 3/18/15.
 */
var editando=false;
var idEditando=0;

$(document).ready(function () {
    var idInsertada = 0;
    var lstRubros;

    $('#htmlOferta,#htmlOferta_editar').wysihtml5({
        toolbar: {
            "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
            "emphasis": true, //Italics, bold, etc. Default true
            "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
            "html": false, //Button which allows you to edit the generated HTML. Default false
            "link": true, //Button to insert a link. Default true
            "image": true, //Button to insert an image. Default true,
            "color": false, //Button to change color of font
            "blockquote": false //Blockquote
        },
        customTemplates: {
            image: function(locale) {
                return '<li>' +
                    "<div class='btn-group'>" +
                    "<a class='btn btn-default' data-toggle='modal' data-target='#imagenModal'  title='Imagen'><span class='glyphicon glyphicon-picture'></span></a>" +
                    "</div>" +
                    "</li>";
            }
        },
        locale: "es-ES"
    });

    $("form").submit(function (event) {
        var id = $(this).attr("id");
        event.preventDefault();

        if(id=="formNuevaOferta")//Nuevo oferta
        {
            var url = "../ws/oferta";
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
                        $("#collapseNuevo").trigger("click");
                        $table = $('#tblOfertas').bootstrapTable('refresh', {
                            url: '../api/v1/ofertas/'+$("#credencial").val()+'/sinformato'
                        });
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $("#divNoticias").html("");
                    console.log(XMLHttpRequest + " " + textStatus);
                }
            });
        }
        else if(id=="formImagen")
        {
            var btnSubmit = $(this).find(':submit');
            var htmlCargando='<i class="fa fa-spinner fa-spin"></i> Cargando';
            var htmlCargado='<i class="fa fa-check"></i> Cargado';
            var htmlError='<i class="fa fa-close"></i> Error';

            var _validFileExtensions = [".jpg", ".jpeg", ".png"];
            var formData = new FormData($(this)[0]);

            btnSubmit.html(htmlCargando);
            btnSubmit.attr('disabled', 'disabled');

            var url = "../ws/evento/subirimagen";
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
                            var htmlAnterior=$('#htmlOferta').val();
                            var wysihtml5Editor = $('#htmlOferta').data("wysihtml5").editor;
                            wysihtml5Editor.setValue(htmlAnterior+'<br/><img width="100%" src="'+ruta+'"/>');
                        }
                        else
                        {
                            $("#hdnRutaImagen_editar").val(ruta);
                            var htmlAnterior=$('#htmlOferta_editar').val();
                            var wysihtml5Editor = $('#htmlOferta_editar').data("wysihtml5").editor;
                            wysihtml5Editor.setValue(htmlAnterior+'<br/><img width="100%" src="'+ruta+'"/>');
                        }

                        $("#imgImagen").val("");
                        $("#imagenModal").modal("hide");
                        btnSubmit.html("Cargar");
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


    $("#cmbRubro").change(function(event){
        $("#cmbExpositor").html("");
        $("#hdnRubro").val($("#cmbRubro option:selected").text());
        llenarExpositores($(this).val());
    });

    $("#cmbExpositor").change(function(event){
        $("#hdnExpositor").val($("#cmbExpositor option:selected").text());
    });

    $("#chkNoExpositor").click(function(event){
        if($("#chkNoExpositor").is(':checked'))
        {
            $("#cmbExpositor").html("").attr("disabled","true").addClass("disabled");
            $("#txtNombreEmpresa").removeAttr("disabled").removeClass("disabled");
        }
        else
        {
            $("#cmbExpositor").removeAttr("disabled").removeClass("disabled");
            $("#txtNombreEmpresa").attr("disabled","true").addClass("disabled");
            $("#hdnRubro").val($("#cmbRubro option:selected").text());
            llenarExpositores($(this).val());
        }
    });

    $("#divNuevaOferta").click(function(event){
        editando=false;
        idEditando=0;
    });

    //Para la edicion
    $("#cmbRubro_editar").change(function(event){
        $("#cmbExpositor_editar").html("");
        $("#hdnRubro_editar").val($("#cmbRubro_editar option:selected").text());
        llenarExpositores($(this).val());
    });

    $("#cmbExpositor_editar").change(function(event){
        $("#hdnExpositor_editar").val($("#cmbExpositor option:selected").text());
    });

    $("#chkNoExpositor_editar").click(function(event){
        if($("#chkNoExpositor_editar").is(':checked'))
        {
            $("#cmbExpositor_editar").html("").attr("disabled","true").addClass("disabled");
            $("#txtNombreEmpresa_editar").removeAttr("disabled").removeClass("disabled");
        }
        else
        {
            $("#cmbExpositor_editar").removeAttr("disabled").removeClass("disabled");
            $("#txtNombreEmpresa_editar").attr("disabled","true").addClass("disabled");
            $("#hdnRubro_editar").val($("#cmbRubro option:selected").text());
            llenarExpositores($(this).val());
        }
    });




    mensaje = function (tipo) {
        $("#mensaje").html('');
        $("#mensaje").addClass("alert");
        var html = '';
        if (tipo == "ok") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Oferta importada!</strong>';
        }
        else if (tipo == "nuevo") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Oferta creada!</strong>';
        }
        else if (tipo == "editada") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Oferta editada!</strong>';
        }
        else if (tipo == "eliminada") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Oferta eliminada!</strong>';
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
                    if(agrupador=="rubros_fipaz")
                        lstRubros=result.resultado.catalogos;

                    for(var i=0;i<arrCatalogos.length;i++)
                        html+='<option value="'+arrCatalogos[i].value+'">'+arrCatalogos[i].label+'</option>';
                    $("#"+cmbId).html(html);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
            }
        });
    };

    llenarCatalogosCallback = function(agrupador,cmbId,rubro_id)
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
                        html+='<option value="'+arrCatalogos[i].value+'">'+arrCatalogos[i].label+'</option>';
                    $("#"+cmbId).html(html);

                    $('#cmbRubro_editar option[value="' + rubro_id + '"]').attr("selected", "selected");
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
            }
        });
    };


    llenarExpositores = function(rubroId)
    {
        var credencial=$("#credencial").val();
        var html='<option value="0"></option>';
        var url="../api/v1/expositores/"+credencial+"/rubro/"+rubroId;
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                if(parseInt(result.intCodigo)==1)
                {
                    var arrExpositores=result.resultado.expositores;
                    for(var i=0;i<arrExpositores.length;i++)
                        html+='<option value="'+arrExpositores[i].id+'">'+arrExpositores[i].nombre+'</option>';
                    $("#cmbExpositor").html(html);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
            }
        });
    };


    llenarExpositoresCallback = function(rubroId,expositor_id)
    {
        var credencial=$("#credencial").val();
        var html='<option value="0"></option>';
        var url="../api/v1/expositores/"+credencial+"/rubro/"+rubroId;
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                if(parseInt(result.intCodigo)==1)
                {
                    var arrExpositores=result.resultado.expositores;
                    for(var i=0;i<arrExpositores.length;i++)
                        html+='<option value="'+arrExpositores[i].id+'">'+arrExpositores[i].nombre+'</option>';
                    $("#cmbExpositor").html(html);

                    $('#cmbExpositor_editar option[value="' + expositor_id + '"]').attr("selected", "selected");
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
            }
        });
    };

    //FIPAZ
    if($("#nombre_sistema").val()=="fipaz"){
        llenarCatalogos("fipaz_rubros","cmbRubro");
    }
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
    'click .edit': function (e, value, row, index) {
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