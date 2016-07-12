@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
    <% HTML::style('public/lib/chosen/chosen.css'); %>
@stop

@section('titulo_plataforma')
    Moto Club La Paz
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

         <span class="clearfix">
            <div id="mensaje">
            </div>
            <button id="collapse" class="btn btn-success pull-right" data-toggle="collapse"
                    data-target="#divNuevoUsuario"><b>+ Importar
                    usuarios</b></button>
        </span>

        <div id="divNuevoUsuario" class="panel panel-collapse chat collapse">
            <div class="panel-heading" id="accordion"><span class="glyphicon glyphicon-user"></span> Importar usuarios
            </div>
            <div class="panel-body">

                <div class="col-md-6">

                    <form id="formImportar" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="file" id="fileCsv" name="usuarios" class="form-control" required/>
                        </div>
                        <div class="form-group col-xs-6 pull-right">
                            <button type="submit" id="btnImportar" class="btn btn-sm btn-warning form-control pull-right">
                                Cargar
                            </button>
                        </div>
                    </form>

                    <div id="prgImportar" class="progress progress-striped small active hidden">
                        <div class="progress-bar progress-bar-warning" role="progressbar"
                             aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"
                             style="width: 100%">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="divResultadoImportar"></div>
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

        <!-- Editar usuario -->
        <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Datos del usuario</h4>
                    </div>

                    <div class="modal-body">

                        <div class="row">
                            <div class="panel panel-default col-md-4">
                                <div class="panel-heading">
                                    <p class="panel-title">Foto del Piloto</p>
                                </div>
                                <div class="panel-body">
                                    <a id="hrefFotoPiloto" href="" title="Foto Piloto" target="_blank">
                                        <img id="imgFotoPiloto" class="img-responsive"/>
                                    </a>
                                </div>
                            </div>

                            <div class="panel panel-default col-md-4">
                                <div class="panel-heading">
                                    <p class="panel-title">Foto de la moto</p>
                                </div>
                                <div class="panel-body">
                                    <a id="hrefFotoMoto" href="" title="Foto Moto" target="_blank">
                                        <img id="imgFotoMoto" class="img-responsive"/>
                                    </a>
                                </div>
                            </div>

                            <div class="panel panel-default col-md-4">
                                <div class="panel-heading">
                                    <p class="panel-title">Foto del recibo</p>
                                </div>
                                <div class="panel-body">
                                    <a id="hrefFotoRecibo" href="" title="Foto Recibo" target="_blank">
                                        <img id="imgFotoRecibo" class="img-responsive"/>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="panel col-md-9 clearfix">
                                <div class="panel-heading panel-blue">
                                    <h3 class="panel-title">Datos del registro</h3>
                                </div>
                                <div class="panel-body">
                                    <ul id="ulDatosRegistros" class="list-group">
                                        <li class="list-group-item">Cras justo odio</li>
                                    </ul>
                                </div>
                            </div>

                            <br/>

                            <div class="panel col-md-9 clearfix">
                                <div class="panel-heading panel-orange">
                                    <h3 class="panel-title">Datos de emergencia</h3>
                                </div>
                                <div class="panel-body">
                                    <ul id="ulDatosEmergencia" class="list-group">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>

    </div><!-- col-->
@stop
@section('contenido2')
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Usuarios registrados</div>
            <div class="panel-body">

                <table id="tblUsuarios" data-toggle="table" data-url="<% '../ws/motoclublapaz/usuarios_sinformato'%>"
                       data-show-refresh="true" data-search="true"
                       data-show-columns="true" data-select-item-name="toolbar1" data-pagination="true"
                       data-sort-name="name" data-sort-order="desc">
                    <thead>
                    <tr>
                        <th data-field="id" data-sortable="true">ID</th>
                        <th data-field="nombre" data-sortable="true">Nombre</th>
                        <th data-field="apellido" data-sortable="true">Apellido</th>
                        <th data-field="email" data-sortable="true">Email</th>-->
                        <th data-field="celular" data-sortable="true">Celular</th>
                        <th data-field="placa" data-sortable="true">Placa</th>
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
    <% HTML::script('public/js/md5.js'); %>
    <% HTML::script('public/lib/chosen/chosen.jquery.js'); %>
    <% HTML::script('public/js/motoclublapaz/usuarios.js'); %>
@stop