@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
@stop

@section('titulo_plataforma')
    Moto Club La Paz
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
                            <span class="glyphicon glyphicon-calendar"></span> Eventos</a></li>
                    <li><a href="#tab2" data-toggle="tab">
                            <span class="glyphicon glyphicon-user"></span> Usuarios</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab1">
                        <h3>Eventos</h3>

                        <p>Credencial: <span class="text-success"><b><% Session::get('credencial')%></b></span></p>

                        <p>La API de eventos despliega información en formato JSON referente a los eventos de la feria</p>
                        <br/>
                        <h4>URL base</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/eventos</code>

                        <br/><br/>
                        <h4>Obtener todos los eventos</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/eventos/credencial</code>
                        <br/>
                        <small>Ej: <a href="http://pilar.cloudapp.net/pilar/api/v1/eventos/<% Session::get('credencial')%>"
                                      target="_blank">http://pilar.cloudapp.net/pilar/api/v1/eventos/<% Session::get('credencial')%></a>
                        </small>
                        <br/><br/>
                        <h4>Obtener los eventos a partir de un día específico</h4>
                        <code>http://pilar.cloudapp.net/pilar/api/v1/eventos/credencial/fecha</code>
                        <br/><br/>
                        <p>Fecha: (dd-MM-yyyy)<span class="text-success"><b>08-10-2015</b></span></p>

                        <small>Ej: <a href="http://pilar.cloudapp.net/pilar/api/v1/eventos/<% Session::get('credencial')%>/08-10-2015"
                                      target="_blank">http://pilar.cloudapp.net/pilar/api/v1/eventos/<% Session::get('credencial')%>/08-10-2015</a>
                        </small>
                        <br/><br/><br/><br/>
                    </div>
                    <div class="tab-pane fade" id="tab2">
                        <h3>Usuarios</h3>

                        <p>Credencial: <span class="text-success"><b><% Session::get('credencial')%></b></span></p>
                        <p>La API de usuarios autentica a los usuarios de la app</p>

                        <br/>
                        <h4>URL base</h4>
                        <code>http://pilar.cloudapp.net/pilar/apimotoclublapaz/v1/usuarios</code>
                        <br/><br/>
                        <br/>
                        <h4>Acceso de usuarios al sistema</h4>
                        <code>http://pilar.cloudapp.net/pilar/apimotoclublapaz/v1/usuarios/auth</code>

                        <p>Método: <span class="text-success"><b>POST</b></span></p>
                        <p>Content-type: <span class="text-success"><b>application/json</b></span></p>

                        <h4>Usuarios registrados por correo</h4>

                        <p>Paramétro: <span class="text-warning"><b>credencial</b></span></p>
                        <p>Paramétro: <span class="text-warning"><b>email</b></span></p>
                        <p>Paramétro: <span class="text-warning"><b>ci</b> (MD5)</span></p>
                        <p>Paramétro: <span class="text-warning"><b>origen</b> <small>(android o ios)</small></span></p>

                        <br/>

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
