@extends('sitio.master.pilar')

@section('header')
    @parent
    <% HTML::style('public/lib/bower_components/components-font-awesome/css/font-awesome.min.css'); %>
@stop

@section('titulo_plataforma')
    Dr. Clipping
@stop

@section('barra_navegacion')
    <li class="active">Catálogos</li>
    <input type="hidden" id="credencial" value="<% Session::get("credencial")%>"/>
@stop

@section('titulo')
    <h1 class="page-header">Catálogos</h1>
@stop

@section('contenido1')
    <div class="col-lg-12">
        <span class="clearfix">
            <div id="mensaje">
            </div>
            <button id="collapseNoticia" class="btn btn-success pull-right" data-toggle="collapse"
                    data-target="#divNuevoCatalogo"><b>+ Nuevo
                    catálogo</b></button>
        </span>
        <div id="divNuevoCatalogo" class="panel panel-collapse chat collapse">
            <% Form::open(array('url' => '/ws/noticias', 'id' => 'formNuevaNoticia')) %>
            <div class="row">
                <div class="col-lg-offset-2 col-lg-8">

                    <div class="form-group">
                        <label>Tiítulo</label>
                        <input id="txtTitulo" class="form-control" required/>
                    </div>

                    <table class="table text-center table-bordered">
                        <thead>
                        <tr>
                            <th class="col-lg-2">Id</th>
                            <th class="col-lg-4">Nombre</th>
                            <th class="col-lg-3">Dependiente</th>
                            <th class="col-lg-1"><button type="button" class="btn btn-default btn-sm agregar">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button></th>
                        </tr>
                        </thead>
                        <tbody id="trTablaNuevoCatalogo">
                        <tr>
                            <td>
                                <input class="form-control" id="ex1" type="number">
                            </td>
                            <td><input class="form-control" type="text"/></td>
                            <td><div class="input-group">
                                    <input type="text" class="form-control" readonly>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                                </div></td>
                            <td class="btn-toolbar">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm  btn-default eliminar">
                                        <span class="glyphicon glyphicon-minus"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <br/>
                    <br/>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-md" id="btnEnviar">Guardar</button>
                        &nbsp;&nbsp;
                        <button type="reset" class="btn btn-danger btn-md">Cancelar</button>
                    </div>

                </div>
            </div>
            <% Form::close() %>
        </div>

        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <!--<div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Collapsible Group Item #1
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                    </div>
                </div>
            </div>-->


        </div>
    </div>



@stop
@section('contenido2')
@stop
@section('pie')
    <% HTML::script('public/js/monitoreo/catalogos.js'); %>
@stop
