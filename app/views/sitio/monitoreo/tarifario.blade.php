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
    <li class="active">Tarifario</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
@stop

@section('titulo')
    <h1 class="page-header">Tarifario</h1>
@stop

@section('contenido1')
    <div class="col-lg-12">

        <div id="divBusqueda" class="panel chat">
            <div class="panel-heading" id="accordion"><span class="glyphicon glyphicon-search"></span> Búsqueda de Medios
            </div>

            <div class="panel-body">
                <form id="formBusquedaMedio" class="form-horizontal">

                    <div class="form-group">
                        <label for="cmbCiudad" class="col-sm-4 control-label">Departamento</label>

                        <div class="col-sm-8">
                            <select id="cmbCiudad" class="form-control"></select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cmbTipoMedio" class="col-sm-4 control-label">Tipo Medio</label>

                        <div class="col-sm-8">
                            <select id="cmbTipoMedio" class="form-control"></select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cmbMedio" class="col-sm-4 control-label">Medio</label>

                        <div class="col-sm-8">
                            <select id="cmbMedio" class="form-control"></select>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-md col-sm-offset-4" id="btnVerTarifario">
                            Ver Tarifario
                        </button>
                        &nbsp;&nbsp;
                        <button type="reset" class="btn btn-danger btn-md" id="btnCancelar">Cancelar</button>
                    </div>

                    <div id="mensajeCargando" class="alert alert-success col-sm-8 col-lg-offset-4 hidden"><i class="fa fa-spinner fa-spin"></i>
                        Cargando datos, espere por favor</div>
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
                        <h4 class="modal-title" id="myModalLabel">Eliminar registro</h4>
                    </div>
                    <div class="modal-body">
                        Está seguro de eliminar la fila seleccionada del Tarifario?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnEliminarFila">Si,
                            eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- col-->
@stop
@section('contenido2')
    <div id="divTarifario" class="col-lg-12 hidden">
        <div class="panel panel-default">
            <div class="panel-heading">Tarifario de <span id="txtMedio"></span></div>
            <div class="panel-body">

                <span class="clearfix">
                    <div id="mensaje">
                    </div>
                    <button id="collapse" class="btn btn-success pull-right" data-toggle="collapse"
                            data-target="#divNuevoTarifario"><b>+ Nuevo registro para el tarifario</b></button>
                </span>

                <div id="divNuevoTarifario" class="panel chat collapse">
                    <div class="panel-heading" id="accordion"><span class="glyphicon glyphicon-usd"></span> Registrar tarifa
                    </div>

                    <div class="panel-body">
                        <form id="formNuevoTarifario" class="form-horizontal">

                            <div class="form-group">
                                <label for="cmbDia" class="col-sm-4 control-label">Día</label>
                                <div class="col-sm-8">
                                    <select id="cmbDia" class="form-control" data-placeholder="Seleccione los días..." class="chosen-select" multiple tabindex="4"></select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="cmbUbicacion" class="col-sm-4 control-label">Ubicación</label>

                                <div class="col-sm-8">
                                    <select id="cmbUbicacion" class="form-control"></select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="cmbColor" class="col-sm-4 control-label">Color</label>

                                <div class="col-sm-8">
                                    <select id="cmbColor" class="form-control"></select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="txtTarifa" class="col-sm-4 control-label">Tarifa</label>

                                <div class="col-sm-4">
                                    <input id="txtTarifa" type="number" class="form-control" required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-md col-sm-offset-4" id="btnEnviar">
                                    Guardar registro
                                </button>
                                &nbsp;&nbsp;
                                <button type="reset" class="btn btn-danger btn-md" id="btnCancelarNuevoTarifario">Cancelar</button>
                            </div>

                            <div id="mensajeCargando" class="alert alert-success col-sm-8 col-lg-offset-4 hidden"><i class="fa fa-spinner fa-spin"></i>
                                Cargando datos, espere por favor</div>
                        </form>
                    </div>
                </div>
            </div>

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
            <br/>
        </div>
    </div>
    </div>
@stop

@section('pie')
    <% HTML::script('public/lib/chosen/chosen.jquery.js'); %>
    <% HTML::script('public/js/monitoreo/tarifario.js'); %>
@stop