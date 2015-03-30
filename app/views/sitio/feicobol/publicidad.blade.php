@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/css/bootstrap-table.css'); %>
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
@stop

@section('barra_navegacion')
    <li class="active">Publicidad</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
@stop

@section('titulo')
    <h1 class="page-header">Anuncios in-App</h1>
@stop

@section('contenido1')
    <div class="col-lg-12">
        <span class="clearfix">
            <div id="mensaje">
            </div>
            <button id="collapsePublicidad" class="btn btn-success pull-right" data-toggle="collapse"
                    data-target="#divNuevoAnuncio"><b>+ Nuevo
                    anuncio</b></button>
        </span>

        <div id="divNuevoAnuncio" class="panel panel-collapse chat collapse in">

            <div class="panel-heading" id="accordion"><span class="glyphicon glyphicon-tag"></span> Nuevo anuncio
            </div>
            <div class="panel-body">
                <ul id="ulFormulario">
                    <li class="left clearfix">
                        <div class="col-md-9">
                            <% Form::open(array('url' => '/ws/publicidad', 'id' => 'formNuevoAnuncio', 'class' => 'form-horizontal')) %>
                            <div class="form-group">
                                <label for="txtNombre" class="col-sm-4 control-label">Nombre del anuncio</label>

                                <div class="col-sm-8">
                                    <input id="txtNombre" class="form-control" required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="txtDescripcion" class="col-sm-4 control-label">Descripción del
                                    anuncio</label>

                                <div class="col-sm-8">
                                    <textarea id="txtDescripcion" class="form-control" required></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="txtUrlAnuncio" class="col-sm-4 control-label">Url detalle del
                                    anuncio</label>

                                <div class="col-sm-8">
                                    <input id="txtUrlAnuncio" class="form-control" required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="cmbPrioridad" class="col-sm-4 control-label">Prioridad</label>

                                <div class="col-sm-8">
                                    <select id="cmbPrioridad" class="form-control">
                                        <option value="1">1 - Prioridad muy baja</option>
                                        <option value="2">2 - Prioridad baja</option>
                                        <option value="3" selected>3 - Prioridad media (recomendado)</option>
                                        <option value="4">4 - Prioridad alta</option>
                                        <option value="5">5 - Prioridad muy alta</option>
                                    </select>
                                </div>
                            </div>

                            <br/>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-md col-sm-offset-4" id="btnEnviar">
                                    Guardar
                                </button>
                                &nbsp;&nbsp;
                                <button type="reset" class="btn btn-danger btn-md" id="btnCancelar">Cancelar</button>
                            </div>
                            <% Form::close() %>
                        </div>
                    </li>
                </ul>
            </div>
            <div id="divImagenes" class="panel-footer hidden">
                <div class="alert alert-success">
                    <h4>Adjunte el arte de la publicidad según cada resolución específica</h4>
                </div>

                <div class="panel-heading"><span class="glyphicon glyphicon-picture"></span> Imágenes adjuntas
                </div>

                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <h4><i class="fa fa-android"></i> Imágenes Android</h4>
                    </div>

                    <div class="col-sm-10 col-sm-offset-1">
                        <!--
                            240x48
                            320x71
                            720x144
                            1440x288
                        -->

                        <div class="row">
                            <div class="col-sm-6" style="padding: 20px">
                                <h5 class="text-center">240 x 48</h5>
                                <img id="android_240x48" src="http://placehold.it/320x47&text=publicidad"
                                     class="img-responsive center-block"/>
                                <br/>

                                <form enctype="multipart/form-data">
                                    <div class="form-group form-inline">
                                        <input type="hidden" class="id_publicidad" name="publicidad_id" value=""/>
                                        <input type="hidden" name="tipo" value="android"/>
                                        <input type="hidden" name="sizex" value="240"/>
                                        <input type="hidden" name="sizey" value="48"/>
                                        <input type="file"  name="imagen" class="form-control imagen" required/>
                                        <button type="submit" class="btn btn-sm btn-info form-control">Cargar</button>
                                    </div>
                                </form>

                            </div>
                            <div class="col-sm-6" style="padding: 20px">
                                <h5 class="text-center">320 x 71</h5>
                                <img id="android_320x71" src="http://placehold.it/320x47&text=publicidad"
                                     class="img-responsive center-block"/>
                                <br/>

                                <form enctype="multipart/form-data">
                                    <div class="form-group form-inline">
                                        <input type="hidden" class="id_publicidad" name="publicidad_id" value=""/>
                                        <input type="hidden" name="tipo" value="android"/>
                                        <input type="hidden" name="sizex" value="320"/>
                                        <input type="hidden" name="sizey" value="71"/>
                                        <input type="file" name="imagen" class="form-control imagen" required/>
                                        <button type="submit" class="btn btn-sm btn-info form-control">Cargar</button>
                                    </div>
                                </form>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6" style="padding: 20px">
                                <h5 class="text-center">720 x 144</h5>
                                <img id="android_720x144" src="http://placehold.it/320x47&text=publicidad"
                                     class="img-responsive center-block"/>
                                <br/>

                                <form enctype="multipart/form-data">
                                    <div class="form-group form-inline">
                                        <input type="hidden" class="id_publicidad" name="publicidad_id" value=""/>
                                        <input type="hidden" name="tipo" value="android"/>
                                        <input type="hidden" name="sizex" value="720"/>
                                        <input type="hidden" name="sizey" value="144"/>
                                        <input type="file" name="imagen" class="form-control imagen" required/>
                                        <button type="submit" class="btn btn-sm btn-info form-control">Cargar</button>
                                    </div>
                                </form>

                            </div>
                            <div class="col-sm-6" style="padding: 20px">
                                <h5 class="text-center">1440 x 288</h5>
                                <img id="android_1440x288" src="http://placehold.it/320x47&text=publicidad"
                                     class="img-responsive center-block"/>
                                <br/>

                                <form enctype="multipart/form-data">
                                    <div class="form-group form-inline">
                                        <input type="hidden" class="id_publicidad" name="publicidad_id" value=""/>
                                        <input type="hidden" name="tipo" value="android"/>
                                        <input type="hidden" name="sizex" value="1440"/>
                                        <input type="hidden" name="sizey" value="288"/>
                                        <input type="file" name="imagen" class="form-control imagen" required/>
                                        <button type="submit" class="btn btn-sm btn-info form-control">Cargar</button>
                                    </div>
                                </form>

                            </div>
                        </div>


                    </div>

                    <div class="col-sm-10 col-sm-offset-1">
                        <h4><i class="fa fa-apple"></i> Imágenes iOS</h4>
                    </div>

                    <div class="col-sm-10 col-sm-offset-1">
                        <!--
                            1x con 320x180
                            2x con 640x360
                            3x con 960x540
                        -->
                    <div class="row">
                        <div class="col-sm-6" style="padding: 20px">
                            <h5 class="text-center">320 x 180</h5>
                            <img id="android_240x48" src="http://placehold.it/320x180&text=publicidad"
                                 class="img-responsive center-block"/>
                            <br/>

                            <form enctype="multipart/form-data">
                                <div class="form-group form-inline">
                                    <input type="hidden" class="id_publicidad" name="publicidad_id" value=""/>
                                    <input type="hidden" name="tipo" value="ios"/>
                                    <input type="hidden" name="sizex" value="320"/>
                                    <input type="hidden" name="sizey" value="180"/>
                                    <input type="file" name="imagen" class="form-control imagen" required/>
                                    <button type="submit" class="btn btn-sm btn-info form-control">Cargar</button>
                                </div>
                            </form>

                        </div>
                        <div class="col-sm-6" style="padding: 20px">
                            <h5 class="text-center">640 x 360</h5>
                            <img id="android_320x71" src="http://placehold.it/320x180&text=publicidad"
                                 class="img-responsive center-block"/>
                            <br/>

                            <form enctype="multipart/form-data">
                                <div class="form-group form-inline">
                                    <input type="hidden" class="id_publicidad" name="publicidad_id" value=""/>
                                    <input type="hidden" name="tipo" value="ios"/>
                                    <input type="hidden" name="sizex" value="640"/>
                                    <input type="hidden" name="sizey" value="360"/>
                                    <input type="file" name="imagen" class="form-control imagen" required/>
                                    <button type="submit" class="btn btn-sm btn-info form-control">Cargar</button>
                                </div>
                            </form>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3" style="padding: 20px">
                            <h5 class="text-center">960 x 540</h5>
                            <img id="android_240x48" src="http://placehold.it/320x180&text=publicidad"
                                 class="img-responsive center-block"/>
                            <br/>

                            <form enctype="multipart/form-data">
                                <div class="form-group form-inline">
                                    <input type="hidden" class="id_publicidad" name="publicidad_id" value=""/>
                                    <input type="hidden" name="tipo" value="ios"/>
                                    <input type="hidden" name="sizex" value="960"/>
                                    <input type="hidden" name="sizey" value="540"/>
                                    <input type="file" name="imagen" class="form-control imagen" required/>
                                    <button type="submit" class="btn btn-sm btn-info form-control">Cargar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    </div>

@stop
@section('contenido2')
    <div class="col-lg-12 hidden">
        <div class="panel panel-default">
            <div class="panel-heading">Anuncios existentes</div>
            <div class="panel-body">
                <table data-toggle="table" data-url="" data-show-refresh="true" data-search="true"
                       data-show-columns="true" data-select-item-name="toolbar1" data-pagination="true"
                       data-sort-name="name" data-sort-order="desc">
                    <thead>
                    <tr>
                        <th data-field="id" data-sortable="true">ID</th>
                        <th data-field="name" data-sortable="true">Nombre anuncio</th>
                        <th data-field="price" data-sortable="true">Vistas</th>
                        <th data-field="price" data-sortable="true">ver detalle</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop
@section('pie')
    <% HTML::script('public/js/bootstrap-table.js'); %>
    <% HTML::script('public/js/sitio/publicidad.js'); %>
@stop
