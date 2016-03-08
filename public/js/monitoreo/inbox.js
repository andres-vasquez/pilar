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
var objAnalisisGlobal=null;
var foto="";
var url_foto="";

var loading=false;
var total=0;

var inicio=1;
var fin=15;
var cursor=fin+1;

var filtro=false
var tipo_filtro="";
var valor="";

$(document).ready(function()
{
    $("#txtFecha" ).datepicker({
        format: 'dd/mm/yyyy'
    });

    sizeContent=function()
    {
        var newHeight = $("#divLleno").height();
        $(".lista").css("height", newHeight);
    };

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


    //**************************** FILTROS ***********************
    $("#btnAplicarFiltros").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        llenarCatalogos('cmbFiltro2',"Departamentos",0);
        llenarCatalogos('cmbFiltro3',"Tipo de medio",0);

        if($("#panelFiltros").hasClass("hidden"))
            $("#panelFiltros").removeClass("hidden");
        else
            $("#panelFiltros").addClass("hidden");
    });

    $("#cmbFiltro1").change(function(){
        $("#panelFiltro2,#panelFiltro3,#panelFiltro4,#panelFiltro5").addClass("hidden");
        switch (parseInt($(this).val()))
        {
            case 1:
                $("#panelFiltro5").removeClass("hidden");
                $("#txtFiltro" ).datepicker( "destroy" );
                $("#txtFiltro" ).removeClass("hasDatepicker").val("");
                break;
            case 2:
                $("#panelFiltro5").removeClass("hidden");
                $("#txtFiltro" ).datepicker({
                    format: 'dd/mm/yyyy',
                    maxDate: '0'
                });
                break;
            case 3:
                $("#panelFiltro2,#panelFiltro3,#panelFiltro4,#panelFiltro6").removeClass("hidden");
                break;
        }
    });

    $("#cmbFiltro2,#cmbFiltro3").change(function(event){
        var idCiudad=$("#cmbFiltro2").val();
        var idTipoMedio=$("#cmbFiltro3").val();

        if(idTipoMedio!="0" && idCiudad!="0")
        {
            if($("#cmbFiltro3 option:selected").text()=="Periódico")
                llenarCatalogos('cmbFiltro4',"Periodicos por dpto",idCiudad);
            else
                llenarCatalogos('cmbFiltro4',"Revistas por dpto",idCiudad);
        }
    });


    $("#btnBusquedaFiltro").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        $("#cmbFiltro1,#cmbFiltro2,#cmbFiltro3,#cmbFiltro4,#txtFiltro,#btnBusquedaFiltro,#btnBusquedaFiltro1").attr("disabled",true);

        filtro=true;

        switch (parseInt($("#cmbFiltro1").val()))
        {
            case 1: tipo_filtro="empresa"; valor=$("#txtFiltro").val(); break;
            case 2:
                tipo_filtro="fecha";

                var arrFecha=$("#txtFiltro").val().split("/");
                valor=arrFecha[2]+"-"+arrFecha[1]+"-"+arrFecha[0];
                alert(valor);
                break;
        }
        $("#btnFiltroActivo").removeClass("hidden");
        llenarLista(0,inicio,fin);
        llenarCantidadPendientes();
    });

    $("#btnBusquedaFiltro1").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        $("#cmbFiltro1,#cmbFiltro2,#cmbFiltro3,#cmbFiltro4,#txtFiltro,#btnBusquedaFiltro,#btnBusquedaFiltro1").attr("disabled",true);

        filtro=true;
        tipo_filtro="medio"; valor=$("#cmbFiltro4").val();
        $("#btnFiltroActivo").removeClass("hidden");
        llenarLista(0,inicio,fin);
        llenarCantidadPendientes();
    });

    $("#btnFiltroActivo").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        $("#cmbFiltro1,#cmbFiltro2,#cmbFiltro3,#cmbFiltro4,#txtFiltro,#btnBusquedaFiltro,#btnBusquedaFiltro1").removeAttr("disabled");
        $("#btnFiltroActivo").addClass("hidden");

        filtro=false;
        tipo_filtro="";
        valor="";
        llenarLista(0,inicio,fin);
        llenarCantidadPendientes();
    });


    llenarLista=function(estado,inicio,fin)
    {
        var html='';

        var url="";
        if(!filtro)
            url="../ws/drclipling/publicacion/"+estado+"/"+inicio+"/"+fin;
        else
            url="../ws/drclipling/publicacion/"+estado+"/"+inicio+"/"+fin+"/"+tipo_filtro+"/"+valor;

        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {

                if (parseInt(result.intCodigo) == 1)
                {
                    var objResultado=result.resultado.publicaciones;

                    total=objResultado.total;
                    var lstPublicaciones=objResultado.publicacion;

                    $("#cargando").addClass("hidden");

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

                            if(lstTareas.length>=total)
                            {
                                $("#btnCargarMas").html("No hay más publicaciones").removeClass("active");
                            }
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

    $("#btnCargarMas").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        if(lstTareas.length>=total)
        {
            $("#btnCargarMas").html("No hay más publicaciones").removeClass("active");
        }
        else
        {
            $("#cargando").removeClass("hidden");
            llenarLista($("#cmbFiltroEstados").val(),cursor,cursor+fin);
            cursor=cursor+fin+1;
        }
    });

    $("#cmbFiltroEstados").change(function(){
        llenarLista($(this).val(),inicio,fin);
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


    /******************************** FOTO ***************************/
    $(".foto").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        if($(this).hasClass("foto1"))
            foto="foto1";
        else
            foto="foto2";

        var src=$(this).attr("src");
        var arrSrc=src.split("?");
        url_foto=arrSrc[0];
        var num = Math.random();

        if(parseInt($("#cmbFiltroEstados").val())==0)
        {
            $("#imgPreview").attr("src",src+"?v="+num).on('load', function(){
                $("#imgPreview").cropper('destroy');
                $(".cropper-container").remove();
            });
            $("#editarImagenModal").modal("show");
        }
        else
        {
            if(parseInt($("#cmbFiltroEstados").val())==1)
            {
                if(parseInt($("#perfil_admin").val())==1) // Perfil ADM
                {
                    $("#imgPreview").attr("src",src+"?v="+num).on('load', function(){
                        $("#imgPreview").cropper('destroy');
                        $(".cropper-container").remove();
                    });
                    $("#editarImagenModal").modal("show");
                }
                else
                {
                    $("#imgPreviewZoom").attr("src",src+"?v="+num);
                    $("#zoomModal").modal("show");
                }
            }
            else // Rechazadas no se pueden editar
            {
                $("#imgPreviewZoom").attr("src",src+"?v="+num);
                $("#zoomModal").modal("show");
            }
        }
    });

    $('#editarImagenModal').on('shown.bs.modal', function() {
        $('#imgPreview').width("100%");
        $('.modal-content').css('height',$(window).height()*0.9);
        $('.modal-content').css('overflowY', 'scroll');

        $("#btnGuardarImagen").html("Guardar cambios");
    });


    $('#zoomModal').on('shown.bs.modal', function() {
        $('#imgPreview').width("100%");
        /*$('#imgPreviewZoom').elevateZoom({
        });*/
    });

    $("#btnCerrarModalFoto").click(function(event){
        //event.preventDefault();
        //event.stopPropagation();
        $("#imgPreview").cropper('clear');
        $("#imgPreview").cropper('clear');
    });

    $("#btnEditarImagen").click(function(event){
        event.preventDefault();
        event.stopPropagation();
        $("#btnGirarDerecha,#btnGirarIzquierda,#btnGuardarImagen,#btnZoomOut,#btnZoomIn").removeClass("hidden");
        $(this).addClass("hidden");
        $('#imgPreview').cropper();
    });


    $("#btnGirarDerecha").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        $('#imgPreview').cropper('rotate', 90);
        /*jQuery.removeData(jQuery('#imgPreview'), 'elevateZoom');//remove zoom instance from image
         jQuery('.zoomContainer').remove();// remove zoom container from DOM
         $('#imgPreview').rotateRight();
         $('#imgPreview').addClass("img-responsive").width("100%");*/
    });

    $("#btnGirarIzquierda").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        $('#imgPreview').cropper('rotate', -90);

        /*jQuery.removeData(jQuery('#imgPreview'), 'elevateZoom');//remove zoom instance from image
         jQuery('.zoomContainer').remove();// remove zoom container from DOM
         $('#imgPreview').rotateLeft();
         $('#imgPreview').addClass("img-responsive").width("100%");*/
    });

    $("#btnZoomIn").click(function(event){
        event.preventDefault();
        event.stopPropagation();
        $('#imgPreview').cropper('zoom', 0.1);
    });

    $("#btnZoomOut").click(function(event){
        event.preventDefault();
        event.stopPropagation();
        $('#imgPreview').cropper('zoom', -0.1);
    });

    $("#btnGuardarImagen").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        var btnSubmit = $(this);
        var htmlCargando='<i class="fa fa-spinner fa-spin"></i> Cargando';
        var htmlCargado='<i class="fa fa-check"></i> Cargado';
        var htmlError='<i class="fa fa-close"></i> Error';


        btnSubmit.html(htmlCargando);
        btnSubmit.attr('disabled', 'disabled');

        var canvas = $("#imgPreview").cropper('getCroppedCanvas');
        var blob = canvas.toDataURL("image/png");

        var formData = new FormData();
        formData.append('imagen', blob);
        formData.append('publicacion_id', objPublicacionGlobal.id);
        formData.append('url', url_foto);
        formData.append('foto', foto);

        var url = "../ws/drclipling/fotos";
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function (result)
            {
                btnSubmit.removeAttr('disabled');
                btnSubmit.html(htmlCargado);

                var result=JSON.parse(result);

                if (parseInt(result.intCodigo) == 1)
                {
                    var fotoTomada=result.resultado.resultado;

                    for(var i=0;i<lstTareas.length;i++)
                        if(lstTareas[i].id==objPublicacionGlobal.id)
                        {
                            var num = Math.random();
                            if(foto=="foto1")
                                lstTareas[i].url_foto1=fotoTomada+"?v="+num;
                            else
                                lstTareas[i].url_foto2=fotoTomada+"?v="+num;
                        }

                    var num = Math.random();
                    if(foto=="foto1")
                    {
                        $("#imgFoto1").attr("src",fotoTomada+"?v="+num).hide();
                        $("#loadingFotoUno").show();
                    }
                    else
                    {
                        $("#imgFoto2").attr("src",fotoTomada+"?v="+num).hide();
                        $("#loadingFotoDos").show();
                    }
                }
                else
                {
                    alert("Error al guardar la imagen");
                    btnSubmit.html(htmlError);
                }
                $("#editarImagenModal").modal("hide");
            },
            error: function () {
                btnSubmit.removeAttr('disabled');
                alert("Error al guardar la imagen");
                btnSubmit.html(htmlError);
                console.log('Upload error');
            }
        });
    });

    jQuery('#editarImagenModal').on('hidden.bs.modal', function (e) {

        $("#btnGirarDerecha,#btnGirarIzquierda,#btnGuardarImagen,#btnZoomOut,#btnZoomIn").addClass("hidden");
        $("#btnEditarImagen").removeClass("hidden");
        /*jQuery.removeData(jQuery('#imgPreview'), 'elevateZoom');//remove zoom instance from image
         jQuery('.zoomContainer').remove();// remove zoom container from DOM
         */
        $("#imgPreview").cropper('clear');
        $("#imgPreview").cropper('destroy');
    });

    jQuery('#zoomModal').on('hidden.bs.modal', function (e) {
        jQuery.removeData(jQuery('#imgPreviewZoom'), 'elevateZoom');//remove zoom instance from image
        jQuery('.zoomContainer').remove();// remove zoom container from DOM
    });



    //FUNCION PRINCIPAL ********************************************
    cargarDetallePublicacion=function(objPublicacion){
        sizeContent();
        limpiarCampos();
        llenarTags();

        $("#btnHabilitarEdicion").addClass("hidden");
        $("#btnEditarAnalisis").addClass("hidden");


        $("#cargandoTarea").addClass("hidden");
        objPublicacionGlobal=objPublicacion;

        var num = Math.random();
        $("#imgFoto1").attr("src",objPublicacion.url_foto1+"?v="+num).hide();
        $("#imgFoto2").attr("src",objPublicacion.url_foto2+"?v="+num).hide();
        $("#loadingFotoUno,#loadingFotoDos").show();


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
            //$("#cmbCuerpo").removeClass("disabled").removeAttr("disabled");
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
            //$("#cmbCuerpo").addClass("disabled").attr("disabled", true);
            $("#txtTarifa").addClass("disabled").attr("disabled", true);
            $("#cmbValoracion").addClass("disabled").attr("disabled", true);
            $("#txtObservaciones").addClass("disabled").attr("disabled", true);

            if($("#cmbFiltroEstados").val()==1)
            {
                $("#btnHabilitarEdicion").removeClass("hidden");
                $("#btnEditarAnalisis").addClass("hidden");
            }
            else //Negativas no se pueden editar
            {
                $("#btnHabilitarEdicion").addClass("hidden");
                $("#btnEditarAnalisis").addClass("hidden");
            }



            $.ajax({
                type: "GET",
                url: "../ws/drclipling/analisisporpublicacion/" + objPublicacion.id,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (result) {
                    if (parseInt(result.intCodigo) == 1)
                    {
                        var objResultado = result.resultado.analisis[0];
                        objAnalisisGlobal=objResultado;

                        $("#txtNombrePublicacion").val(objResultado.nombre_publicacion);
                        $("#txtTamanio").val(objResultado.tamanio);
                        $("#txtDia").val(objResultado.dia);
                        $("#txtTarifa").val(objResultado.tarifa);
                        $("#txtObservaciones").val(objResultado.comentario);
                        $('#cmbColor option[value="'+objResultado.color_id+'"]').attr('selected', 'selected');
                        //$('#cmbCuerpo option[value="'+objResultado.cuerpo_id+'"]').attr('selected', 'selected');
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

        if($("#txtObservaciones").val()=="")
        {
            alert("Introduzca sus observaciones para rechazar esta publicación.");
            return;
        }

        $("#rechazarModal").modal("show");
    });

    $("#btnRechazar").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        var data={
            "observaciones":$("#txtObservaciones").val()
        };

        $("#btnRechazar").attr('disabled',true);
        $.ajax({
            type: "POST",
            data: JSON.stringify(data),
            url: "../ws/drclipling/publicacion/rechazar/"+objPublicacionGlobal.id,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {

                if (parseInt(result.intCodigo) == 1)
                {
                    mensaje("ok");
                    llenarLista(0,inicio,fin);
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

    /********************* CONTROLES DE EDICION ******************************/
    $("#btnHabilitarEdicion").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        $(this).addClass("hidden");
        $("#tdAcciones,#tdAccionesTitulo").removeClass("hidden");
        $("#cmbTags").removeClass("disabled").removeAttr("disabled").removeProp('disabled').trigger("chosen:updated");
        $("#txtNombrePublicacion").removeClass("disabled").removeAttr("disabled");
        $("#cmbColor").removeClass("disabled").removeAttr("disabled");
        $("#txtTamanio").removeClass("disabled").removeAttr("disabled");
        //$("#cmbCuerpo").removeClass("disabled").removeAttr("disabled");
        $("#txtTarifa").removeClass("disabled").removeAttr("disabled");
        $("#cmbValoracion").removeClass("disabled").removeAttr("disabled");
        $("#txtObservaciones").removeClass("disabled").removeAttr("disabled");

        var arrTags=objAnalisisGlobal.args.split(",");
        for(var i=0;i<arrTags.length;i++)
            $('#cmbTags option[value="'+arrTags[i]+'"]').attr('selected', 'selected').trigger("chosen:updated");

        $("#btnEditarAnalisis").removeClass("hidden");
    });


    $("#btnEditarAnalisis").click(function(event){
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

        /*if($("#cmbCuerpo").val()=="0")
        {
            alert("Seleccione la ubicación (cuerpo)");
            return;
        }*/

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
                    editarAnalisis(lstAntiguos,objAnalisisGlobal.id);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest + " "+textStatus);
                }
            });
        }
        else
        {
            editarAnalisis(lstTags,objAnalisisGlobal.id);
        }
    });

    editarAnalisis=function(lstTagsSeleccionados,id)
    {
        var tags="";
        try
        {
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
            //"cuerpo_id": $("#cmbCuerpo").val(),
            //"cuerpo": $("#cmbCuerpo option:selected").text(),
            "cuerpo_id": 0,
            "cuerpo": "",
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
            url: "../ws/drclipling/analisis/"+id,
            data: JSON.stringify(datos),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {

                if (parseInt(result.intCodigo) == 1) {
                    mensaje("editada");
                    llenarLista(1,inicio,fin);
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

    //Control de carga fotos
    $("#imgFoto1").load(function() {
        $("#loadingFotoUno").hide();
        $("#imgFoto1").show();
    });
    $("#imgFoto2").load(function() {
        $("#loadingFotoDos").hide();
        $("#imgFoto2").show();
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
        $("#mensaje").html(html).focus();
    };

    limpiarCampos=function()
    {
        $("#cmbTags").val('').trigger("chosen:updated");
        $("#txtNombrePublicacion").val("");
        $('#cmbColor option[value=0]').attr('selected', 'selected');
        $("#txtTamanio").val("");
        //$('#cmbCuerpo option[value=0]').attr('selected', 'selected');
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

        /*if($("#cmbCuerpo").val()=="0")
        {
            alert("Seleccione la ubicación (cuerpo)");
            return;
        }*/

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
            //"cuerpo_id": $("#cmbCuerpo").val(),
            //"cuerpo": $("#cmbCuerpo option:selected").text(),
            "cuerpo_id": 0,
            "cuerpo": "",
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
                    llenarLista(0,inicio,fin);
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

    llenarCantidadPendientes=function() {

        var url="";
        if(!filtro)
            url="../ws/drclipling/reportes/conteoInbox";
        else
            url="../ws/drclipling/reportes/conteoInbox/"+tipo_filtro+"/"+valor;

        var html='';
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                for(var i=0;i<result.length;i++)
                    switch (parseInt(result[i].estado_tarea))
                    {
                        case 0: html+='<option value="0">Tareas pendientes ('+result[i].cantidad+')</option>'; break;
                        case 1: html+='<option value="1">Tareas realizadas ('+result[i].cantidad+')</option>'; break;
                        case 2: html+='<option value="2">Tareas rechazada ('+result[i].cantidad+')</option>'; break;
                    }
                $("#cmbFiltroEstados").html(html).trigger("change");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
            }
        });
    };

    llenarCantidadPendientes();
    //Form Anaylis
    llenarCatalogos('cmbColor',"Color",0);
    //llenarCatalogos('cmbCuerpo',"Ubicacion",0);
    llenarCatalogos('cmbValoracion',"Valoracion",0);
    llenarTags();

    llenarLista(0,inicio,fin);
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


