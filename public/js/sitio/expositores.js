/**
 * Created by andresvasquez on 3/18/15.
 */
$(document).ready(function () {
    var idInsertada = 0;


    $("form").submit(function (event) {
        var id = $(this).attr("id");
        event.preventDefault();

        var url = "../ws/expositores/importar";
        var formData = new FormData($(this)[0]);

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function (result) {
                result = JSON.parse(result);
                console.log(JSON.stringify(result));
                if (parseInt(result.intCodigo) == 1) {
                    mensaje("ok");
                    $("#fileCsv").val("");
                    $("#collapseImportar").trigger("click");

                    $table = $('#tblExpositores').bootstrapTable('refresh', {
                        url: '../api/v1/expositores/'+$("#credencial").val()+'/sinformato'
                    });
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $("#divNoticias").html("");
                console.log(XMLHttpRequest + " " + textStatus);
            }
        });
});


mensaje = function (tipo) {
    $("#mensaje").html('');
    $("#mensaje").addClass("alert");
    var html = '';
    if (tipo == "ok") {
        $("#mensaje").removeClass("alert-danger");
        $("#mensaje").addClass("alert-success");
        html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        html += '<strong>¡Expositores importados!</strong>';
    }
    else if (tipo == "editada") {
        $("#mensaje").removeClass("alert-danger");
        $("#mensaje").addClass("alert-success");
        html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        html += '<strong>¡Expositor editado!</strong>';
    }
    else if (tipo == "eliminada") {
        $("#mensaje").removeClass("alert-danger");
        $("#mensaje").addClass("alert-success");
        html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        html += '<strong>¡Expositor eliminado!</strong>';
    }
    else {
        $("#mensaje").removeClass("alert-success");
        $("#mensaje").addClass("alert-danger");
        html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        html += '<strong>¡Error!</strong> ' + tipo;
    }
    $("#mensaje").html(html);
};
})
;
