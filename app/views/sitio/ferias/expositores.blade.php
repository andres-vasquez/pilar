@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/css/bootstrap-table.css'); %>
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
@stop

@section('titulo_plataforma')
    <% Session::get("nombre_sistema")%>
@stop

@section('barra_navegacion')
    <li class="active">Expositores</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
    <input type="hidden" id="nombre_sistema" value="<% Session::get("nombre_sistema")%>"/>
@stop

@section('titulo')
    <h1 class="page-header">Expositores</h1>
@stop

@section('contenido1')
    <div class="col-lg-12">
        <span class="clearfix">
            <div id="mensaje">
            </div>
            <!-- Feicobol-->
            @if (Session::get("nombre_sistema") === "feicobol")
                <button id="collapseImportar" class="btn btn-success pull-right" data-toggle="collapse"
                        data-target="#divImportarExpositores"><b>+ Cargar CSV</b></button>
            @else
                <button id="collapseNuevo" class="btn btn-success pull-right" data-toggle="collapse"
                        data-target="#divNuevoExpositor"><b>+ Agregar Expositor</b></button>
            @endif
        </span>

        <div id="divImportarExpositores" class="panel panel-collapse chat collapse">

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


        <div id="divNuevoExpositor" class="panel panel-collapse chat collapse">
            <% Form::open(array('url' => '/ws/expositores', 'id' => 'formNuevoExpositor', 'class' => 'form-horizontal')) %>
            <div class="panel-heading" id="accordion"><span class="glyphicon glyphicon-user"></span> Agregar
                expositor
            </div>
            <div class="panel-body">
                <ul id="ulFormulario">
                    <li class="left clearfix">
                        <div class="row">
                            <br/>
                            <div class="col-md-5 form-group">
                                <label>Nombre</label>
                                <input type="text" id="txtNombre" name="nombre" class="form-control" required/>
                            </div>

                            <div class="col-md-5 col-lg-offset-1 form-group">
                                <label>Dirección</label>
                                <input type="text" id="txtDireccion" name="direccion" class="form-control" required/>
                            </div>

                            <div class="col-md-5 form-group">
                                <label>Área</label>
                                <select class="form-control" name="pabellon" id="cmbArea">
                                </select>
                            </div>
                            <div class="col-md-5 col-lg-offset-1 form-group">
                                <label>Stand</label>
                                <input type="text" id="txtStand" name="stand" size="5" class="form-control" required/>
                            </div>

                            <div class="col-md-5 form-group">
                                <label>Página web</label>
                                <input type="text" id="txtWebsite" name="website" class="form-control" required/>
                            </div>
                            <div class="col-md-5 col-lg-offset-1 form-group">
                                <label>Fanpage (facebook)</label>
                                <input type="text" id="txtFacebook" name="fanpage" class="form-control" required/>
                            </div>

                            <div class="col-md-5 form-group">
                                <label>Teléfono</label>
                                <input type="text" id="txtTelefono" name="telefono" class="form-control" required/>
                            </div>
                            <div class="col-md-5 col-lg-offset-1 form-group">
                                <label>Fax</label>
                                <input type="text" id="txtFax" name="fax" class="form-control" required/>
                            </div>

                            <div class="col-md-5 form-group">
                                <label>Email</label>
                                <input type="text" id="txtEmail" name="email" class="form-control" required/>
                            </div>

                        </div>
                    </li>
                </ul>
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

@stop
@section('contenido2')
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Expositores existentes</div>
            <div class="panel-body">
                <table id="tblExpositores" data-toggle="table" data-url="<% '../api/v1/expositores/'.Session::get("credencial").'/sinformato'%>" data-show-refresh="true" data-search="true"
                       data-show-columns="true" data-select-item-name="toolbar1" data-pagination="true"
                       data-sort-name="name" data-sort-order="desc">
                    <thead>
                    <tr>
                        <th data-field="id" data-sortable="true">ID</th>
                        <th data-field="nombre" data-sortable="true">Nombre</th>
                        <th data-field="direccion" data-sortable="true">Dirección</th>
                        <th data-field="pabellon" data-sortable="true">Pabellón</th>
                        <th data-field="stand" data-sortable="true">Stand</th>
                        <!--<th data-field="website" data-sortable="true">Website</th>-->
                        <th data-field="fanpage" data-sortable="true">fanpage</th>
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
