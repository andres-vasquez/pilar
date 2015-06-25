<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sms</title>
    <link href="bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap-3.3.5-dist/css/bootstrap-theme.min.css" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome-4.3.0/css/font-awesome.min.css">
    <link href="css/estilos.css" rel="stylesheet">
    <script>
        window.fbAsyncInit = function () {
            FB.init({
                appId: '258931317609123',
                xfbml: true,
                version: 'v2.3'
            });

            function onLogin(response) {
                if (response.status == 'connected') {
                    FB.api('/me?fields=first_name', function (data) {
                        var welcomeBlock = document.getElementById('fb-welcome');
                        welcomeBlock.innerHTML = '' + data.first_name + '!';
                    });
                }
            }

            FB.getLoginStatus(function (response) {
                // Check login status on load, and if the user is
                // already logged in, go directly to the welcome message.
                if (response.status == 'connected') {
                    onLogin(response);
                } else {
                    // Otherwise, show Login dialog first.
                    FB.login(function (response) {
                        onLogin(response);
                    }, {scope: 'user_friends, email'});
                }
            });
        };

        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <script src="js/jquery-1.11.2.min.js"></script>
    <script src="bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <script src="js/funciones.js"></script>
</head>
<body>
<div class="container">
    <div class="header clearfix">
        <nav>
            <ul class="nav nav-pills pull-right">
                <li role="presentation" class="active"><a href="#">Home</a></li>
                <!--<li role="presentation"><a href="#">Actualizar</a></li>
                <li role="presentation"><a href="#">Otros meses</a></li>-->

                <li role="presentation"><a href="#">Bienvenido: <span id="fb-welcome"></span></a></li>
            </ul>
        </nav>
        <h4 class="text-success">Sm$ convierte tu saldo en dinero</h4>
    </div>

    <div class="jumbotron">
        <img src="img/logo_sms.png">
        <br/><br/><br/>

        <p>
            <a class="btn btn-grande btn-info" href="#" id="btnRegistro" role="button">Registrarme</a>
            <a class="btn btn-grande btn-success" href="#" id="btnSaldo" role="button">Ver mi cuenta</a>
        </p>
    </div>

    <div id="panel" class="jumbotron hidden">
        <div id="mensaje">
        </div>

        <div id="divRegistro" class="row conteiner hidden">
            <form role="form" id="formRegistro" class="col-xs-8 col-xs-offset-2 text-left">
                <div class="form-group">
                    <label for="txtNombre">Nombre Completo</label>
                    <input type="text" class="form-control" id="txtNombre"
                           placeholder="Inttroduce tu nombre completo" maxlength="60" required>
                </div>
                <div class="form-group">
                    <label for="txtNombreDeposito">Nombre a depositar</label>
                    <input type="text" class="form-control" id="txtNombreDeposito"
                           placeholder="Introduce nombre a depositar" maxlength="60" required>
                </div>

                <div class="form-group">
                    <label for="txtDigitos">Últimos 5 números de tu celular</label>
                    <input type="text" class="form-control" id="txtDigitos"
                           placeholder="5 dīgitos de tu celuar" style="width: 300px" maxlength="5" required>
                </div>

                <div class="form-group">
                    <label for="txtClabe">CLABE INTERBANCARIA (18 dìgitos)</label>
                    <input type="text" class="form-control" id="txtClabe"
                           placeholder="Introduce tu Clabe interbancaria" style="width: 300px" maxlength="18">
                </div>

                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="chkSinClabe"> Aún no tengo Clabe
                    </label>
                </div>

                <div class="form-group">
                    <label for="txtEmail">Email de Confirmación</label>
                    <input type="email" class="form-control" id="txtEmail"
                           placeholder="Introduce tu email de confirmación" maxlength="60" required>
                </div>
                <button type="submit" class="btn btn-info">Registrar</button>
            </form>
        </div>


        <div id="divAcceso" class="row conteiner">
            <form id="formAcceso" role="form" class="col-xs-6 col-xs-offset-3 text-left">
                <div class="form-group">
                    <label for="txtCodigo">Código</label>
                    <input type="text" class="form-control text-uppercase" id="txtCodigo"
                           placeholder="Introduce tu código" maxlength="20" required>
                </div>
                <button type="submit" class="btn btn-success pull-right">Consultar</button>
            </form>
            <div class="col-xs-12 col-xs-12">
                <br/>
                <div id="loading" class="hidden">
                    <h4><i class="fa fa-cog fa-spin fa-2x"></i>&nbsp; Cargando...</h4>
                </div>
                <br/>

                <div id="divPanelDatos" class="row hidden">
                    <div class="col-xs-6 col-md-6">
                        <div class="panel panel-success" style="margin-left: 10px">
                            <div class="panel-heading clearfix">
                                <h4 class="panel-title pull-left">Datos de cuenta</h4>

                                <div class="pull-right dropdown">
                                    <i id="btnActualizar" class="glyphicon glyphicon-refresh puntero"></i>&nbsp;&nbsp;
                                    <i class="glyphicon glyphicon-calendar puntero btnCalendario dropdown-toggle" data-toggle="dropdown"></i><span class="caret btnCalendario puntero dropdown-toggle" data-toggle="dropdown"></span>

                                    <ul id="lstMeses" class="dropdown-menu">
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body">
                                <small class="pull-right" id="periodo"></small>
                                <strong class="pull-left">Información cuenta</strong>
                                <div class="clearfix left">
                                    <br/><br/>
                                    <table class="col-lg-12">
                                        <tr>
                                            <td class="text-left">Mensajes enviados:</td>
                                            <td class="text-left" style="padding-left:15px;"><b id="txtCantidadMensajes"></b></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Monto a depositar:</td>
                                            <td class="text-left" style="padding-left:15px;"><b id="txtMonto"></b></td>
                                        </tr>
                                    </table>
                                </div>
                                <br/><br/>
                                <strong class="pull-left">Recibido por mensaje: <span id="txtGanancia"></span></strong>
                            </div>
                            <div class="panel-footer">
                                <small class="text-right">
                                    Actualizado: <span id="txtFechaActualizacion"></span>
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-6">
                        <div class="panel panel-info" style="margin-right: 10px">
                            <div class="panel-heading"><strong>Avisos</strong></div>
                            <div class="panel-body">
                                <p class="pull-left">
                                    <small id="txtAvisos" style="font-size:14px">Lalsdlas</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger" id="btnCerrarSesion">
                        <i class="glyphicon glyphicon-log-out"></i> Cerrar sesión</button>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <p>&copy; Bolivia onTouch 2015</p>
    </footer>

    <div class="modal fade" id="dialogMensaje" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Antes de registrarse...
                </div>
                <div class="modal-body">
                    Asegúrese de enviar un SMS al <b>77700</b> con la palabra <b>pp</b> antes de registrarse.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button id="btnYaEnvie" type="button" class="btn btn-success" data-dismiss="modal">Ya envié, registrarme</button>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /container -->


</body>
</body>
</html>