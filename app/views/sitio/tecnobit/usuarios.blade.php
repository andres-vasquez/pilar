@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
@stop

@section('titulo_plataforma')
    TecnoBit
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
                    <br/>
                </div>
                <table id="tblUsuarios" data-toggle="table" data-url="<% '../ws/tecnobit/usuarios/sin_formato'%>"
                       data-show-refresh="true" data-search="true"
                       data-show-columns="true" data-select-item-name="toolbar1" data-pagination="true"
                       data-sort-name="name" data-sort-order="desc">
                    <thead>
                    <tr>
                        <th data-field="id" data-sortable="true">ID</th>
                        <th data-field="nombre" data-sortable="true">Nombre</th>
                        <th data-field="email" data-sortable="true">Email</th>
                        <th data-field="acceso" data-sortable="true">Tipo acceso</th>
                        <th data-field="dispositivo" data-sortable="true">Dispositivo</th>
                        <th data-field="ultimo_acceso" data-halign="center" data-sortable="true">Último acceso</th>
                        <th data-field="operate" data-halign="center" data-formatter="operateFormatter"
                            data-events="operateEvents">Acciones
                        </th>
                    </tr>
                    </thead>
                </table>
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
                        <h4 class="modal-title" id="myModalLabel">Eliminar usuario</h4>
                    </div>
                    <div class="modal-body">
                        Está seguro de dar de baja el usuario seleccionado?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnEliminarUsuario">Si,
                            eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- col-->
@stop
@section('contenido2')
@stop

@section('pie')
    <% HTML::script('public/js/sms/usuarios.js'); %>
@stop