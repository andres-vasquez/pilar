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
                    console.log(JSON.stringify(result));
                    if(parseInt(result.intCodigo)==1)
                    {
                        idInsertada=result.resultado.id;
                        alert(idInsertada);

                        mensaje("ok");
                        $("#btnEnviar").removeAttr('disabled');
                        $("#collapsePublicidad").trigger("click");
                        window.location.href="#";
                        limpiarCampos();
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
                success: function (result) {
                    console.log(JSON.stringify(result));
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
            html+='<strong>¡Noticia agregada!</strong>';
        }
        else if(tipo=="editada")
        {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html+='<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html+='<strong>¡Noticia editada!</strong>';
        }
        else if(tipo=="eliminada")
        {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html+='<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html+='<strong>¡Noticia eliminada!</strong>';
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
