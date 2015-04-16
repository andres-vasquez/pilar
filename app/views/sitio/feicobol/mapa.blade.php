@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/ui-lightness/jquery-ui.css'); %>
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
    <% HTML::style('public/css/evol.colorpicker.min.css'); %>
@stop

@section('barra_navegacion')
    <li class="active">Mapa</li>
@stop

@section('titulo')
    <h1 class="page-header">Mapa - Feicobol 2015</h1>
@stop

@section('contenido1')
    <div class="col-lg-12">
        <div id="mensaje"></div>
        <div class="row">
            <table class="col-lg-6 text-center">
                <tr>
                    <td class="text-right">Color:</td>
                    <td><input class="colorPicker evo-cp0" id="txtColorPicker" size="8" maxlength="8"/></td>
                    <td class="text-right">Nombre del Layout:</td>
                    <td><input id="txtNombre" size="20" maxlength="20"/></td>
                </tr>
            </table>

            <div class="col-lg-6 text-right">
                <div id="btnGuardarCambios" class="btn btn-info btn-sm disabled"><i id="loagingGuardarCambios" class="fa fa-spinner fa-spin hidden"></i> Guardar cambios</div>

                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle"
                            data-toggle="dropdown">
                        <span class="glyphicon glyphicon-th-large"></span>
                        Capas disponibles <span class="caret"></span>
                    </button>

                    <ul id="ulLstCapas" class="dropdown-menu" role="menu">
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div id="mapa" style="width:100%;height:430px" class="fill"></div>
    </div><!--/.col-->
@stop
@section('contenido2')
@stop

@section('pie')
    <% HTML::script('public/js/fontawesome-markers.min.js'); %>
    <% HTML::script('https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=drawing'); %>
    <% HTML::script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js'); %>
    <% HTML::script('public/js/evol.colorpicker.min.js'); %>
    <% HTML::script('public/js/sitio/mapa.js'); %>
@stop