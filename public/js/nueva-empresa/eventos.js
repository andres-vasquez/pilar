/**
 * Created by andresvasquez on 9/14/15.
 */
$.fn.datepicker.dates['es'] = {
    days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"],
    daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
    daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa", "Do"],
    months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
    monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
};


var rutaImagen="";
var calendar;
var editando=false;
var id_editando=0;

var obj = []; // Aca se guarda el punto
var map;

var latitud = 0;
var longitud = 0;
var editando=false;

$(document).ready(function () {


    $("#txtFechaInicio,#txtFechaFin,#txtFechaInicio_editar,#txtFechaFin_editar").datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        autoclose: true
    });

    //Fechas nuevo
    $('#txtFechaInicio').datepicker().on('changeDate', function (selected) {
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('#txtFechaFin').datepicker('setStartDate', startDate);
    });
    $('#txtFechaFin').datepicker().on('changeDate', function (selected) {
        FromEndDate = new Date(selected.date.valueOf());
        FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
        $('#txtFechaInicio').datepicker('setEndDate', FromEndDate);
    });

    //Fechas editar
    $('#txtFechaInicio_editar').datepicker().on('changeDate', function (selected) {
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('#txtFechaFin_editar').datepicker('setStartDate', startDate);
    });
    $('#txtFechaFin_editar').datepicker().on('changeDate', function (selected) {
        FromEndDate = new Date(selected.date.valueOf());
        FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
        $('#txtFechaInicio_editar').datepicker('setEndDate', FromEndDate);
    });

    $('#htmlEvento,#htmlEvento_editar').wysihtml5({
        toolbar: {
            "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
            "emphasis": true, //Italics, bold, etc. Default true
            "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
            "html": false, //Button which allows you to edit the generated HTML. Default false
            "link": false, //Button to insert a link. Default true
            "image": false, //Button to insert an image. Default true,
            "color": false, //Button to change color of font
            "blockquote": true //Blockquote
        },
        locale: "es-ES"
    });


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


    //Envio de la fotografia
    $("form").submit(function (event)
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
                        $("#imgEvento").attr("src", ruta);
                    }
                    else
                    {
                        $("#hdnRutaImagen_editar").val(ruta);
                        $("#imgImagenEvento_editar").attr("src", ruta);
                    }

                    rutaImagen=ruta;
                    btnSubmit.html(htmlCargado);
                }
                else
                {
                    alert("Error al subir la imagen del evento");
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
    });

    /*********************** POPUP MAPA ************/
        //Click en abrir el popup
    $("#btnGeo,#btnGeo_editar").click(function(event){
        event.stopPropagation();
        event.preventDefault();

        $("#divMapa").modal("show");
    });

    $("#divMapa").on('shown.bs.modal', function() {
        iniciarMapa();
        google.maps.event.addListenerOnce(map, 'resize', function ()
        {
            iniciarMapa();
            var lat = $("#lat").val();
            var lon = $("#lon").val();
            //Si hay algo en la caja de texto lo mostramos en el mapa
            if (lat != "" && lon != "") {
                placeMarker(new google.maps.LatLng(parseFloat(lat), parseFloat(lon)));
            }
            else
            {
                //Deshabilitamos el botón seleccionar hasta que se haga alguna accion en el mapa
                $("#btnSeleccionarPunto").attr("disabled", "disabled");
            }
        });
        google.maps.event.trigger(map, "resize");
    });

    $("#divMapa").on('hidden.bs.modal', function () {

        if(id_editando==0) {
            if ($("#lat").val() != "" && $("#lon").val() != "")
                $("#btnGeo").removeClass("btn-warning").addClass("btn-success").html("Tiene ubicación Georeferenciada!");
            else
                $("#btnGeo").removeClass("btn-success").addClass("btn-warning").html("Agregar Ubicación Georeferenciada");
        }
        else
        {
            if ($("#lat").val() != "" && $("#lon").val() != "")
                $("#btnGeo_editar").removeClass("btn-warning").addClass("btn-success").html("Tiene ubicación Georeferenciada!");
            else
                $("#btnGeo_editar").removeClass("btn-success").addClass("btn-warning").html("Agregar Ubicación Georeferenciada");
        }
        $("body").addClass("modal-open");
    });

    //BOTONES DEL POP UP
    //Seleccionamos punto y cerramos el pop up
    $("#btnSeleccionarPunto").click(function(event) {
        event.stopPropagation();
        event.preventDefault();

        seleccionarPunto();
        $("#divMapa").modal("hide");
    });

    //Cerramos el pop up
    $(".cancelar").click(function(event) {
        event.stopPropagation();
        event.preventDefault();
        $("#divMapa").modal("hide");
    });

    /********************* FIN MAPA **********************/


    $("#btnAgregar").click(function(event)
    {
        event.preventDefault();

        var horaInicio = $("#cmbHoraInicio").val();
        var horaFin = $("#cmbHoraFin").val();
        var minInicio = $("#cmbMinutoInicio").val();
        var minFin = $("#cmbMinutoFin").val();

        var datos= {
            "nombre":$("#txtNombre").val(),
            "lugar":$("#txtLugar").val(),
            "imagen_aws":$("#hdnRutaImagen").val(),
            "fecha_inicio":$("#txtFechaInicio").val()+" "+horaInicio+":"+minInicio+":00",
            "fecha_fin":$("#txtFechaFin").val()+" "+horaFin+":"+minFin+":00",
            "descripcion":$("#txtDescripcion").val(),
            "lat":$("#lat").val(),
            "lon":$("#lon").val(),
            "tipo_evento":$('input[name=tipo_evento]:checked').val(),
            "html":$("#htmlEvento").val()
        };
        //console.log(JSON.stringify(datos));

        var url = "../ws/evento";
        $("#btnAgregar").attr('disabled', 'disabled');
        $.ajax({
            type: "POST",
            url: url,
            data:  JSON.stringify(datos),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                $("#agregar-modal").modal("hide");
                $("#btnAgregar").removeAttr('disabled');
                if(parseInt(result.intCodigo)==1)
                {
                    mensaje("ok");
                    window.location.href="#";
                    limpiarCampos();
                    calendar.view();
                }
                else
                {
                    mensaje(result.resultado.errores);
                    window.location.href="#";
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
                $("#btnAgregar").removeAttr('disabled');
            }
        });
    });

    ///Boton para editar el Evento
    $("#btnEditarEvento").click(function(event){
        event.preventDefault();

        var horaInicio = $("#cmbHoraInicio_editar").val();
        var horaFin = $("#cmbHoraFin_editar").val();
        var minInicio = $("#cmbMinutoInicio_editar").val();
        var minFin = $("#cmbMinutoFin_editar").val();

        var datos= {
            "nombre":$("#txtNombre_editar").val(),
            "lugar":$("#txtLugar_editar").val(),
            "imagen_aws":$("#hdnRutaImagen_editar").val(),
            "fecha_inicio":$("#txtFechaInicio_editar").val()+" "+horaInicio+":"+minInicio+":00",
            "fecha_fin":$("#txtFechaFin_editar").val()+" "+horaFin+":"+minFin+":00",
            "descripcion":$("#txtDescripcion_editar").val(),
            "lat":$("#lat").val(),
            "lon":$("#lon").val(),
            "tipo_evento":$('input[name=tipo_evento_editar]:checked').val(),
            "html":$("#htmlEvento_editar").val()
        };
        //console.log(JSON.stringify(datos));

        var url = "../ws/evento/"+id_editando;
        $("#btnEditarEvento").attr('disabled', 'disabled');
        $.ajax({
            type: "POST",
            url: url,
            data:  JSON.stringify(datos),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                $("#btnEditarEvento").removeAttr('disabled');
                if(parseInt(result.intCodigo)==1)
                {
                    mensaje("editada");
                    window.location.href="#";
                    limpiarCamposEditar();
                    calendar.view();
                    $("#events-modal").modal("hide");
                }
                else
                {
                    mensaje(result.resultado.errores);
                    window.location.href="#";
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
                $("#btnEditarEvento").removeAttr('disabled');
            }
        });
    });

    $("#btnMostrarModalEliminar").click(function(event){
        event.preventDefault();
        event.stopPropagation();
        $("#eliminarModal").modal("show");
    });

    //Eliminar Evento
    $("#btnEliminarEvento").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        var url = "../ws/evento/eliminar/"+id_editando;

        $("#btnEliminarEvento").attr("disabled","disabled");
        $.ajax({
            type: "POST",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                $("#btnEliminarEvento").removeAttr('disabled');
                if(parseInt(result.intCodigo)==1)
                {
                    mensaje("eliminada");
                    window.location.href="#";
                    calendar.view();
                    $("#eliminarModal").modal("hide");
                    $("#events-modal").modal("hide");
                }
                else
                {
                    mensaje(result.resultado.errores);
                    window.location.href="#";
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
                $("#btnEliminarEvento").removeAttr('disabled');
            }
        });
    });


    mensaje=function(tipo) {
        $("#mensaje").html('');
        $("#mensaje").addClass("alert");
        var html='';
        if(tipo=="ok")
        {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html+='<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html+='<strong>¡Evento agregado!</strong>';
        }
        else if(tipo=="editada")
        {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html+='<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html+='<strong>¡Evento editado!</strong>';
        }
        else if(tipo=="eliminada")
        {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html+='<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html+='<strong>¡Evento eliminado!</strong>';
        }
        else
        {
            $("#mensaje").removeClass("alert-success");
            $("#mensaje").addClass("alert-danger");
            html+='<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html+='<strong>¡Error!</strong> '+tipo;
        }
        $("#mensaje").html(html);
    };

    llenarHorasMinutos = function()
    {
        var htmlHoras='';
        var htmlMinutos='';

        for(var i=10;i<24;i++)
            htmlHoras+='<option value="'+padLeft(i,2)+'">'+padLeft(i,2)+'</option>';

        for(var i=0;i<60;i=i+5)
            htmlMinutos+='<option value="'+padLeft(i,2)+'">'+padLeft(i,2)+'</option>';

        $(".horas").html(htmlHoras);
        $(".minutos").html(htmlMinutos);
    };

    padLeft =function(nr, n, str){
        return Array(n-String(nr).length+1).join(str||'0')+nr;
    };


    limpiarCampos=function()
    {
        $("#txtTitular").val("");
        $("txtLugar").val("");
        $("#hdnRutaImagen").val("");
        $("#txtFechaInicio").val();
        $("#txtFechaFin").val("");
        $("#txtDescripcion").val("");
        $("#htmlNoticia").val("");

        $("#lat").val("");
        $("#lon").val("");

        llenarHorasMinutos();
    };

    limpiarCamposEditar=function()
    {
        $("#txtTitular_editar").val("");
        $("#txtLugar_editar").val("");
        $("#hdnRutaImagen_editar").val("");
        $("#txtFechaInicio_editar").val();
        $("#txtFechaFin_editar").val("");
        $("#txtDescripcion_editar").val("");
        $("#htmlNoticia_editar").val("");

        $("#lat").val("");
        $("#lon").val("");

        llenarHorasMinutos();
    };

    llenarHorasMinutos();

    //Funciones de MAPA
    iniciarMapa = function ()
    {
        var mapOptions = {
            center: new google.maps.LatLng(-16.2837065, -63.5493965),
            zoom: 5,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("mapa"),
            mapOptions);

        //Evento click sobre el mapa
        google.maps.event.addListener(map, 'click', function (event)
        {
            //Colocamos un marcador
            placeMarker(event.latLng);
            latitud = event.latLng.lat();
            longitud = event.latLng.lng();
            //deshabili
            $("#btnSeleccionarPunto").removeAttr("disabled");
        });
    };

    placeMarker = function(location)
    {
        //Vacia los puntos existentes
        for (var i = 0; i < obj.length; i++)
            obj[i].setMap(null);

        console.log("PC1");

        var marker = new google.maps.Marker({
            position: location,
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP
        });
        obj.push(marker); // Agrega el punto selccionado
        console.log("PC2");

        //Evento cuando el mapa se mueve
        google.maps.event.addListener(marker, 'dragend', function(event) {
            latitud = event.latLng.lat();
            longitud = event.latLng.lng();
        });

        console.log("PC3");
        //Centra el mapa
        map.setCenter(location);
        return marker;
    };

    seleccionarPunto = function() {
        $("#lat").val(latitud);
        $("#lon").val(longitud);
    };


    //Agregar modal
    $("#btnAgregarModal").click(function(event){
        event.preventDefault();
        event.stopPropagation();
        editando=false;
        id_editando=0;
        $("#agregar-modal").modal("show");
    });
});

(function($) {
    "use strict";

    var match = '2015-09-16 10:30:00'.match(/^(\d+)-(\d+)-(\d+) (\d+)\:(\d+)\:(\d+)$/)
    var date = new Date(match[1], match[2] - 1, match[3], match[4], match[5], match[6])
    var ayer=date.getTime();

    match = '2015-09-16 15:30:00'.match(/^(\d+)-(\d+)-(\d+) (\d+)\:(\d+)\:(\d+)$/)
    date = new Date(match[1], match[2] - 1, match[3], match[4], match[5], match[6])
    var hoy=date.getTime();

    console.log(ayer);
    console.log(hoy);

    var options =
    {
        time_start: '10:00',
        time_end: '23:30',
        time_split: '30',
        tmpl_path: "../public/lib/bower_components/bootstrap-calendar/tmpls/",
        events_source:'../ws/eventos/sinformato', //REF: https://github.com/Serhioromano/bootstrap-calendar
        onAfterEventsLoad: function(events) {
            if(!events) {
                return;
            }
            var list = $('#eventlist');
            list.html('');

            $.each(events, function(key, val){
                $(document.createElement('li'))
                    .html('<a href="' + val.url + '">' + val.title + '</a>')
                    .appendTo(list);
            });
        },
        onAfterViewLoad: function(view)
        {
            $('.page-header h3').text(this.getTitle());
            $('.btn-group button').removeClass('active');
            $('button[data-calendar-view="' + view + '"]').addClass('active');
        },
        classes: {
            months: {
                general: 'label'
            }
        },
        modal : "#events-modal",
        modal_type : "ajax",
        modal_title : function (e)
        {
            var objEvento=e;
            id_editando=objEvento.id;
            var url='../ws/evento/'+objEvento.id;
            $.ajax({
                type: "GET",
                url: url,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function(result)
                {
                    console.log(JSON.stringify(result));
                    if(parseInt(result.intCodigo)==1)
                    {
                        var objEvento=result.resultado.evento;
                        //$("#txtTituloDetalleEvento").html(objEvento.nombre);
                        $("#imgImagenEvento_editar").attr("src",objEvento.imagen_aws);
                        $("#txtNombre_editar").val(objEvento.nombre);
                        $("#hdnRutaImagen_editar").val(objEvento.imagen_aws);
                        $("#txtLugar_editar").val(objEvento.lugar);

                        if(objEvento.lat!="0" && objEvento.lon!="0")
                        {
                            $("#lat").val(objEvento.lat);
                            $("#lon").val(objEvento.lon);
                            $("#btnGeo_editar").removeClass("btn-warning").addClass("btn-success").html("Tiene ubicación Georeferenciada!");
                        }

                        var tipo=objEvento.tipo_evento;
                        if(tipo=="emprendimiento")
                            $("#emprendimiento_editar").prop("checked", true);
                        else
                            $("#empresarial_editar").prop("checked", true);

                        editando=true;

                        var arr_fecha_inicio=objEvento.fecha_inicio.split(" ");
                        var arr_fecha_fin=objEvento.fecha_fin.split(" ");

                        $("#txtFechaInicio_editar").val(arr_fecha_inicio[0]);
                        $("#txtFechaFin_editar").val(arr_fecha_fin[0]);

                        var arr_hora_inicio=arr_fecha_inicio[1].split(":");
                        var arr_hora_fin=arr_fecha_fin[1].split(":");

                        $('#cmbHoraInicio_editar option[value="' + arr_hora_inicio[0] + '"]').attr("selected", "selected");
                        $('#cmbMinutoInicio_editar option[value="' + arr_hora_inicio[1] + '"]').attr("selected", "selected");

                        $('#cmbHoraFin_editar option[value="' + arr_hora_fin[0] + '"]').attr("selected", "selected");
                        $('#cmbMinutoFin_editar option[value="' + arr_hora_fin[1] + '"]').attr("selected", "selected");

                        $("#txtDescripcion_editar").val(objEvento.descripcion);



                        var wysihtml5Editor = $('#htmlEvento_editar').data("wysihtml5").editor;
                        wysihtml5Editor.setValue(objEvento.html);

                    }
                    else
                    {
                        alert("Error al cargar evento");
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest + " "+textStatus);
                }
            });
            return e.title
        }
    };

    calendar = $('#calendario').calendar(options);
    calendar.setOptions({display_week_numbers: false});
    calendar.setOptions({weekbox: false});
    calendar.setLanguage('es-ES');
    calendar.view();

    $('.btn-group button[data-calendar-nav]').each(function() {
        var $this = $(this);
        $this.click(function() {
            calendar.navigate($this.data('calendar-nav'));
        });
    });

    $('.btn-group button[data-calendar-view]').each(function() {
        var $this = $(this);
        $this.click(function() {
            calendar.view($this.data('calendar-view'));
        });
    });

}(jQuery));


