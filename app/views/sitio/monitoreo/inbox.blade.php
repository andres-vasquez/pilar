@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
@stop

@section('titulo_plataforma')
    Dr. Clipping
    <style type="text/css">
        html, body,div[class^="container"], .main, .contenido1, .lateral, .lista{
            height: 100%;
        }

        .lista{
            overflow-y: scroll;
        }


    </style>
@stop

@section('barra_navegacion')
    <li class="active">Inbox</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
@stop

@section('titulo')
    <h1 class="page-header">Tareas</h1>
@stop

@section('contenido1')


    <div class="col-xs-12 col-md-6 col-lg-3 lateral">

        <select class="form-control">
            <option value="1">Tareas pendientes</option>
            <option value="1">Tareas realizadas</option>
            <option value="1">Tareas rechazada</option>
        </select>
        <br/>
        <div id="lstTareas" class="list-group lista">
            <!--<a href="#" class="list-group-item active">
                <h4 class="list-group-item-heading">Título del elemento de la lista</h4>
                <p class="list-group-item-text">...</p>
            </a>
            <a href="#" class="list-group-item">
                <h4 class="list-group-item-heading">Título del elemento de la lista</h4>
                <p class="list-group-item-text">...</p>
            </a>
            <a href="#" class="list-group-item">
                <h4 class="list-group-item-heading">Título del elemento de la lista</h4>
                <p class="list-group-item-text">...</p>
            </a>
            <a href="#" class="list-group-item">
                <h4 class="list-group-item-heading">Título del elemento de la lista</h4>
                <p class="list-group-item-text">...</p>
            </a>-->
        </div>

        <p class="text-center hidden"><i class="fa fa-spinner fa-spin"></i> Cargando</p>
        <p class="text-center hidden"><b>Ya no se encontraron más tareas</b></p>
    </div>

    <div class="col-xs-12 col-md-6 col-lg-9">
        <div class="panel panel-info">
            <div class="panel-heading">Publicación</div>
            <div class="panel-body">

                <h3 id="cargandoTarea" class="text-center hidden"><i class="fa fa-spinner fa-spin"></i> Cargando</h3>

                <br/>

                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-6">
                        <img id="imgFoto1" src="" class="img-responsive" width="90%"/>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-6">
                        <img id="imgFoto2" src="" class="img-responsive" width="90%"/>
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
                        <td class="text-center">Acciones</td>
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
                        <td class="text-center">
                            <a class="edit ml10" href="#" title="Editar">
                                <i class="glyphicon glyphicon-pencil"></i>
                            </a>
                        </td>
                    </tr>
                </table>

                <br/>
                <br/>
                <form class="form-horizontal">

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
                        <label for="cmbTarifa" class="col-sm-3 control-label">Tarifa</label>
                        <div class="col-sm-4">
                            <select id="cmbTarifa" class="form-control"></select>
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
                            <input type="submit" id="btnEnviarAnalisis" class="btn btn-success right" value="Enviar formulario de análisis"/>
                            <input type="button" id="btnEnviarNegativa" class="btn btn-danger right" value="Enviar como Rechazada"/>
                        </div>
                    </div>

                </form>

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
    <% HTML::script('public/js/monitoreo/inbox.js'); %>
@stop
