<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

//FIltro de session
Route::filter('session', function()
{
    if (!Session::has('accesos'))
    {
        return Redirect::to('/login');
    }
});

//Filtro para administrativos
Route::filter('permisos', function()
{
    if (!Session::has('permisos'))
    {
        return Redirect::to('/login');
    }
});


//Filtro para pilar
Route::filter('pilar', function()
{
    if (!Session::has('pilar'))
    {
        return Redirect::to('/login');
    }
});

Route::filter('output', function()
{
    if (!Session::has('output'))
    {
        return Redirect::to('/login');
    }
});

//Credencial
Route::filter('credencial', function()
{
    $data = Input::all();
    if (!isset($data["credencial"]))
    {
        $errores="El acceso al sistema es por credencial";
        return View::make('ws.json_errores', array("errores"=>compact('errores')));
    }
    else
    {
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($data["credencial"]))->get();
        if(sizeof($sistemas)==0)
        {
            $errores="Acceso no autorizado al sistema";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }
});
