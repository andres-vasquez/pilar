@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/ui-lightness/jquery-ui.css'); %>
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
    <% HTML::style('public/css/evol.colorpicker.min.css'); %>
    <% HTML::style('public/css/iconselect.css'); %>
@stop

@section('titulo_plataforma')
    ADMINISTRADOR
@stop

@section('barra_navegacion')
    <li class="active">Usuarios</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
@stop

@section('titulo')
    <h1 class="page-header">Usuarios</h1>
@stop

@section('contenido1')
    <div class="col-lg-12">
        <h2>Usuarios</h2>
    </div><!--/.col-->
@stop
@section('contenido2')
@stop

@section('pie')
    <% HTML::script('public/js/fontawesome-markers.min.js'); %>
    <% HTML::script('https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=drawing'); %>
    <% HTML::script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js'); %>
    <% HTML::script('public/js/evol.colorpicker.min.js'); %>
    <% HTML::script('public/js/iconselect.js'); %>
    <% HTML::script('public/js/iscroll.js'); %>
@stop