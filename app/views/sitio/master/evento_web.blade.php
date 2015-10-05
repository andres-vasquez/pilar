
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PILAR - EVENTOS</title>

    <% HTML::style('public/css/bootstrap.min.css'); %>
    <% HTML::style('public/css/styles.css'); %>
    <!--[if lt IE 9]>
    <% HTML::script('public/js/html5shiv.js'); %>
    <% HTML::script('public/js/respond.min.js'); %>
    <![endif]-->
</head>

<body style="padding-top: 0px">
<!--<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#"><span>PILAR - </span> LECTOR DE NOTICIAS</a>
        </div>
    </div>
</nav>-->
<img class="img-responsive" src="<% $resultado["datos"]["imagen_aws"]%>" style="width: 100%"/>
<div class="col-sm-8 col-sm-offset-2 col-lg-8 col-lg-offset-2 main">
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <h2><% $resultado["datos"]["nombre"]%></h2>
            </ol>
            <br/>
            <br/><br/>
            <% $resultado["datos"]["html"]; %>
            <br/>
            <small>Lugar: <cite title="Lugar de evento"><% $resultado["datos"]["lugar"]%></cite></small>
            <br/><br/>
            <small>Fecha del evento: <cite title="Fecha de evento"><% date('d-m-Y H:i',strtotime($resultado["datos"]["fecha_inicio"]));%></cite></small>
            <br/><br/>
        </div>
    </div>
</div>
<!--/.main-->

<% HTML::script('public/js/jquery-1.11.1.min.js'); %>
<% HTML::script('public/js/bootstrap.min.js'); %>
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
