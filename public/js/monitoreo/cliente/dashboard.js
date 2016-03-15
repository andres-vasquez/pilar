/**
 * Created by andresvasquez on 11/12/15.
 */

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
    $("#txtFechaInicio" ).datepicker({
        defaultDate: "+1w",
        formatDate: 'dd-mm-yy',
        changeMonth: true,
        onClose: function( selectedDate ) {
            $("#txtFechaFin" ).datepicker( "option", "minDate", selectedDate );
        }
    });
    $("#txtFechaFin" ).datepicker({
        defaultDate: "+1w",
        formatDate: 'dd-mm-yy',
        changeMonth: true,
        onClose: function( selectedDate ) {
            $("#txtFechaInicio" ).datepicker( "option", "maxDate", selectedDate );
        }
    });

    llenarCatalogos=function(agrupador,callback){
        var credencial=$("#credencial").val();
        var url="../api/v1/catalogos/"+credencial+"/"+agrupador+"/0";
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: callback,
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
            }
        });
    };


    llenarTablaTipoMedioCiudad=function(args)
    {
        var html='';
        var datos={
          "credencial":$("#credencial").val()
        };

        var url = "../apiclippinh/v1/reportes/tipomediociudad";
        $.ajax({
            type: "POST",
            url: url,
            data:JSON.stringify(datos),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result)
            {
                if(result.intCodigo=="1")
                {
                    var contenido=result.resultado.reporte.contenido;
                    var cantidadColumnas=parseInt(result.resultado.reporte.columnas);

                    var s1 = [];
                    var s2 = [];
                    var ticks = [];

                    for(var i=0;i<contenido.length;i++)
                    {
                        html+='<tr>';
                        for(var j=0;j<cantidadColumnas;j++)
                        {
                            if(i==0 && j>0){ //Titulo
                                ticks.push(contenido[i][j]);
                                html+='<td class="text-center"><a href="#" class="titulo">'+contenido[i][j]+'</a></td>';
                            }
                            else if(j!=0){ //Contenido
                                html+='<td class="text-center"><a href="#" class="contenido">'+contenido[i][j]+'</a></td>';

                                if(i==1)
                                    s1.push(parseInt(contenido[i][j]));
                                else
                                    s2.push(parseInt(contenido[i][j]));
                            }
                            else {//Laterales
                                html+='<td class="text-center"><b>'+contenido[i][j]+'</b></td>';
                            }
                        }
                        html+='</tr>';
                    }

                    plot2 = $.jqplot('graficoTipoMedioCiudad', [s1, s2], {
                        seriesColors:['#30A5FF', '#FFB53E', '#1EBFAE', '#F9243F'],
                        seriesDefaults: {
                            renderer:$.jqplot.BarRenderer,
                            pointLabels: { show: true },
                            rendererOptions: {
                                //varyBarColor: true
                            }
                        },
                        axes: {
                            xaxis: {
                                renderer: $.jqplot.CategoryAxisRenderer,
                                ticks: ticks,
                                label:'Cantidad de publicaciones'
                            },
                            yaxis:{
                                label:'Departamento'
                            }
                        },
                        legend: {
                            show: true,
                            location: 'e',
                            placement: 'outside',
                            labels:['Revista','Periódico'],
                            renderer: $.jqplot.EnhancedLegendRenderer,
                            background: '#ffffff',
                            rowSpacing: '0.5em',
                            rendererOptions: {
                                numberRows: 1,
                                disableIEFading: true
                            }
                        },
                        animate: true,
                        animateReplot: true,
                        cursor: {
                            show: true,
                            zoom: true,
                            looseZoom: true,
                            showTooltip: false
                        },
                        grid: {
                            drawGridLines: true,        // wether to draw lines across the grid or not.
                            gridLineColor: '#cccccc',   // *Color of the grid lines.
                            background: '#ffffff',      // CSS color spec for background color of grid.
                            borderColor: '#999999',     // CSS color spec for border around grid.
                            borderWidth: 2.0,           // pixel width of border around grid.
                            shadow: true,               // draw a shadow for grid.
                            shadowAngle: 45,            // angle of the shadow.  Clockwise from x axis.
                            shadowOffset: 1.5,          // offset from the line of the shadow.
                            shadowWidth: 3,             // width of the stroke for the shadow.
                            shadowDepth: 3,             // Number of strokes to make when drawing shadow.
                                                        // Each stroke offset by shadowOffset from the last.
                            shadowAlpha: 0.07,          // Opacity of the shadow
                            renderer: $.jqplot.CanvasGridRenderer,  // renderer to use to draw the grid.
                            rendererOptions: {}         // options to pass to the renderer.  Note, the default
                                                        // CanvasGridRenderer takes no additional options.
                        }
                    });

                    $("#tblTipoMedioCiudad").html(html);
                }
                else
                {
                    alert(console.log(result));
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
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

                    var arrArgs=$("#args").val().split(",");

                    var objTags = result.resultado.drclippingtags;
                    for(var i=0;i<objTags.length;i++)
                    {
                        var cont=0;
                        for(var j=0;j<arrArgs.length;j++)
                            if(parseInt(objTags[i].id)==parseInt(arrArgs[j]))
                            cont++;
                        if(cont>0)
                            html+='<option value="'+objTags[i].id+'" selected="selected">'+objTags[i].nombre+'</option>';
                    }


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

    llenarTablaTipoMedioCiudad("");

    $('body').on('click',".mes",function (event) {
        event.preventDefault();
        event.stopPropagation();

        var target = $(this).attr("target");
        var ano = d.getFullYear();

        llenarGrafico(ano,parseInt(target));
        $("#btnSelecciondo").html("Seleccionado: " + $(this).html());
        return false;
    });

    llenarLinksMeses=function()
    {
        var d = new Date();
        var mes = d.getMonth()+1;
        $("#btnSelecciondo").html("Seleccionado: " + literalMeses(mes));
        for(var i=mes;i>mes-5;i--)
        {
            if(i>0)
                $("#ulMeses").append('<li><a href="#" class="mes" target="'+i+'">'+literalMeses(i)+'</a></li>');
            else
            {
                var cursor=12+i;
                $("#ulMeses").append('<li><a href="#" class="mes" target="'+cursor+'">'+literalMeses(cursor)+'</a></li>');
            }
        }
    };

    llenarContador=function(campo,criterio)
    {
        $("#"+campo).html("");
        $("#"+campo).append('<i class="fa fa-spinner fa-spin fa-1x"></i>');

        var url = "../pilar/ws/drclipling/reportes/dashboard/" + criterio;
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                if (parseInt(result.intCodigo) == 1)
                {
                    var cantidad = parseInt(result.resultado.resultado.cantidad);
                    $("#"+campo).html(cantidad);
                }
                else
                    $("#"+campo).html("0");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
            }
        });
    };

    literalMeses=function(idMes)
    {
        switch (idMes)
        {
            case 1: return "Enero";
            case 2: return "Febrero";
            case 3: return "Marzo";
            case 4: return "Abril";
            case 5: return "Mayo";
            case 6: return "Junio";
            case 7: return "Julio";
            case 8: return "Agosto";
            case 9: return "Septiembre";
            case 10: return "Octubre";
            case 11: return "Noviembre";
            case 12: return "Diciembre";
        }
    };

    existeEnArray = function (array, item) {
        for (var i = 0; i < array.length; i++)
            if (array[i] == item)
                return true;
        return false;
    };

    var d = new Date();
    var mes = d.getMonth();
    var ano = d.getFullYear();

    llenarTags();
    //llenarGrafico(ano, mes + 1);
    //llenarLinksMeses();

    //llenarContador("numRevistas","revistas");
    //llenarContador("numPeriodico","periodico");
    //llenarContador("numUsuarios","usuarios");
    //llenarContador("numTotal","total");
});

