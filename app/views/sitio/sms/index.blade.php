@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
@stop

@section('titulo_plataforma')
    ADMINISTRADOR Sm$
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
        <div class="panel panel-blue panel-widget ">
            <div class="row no-padding">
                <div class="col-sm-3 col-lg-5 widget-left">
                    <em class="glyphicon glyphicon-envelope glyphicon-l"></em>
                </div>
                <div class="col-sm-9 col-lg-7 widget-right">
                    <div class="large" id="numMes">0</div>
                    <div class="text-muted">SMS/mes</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-md-6 col-lg-3">
        <div class="panel panel-red panel-widget">
            <div class="row no-padding">
                <div class="col-sm-3 col-lg-5 widget-left">
                    <em class="glyphicon glyphicon-usd glyphicon-l"></em>
                </div>
                <div class="col-sm-9 col-lg-7 widget-right">
                    <div class="large" id="numBalance">0</div>
                    <div class="text-muted">Balance/mes</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-md-6 col-lg-3">
        <div class="panel panel-orange panel-widget">
            <div class="row no-padding">
                <div class="col-sm-3 col-lg-5 widget-left">
                    <em class="glyphicon glyphicon-envelope glyphicon-l"></em>
                </div>
                <div class="col-sm-9 col-lg-7 widget-right">
                    <div class="large" id="numTotal">0</div>
                    <div class="text-muted">SMS total</div>
                </div>
            </div>
        </div>
    </div>


@stop
@section('contenido2')
    <div class="col-lg-12">
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
    <% HTML::script('public/js/chart.min.js'); %>
    <% HTML::script('public/js/easypiechart.js'); %>
    <% HTML::script('public/js/easypiechart-data.js'); %>
    <% HTML::script('public/js/sms/dashboard.js'); %>
@stop
