@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
@stop

@section('titulo_plataforma')
    TecnoBit
@stop

@section('barra_navegacion')
    <li class="active">REST API</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
@stop

@section('titulo')
    <h1 class="page-header">REST API v1.0</h1>
    <h3 class="alert-danger"><li class="glyphicon glyphicon-music"></li> Hi Andrew</h3>
@stop

@section('contenido1')
    <div class="col-xs-12 col-md-12 col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body tabs">

                <ul class="nav nav-pills">
                    <li class="active"><a href="#tab1" data-toggle="tab">
                            <span class="glyphicon glyphicon-comment"></span> Noticias</a></li>
                    <li><a href="#tab2" data-toggle="tab">
                            <span class="glyphicon glyphicon-tag"></span> Secciones</a></li>
                    <li><a href="#tab3" data-toggle="tab">
                            <span class="glyphicon glyphicon-tag"></span> Publicidad</a></li>
                    <li><a href="#tab4" data-toggle="tab">
                            <span class="glyphicon glyphicon-user"></span> Notificaciones</a></li>
                    <li><a href="#tab5" data-toggle="tab">
                            <span class="glyphicon glyphicon-globe"></span> Portada</a></li>
                    <li><a href="#tab6" data-toggle="tab">
                            <span class="glyphicon glyphicon-thumbs-up"></span> Slide Imágenes</a></li>
                    <li><a href="#tab7" data-toggle="tab">
                            <span class="glyphicon glyphicon-thumbs-up"></span> Revista digital</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab1">
                        <h3>Noticias</h3>

                        <p>#Credencial: <span class="text-success"><b><% Session::get('credencial')%></b></span></p>

                        <p>La API de noticias despliega información en formato JSON de noticias a dispositivos
                            móviles</p>
                        <br/>
                        <h4>URL base</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/noticias</code>

                        <br/><br/>
                        <h4>Obtener todas las noticias</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/noticias/credencial</code>
                        <br/>
                        <small>Ej: <a href="http://pilar.cloudapp.net/pilar/api/v1/noticias/<% Session::get('credencial')%>"
                                      target="_blank">http://pilar.cloudapp.net/pilar/api/v1/noticias/<% Session::get('credencial')%></a>
                        </small>

                        <br/><br/>
                        <h4>Obtener noticias en un rango: Inicio - Fin</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/noticias/credencial/inicio/fin</code>
                        <br/>
                        <small>Ej Las 5 primeras noticias: <a
                                    href="http://pilar.cloudapp.net/pilar/api/v1/noticias/<% Session::get('credencial')%>/1/5"
                                    target="_blank">http://pilar.cloudapp.net/pilar/api/v1/noticias/<% Session::get('credencial')%>/1/5</a>
                        </small>

                        <br/><br/>
                        <h4>Obtener noticias en un rango: Inicio - Fin con orden (ASC o DESC)</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/noticias/credencial/inicio/fin/orden</code>
                        <br/>
                        <small>Ej Las 5 primeras noticias la más antigua adelante: <a
                                    href="http://pilar.cloudapp.net/pilar/api/v1/noticias/<% Session::get('credencial')%>/1/5/ASC"
                                    target="_blank">http://pilar.cloudapp.net/pilar/api/v1/noticias/<% Session::get('credencial')%>/1/5/ASC</a>
                        </small>
                        <br/><br/><br/><br/>
                    </div>
                    <div class="tab-pane fade" id="tab2">
                        <h3>Secciones de la revista</h3>

                        <p>Credencial: <span class="text-success"><b><% Session::get('credencial')%></b></span></p>

                        <p>La API de secciones despliega información en formato JSON referente a las secciones de la revista
                            hacia dispositivos móviles</p>
                        <br/>
                        <h4>URL base</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/catalogos</code>

                        <br/><br/>
                        <h4>Obtener las secciones</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/catalogos/credencial/secciones_tecnobit</code>
                        <br/>
                        <small>Ej: <a href="http://pilar.cloudapp.net/pilar/api/v1/catalogos/<% Session::get('credencial')%>/secciones_tecnobit"
                                      target="_blank">http://pilar.cloudapp.net/pilar/api/v1/catalogos/<% Session::get('credencial')%>/secciones_tecnobit</a>
                        </small>

                        <br/><br/><br/><br/>
                    </div>
                    <div class="tab-pane fade" id="tab3">
                        <h3>Publicidad</h3>

                        <p>Credencial: <span class="text-success"><b><% Session::get('credencial')%></b></span></p>

                        <p>La API de publicidad despliega información en formato JSON referente a anuncios y banners
                            hacia dispositivos móviles</p>
                        <br/>
                        <h4>URL base</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/publicidad</code>

                        <br/><br/>
                        <h4>Obtener todos los anuncios</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/publicidad/credencial</code>
                        <br/>
                        <small>Ej: <a href="http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>"
                                      target="_blank">http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%></a>
                        </small>

                        <br/><br/>
                        <h4>Obtener anuncios específicos para dispositivo. SO - Tamaño x - Tamaño y</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/publicidad/credencial/tipo/sizex/sizey</code>
                        <br/>
                        <small>Ej anuncios para android con tamaño 240x48: <a
                                    href="http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>/android/240/48"
                                    target="_blank">http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>/android/240/48</a>
                        </small>

                        <br/><br/>
                        <h4>Obtener anuncios específicos para dispositivo. SO - Tamaño x - Tamaño y - Cantidad</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/publicidad/credencial/tipo/sizex/sizey/cantidad</code>
                        <br/>
                        <small>Ej 3 anuncios para android con tamaño 240x48 (son obtenidos según la variable prioridad):
                            <a href="http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>/android/240/48/3"
                               target="_blank">http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>/android/240/48/3</a>
                        </small>
                        <br/><br/><br/><br/>
                    </div>
                    <div class="tab-pane fade" id="tab4">
                        <h3>Notificaciones push</h3>

                        <p>Aun por definir</p>
                        <br/><br/><br/><br/>
                    </div>
                    <div class="tab-pane fade" id="tab5">
                        <h3>Portada</h3>

                        <p>Credencial: <span class="text-success"><b><% Session::get('credencial')%></b></span></p>

                        <p>La API de portadas (Splash page) despliega información en formato JSON referente a imágenes de portada que tendrá la aplicación</p>
                        <br/>
                        <h4>URL base</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/publicidad</code>

                        <br/><br/>
                        <h4>Obtener todas las imágenes de portada</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/publicidad/credencial</code>
                        <br/>
                        <small>Ej: <a href="http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>"
                                      target="_blank">http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%></a>
                        </small>

                        <br/><br/>
                        <h4>Obtener imágenes de portada específicas para dispositivo. SO - Tamaño x - Tamaño y</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/publicidad/credencial/tipo/sizex/sizey</code>
                        <br/>
                        <small>Ej anuncios para android con tamaño 240x48: <a
                                    href="http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>/android/240/48"
                                    target="_blank">http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>/android/240/48</a>
                        </small>
                        <br/><br/><br/><br/>

                    </div>
                    <div class="tab-pane fade" id="tab6">
                        <h3>Slide Imágenes</h3>

                        <p>Credencial: <span class="text-success"><b><% Session::get('credencial')%></b></span></p>

                        <p>La API de Slides de imágenes despliega información en formato JSON referente a imágenes que serán mostradas en
                            dispositivos móviles</p>
                        <br/>
                        <h4>URL base</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/publicidad</code>

                        <br/><br/>
                        <h4>Obtener todos los Slides de imágenes</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/publicidad/credencial</code>
                        <br/>
                        <small>Ej: <a href="http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>"
                                      target="_blank">http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%></a>
                        </small>

                        <br/><br/>
                        <h4>Obtener Slides de imágenes específicos para dispositivo. SO - Tamaño x - Tamaño y</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/publicidad/credencial/tipo/sizex/sizey</code>
                        <br/>
                        <small>Ej anuncios para android con tamaño 240x48: <a
                                    href="http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>/android/240/48"
                                    target="_blank">http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>/android/240/48</a>
                        </small>

                        <br/><br/>
                        <h4>Obtener Slides de imágenes específicos para dispositivo. SO - Tamaño x - Tamaño y - Cantidad</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/publicidad/credencial/tipo/sizex/sizey/cantidad</code>
                        <br/>
                        <small>Ej 3 Slides de imágenes para android con tamaño 240x48 (son obtenidos según la variable prioridad):
                            <a href="http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>/android/240/48/3"
                               target="_blank">http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>/android/240/48/3</a>
                        </small>
                        <br/><br/><br/><br/>
                    </div>
                    <div class="tab-pane fade" id="tab7">
                        <h3>Revista digital</h3>

                        <p>Credencial: <span class="text-success"><b><% Session::get('credencial')%></b></span></p>

                        <p>La API de revista digital despliega información en formato JSON referente a revistas cargadas a la plataforma PILAR en formato PDF
                            hacia dispositivos móviles</p>
                        <br/>
                        <h4>URL base</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/publicidad</code>


                        <br/><br/>
                        <h4>Obtener todas las revistas subidas</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/noticias/credencial</code>
                        <br/>
                        <small>Ej: <a href="http://pilar.cloudapp.net/pilar/api/v1/noticias/<% Session::get('credencial')%>"
                                      target="_blank">http://pilar.cloudapp.net/pilar/api/v1/noticias/<% Session::get('credencial')%></a>
                        </small>
                        <br/><br/><br/><br/>
                    </div>
                </div>
            </div>
        </div>
        <!--/.panel-->
    </div><!-- /.col-->

    </div>
@stop
@section('contenido2')
@stop
@section('pie')
    <% HTML::script('public/js/sitio/dashboard.js'); %>
@stop
