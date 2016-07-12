/**
 * Created by andresvasquez on 11/12/15.
 */

$(document).ready(function()
{
    $("#formImportar").submit(function (event) {
        event.preventDefault();
        var url = "../ws/motoclublapaz";
        var formData = new FormData($(this)[0]);

        $("#btnImportar").addClass("disabled");
        $("#prgImportar").removeClass("hidden");

        $("#divResultadoImportar").html("");
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function (result) {
                $("#prgImportar").addClass("hidden");
                $("#btnImportar").removeClass("disabled");
                result = JSON.parse(result);
                console.log(JSON.stringify(result));
                if (parseInt(result.intCodigo) == 1) {
                    var objResultado = result.resultado.objResultado;
                    $("#fileCsv").val("");
                    var html = '<br/><br/>';
                    html += '<p><b>Resultado de importación</b></p>';
                    html += '<p>Importados: <b>' + objResultado.insertados + '</b></p>';
                    html += '<p>Error:  <b></b>' + objResultado.error + '</p>';
                    $("#divResultadoImportar").html(html);

                    $table = $('#tblUsuarios').bootstrapTable('refresh', {
                        url: '../ws/motoclublapaz/usuarios_sinformato'
                    });
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $("#btnImportar").removeClass("disabled");
                $("#prgImportar").addClass("hidden");
                console.log(XMLHttpRequest + " " + textStatus);
            }
        });
    });

    encriptar=function(password){
        var hash = CryptoJS.MD5(password);
        return CryptoJS.MD5(hash.toString().substring(0,10)).toString();
    };

    mensaje = function (tipo) {
        $("#mensaje").html('');
        $("#mensaje").addClass("alert");
        var html = '';
        if (tipo == "ok") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Usuario creado!</strong>';
        }
        else if (tipo == "editada") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Usuario editado!</strong>';
        }
        else if (tipo == "eliminada") {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Usuario eliminado!</strong>';
        }
        else {
            $("#mensaje").removeClass("alert-success");
            $("#mensaje").addClass("alert-danger");
            html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<strong>¡Error!</strong> ' + tipo;
        }
        $("#mensaje").html(html);
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

                    var objTags = result.resultado.drclippingtags;
                    for(var i=0;i<objTags.length;i++)
                        html+='<option value="'+objTags[i].id+'">'+objTags[i].nombre+'</option>';


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
});

function operateFormatter(value, row, index) {
    return [
        '<a class="edit ml10" href="javascript:void(0)" title="Mostrar">',
        '<i class="glyphicon glyphicon-search"></i>',
        '</a> ',
        '<a class="remove ml10" href="javascript:void(0)" title="Eliminar">',
        '<i class="glyphicon glyphicon-remove"></i>',
        '</a>'
    ].join('');
}

window.operateEvents = {
    'click .edit': function (e, value, row, index) {
        var id = row.id;

        $("#showModal").modal("show");

        if(row.foto_piloto!=""){
            $("#imgFotoPiloto").attr("src",row.foto_piloto);
            $("#hrefFotoPiloto").attr("href",row.foto_piloto);
        }
        else{
            $("#imgFotoPiloto").attr("src","");
            $("#hrefFotoPiloto").attr("href","");
        }

        if(row.foto_moto!=""){
            $("#imgFotoMoto").attr("src",row.foto_moto);
            $("#hrefFotoMoto").attr("href",row.foto_moto);
        }else{
            $("#imgFotoMoto").attr("src","");
            $("#hrefFotoMoto").attr("href","");
        }

        if(row.foto_pago!=""){
            $("#imgFotoRecibo").attr("src",row.foto_pago);
            $("#hrefFotoRecibo").attr("href",row.foto_pago);
        }
        else {
            $("#imgFotoRecibo").attr("src","");
            $("#hrefFotoRecibo").attr("href","");
        }

        var htmlEmergencia="";
        var htmlRegistros="";

        htmlRegistros+='<li class="list-group-item">Nombre: '+row.nombre+'</li>';
        htmlRegistros+='<li class="list-group-item">Apellido: '+row.apellido+'</li>';
        htmlRegistros+='<li class="list-group-item">Email: '+row.email+'</li>';
        htmlRegistros+='<li class="list-group-item">Celular: '+row.celular+'</li>';
        htmlRegistros+='<li class="list-group-item">Teléfono fijo: '+row.telefono_fijo+'</li>';
        htmlRegistros+='<li class="list-group-item">CI: '+row.ci+'</li>';
        htmlRegistros+='<li class="list-group-item">Tipo sangre: '+row.tipo_sangre+'</li>';
        htmlRegistros+='<li class="list-group-item">Alergias: '+row.alergias+'</li>';
        htmlRegistros+='<li class="list-group-item">Ocupación: '+row.ocupacion+'</li>';
        htmlRegistros+='<li class="list-group-item">Nacionalidad: '+row.nacionalidad+'</li>';
        htmlRegistros+='<li class="list-group-item">Placa: '+row.placa+'</li>';
        htmlRegistros+='<li class="list-group-item">Marca: '+row.marca+'</li>';
        htmlRegistros+='<li class="list-group-item">Modelo: '+row.modelo+'</li>';
        htmlRegistros+='<li class="list-group-item">Año: '+row.anio+'</li>';
        htmlRegistros+='<li class="list-group-item">Cilindrada: '+row.cilindrada+'</li>';
        htmlRegistros+='<li class="list-group-item">Seguro: '+row.seguro+'</li>';


        htmlEmergencia+='<li class="list-group-item">Nombre: '+row.nombre_contacto+'</li>';
        htmlEmergencia+='<li class="list-group-item">Celular: '+row.celular_contacto+'</li>';
        htmlEmergencia+='<li class="list-group-item">Teléfono fijo: '+row.telefono_fijo_contacto+'</li>';

        $("#ulDatosEmergencia").html(htmlEmergencia);
        $("#ulDatosRegistros").html(htmlRegistros);

    },
    'click .remove': function (e, value, row, index) {
        var id = row.id;

        $('#eliminarModal').modal('show');
        $("#btnEliminarUsuario").click(function (event) {
            event.preventDefault();
            event.stopPropagation();

            var url="../ws/motoclublapaz/eliminar/"+id;
            $.ajax({
                type: "POST",
                url: url,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function(result)
                {
                    if(parseInt(result.intCodigo)==1) {
                        mensaje("eliminada");
                        $table = $('#tblUsuarios').bootstrapTable('refresh', {
                            url: '../ws/motoclublapaz/usuarios_sinformato'
                        });
                    }
                    else
                    {
                        mensaje("Error al eliminar usuario");
                    }
                    $('#eliminarModal').modal('hide');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest + " "+textStatus);
                }
            });
        });
    }
};

