/**
 * Created by andresvasquez on 17/12/15.
 */

$(document).ready(function()
{
    llenarCatalogos=function(campo,agrupador,idPadre)
    {
        var credencial=$("#credencial").val();
        var html='<option value="0">Seleccione</option>';

        var url="../api/v1/catalogos/"+credencial+"/"+agrupador+"/"+idPadre;
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
                    for(var i=0;i<arrCatalogos.length;i++)
                        html+='<option value="'+arrCatalogos[i].id+'">'+arrCatalogos[i].label+'</option>';
                    $("#"+campo).html(html);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
            }
        });
    };

    llenarDias=function()
    {
        var html='';
        html+='<option value="1">Lunes</option>';
        html+='<option value="2">Martes</option>';
        html+='<option value="3">Miércoles</option>';
        html+='<option value="4">Jueves</option>';
        html+='<option value="5">Viernes</option>';
        html+='<option value="6">Sábado</option>';
        html+='<option value="7">Domingo</option>';

        $("#cmbDia").html(html).chosen();
        $(".chosen-select").chosen();
        $(".chosen-container").css("width","100%");
    };

    mensaje = function (tipo) {
        $("#mensaje").html('');
        $("#mensaje").addClass("alert");
        var html = '';
        if (tipo == "ok") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Registro creado!</strong>';
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

    $("#cmbCiudad,#cmbTipoMedio").change(function(event){
        if($("#cmbCiudad").val()!="0" && $("#cmbTipoMedio").val()!="0")
        {
            var idCiudad=$("#cmbCiudad").val();
            var TipoMedio=$("#cmbTipoMedio option:selected").text();

            var agrupador="";
            if(TipoMedio=="Periódico")
              agrupador="Periodicos por dpto";
            else
                agrupador="Revistas por dpto";

            llenarCatalogos('cmbMedio',agrupador,idCiudad);
        }
    });

    $("#btnCancelarNuevoTarifario").click(function(){
        $("#cmbDia").trigger('chosen:updated');
    });

    $("#formBusquedaMedio").submit(function(event)
    {
        event.preventDefault();
        event.stopPropagation();

        if($("#cmbCiudad").val()=="0")
        {
            alert("Seleccione el departamento");
            return;
        }

        if($("#cmbTipoMedio").val()=="0")
        {
            alert("Seleccione el tipo de medio");
            return;
        }

        if($("#cmbMedio").val()=="0")
        {
            alert("Seleccione el medio");
            return;
        }

        $("#txtMedio").html($("#cmbMedio option:selected").text());

        $("#mensajeCargando").removeClass("hidden");
        $table = $('#tblTarifario').bootstrapTable('refresh', {
            url: '../ws/drclipling/tarifario/'+$("#cmbMedio").val()
        });

        $("#cmbCiudad,#cmbMedio,#cmbTipoMedio").attr("disabled",'disabled');

        $("#divTarifario").removeClass("hidden");
        $("#mensajeCargando").addClass("hidden");

        window.location.href = "#divTarifario";
    });

    $("#formNuevoTarifario").submit(function(event){
        event.preventDefault();
        event.stopPropagation();

        if($("#cmbColor").val()=="0")
        {
            alert("Seleccione el color");
            return;
        }

        if($("#cmbUbicacion").val()=="0")
        {
            alert("Seleccione la ubicación");
            return;
        }

        if($("#txtTarifa").val()=="")
        {
            alert("Ingrese la tarifa");
            return;
        }

        var tags="";
        try
        {
            var cmbEtiquetas=$("#cmbDia").val();
            for(var i=0;i<cmbEtiquetas.length;i++)
                tags+=cmbEtiquetas[i]+",";
        }catch (ex){}

        tags=tags.substring(0,tags.length-1);

        var datos = {
            "medio_id": $("#cmbMedio").val(),
            "color_id": $("#cmbColor").val(),
            "color": $("#cmbColor option:selected").text(),
            "ubicacion_id": $("#cmbUbicacion").val(),
            "ubicacion": $("#cmbUbicacion option:selected").text(),
            "dia": tags,
            "tarifa": $("#txtTarifa").val()
        };

        $("#btnEnviar").attr('disabled', 'disabled');
        $.ajax({
            type: "POST",
            url: "../ws/drclipling/tarifario",
            data: JSON.stringify(datos),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                $("#cmbCiudad,#cmbMedio,#cmbTipoMedio").removeAttr("disabled");

                if (parseInt(result.intCodigo) == 1) {
                    mensaje("ok");
                    $("#btnEnviar").removeAttr('disabled');
                    $("#collapse").trigger("click");
                    window.location.href = "#tblTarifario";
                    $table = $('#tblTarifario').bootstrapTable('refresh', {
                        url: '../ws/drclipling/tarifario/'+$("#cmbMedio").val()
                    });
                }
                else {
                    mensaje(result.resultado.errores);
                    window.location.href = "#tblTarifario";
                    $("#btnEnviar").removeAttr('disabled');
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
                mensaje("error");
                $("#cmbCiudad,#cmbMedio,#cmbTipoMedio").removeAttr("disabled");
                $("#btnEnviar").removeAttr('disabled');
            }
        });

    });



    llenarCatalogos('cmbCiudad',"Departamentos",0);
    llenarCatalogos('cmbTipoMedio',"Tipo de medio",0);

    llenarDias();
    llenarCatalogos('cmbUbicacion',"Ubicacion",0);
    llenarCatalogos('cmbColor',"Color",0);

});

function operateFormatter(value, row, index) {
    return [
        '<a class="remove ml10" href="javascript:void(0)" title="Eliminar">',
        '<i class="glyphicon glyphicon-remove"></i>',
        '</a>'
    ].join('');
}

window.operateEvents = {
    'click .remove': function (e, value, row, index) {
        var id = row.id;
        $('#eliminarModal').modal('show');
        $("#btnEliminarFila").click(function ()
        {
            $("#btnEliminarFila").attr('disabled','disabled');
            var url = "../ws/drclipling/tarifario/eliminar/" + id;
            $.ajax({
                type: "POST",
                url: url,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (result) {
                    $('#eliminarModal').modal('hide');
                    $("#btnEliminarFila").removeAttr('disabled');
                    if (parseInt(result.intCodigo) == 1) {
                        mensaje("eliminada");
                        $table = $('#tblTarifario').bootstrapTable('refresh', {
                            url: '../ws/drclipling/tarifario/'+$("#cmbMedio").val()
                        });
                    }
                    else {
                        $("#btnEliminarFila").removeAttr('disabled');
                        mensaje(result.resultado.errores);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $('#eliminarModal').modal('hide');
                    $("#btnEliminarFila").removeAttr('disabled');
                    console.log(XMLHttpRequest + " " + textStatus);
                    mensaje("error");
                }
            });


        });
    }
};
