<?php

class NotificacionesController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$notificaciones = Notificacione::all();

		return View::make('ws.json', array("resultado"=>compact('notificaciones')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Notificacione::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(Notificacione::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Notificacione')));
		}
		else
		{
			$errores="Error al crear registro";
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
		$notificacione = Notificacione::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('notificacione')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$notificacione = Notificacione::findOrFail($id);
		$data = Input::all();

		if($notificacione->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('notificacione')));
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
		$notificacione = Notificacione::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($notificacione->update($data))
		{
			$notificacione = Notificacione::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('notificacione')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
