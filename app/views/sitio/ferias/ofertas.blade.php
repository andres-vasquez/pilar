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
    <li class="active">Ofertas</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
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
                    <br/>
                    <div class="col-md-5 form-group">
                        <label>Nombre</label>
                        <input type="text" id="txtNombre" name="nombre" class="form-control" required/>
                    </div>

                    <!-- Feicobol-->
                    @if (Session::get("nombre_sistema") === "feicobol")
                        <div class="col-md-5 col-lg-offset-1 form-group">
                            <label>Dirección</label>
                            <input type="text" id="txtDireccion" name="direccion" class="form-control" required/>
                        </div>
                    @else
                        <div class="col-md-5 form-group">
                            <label>Rubro</label>
                            <input type="hidden" name="rubro_id" id="hdnRubro"/>
                            <select id="cmbRubro" name="rubro" class="form-control" required></select>
                        </div>
                    @endif


                    @if (Session::get("nombre_sistema") === "feicobol")
                        <div class="col-md-5 form-group">
                            <label>Área</label>
                            <input class="form-control" name="pabellon" id="txtArea" required/>
                        </div>
                    @else
                        <div class="col-md-5 form-group">
                            <label>Área</label>
                            <select class="form-control" name="pabellon" id="cmbArea">
                            </select>
                        </div>
                    @endif

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
    <% HTML::script('public/js/bootstrap-table.js'); %>
    <% HTML::script('public/js/sitio/expositores.js'); %>
@stop
