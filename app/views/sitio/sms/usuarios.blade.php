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
                    <button id="collapseNuevo" class="btn btn-success pull-right" data-toggle="collapse"
                            data-target="#divNuevoUsuario">+ Nuevo usuario
                    </button>
                    </span>

                <div id="divNuevoUsuario" class="collapse">
                    <br/>
                    <% Form::open(array('url' => '/ws/SmsUsuarios', 'id' => 'formNuevoUsuario', 'class' => 'form-horizontal')) %>

                    <div class="form-group">
                        <label for="txtNombre" class="col-sm-4 control-label">Nombre completo</label>

                        <div class="col-sm-8">
                            <input id="txtNombre" class="form-control" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="txtNombreDeposito" class="col-sm-4 control-label">Nombre depósito</label>

                        <div class="col-sm-8">
                            <input id="txtNombreDeposito" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="txtDigitos" class="col-sm-4 control-label">5 últimos dígitos</label>

                        <div class="col-sm-4">
                            <input id="txtDigitos" class="form-control" maxlength="5" size="10" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="txtClabe" class="col-sm-4 control-label">CLABE interbancaria</label>

                        <div class="col-sm-6">
                            <input id="txtClabe" class="form-control" maxlength="21" size="25"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="txtEmail" class="col-sm-4 control-label">Email de confirmación</label>

                        <div class="col-sm-8">
                            <input id="txtEmail" class="form-control" required/>
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
                    <br/>
                </div>
                <table id="tblUsuarios" data-toggle="table" data-url="<% '../ws/SmsUsuarios_sinformato'%>"
                       data-show-refresh="true" data-search="true"
                       data-show-columns="true" data-select-item-name="toolbar1" data-pagination="true"
                       data-sort-name="name" data-sort-order="desc">
                    <thead>
                    <tr>
                        <th data-field="id" data-sortable="true">ID</th>
                        <th data-field="nombre" data-sortable="true">Nombre</th>
                        <th data-field="username" data-sortable="true">Username</th>
                        <th data-field="celular" data-sortable="true">Celular</th>
                        <th data-field="email" data-sortable="true">Email</th>
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

        <!-- Modal editar -->
        <div class="modal modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Editar usuario</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">

                            <div class="form-group">
                                <label for="txtNombre_editar" class="col-sm-4 control-label">Nombre completo</label>

                                <div class="col-sm-8">
                                    <input id="txtNombre_editar" class="form-control" required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="txtNombreDeposito_editar" class="col-sm-4 control-label">Nombre depósito</label>

                                <div class="col-sm-8">
                                    <input id="txtNombreDeposito_editar" class="form-control"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="txtDigitos_editar" class="col-sm-4 control-label">5 últimos dígitos</label>

                                <div class="col-sm-4">
                                    <input id="txtDigitos_editar" class="form-control" maxlength="5" size="10" required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="txtCelular_editar" class="col-sm-4 control-label">Celular</label>

                                <div class="col-sm-5">
                                    <input id="txtCelular_editar" class="form-control" maxlength="20" size="20"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="txtClabe_editar" class="col-sm-4 control-label">CLABE interbancaria</label>

                                <div class="col-sm-6">
                                    <input id="txtClabe_editar" class="form-control" maxlength="21" size="25"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="txtEmail_editar" class="col-sm-4 control-label">Email de confirmación</label>

                                <div class="col-sm-8">
                                    <input id="txtEmail_editar" class="form-control" required/>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnEditarUsuario">Guardar
                            cambios
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