@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
    <style>
        .azul{
            border:solid 9px rgba(48, 164, 255, 1);
            width:8px;
            height:8px;
            margin-right: 5px;
        }
        .rojo{
            border:solid 9px rgba(249, 36, 63, 1);
            width:8px;
            height:8px;
            margin-right: 5px;
            margin-left: 20px;
        }
    </style>
@stop

@section('titulo_plataforma')
    Dr. Clipping
@stop

@section('barra_navegacion')
    <li class="active">Dashboard</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
@stop

@section('titulo')
    <h1 class="page-header">Indicadores</h1>
@stop

@section('contenido1')
    <div class="col-xs-12 col-md-6 col-lg-3">
        <div class="panel panel-blue panel-widget ">
            <div class="row no-padding">
                <div class="col-sm-3 col-lg-5 widget-left">
                    <em class="glyphicon glyphicon-file glyphicon-l"></em>
                </div>
                <div class="col-sm-9 col-lg-7 widget-right">
                    <div class="large" id="numRevistas">0</div>
                    <div class="text-muted">Anuncios en Revistas</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-md-6 col-lg-3">
        <div class="panel panel-orange panel-widget">
            <div class="row no-padding">
                <div class="col-sm-3 col-lg-5 widget-left">
                    <em class="glyphicon glyphicon-align-left glyphicon-l"></em>
                </div>
                <div class="col-sm-9 col-lg-7 widget-right">
                    <div class="large" id="numPeriodico">0</div>
                    <div class="text-muted">Anuncios en Periódicos</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6 col-lg-3">
        <div class="panel panel-teal panel-widget">
            <div class="row no-padding">
                <div class="col-sm-3 col-lg-5 widget-left">
                    <em class="glyphicon glyphicon-user glyphicon-l"></em>
                </div>
                <div class="col-sm-9 col-lg-7 widget-right">
                    <div class="large" id="numUsuarios">0</div>
                    <div class="text-muted">Usuarios</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6 col-lg-3">
        <div class="panel panel-red panel-widget">
            <div class="row no-padding">
                <div class="col-sm-3 col-lg-5 widget-left">
                    <em class="glyphicon glyphicon-tag glyphicon-l"></em>
                </div>
                <div class="col-sm-9 col-lg-7 widget-right">
                    <div class="large" id="numTotal">0</div>
                    <div class="text-muted">Anuncios en total</div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('contenido2')
    <div class="col-lg-12">
        <span><img class="azul"/>Publicaciones Recibidas</span>
        <span><img class="rojo"/>Publicaciones Analizadas</span>
        <div class="btn-group pull-right">
            <div class="btn-group">
                <button id="btnSelecciondo" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    Seleccione un mes
                    <span class="caret"></span>
                </button>
                <ul id="ulMeses" class="dropdown-menu">
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="canvas-wrapper">
                    <canvas class="main-chart" id="line-chart" height="180" width="600"></canvas>
                </div>
            </div>
        </div>
    </div>
@stop
@section('pie')
    <% HTML::script('public/js/monitoreo/dashboard.js'); %>
    <% HTML::script('public/js/chart.min.js'); %>
    <% HTML::script('public/js/easypiechart.js'); %>
    <% HTML::script('public/js/easypiechart-data.js'); %>
@stop
