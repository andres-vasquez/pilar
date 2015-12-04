@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/css/bootstrap-table.css'); %>
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
    <% HTML::style('public/lib/bower_components/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.min.css'); %>
@stop

@section('titulo_plataforma')
    TecnoBit
@stop

@section('barra_navegacion')
    <li class="active">Publicidad</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
    <input type="hidden" id="agrupador" value="tecnobit_portada"/>
@stop

@section('titulo')
    <h1 class="page-header">Portada</h1>
@stop

@section('contenido1')
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading"></div>
            <div class="panel-body">
                 <span class="clearfix">
                        <div id="mensaje"></div>
                    <button id="collapseNuevo" class="btn btn-success pull-right" data-toggle="collapse"
                            data-target="#divCargarRevista">+ Cargar Portada
                    </button>
                    </span>

                <div id="divCargarRevista" class="collapse">
                    <br/>
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="txtTitulo" class="col-sm-4 control-label">Título portada</label>

                            <div class="col-sm-6">
                                <input id="txtTitulo" class="form-control" required/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="txtNombre" class="col-sm-4 control-label">Adjuntar Imagen de Portada</label>

                            <div class="col-sm-6">
                                <form id="adjunto" enctype="multipart/form-data">
                                    <div class="form-group form-inline">
                                        <input type="file" id="inputAdjunto" name="adjunto" class="form-control imagen" required/>
                                        <button type="submit" class="btn btn-sm btn-info form-control">Cargar</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <input type="hidden" id="thumbnail" value=""/>
                        <input type="hidden" id="ruta" value=""/>
                        <input type="hidden" id="ruta_aws" value=""/>
                        <input type="hidden" id="sistema_id" name="sistema_id" value="<% Session::get("id_sistema")%>"/>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-md col-sm-offset-4" id="btnEnviar">
                                Guardar
                            </button>
                            &nbsp;&nbsp;
                            <button type="reset" class="btn btn-danger btn-md" id="btnCancelar">Cancelar</button>
                        </div>

                        <br/>
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
                        <h4 class="modal-title" id="myModalLabel">Eliminar imagen de portada</h4>
                    </div>
                    <div class="modal-body">
                        Está seguro de eliminar la imagen de portada seleccionada?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnEliminarImagen">Si,
                            eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- col-->
@stop
@section('contenido2')
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Imagen de Portada</div>
            <div class="panel-body">

            <table id="tblPortada" data-toggle="table" data-url="<% '../ws/tecnobit/adjuntos/'.Session::get("credencial").'/tecnobit_portada'%>"
                   data-show-refresh="true" data-search="true"
                   data-show-columns="true" data-select-item-name="toolbar1" data-pagination="true"
                   data-sort-name="name" data-sort-order="desc">
                <thead>
                <tr>
                    <th data-field="id" data-sortable="true">ID</th>
                    <th data-field="nombre" data-sortable="true">Titulo</th>
                    <th data-field="ruta_aws" data-sortable="false">Link</th>
                    <th data-field="fecha_creacion" data-halign="center" data-sortable="true">Fecha publicación</th>
                    <th data-field="operate" data-halign="center" data-formatter="operateFormatter"
                        data-events="operateEvents">Acciones
                    </th>
                </tr>
                </thead>
            </table>
                <br/>
            </div>
        </div>
    </div>
@stop
@section('pie')
    <% HTML::script('public/js/bootstrap-table.js'); %>
    <% HTML::script('public/lib/bower_components/wysihtml5x/dist/wysihtml5x-toolbar.js'); %>
    <% HTML::script('public/lib/bower_components/handlebars/handlebars.runtime.min.js'); %>
    <% HTML::script('public/lib/bower_components/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.min.js'); %>
    <% HTML::script('public/lib/bower_components/bootstrap3-wysihtml5-bower/dist/locales/bootstrap-wysihtml5.es-ES.js'); %>
    <% HTML::script('public/js/tecnobit/adjuntar.js'); %>
@stop
