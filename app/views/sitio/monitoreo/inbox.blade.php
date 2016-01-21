@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
    <% HTML::style('public/lib/chosen/chosen.css'); %>
    <% HTML::style('public/lib/cropper/dist/cropper.min.css'); %>
    <% HTML::style('public/lib/jquery-ui-1.11.4.custom/jquery-ui.min.css'); %>
@stop

@section('titulo_plataforma')
    Dr. Clipping
    <style type="text/css">
        html, body,div[class^="container"], .main, .contenido1, .lateral, .lista{
            height: 100%;
        }

        .lista{
            height: 100%;
            overflow-y: scroll;
        }

        .zoomContainer{ z-index: 9999;}
        .zoomWindow{ z-index: 9999;}
    </style>
@stop

@section('barra_navegacion')
    <li class="active">Inbox</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
    <input type="hidden" id="usuario_id" value="<% Session::get("id_usuario")%>"/>
    <input type="hidden" id="perfil_admin" value="<% $data["perfil_admin"]%>"/>

@stop

@section('titulo')
    <h1 class="page-header">Tareas</h1>
@stop

@section('contenido1')

    <div class="col-xs-12 col-md-12 col-lg-12 filtros">
        <button id="btnAplicarFiltros" class="btn btn-sm btn-default"><i class="fa fa-filter"></i> Filtros</button>

        <button id="btnFiltroActivo" class="btn btn-sm btn-warning hidden"><strong><i class="fa fa-filter"></i> Filtro activo!</strong></button>
        <div id="panelFiltros" class="form-horizontal alert alert-warning hidden">
            <div class="row">

                <div id="panelFiltro1" class="col-lg-3">
                    <select id="cmbFiltro1" class="form-control">
                        <option value="0">Seleccione</option>
                        <option value="1">Nombre Empresa</option>
                        <option value="2">Fecha Publicación</option>
                        <option value="3">Medio</option>
                    </select>
                </div>
                <div id="panelFiltro2" class="col-lg-2 hidden">
                    <select id="cmbFiltro2" class="form-control">
                    </select>
                </div>
                <div id="panelFiltro3" class="col-lg-2 hidden">
                    <select id="cmbFiltro3" class="form-control">
                    </select>
                </div>
                <div id="panelFiltro4"  class="col-lg-3 hidden">
                    <select id="cmbFiltro4" class="form-control">
                    </select>
                </div>
                <div id="panelFiltro5" class=" col-lg-3 input-group hidden">
                    <input id="txtFiltro" type="text" class="form-control">
                    <span class="input-group-btn">
                        <button id="btnBusquedaFiltro" class="btn btn-default" type="button">Buscar</button>
                    </span>
                </div>
                <div id="panelFiltro6" class="col-lg-2 hidden">
                    <button id="btnBusquedaFiltro1" class="btn btn-default" type="button">Buscar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-md-6 col-lg-3 lateral">

        <select id="cmbFiltroEstados" class="form-control">
            <option value="0">Tareas pendientes (0)</option>
            <option value="1">Tareas realizadas (0)</option>
            <option value="2">Tareas rechazada (0)</option>
        </select>
        <br/>
        <div id="lstTareas" class="list-group lista">
        </div>

        <div class="list-group">
            <a href="#" id="btnCargarMas" class="list-group-item active text-center">
                <i id="cargando" class="fa fa-spinner fa-spin"></i>
                Cargar más</a>
        </div>
    </div>

    <div class="col-xs-12 col-md-6 col-lg-9">
        <div id="mensaje">
        </div>
        <div id="divVacio" class="panel panel-info">
            <div class="panel-heading">Publicación</div>
            <div class="panel-body">
                <h4>Seleccione una tarea</h4>
            </div>
        </div>

        <div id="divLleno" class="panel panel-info hidden">
            <div class="panel-heading">Publicación</div>
            <div class="panel-body">

                <h3 id="cargandoTarea" class="text-center hidden"><i class="fa fa-spinner fa-spin"></i> Cargando</h3>

                <br/>

                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-6">
                        <h4 class="text-center">Fotografía Página Completa</h4>
                        <img id="imgFoto1" src="" class="img-responsive foto foto1" width="90%" style="cursor: pointer"/>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-6">
                        <h4 class="text-center">Fotografía Artículo</h4>
                        <img id="imgFoto2" src="" class="img-responsive foto foto2" width="90%" style="cursor: pointer"/>
                    </div>
                </div>

                <br/>
                <table class="table table-bordered">
                    <tr>
                        <td>Id</td>
                        <td>Ciudad</td>
                        <td>Tipo de Medio</td>
                        <td>Medio</td>
                        <td>Ubicación</td>
                        <td>Pág.</td>
                        <td>Empresa.</td>
                        <td>Fecha</td>
                        <td id="tdAccionesTitulo" class="text-center">Acciones</td>
                    </tr>
                    <tr>
                        <td id="tdId">Id</td>
                        <td id="tdCiudad">Ciudad</td>
                        <td id="tdTipoMedio">Tipo de Medio</td>
                        <td id="tdMedio">Medio</td>
                        <td id="tdUbicacion">Ubicación</td>
                        <td id="tdPagina">Pág.</td>
                        <td id="tdEmpresa">Empresa.</td>
                        <td id="tdFecha">Fecha</td>
                        <td id="tdAcciones" class="text-center">
                            <a class="edit ml10" href="#" title="Editar">
                                <i class="glyphicon glyphicon-pencil"></i>
                            </a>
                        </td>
                    </tr>
                </table>

                <br/>
                <br/>
                <form id="formAnalisis" class="form-horizontal">

                    <div class="form-group">
                        <label for="cmbTags" class="col-sm-3 control-label">Tags</label>
                        <div class="col-sm-8">
                            <select id="cmbTags" class="form-control" data-placeholder="Seleccione tags..." class="chosen-select" multiple tabindex="4"></select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="txtNombrePublicacion" class="col-sm-3 control-label">Nombre de la publicación</label>
                        <div class="col-sm-8">
                            <input id="txtNombrePublicacion" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cmbColor" class="col-sm-3 control-label">Color</label>
                        <div class="col-sm-6">
                            <select id="cmbColor" class="form-control"></select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="txtTamanio" class="col-sm-3 control-label">Tamaño</label>
                        <div class="col-sm-6">
                            <input id="txtTamanio" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cmbCuerpo" class="col-sm-3 control-label">Cuerpo</label>
                        <div class="col-sm-6">
                            <select id="cmbCuerpo" class="form-control"></select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="txtDia" class="col-sm-3 control-label">Día</label>
                        <div class="col-sm-4">
                            <input id="txtDia" class="form-control" readonly/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="txtTarifa" class="col-sm-3 control-label">Tarifa</label>

                        <div class="col-sm-4">

                            <div class="input-group">
                                <input id="txtTarifa"  type="number" class="form-control">
                                <span id="btnBuscarTarifa" class="input-group-addon" style="cursor: pointer"><i class="glyphicon glyphicon-search"></i></span>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cmbValoracion" class="col-sm-3 control-label">Valoración Cualitativa</label>
                        <div class="col-sm-6">
                            <select id="cmbValoracion" class="form-control"></select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="txtObservaciones" class="col-sm-3 control-label">Observaciones</label>
                        <div class="col-sm-8">
                            <textarea id="txtObservaciones" class="form-control" rows="7"></textarea>
                        </div>
                    </div>

                    <br/>
                    <div class="form-group">
                        <label for="btnEnviarAnalisis" class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">


                            <button type="submit" id="btnEnviarAnalisis" class="btn btn-success right">Enviar formulario de análisis</button>
                            <button type="button" id="btnEnviarNegativa" class="btn btn-danger right">Enviar como Rechazada</button>

                            @if($data["perfil_admin"])
                                <button type="button" id="btnHabilitarEdicion" class="btn btn-default right hidden">Editar Análisis</button>
                                <button type="button" id="btnEditarAnalisis" class="btn btn-success right hidden">Guardar cambios</button>
                            @endif
                        </div>
                    </div>

                </form>

            </div>

        </div>

    </div>

    <!-- Modal Seleccionar Tarifa -->
    <div class="modal fade" id="tarifaModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Seleccionar Tarifa</h4>
                </div>
                <div class="modal-body">

                    <table class="table">
                        <thead>
                        <tr>
                            <th class="col-lg-2 text-center">Departamento</th>
                            <th class="col-lg-3 text-center">Tipo de Medio</th>
                            <th class="col-lg-5 text-center">Medio</th>
                            <th class="col-lg-2 text-center">Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><select id="cmbCiudad_popup" class="form-control"></select></td>
                            <td><select id="cmbTipoMedio_popup" class="form-control"></select></td>
                            <td><select id="cmbMedio_popup" class="form-control"></select></td>
                            <td><button id="btnVerTarifas" class="btn btn-success form-control">Buscar</button></td>
                        </tr>
                        </tbody>
                    </table>

                    <br/>
                    <table id="tblTarifario" data-toggle="table" data-url=""
                           data-show-refresh="true" data-search="true"
                           data-show-columns="true" data-select-item-name="toolbar1" data-pagination="true"
                           data-sort-name="name" data-sort-order="desc">
                        <thead>
                        <tr>
                            <th data-field="id" data-sortable="true">ID</th>
                            <th data-field="dia" data-sortable="true">Días</th>
                            <th data-field="ubicacion" data-sortable="true">Cuerpo</th>
                            <th data-field="color" data-sortable="true">Color</th>
                            <th data-field="tarifa" data-sortable="true">Tarifa</th>
                            <th data-field="operate" data-halign="center" data-formatter="operateFormatter"
                                data-events="operateEvents">Acciones
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Rechazada -->
    <div class="modal fade" id="rechazarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Rechazar la publicación</h4>
                </div>
                <div class="modal-body">
                    Está seguro de rechazar la publicación recibida?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="btnRechazar">Si, rechazar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Imagen -->
    <div class="modal fade" id="editarImagenModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Vista previa de Fotografía
                        <div class=" pull-right" style="margin-right: 12px">
                            <button id="btnEditarImagen" class="btn btn-default"><i class="fa fa-pencil"></i>&nbsp;Editar imagen</button>

                            <button id="btnZoomOut" class="btn btn-default hidden"><i class="glyphicon glyphicon-zoom-out"></i></button>
                            <button id="btnZoomIn" class="btn btn-default hidden"><i class="glyphicon glyphicon-zoom-in"></i></button>

                            <button id="btnGirarIzquierda" class="btn btn-default hidden"><i class="fa fa-undo"></i></button>
                            <button id="btnGirarDerecha" class="btn btn-default hidden"><i class="fa fa-repeat"></i></button>
                        </div>
                    </h4>
                </div>
                <div id="modalBody" class="modal-body">
                    <img id="imgPreview" class="img-responsive" width="100%" src="" crossorigin/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </button>
                    <button type="button" class="btn btn-success hidden" id="btnGuardarImagen">Guardar cambios</button>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Zoom Imagen -->
    <div class="modal fade" id="zoomModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Vista previa de Fotografía
                    </h4>
                </div>
                <div id="modalBody" class="modal-body">
                    <img id="imgPreviewZoom" class="img-responsive" width="100%" src=""/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
                    <h4 class="modal-title" id="myModalLabel">Edición de Publicación</h4>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="cmbCiudad" class="col-sm-3 control-label">Ciudad</label>
                            <div class="col-sm-8">
                                <select id="cmbCiudad" class="form-control"></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cmbTipoMedio" class="col-sm-3 control-label">Tipo de Medio</label>
                            <div class="col-sm-8">
                                <select id="cmbTipoMedio" class="form-control"></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cmbMedio" class="col-sm-3 control-label">Medio</label>
                            <div class="col-sm-8">
                                <select id="cmbMedio" class="form-control"></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cmbUbicacion" class="col-sm-3 control-label">Ubicación</label>
                            <div class="col-sm-8">
                                <select id="cmbUbicacion" class="form-control"></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="txtPagina" class="col-sm-3 control-label">Página</label>
                            <div class="col-sm-8">
                                <input id="txtPagina" class="form-control" type="number"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="txtEmpresa" class="col-sm-3 control-label">Empresa</label>
                            <div class="col-sm-8">
                                <input id="txtEmpresa" class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cmbTipoNoticia" class="col-sm-3 control-label">Tipo de noticia</label>
                            <div class="col-sm-8">
                                <select id="cmbTipoNoticia" class="form-control"></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="txtFecha" class="col-sm-3 control-label">Fecha</label>
                            <div class="col-sm-8">
                                <input id="txtFecha" class="form-control" readonly/>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnEditarPublicacion">Guardar cambios
                    </button>
                </div>
            </div>
        </div>
    </div>

@stop
@section('contenido2')
    <!--<div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Concurso de Likes entre expositores</div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-lg-6">
                        <table id="tblPublicidad" data-toggle="table"
                               data-url="<% 'api/v1/expositoreslikes/'.Session::get("credencial").'/conteo' %>"
                               data-pagination="true"
                               data-sort-name="name"
                               data-sort-order="desc">
                            <thead>
                            <tr>
                                <th data-field="expositor_id" data-sortable="true">ID</th>
                                <th data-field="expositor_nombre" data-sortable="true">Expositor</th>
                                <th data-field="conteo" data-sortable="true" class="text-right">Votos</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="canvas-wrapper">
                                <canvas class="chart" id="pie-chart" ></canvas>
                            </div>
                        </div>
                            <div class="panel-footer text-center">
                               Se mostrarán los 10 Expositores con más Likes
                            </div>
                    </div>
                    </div>
                </div>

            </div>
        </div>
    </div>-->
@stop
@section('pie')
    <% HTML::script('public/lib/bower_components/elevatezoom-master/jquery.elevateZoom-3.0.8.min.js'); %>
    <% HTML::script('public/lib/chosen/chosen.jquery.js'); %>
    <!-- <% HTML::script('public/lib/rotate/jquery.rotate.1-1.js'); %> -->
    <% HTML::script('public/lib/cropper/dist/cropper.min.js'); %>
    <% HTML::script('public/lib/jquery-ui-1.11.4.custom/jquery-ui.min.js'); %>
    <% HTML::script('public/js/monitoreo/inbox.js'); %>
@stop
