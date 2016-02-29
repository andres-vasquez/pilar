@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/ui-lightness/jquery-ui.css'); %>
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
    <% HTML::style('public/lib/chosen/chosen.css'); %>
    <% HTML::style('public/lib/jqplot/jquery.jqplot.css'); %>
    <% HTML::style('public/lib/jquery-ui-1.11.4.custom/jquery-ui.min.css'); %>
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
    <input type="hidden" id="args" value="<% Session::get("perfiles")[0]["args"]%>"/>
@stop

@section('titulo')
    <div class="col-xs-12 col-md-12 col-lg-12 alert alert-warning">
        <h4>Filtro avanzado</h4>
        <form class="form-horizontal">
            <div class="row">

                <div id="panelFiltro2" class="col-lg-2">
                    <label>Fecha Inicio</label>
                    <input id="txtFechaInicio" type="text" class="form-control"/>
                </div>
                <div id="panelFiltro3" class="col-lg-2">
                    <label>Fecha Fin</label>
                    <input id="txtFechaFin" type="text" class="form-control"/>
                </div>
                <div id="panelFiltro3" class="col-lg-6">
                    <label>Tags de búsqueda</label>
                    <select id="cmbTags" class="form-control" data-placeholder="Seleccione tags..." class="chosen-select" multiple tabindex="4"></select>
                </div>
                <div id="panelFiltro6" class="col-lg-2">
                    <label>&nbsp;</label>
                    <button id="btnActualizar" class="btn btn-default form-control" type="button"><i class="fa fa-refresh"></i> Actualizar</button>
                </div>
            </div>
        </form>
    </div>
    <br/>
@stop

@section('contenido1')
    <div class="col-xs-12 col-md-12 col-lg-9">

        <div class="panel panel-primary">
            <div class="panel-heading">
                Cantidad de Publicaciones por medio y por ciudad</div>
            <div class="panel-body">

                <table id="tblTipoMedioCiudad" class="table table-bordered">
                </table>
            </div>

            <div id="graficoTipoMedioCiudad" class="text-center container" style="height:300px; width:80%">
            </div>
            <br/><br/>
        </div>
    </div>
    <br/><br/>


    <div class="col-xs-12 col-md-12 col-lg-12">
        <br/><br/>
        <div class="row">
            <div class="col-xs-12 col-md-12 col-lg-6">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Revistas de <span class="txtCiudad">Santa Cruz</span></div>
                    <div class="panel-body">
                        <table id="tblRevistasCiudad" class="table table-bordered">
                        </table>
                    </div>
                    <div id="graficoRevistas" class="text-center container" style="height:300px; width:80%">
                    </div>
                    <br/><br/>
                </div>
            </div>
            <div class="col-xs-12 col-md-12 col-lg-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        Periódicos de <span class="txtCiudad">Santa Cruz</span></div>
                    <div class="panel-body">
                        <table id="tblPeriodicosCiudad" class="table table-bordered">
                        </table>
                    </div>
                    <div id="graficoPeriodicos" class="text-center container" style="height:300px; width:80%">
                    </div>
                    <br/><br/>
                </div>
            </div>
        </div>
    </div>




    <!--<div class="col-xs-12 col-md-6 col-lg-3">
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
    </div>-->
@stop
@section('contenido2')
    <!--<div class="col-lg-12">
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
    </div>-->
@stop
@section('pie')
    <% HTML::script('public/lib/chosen/chosen.jquery.js'); %>
    <% HTML::script('public/lib/jqplot/jquery.jqplot.js'); %>
    <% HTML::script('public/lib/jqplot/plugins/jqplot.barRenderer.js'); %>
    <% HTML::script('public/lib/jqplot/plugins/jqplot.pieRenderer.js'); %>
    <% HTML::script('public/lib/jqplot/plugins/jqplot.categoryAxisRenderer.js'); %>
    <% HTML::script('public/lib/jqplot/plugins/jqplot.pointLabels.js'); %>
    <% HTML::script('public/lib/jquery-ui-1.11.4.custom/jquery-ui.min.js'); %>
    <% HTML::script('public/js/monitoreo/cliente/dashboard.js'); %>
@stop
