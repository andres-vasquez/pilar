@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/css/bootstrap-table.css'); %>
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
@stop

@section('titulo_plataforma')
    <% Session::get("nombre_sistema")%>
@stop

@section('barra_navegacion')
    <li class="active">Ofertas</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
@stop

@@section('titulo')
    <h1 class="page-header">Ofertas</h1>
@stop

@section('contenido1')
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Ofertas de la Feria</div>
            <div class="panel-body">
                Aca vendran las ofertas
            </div>
        </div>
    </div>
@stop
@section('contenido2')
    <!-- Contenido aca-->
@stop
@section('pie')
    <% HTML::script('public/js/bootstrap-table.js'); %>
    <% HTML::script('public/js/sitio/expositores.js'); %>
@stop
