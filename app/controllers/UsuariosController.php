<?php

class UsuariosController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$usuarios = Usuario::whereRaw('baja_logica=?',array(1))->get();

		$Usuarios=array();
		foreach ($usuarios as $usuario) 
		{
			$datos=array();
			$datos["id"]=$usuario["id"];
			$datos["id_fb"]=$usuario["id_fb"];
			$datos["id_google"]=$usuario["id_google"];
			$datos["nombre"]=$usuario["nombre"];
			$datos["email"]=$usuario["email"];
			$datos["estado"]=$usuario["estado"];
			$datos["baja_logica"]=$usuario["baja_logica"];
			if($usuario["ultimo_acceso"]!="0000-00-00 00:00:00")
				$datos["ultimo_acceso"]=date('d-m-Y H:i',strtotime($usuario["ultimo_acceso"]));
			array_push($Usuarios,$datos);
		}

		return View::make('ws.json', array("resultado"=>compact('Usuarios')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Usuario::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		$usuario=Usuario::whereRaw('email=? AND baja_logica=1',array($data["email"]))->get();
		if(sizeof($usuario)==0)
		{
			if(Usuario::create($data))
			{
				$mensaje="Registro creado";
				return View::make('ws.json', array("resultado"=>compact('mensaje')));
			}
			else
			{
				$errores="Error al crear registro";
				return View::make('ws.json_errores', array("errores"=>compact('errores')));
			}
		}
		else
		{
			$errores="El email ".$data["email"]." ya está en uso";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

	/**
	 * Muestra el registro segun el ID ingresado.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$usuario = Usuario::findOrFail($id);

		$datos=array();
		$datos["id"]=$usuario["id"];
		$datos["id_fb"]=$usuario["id_fb"];
		$datos["id_google"]=$usuario["id_google"];
		$datos["nombre"]=$usuario["nombre"];
		$datos["email"]=$usuario["email"];
		$datos["estado"]=$usuario["estado"];
		$datos["baja_logica"]=$usuario["baja_logica"];
		$datos["ultimo_acceso"]=$usuario["ultimo_acceso"];

		$usuario=$datos;

		return View::make('ws.json', array("resultado"=>compact('usuario')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$usuario = Usuario::findOrFail($id);
		$data = Input::all();

		if($usuario->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('usuario')));
		}
		else
		{
			$errores="Error al actualizar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

	/**
	 * Define baja logica a l registro indicado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$usuario = Usuario::findOrFail($id);

		$data = array();
		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		$usuario->update($data);

		if($usuario->update($data))
		{
			$usuario = Usuario::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('usuario')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
