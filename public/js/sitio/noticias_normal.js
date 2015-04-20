/**
 * Created by andresvasquez on 3/18/15.
 */
$(document).ready(function()
{
    var urlBase="http://placehold.it/150/30a5ff/fff";
    $("#divNoticias").html("");
    var noticiasPorPagina=5;
    var total=0;
    var indice=1;
    var lstNoticias=[];

    $('#htmlNoticia').wysihtml5({
        toolbar: {
            "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
            "emphasis": true, //Italics, bold, etc. Default true
            "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
            "html": false, //Button which allows you to edit the generated HTML. Default false
            "link": false, //Button to insert a link. Default true
            "image": false, //Button to insert an image. Default true,
            "color": false, //Button to change color of font
            "blockquote": true //Blockquote
        },
        locale: "es-ES"
    });
    $('#htmlNoticia_editar').wysihtml5({
        toolbar: {
            "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
            "emphasis": true, //Italics, bold, etc. Default true
            "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
            "html": false, //Button which allows you to edit the generated HTML. Default false
            "link": false, //Button to insert a link. Default true
            "image": false, //Button to insert an image. Default true,
            "color": false, //Button to change color of font
            "blockquote": true //Blockquote
        },
        locale: "es-ES"
    });

    $("#txtUrlImagen").change(function(){
        $("#imgNoticia").attr("src",$(this).val());
    });


    $("#formNuevaNoticia").submit(function(event)
    {
        event.preventDefault();
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
