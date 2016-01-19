/**
 * Created by andresvasquez on 11/12/15.
 */

var lstResultado = [];
var lstBase = [];

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
        format: 'dd-mm-yyyy',
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
                case 1: $("#criterio").html("Researcher"); cargarTabla('researcher'); break;
                case 2: $("#criterio").html("Analyst"); cargarTabla('analyst'); break;
                case 3: $("#criterio").html("Ciudad"); cargarTabla('ciudades'); break;
            }
            $("#divPaso2").removeClass("hidden");
            mostrarPaso3();
        }
        else
        {
            $("#divPaso2").addClass("hidden");
            $("#divPaso3").addClass("hidden");
        }
    });

    vaciarTodos = function () {
        for (var i = 0; i < lstResultado.length; i++)
            lstResultado[i] = 0;
    };
    llenarTodos = function () {
        vaciarTodos();
        for (var i = 0; i < lstBase.length; i++)
            lstResultado.push(lstBase[i].id);
    };

    existeEnArray = function (array, item) {
        for (var i = 0; i < array.length; i++)
            if (array[i] == item)
                return true;
        return false;
    };

    cargarTabla=function(criterio){
        var url = "../ws/drclipling/reportes/variablesReporte/" +criterio;
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                lstBase=result;

                $('#tblCriterio').bootstrapTable("destroy");
                $('#tblCriterio').bootstrapTable({
                    data: result
                })
                    .on('check.bs.table', function (e, row) {
                        lstResultado.push(row.id);
                    })
                    .on('uncheck.bs.table', function (e, row) {
                        for (var i = 0; i < lstResultado.length; i++)
                            if (lstResultado[i] == row.id)
                                lstResultado[i] = 0;
                    })
                    .on('check-all.bs.table', function (e) {
                        llenarTodos();
                    })
                    .on('uncheck-all.bs.table', function (e) {
                        vaciarTodos();
                    });
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
            }
        });
    };


    $("#btnGenerarReporte").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        var nLista = [];
        for (var i = 0; i < lstResultado.length; i++)
            if (lstResultado[i] != 0)
                nLista.push(lstResultado[i]);
        lstResultado = nLista;

        var lstFitro="";
        for(var j=0;j<lstResultado.length; j++)
        {
            lstFitro+=lstResultado[j];
            if(j<lstResultado.length-1)
                lstFitro+=",";
        }

        var criterio="";
        switch(parseInt($("#cmbTipoReporte").val())  )
        {
            case 1: criterio='researcher'; break;
            case 2: criterio='analyst'; break;
            case 3: criterio='ciudades'; break;
        }

        var data=
        {
            "criterio":criterio,
            "fecha_inicio":$("#fecha_inicio").val(),
            "fecha_fin":$("#fecha_fin").val(),
            "lstFitro":lstFitro
        };

        console.log(JSON.stringify(data));

        $("#divResultadoReporte").removeClass("hidden");
        $("#divReporte").html('<br/><br/><h4 class="text-center"><i class="fa fa-cog fa-spin"></i> Espere por favor, generando reporte...</h4>');

        var url = "../ws/drclipling/reportes";
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

