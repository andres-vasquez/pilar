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

$(document).ready(function () {
    $("#txtFechaInicio,#txtFechaFin").datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        autoclose: true
    });

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

    $('#htmlEvento').wysihtml5({
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

    $("#btnAgregar").click(function(event)
    {
        event.preventDefault();
        var datos= {
            "nombre":$("#txtTitular").val(),
            "url_imagen":$("#txtUrlImagen").val(),
            "url_imagen":$("#txtUrlImagen").val(),
            "descripcion":$("#txtDescripcion").val(),
            "html":$("#htmlNoticia").val()
        };

        $("#btnEnviar").attr('disabled', 'disabled');
        $.ajax({
            type: "POST",
            url: $(this).attr("action"),
            data:  JSON.stringify(datos),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                if(parseInt(result.intCodigo)==1)
                {
                    mensaje("ok");
                    $("#btnEnviar").removeAttr('disabled');
                    $("#collapseNoticia").trigger("click");
                    window.location.href="#";
                    llenarNoticias(1,noticiasPorPagina);
                    limpiarCampos();
                }
                else
                {
                    mensaje(result.resultado.errores);
                    window.location.href="#";
                    $("#btnEnviar").removeAttr('disabled');
                }
                $("#imgNoticia").attr("src",urlBase);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
                mensaje("error");
                $("#imgNoticia").attr("src",urlBase);
            }
        });
    });

    llenarHorasMinutos = function()
    {
        var htmlHoras='';
        var htmlMinutos='';

        for(var i=10;i<24;i++)
           htmlHoras+='<option value="">'+padLeft(i,2)+'</option>';

        for(var i=0;i<60;i=i+5)
            htmlMinutos+='<option value="">'+padLeft(i,2)+'</option>';

        $(".horas").html(htmlHoras);
        $(".minutos").html(htmlMinutos);
    };

    padLeft =function(nr, n, str){
        return Array(n-String(nr).length+1).join(str||'0')+nr;
    };

    llenarHorasMinutos();
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
        events_source: function () {
            return [
                {
                    "id": 293,
                    "title": "Event 1",
                    //"url": "http://example.com",
                    "class": "event-important",
                    "start": parseInt(ayer), // Milliseconds
                    "end": parseInt(hoy) // Milliseconds
                },
                {
                    "id": 294,
                    "title": "Event 2",
                    //"url": "http://example.com",
                    "class": "event-warning",
                    "start": parseInt(ayer), // Milliseconds
                    "end": parseInt(hoy) // Milliseconds
                }
            ];
        },
        /*events_source: [
         {
         "id": 293,
         "title": "Event 1",
         "url": "http://example.com",
         "class": "event-important",
         "start": ayer, // Milliseconds
         "end": hoy // Milliseconds
         }
         ],*/
        onAfterEventsLoad: function(events) {
            if(!events) {
                return;
            }
            var list = $('#eventlist');
            list.html('');

            $.each(events, function(key, val)
            {
                $(document.createElement('li'))
                    .html('<a href="' + val.url + '">' + val.title + '</a>')
                    .appendTo(list);
            });
        },
        onAfterViewLoad: function(view) {
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
        modal_title : function (e) { return e.title }
    };

    var calendar = $('#calendario').calendar(options);
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


    $("#btnAgregarModal").click(function(event){
        event.preventDefault();
        event.stopPropagation();
        $("#agregar-modal").modal("show");
    });

    $("#btnAgregar").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        $("#agregar-modal").modal("close");
    });




}(jQuery));


