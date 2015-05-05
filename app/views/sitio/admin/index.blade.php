@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
@stop

@section('titulo_plataforma')
    ADMINISTRADOR
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
                    <em class="glyphicon glyphicon-thumbs-up glyphicon-l"></em>
                </div>
                <div class="col-sm-9 col-lg-7 widget-right">
                    <div class="large" id="numLikes">0</div>
                    <div class="text-muted">Likes</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-md-6 col-lg-3">
        <div class="panel panel-orange panel-widget">
            <div class="row no-padding">
                <div class="col-sm-3 col-lg-5 widget-left">
                    <em class="glyphicon glyphicon-comment glyphicon-l"></em>
                </div>
                <div class="col-sm-9 col-lg-7 widget-right">
                    <div class="large" id="numNoticias">0</div>
                    <div class="text-muted">Noticias</div>
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
                    <div class="large" id="numExpositores">0</div>
                    <div class="text-muted">Expositores</div>
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
                    <div class="large" id="numAnuncios">0</div>
                    <div class="text-muted">Anuncios</div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('contenido2')
    <div class="col-lg-12">
        <h2>Dashboard</h2>
    </div>
@stop
@section('pie')
    <% HTML::script('public/js/chart.min.js'); %>
    <% HTML::script('public/js/easypiechart.js'); %>
    <% HTML::script('public/js/easypiechart-data.js'); %>
@stop
