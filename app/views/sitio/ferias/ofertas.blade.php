@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/css/bootstrap-table.css'); %>
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
    <% HTML::style('public/lib/bower_components/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.min.css'); %>
@stop

@section('titulo_plataforma')
    <% Session::get("nombre_sistema")%>
@stop

@section('barra_navegacion')
    <li class="active">Ofertas</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
    <input type="hidden" id="nombre_sistema" value="<% Session::get("nombre_sistema")%>"/>
@stop

@@section('titulo')
    <h1 class="page-header">Ofertas de la feria</h1>
@stop

@section('contenido1')
    <div class="col-lg-12">
        <span class="clearfix">
            <div id="mensaje">
            </div>
            <button id="collapseNuevo" class="btn btn-success pull-right" data-toggle="collapse"
                    data-target="#divNuevaOferta"><b>+ Agregar Oferta</b></button>
            </span>

        <div id="divNuevaOferta" class="panel panel-collapse chat collapse">
            <% Form::open(array('url' => '/ws/ofertas', 'id' => 'formNuevaOferta', 'class' => '')) %>
            <div class="panel-heading" id="accordion"><span class="glyphicon glyphicon-shopping-cart"></span> Agregar
                ofertas
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-5 form-group">
                        <label>Rubro</label>
                        <input type="hidden" name="rubro" id="hdnRubro"/>
                        <select id="cmbRubro" name="rubro_id" class="form-control" required></select>
                    </div>
                    <div class="col-md-5 form-group">
                        <label>Expositor</label>
                        <input type="hidden" name="expositor" id="hdnExpositor"/>
                        <select id="cmbExpositor" name="expositor_id" class="form-control"></select>
                    </div>

                    <div class="col-md-5 form-group">
                        <label class="checkbox-inline no_indent">
                            <input type="checkbox" id="chkNoExpositor"> No es expositor
                        </label>
                        <input type="text" id="txtNombreEmpresa" name="empresa" class="form-control disabled" disabled/>
                    </div>

                    <div class="col-md-10">
                        <label>Descripción ofertas</label>
                        <textarea id="htmlOferta" name="html" class="form-control" style="height:400px" required></textarea>
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
    </div>

    <!-- Modal de imagen -->
    <div class="modal fade" id="imagenModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Adjuntar imagen</h4>
                </div>
                <div class="modal-body">
                    <form id="formImagen" enctype="multipart/form-data">
                        <div class="form-group form-inline">
                            <input type="hidden" name="ruta" id="hdnRutaImagen" class="form-control"/>
                            <input type="file" name="imagen" id="imgImagen" class="form-control imagen" required/>
                            <button type="submit" class="btn btn-sm btn-info form-control">Cargar
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
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
                    <h4 class="modal-title" id="myModalLabel">Eliminar oferta</h4>
                </div>
                <div class="modal-body">
                    Está seguro de eliminar la oferta seleccionada?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnEliminarOferta">Si,
                        eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="http://localhost:8888/pilar/ws/ofertas" accept-charset="UTF-8" id="formEditarOferta" class=""><input name="_token" type="hidden" value="c2fJc8w7hizABDFbxBHhXmYYhEVFy3yoljBMLybn">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Editar oferta</h4>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-5 form-group">
                                <label>Rubro</label>
                                <input type="hidden" name="rubro" id="hdnRubro_editar"/>
                                <select id="cmbRubro_editar" name="rubro_id" class="form-control" required></select>
                            </div>
                            <div class="col-md-5 form-group">
                                <label>Expositor</label>
                                <input type="hidden" name="expositor" id="hdnExpositor_editar"/>
                                <select id="cmbExpositor_editar" name="expositor_id" class="form-control"></select>
                            </div>

                            <div class="col-md-5 form-group">
                                <label class="checkbox-inline no_indent">
                                    <input type="checkbox" id="chkNoExpositor_editar"> No es expositor
                                </label>
                                <input type="text" id="txtNombreEmpresa_editar" name="empresa" class="form-control disabled" disabled/>
                            </div>

                            <div class="col-md-10">
                                <label>Descripción ofertas</label>
                                <textarea id="htmlOferta_editar" name="html" class="form-control" style="height:400px" required></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" data-dismiss="modal" id="btnGuardarEditar">Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@stop
@section('contenido2')
    <!-- Contenido aca-->
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Ofertas existentes</div>
            <div class="panel-body">
                <table id="tblOfertas" data-toggle="table" data-url="<% '../api/v1/ofertas/'.Session::get("credencial").'/sinformato'%>" data-show-refresh="true" data-search="true"
                       data-show-columns="true" data-select-item-name="toolbar1" data-pagination="true"
                       data-sort-name="name" data-sort-order="desc">
                    <thead>
                    <tr>
                        <th data-field="id" data-sortable="true">ID</th>
                        <th data-field="empresa" data-sortable="true">Empresa</th>
                        <th data-field="rubro" data-sortable="true">Rubro</th>
                        <th data-field="fecha" data-sortable="true">Fecha de publicación</th>
                        <th data-field="link" data-sortable="true">Link</th>
                        <th data-field="operate" data-halign="center" data-formatter="operateFormatter"
                            data-events="operateEvents">Acciones
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop
@section('pie')
    <% HTML::script('public/lib/bower_components/wysihtml5x/dist/wysihtml5x-toolbar.js'); %>
    <% HTML::script('public/lib/bower_components/handlebars/handlebars.runtime.min.js'); %>
    <% HTML::script('public/lib/bower_components/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.min.js'); %>
    <% HTML::script('public/lib/bower_components/bootstrap3-wysihtml5-bower/dist/locales/bootstrap-wysihtml5.es-ES.js'); %>
    <% HTML::script('public/js/bootstrap-table.js'); %>
    <% HTML::script('public/js/sitio/ofertas.js'); %>
@stop
