@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
@stop

@section('titulo_plataforma')
    TecnoBit
@stop

@section('barra_navegacion')
    <li class="active">Dashboard</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
@stop

@section('titulo')
    <h1 class="page-header">Notificaciones</h1>
@stop

@section('contenido1')
    <div class="col-xs-12 col-md-9 col-lg-6">
        <div class="panel">
            <div class="panel-heading panel-blue">
                Notificaciones
                <div class="pull-right">
                    <span class="glyphicon glyphicon-envelope acciones"></span>
                </div>
            </div>
            <div class="panel-body">
                <textarea class="form-control" id="txtMensaje" rows="2"></textarea>
                <br/>
                <button class="btn btn-info pull-right" id="btnNotificaciones">Enviar</button>
            </div>

            <div class="panel-footer text-right" id="valido_mensaje">
            </div>
        </div>
    </div><!--/.col-->
@stop
@section('contenido2')
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Notificaciones enviadas</div>
            <div class="panel-body">
                <table id="tblUsuarios" data-toggle="table" data-url="<% '../ws/tecnobit/usuarios/sin_formato1'%>"
                       data-show-refresh="true" data-search="true"
                       data-show-columns="true" data-select-item-name="toolbar1" data-pagination="true"
                       data-sort-name="name" data-sort-order="desc">
                    <thead>
                    <tr>
                        <th data-field="id" data-sortable="true">ID</th>
                        <th data-field="nombre" data-sortable="true">Mensaje</th>
                        <th data-field="email" data-sortable="true">Alcance</th>
                        <th data-field="ultimo_acceso" data-halign="center" data-sortable="true">Fecha</th>
                    </tr>
                    </thead>
                </table>
                <br/>
            </div>
        </div>
    </div>
@stop
@section('pie')
    <% HTML::script('public/js/sitio/dashboard.js'); %>
    <% HTML::script('public/js/chart.min.js'); %>
    <% HTML::script('public/js/easypiechart.js'); %>
    <% HTML::script('public/js/easypiechart-data.js'); %>
@stop
