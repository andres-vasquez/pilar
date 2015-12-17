
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PILAR - NOTICIAS</title>

    <% HTML::style('public/css/bootstrap.min.css'); %>
    <% HTML::style('public/css/styles.css'); %>

    <!--[if lt IE 9]>
    <% HTML::script('public/js/html5shiv.js'); %>
    <% HTML::script('public/js/respond.min.js'); %>
    <![endif]-->

    <% HTML::style('public/fonts/material/fonts.css'); %>
    <style>
        .ribon-white-top{
            position:relative;
            /*top: -84px; /* Aqui cambias la posicion del triangulo blanco de abajo */
        }
        .rib{
            fill: #fff;
        }
    </style>
</head>

<body style="padding-top: 0px">
<!--<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#"><span>PILAR - </span> LECTOR DE NOTICIAS</a>
        </div>
    </div>
</nav>-->
<img id="imagen" class="img-responsive" src="<% $resultado["datos"]["url_imagen"]%>" style="width: 100%"/>

@if ($resultado["datos"]["sistema_id"] == 6)
<svg id="esquina" class="ribon-white-top" xmlns="http://www.w3.org/2000/svg" width="100%" height="100" viewBox="0 0 100 100" preserveAspectRatio="none">
    <path class="rib" d="M0 100 L100 100 L0 0 Z" stroke-width="0"/>
</svg>
@endif

<div class="col-sm-8 col-sm-offset-2 col-lg-8 col-lg-offset-2 main">
    <div class="row">
        <div class="col-lg-12">
                <h2><% $resultado["datos"]["titular"]%></h2>
            <cite style="font-family: 'Noto Serif" title=""><% date('d/m/Y',strtotime($resultado["datos"]["created_at"]));%></cite>
            <br/><br/>
            <div style="width: 90%; font-family: 'Noto Serif">
            <% $resultado["datos"]["html"]; %>
            </div>
            <br/>

            <br/><br/>
            <br/><br/>
        </div>
    </div>
</div>
<!--/.main-->

<% HTML::script('public/js/jquery-1.11.1.min.js'); %>
<% HTML::script('public/js/bootstrap.min.js'); %>
<script>
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

    $(document).ready(function() {
        $("#esquina").hide();
        $('#imagen').each(function() {
            $(this).on('load', function(){
                var altura=parseInt($(this).height()/10);
                var margen=($(this).height())*-1;
                //console.log("Imagen "+altura);
                //console.log("MARgen "+margen);
                $(".ribon-white-top").css('top','-'+altura+'px')
                $("#esquina").height(altura);
                $("#esquina").show();
            });
            //tmpImg.src = $(this).attr('src') ;
        }) ;
    }) ;
</script>
</body>

</html>
