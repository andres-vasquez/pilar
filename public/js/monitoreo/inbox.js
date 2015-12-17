/**
 * Created by andresvasquez on 11/12/15.
 */
var lstTareas=[];

$(document).ready(function()
{

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
                cargarDetalleNoticia(lstTareas[i]);
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

                    //console.log(JSON.stringify(lstPublicaciones));

                    for(var i=0;i<lstPublicaciones.length;i++)
                    {
                        var activo = "";
                        if (inicio == 1 && i == 0)
                        {
                            activo = "active";
                            cargarDetalleNoticia(lstPublicaciones[i]);
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
                    html+='<p>No hay datos</p>';
                }
                $("#lstTareas").append(html);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
                mensaje("error");
            }
        });
    };

    cargarDetalleNoticia=function(objPublicacion){
        $("#cargandoTarea").addClass("hidden");

        //$("#imgFoto1").attr("src",objPublicacion.url_foto1);
        //$("#imgFoto2").attr("src",objPublicacion.url_foto2);

        $("#tdId").html(objPublicacion.id);
        $("#tdCiudad").html(objPublicacion.ciudad);
        $("#tdTipoMedio").html(objPublicacion.tipo_medio);
        $("#tdMedio").html(objPublicacion.medio);
        $("#tdUbicacion").html(objPublicacion.ubicacion);
        $("#tdPagina").html(objPublicacion.pagina);
        $("#tdEmpresa").html(objPublicacion.empresa);
        $("#tdFecha").html(objPublicacion.fecha_publicacion);
    };

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
                        html+='<option value="'+arrCatalogos[i].value+'">'+arrCatalogos[i].label+'</option>';
                    $("#"+campo).html(html);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
            }
        });
    };

    llenarLista(0,1,15);
    llenarCatalogos('cmbColor',"Color",0);
    llenarCatalogos('cmbCuerpo',"Ubicacion",0);
    llenarCatalogos('cmbValoracion',"Valoracion",0);
});

