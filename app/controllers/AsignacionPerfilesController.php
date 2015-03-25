<?php

class AsignacionPerfilesController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$asignacionperfiles = Asignacionperfiles::all();

		return View::make('ws.json', array("resultado"=>compact('asignacionperfiles')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Asignacionperfiles::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(Asignacionperfiles::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Asignacionperfiles')));
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
	public function show($id_usuario)
	{
		$asignacionperfiles = Asignacionperfiles::whereRaw('usuario_id = ? AND estado=1 AND baja_logica!=0',array($id_usuario))->get();
			return View::make('ws.json', array("resultado"=>compact('asignacionperfiles')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$asignacionperfiles = Asignacionperfiles::findOrFail($id);
		$data = Input::all();

		if($asignacionperfiles->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('asignacionperfiles')));
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
		$asignacionperfiles = Asignacionperfiles::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($asignacionperfiles->update($data))
		{
			$asignacionperfiles = Asignacionperfiles::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('asignacionperfiles')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
