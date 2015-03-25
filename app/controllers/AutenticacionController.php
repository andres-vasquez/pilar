<?php

class AutenticacionController extends \BaseController 
{

	/**
	 * [index description] Autenticacion
	 * @return [type] Objeto con la lista de acceossos [description]
	 */
	public function index()
	{
		$parametros = Input::all();

		if(isset($parametros["email"]) && isset($parametros["password"]))
		{
			$email=$parametros["email"];
			$password=$parametros["password"];

			if($email!="" && $password!="")
			{
				$usuario=Usuario::whereRaw('email=? AND password=?',array($email,$password))->get();
				if(sizeof($usuario)>0)
				{
					if($usuario[0]["estado"]!="1")
					{
						$errores="Usuario deshabilitado";
						return View::make('ws.json_errores', array("errores"=>compact('errores')));
					}
					else if($usuario[0]["baja_logica"]=="0")
					{
						$errores="Usuario eliminado";
						return View::make('ws.json_errores', array("errores"=>compact('errores')));
					}
					else
					{
						$request = Request::create('/ws/autenticacion/'.$usuario[0]["id"], 'GET', array());
						$datos=array();
						$datos= json_decode(Route::dispatch($request)->getContent(),true);
						$datos["usuario"]=$usuario[0]["nombre"];

                        Session::put('id_usuario',$usuario[0]["id"]);
						Session::put('accesos',$datos);
						return View::make('ws.json', array("resultado"=>compact('datos')));
					}
				}
				else
				{
					$errores="Email o Contraseña inválidos";
					return View::make('ws.json_errores', array("errores"=>compact('errores')));
				}
			}
			else
			{
				$errores="Ingrese Email y Contraseña";
				return View::make('ws.json_errores', array("errores"=>compact('errores')));
			}
		}
		else
		{
			$errores="Ingrese Email y Contraseña";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}



	/**
	 * Funcion que devuelve los datos de acceso del usuario
	 * GET /autenticacion
	 *
	 * @return Json
	 */
	public function datos($id_usuario)
	{
		$datos=array();
		$lstPerfilesAsignados = AsignacionPerfiles::whereRaw('usuario_id=? AND estado=1 AND baja_logica!=0', array($id_usuario))->get();

		foreach ($lstPerfilesAsignados as $Perfil)
		{
			//Obtenemos los detos del perfil
			$datos_perfil=array();
			$request = Request::create('/ws/perfil/'.$Perfil["perfil_id"], 'GET', array());
			$aux=array();
			$aux= json_decode(Route::dispatch($request)->getContent(),true);

			//Obtenemos el nombre del sistema
			$request = Request::create('/ws/sistemas/'.$aux["resultado"]["perfile"]["sistema_id"], 'GET', array());
			$aux2=array();
			$aux2=json_decode(Route::dispatch($request)->getContent(),true);
			$aux["resultado"]["perfile"]["sistema"]=$aux2["resultado"]["sistemasdesarrollado"];

			$datos_perfil=$aux["resultado"];

			array_push($datos, $datos_perfil);
		}

		return View::make('ws.json', array("resultado"=>compact('datos')));
	}
}