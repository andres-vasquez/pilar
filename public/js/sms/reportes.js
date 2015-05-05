/**
 * Created by andresvasquez on 3/18/15.
 */

$.fn.datepicker.dates['es'] = {
    days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"],
    daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
    daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa", "Do"],
    months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
    monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
};

$(document).ready(function () {
    $("#fecha_inicio,#fecha_fin").datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        autoclose: true
    });

    $('#fecha_inicio').datepicker().on('changeDate', function (selected)
    {
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('#fecha_fin').datepicker('setStartDate', startDate);
        mostrarPaso3();
    });
    $('#fecha_fin').datepicker().on('changeDate', function (selected)
    {
        FromEndDate = new Date(selected.date.valueOf());
        FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
        $('#fecha_inicio').datepicker('setEndDate', FromEndDate);
        mostrarPaso3();
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
            $("#divPaso2").removeClass("hidden");
        }
        else
        {
            $("#divPaso2").addClass("hidden");
            $("#divPaso3").addClass("hidden");
        }
    });

});
