/**
 * Created by andresvasquez on 3/18/15.
 */
$(document).ready(function () {
    var idInsertada = 0;
    var lstRubros;

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


    //FIPAZ

    if($("#nombre_sistema").val()=="fipaz")
    {
        llenarCatalogos("areas_fipaz","cmbArea");
        llenarCatalogos("rubros_fipaz","cmbRubro");
    }

});
