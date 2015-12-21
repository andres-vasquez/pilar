/**
 * Created by andresvasquez on 11/12/15.
 */
var lstTareas=[];
var objPublicacionGlobal=null;

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


    $(".edit").click(function(event){

    });

    $(".foto").click(function(event){
        event.preventDefault();
        event.stopPropagation();

        var src=$(this).attr("src");
        $("#imgPreview").attr("src",src);;
        $("#zoomModal").modal("show");
    });

    $('#zoomModal').on('shown.bs.modal', function() {
        $('#imgPreview').elevateZoom({
            zoomType: "inner",
            cursor: "crosshair"
        });
    });

    jQuery('#zoomModal').on('hidden.bs.modal', function (e) {

        jQuery.removeData(jQuery('#imgPreview'), 'elevateZoom');//remove zoom instance from image
        jQuery('.zoomContainer').remove();// remove zoom container from DOM
    });

    cargarDetalleNoticia=function(objPublicacion){
        $("#cargandoTarea").addClass("hidden");

        objPublicacionGlobal=objPublicacion;

        $("#imgFoto1").attr("src",objPublicacion.url_foto1);
        $("#imgFoto2").attr("src",objPublicacion.url_foto2);

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

            llenarCatalogos('cmbMedio_popup',agrupador,idCiudad);
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


    llenarLista(0,1,15);
    llenarCatalogos('cmbColor',"Color",0);
    llenarCatalogos('cmbCuerpo',"Ubicacion",0);
    llenarCatalogos('cmbValoracion',"Valoracion",0);

    llenarCatalogos('cmbCiudad_popup',"Departamentos",0);
    llenarCatalogos('cmbTipoMedio_popup',"Tipo de medio",0);
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


