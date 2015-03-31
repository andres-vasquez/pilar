@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/css/bootstrap-table.css'); %>
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
@stop

@section('barra_navegacion')
    <li class="active">Expositores</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
@stop

@section('titulo')
    <h1 class="page-header">Expositores</h1>
@stop

@section('contenido1')
    <div class="col-lg-12">
        <span class="clearfix">
            <div id="mensaje">
            </div>
            <button id="collapseImportar" class="btn btn-success pull-right" data-toggle="collapse"
                    data-target="#divImportarExpositores"><b>+ Cargar CSV</b></button>
        </span>

        <div id="divImportarExpositores" class="panel panel-collapse chat collapse in">

            <div class="panel-heading" id="accordion"><span class="glyphicon glyphicon-user"></span> Importar
                expositores
            </div>
            <div class="panel-body">
                <ul id="ulFormulario">
                    <li class="left clearfix">
                        <div class="col-md-9">
                            <% Form::open(array('url' => '/ws/expositores', 'id' => 'formImportarExpositores', 'class' => 'form-horizontal')) %>
                            <br/>

                            <div class="form-group">
                                <label for="txtDescripcion" class="col-sm-4 control-label">Archivo adjunto en formato
                                    CSV</label>

                                <div class="col-sm-8">
                                    <input type="file" id="fileCsv" name="filecsv" class="form-control" required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-md col-sm-offset-4" id="btnEnviar">
                                    Guardar
                                </button>
                                &nbsp;&nbsp;
                                <button type="reset" class="btn btn-danger btn-md" id="btnCancelar">Cancelar</button>
                            </div>
                            <% Form::close() %>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

@stop
@section('contenido2')
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Expositores existentes</div>
            <div class="panel-body">
                <table data-toggle="table" data-url="" data-show-refresh="true" data-search="true"
                       data-show-columns="true" data-select-item-name="toolbar1" data-pagination="true"
                       data-sort-name="name" data-sort-order="desc">
                    <thead>
                    <tr>
                        <th data-field="id" data-sortable="true">ID</th>
                        <th data-field="name" data-sortable="true">Nombre</th>
                        <th data-field="price" data-sortable="true">Dirección</th>
                        <th data-field="price" data-sortable="true">Pabellón</th>
                        <th data-field="price" data-sortable="true">Website</th>
                        <th data-field="price" data-sortable="true">facebook</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop
@section('pie')
    <% HTML::script('public/js/bootstrap-table.js'); %>
    <% HTML::script('public/js/sitio/expositores.js'); %>
@stop
