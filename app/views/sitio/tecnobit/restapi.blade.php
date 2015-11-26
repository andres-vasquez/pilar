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
@stop

@section('contenido1')
    <div class="col-xs-12 col-md-12 col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body tabs">

                <ul class="nav nav-pills">
                    <li class="active"><a href="#tab1" data-toggle="tab">
                            <span class="glyphicon glyphicon-comment"></span> Noticias</a></li>
                    <li><a href="#tab2" data-toggle="tab">
                            <span class="glyphicon glyphicon-bookmark"></span> Secciones</a></li>
                    <li><a href="#tab3" data-toggle="tab">
                            <span class="glyphicon glyphicon-tag"></span> Publicidad</a></li>
                    <li><a href="#tab4" data-toggle="tab">
                            <span class="glyphicon glyphicon-comment"></span> Notificaciones</a></li>
                    <li><a href="#tab5" data-toggle="tab">
                            <span class="glyphicon glyphicon-globe"></span> Portada</a></li>
                    <li><a href="#tab6" data-toggle="tab">
                            <span class="glyphicon glyphicon-film"></span> Slide Imágenes</a></li>
                    <li><a href="#tab7" data-toggle="tab">
                            <span class="glyphicon glyphicon-book"></span> Revista digital</a></li>
                    <li><a href="#tab8" data-toggle="tab">
                            <span class="glyphicon glyphicon-user"></span> Usuarios</a></li>
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

                        <br/><br/>
                        <h4>Filtrar por sección</h4>
                        <small>En cualquiera de los WS anteriores agregar el ID_SECCION antes de la credencial</small>
                        <br/>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/noticias/ID_SECCION/credencial</code>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/noticias/ID_SECCION/credencial/inicio/fin</code>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/noticias/ID_SECCION/credencial/inicio/fin/orden</code>
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
                        <code>http://pilar.cloudapp.net/pilar/api/v1/catalogos/credencial/tecnobit_secciones</code>
                        <br/>
                        <small>Ej: <a href="http://pilar.cloudapp.net/pilar/api/v1/catalogos/<% Session::get('credencial')%>/tecnobit_secciones"
                                      target="_blank">http://pilar.cloudapp.net/pilar/api/v1/catalogos/<% Session::get('credencial')%>/tecnobit_secciones</a>
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
                        <h4>Obtener anuncios específicos para dispositivo. Tipo de publicidad - SO - Tamaño x - Tamaño y</h4>

                        <p>Tipo publicidad: <span class="text-success"><b>slider, banner</b></span></p>
                        <p>Sistema operativo (SO): <span class="text-success"><b>android, ios</b></span></p>

                        <code>http://pilar.cloudapp.net/pilar/api/v1/publicidad/credencial/tipo_publicidad/tipo/sizex/sizey</code>
                        <br/>
                        <small>Ej anuncios para android con tamaño 240x48: <a
                                    href="http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>/slider/android/240/48"
                                    target="_blank">http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>/slider/android/240/48</a>
                        </small>


                        <br/><br/>
                        <h4>Obtener anuncios específicos para dispositivo. Tipo de publicidad - SO - Tamaño x - Tamaño y - Plan</h4>

                        <p>Tipo publicidad: <span class="text-success"><b>slider, banner</b></span></p>
                        <p>Sistema operativo (SO): <span class="text-success"><b>android, ios</b></span></p>
                        <p>Plan (Mayuúsculas): <span class="text-success"><b>PREMIUM, PLUS, ECONOMICO</b></span></p>

                        <code>http://pilar.cloudapp.net/pilar/api/v1/publicidad/credencial/tipo_publicidad/tipo/sizex/sizey/plan</code>
                        <br/>
                        <small>Ej anuncios para android con tamaño 240x48: <a
                                    href="http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>/slider/android/240/48/PREMIUM"
                                    target="_blank">http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>/slider/android/240/48/PREMIUM</a>
                        </small>


                        <br/><br/>
                        <h4>Obtener anuncios específicos para dispositivo. Tipo de publicidad - SO - Tamaño x - Tamaño y - Cantidad</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/publicidad/credencial/tipo_publicidad/tipo_publicidad/tipo/sizex/sizey/cantidad</code>
                        <br/>
                        <small>Ej 3 anuncios para android con tamaño 240x48 (son obtenidos según la variable prioridad):
                            <a href="http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>/slider/android/240/48/3"
                               target="_blank">http://pilar.cloudapp.net/pilar/api/v1/publicidad/<% Session::get('credencial')%>/slider/android/240/48/3</a>
                        </small>
                        <br/><br/><br/><br/>
                    </div>
                    <div class="tab-pane fade" id="tab4">
                        <h3>Notificaciones push</h3>

                        <p>Credencial: <span class="text-success"><b><% Session::get('credencial')%></b></span></p>

                        <p>La API de Notificaciones habilita a los usuarios de la App para envío de notificaciones</p>
                        <br/>
                        <h4>URL base</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/gcm</code>

                        <br/><br/>
                        <h4>Enviar Token de Google</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/gcm</code>

                        <p>Método: <span class="text-success"><b>POST</b></span></p>
                        <p>Content-type: <span class="text-success"><b>application/json</b></span></p>
                        <p>Paramétro: <span class="text-warning"><b>credencial</b></span></p>
                        <p>Paramétro: <span class="text-warning"><b>token</b></span></p>

                        <small>Ej de uso:
                            <ul>
                                <li>credencial : <b><% Session::get('credencial')%></b></li>
                                <li>token : <b>APA91bHun4MxP5egoKMwt2KZFBaFUH-1RYqx...</b></li> <small><a href="https://developers.google.com/cloud-messaging/downstream" target="_blank">https://developers.google.com/cloud-messaging/downstream</a></small>
                            </ul>
                        </small>
                        <br/>
                        <small>Resultado:
                            {"intCodigo":"1","resultado":{}}
                        </small>
                        <br/><br/>
                        <small class="text-success">Nota: Solo mandar una vez, no admite repetidos</small>
                        <br/><br/>
                    </div>
                    <div class="tab-pane fade" id="tab5">
                        <h3>Portada</h3>

                        <p>Credencial: <span class="text-success"><b><% Session::get('credencial')%></b></span></p>

                        <p>La API de portadas (Splash page) despliega información en formato JSON referente a imágenes de portada que tendrá la aplicación</p>
                        <br/>
                        <h4>URL base</h4>
                        <code>http://pilar.cloudapp.net/pilar/apitecnobit/v1/contenido/tecnobit_portada</code>

                        <br/><br/>
                        <h4>Obtener todas las imágenes de portada</h4>
                        <code>http://pilar.cloudapp.net/pilar/apitecnobit/v1/contenido/tecnobit_portada/credencial</code>
                        <br/>
                        <small>Ej: <a href="http://pilar.cloudapp.net/pilar/apitecnobit/v1/contenido/tecnobit_portada/<% Session::get('credencial')%>"
                                      target="_blank">http://pilar.cloudapp.net/pilar/apitecnobit/v1/contenido/tecnobit_portada/<% Session::get('credencial')%></a>
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
                        <code>http://pilar.cloudapp.net/pilar/apitecnobit/v1/contenido/tecnobit_slider</code>

                        <br/><br/>
                        <h4>Obtener todas las imágenes de portada</h4>
                        <code>http://pilar.cloudapp.net/pilar/apitecnobit/v1/contenido/tecnobit_slider/credencial</code>
                        <br/>
                        <small>Ej: <a href="http://pilar.cloudapp.net/pilar/apitecnobit/v1/contenido/tecnobit_slider/<% Session::get('credencial')%>"
                                      target="_blank">http://pilar.cloudapp.net/pilar/apitecnobit/v1/contenido/tecnobit_slider/<% Session::get('credencial')%></a>
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
                        <code>http://pilar.cloudapp.net/pilar/apitecnobit/v1/contenido/tecnobit_revista</code>

                        <br/><br/>
                        <h4>Obtener todas las imágenes de portada</h4>
                        <code>http://pilar.cloudapp.net/pilar/apitecnobit/v1/contenido/tecnobit_revista/credencial</code>
                        <br/>
                        <small>Ej: <a href="http://pilar.cloudapp.net/pilar/apitecnobit/v1/contenido/tecnobit_revista/<% Session::get('credencial')%>"
                                      target="_blank">http://pilar.cloudapp.net/pilar/apitecnobit/v1/contenido/tecnobit_revista/<% Session::get('credencial')%></a>
                        </small>

                        <br/><br/><br/><br/>
                    </div>
                    <div class="tab-pane fade" id="tab8">
                        <h3>Usuarios</h3>

                        <p>Credencial: <span class="text-success"><b><% Session::get('credencial')%></b></span></p>
                        <p>La API de usuarios registra y autentica a los usuarios de la app</p>

                        <br/>
                        <h4>URL base</h4>
                        <code>http://pilar.cloudapp.net/pilar/apitecnobit/v1/usuarios</code>

                        <br/>
                        <h4>Registro de usuarios al sistema</h4>
                        <code>http://pilar.cloudapp.net/pilar/apitecnobit/v1/usuarios/registrar</code>

                        <p>Método: <span class="text-success"><b>POST</b></span></p>
                        <p>Content-type: <span class="text-success"><b>application/json</b></span></p>
                        <p>Paramétro: <span class="text-warning"><b>credencial</b></span></p>
                        <p>Paramétro: <span class="text-warning"><b>nombre</b></span></p>
                        <p>Paramétro: <span class="text-warning"><b>email</b></span></p>
                        <p>Paramétro: <span class="text-warning"><b>password</b> (MD5)</span></p>
                        <p>Paramétro: <span class="text-warning"><b>id_fb</b></span></p>
                        <p>Paramétro: <span class="text-warning"><b>origen</b></span></p>

                        <small>Ej de uso:
                            <ul>
                                <li>credencial : <b><% Session::get('credencial')%></b></li>
                                <li>nombre : <b>Juan</b></li>
                                <li>email : <b>juan@yopmail.com</b></li>
                                <li>password : <b>1232</b> Vacio: Si ingresa con fb</li>
                                <li>id_fb : <b>0</b> 0: Si no entra con fb</li>
                                <li>origen : <b>android</b></li>
                            </ul>
                        </small>
                        <br/>
                        <small>Resultado:
                            {"intCodigo":"1","resultado":{id=1}} Retorna el id de usuario registrado.
                        </small>

                        <br/>
                        <br/>

                        <br/>
                        <h4>Acceso de usuarios al sistema</h4>
                        <code>http://pilar.cloudapp.net/pilar/apitecnobit/v1/usuarios/auth</code>

                        <p>Método: <span class="text-success"><b>POST</b></span></p>
                        <p>Content-type: <span class="text-success"><b>application/json</b></span></p>

                        <h4>Usuarios registrados por correo</h4>

                        <p>Paramétro: <span class="text-warning"><b>credencial</b></span></p>
                        <p>Paramétro: <span class="text-warning"><b>email</b></span></p>
                        <p>Paramétro: <span class="text-warning"><b>password</b> (MD5)</span></p>
                        <p>Paramétro: <span class="text-warning"><b>origen</b></span></p>

                        <br/>
                        <h4>Usuarios registrados por fb</h4>

                        <p>Paramétro: <span class="text-warning"><b>credencial</b></span></p>
                        <p>Paramétro: <span class="text-warning"><b>id_fb</b></span></p>
                        <p>Paramétro: <span class="text-warning"><b>origen</b></span></p>

                        <br/>
                        <small>Resultado:
                            <code>{
                              "intCodigo":"1",
                            "resultado":{
                                "datos":{
                                    "email":"andres@yopmail.com",
                                    "nombre":"Andres",
                                    "id_usuario":"1"
                            }}}</code>
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
