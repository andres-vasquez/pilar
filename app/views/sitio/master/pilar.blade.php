<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feicobol - Dashboard</title>

    <% HTML::style('public/css/bootstrap.min.css'); %>
    <% HTML::style('public/css/datepicker3.css'); %>
    <% HTML::style('public/css/styles.css'); %>

    <!--[if lt IE 9]>
    <% HTML::script('public/js/html5shiv.js'); %>
    <% HTML::script('public/js/respond.min.js'); %>
    <![endif]-->
    @yield('header')
</head>

<body>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#sidebar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><span>PILAR - </span> FEICOBOL</a>

            <ul class="user-menu">
                <li class="dropdown pull-right">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span
                                class="glyphicon glyphicon-user"></span> Usuario <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <!--<li><a href="#"><span class="glyphicon glyphicon-user"></span> Usuario</a></li>
                        <li><a href="#"><span class="glyphicon glyphicon-cog"></span> Opciones</a></li>-->
                        <li><a href="<% url('/logout') %>"><span class="glyphicon glyphicon-log-out"></span> Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="user-menu">
                <li class="dropdown pull-right">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span
                                class="glyphicon glyphicon-th"></span> Sistemas <span class="caret"></span>&nbsp;&nbsp;
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        @foreach ($data["sistemas"] as $sistema)
                            <li>
                                <a href="<% url('/'.$sistema) %>"><span class="glyphicon glyphicon-home"></span> <% $sistema %></a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>

        </div>
    </div>
    <!-- /.container-fluid -->
</nav>

<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
    <ul class="nav menu">
        <li role="presentation" class="divider"></li>
        @if (count($data["menus"])>0)
            @for ($i = 0; $i < count($data["menus"]); $i++)
                @if('/'.Request::path()==$data["menus"][$i]["menus"]["link"])
                    <li class="active">
                @else
                    <li>
                        @endif
                        <a href="<% url($data["menus"][$i]["menus"]["link"]) %>"><span class="glyphicon <% $data["menus"][$i]["menus"]["icono"]%>"></span> <% $data["menus"][$i]["menus"]["titulo"] %></a>
                    </li>
                    @endfor
                @endif

                <li role="presentation" class="divider"></li>
                <li><a href="<% url('/logout') %>"><span class="glyphicon glyphicon-log-out"></span> Cerrar sesión</a></li>
    </ul>
    <div class="attribution"><a href="#">PILAR</a><br/> Plataforma Integral Líder en Administración REST</div>
</div>
<!--/.sidebar-->

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li>
                <a href="<% url('/') %>"><span class="glyphicon glyphicon-home"></span></a>
            </li>
            @section('barra_navegacion')
            @show
        </ol>
    </div>
    <!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            @section('titulo')
            @show
        </div>
    </div>
    <!--/.row-->

    <div class="row">
        @section('contenido1')
        @show
    </div>
    <!--/.row-->

    <div class="row">
        @section("contenido2")
        @show
    </div>
    <!--/.row-->

</div>
<!--/.main-->

<% HTML::script('public/js/jquery-1.11.1.min.js'); %>
<% HTML::script('public/js/bootstrap.min.js'); %>
<% HTML::script('public/js/easypiechart.js'); %>
<% HTML::script('public/js/bootstrap-datepicker.js'); %>
@yield('pie')
<script>
    $('#calendar').datepicker({});

    !function ($) {
        $(document).on("click", "ul.nav li.parent > a > span.icon", function () {
            $(this).find('em:first').toggleClass("glyphicon-minus");
        });
        $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
    }(window.jQuery);

    $(window).on('resize', function () {
        if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
    })
    $(window).on('resize', function () {
        if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
    })
</script>
</body>

</html>
