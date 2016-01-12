@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
    <% HTML::style('public/lib/chosen/chosen.css'); %>
@stop

@section('titulo_plataforma')
    Dr. Clipping
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
                    data-target="#divNuevoUsuario"><b>+ Nuevo
                    usuario</b></button>
        </span>

        <div id="divNuevoUsuario" class="panel panel-collapse chat collapse">
            <div class="panel-heading" id="accordion"><span class="glyphicon glyphicon-tag"></span> Nuevo anuncio
            </div>
            <div class="panel-body">

                <form id="formNuevoUsuario" class="form-horizontal">

                    <div class="form-group">
                        <label for="txtNombre" class="col-sm-4 control-label">Nombre Completo</label>

                        <div class="col-sm-8">
                            <input id="txtNombre" class="form-control" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="txtEmail" type="number" class="col-sm-4 control-label">Email</label>

                        <div class="col-sm-4">
                            <input id="txtEmail" class="form-control" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="txtPassword" class="col-sm-4 control-label">Contraseña</label>

                        <div class="col-sm-6">
                            <input id="txtPassword" type="password" class="form-control" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="txtPassword2" class="col-sm-4 control-label">Repetir Contraseña</label>

                        <div class="col-sm-6">
                            <input id="txtPassword2" type="password" class="form-control" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cmbPerfil" class="col-sm-4 control-label">Perfil</label>

                        <div class="col-sm-6">
                            <select id="cmbPerfil" class="form-control">
                                <option value="0">Seleccione</option>
                                <option value="1">Researcher (App móvil)</option>
                                <option value="2">Analyst</option>
                                <option value="3">Cliente</option>
                                <option value="4">Administrador</option>
                            </select>
                        </div>
                    </div>

                    <div id="divCelular" class="form-group hidden">
                        <label for="txtCelular" type="number" class="col-sm-4 control-label">Celular</label>

                        <div class="col-sm-4">
                            <input id="txtCelular" class="form-control"/>
                        </div>
                    </div>

                    <div  id="divImei" class="form-group hidden" >
                        <label for="txtImei" class="col-sm-4 control-label">IMEI (*#06#)</label>

                        <div class="col-sm-4">
                            <input id="txtImei" class="form-control"/>
                        </div>
                    </div>

                    <div  id="divTags" class="form-group hidden">
                        <label for="cmbTags" class="col-sm-4 control-label">Qué Tags verá el cliente?</label>
                        <div class="col-sm-8">
                            <select id="cmbTags" class="form-control" data-placeholder="Seleccione tags..." class="chosen-select" multiple tabindex="4"></select>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-md col-sm-offset-4" id="btnEnviar">
                            Crear
                        </button>
                        &nbsp;&nbsp;
                        <button type="reset" class="btn btn-danger btn-md" id="btnCancelar">Cancelar</button>
                    </div>

                </form>
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
        <div class="modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Editar Usuario</h4>
                    </div>
                    <div class="modal-body">
                        <form id="formEditarUsuario" class="form-horizontal">

                            <div class="form-group">
                                <label for="txtNombre_editar" class="col-sm-4 control-label">Nombre Completo</label>

                                <div class="col-sm-8">
                                    <input id="txtNombre_editar" class="form-control" required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="txtEmail_editar" type="number" class="col-sm-4 control-label">Email</label>

                                <div class="col-sm-4">
                                    <input id="txtEmail_editar" class="form-control" required readonly/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="chkRestablecer" class="col-sm-4 control-label">Restablecer Password</label>

                                <div class="col-sm-4">
                                    <input id="chkRestablecer" type="checkbox" class="form-control"/>
                                </div>
                            </div>

                            <div id="editPassword" class="hidden">
                                <div class="form-group">
                                    <label for="txtPassword_editar" class="col-sm-4 control-label">Contraseña</label>

                                    <div class="col-sm-6">
                                        <input id="txtPassword_editar" type="password" class="form-control" required/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="txtPassword2_editar" class="col-sm-4 control-label">Repetir Contraseña</label>

                                <div class="col-sm-6">
                                    <input id="txtPassword2_editar" type="password" class="form-control" required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="cmbPerfil_editar" class="col-sm-4 control-label">Perfil</label>

                                <div class="col-sm-6">
                                    <select id="cmbPerfil_editar" class="form-control">
                                        <option value="0">Seleccione</option>
                                        <option value="1">Researcher (App móvil)</option>
                                        <option value="2">Analyst</option>
                                        <option value="3">Cliente</option>
                                        <option value="4">Administrador</option>
                                    </select>
                                </div>
                            </div>

                            <div id="divCelular" class="form-group hidden">
                                <label for="txtCelular_editar" type="number" class="col-sm-4 control-label">Celular</label>

                                <div class="col-sm-4">
                                    <input id="txtCelular_editar" class="form-control"/>
                                </div>
                            </div>

                            <div  id="divImei" class="form-group hidden" >
                                <label for="txtImei_editar" class="col-sm-4 control-label">IMEI (*#06#)</label>

                                <div class="col-sm-4">
                                    <input id="txtImei_editar" class="form-control"/>
                                </div>
                            </div>

                            <div  id="divTags_editar" class="form-group hidden">
                                <label for="cmbTags" class="col-sm-4 control-label">Qué Tags verá el cliente?</label>
                                <div class="col-sm-8">
                                    <select id="cmbTags_editar" class="form-control" data-placeholder="Seleccione tags..." class="chosen-select" multiple tabindex="4"></select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnEditarUsuario">Guardar cambios
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
            <div class="panel-heading">Usuarios registrados</div>
            <div class="panel-body">

                <table id="tblUsuarios" data-toggle="table" data-url="<% '../ws/drclipling/usuarios_sinformato'%>"
                       data-show-refresh="true" data-search="true"
                       data-show-columns="true" data-select-item-name="toolbar1" data-pagination="true"
                       data-sort-name="name" data-sort-order="desc">
                    <thead>
                    <tr>
                        <th data-field="id" data-sortable="true">ID</th>
                        <th data-field="perfil" data-sortable="true">Perfil</th>
                        <th data-field="nombre_completo" data-sortable="true">Nombre Completo</th>
                        <th data-field="email" data-sortable="true">Email</th>
                        <th data-field="celular" data-sortable="true">Celular</th>
                        <th data-field="imei" data-sortable="true">IMEI</th>
                        <th data-field="ultimo_acceso" data-halign="center" data-sortable="true">Último acceso</th>
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
    <% HTML::script('public/js/monitoreo/usuarios.js'); %>
@stop