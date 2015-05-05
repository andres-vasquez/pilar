@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/ui-lightness/jquery-ui.css'); %>
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
    <% HTML::style('public/css/evol.colorpicker.min.css'); %>
    <% HTML::style('public/css/iconselect.css'); %>
@stop

@section('titulo_plataforma')
    ADMINISTRADOR Sm$
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
                    <option value="1">Mensajes por usuario</option>
                    <option value="2">Mensajes por email</option>
                    <option value="3">Mensajes por banco</option>
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
                <h4>Criterio de búsqueda</h4>

                <div class="input-group add-on">
                    <input type="text" class="form-control" placeholder="Búsqueda" name="srch-term" id="srch-term">

                    <div class="input-group-btn">
                        <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i>
                        </button>
                    </div>
                </div>
                <small class="pull-right">Se autocompletará por banco</small>
                <br/>
                <br/>
            </div>
            <div class="panel-footer">
                <div class="text-right">
                    <button class="btn btn-primary">Generar reporte</button>
                </div>
            </div>
        </div>
    </div>
    <!--
    <div class="col-lg-12">
        <h2>Reportes</h2>
        -Fecha inicio
        -Fecha fin

        -Por banco
        -Por usuario
        -Por cantidad de mensajes
    </div><!--/.col-->
@stop
@section('contenido2')
@stop

@section('pie')
    <% HTML::script('public/js/sms/reportes.js'); %>
@stop