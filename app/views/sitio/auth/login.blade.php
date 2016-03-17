<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

    <% HTML::style('public/css/bootstrap.min.css'); %>
    <% HTML::style('public/css/datepicker3.css'); %>
    <% HTML::style('public/css/styles.css'); %>

    <!--[if lt IE 9]>
    <% HTML::script('public/js/html5shiv.js'); %>
    <% HTML::script('public/js/respond.min.js'); %>
    <![endif]-->

    <style>
        body{
            background: #298EEA;
        }
    </style>
</head>

<body>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
        <img class="img-responsive" src="<% 'public/img/logo_pilar_transparente.png' %>"/>
    </div>
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">

            <div id="mensaje">
            </div>

            <div class="panel-heading">Acceso a la plataforma</div>
            <div class="panel-body">
                <% Form::open(array('url' => '/ws/autenticacion', 'id' => 'formLogin')) %>
                    <fieldset>
                        <br/>
                        <div class="form-group">
                            <input class="form-control" placeholder="E-mail" id="email" name="email" type="email" autofocus="">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Password" id="password" name="password" type="password" value="">
                        </div>
                        <!--<div class="checkbox">
                            <label>
                                <input name="remember" type="checkbox" value="Remember Me">Recordar
                            </label>
                        </div>-->
                        <br/> <br/>
                        <button  id="btnEnviar" class="btn btn-success col-sm-12 btn-lg">Acceder
                        </button>
                    </fieldset>
                <% Form::close() %>
            </div>
        </div>
    </div><!-- /.col-->
</div><!-- /.row -->

<% HTML::script('public/js/jquery-1.11.1.min.js'); %>
<% HTML::script('public/js/bootstrap.min.js'); %>
<% HTML::script('public/js/md5.js'); %>
<% HTML::script('public/js/sitio/login.js'); %>
<script>
    !function ($) {
        $(document).on("click","ul.nav li.parent > a > span.icon", function(){
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
