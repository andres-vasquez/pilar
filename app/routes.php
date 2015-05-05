<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


//Login
Route::get('/login', function () {
    Blade::setContentTags('<%', '%>');
    Blade::setEscapedContentTags('<%%', '%%>');

    return View::make('sitio.auth.login');
    //return 'Sesion creada';
});

//Todas las paginas que requieran sesion
Route::group(array('before' => 'session'), function () {
    Blade::setContentTags('<%', '%>');
    Blade::setEscapedContentTags('<%%', '%%>');


    Route::get('/rutarelativa/{sistema}', array('as' => 'show', 'uses' => 'AutenticacionController@enlace'));

    Route::get('/', function () {
        $jsonAccesos = Session::get('accesos');
        if ($jsonAccesos["intCodigo"] == "1") // Si tiene accesos
        {
            $jsonDatos = $jsonAccesos["resultado"]["datos"];
            if (count($jsonDatos) > 0) {
                $sistemas = array();
                for ($i = 0; $i < count($jsonDatos); $i++) // Carga los datos de sistemas
                {
                    $perfile = $jsonDatos[$i]["perfile"]; //Obtiene los perfiles y carga el ultimo
                    array_push($sistemas, $jsonDatos[$i]["perfile"]["sistema"]["nombre"]);
                }
                //Datos de accesos
                Session::put('sistemas', $sistemas);
                return Redirect::to('/rutarelativa/' . $perfile["sistema"]["nombre"]);
            } else
                return Redirect::to('/login');
        } else
            return Redirect::to('/login');
    });

    //************** ADMIN **********************
    Route::group(array('before' => 'permisos'), function () {
        //REST Api consultas
        Route::group(array('prefix' => 'admin'), function () {
            Route::get('/', function () {
                return View::make('sitio.admin.index')->with('data', array('sistemas' => Session::get('sistemas'), 'menus' => Session::get('menus')));
            });
            Route::get('/perfiles', function () {
                return View::make('sitio.admin.perfiles')->with('data', array('sistemas' => Session::get('sistemas'), 'menus' => Session::get('menus')));
            });
            Route::get('/usuarios', function () {
                return View::make('sitio.admin.usuarios')->with('data', array('sistemas' => Session::get('sistemas'), 'menus' => Session::get('menus')));
            });
            Route::get('/sistemas', function () {
                return View::make('sitio.admin.sistemas')->with('data', array('sistemas' => Session::get('sistemas'), 'menus' => Session::get('menus')));
            });
        });
    });


    //************* SMS ******************
    Route::group(array('prefix' => 'sms'), function () {
        Route::get('/', function () {
            return View::make('sitio.sms.index')->with('data', array('sistemas' => Session::get('sistemas'), 'menus' => Session::get('menus')));
        });
        Route::get('/usuarios', function () {
            return View::make('sitio.sms.usuarios')->with('data', array('sistemas' => Session::get('sistemas'), 'menus' => Session::get('menus')));
        });
        Route::get('/reportes', function () {
            return View::make('sitio.sms.reportes')->with('data', array('sistemas' => Session::get('sistemas'), 'menus' => Session::get('menus')));
        });
        Route::get('/config', function () {
            return View::make('sitio.sms.config')->with('data', array('sistemas' => Session::get('sistemas'), 'menus' => Session::get('menus')));
        });
    });

    //************** Feicobol **********************
    Route::group(array('prefix' => 'feicobol'), function () {
        Route::get('/', function () {
            return View::make('sitio.feicobol.index')->with('data', array('sistemas' => Session::get('sistemas'), 'menus' => Session::get('menus')));
        });
        Route::get('/noticias', function () {
            return View::make('sitio.feicobol.noticias')->with('data', array('sistemas' => Session::get('sistemas'), 'menus' => Session::get('menus')));
        });
        Route::get('/mapa', function () {
            return View::make('sitio.feicobol.mapa')->with('data', array('sistemas' => Session::get('sistemas'), 'menus' => Session::get('menus')));
        });
        Route::get('/publicidad', function () {
            return View::make('sitio.feicobol.publicidad')->with('data', array('sistemas' => Session::get('sistemas'), 'menus' => Session::get('menus')));
        });
        Route::get('/expositores', function () {
            return View::make('sitio.feicobol.expositores')->with('data', array('sistemas' => Session::get('sistemas'), 'menus' => Session::get('menus')));
        });
        Route::get('/restapi', function () {
            return View::make('sitio.feicobol.restapi')->with('data', array('sistemas' => Session::get('sistemas'), 'menus' => Session::get('menus')));
        });

    });
    //*************** GENERAL *******************

    Route::get('/logout', function () {
        Session::forget('accesos');
        return Redirect::to('/login');
    });
});


//**********************  WS  *******************************
//WS usuarios
Route::get('/ws/usuarios', 'UsuariosController@index');
Route::get('/ws/usuario/{id}', array('as' => 'show', 'uses' => 'UsuariosController@show'));
Route::post('/ws/usuarios', 'UsuariosController@store');
Route::post('/ws/usuario/{id}', array('as' => 'show', 'uses' => 'UsuariosController@update'));
Route::post('/ws/usuario/eliminar/{id}', array('as' => 'show', 'uses' => 'UsuariosController@destroy'));

//WS perfiles CRUD
Route::get('/ws/perfiles', 'PerfilesController@index');
Route::get('/ws/perfil/{id}', array('as' => 'show', 'uses' => 'PerfilesController@show'));
Route::post('/ws/perfiles', 'PerfilesController@store');
Route::post('/ws/perfil/{id}', array('as' => 'show', 'uses' => 'PerfilesController@update'));
Route::post('/ws/perfil/eliminar/{id}', array('as' => 'show', 'uses' => 'PerfilesController@destroy'));

//WS eventos
Route::get('/ws/perfiles/arbol', 'PerfilesController@ArbolPerfiles');
Route::get('/ws/perfiles/{id_sistema}', array('as' => 'show', 'uses' => 'PerfilesController@PerfilesPorSistema'));

//WS asignacion perfiles
Route::get('/ws/aperfiles', 'AsignacionPerfilesController@index'); // Todos los perfiles
Route::get('/ws/aperfiles/{id_usuario}', array('as' => 'show', 'uses' => 'AsignacionPerfilesController@show'));// id usuario. Ver los perfiles por usuario

//WS sistemas desarrollados
Route::get('/ws/sistemas', 'SistemasDesarrolladosController@index'); // Todos los sistemas
Route::get('/ws/sistemas/{id}', array('as' => 'show', 'uses' => 'SistemasDesarrolladosController@show'));

//WS Autenticacion de usuarios
Route::post('/ws/autenticacion', 'AutenticacionController@index'); // Todos los sistemas
Route::get('/ws/autenticacion/{id_usuario}', array('as' => 'show', 'uses' => 'AutenticacionController@datos')); // Muestra los datos de perfiles

//WS Menus
Route::get('/ws/menu/{id_menu}', array('as' => 'show', 'uses' => 'MenusController@show'));
Route::get('/ws/menus/perfil/{id_perfil}', array('as' => 'show', 'uses' => 'AsignacionMenusController@datos'));


//WS Archivos
Route::post('/ws/descargas','HomeController@descargas');


//************************ PILAR ****************************
//WS Noticias
Route::get('/ws/noticia/{id}', array('as' => 'show', 'uses' => 'NoticiasController@show'));
Route::post('/ws/noticias', 'NoticiasController@store');
Route::post('/ws/noticia/{id}', array('as' => 'show', 'uses' => 'NoticiasController@update'));
Route::post('/ws/noticia/eliminar/{id}', array('as' => 'show', 'uses' => 'NoticiasController@destroy'));

//WS Publicidad
Route::get('/ws/publicidad', 'PublicidadsController@index');
Route::get('/ws/publicidad/{id}', array('as' => 'show', 'uses' => 'PublicidadsController@show'));
Route::post('/ws/publicidad', 'PublicidadsController@store');
Route::post('/ws/publicidad/{id}', array('as' => 'show', 'uses' => 'PublicidadsController@update'));
Route::post('/ws/publicidad/eliminar/{id}', array('as' => 'show', 'uses' => 'PublicidadsController@destroy'));

//WS Expositores
Route::get('/ws/expositores', 'ExpositoresController@index');
Route::get('/ws/expositores/{id}', array('as' => 'show', 'uses' => 'ExpositoresController@show'));
Route::post('/ws/expositores/importar', 'ExpositoresController@importar');
Route::post('/ws/expositores', 'ExpositoresController@store');
Route::post('/ws/expositores/{id}', array('as' => 'show', 'uses' => 'ExpositoresController@update'));
Route::post('/ws/expositores/eliminar/{id}', array('as' => 'show', 'uses' => 'ExpositoresController@destroy'));


//WS Publicidad Imagenes
Route::get('/ws/publicidad_imagenes/{id}', array('as' => 'show', 'uses' => 'PublicidadImagensController@show'));
Route::post('/ws/publicidad_imagenes', 'PublicidadImagensController@store');
Route::post('/ws/publicidad_imagenes/{id}', array('as' => 'show', 'uses' => 'PublicidadImagensController@update'));
Route::post('/ws/publicidad_imagenes/eliminar/{id}', array('as' => 'show', 'uses' => 'PublicidadImagensController@destroy'));

//TODO: Hacer ruta feicobol dinamica
Route::get('feicobol/public/uploads/feicobol/{archivo}', array('as' => 'show', 'uses' => 'PublicidadImagensController@mostrarImagen'));

//WS Mapas
Route::get('/ws/mapa', 'MapasController@index');
//TODO: Mapas por sistemas
Route::get('/ws/mapa/{id}', array('as' => 'show', 'uses' => 'MapasController@show'));
Route::post('/ws/mapa', 'MapasController@store');
Route::post('/ws/mapa/{id}', array('as' => 'show', 'uses' => 'MapasController@update'));
Route::post('/ws/mapa/eliminar/{id}', array('as' => 'show', 'uses' => 'MapasController@destroy'));

//WS MapaTags
Route::get('/ws/mapa_tags', 'MapaTagsController@index');
Route::get('/ws/mapa_tags/{id}', array('as' => 'show', 'uses' => 'MapaTagsController@show'));
Route::post('/ws/mapa_tags', 'MapaTagsController@store');
Route::post('/ws/mapa_tags/{id}', array('as' => 'show', 'uses' => 'MapaTagsController@update'));
Route::post('/ws/mapa_tags/eliminar/{id}', array('as' => 'show', 'uses' => 'MapaTagsController@destroy'));

//WS Likes
Route::get('/ws/likesExpositores', 'LikesExpositoresController@index');
Route::get('/ws/likesExpositores/{id}', array('as' => 'show', 'uses' => 'LikesExpositoresController@show'));
Route::post('/ws/likesExpositores', 'LikesExpositoresController@store');
Route::post('/ws/likesExpositores/{id}', array('as' => 'show', 'uses' => 'LikesExpositoresController@update'));
Route::post('/ws/likesExpositores/eliminar/{id}', array('as' => 'show', 'uses' => 'LikesExpositoresController@destroy'));


//*************** REST API GENERAL ***************************
Route::group(array('prefix' => 'api/v1'), function () {

    //REST Api noticias
    Route::group(array('prefix' => '/noticias'), function () {
        Route::get('/{sistema}', array('as' => 'show', 'uses' => 'NoticiasController@apitodas'));
        Route::get('/{sistema}/{inicio}', array('as' => 'show', 'uses' => 'NoticiasController@error'))->where('inicio', '[0-9]+');
        Route::get('/{sistema}/{inicio}/{fin}', array('as' => 'show', 'uses' => 'NoticiasController@apirangos'))->where(array('inicio' => '[0-9]+', 'fin' => '[0-9]+'));
        Route::get('/{sistema}/{inicio}/{fin}/{orden}', array('as' => 'show', 'uses' => 'NoticiasController@apiorden'));
        Route::get('/{sistema}/{id_noticia}/{metodo}', array('as' => 'show', 'uses' => 'NoticiasController@web'))->where(array('id_noticia' => '[0-9]+', 'metodo' => '[a-z]+'));;
    });

    //REST Api publicidad
    Route::group(array('prefix' => '/publicidad'), function () {
        Route::get('/{sistema}/sinformato', array('as' => 'show', 'uses' => 'PublicidadsController@apitodassinformato')); //
        Route::get('/{sistema}', array('as' => 'show', 'uses' => 'PublicidadsController@apitodas')); // Todas
        Route::get('/{sistema}/{tipo}/{tamanox}/{tamanoy}', array('as' => 'show', 'uses' => 'PublicidadsController@apitipotamano'));
        Route::get('/{sistema}/{tipo}/{tamanox}/{tamanoy}/{cantidad}', array('as' => 'show', 'uses' => 'PublicidadsController@apitipotamanoq'));
    });

    //REST Api publicidad
    Route::group(array('prefix' => '/expositores'), function () {
        Route::get('/{sistema}', array('as' => 'show', 'uses' => 'ExpositoresController@apitodas')); // Todas
        Route::get('/{sistema}/sinformato', array('as' => 'show', 'uses' => 'ExpositoresController@apitodassinformato')); // Todas
    });

    //REST Api likes
    Route::group(array('prefix' => '/expositoreslikes'), function () {
        Route::post('/', 'LikesExpositoresController@store');
        Route::get('/{sistema}/reporte', array('as' => 'show', 'uses' => 'LikesExpositoresController@apitodas')); // Todas
        Route::get('/{sistema}/conteo', array('as' => 'show', 'uses' => 'LikesExpositoresController@apiconteo'));
    });

    //MAPA Api
    Route::group(array('prefix' => '/mapas'), function () {
        Route::get('/{sistema}', array('as' => 'show', 'uses' => 'MapasController@apitodas')); // Todas
    });
});


//***************************** SMS ************************************************


//WS Mensajes
Route::get('/ws/SmsMensaje', 'SmsMensajesController@index');
Route::post('/ws/SmsMensaje', 'SmsMensajesController@store');
Route::get('/ws/SmsMensaje/{usuario_id}', array('as' => 'show', 'uses' => 'SmsMensajesController@show'));
Route::get('/ws/SmsMensaje/cantidad/{todos}', array('as' => 'show', 'uses' => 'SmsMensajesController@cantidad'));

Route::get('/ws/SmsMensaje/dashboard/{mes}', array('as' => 'show', 'uses' => 'SmsMensajesController@graficoDashboard'));

//WS Bancos
Route::get('/ws/SmsBanco', 'SmsBancosController@index');
Route::get('/ws/SmsBanco_sinformato', 'SmsBancosController@sinformato');
Route::get('/ws/SmsBanco/{id}', array('as' => 'show', 'uses' => 'SmsBancosController@show'));
Route::post('/ws/SmsBanco', 'SmsBancosController@store');
Route::post('/ws/SmsBanco/{id}', array('as' => 'show', 'uses' => 'SmsBancosController@update'));
Route::post('/ws/SmsBanco/eliminar/{id}', array('as' => 'show', 'uses' => 'SmsBancosController@destroy'));

//WS Config
Route::get('/ws/SmsConfiguracion', 'SmsConfiguracionsController@index');
Route::post('/ws/SmsConfiguracion/editar', 'SmsConfiguracionsController@editar');


//WS Usuarioss
Route::get('/ws/SmsUsuarios', 'SmsUsuariosController@index');
Route::get('/ws/SmsUsuarios_sinformato', 'SmsUsuariosController@sinformato');
Route::get('/ws/SmsUsuarios/{id}', array('as' => 'show', 'uses' => 'SmsUsuariosController@show'))->where(array('id' => '[0-9]+'));
Route::post('/ws/SmsUsuarios', 'SmsUsuariosController@store');
Route::post('/ws/SmsUsuarios/{id}', array('as' => 'show', 'uses' => 'SmsUsuariosController@update'));
Route::post('/ws/SmsUsuarios/eliminar/{id}', array('as' => 'show', 'uses' => 'SmsUsuariosController@destroy'));
Route::get('/ws/SmsUsuarios/cantidad', 'SmsUsuariosController@cantidad');

//WS Accesos
Route::get('/ws/SmsAcceso', 'SmsAccesoController@index');
Route::get('/ws/SmsAcceso/{usuario_id}', array('as' => 'show', 'uses' => 'SmsAccesoController@show'));
Route::post('/ws/SmsAcceso', 'SmsAccesoController@store');


//*********************** REST API SMS *********************************

//REST Api
Route::group(array('before'=>'credencial','prefix' => 'apisms/v1'), function () {

    //REST Api bancos
    Route::group(array('prefix' => '/bancos'), function () {
        Route::get('/', array('as' => 'show', 'uses' => 'SmsBancosController@index'));
    });

    //REST Api usuarios
    Route::group(array('prefix' => '/usuarios'), function () {
        Route::post('/registrar', 'SmsUsuariosController@store');
        Route::post('/actualizar/{id}', array('as' => 'show', 'uses' => 'SmsUsuariosController@update'));
        Route::post('/auth', 'SmsUsuariosController@autenticacion');
    });

    //REST Api configuracion
    Route::group(array('prefix' => '/config'), function () {
        Route::get('/', array('as' => 'show', 'uses' => 'SmsConfiguracionController@obtener'));
    });

    //REST Api stats
    Route::group(array('prefix' => '/stats'), function () {
        Route::post('/','SmsMensajesController@stats');
    });
});



