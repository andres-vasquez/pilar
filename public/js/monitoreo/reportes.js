/**
 * Created by andresvasquez on 11/12/15.
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

$(document).ready(function()
{
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
                case 1: $("#criterio").html("Researcher"); break;
                case 2: $("#criterio").html("Analyst"); break;
                case 3: $("#criterio").html("Ciudad"); break;
                case 4: $("#criterio").html("Usuarios");break;
            }
            //llenarControlBusqueda($("#criterio").html());

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

});

