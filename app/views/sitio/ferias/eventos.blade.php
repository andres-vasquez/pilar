@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
    <% HTML::style('public/lib/bower_components/bootstrap-calendar/css/calendar.css'); %>
    <style>
        .datepicker{z-index:1151 !important;}
    </style>
@stop

@section('titulo_plataforma')
    <% Session::get("nombre_sistema")%>
@stop

@section('barra_navegacion')
    <li class="active">Eventos</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
@stop

@section('titulo')
    <h1 class="page-header">Eventos</h1>
@stop

@section('contenido1')
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div id="mensaje">
                </div>
                <div class="page-header">
                    <div class="pull-right form-inline">
                        <div class="btn-group">
                            <button class="btn btn-success" id="btnAgregarModal">+ Agregar</button>
                        </div>

                        <div class="btn-group">
                            <button class="btn btn-primary" data-calendar-nav="prev"><< Anterior</button>
                            <button class="btn" data-calendar-nav="today">Hoy</button>
                            <button class="btn btn-primary" data-calendar-nav="next">Siguiente >></button>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-info" data-calendar-view="year">Año</button>
                            <button class="btn btn-info active" data-calendar-view="month">Mes</button>
                            <button class="btn btn-info" data-calendar-view="day">Día</button>
                        </div>
                    </div>

                    <h3></h3>
                </div>

                <div id="calendario"></div>
                <div class="modal fade" id="events-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3>Event</h3>
                            </div>
                            <div class="modal-body" style="height: 400px">
                            </div>
                            <div class="modal-footer">
                                <a href="#" data-dismiss="modal" class="btn">Close</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="agregar-modal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3>Agregar evento</h3>
                            </div>
                            <div class="modal-body">
                                <ul>
                                    <li class="left clearfix">
						                <span class="chat-img pull-left">
                                         <img id="imgEvento" src="http://placehold.it/150/30a5ff/fff" alt="Foto evento"
                                              width="180px" height="180px" class="img-responsive"/>
						                </span>

                                        <div class="chat-body clearfix row">
                                            <div class="col-md-9">

                                                <div class="form-group">
                                                    <label>Nombre del evento</label>
                                                    <input type="text" id="txtNombre" class="form-control" required/>
                                                </div>

                                                <div class="form-group">
                                                    <label>Imagen</label>
                                                    <input id="txtImagen" type="file" class="form-control" required/>
                                                </div>

                                                <div class="form-group">
                                                    <label>Lugar</label>
                                                    <input type="text" id="txtLugar" class="form-control" required/>
                                                </div>

                                                <table>
                                                    <tr>
                                                        <td colspan="2"><label>Inicio de evento</label></td>
                                                        <td><label>Fin de evento</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="text" id="txtFechaInicio" size="10"/>
                                                            <select class="horas" id="cmbHoraInicio">
                                                            </select>
                                                            :
                                                            <select class="minutos" id="cmbMinutoInicio">
                                                            </select>
                                                        </td>
                                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                                        <td><input type="text" id="txtFechaFin" size="10"/>
                                                            <select class="horas" id="cmbHoraFin">
                                                            </select>
                                                            :
                                                            <select class="minutos" id="cmMinutoFin">
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <br/><br/>

                                                <div class="form-group">
                                                    <label>Descripción corta</label>
                                                    <textarea id="txtDescripcion" class="form-control" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnAgregar">Agregar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('contenido2')
    <!-- Contenido aca-->
@stop
@section('pie')
    <% HTML::script('public/lib/bower_components/bootstrap-calendar/js/calendar.js'); %>
    <% HTML::script('public/lib/bower_components/underscore/underscore-min.js'); %>
    <% HTML::script('public/lib/bower_components/bootstrap-calendar/js/language/es-ES.js'); %>
    <% HTML::script('public/js/sitio/eventos.js'); %>
@stop
