/**
 * Created by andresvasquez on 11/12/15.
 */
var lstTags=[];
var lstTareas=[];
var objPublicacionGlobal=null;

$(document).ready(function()
{
    $("body").on("click", ".item-lista", function(event){
        event.preventDefault();
        event.stopPropagation();

        var id=$(this).attr("id");

        $(".item-lista" ).each(function( index ) {
            if($(this).attr("id")==id)
                $(this).addClass("active");
            else
                $(this).removeClass("active");
        });

        $("#cargandoTarea").removeClass("hidden");
        for(var i=0;i<lstTareas.length;i++)
            if(lstTareas[i].id==id)
                cargarDetallePublicacion(lstTareas[i]);
    });

    llenarLista=function(estado,inicio,fin)
    {
        var html='';
        $.ajax({
            type: "GET",
            url: "../ws/drclipling/publicacion/"+estado+"/"+inicio+"/"+fin,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {

                if (parseInt(result.intCodigo) == 1)
                {
                    var objResultado=result.resultado.publicaciones;

                    var total=objResultado.total;
                    var lstPublicaciones=objResultado.publicacion;

                    //console.log(JSON.stringify(lstPublicaciones));

                    for(var i=0;i<lstPublicaciones.length;i++)
                    {
                        var activo = "";
                        if (inicio == 1 && i == 0)
                        {
                            activo = "active";
                            cargarDetallePublicacion(lstPublicaciones[i]);
                        }

                        html+='<a href="#" id="'+lstPublicaciones[i].id+'" class="list-group-item item-lista '+activo+'">';
                        html+='<h5 class="list-group-item-heading">'+lstPublicaciones[i].empresa+'</h5>';
                        html+='<p class="list-group-item-text">'+lstPublicaciones[i].ubicacion+' Página: '+lstPublicaciones[i].pagina+'</p>';
                        html+='<p class="list-group-item-text">'+lstPublicaciones[i].fecha_publicacion+'</p>';
                        html+='</a>';

                        lstTareas.push(lstPublicaciones[i]);
                    }
                }
                else
                {
                    html+='<p>No hay datos</p>';
                }
                if(inicio!=1)
                    $("#lstTareas").append(html);
                else
                    $("#lstTareas").html(html);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
                mensaje("error");
            }
        });
    };


    $("#cmbFiltroEstados").change(function(){
        llenarLista($(this).val(),1,15);
    });

    $(".edit").click(function(event){
        $("#editarModal").modal("show");


    });

    $(".foto").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        var src=$(this).attr("src");
        $("#imgPreview").attr("src",src);;
        $("#zoomModal").modal("show");
    });

    $('#zoomModal').on('shown.bs.modal', function() {
        $('#imgPreview').elevateZoom({
            zoomType: "inner",
            cursor: "crosshair"
        });
    });

    jQuery('#zoomModal').on('hidden.bs.modal', function (e) {

        jQuery.removeData(jQuery('#imgPreview'), 'elevateZoom');//remove zoom instance from image
        jQuery('.zoomContainer').remove();// remove zoom container from DOM
    });


    //FUNCION PRINCIPAL ********************************************
    cargarDetallePublicacion=function(objPublicacion){
        limpiarCampos();
        llenarTags();

        $("#cargandoTarea").addClass("hidden");

        objPublicacionGlobal=objPublicacion;

        $("#imgFoto1").attr("src",objPublicacion.url_foto1);
        $("#imgFoto2").attr("src",objPublicacion.url_foto2);

        $("#tdId").html(objPublicacion.id);
        $("#tdCiudad").html(objPublicacion.ciudad);
        $("#tdTipoMedio").html(objPublicacion.tipo_medio);
        $("#tdMedio").html(objPublicacion.medio);
        $("#tdUbicacion").html(objPublicacion.ubicacion);
        $("#tdPagina").html(objPublicacion.pagina);
        $("#tdEmpresa").html(objPublicacion.empresa);
        $("#tdFecha").html(objPublicacion.fecha_publicacion);

        var arrFecha=objPublicacion.fecha_publicacion.split("/");
        var fecha=new Date(arrFecha[2],arrFecha[1]-1,arrFecha[0]);

        switch (fecha.getDay())
        {
            case 0:$("#txtDia").val("Domingo");break;
            case 1:$("#txtDia").val("Lunes");break;
            case 2:$("#txtDia").val("Martes");break;
            case 3:$("#txtDia").val("Miércoles");break;
            case 4:$("#txtDia").val("Jueves");break;
            case 5:$("#txtDia").val("Viernes");break;
            case 6:$("#txtDia").val("Sábado");break;
        }
    };

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

    llenarCatalogosSeleccionado=function(campo,agrupador,idPadre,idSeleccionado)
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

    $("#btnBuscarTarifa").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        $("#tarifaModal").modal("show");
    });

    $("#cmbCiudad_popup,#cmbTipoMedio_popup").change(function(event){
        if($("#cmbCiudad_popup").val()!="0" && $("#cmbTipoMedio_popup").val()!="0")
        {
            var idCiudad=$("#cmbCiudad_popup").val();
            var TipoMedio=$("#cmbTipoMedio_popup option:selected").text();

            var agrupador="";
            if(TipoMedio=="Periódico")
                agrupador="Periodicos por dpto";
            else
                agrupador="Revistas por dpto";

            llenarCatalogos('cmbMedio_popup',agrupador,idCiudad);
        }
    });

    $("#btnVerTarifas").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        if($("#cmbCiudad_popup").val()=="0")
        {
            alert("Seleccione el departamento");
            return;
        }

        if($("#cmbTipoMedio_popup").val()=="0")
        {
            alert("Seleccione el tipo de medio");
            return;
        }

        if($("#cmbMedio_popup").val()=="0")
        {
            alert("Seleccione el medio");
            return;
        }

        $table = $('#tblTarifario').bootstrapTable('refresh', {
            url: '../ws/drclipling/tarifario/'+$("#cmbMedio_popup").val()
        });

        window.location.href = "#tblTarifario";
    });

    mensaje = function (tipo) {
        $("#mensaje").html('');
        $("#mensaje").addClass("alert");
        var html = '';
        if (tipo == "ok") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Operación realizada!</strong>';
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

    limpiarCampos=function()
    {
        $("#cmbTags").val('').trigger("chosen:updated");
        $("#txtNombrePublicacion").val("");
        $('#cmbColor option[value=0]').attr('selected', 'selected');
        $("#txtTamanio").val("");
        $('#cmbCuerpo option[value=0]').attr('selected', 'selected');
        $("#txtDia").val("");
        $("#txtTarifa").val("");
        $('#cmbValoracion option[value=0]').attr('selected', 'selected');
        $("#txtObservaciones").val("");
    };

    $("#formAnalisis").submit(function(event) {
        event.preventDefault();
        event.stopPropagation();

        if($("#cmbTags").val()==null)
        {
            alert("Seleccione Tags para la publicación");
            return;
        }

        if($("#txtNombrePublicacion").val()=="")
        {
            alert("Ingrese un nombre para la publicación");
            return;
        }

        if($("#cmbColor").val()=="0")
        {
            alert("Seleccione el color");
            return;
        }

        if($("#cmbCuerpo").val()=="0")
        {
            alert("Seleccione la ubicación (cuerpo)");
            return;
        }

        if($("#txtTarifa").val()=="")
        {
            alert("Ingrese la tarifa");
            return;
        }

        if($("#cmbValoracion").val()=="0")
        {
            alert("Seleccione la valoración");
            return;
        }


        $("#btnEnviarAnalisis").attr('disabled', 'disabled');

        //Nuevos Tags
        var lstAntiguos=[];
        var lstEnviar=[];
        var lstTags=$("#cmbTags").val();
        for(var i=0;i<lstTags.length;i++) {
            if(""+lstTags[i].indexOf("nuevo_")!=-1)
                lstEnviar.push({"nombre":lstTags[i].replace("nuevo_","")});
            else
                lstAntiguos.push(lstTags[i]);
        }

        if(lstEnviar.length>0)
        {
            var datos={"lstTags":JSON.stringify(lstEnviar)};
            var url="../ws/drclipling/tags";
            $.ajax({
                type: "POST",
                url: url,
                data:JSON.stringify(datos),
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function(result)
                {
                    for(var j=0;j<result.length;j++)
                        lstAntiguos.push(result[j].id);
                    enviarAnalisis(lstAntiguos);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest + " "+textStatus);
                }
            });
        }
        else
        {
            enviarAnalisis(lstTags);
        }
    });


    enviarAnalisis=function(lstTagsSeleccionados)
    {
        var tags="";
        try
        {
            var cmbEtiquetas=$("#cmbDia").val();
            for(var i=0;i<lstTagsSeleccionados.length;i++)
                tags+=lstTagsSeleccionados[i]+",";
        }catch (ex){}
        tags=tags.substring(0,tags.length-1);

        var datos = {
            "usuario_id": $("#usuario_id").val(),
            "publicacion_id": objPublicacionGlobal.id,
            "tipo_noticia_id": objPublicacionGlobal.tipo_noticia_id,
            "tipo_noticia": objPublicacionGlobal.tipo_noticia,
            "nombre_publicacion": $("#txtNombrePublicacion").val(),
            "color_id": $("#cmbColor").val(),
            "color": $("#cmbColor option:selected").text(),
            "tamanio": $("#txtTamanio").val(),
            "cuerpo_id": $("#cmbCuerpo").val(),
            "cuerpo": $("#cmbCuerpo option:selected").text(),
            "dia": $("#txtDia").val(),
            "tarifa_id": 0,
            "tarifa": $("#txtTarifa").val(),
            "valoracion_id": $("#cmbValoracion").val(),
            "valoracion": $("#cmbValoracion option:selected").text(),
            "args": tags,
            "comentario": $("#txtObservaciones").val()
        };

        $.ajax({
            type: "POST",
            url: "../ws/drclipling/analisis",
            data: JSON.stringify(datos),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {

                if (parseInt(result.intCodigo) == 1) {
                    mensaje("ok");
                    llenarLista(0,1,15);
                    $("#btnEnviarAnalisis").removeAttr('disabled');
                }
                else
                {
                    mensaje(result.resultado.errores);
                    $("#btnEnviarAnalisis").removeAttr('disabled');
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
                mensaje("error");
                $("#btnEnviarAnalisis").removeAttr('disabled');
            }
        });
    };

    llenarLista(0,1,15);
    llenarCatalogos('cmbColor',"Color",0);
    llenarCatalogos('cmbCuerpo',"Ubicacion",0);
    llenarCatalogos('cmbValoracion',"Valoracion",0);

    llenarTags();

    llenarCatalogos('cmbCiudad_popup',"Departamentos",0);
    llenarCatalogos('cmbTipoMedio_popup',"Tipo de medio",0);
});

function operateFormatter(value, row, index) {
    return [
        '<button class="seleccionar ml10 btn btn-info btn-sm" title="Seleccionar">',
        'Seleccionar <i class="glyphicon glyphicon-check"></i>',
        '</button>'
    ].join('');
}


window.operateEvents = {
    'click .seleccionar': function (e, value, row, index) {
        var id = row.id;
        var tarifa=row.tarifa;
        $("#tarifaModal").modal("hide");
        $("#txtTarifa").val(tarifa);
    }
};


