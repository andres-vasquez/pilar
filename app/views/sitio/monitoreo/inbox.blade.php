@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
@stop

@section('titulo_plataforma')
    Dr. Clipping
@stop

@section('barra_navegacion')
    <li class="active">Inbox</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
@stop

@section('titulo')
    <h1 class="page-header">Tareas</h1>
@stop

@section('contenido1')
    <div class="col-xs-12 col-md-6 col-lg-3">

    </div>

    <div class="col-xs-12 col-md-6 col-lg-9">

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
