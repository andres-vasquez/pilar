$.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '<Ant',
    nextText: 'Sig>',
    currentText: 'Hoy',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''
};
$.datepicker.setDefaults($.datepicker.regional['es']);

/**
 * Created by andresvasquez on 11/12/15.
 */
var lstTags=[];
var lstTareas=[];
var objPublicacionGlobal=null;

$(document).ready(function()
{
    $("#txtFecha" ).datepicker({
        format: 'dd/mm/yyyy'
    });


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

                    if(lstPublicaciones.length>0)
                    {
                        $("#divVacio").addClass("hidden");
                        $("#divLleno").removeClass("hidden");

                        switch(parseInt(estado))
                        {
                            case 0:
                                $("#btnEnviarAnalisis, #btnEnviarNegativa")
                                    .removeClass("hidden")
                                break;
                            case 1:
                                $("#btnEnviarAnalisis, #btnEnviarNegativa")
                                    .addClass("hidden")
                                break;
                            case 2:
                                $("#btnEnviarAnalisis, #btnEnviarNegativa")
                                    .addClass("hidden")
                                break;
                        }

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
                        $("#divVacio").removeClass("hidden");
                        $("#divLleno").addClass("hidden");
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
        $("#cmbTipoMedio").trigger('change');
        $("#editarModal").modal("show");
    });

    $("#cmbTipoMedio,#cmbCiudad").change(function(event){
        var idTipoMedio=$("#cmbTipoMedio").val();
        var idCiudad=$("#cmbCiudad").val();

        if(idTipoMedio!="0" && idCiudad!="0")
        {
            if($("#cmbTipoMedio option:selected").text()=="Periódico")
                llenarCatalogosSeleccionado('cmbMedio',"Periodicos por dpto",0,objPublicacionGlobal.medio_id);
            else
                llenarCatalogosSeleccionado('cmbMedio',"Revistas por dpto",0,objPublicacionGlobal.medio_id);
        }
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

    $("#btnGirarDerecha").click(function(event){
       event.preventDefault();
        event.stopPropagation();

        jQuery.removeData(jQuery('#imgPreview'), 'elevateZoom');//remove zoom instance from image
        jQuery('.zoomContainer').remove();// remove zoom container from DOM
        $('#imgPreview').rotateRight();
        $('#imgPreview').addClass("img-responsive").width("100%");
    });

    $("#btnGirarIzquierda").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        jQuery.removeData(jQuery('#imgPreview'), 'elevateZoom');//remove zoom instance from image
        jQuery('.zoomContainer').remove();// remove zoom container from DOM
        $('#imgPreview').rotateLeft();
        $('#imgPreview').addClass("img-responsive").width("100%");
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

        if(parseInt(objPublicacion.estado_tarea)==0)
        {
            $("#tdAcciones,#tdAccionesTitulo").removeClass("hidden");
            $("#cmbTags").removeClass("disabled").removeAttr("disabled").removeProp('disabled').trigger("chosen:updated");
            $("#txtNombrePublicacion").removeClass("disabled").removeAttr("disabled");
            $("#cmbColor").removeClass("disabled").removeAttr("disabled");
            $("#txtTamanio").removeClass("disabled").removeAttr("disabled");
            $("#cmbCuerpo").removeClass("disabled").removeAttr("disabled");
            $("#txtTarifa").removeClass("disabled").removeAttr("disabled");
            $("#cmbValoracion").removeClass("disabled").removeAttr("disabled");
            $("#txtObservaciones").removeClass("disabled").removeAttr("disabled");

            $("#txtPagina").val(objPublicacion.pagina);
            $("#txtEmpresa").val(objPublicacion.empresa);
            $("#txtFecha").val(objPublicacion.fecha_publicacion);

            //Editar Modal
            llenarCatalogosSeleccionado('cmbCiudad',"Departamentos",0,objPublicacionGlobal.ciudad_id);
            llenarCatalogosSeleccionado('cmbTipoMedio',"Tipo de medio",0,objPublicacionGlobal.tipo_medio_id);
            llenarCatalogosSeleccionado('cmbUbicacion',"Ubicacion",0,objPublicacionGlobal.ubicacion_id);
            llenarCatalogosSeleccionado('cmbTipoNoticia',"Tipo de noticia",0,objPublicacionGlobal.tipo_noticia_id);

            //Tarigas Modal
            llenarCatalogosSeleccionado('cmbCiudad_popup',"Departamentos",0,objPublicacionGlobal.ciudad_id);
            llenarCatalogosSeleccionado('cmbTipoMedio_popup',"Tipo de medio",0,objPublicacionGlobal.tipo_medio_id);
        }
        else
        {
            //TAREAS REALIZADAS
            $("#tdAcciones,#tdAccionesTitulo").addClass("hidden");
            $("#cmbTags").addClass("disabled").attr("disabled", true).prop('disabled', true).trigger("chosen:updated");
            $("#txtNombrePublicacion").addClass("disabled").attr("disabled", true);
            $("#cmbColor").addClass("disabled").attr("disabled", true);
            $("#txtTamanio").addClass("disabled").attr("disabled", true);
            $("#cmbCuerpo").addClass("disabled").attr("disabled", true);
            $("#txtTarifa").addClass("disabled").attr("disabled", true);
            $("#cmbValoracion").addClass("disabled").attr("disabled", true);
            $("#txtObservaciones").addClass("disabled").attr("disabled", true);

            $.ajax({
                type: "GET",
                url: "../ws/drclipling/analisisporpublicacion/" + objPublicacion.id,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (result) {
                    if (parseInt(result.intCodigo) == 1) {
                        var objResultado = result.resultado.analisis[0];
                        $("#txtNombrePublicacion").val(objResultado.nombre_publicacion);
                        $("#txtTamanio").val(objResultado.tamanio);
                        $("#txtDia").val(objResultado.dia);
                        $("#txtTarifa").val(objResultado.tarifa);
                        $("#txtObservaciones").val(objResultado.comentario);
                        $('#cmbColor option[value="'+objResultado.color_id+'"]').attr('selected', 'selected');
                        $('#cmbCuerpo option[value="'+objResultado.cuerpo_id+'"]').attr('selected', 'selected');
                        $('#cmbValoracion option[value="'+objResultado.valoracion_id+'"]').attr('selected', 'selected');

                        var arrTags=objResultado.args.split(",");
                        for(var i=0;i<arrTags.length;i++)
                            $('#cmbTags option[value="'+arrTags[i]+'"]').attr('selected', 'selected').trigger("chosen:updated");
                    }
                }
            });
        }
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

    $("#btnEditarPublicacion").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        if($("#cmbCiudad").val()=="0")
        {
            alert("Seleccione la ciudad");
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

        if($("#cmbUbicacion").val()=="0")
        {
            alert("Seleccione la Ubicacioón");
            return;
        }

        if($("#txtPagina").val()=="")
        {
            alert("Ingrese el número de página");
            return;
        }

        if($("#txtEmpresa").val()=="")
        {
            alert("Ingrese la Empresa");
            return;
        }

        if($("#cmbTipoNoticia").val()=="")
        {
            alert("Ingrese el tipo de noticia");
            return;
        }

        if($("#txtFecha").val()=="0")
        {
            alert("Ingrese la fecha de la publicación");
            return;
        }

        var datos={
            "ciudad_id":$("#cmbCiudad").val(),
            "ciudad":$("#cmbCiudad option:selected").text(),
            "tipo_medio_id":$("#cmbTipoMedio").val(),
            "tipo_medio":$("#cmbTipoMedio option:selected").text(),
            "medio_id":$("#cmbMedio").val(),
            "medio":$("#cmbMedio option:selected").text(),
            "fecha_publicacion":$("#txtFecha").val(),
            "ubicacion_id":$("#cmbUbicacion").val(),
            "ubicacion":$("#cmbUbicacion option:selected").text(),
            "pagina":$("#txtPagina").val(),
            "empresa":$("#txtEmpresa").val(),
            "tipo_noticia_id":$("#cmbTipoNoticia").val(),
            "tipo_noticia":$("#cmbTipoNoticia option:selected").text()
        };

        $("#btnEditarPublicacion").attr('disabled',true);
        $.ajax({
            type: "POST",
            url: "../ws/drclipling/publicacion/"+objPublicacionGlobal.id,
            data:JSON.stringify(datos),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {

                if (parseInt(result.intCodigo) == 1)
                {
                    mensaje("editada");
                    $("#btnEditarPublicacion").removeAttr('disabled');

                    //Actualiza los datos de campos
                    $("#tdCiudad").html($("#cmbCiudad option:selected").text());
                    $("#tdTipoMedio").html($("#cmbTipoMedio option:selected").text());
                    $("#tdMedio").html($("#cmbMedio option:selected").text());
                    $("#tdUbicacion").html($("#cmbUbicacion option:selected").text());
                    $("#tdPagina").html($("#txtPagina").val());
                    $("#tdEmpresa").html($("#txtEmpresa").val());
                    $("#tdFecha").html($("#txtFecha").val());

                    var arrFecha=$("#txtFecha").val().split("/");
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
                }
                else
                {
                    mensaje(result.resultado.errores);
                    $("#btnEditarPublicacion").removeAttr('disabled');
                }
                $("#editarModal").modal("hide");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
                mensaje("error");
                $("#btnEditarPublicacion").removeAttr('disabled');
            }
        });
    });

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

                    $('#'+campo+' option[value="'+idSeleccionado+'"]').attr('selected', 'selected');
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

        $('#cmbCiudad_popup').trigger('change');
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

            llenarCatalogosSeleccionado('cmbMedio_popup',agrupador,idCiudad,objPublicacionGlobal.medio_id);
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

    $("#btnEnviarNegativa").click(function(event){
        event.preventDefault();
        event.stopPropagation();
        $("#rechazarModal").modal("show");

        $("#btnRechazar").click(function(event){
            event.preventDefault();
            event.stopPropagation();

            $("#btnRechazar").attr('disabled',true);
            $.ajax({
                type: "POST",
                url: "../ws/drclipling/publicacion/rechazar/"+objPublicacionGlobal.id,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (result) {

                    if (parseInt(result.intCodigo) == 1)
                    {
                        mensaje("ok");
                        llenarLista(0,1,15);
                        $("#btnRechazar").removeAttr('disabled');
                    }
                    else
                    {
                        mensaje(result.resultado.errores);
                        $("#btnRechazar").removeAttr('disabled');
                    }
                    $("#rechazarModal").modal("hide");
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest + " " + textStatus);
                    mensaje("error");
                    $("#btnRechazar").removeAttr('disabled');
                }
            });



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


    //Form Anaylis
    llenarCatalogos('cmbColor',"Color",0);
    llenarCatalogos('cmbCuerpo',"Ubicacion",0);
    llenarCatalogos('cmbValoracion',"Valoracion",0);
    llenarTags();

    llenarLista(0,1,15);
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


