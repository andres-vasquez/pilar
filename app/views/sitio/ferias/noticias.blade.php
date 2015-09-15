@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.min.css'); %>
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
@stop

@section('titulo_plataforma')
    <% Session::get("nombre_sistema")%>
@stop

@section('barra_navegacion')
    <li class="active">Noticias</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
@stop

@section('titulo')
    <h1 class="page-header">Noticias</h1>
@stop

@section('contenido1')

@stop
@section('contenido2')
    <div class="col-lg-12">
        <span class="clearfix">

            <div id="mensaje">
            </div>
            <button id="collapseNoticia" class="btn btn-success pull-right" data-toggle="collapse"
                    data-target="#divNuevaNoticia"><b>+ Nueva
                    noticia</b></button>
        </span>

        <div id="divNuevaNoticia" class="panel panel-collapse chat collapse">
            <% Form::open(array('url' => '/ws/noticias', 'id' => 'formNuevaNoticia')) %>
            <div class="panel-heading" id="accordion"><span class="glyphicon glyphicon-th-list"></span> Nueva noticia
            </div>
            <div class="panel-body">
                <ul>
                    <li class="left clearfix">
						<span class="chat-img pull-left">
                            <img id="imgNoticia" src="http://placehold.it/150/30a5ff/fff" alt="Foto noticia"
                                 width="180px" height="180px" class="img-responsive"/>
						</span>

                        <div class="chat-body clearfix row">
                            <div class="col-md-9">

                                <div class="form-group">
                                    <label>Titular</label>
                                    <input id="txtTitular" class="form-control" required/>
                                </div>

                                <div class="form-group">
                                    <label>URL de la imagen</label>
                                    <input id="txtUrlImagen" class="form-control" required/>
                                </div>

                                <div class="form-group">
                                    <label>Descripción corta</label>
                                    <textarea id="txtDescripcion" class="form-control" required></textarea>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <div class="row container">
                    <div class="col-md-9">
                        <div class="form-group">
                            <textarea id="htmlNoticia" class="form-control" required></textarea>
                        </div>
                    </div>
                </div>

            </div>
            <div class="panel-footer">
                <div class="input-group">
                    <button type="submit" class="btn btn-success btn-md" id="btnEnviar">Guardar</button>
                    &nbsp;&nbsp;
                    <button type="reset" class="btn btn-danger btn-md">Cancelar</button>
                </div>
            </div>
            <% Form::close() %>
        </div>


        <div class="panel panel-default chat">
            <div class="panel-body">
                <ul id="divNoticias">
                </ul>
            </div>
            <div class="panel-footer">
                <div class="input-group">
                    <ul id="ulPaginacion" class="pagination text-center">
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal eliminar -->
    <div class="modal fade" id="eliminarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Eliminar noticia</h4>
                </div>
                <div class="modal-body">
                    Está seguro de eliminar la noticia seleccionada?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnEliminarNoticia">Si,
                        eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal foto -->
    <div class="modal fade" id="fotoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Imagen Adjunta</h4>
                </div>
                <div class="modal-body">
                    <img id="imgFotoModal" class="img-responsive" />
                </div>
            </div>
        </div>
    </div>

    <!-- Modal editar -->
    <div class="modal modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Editar noticia</h4>
                </div>
                <div class="modal-body chat">

                    <ul>
                        <li class="left clearfix">
						<span class="chat-img pull-left">
                            <img id="imgNoticia_editar" src="http://placehold.it/150/30a5ff/fff" alt="Foto noticia"
                                 width="180px" height="180px" class="img-responsive"/>
						</span>

                            <div class="chat-body clearfix row">
                                <div class="col-md-9">

                                    <div class="form-group">
                                        <label>Titular</label>
                                        <input id="txtTitular_editar" class="form-control" required/>
                                    </div>

                                    <div class="form-group">
                                        <label>URL de la imagen</label>
                                        <input id="txtUrlImagen_editar" class="form-control" required/>
                                    </div>

                                    <div class="form-group">
                                        <label>Descripción corta</label>
                                        <textarea id="txtDescripcion_editar" class="form-control" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <div class="form-group">
                        <textarea id="htmlNoticia_editar" class="form-control" required></textarea>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnEditarNoticia">Guardar
                    cambios
                </button>
            </div>
        </div>
    </div>
    </div>


    </div><!--/.col-->
@stop

@section('pie')
    <% HTML::script('public/lib/bower_components/wysihtml5x/dist/wysihtml5x-toolbar.js'); %>
    <% HTML::script('public/lib/bower_components/handlebars/handlebars.runtime.min.js'); %>
    <% HTML::script('public/lib/bower_components/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.min.js'); %>
    <% HTML::script('public/lib/bower_components/bootstrap3-wysihtml5-bower/dist/locales/bootstrap-wysihtml5.es-ES.js'); %>
    <% HTML::script('public/js/sitio/noticias.js'); %>
@stop