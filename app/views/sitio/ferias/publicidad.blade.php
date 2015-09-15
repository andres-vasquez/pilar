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

        <div id="divNuevoAnuncio" class="panel panel-collapse chat collapse">

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
                                <label for="cmbPrioridad" class="col-sm-4 control-label">Orden</label>

                                <div class="col-sm-8">
                                    <select id="cmbPrioridad" class="form-control">
                                        <option value="1">1 - Primero en mostrar</option>
                                        <option value="2">2 - Segundo en mostrar</option>
                                        <option value="3">3 - Tercero en mostrar</option>
                                        <option value="4">4 - Cuarto en mostrar</option>
                                        <option value="5">5 - Quinto en mostrar</option>
                                        <option value="6">6 - en mostrar</option>
                                        <option value="7">7 - en mostrar</option>
                                        <option value="8">8 - en mostrar</option>
                                        <option value="9">9 - en mostrar</option>
                                        <option value="10">10 - en mostrar</option>
                                        <option value="11">11 - en mostrar</option>
                                        <option value="12">12 - en mostrar</option>
                                        <option value="13">13 - en mostrar</option>
                                        <option value="14">14 - en mostrar</option>
                                        <option value="15">15 - en mostrar</option>
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
                                        <input type="file" name="imagen" class="form-control imagen" required/>
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
                                <img id="ios_320x180" src="http://placehold.it/320x180&text=publicidad"
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
                                <img id="ios_640x360" src="http://placehold.it/320x180&text=publicidad"
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
                                <img id="ios_960x540" src="http://placehold.it/320x180&text=publicidad"
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

    <!-- Modal eliminar -->
    <div class="modal fade" id="eliminarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Eliminar noticia</h4>
                </div>
                <div class="modal-body">
                    Está seguro de eliminar la publicidad seleccionada?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnEliminarPublicidad">Si,
                        eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal editar -->
    <div class="modal modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Editar publicidad</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="txtNombre_editar" class="col-sm-4 control-label">Nombre del anuncio</label>

                        <div class="col-sm-8">
                            <input id="txtNombre_editar" class="form-control" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="txtDescripcion_editar" class="col-sm-4 control-label">Descripción del
                            anuncio</label>

                        <div class="col-sm-8">
                            <textarea id="txtDescripcion_editar" class="form-control" required></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="txtUrlAnuncio_editar" class="col-sm-4 control-label">Url detalle del
                            anuncio</label>

                        <div class="col-sm-8">
                            <input id="txtUrlAnuncio_editar" class="form-control" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cmbPrioridad_editar" class="col-sm-4 control-label">Orden</label>

                        <div class="col-sm-8">
                            <select id="cmbPrioridad_editar" class="form-control">
                                <option value="1">1 - Primero en mostrar</option>
                                <option value="2">2 - Segundo en mostrar</option>
                                <option value="3">3 - Tercero en mostrar</option>
                                <option value="4">4 - Cuarto en mostrar</option>
                                <option value="5">5 - Quinto en mostrar</option>
                                <option value="6">6 - en mostrar</option>
                                <option value="7">7 - en mostrar</option>
                                <option value="8">8 - en mostrar</option>
                                <option value="9">9 - en mostrar</option>
                                <option value="10">10 - en mostrar</option>
                                <option value="11">11 - en mostrar</option>
                                <option value="12">12 - en mostrar</option>
                                <option value="13">13 - en mostrar</option>
                                <option value="14">14 - en mostrar</option>
                                <option value="15">15 - en mostrar</option>
                            </select>
                        </div>
                    </div>

                    <div id="divImagenes">
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
                                        <img id="android_240x48_editar" src="http://placehold.it/320x47&text=publicidad"
                                             class="img-responsive center-block"/>
                                        <br/>

                                        <form id="f1" class="editar" enctype="multipart/form-data">
                                            <div class="form-group form-inline">
                                                <input type="hidden" class="id_publicidad" name="publicidad_id"
                                                       value=""/>
                                                <input type="hidden" name="id_imagen" value="android"/>
                                                <input type="hidden" name="tipo" value="android"/>
                                                <input type="hidden" name="sizex" value="240"/>
                                                <input type="hidden" name="sizey" value="48"/>
                                                <input type="file" name="imagen" class="form-control imagen" required/>
                                                <button type="submit" class="btn btn-sm btn-info form-control">Cargar
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                    <div class="col-sm-6" style="padding: 20px">
                                        <h5 class="text-center">320 x 71</h5>
                                        <img id="android_320x71_editar" src="http://placehold.it/320x47&text=publicidad"
                                             class="img-responsive center-block"/>
                                        <br/>

                                        <form id="f2" class="editar" enctype="multipart/form-data">
                                            <div class="form-group form-inline">
                                                <input type="hidden" class="id_publicidad" name="publicidad_id"
                                                       value=""/>
                                                <input type="hidden" name="id_imagen" value="android"/>
                                                <input type="hidden" name="tipo" value="android"/>
                                                <input type="hidden" name="sizex" value="320"/>
                                                <input type="hidden" name="sizey" value="71"/>
                                                <input type="file" name="imagen" class="form-control imagen" required/>
                                                <button type="submit" class="btn btn-sm btn-info form-control">Cargar
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6" style="padding: 20px">
                                        <h5 class="text-center">720 x 144</h5>
                                        <img id="android_720x144_editar"
                                             src="http://placehold.it/320x47&text=publicidad"
                                             class="img-responsive center-block"/>
                                        <br/>

                                        <form id="f3" class="editar" enctype="multipart/form-data">
                                            <div class="form-group form-inline">
                                                <input type="hidden" class="id_publicidad" name="publicidad_id"
                                                       value=""/>
                                                <input type="hidden" name="id_imagen" value="android"/>
                                                <input type="hidden" name="tipo" value="android"/>
                                                <input type="hidden" name="sizex" value="720"/>
                                                <input type="hidden" name="sizey" value="144"/>
                                                <input type="file" name="imagen" class="form-control imagen" required/>
                                                <button type="submit" class="btn btn-sm btn-info form-control">Cargar
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                    <div class="col-sm-6" style="padding: 20px">
                                        <h5 class="text-center">1440 x 288</h5>
                                        <img id="android_1440x288_editar"
                                             src="http://placehold.it/320x47&text=publicidad"
                                             class="img-responsive center-block"/>
                                        <br/>

                                        <form id="f4" class="editar" enctype="multipart/form-data">
                                            <div class="form-group form-inline">
                                                <input type="hidden" class="id_publicidad" name="publicidad_id"
                                                       value=""/>
                                                <input type="hidden" name="id_imagen" value="android"/>
                                                <input type="hidden" name="tipo" value="android"/>
                                                <input type="hidden" name="sizex" value="1440"/>
                                                <input type="hidden" name="sizey" value="288"/>
                                                <input type="file" name="imagen" class="form-control imagen" required/>
                                                <button type="submit" class="btn btn-sm btn-info form-control">Cargar
                                                </button>
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
                                        <img id="ios_320x180_editar" src="http://placehold.it/320x180&text=publicidad"
                                             class="img-responsive center-block"/>
                                        <br/>

                                        <form id="f5" class="editar" enctype="multipart/form-data">
                                            <div class="form-group form-inline">
                                                <input type="hidden" class="id_publicidad" name="publicidad_id"
                                                       value=""/>
                                                <input type="hidden" name="id_imagen" value="android"/>
                                                <input type="hidden" name="tipo" value="ios"/>
                                                <input type="hidden" name="sizex" value="320"/>
                                                <input type="hidden" name="sizey" value="180"/>
                                                <input type="file" name="imagen" class="form-control imagen" required/>
                                                <button type="submit" class="btn btn-sm btn-info form-control">Cargar
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                    <div class="col-sm-6" style="padding: 20px">
                                        <h5 class="text-center">640 x 360</h5>
                                        <img id="ios_640x360_editar" src="http://placehold.it/320x180&text=publicidad"
                                             class="img-responsive center-block"/>
                                        <br/>

                                        <form id="f6" class="editar" enctype="multipart/form-data">
                                            <div class="form-group form-inline">
                                                <input type="hidden" class="id_publicidad" name="publicidad_id"
                                                       value=""/>
                                                <input type="hidden" name="id_imagen" value="android"/>
                                                <input type="hidden" name="tipo" value="ios"/>
                                                <input type="hidden" name="sizex" value="640"/>
                                                <input type="hidden" name="sizey" value="360"/>
                                                <input type="file" name="imagen" class="form-control imagen" required/>
                                                <button type="submit" class="btn btn-sm btn-info form-control">Cargar
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 col-sm-offset-3" style="padding: 20px">
                                        <h5 class="text-center">960 x 540</h5>
                                        <img id="ios_960x540_editar" src="http://placehold.it/320x180&text=publicidad"
                                             class="img-responsive center-block"/>
                                        <br/>

                                        <form id="f7" class="editar" enctype="multipart/form-data">
                                            <div class="form-group form-inline">
                                                <input type="hidden" class="id_publicidad" name="publicidad_id"
                                                       value=""/>
                                                <input type="hidden" name="id_imagen" value="android"/>
                                                <input type="hidden" name="tipo" value="ios"/>
                                                <input type="hidden" name="sizex" value="960"/>
                                                <input type="hidden" name="sizey" value="540"/>
                                                <input type="file" name="imagen" class="form-control imagen" required/>
                                                <button type="submit" class="btn btn-sm btn-info form-control">Cargar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnEditarPublicidad">Guardar
                        cambios
                    </button>
                </div>
            </div>
        </div>
    </div>


@stop
@section('contenido2')
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Anuncios existentes</div>
            <div class="panel-body">
                <table id="tblPublicidad" data-toggle="table"
                       data-url="<% '../api/v1/publicidad/'.Session::get("credencial").'/sinformato' %>"
                       data-show-refresh="true" data-search="true"
                       data-show-columns="true" data-select-item-name="toolbar1" data-pagination="true"
                       data-sort-name="name" data-sort-order="desc">
                    <thead>
                    <tr>
                        <th data-field="id" data-sortable="true">ID</th>
                        <th data-field="nombre" data-sortable="true">Nombre anuncio</th>
                        <th data-field="link" data-sortable="true">Link</th>
                        <th data-field="prioridad" data-halign="center" data-sortable="true">Prioridad</th>
                        <th data-field="fecha_creacion" data-sortable="true">Fecha</th>
                        <th data-field="operate" data-halign="center" data-formatter="operateFormatter"
                            data-events="operateEvents">Acciones
                        </th>
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
