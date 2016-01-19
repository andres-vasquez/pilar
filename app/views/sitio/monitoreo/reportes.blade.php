@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/ui-lightness/jquery-ui.css'); %>
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
    <% HTML::style('public/css/evol.colorpicker.min.css'); %>
    <% HTML::style('public/css/iconselect.css'); %>
    <% HTML::style('public/lib/jquery-ui-1.11.4.custom/jquery-ui.min.css'); %>
@stop

@section('titulo_plataforma')
    Dr. Clipping
@stop

@section('barra_navegacion')
    <li class="active">Reportes</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
@stop

@section('titulo')
    <h1 class="page-header">Plataforma de reportes</h1>
@stop

@section('contenido1')

    <div id="divPaso1" class="col-xs-12 col-md-6 col-lg-4">
        <div class="panel">
            <div class="panel-heading panel-default">
                Paso 1
            </div>
            <div class="panel-body">
                <h4>Seleccione el tipo de reporte</h4>
                <select id="cmbTipoReporte" class="form-control">
                    <option value="0">Seleccione</option>
                    <option value="1">Publicaciones por Researcher</option>
                    <option value="2">Tareas por Analyst</option>
                    <option value="3">Reporte por ciudad</option>
                </select>
            </div>
        </div>
    </div>

    <div id="divPaso2" class="col-xs-12 col-md-6 col-lg-4 hidden">
        <div class="panel">
            <div class="panel-heading panel-teal">
                Paso 2
            </div>
            <div class="panel-body">
                <h4>Defina el rango de fechas</h4>
                <table class="table text-center">
                    <tr>
                        <td>Fecha inicio</td>
                        <td>Fecha fin</td>
                    </tr>
                    <tr>
                        <td><input type="text" id="fecha_inicio" class="form-control input-sm"/></td>
                        <td><input type="text" id="fecha_fin" class="form-control input-sm"/></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div id="divPaso3" class="col-xs-12 col-md-6 col-lg-4 hidden">
        <div class="panel">
            <div class="panel-heading panel-blue">
                Paso 3
            </div>
            <div class="panel-body">
                <h4>Variables</h4>
                <div class="input-group add-on">
                    ﻿<table id="tblCriterio"
                            data-show-refresh="false"
                            data-search="false"
                            data-show-columns="false"
                            data-select-item-name="toolbar1"
                            data-pagination="true"
                            data-sort-name="name"
                            data-sort-order="desc">
                        <thead>
                        <tr>
                            <th data-field="state" data-checkbox="true"
                                data-formatter="stateFormatter"></th>
                            <th data-field="id" data-sortable="true">ID</th>
                            <th data-field="nombre" data-sortable="true">Nombre</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <br/>
                <br/>
            </div>
            <div class="panel-footer">
                <div class="text-right">
                    <button id="btnGenerarReporte" class="btn btn-primary">Generar reporte</button>
                </div>
            </div>
        </div>
    </div>
    <div id="divResultadoReporte" class="col-lg-12 hidden">
        <h2>Reporte generado</h2>
        <span class="clearfix">
                    <button id="btnExportar" class="btn btn-success pull-right hidden">
                        <i class="fa fa-file-excel-o"></i> Exportar a excel
                    </button>
                    </span>
        <div id="divReporte" class="row">
            <br/><br/>
            <h4 class="text-center"><i class="fa fa-cog fa-spin"></i> Espere por favor, generando reporte...</h4>
        </div>
    </div>
@stop
@section('contenido2')
@stop

@section('pie')
    <% HTML::script('public/lib/jquery-ui-1.11.4.custom/jquery-ui.min.js'); %>
    <% HTML::script('public/js/monitoreo/reportes.js'); %>
@stop