@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.min.css'); %>
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
    <input type="hidden" id="credencial" value="<% Session::get("credencial") %>"/>
    <input type="hidden" id="lat" value=""/>
    <input type="hidden" id="lon" value=""/>
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

                <!-- Popup modal Edit Event-->
                <div class="modal fade" id="events-modal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3 id="txtTituloDetalleEvento">Evento</h3>
                            </div>
                            <div class="modal-body" style="height: auto">
                                <div class="chat-body clearfix row">
                                    <div class="col-md-3">
                                        <img id="imgImagenEvento_editar" alt="Foto evento"
                                             width="180px" height="180px" class="img-responsive"/>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>Nombre del evento</label>
                                            <input type="text" id="txtNombre_editar" class="form-control" required/>
                                        </div>

                                        <div class="form-group">
                                            <label>Arte del evento</label>
                                        </div>
                                        <form id="formEdicion" enctype="multipart/form-data">
                                            <div class="form-group form-inline">
                                                <input type="hidden" name="ruta" id="hdnRutaImagen_editar" class="form-control"/>
                                                <input type="file" name="imagen" class="form-control imagen" required/>
                                                <button type="submit" class="btn btn-sm btn-info form-control">Cargar
                                                </button>
                                            </div>
                                        </form>

                                        <div class="form-group">
                                            <label>Lugar</label>
                                            <input type="text" id="txtLugar_editar" class="form-control" required/>
                                        </div>

                                        <div class="form-group">
                                            <label>Ubicación Georeferenciada</label>
                                            <br/><button id="btnGeo_editar" class="btn btn-sm btn-warning">Agregar</button>
                                        </div>

                                        <table>
                                            <tr>
                                                <td colspan="2"><label>Inicio de evento</label></td>
                                                <td><label>Fin de evento</label></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="text" id="txtFechaInicio_editar" size="10"/>
                                                    <select class="horas" id="cmbHoraInicio_editar">
                                                    </select>
                                                    :
                                                    <select class="minutos" id="cmbMinutoInicio_editar">
                                                    </select>
                                                </td>
                                                <td>&nbsp;&nbsp;&nbsp;</td>
                                                <td><input type="text" id="txtFechaFin_editar" size="10"/>
                                                    <select class="horas" id="cmbHoraFin_editar">
                                                    </select>
                                                    :
                                                    <select class="minutos" id="cmbMinutoFin_editar">
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                        <br/><br/>

                                        <div class="form-group">
                                            <label>Tipo de evento</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="tipo_evento_editar" id="emprendimiento_editar" value="emprendimiento" checked>
                                                    Evento sobre Emprendimiento
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="tipo_evento_editar" id="empresarial_editar" value="empresarial">
                                                    Evento Empresarial
                                                </label>
                                            </div>
                                        </div>
                                        <br/>

                                        <div class="form-group">
                                            <label>Descripción corta</label>
                                            <textarea id="txtDescripcion_editar" class="form-control" maxlength="100" required></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Contenido</label>
                                            <textarea id="htmlEvento_editar" class="form-control" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="#" id="btnMostrarModalEliminar" class="btn btn-danger">Eliminar</a>
                                <a href="#" id="btnEditarEvento" class="btn btn-info">Editar</a>
                                <a href="#" data-dismiss="modal" class="btn">Cerrar</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal eliminar -->
                <div class="modal fade" id="eliminarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Eliminar evento</h4>
                            </div>
                            <div class="modal-body">
                                Está seguro de eliminar el evento seleccionado?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnEliminarEvento">Si,
                                    eliminar
                                </button>
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
                                <div class="chat-body clearfix row">
                                    <div class="col-md-3">
						                <span class="chat-img pull-left">
                                         <img id="imgEvento" src="http://placehold.it/640x480/30a5ff/fff" alt="Foto evento"
                                              width="180px" class="img-responsive"/>
						                </span>
                                    </div>
                                    <div class="col-md-9">

                                        <div class="form-group">
                                            <label>Nombre del evento</label>
                                            <input type="text" id="txtNombre" class="form-control" required/>
                                        </div>

                                        <div class="form-group">
                                            <label>Arte del evento</label>
                                        </div>
                                        <form id="f1" enctype="multipart/form-data">
                                            <div class="form-group form-inline">
                                                <input type="hidden" name="ruta" id="hdnRutaImagen" class="form-control"/>
                                                <input type="file" name="imagen" class="form-control imagen" required/>
                                                <button type="submit" class="btn btn-sm btn-info form-control">Cargar
                                                </button>
                                            </div>
                                        </form>

                                        <div class="form-group">
                                            <label>Lugar</label>
                                            <input type="text" id="txtLugar" class="form-control" required/>
                                        </div>

                                        <div class="form-group">
                                            <label>Ubicación Georeferenciada</label>
                                            <br/><button id="btnGeo" class="btn btn-sm btn-warning">Agregar</button>
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
                                                    <select class="minutos" id="cmbMinutoFin">
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                        <br/><br/>

                                        <div class="form-group">
                                            <label>Tipo de evento</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="tipo_evento" id="opciones_1" value="emprendimiento" checked>
                                                    Evento sobre Emprendimiento
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="tipo_evento" id="opciones_2" value="empresarial">
                                                    Evento Empresarial
                                                </label>
                                            </div>
                                        </div>
                                        <br/>


                                        <div class="form-group">
                                            <label>Descripción corta</label>
                                            <textarea id="txtDescripcion" class="form-control" maxlength="100" required></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Contenido</label>
                                            <textarea id="htmlEvento" class="form-control" required></textarea>
                                        </div>
                                    </div>
                                </div>
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
    <!-- Pop up MAPA-->
    <div class="modal fade" id="divMapa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Seleccionar lugar de evento</h4>
                </div>
                <div class="modal-body">
                    <div id="mapa" style="width: 550px; height: 550px"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnSeleccionarPunto">Seleccionar
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop
@section('pie')
    <% HTML::script('https://maps.googleapis.com/maps/api/js?sensor=true'); %>
    <% HTML::script('public/lib/bower_components/bootstrap-calendar/js/calendar.js'); %>
    <% HTML::script('public/lib/bower_components/underscore/underscore-min.js'); %>
    <% HTML::script('public/lib/bower_components/bootstrap-calendar/js/language/es-ES.js'); %>
    <% HTML::script('public/lib/bower_components/wysihtml5x/dist/wysihtml5x-toolbar.js'); %>
    <% HTML::script('public/lib/bower_components/handlebars/handlebars.runtime.min.js'); %>
    <% HTML::script('public/lib/bower_components/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.min.js'); %>
    <% HTML::script('public/lib/bower_components/bootstrap3-wysihtml5-bower/dist/locales/bootstrap-wysihtml5.es-ES.js'); %>
    <% HTML::script('public/js/nueva-empresa/eventos.js'); %>
@stop
