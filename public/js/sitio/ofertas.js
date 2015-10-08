/**
 * Created by andresvasquez on 3/18/15.
 */
$(document).ready(function () {
    var idInsertada = 0;
    var lstRubros;

    $('#htmlOferta').wysihtml5({
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
        else //Editar
        {

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

    //FIPAZ
    if($("#nombre_sistema").val()=="fipaz"){
        llenarCatalogos("fipaz_rubros","cmbRubro");
    }
});
