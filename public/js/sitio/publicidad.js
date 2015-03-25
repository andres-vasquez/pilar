/**
 * Created by andresvasquez on 3/18/15.
 */
$(document).ready(function ()
{
    var idInsertada=0;

    $("form").submit(function (event) {
        var id = $(this).attr("id");
        if (id == "formNuevoAnuncio") {
            event.preventDefault();

            var datos = {
                "nombre": $("#txtNombre").val(),
                "descripcion": $("#txtDescripcion").val(),
                "link": $("#txtUrlAnuncio").val(),
                "prioridad": $("#cmbPrioridad").val()
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
                        idInsertada=result.resultado.id;

                        mensaje("ok");
                        $("#btnEnviar").removeAttr('disabled');
                        $('.id_publicidad').val(idInsertada);

                        //$("#collapsePublicidad").trigger("click");
                        window.location.href="#divImagenes";
                        //limpiarCampos();
                    }
                    else
                    {
                        mensaje(result.resultado.errores);
                        window.location.href="#";
                        $("#btnEnviar").removeAttr('disabled');
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest + " "+textStatus);
                    mensaje("error");
                }
            });

        }
        else {
            event.preventDefault();
            var url = "../ws/publicidad_imagenes";
            var formData = new FormData($(this)[0]);

            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (result)
                {
                    result=JSON.parse(result);
                    console.log(JSON.stringify(result));
                    if(parseInt(result.intCodigo)==1)
                    {
                        var ruta=result.resultado.carga.ruta;
                        var imagen=result.resultado.carga.id;
                        console.log("URI imagen:"+imagen);
                        $("#"+imagen).attr("src",ruta);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $("#divNoticias").html("");
                    console.log(XMLHttpRequest + " " + textStatus);
                }
            });
        }
    });


    limpiarCampos=function(){
        $("#txtNombre").val("");
        $("#txtDescripcion").val("");
        $("#txtUrlAnuncio").val("");
        $("#cmbPrioridad").val("3");
    };

    mensaje=function(tipo) {
        $("#mensaje").html('');
        $("#mensaje").addClass("alert");
        var html='';
        if(tipo=="ok")
        {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html+='<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html+='<strong>¡Anuncio agregado!</strong>';
        }
        else if(tipo=="editada")
        {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html+='<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html+='<strong>¡Anuncio editado!</strong>';
        }
        else if(tipo=="eliminada")
        {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html+='<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html+='<strong>¡Anuncio eliminado!</strong>';
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
});
