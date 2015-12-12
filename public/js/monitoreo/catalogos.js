/**
 * Created by andresvasquez on 11/12/15.
 */

$(document).ready(function()
{
    $(".agregar").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        var html='<tr><td>';
        html+='<input class="form-control" id="ex1" type="number">';
        html+='</td>';
        html+='<td><input class="form-control" type="text"/></td>';
        html+='<td><div class="input-group">';
        html+='<input type="text" class="form-control" readonly>';
        html+='<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>';
        html+='</div></td>';
        html+='<td class="btn-toolbar">';
        html+='<div class="btn-group">';
        html+='<button type="button" class="btn btn-sm  btn-default eliminar">';
        html+='<span class="glyphicon glyphicon-minus"></span>';
        html+='</button>';
        html+='</div>';
        html+='</td></tr>';

        $("#trTablaNuevoCatalogo").append(html);
    });

    llenarCatalogos=function(seccion)
    {
        var credencial=$("#credencial").val();
        var html='';
        var url="../api/v1/catalogos/"+credencial+"/"+seccion;
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
                        html+='<option value="'+arrCatalogos[i].value+'">'+arrCatalogos[i].label+'</option>';
                    $(".chosen-select").html(html).chosen();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
            }
        });
    };

    llenarListaCatalogos=function()
    {
        var credencial=$("#credencial").val();
        var html='';
        var url="../api/v1/catalogos/"+credencial+"";
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                if(parseInt(result.intCodigo)==1) {
                    var lstCatalogos = result.resultado.catalogos;
                    $("#accordion").html("");

                    for (var i = 0; i < lstCatalogos.length; i++) {

                        var agrupador=lstCatalogos[i].agrupador.replace(/ /g,"");
                        console.log(agrupador);

                        var html = '';
                        html += '<div class="panel">';
                        html += '<div class="panel-heading panel-default" role="tab" id="headingOne">';
                        html += '<div class="panel-title">';
                        html += '<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse' +agrupador + '" aria-expanded="true" aria-controls="collapseOne">';
                        html += lstCatalogos[i].agrupador;
                        html += '</a>';
                        html += '</div></div>';
                        html += '<div id="collapse' + agrupador + '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">';
                        html+='<br/><div class="row"><div class="col-lg-6"> ';
                        html += '<table class="table table-bordered text-center"><thead><tr><th class="col-lg-2 text-center"><small>Id</small></th><th class="col-lg-4 text-center"><small>Nombre</small></th><th class="col-lg-4 text-center"><small>Dependencia</small></th></tr></thead>';

                        html+='<tbody>';

                        for(var j=0;j<lstCatalogos[i].datos.length;j++)
                        {
                            html+='<tr>';
                            html+='<td><small>'+lstCatalogos[i].datos[j].value+'</small></td>';
                            html+='<td><small>'+lstCatalogos[i].datos[j].label+'</small></td>';
                            if(lstCatalogos[i].datos[j].padre!=null)
                                html+='<td><small>'+lstCatalogos[i].datos[j].padre+'</small></td>';
                            else
                                html+='<td><small></small></td>';

                            html+='</tr>';
                        }

                        html+='</tbody>';

                        html += '</table></div></div></div>';
                        html += '</div>';
                        $("#accordion").append(html);
                    }
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
            }
        });

    };

    llenarListaCatalogos();
});

