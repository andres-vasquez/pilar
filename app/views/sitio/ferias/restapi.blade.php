@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
@stop

@section('titulo_plataforma')
    <% Session::get("nombre_sistema")%>
@stop

@section('barra_navegacion')
    <li class="active">REST API</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
@stop

@section('titulo')
    <h1 class="page-header">REST API v1.0</h1>
@stop

@section('contenido1')
    <div class="col-xs-12 col-md-12 col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body tabs">

                <ul class="nav nav-pills">
                    <li class="active"><a href="#tab1" data-toggle="tab">
                            <span class="glyphicon glyphicon-comment"></span> Noticias</a></li>
                    <li><a href="#tab2" data-toggle="tab">
                            <span class="glyphicon glyphicon-tag"></span> Publicidad</a></li>
                    <li><a href="#tab3" data-toggle="tab">
                            <span class="glyphicon glyphicon-user"></span> Expositores</a></li>
                    <li><a href="#tab4" data-toggle="tab">
                            <span class="glyphicon glyphicon-globe"></span> Mapas</a></li>
                    <li><a href="#tab5" data-toggle="tab">
                            <span class="glyphicon glyphicon-thumbs-up"></span> Likes</a></li>
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
                        <small>Ej: <a href="http://pilar.cloudapp.net/pilar/api/v1/noticias/o0wXYg03Y8KEM7o"
                                      target="_blank">http://pilar.cloudapp.net/pilar/api/v1/noticias/o0wXYg03Y8KEM7o</a>
                        </small>

                        <br/><br/>
                        <h4>Obtener noticias en un rango: Inicio - Fin</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/noticias/credencial/inicio/fin</code>
                        <br/>
                        <small>Ej Las 5 primeras noticias: <a
                                    href="http://pilar.cloudapp.net/pilar/api/v1/noticias/o0wXYg03Y8KEM7o/1/5"
                                    target="_blank">http://pilar.cloudapp.net/pilar/api/v1/noticias/o0wXYg03Y8KEM7o/1/5</a>
                        </small>

                        <br/><br/>
                        <h4>Obtener noticias en un rango: Inicio - Fin con orden (ASC o DESC)</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/noticias/credencial/inicio/fin/orden</code>
                        <br/>
                        <small>Ej Las 5 primeras noticias la más antigua adelante: <a
                                    href="http://pilar.cloudapp.net/pilar/api/v1/noticias/o0wXYg03Y8KEM7o/1/5/ASC"
                                    target="_blank">http://pilar.cloudapp.net/pilar/api/v1/noticias/o0wXYg03Y8KEM7o/1/5/ASC</a>
                        </small>
                        <br/><br/><br/><br/>
                    </div>
                    <div class="tab-pane fade" id="tab2">
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
                        <small>Ej: <a href="http://pilar.cloudapp.net/pilar/api/v1/publicidad/o0wXYg03Y8KEM7o"
                                      target="_blank">http://pilar.cloudapp.net/pilar/api/v1/publicidad/o0wXYg03Y8KEM7o</a>
                        </small>

                        <br/><br/>
                        <h4>Obtener anuncios específicos para dispositivo. SO - Tamaño x - Tamaño y</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/publicidad/credencial/tipo/sizex/sizey</code>
                        <br/>
                        <small>Ej anuncios para android con tamaño 240x48: <a
                                    href="http://pilar.cloudapp.net/pilar/api/v1/publicidad/o0wXYg03Y8KEM7o/android/240/48"
                                    target="_blank">http://pilar.cloudapp.net/pilar/api/v1/publicidad/o0wXYg03Y8KEM7o/android/240/48</a>
                        </small>

                        <br/><br/>
                        <h4>Obtener anuncios específicos para dispositivo. SO - Tamaño x - Tamaño y - Cantidad</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/publicidad/credencial/tipo/sizex/sizey/cantidad</code>
                        <br/>
                        <small>Ej 3 anuncios para android con tamaño 240x48 (son obtenidos según la variable prioridad):
                            <a href="http://pilar.cloudapp.net/pilar/api/v1/publicidad/o0wXYg03Y8KEM7o/android/240/48/3"
                               target="_blank">http://pilar.cloudapp.net/pilar/api/v1/publicidad/o0wXYg03Y8KEM7o/android/240/48/3</a>
                        </small>
                        <br/><br/><br/><br/>
                    </div>
                    <div class="tab-pane fade" id="tab3">
                        <h3>Expositores</h3>

                        <p>Credencial: <span class="text-success"><b><% Session::get('credencial')%></b></span></p>

                        <p>La API de expositores despliega información en formato JSON referente a los expositores</p>
                        <br/>
                        <h4>URL base</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/expositores</code>

                        <br/><br/>
                        <h4>Obtener todos los expositores</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/expositores/credencial</code>
                        <br/>
                        <small>Ej: <a href="http://pilar.cloudapp.net/pilar/api/v1/expositores/o0wXYg03Y8KEM7o"
                                      target="_blank">http://pilar.cloudapp.net/pilar/api/v1/expositores/o0wXYg03Y8KEM7o</a>
                        </small>
                        <br/><br/><br/><br/>
                    </div>
                    <div class="tab-pane fade" id="tab4">
                        <h3>Mapas</h3>

                        <p>Credencial: <span class="text-success"><b><% Session::get('credencial')%></b></span></p>
                        <br/><br/>

                        <p>La API de Mapa despliega las capas mostradas en la interfaz web</p>
                        <br/>
                        <h4>URL base</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/mapas/credencial</code>

                        <br/>
                        <small>Ej: <a href="http://pilar.cloudapp.net/pilar/api/v1/mapas/o0wXYg03Y8KEM7o"
                                      target="_blank">http://pilar.cloudapp.net/pilar/api/v1/mapas/o0wXYg03Y8KEM7o</a>
                        </small>

                        <small>
                            <br/><br/><br/>
                            Datos de respuesta:
                            <br/><br/>
                            <ul>
                                <li>Marcador: coordenada lat y lon</li>
                                <li>Círculo: coordenada lat y lon del centro y un dato para radio</li>
                                <li>Polígono: coordenadas lat y lon de todos los vertices del polígono</li>
                                <li>Rectángulo: coordenadas lat y lon del punto Noreste y punto Sudoeste</li>
                            </ul>
                            <br/>
                            Adicionalmente despliega atributos de color de línea e ícono si corresponde.
                        </small>

                        <br/><br/><br/><br/>
                    </div>

                    <!-- Likes Feicobol-->
                    @if (Session::get("nombre_sistema") === "feicobol")
                        <div class="tab-pane fade" id="tab5">
                            <h3>Likes</h3>

                            <p>Credencial: <span class="text-success"><b><% Session::get('credencial')%></b></span></p>
                            <br/><br/>

                            <p>La API de LIKE ingresa datos para votación de Expositores</p>
                            <br/>
                            <h4>URL base</h4>
                            <code>http://pilar.cloudapp.net/pilar/api/v1/expositoreslikes</code>

                            <br/><br/>
                            <h4>Enviar voto</h4>
                            <code>http://pilar.cloudapp.net/pilar/api/v1/expositoreslikes</code>

                            <p>Método: <span class="text-success"><b>POST</b></span></p>
                            <p>Content-type: <span class="text-success"><b>application/json</b></span></p>
                            <p>Paramétro: <span class="text-warning"><b>sistema</b></span></p>
                            <p>Paramétro: <span class="text-warning"><b>expositor_id</b></span></p>
                            <p>Parámetro: <span class="text-warning"><b>imei</b></span></p>

                            <small>Ej de uso:
                                <ul>
                                    <li>sistema : <b><% Session::get('credencial')%></b></li>
                                    <li>expositor_id : <b>273</b></li>
                                    <li>imei : <b>12315123</b></li>
                                </ul>
                            </small>
                            <br/>
                            <small>Resultado:
                                {"intCodigo":"1","resultado":{"id":#id Insertado}}
                            </small>

                        </div>
                    @endif
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
