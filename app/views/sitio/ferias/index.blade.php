@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
@stop

@section('titulo_plataforma')
    <% Session::get("nombre_sistema")%>
@stop

@section('barra_navegacion')
    <li class="active">Dashboard</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
@stop

@section('titulo')
    <h1 class="page-header">Indicadores</h1>
@stop

@section('contenido1')
    <!-- Dashboard Feicobol-->
    @if (Session::get("nombre_sistema") === "feicobol")

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


        @else <!-- Ferias en general -->

        <div class="col-xs-12 col-md-6 col-lg-3">
            <div class="panel panel-blue panel-widget ">
                <div class="row no-padding">
                    <div class="col-sm-3 col-lg-5 widget-left">
                        <em class="glyphicon glyphicon-file glyphicon-l"></em>
                    </div>
                    <div class="col-sm-9 col-lg-7 widget-right">
                        <div class="large" id="numNoticias">0</div>
                        <div class="text-muted">Noticias</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-6 col-lg-3">
            <div class="panel panel-orange panel-widget">
                <div class="row no-padding">
                    <div class="col-sm-3 col-lg-5 widget-left">
                        <em class="glyphicon glyphicon-calendar glyphicon-l"></em>
                    </div>
                    <div class="col-sm-9 col-lg-7 widget-right">
                        <div class="large" id="numEventos">0</div>
                        <div class="text-muted">Eventos</div>
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
                        <em class="glyphicon glyphicon-shopping-cart glyphicon-l"></em>
                    </div>
                    <div class="col-sm-9 col-lg-7 widget-right">
                        <div class="large" id="numOfertas">0</div>
                        <div class="text-muted">Ofertas</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda fila -->
        <div class="col-xs-12 col-md-6 col-lg-3">
            <div class="panel panel-red panel-widget">
                <div class="row no-padding">
                    <div class="col-sm-3 col-lg-5 widget-left">
                        <em class="glyphicon glyphicon-tag glyphicon-l"></em>
                    </div>
                    <div class="col-sm-9 col-lg-7 widget-right">
                        <div class="large" id="numPublicidad">0</div>
                        <div class="text-muted">Publicidad</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-6 col-lg-3">
            <div class="panel panel-teal panel-widget">
                <div class="row no-padding">
                    <div class="col-sm-3 col-lg-5 widget-left">
                        <em class="glyphicon glyphicon glyphicon-minus glyphicon-l"></em>
                    </div>
                    <div class="col-sm-9 col-lg-7 widget-right">
                        <div class="large" id="numBanner">0</div>
                        <div class="text-muted">Banners</div>
                    </div>
                </div>
            </div>
        </div>



    @endif


@stop
@section('contenido2')
    <!-- Concurso likes Feicobol-->
    @if (Session::get("nombre_sistema") === "feicobol")
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">Concurso de Likes entre expositores</div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-lg-6">
                            <table id="tblPublicidad" data-toggle="table"
                                   data-url="<% 'api/v1/expositoreslikes/'.Session::get("credencial").'/conteo' %>"
                                   data-pagination="true"
                                   data-sort-name="name"
                                   data-sort-order="desc">
                                <thead>
                                <tr>
                                    <th data-field="expositor_id" data-sortable="true">ID</th>
                                    <th data-field="expositor_nombre" data-sortable="true">Expositor</th>
                                    <th data-field="conteo" data-sortable="true" class="text-right">Votos</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="canvas-wrapper">
                                        <canvas class="chart" id="pie-chart" ></canvas>
                                    </div>
                                </div>
                                <div class="panel-footer text-center">
                                    Se mostrarán los 10 Expositores con más Likes
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif
@stop
@section('pie')
    <!-- JS del Dashboard de Feicobol-->
    @if (Session::get("nombre_sistema") === "feicobol")
        <% HTML::script('public/js/sitio/dashboard_feicobol.js'); %>
    @else
        <% HTML::script('public/js/sitio/dashboard.js'); %>
    @endif

    <% HTML::script('public/js/chart.min.js'); %>
    <% HTML::script('public/js/easypiechart.js'); %>
    <% HTML::script('public/js/easypiechart-data.js'); %>
@stop
