@extends('sitio.master.pilar')

@section('header')
    @parent
@stop

@section('barra_navegacion')
    <li class="active">Mapa</li>
@stop

@section('titulo')
    <h1 class="page-header">Mapa - Feicobol 2015</h1>
@stop

@section('contenido1')

@stop
@section('contenido2')
    <div class="col-lg-12">
        <div id="mapa" style="width:100%;height:430px"></div>
    </div><!--/.col-->
@stop

@section('pie')
    <% HTML::script('https://maps.googleapis.com/maps/api/js?v=3.exp'); %>
    <% HTML::script('public/js/sitio/mapa.js'); %>
@stop