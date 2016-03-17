/**
 * Created by andresvasquez on 3/18/15.
 */

var editando=false;
var idEditando=0;

var alturaHtml=500;

$(document).ready(function()
{
    var urlBase="http://placehold.it/150/30a5ff/fff";
    $("#divNoticias").html("");
    var noticiasPorPagina=5;
    var total=0;
    var indice=1;
    var lstNoticias=[];

    $('#htmlNoticia,#htmlNoticia_editar').wysihtml5({
        toolbar: {
            "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
            "emphasis": true, //Italics, bold, etc. Default true
            "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
            "html": false, //Button which allows you to edit the generated HTML. Default false
            "link": false, //Button to insert a link. Default true
            "image": true, //Button to insert an image. Default true,
            "video":true,
            "color": false, //Button to change color of font
            "blockquote": true //Blockquote
        },
        customTemplates: {
            image: function(locale) {
                return '<li>' +
                    "<div class='btn-group'>" +
                    "<a class='btn btn-default' data-toggle='modal' data-target='#imagenModal'  title='Imagen'><span class='glyphicon glyphicon-picture'></span></a>" +
                    "</div>" +
                    "</li>";
            },
            video: function(locale) {
                return '<li>' +
                    "<div class='btn-group'>" +
                    "<a class='btn btn-default' data-toggle='modal' data-target='#videoModal'  title='Video'><span class='glyphicon glyphicon-film'></span></a>" +
                    "</div>" +
                    "</li>";
            }
        },
        locale: "es-ES"
    });

    $(".wysihtml5-sandbox").click(function(){
        alturaHtml=500;
        $(".wysihtml5-sandbox").height(alturaHtml);
    });

    $("#collapseNoticia").click(function(){
        editando=false;
        alturaHtml=500;
        $(".wysihtml5-sandbox").height(alturaHtml);
    });

    $("#txtUrlImagen").change(function(){
        $("#imgNoticia").attr("src",$(this).val());
    });

    $("#btnPreCargarVideo").click(function(){
        var arrUrl=$("#txtUrlVido").val().split("/");
        var urlFormato="http://www.youtube.com/embed/"+arrUrl[arrUrl.length-1].replace("watch?v=","");
        $("#ytplayer").attr("src",urlFormato);
        $("#btnCargarVideo").attr("disabled","disabled");
    });

    $('#ytplayer').on('load', function(){
        $("#btnCargarVideo").removeAttr("disabled").removeClass("disabled");
    });

    $("form").submit(function (event) {
        var id = $(this).attr("id");
        event.preventDefault();

        var btnSubmit = $(this).find(':submit');
        var htmlCargando='<i class="fa fa-spinner fa-spin"></i> Cargando';
        var htmlCargado='<i class="fa fa-check"></i> Cargado';
        var htmlError='<i class="fa fa-close"></i> Error';

        var _validFileExtensions = [".jpg", ".jpeg", ".png"];

        if(id=="formNuevaNoticia")
        {
            var datos= {
                "titular":$("#txtTitular").val(),
                "url_imagen":$("#txtUrlImagen").val(),
                "descripcion":$("#txtDescripcion").val(),
                "html":$("#htmlNoticia").val()
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
                        mensaje("ok");
                        $("#btnEnviar").removeAttr('disabled');
                        $("#collapseNoticia").trigger("click");
                        window.location.href="#";
                        llenarNoticias(1,noticiasPorPagina);
                        limpiarCampos();
                    }
                    else
                    {
                        mensaje(result.resultado.errores);
                        window.location.href="#";
                        $("#btnEnviar").removeAttr('disabled');
                    }
                    $("#imgNoticia").attr("src",urlBase);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest + " "+textStatus);
                    mensaje("error");
                    $("#imgNoticia").attr("src",urlBase);
                }
            });
        }
        else if(id=="formImagen")
        {
            var formData = new FormData($(this)[0]);

            btnSubmit.html(htmlCargando);
            btnSubmit.attr('disabled', 'disabled');

            var url = "../ws/funciones/subirArchivoAWS";
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (result)
                {
                    btnSubmit.removeAttr('disabled');
                    result = JSON.parse(result);
                    if (parseInt(result.intCodigo) == 1)
                    {

                        var data=result.resultado.data;
                        var ruta=data.ruta_aws;

                        if(!editando)
                        {
                            $("#hdnRutaImagen").val(ruta);
                            var htmlAnterior=$('#htmlNoticia').val();
                            var wysihtml5Editor = $('#htmlNoticia').data("wysihtml5").editor;
                            wysihtml5Editor.setValue(htmlAnterior+'<br/><img width="100%" src="'+ruta+'"/>');
                        }
                        else
                        {
                            $("#hdnRutaImagen_editar").val(ruta);
                            var htmlAnterior=$('#htmlNoticia_editar').val();
                            var wysihtml5Editor = $('#htmlNoticia_editar').data("wysihtml5").editor;
                            wysihtml5Editor.setValue(htmlAnterior+'<br/><img width="100%" src="'+ruta+'"/>');
                        }

                        $("#imgImagen").val("");
                        $("#imagenModal").modal("hide");
                        btnSubmit.html("Cargar");
                    }
                    else
                    {
                        alert("Error al subir la imagen");
                        btnSubmit.html(htmlError);
                    }
                    //console.log(JSON.stringify(result));
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    btnSubmit.removeAttr('disabled');
                    console.log(XMLHttpRequest + " " + textStatus);
                    btnSubmit.html(htmlError);
                }
            });
        }
        else if(id=="formVideo")
        {
            var urlVideo=$("#ytplayer").attr("src");

            btnSubmit.html(htmlCargando);
            btnSubmit.attr('disabled', 'disabled');
            if(!editando)
            {
                var htmlAnterior=$('#htmlNoticia').val();
                var wysihtml5Editor = $('#htmlNoticia').data("wysihtml5").editor;

                var iframe='<iframe id="ytplayer" type="text/html" width="100%" height="320"';
                iframe+='src="'+urlVideo+'"'
                iframe+='frameborder="0"></iframe>';
                wysihtml5Editor.setValue(htmlAnterior+'<br/>'+iframe+'<br/><br/>');
            }
            else
            {
                var htmlAnterior=$('#htmlNoticia_editar').val();
                var wysihtml5Editor = $('#htmlNoticia_editar').data("wysihtml5").editor;

                var iframe='<iframe id="ytplayer" type="text/html" width="100%" height="320"';
                iframe+='src="'+urlVideo+'"'
                iframe+='frameborder="0"></iframe>';
                wysihtml5Editor.setValue(htmlAnterior+'<br/>'+iframe+'<br/><br/>');
            }

            $("#imgImagen").val("");
            $("#imagenModal").modal("hide");

            btnSubmit.removeAttr('disabled');
            btnSubmit.html("Incluir video");
        }
    });

    limpiarCampos=function(){
        $("#txtTitular").val("");
        $("#txtUrlImagen").val("");
        $("#txtDescripcion").val("");
        $("#htmlNoticia").val("");
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

    //Funcion para desplegar noticias
    llenarNoticias = function(inicio,fin){
        $("#divNoticias").html("");
        $("#divNoticias").append('<p class="text-center"><i class="fa fa-spinner fa-spin fa-lg"></i><span>&nbsp;&nbsp; Cargando</span></p>');

        inicio=parseInt(inicio);
        fin=parseInt(fin);
        var credencial=$("#credencial").val();

        var url="../api/v1/noticias/"+credencial+"/"+inicio+"/"+fin;
        $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(result)
            {
                $("#divNoticias").html("");
                if(parseInt(result.intCodigo)==1)
                {
                    total=parseInt(result.resultado.total);
                    lstNoticias=result.resultado.noticias;

                    for(var i=0;i<lstNoticias.length;i++)
                        $("#divNoticias").append(noticiaIzquierda(lstNoticias[i]));

                    $("#ulPaginacion").html(noticiaPaginador());
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $("#divNoticias").html("");
                console.log(XMLHttpRequest + " "+textStatus);
                mensaje("error");
            }
        });
    };


    noticiaIzquierda=function(objNoticia){
        var html='<li class="left clearfix">';
        html+='<span class="chat-img pull-left">';
        html+='<img src="'+objNoticia.url_imagen+'" alt="Imagen noticia" width="120" max-height="120" data-toggle="modal" class="foto" style="cursor:pointer" data-target="#fotoModal"/></span>';
        html+='<div class="chat-body clearfix">';
        html+='<div class="header">';
        html+='<strong class="primary-font">'+objNoticia.titular+'</strong>';
        html+='<small class="text-muted">'+objNoticia.fecha_creacion.substring(0,11)+'</small>';
        html+='</div>';
        html+='<p>'+objNoticia.descripcion+'</p><br/><br/>';
        html+='<p class="pull-right">';
        html+='<button class="btn btn-info btn-sm accion" id="editar_'+objNoticia.id+'" data-toggle="modal" data-target="#editarModal">Editar</button>&nbsp;';
        html+='<button class="btn btn-danger btn-sm accion" id="eliminar_'+objNoticia.id+'" data-toggle="modal" data-target="#eliminarModal">Eliminar</button>';
        html+='</p></div>';
        html+='</li>';

        return html;
    };

    noticiaPaginador=function(){
        var html='';

        if(indice==1)
            html+='<li class="disabled"><a href="#" class="anterior">&laquo;</a></li>';
        else
            html+='<li><a href="#" class="anterior">&laquo;</a></li>';


        var totalEntero=0;
        if(total%noticiasPorPagina==0)
            totalEntero=parseInt(total/noticiasPorPagina);
        else
            totalEntero=parseInt(total/noticiasPorPagina)+1;

        for(var i=1;i<=totalEntero;i++)
        {
            if(i==indice)
                html+='<li class="active"><a href="#" class="pagina">'+i+'</a></li>';
            else
                html+='<li><a href="#" class="pagina">'+i+'</a></li>';
        }

        if(indice*noticiasPorPagina>=total)
            html+='<li class="disabled"><a href="" class="siguiente" aria-disabled="true">&laquo;</a></li>';
        else
            html+='<li><a href="#" class="siguiente">&raquo;</a></li>';

        return html;
    };

    calcularInicioFin=function()
    {
        var fin=indice*noticiasPorPagina;
        var inicio=fin-noticiasPorPagina+1;
        llenarNoticias(inicio,fin);
    };

    $('body').on('click', '.anterior', function(event) {
        if(indice!=1) {
            indice--;
            calcularInicioFin();
        }
        else
            event.preventDefault();
    });

    $('body').on('click', '.siguiente', function(event) {
        if(indice*noticiasPorPagina<=total) {
            indice++;
            calcularInicioFin();
        }
        else
            event.preventDefault();
    });

    $('body').on('click', '.pagina', function() {
        indice = parseInt($(this).html());
        calcularInicioFin();
    });

    $('body').on('click', '.foto', function() {
        $("#imgFotoModal").removeAttr("src");
        $("#imgFotoModal").attr("src",$(this).attr("src"));
    });

    $('body').on('click', '.accion', function() {
        var contenido=$(this).attr("id").split("_");
        var accion=contenido[0];
        var id=contenido[1];

        if(accion=="eliminar")
        {
            editando=false;
            $("#btnEliminarNoticia").click(function ()
            {
                $("#divNoticias").html("");
                $("#divNoticias").append('<p class="text-center"><i class="fa fa-spinner fa-spin fa-lg"></i><span>&nbsp;&nbsp; Cargando</span></p>');
                var url="../ws/noticia/eliminar/"+id;
                $.ajax({
                    type: "POST",
                    url: url,
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function(result)
                    {
                        if(parseInt(result.intCodigo)==1)
                        {
                            mensaje("eliminada");
                            window.location.href="#";
                            llenarNoticias(1,noticiasPorPagina);
                        }
                        else
                        {
                            mensaje(result.resultado.errores);
                            window.location.href="#";
                            llenarNoticias(1,noticiasPorPagina);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#divNoticias").html("");
                        console.log(XMLHttpRequest + " "+textStatus);
                        mensaje("error");
                        llenarNoticias(1,noticiasPorPagina);
                    }
                });
            });
        }
        else if(accion=="editar")
        {
            editando=true;
            var wysihtml5Editor = $('#htmlNoticia_editar').data("wysihtml5").editor;
            wysihtml5Editor.setValue("");
            var objNoticia=obtenerNoticiaId(id);
            var credencial=$("#credencial").val();
            var url="../api/v1/noticias/"+credencial+"/"+id+"/variable";
            $.ajax({
                type: "GET",
                url: url,
                success: function(result)
                {
                    wysihtml5Editor.setValue(result);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#divNoticias").html("");
                    console.log(XMLHttpRequest + " "+textStatus);
                }
            });

            $("#imgNoticia_editar").attr("src",objNoticia.url_imagen);
            $("#txtTitular_editar").val(objNoticia.titular);
            $("#txtUrlImagen_editar").val(objNoticia.url_imagen);
            $("#txtDescripcion_editar").val(objNoticia.descripcion);

            $("#btnEditarNoticia").click(function(){
                var datos={
                    "titular":$("#txtTitular_editar").val(),
                    "url_imagen":$("#txtUrlImagen_editar").val(),
                    "descripcion":$("#txtDescripcion_editar").val(),
                    "html":$("#htmlNoticia_editar").val()
                };
                $("#divNoticias").html("");
                $("#divNoticias").append('<p class="text-center"><i class="fa fa-spinner fa-spin fa-lg"></i><span>&nbsp;&nbsp; Cargando</span></p>');

                $("#btnEditarNoticia").unbind("click");
                var url="../ws/noticia/"+id;
                $.ajax({
                    type: "POST",
                    url: url,
                    data:  JSON.stringify(datos),
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function(result)
                    {
                        if(parseInt(result.intCodigo)==1)
                        {
                            mensaje("editada");
                            window.location.href="#";
                            calcularInicioFin();
                        }
                        else
                        {
                            mensaje(result.resultado.errores);
                            window.location.href="#";
                            calcularInicioFin();
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#divNoticias").html("");
                        console.log(XMLHttpRequest + " "+textStatus);
                        mensaje("error");
                        calcularInicioFin();
                    }
                });
            });
        }
    });

    obtenerNoticiaId=function(id){
        for(var i=0;i<lstNoticias.length;i++)
            if(parseInt(lstNoticias[i].id)==parseInt(id))
                return lstNoticias[i];
    };

    //Funcion al inicializar
    llenarNoticias(1,noticiasPorPagina);
});
