/**
 * Created by andresvasquez on 11/12/15.
 */

$(document).ready(function()
{
    $(".agregar").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        var html='<tr><td>';
        html+='<input class="form-control" id="ex1" type="number">';
        html+='</td>';
        html+='<td><input class="form-control" type="text"/></td>';
        html+='<td><div class="input-group">';
        html+='<input type="text" class="form-control" readonly>';
        html+='<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>';
        html+='</div></td>';
        html+='<td class="btn-toolbar">';
        html+='<div class="btn-group">';
        html+='<button type="button" class="btn btn-sm  btn-default eliminar">';
        html+='<span class="glyphicon glyphicon-minus"></span>';
        html+='</button>';
        html+='</div>';
        html+='</td></tr>';

        $("#trTablaNuevoCatalogo").append(html);
    });

    llenarCatalogos=function(seccion)
    {
        var credencial=$("#credencial").val();
        var html='';
        var url="../api/v1/catalogos/"+credencial+"/"+seccion;
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
                        html+='<option value="'+arrCatalogos[i].value+'">'+arrCatalogos[i].label+'</option>';
                    $(".chosen-select").html(html).chosen();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
            }
        });
    };

    llenarListaCatalogos=function()
    {
        var credencial=$("#credencial").val();
        var html='';
        var url="../api/v1/catalogos/"+credencial+"";
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                if(parseInt(result.intCodigo)==1) {
                    var lstCatalogos = result.resultado.catalogos;
                    $("#accordion").html("");

                    for (var i = 0; i < lstCatalogos.length; i++) {

                        var agrupador=lstCatalogos[i].agrupador.replace(/ /g,"");

                        var html = '';
                        html += '<div class="panel">';
                        html += '<div class="panel-heading panel-default" role="tab" id="headingOne">';
                        html += '<div class="panel-title">';
                        html += '<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse' +agrupador + '" aria-expanded="true" aria-controls="collapseOne">';
                        html += lstCatalogos[i].agrupador;
                        html += '</a>';
                        html += '</div></div>';
                        html += '<div id="collapse' + agrupador + '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">';
                        html+='<br/><div class="row"><div class="col-lg-6 col-lg-offset-1"> ';
                        html+='<button id="agregar_'+lstCatalogos[i].agrupador+'" class="btn btn-success btn-sm pull-right agregar">+ Agregar item</button>';
                        html += '<table class="table table-bordered text-center"><thead><tr><th class="col-lg-2 text-center"><small>Id</small></th><th class="col-lg-4 text-center"><small>Nombre</small></th><th class="col-lg-4 text-center"><small>Dependencia</small></th><th class="col-lg-2 text-center" colspan="2"><small>Acciones</small></th></tr></thead>';

                        html+='<tbody>';

                        for(var j=0;j<lstCatalogos[i].datos.length;j++)
                        {
                            html+='<tr>';
                            html+='<td><small>'+lstCatalogos[i].datos[j].value+'</small></td>';
                            html+='<td><small>'+lstCatalogos[i].datos[j].label+'</small></td>';
                            if(lstCatalogos[i].datos[j].padre!=null)
                                html+='<td><small>'+lstCatalogos[i].datos[j].padre+'</small></td>';
                            else
                                html+='<td><small></small></td>';
                            html+='<td><button id="editar_'+lstCatalogos[i].datos[j].id+'" class="btn btn-sm btn-info editar"><i class="fa fa-pencil"></i></button></td>';
                            html+='<td><button id="eliminar_'+lstCatalogos[i].datos[j].id+'" class="btn btn-sm btn-danger eliminar"><i class="fa fa-times"></i></button></td>';
                            html+='</tr>';
                        }

                        html+='</tbody>';

                        html += '</table></div></div></div>';
                        html += '</div>';
                        $("#accordion").append(html);
                    }
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
            }
        });

    };


    $('body').on('click', '.agregar', function() {
        var id=$(this).attr("id").replace("agregar_","");

        $("#txtNombreCatalogo").html(id);

        if(id.indexOf("dpto")>-1)
        {
            var credencial=$("#credencial").val();
            var html='<option value="0">Seleccione</option>';
            var url="../api/v1/catalogos/"+credencial+"/Departamentos";
            $.ajax({
                type: "GET",
                url: url,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function(result) {
                    if (parseInt(result.intCodigo) == 1) {
                        var lstCatalogos = result.resultado.catalogos;
                        for(var i=0;i<lstCatalogos.length;i++)
                            html+='<option value="'+lstCatalogos[i].id+'">'+lstCatalogos[i].label+'</option>';

                        $("#cmbDependencia").html(html);
                        $("#agregarModal").modal("show");
                    }
                    else
                        mensaje(result.mensaje);
                }
            });
        }
        else
        {
            $("#cmbDependencia").html('<option value="0">Ninguna</option>');
            $("#agregarModal").modal("show");
        }

        $("#btnAgregarItem").click(function(event){
            event.preventDefault();
            event.stopPropagation();

            if($("#cmbDependencia").val()=="0")
            {
                if($("#cmbDependencia option:selected").text()!="Ninguna")
                {
                    alert("Seleccione la dependencia");
                    return;
                }
            }

            if($("#txtNombreItemCatalogo").val()=="")
            {
                alert("Ingrese el nombre del item del catálogo");
                return;
            }

            var datos = {
                "sistema_id": "8",
                "agrupador": id,
                "label": $("#txtNombreItemCatalogo").val(),
                "value": "0",
                "value2": "drclipping",
                "idpadre": $("#cmbDependencia").val()
            };

            var url="../api/v1/catalogos";
            $.ajax({
                type: "POST",
                url: url,
                data: JSON.stringify(datos),
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function(result)
                {
                    console.log(JSON.stringify(result));
                    if(parseInt(result.intCodigo)==1)
                    {
                        $("#agregarModal").modal("hide");
                        mensaje("ok");
                        $('#mensaje').focus();
                        llenarListaCatalogos();
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest + " "+textStatus);
                }
            });
        });
    });

    limpiarCampos=function(){
        $("#txtNombreItemCatalogo").val("")
    };

    $('body').on('click', '.eliminar', function() {
        var id=$(this).attr("id").replace("eliminar_","");
        $("#eliminarModal").modal("show");
        $("#btnEliminarItem").click(function(event){
            event.preventDefault();
            event.stopPropagation();

            var url="../api/v1/catalogos/eliminar/"+id;
            $.ajax({
                type: "POST",
                url: url,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function(result)
                {
                    if(parseInt(result.intCodigo)==1)
                    {
                        $("#eliminarModal").modal("hide");
                        mensaje("eliminada");
                        $('#mensaje').focus();
                        llenarListaCatalogos();
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest + " "+textStatus);
                }
            });
        });
    });

    $('body').on('click', '.editar', function()
    {
        var id=$(this).attr("id").replace("editar_","");
        var url="../api/v1/catalogos_lista/"+id;
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                if (parseInt(result.intCodigo) == 1)
                {
                    var objCatalogo=result.resultado.catalogo;
                    $("#txtNombreItemCatalogo_editar").val(objCatalogo.label);
                    if(objCatalogo.agrupador.indexOf("dpto")>-1)
                    {
                        var credencial=$("#credencial").val();
                        var html='<option value="0">Seleccione</option>';
                        var url="../api/v1/catalogos/"+credencial+"/Departamentos";
                        $.ajax({
                            type: "GET",
                            url: url,
                            contentType: "application/json; charset=utf-8",
                            dataType: "json",
                            success: function(result) {
                                if (parseInt(result.intCodigo) == 1) {
                                    var lstCatalogos = result.resultado.catalogos;
                                    for(var i=0;i<lstCatalogos.length;i++)
                                            html+='<option value="'+lstCatalogos[i].id+'">'+lstCatalogos[i].label+'</option>';
                                    $("#cmbDependencia_editar").html(html);
                                    $('#cmbDependencia_editar option[value="'+objCatalogo.idpadre+'"]').attr('selected', 'selected');
                                    $("#editarModal").modal("show");
                                }
                                else
                                    mensaje(result.mensaje);
                            }
                        });
                    }
                    else
                    {
                        $("#cmbDependencia_editar").html('<option value="0">Ninguna</option>');
                        $("#editarModal").modal("show");
                    }

                    $("#btnEditarItem").click(function(event){
                        event.preventDefault();
                        event.stopPropagation();

                        if($("#cmbDependencia_editar").val()=="0")
                        {
                            if($("#cmbDependencia_editar option:selected").text()!="Ninguna")
                            {
                                alert("Seleccione la dependencia");
                                return;
                            }
                        }

                        if($("#txtNombreItemCatalogo_editar").val()=="")
                        {
                            alert("Ingrese el nombre del item del catálogo");
                            return;
                        }

                        var datos = {
                            "sistema_id": "8",
                            "agrupador": objCatalogo.agrupador,
                            "label": $("#txtNombreItemCatalogo_editar").val(),
                            "value": objCatalogo.value,
                            "value2": "drclipping",
                            "idpadre": $("#cmbDependencia_editar").val()
                        };

                        var url="../api/v1/catalogos/editar/"+id;
                        $.ajax({
                            type: "POST",
                            url: url,
                            data:JSON.stringify(datos),
                            contentType: "application/json; charset=utf-8",
                            dataType: "json",
                            success: function(result)
                            {
                                if(parseInt(result.intCodigo)==1)
                                {
                                    $("#editarModal").modal("hide");
                                    mensaje("editada");
                                    llenarListaCatalogos();
                                }
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                console.log(XMLHttpRequest + " "+textStatus);
                            }
                        });
                    });
                }
                else
                    mensaje(result.mensaje);
            }
        });
    });



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

    llenarListaCatalogos();
});

