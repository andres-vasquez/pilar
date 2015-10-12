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
            <div class="panel-heading" id="accordion"><span class="glyphicon glyphicon-user"></span> Agregar
                expositor
            </div>
            <div class="panel-body">
                <ul id="ulFormulario">
                    <li class="left clearfix">
                        <div class="row">
                            <div class="col-md-5 form-group">
                                <img id="imgExpositor" alt="Foto expositor"
                                     src="http://placehold.it/150x80/30a5ff/fff" class="img-responsive" width="180px"/>
                            </div>

                            <div class="col-md-5 form-group">
                                <p>Subir logo del expositor (opcional)</p>

                                <form id="formImagen" enctype="multipart/form-data">
                                    <div class="form-group form-inline">
                                        <input type="hidden" name="ruta_aws" id="hdnRutaImagen" class="form-control"/>
                                        <input type="file" name="imagen" class="form-control imagen" required/>
                                        <button type="submit" class="btn btn-sm btn-info form-control">Cargar
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
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

                            <div class="col-md-5 form-group">
                                <label>Rubro especiífico</label>
                                <input type="text" name="rubro_especifico" id="txtRubroEspecifico" class="form-control"/>
                            </div>

                            <div class="col-md-5 form-group">
                                <label>Stand</label>
                                <input type="text" id="txtStand" name="stand" size="5" class="form-control" required/>
                            </div>

                            <div class="col-md-5 form-group">
                                <label>Página web</label>
                                <input type="text" id="txtWebsite" name="website" class="form-control" required/>
                            </div>
                            <div class="col-md-5 form-group">
                                <label>Fanpage (facebook)</label>
                                <input type="text" id="txtFacebook" name="fanpage" class="form-control" required/>
                            </div>

                            <!-- Feicobol-->
                            @if (Session::get("nombre_sistema") === "feicobol")
                                <div class="col-md-5 form-group">
                                    <label>Teléfono</label>
                                    <input type="text" id="txtTelefono" name="telefono" class="form-control" required/>
                                </div>
                                <div class="col-md-5 form-group">
                                    <label>Fax</label>
                                    <input type="text" id="txtFax" name="fax" class="form-control" required/>
                                </div>
                            @endif

                            <div class="col-md-5 form-group">
                                <label>Email</label>
                                <input type="text" id="txtEmail" name="email" class="form-control" required/>
                            </div>

                            <div class="col-md-5 form-group">
                                <label>Descripcion</label>
                                <textarea id="txtDescripcion" name="descripcion" maxlength="200" class="form-control"></textarea>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="panel-footer">
                <div class="input-group">
                    <button type="button" class="btn btn-success btn-md" id="btnNuevoExpositor">Guardar</button>
                    &nbsp;&nbsp;
                    <button type="button" class="btn btn-danger btn-md">Cancelar</button>
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
                    <h4 class="modal-title" id="myModalLabel">Eliminar expositor</h4>
                </div>
                <div class="modal-body">
                    Está seguro de eliminar el expositor seleccionado?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnEliminarExpositor">Si,
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
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Editar expositor</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5 form-group">
                                <img id="imgExpositor_editar" alt="Foto expositor"
                                     src="http://placehold.it/150x80/30a5ff/fff" class="img-responsive" width="180px"/>
                            </div>

                            <div class="col-md-5 form-group">
                                <p>Subir logo del expositor (opcional)</p>

                                <form id="formImagen" enctype="multipart/form-data">
                                    <div class="form-group form-inline">
                                        <input type="hidden" name="ruta_aws" id="hdnRutaImagen_editar" class="form-control"/>
                                        <input type="file" name="imagen" class="form-control imagen" required/>
                                        <button type="submit" class="btn btn-sm btn-info form-control">Cargar
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <div class="row">
                            <br/>
                            <div class="col-md-5 form-group">
                                <label>Nombre</label>
                                <input type="text" id="txtNombre_editar" name="nombre" class="form-control" required/>
                            </div>

                                <div class="col-md-5 form-group">
                                    <label>Rubro</label>
                                    <input type="hidden" name="rubro_id" id="hdnRubro_editar"/>
                                    <select id="cmbRubro_editar" name="rubro" class="form-control" required></select>
                                </div>

                                <div class="col-md-5 form-group">
                                    <label>Área</label>
                                    <select class="form-control" name="pabellon" id="cmbArea_editar">
                                    </select>
                                </div>

                            <div class="col-md-5 form-group">
                                <label>Rubro especiífico</label>
                                <input type="text" name="rubro_especifico" id="txtRubroEspecifico_editar" class="form-control"/>
                            </div>

                            <div class="col-md-5 form-group">
                                <label>Stand</label>
                                <input type="text" id="txtStand_editar" name="stand" size="5" class="form-control" required/>
                            </div>

                            <div class="col-md-5 form-group">
                                <label>Página web</label>
                                <input type="text" id="txtWebsite_editar" name="website" class="form-control" required/>
                            </div>
                            <div class="col-md-5 form-group">
                                <label>Fanpage (facebook)</label>
                                <input type="text" id="txtFacebook_editar" name="fanpage" class="form-control" required/>
                            </div>

                            <div class="col-md-5 form-group">
                                <label>Email</label>
                                <input type="text" id="txtEmail_editar" name="email" class="form-control" required/>
                            </div>

                            <div class="col-md-5 form-group">
                                <label>Descripcion</label>
                                <textarea id="txtDescripcion_editar" name="descripcion" maxlength="200" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnGuardarEditar">Guardar
                        </button>
                    </div>
                </div>
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
                        <!-- Feicobol-->
                        @if (Session::get("nombre_sistema") === "feicobol")
                            <th data-field="direccion" data-sortable="true">Dirección</th>
                        @else
                            <th data-field="rubro" data-sortable="true">Rubro</th>
                        @endif
                        <th data-field="pabellon" data-sortable="true">Pabellón</th>
                        <th data-field="stand" data-sortable="true">Stand</th>
                        <!--<th data-field="website" data-sortable="true">Website</th>-->
                        <th data-field="fanpage" data-sortable="true">fanpage</th>
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
