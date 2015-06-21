/**
 * Created by andresvasquez on 3/18/15.
 */

var id_seleccionado=0;

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

$(document).ready(function () {
    $("#fecha_inicio" ).datepicker({
        defaultDate: "+1w",
        format: 'dd-mm-yyyy',
        changeMonth: true,
        onClose: function( selectedDate ) {
            $( "#fecha_fin" ).datepicker( "option", "minDate", selectedDate );
            mostrarPaso3();
        }
    });
    $("#fecha_fin" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        onClose: function( selectedDate ) {
            $( "#fecha_inicio" ).datepicker( "option", "maxDate", selectedDate );
            mostrarPaso3();
        }
    });

    mostrarPaso3=function()
    {
        if($("#fecha_inicio").val()!="" && $("#fecha_fin").val()!="")
            $("#divPaso3").removeClass("hidden");
        else
            $("#divPaso3").addClass("hidden");
    };

    $("#cmbTipoReporte").change(function(){
        if($(this).val()!="0")
        {
            $("#txtBusqueda").val("");
            id_seleccionado=0;
            switch(parseInt($(this).val())  )
            {
                case 1: $("#criterio").html("usuarios"); break;
                case 2: $("#criterio").html("email"); break;
                case 3: $("#criterio").html("banco"); break;
                case 4: $("#criterio").html("usuarios");break;
            }
            llenarControlBusqueda($("#criterio").html());

            $("#divPaso2").removeClass("hidden");
            if($(this).val()=="4")
                $("#divPaso3").removeClass("hidden");
            else
                $("#divPaso3").addClass("hidden");

            /*if($(this).val()=="3")
                $("#divTodos").removeClass("hidden");
            else
                $("#divTodos").addClass("hidden");*/
        }
        else
        {
            $("#divPaso2").addClass("hidden");
            $("#divPaso3").addClass("hidden");
        }
    });

    llenarControlBusqueda=function(tipo)
    {
        var url = "../ws/SmsReportes/listado/" + tipo;
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                if(parseInt(result.intCodigo)==1)
                {
                    var lstResultado=result.resultado.resultado;
                    var datos=[];
                    for(var i=0;i<lstResultado.length;i++)
                        datos.push({value: lstResultado[i].id, label: lstResultado[i].nombre});

                    $("#txtBusqueda").autocomplete({
                        minLength: 0,
                        source: datos,
                        focus: function( event, ui ) {
                            $( "#txtBusqueda" ).val(ui.item.label);
                            return false;
                        },
                        select: function( event, ui ) {
                            id_seleccionado=parseInt(ui.item.value);
                            $("#txtBusqueda").val(ui.item.label);
                            return false;
                        }
                    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
                        return $( "<li>" )
                            .append( "<a>"+item.label+ "</a>" )
                            .appendTo( ul );
                    };
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
            }
        });
    };

    $("#btnGenerarReporte").click(function(event){
        event.preventDefault();
        event.stopPropagation();


        var todos;
        if($("#chkTodos").is(':checked'))
            todos=1;
        else
            todos=0;

        var data={
            tipo_reporte:$("#cmbTipoReporte").val(),
            fecha_inicio:$("#fecha_inicio").val(),
            fecha_fin:$("#fecha_fin").val(),
            busqueda:$("#txtBusqueda").val(),
            seleccionado:id_seleccionado,
            todos:todos
        };

        $("#divResultadoReporte").removeClass("hidden");
        $("#divReporte").html('<br/><br/><h4 class="text-center"><i class="fa fa-cog fa-spin"></i> Espere por favor, generando reporte...</h4>');

        var url = "../ws/SmsReportes/reporte";
        $.ajax({
            type: "POST",
            url: url,
            data:JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            dataType: "html",
            success: function (result) {
                $("#btnExportar").removeClass("hidden");
                $("#divReporte").html(result);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
            }
        });
    });

    $("#btnExportar").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        window.open('../output/excel','_blank');
    });

});
