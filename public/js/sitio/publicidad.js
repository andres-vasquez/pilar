/**
 * Created by andresvasquez on 3/18/15.
 */
$(document).ready(function ()
{
    var idInsertada=0;

    $(".imagen").change(function(e){

        var tamano=this.files[0].size;

        if(parseInt(tamano)>2000000) //2 MB
        {
            alert("Tamaño máximo permitido de 2MB reduzca su imagen por favor");
            $(this).val("");
        }
        else
        {
            var fileExtension = ['jpeg', 'jpg','png'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                alert("Formato incorrecto. Las imágenes deben estar en formato jpeg o png.");
                $(this).val("");
            }
        }
    });


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
                        bloquearCampos();
                        $("#divImagenes").removeClass("hidden");
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
        else
        {
            event.preventDefault();
            var _validFileExtensions = [".jpg", ".jpeg", ".png"];


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


    bloquearCampos=function(){
        $("#txtNombre").addClass("disabled");
        $("#txtDescripcion").addClass("disabled");
        $("#txtUrlAnuncio").addClass("disabled");
        $("#cmbPrioridad").addClass("disabled");
        $("#btnEnviar").addClass("disabled");
        $("#btnCancelar").addClass("disabled");
    };

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

function operateFormatter(value, row, index) {
    return [
        '<a class="edit ml10" href="javascript:void(0)" title="Editar">',
        '<i class="glyphicon glyphicon-pencil"></i>',
        '</a> ',
        '<a class="remove ml10" href="javascript:void(0)" title="Eliminar">',
        '<i class="glyphicon glyphicon-remove"></i>',
        '</a>'
    ].join('');
}

window.operateEvents = {
    'click .edit': function (e, value, row, index)
    {
        var id=row.id;
        alert("editar"+id);
    },
    'click .remove': function (e, value, row, index) {
        var id=row.id;
        alert("elim"+id);
    }
};
