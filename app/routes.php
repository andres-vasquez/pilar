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
Route::get('/login', function()
{
    Blade::setContentTags('<%', '%>');
    Blade::setEscapedContentTags('<%%', '%%>');

    return View::make('sitio.auth.login');
    //return 'Sesion creada';
});

//Todas las paginas que requieran sesion
Route::group(array('before'=>'session'),function()
{
    Blade::setContentTags('<%', '%>');
    Blade::setEscapedContentTags('<%%', '%%>');


    Route::get('/', function()
    {
        $id_sistema=0;
        $credencial="";


        $jsonAccesos = Session::get('accesos');
        if($jsonAccesos["intCodigo"]=="1") // Si tiene accesos
        {
            $jsonDatos=$jsonAccesos["resultado"]["datos"];
            if(count($jsonDatos)>0)
            {
                $perfile=$jsonDatos[0]["perfile"]; // Si tiene perfil
                $sistemas=array();

                for($i=0;$i<count($jsonDatos);$i++) // Carga los datos de sistemas
                {
                    array_push($sistemas,$jsonDatos[$i]["perfile"]["sistema"]["nombre"]);
                    if($id_sistema==0)
                    {
                        $id_sistema=$jsonDatos[$i]["perfile"]["sistema"]["id"];
                        $credencial=$jsonDatos[$i]["perfile"]["sistema"]["app"];
                    }
                }

                //Carga los menus disponibles
                $request = Request::create('/ws/menus/perfil/'.$perfile["id"], 'GET', array());
                $menus=array();
                $menus=json_decode(Route::dispatch($request)->getContent(),true);

                //Datos de aud
                Session::put('id_sistema',$id_sistema);
                Session::put('credencial',$credencial);
                //Datos de accesos
                Session::put('sistemas',$sistemas);
                Session::put('menus',$menus["resultado"]["datos"]);
                return Redirect::to('/'.$perfile["sistema"]["nombre"]);
            }
            else
                return Redirect::to('/login');
        }
        else
            return Redirect::to('/login');
    });



    Route::get('/feicobol', function()
    {
        $data = array('sistemas'  => Session::get('sistemas'),'menus'   => Session::get('menus'));
        return View::make('sitio.feicobol.index')->with('data', $data);
    });


    Route::get('/feicobol/noticias', function(){
        $data = array('sistemas'  => Session::get('sistemas'),'menus'   => Session::get('menus'));
        return View::make('sitio.feicobol.noticias')->with('data', $data);
    });

    Route::get('/feicobol/mapa', function(){
        $data = array('sistemas'  => Session::get('sistemas'),'menus'   => Session::get('menus'));
        return View::make('sitio.feicobol.mapa')->with('data', $data);
    });

    Route::get('/feicobol/publicidad', function(){
        $data = array('sistemas'  => Session::get('sistemas'),'menus'   => Session::get('menus'));
        return View::make('sitio.feicobol.publicidad')->with('data', $data);
    });

    Route::get('/feicobol/expositores', function(){
        $data = array('sistemas'  => Session::get('sistemas'),'menus'   => Session::get('menus'));
        return View::make('sitio.feicobol.expositores')->with('data', $data);
    });

    Route::get('/feicobol/restapi', function(){
        $data = array('sistemas'  => Session::get('sistemas'),'menus'   => Session::get('menus'));
        return View::make('sitio.feicobol.restapi')->with('data', $data);
    });


    Route::get('/logout', function(){
        Session::forget('accesos');
        return Redirect::to('/login');
    });
});


//**********************  WS  *******************************
//WS usuarios
Route::get('/ws/usuarios', 'UsuariosController@index');
Route::get('/ws/usuario/{id}',  array('as' => 'show', 'uses' =>'UsuariosController@show'));
Route::post('/ws/usuarios', 'UsuariosController@store');
Route::post('/ws/usuario/{id}',  array('as' => 'show', 'uses' =>'UsuariosController@update'));
Route::post('/ws/usuario/eliminar/{id}',  array('as' => 'show', 'uses' =>'UsuariosController@destroy'));

//WS perfiles CRUD
Route::get('/ws/perfiles', 'PerfilesController@index');
Route::get('/ws/perfil/{id}',  array('as' => 'show', 'uses' =>'PerfilesController@show'));
Route::post('/ws/perfiles', 'PerfilesController@store');
Route::post('/ws/perfil/{id}', array('as' => 'show', 'uses' =>'PerfilesController@update'));
Route::post('/ws/perfil/eliminar/{id}', array('as' => 'show', 'uses' =>'PerfilesController@destroy'));

//WS eventos
Route::get('/ws/perfiles/arbol', 'PerfilesController@ArbolPerfiles');
Route::get('/ws/perfiles/{id_sistema}',  array('as' => 'show', 'uses' =>'PerfilesController@PerfilesPorSistema'));

//WS asignacion perfiles
Route::get('/ws/aperfiles', 'AsignacionPerfilesController@index'); // Todos los perfiles
Route::get('/ws/aperfiles/{id_usuario}', array('as' => 'show', 'uses' =>'AsignacionPerfilesController@show'));// id usuario. Ver los perfiles por usuario

//WS sistemas desarrollados
Route::get('/ws/sistemas', 'SistemasDesarrolladosController@index'); // Todos los sistemas
Route::get('/ws/sistemas/{id}', array('as' => 'show', 'uses' =>'SistemasDesarrolladosController@show'));

//WS Autenticacion de usuarios
Route::post('/ws/autenticacion', 'AutenticacionController@index'); // Todos los sistemas
Route::get('/ws/autenticacion/{id_usuario}', array('as' => 'show', 'uses' =>'AutenticacionController@datos')); // Muestra los datos de perfiles

//WS Menus
Route::get('/ws/menu/{id_menu}', array('as' => 'show', 'uses' =>'MenusController@show'));
Route::get('/ws/menus/perfil/{id_perfil}', array('as' => 'show', 'uses' =>'AsignacionMenusController@datos'));



//************************ PILAR ****************************
//WS Noticias
Route::get('/ws/noticia/{id}',  array('as' => 'show', 'uses' =>'NoticiasController@show'));
Route::post('/ws/noticias', 'NoticiasController@store');
Route::post('/ws/noticia/{id}',  array('as' => 'show', 'uses' =>'NoticiasController@update'));
Route::post('/ws/noticia/eliminar/{id}',  array('as' => 'show', 'uses' =>'NoticiasController@destroy'));


//REST Api consultas
Route::group(array('prefix' => 'api/v1/noticias'), function()
{
    Route::get('/{sistema}',  array('as' => 'show', 'uses' =>'NoticiasController@apitodas'));
    Route::get('/{sistema}/{inicio}',  array('as' => 'show', 'uses' =>'NoticiasController@error'))->where('inicio', '[0-9]+');
    Route::get('/{sistema}/{inicio}/{fin}',  array('as' => 'show', 'uses' =>'NoticiasController@apirangos'))->where(array('inicio'=>'[0-9]+','fin'=>'[0-9]+'));
    Route::get('/{sistema}/{inicio}/{fin}/{orden}',  array('as' => 'show', 'uses' =>'NoticiasController@apiorden'));
    Route::get('/{sistema}/{id_noticia}/{metodo}',  array('as' => 'show', 'uses' =>'NoticiasController@web'))->where(array('id_noticia'=>'[0-9]+','metodo'=>'[a-z]+'));;
});


//WS Publicidad
Route::get('/ws/publicidad', 'PublicidadsController@index');
Route::get('/ws/publicidad/{id}',  array('as' => 'show', 'uses' =>'PublicidadsController@show'));
Route::post('/ws/publicidad', 'PublicidadsController@store');
Route::post('/ws/publicidad/{id}',  array('as' => 'show', 'uses' =>'PublicidadsController@update'));
Route::post('/ws/publicidad/eliminar/{id}',  array('as' => 'show', 'uses' =>'PublicidadsController@destroy'));

//REST Api publicidad
Route::group(array('prefix' => 'api/v1/publicidad'), function()
{
    Route::get('/{sistema}/sinformato',  array('as' => 'show', 'uses' =>'PublicidadsController@apitodassinformato')); //
    Route::get('/{sistema}',  array('as' => 'show', 'uses' =>'PublicidadsController@apitodas')); // Todas
    Route::get('/{sistema}/{tipo}/{tamanox}/{tamanoy}',  array('as' => 'show', 'uses' =>'PublicidadsController@apitipotamano'));
    Route::get('/{sistema}/{tipo}/{tamanox}/{tamanoy}/{cantidad}',  array('as' => 'show', 'uses' =>'PublicidadsController@apitipotamanoq'));
});

//WS Expositores
Route::get('/ws/expositores', 'ExpositoresController@index');
Route::get('/ws/expositores/{id}',  array('as' => 'show', 'uses' =>'ExpositoresController@show'));
Route::post('/ws/expositores/importar', 'ExpositoresController@importar');
Route::post('/ws/expositores', 'ExpositoresController@store');
Route::post('/ws/expositores/{id}',  array('as' => 'show', 'uses' =>'ExpositoresController@update'));
Route::post('/ws/expositores/eliminar/{id}',  array('as' => 'show', 'uses' =>'ExpositoresController@destroy'));

//REST Api publicidad
Route::group(array('prefix' => 'api/v1/expositores'), function()
{
    Route::get('/{sistema}',  array('as' => 'show', 'uses' =>'ExpositoresController@apitodas')); // Todas
    Route::get('/{sistema}/sinformato',  array('as' => 'show', 'uses' =>'ExpositoresController@apitodassinformato')); // Todas
});



//WS Publicidad Imagenes
Route::get('/ws/publicidad_imagenes/{id}',  array('as' => 'show', 'uses' =>'PublicidadImagensController@show'));
Route::post('/ws/publicidad_imagenes', 'PublicidadImagensController@store');
Route::post('/ws/publicidad_imagenes/{id}',  array('as' => 'show', 'uses' =>'PublicidadImagensController@update'));
Route::post('/ws/publicidad_imagenes/eliminar/{id}',  array('as' => 'show', 'uses' =>'PublicidadImagensController@destroy'));

//TODO: Hacer ruta feicobol dinamica
Route::get('feicobol/public/uploads/feicobol/{archivo}',  array('as' => 'show', 'uses' =>'PublicidadImagensController@mostrarImagen'));


//WS Mapas
Route::get('/ws/mapa', 'MapasController@index');
//TODO: Mapas por sistemas
Route::get('/ws/mapa/{id}',  array('as' => 'show', 'uses' =>'MapasController@show'));
Route::post('/ws/mapa', 'MapasController@store');
Route::post('/ws/mapa/{id}',  array('as' => 'show', 'uses' =>'MapasController@update'));
Route::post('/ws/mapa/eliminar/{id}',  array('as' => 'show', 'uses' =>'MapasController@destroy'));

//WS MapaTags
Route::get('/ws/mapa_tags', 'MapaTagsController@index');
Route::get('/ws/mapa_tags/{id}',  array('as' => 'show', 'uses' =>'MapaTagsController@show'));
Route::post('/ws/mapa_tags', 'MapaTagsController@store');
Route::post('/ws/mapa_tags/{id}',  array('as' => 'show', 'uses' =>'MapaTagsController@update'));
Route::post('/ws/mapa_tags/eliminar/{id}',  array('as' => 'show', 'uses' =>'MapaTagsController@destroy'));

//WS Likes
Route::get('/ws/likesExpositores', 'LikesExpositoresController@index');
Route::get('/ws/likesExpositores/{id}',  array('as' => 'show', 'uses' =>'LikesExpositoresController@show'));
Route::post('/ws/likesExpositores', 'LikesExpositoresController@store');
Route::post('/ws/likesExpositores/{id}',  array('as' => 'show', 'uses' =>'LikesExpositoresController@update'));
Route::post('/ws/likesExpositores/eliminar/{id}',  array('as' => 'show', 'uses' =>'LikesExpositoresController@destroy'));

//REST Api likes
Route::group(array('prefix' => 'api/v1/expositoreslikes'), function()
{
    Route::post('/', 'LikesExpositoresController@store');
    Route::get('/{sistema}/reporte',  array('as' => 'show', 'uses' =>'LikesExpositoresController@apitodas')); // Todas
    Route::get('/{sistema}/conteo',  array('as' => 'show', 'uses' =>'LikesExpositoresController@apiconteo'));
});

//MAPA Api
Route::group(array('prefix' => 'api/v1/mapas'), function()
{
    Route::get('/{sistema}',  array('as' => 'show', 'uses' =>'MapasController@apitodas')); // Todas
});


