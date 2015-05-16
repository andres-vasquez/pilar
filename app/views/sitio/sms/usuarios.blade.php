@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
@stop

@section('titulo_plataforma')
    ADMINISTRADOR Sm$
@stop

@section('barra_navegacion')
    <li class="active">Usuarios</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
@stop

@section('titulo')
    <h1 class="page-header">Usuarios</h1>
@stop

@section('contenido1')
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Usuarios registrados</div>
            <div class="panel-body">
                 <span class="clearfix">
                        <div id="mensaje"></div>
                    <button id="collapseBanco" class="btn btn-success pull-right" data-toggle="collapse"
                            data-target="#divNuevoUsuario">+ Nuevo usuario</button>
                    </span>
                <table id="tblUsuarios" data-toggle="table" data-url="<% '../ws/SmsUsuarios_sinformato'%>" data-show-refresh="true" data-search="true"
                       data-show-columns="true" data-select-item-name="toolbar1" data-pagination="true"
                       data-sort-name="name" data-sort-order="desc">
                    <thead>
                    <tr>
                        <th data-field="id" data-sortable="true">ID</th>
                        <th data-field="nombre" data-sortable="true">Nombre</th>
                        <th data-field="username" data-sortable="true">Username</th>
                        <th data-field="celular" data-sortable="true">Celular</th>
                        <th data-field="email" data-sortable="true">Email</th>
                        <th data-field="ultimo_acceso" data-halign="center"  data-sortable="true">Último acceso</th>
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
@section('contenido2')
@stop

@section('pie')
    <% HTML::script('public/js/sms/usuarios.js'); %>
@stop