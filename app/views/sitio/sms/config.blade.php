@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
@stop

@section('titulo_plataforma')
    ADMINISTRADOR Sm$
@stop

@section('barra_navegacion')
    <li class="active">Config</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
@stop

@section('titulo')
    <h1 class="page-header">Configuraciones</h1>
@stop

@section('contenido1')
    <div class="modal modal fade" id="modalFormato" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Formato de importación</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-xs-8 col-xs-offset-2 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
                            <div class="panel panel-teal panel-widget ">
                                <div class="row no-padding">
                                    <form id="formExcel" method="post" action="../ws/descargas">
                                        <input type="hidden" name="path" value="uploads/sms/modelo_fechas.xlsx"/>
                                        <input type="hidden" name="nombre" value="formato_excel.xlsx"/>
                                        <input type="hidden" name="type"
                                               value="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/>
                                    </form>
                                    <div id="divExcel" class="col-sm-3 col-lg-5 widget-left acciones">
                                        <em class="glyphicon glyphicon-cloud-download glyphicon-l"></em>
                                    </div>

                                    <div class="col-sm-9 col-lg-7 widget-right">
                                        <div class="large">.Xlxs</div>
                                        <div class="text-muted">Formato de excel</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-8 col-xs-offset-2 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
                            <div class="panel panel-orange panel-widget ">
                                <div class="row no-padding">
                                    <form id="formCsv" method="post" action="../ws/descargas">
                                        <input type="hidden" name="path" value="uploads/sms/modelo_fechas_csv.csv"/>
                                        <input type="hidden" name="nombre" value="formato_csv.csv"/>
                                        <input type="hidden" name="type" value="text/csv"/>
                                    </form>
                                    <div id="divCsv" class="col-sm-3 col-lg-5 widget-left acciones">
                                        <em class="glyphicon glyphicon-cloud-download glyphicon-l"></em>
                                    </div>
                                    <div class="col-sm-9 col-lg-7 widget-right">
                                        <div class="large">.CSV</div>
                                        <div class="text-muted">Separado por comas para <b>importación</b></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-md-6 col-lg-4">
        <div class="panel">
            <div class="panel-heading panel-orange">
                Mensajes
                <div class="pull-right">
                    <span data-toggle="modal" data-target="#modalFormato" title="Descargar formato"
                          class="glyphicon glyphicon-download-alt" style="cursor: pointer"></span>
                </div>
            </div>
            <div class="panel-body">
                Importación de mensajes
                <br/><br/>

                <form id="formImportar" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="file" id="fileCsv" name="mensajes" class="form-control" required/>
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

                <div id="divResultadoImportar"></div>
            </div>
            <div class="panel-footer text-right" id="ultimo_mensaje">
            </div>
        </div>
    </div><!--/.col-->

    <div class="col-xs-12 col-md-6 col-lg-4">
        <div class="panel">
            <div class="panel-heading panel-blue">
                Ganancia para usuarios
                <div class="pull-right">
                    <span title="Ver histórico" class="glyphicon glyphicon-time acciones"></span>
                    <span id="btnEditarGanancia" title="Editar" class="glyphicon glyphicon-pencil acciones"></span>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">

                    <div class="col-xs-8 col-xs-offset-2 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
                        <div class="panel panel-blue panel-widget ">
                            <div class="row no-padding">
                                <div class="col-sm-3 col-lg-6 widget-left">
                                    <em class="glyphicon glyphicon-envelope glyphicon-l"></em>
                                </div>
                                <div class="col-sm-9 col-lg-6 widget-right">
                                    <div class="large" id="ganancia"></div>
                                    <input type="text" id="txtGanancia" class="form-control hidden" maxlength="7"
                                           size="7"/>

                                    <div class="text-muted">SMS/enviado</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-xs-10 col-xs-offset-1 hidden" id="divGuardarGanancia">
                        <table class="table text-center">
                            <tr>
                                <td>Fecha inicio</td>
                                <td>Fecha fin</td>
                            </tr>
                            <tr>
                                <td><input type="text" id="fini_ganancia" class="form-control input-sm"/></td>
                                <td><input type="text" id="ffin_ganancia" class="form-control input-sm"/></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><label>
                                        <input type="checkbox" id="chkIndGanancia" value="">
                                        Indefinido
                                    </label></td>
                            </tr>
                        </table>

                        <button type="submit" id="btnGuardarGanancia"
                                class="btn btn-sm btn-primary form-control pull-right">Guardar
                        </button>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right" id="ultimo_ganancia">
            </div>
        </div>
    </div><!--/.col-->

    <div class="col-xs-12 col-md-6 col-lg-4">
        <div class="panel">
            <div class="panel-heading panel-teal">
                Mensaje vigente
                <div class="pull-right">
                    <span id="btnEditarMensaje" title="Editar mensaje"
                          class="glyphicon glyphicon-pencil acciones"></span>
                </div>
            </div>
            <div class="panel-body">
                <br/><br/>

                <div id="mensaje_pago"></div>
                <textarea class="form-control hidden" id="txtMensaje" rows="3"></textarea>

                <div class="hidden" id="divGuardarMensaje">
                    <table class="table text-center">
                        <tr>
                            <td>Fecha inicio</td>
                            <td>Fecha fin</td>
                        </tr>
                        <tr>
                            <td><input type="text" id="fini_mensaje" class="form-control input-sm"/></td>
                            <td><input type="text" id="ffin_mensaje" class="form-control input-sm"/></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><label>
                                    <input type="checkbox" id="chkIndMensaje" value="">
                                    Indefinido
                                </label></td>
                        </tr>
                    </table>
                    <div class="col-xs-6 pull-right">
                        <button type="submit" id="btnGuardarMensaje"
                                class="btn btn-sm btn-success form-control pull-right">Guardar
                        </button>
                    </div>
                </div>

            </div>
            <div class="panel-footer text-right" id="valido_mensaje">
            </div>
        </div>
    </div><!--/.col-->
@stop
@section('contenido2')
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Bancos registrados</div>
            <div class="panel-body">
                    <span class="clearfix">
                        <div id="mensaje"></div>

                    <button id="collapseBanco" class="btn btn-success pull-right" data-toggle="collapse"
                            data-target="#divNuevoBanco">+ Nuevo Banco
                    </button>
                    </span>
                <table id="tblUsuarios" data-toggle="table" data-url="<% '../ws/SmsBanco_sinformato'%>"
                       data-show-refresh="true" data-search="true"
                       data-show-columns="true" data-select-item-name="toolbar1" data-pagination="true"
                       data-sort-name="name" data-sort-order="desc">
                    <thead>
                    <tr>
                        <th data-field="id" data-sortable="true">ID</th>
                        <th data-field="nombre" data-sortable="true">Nombre</th>
                        <th data-field="abreviacion" data-sortable="true">Abreviación</th>
                        <th data-field="indice" data-sortable="true">Indice</th>
                        <th data-field="fecha_creacion" data-sortable="true">Creado</th>
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
    <% HTML::script('public/js/sms/config.js'); %>
@stop