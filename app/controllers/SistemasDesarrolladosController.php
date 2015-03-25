<?php

class SistemasDesarrolladosController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$sistemasdesarrollados = Sistemasdesarrollados::all();
		return View::make('ws.json', array("resultado"=>compact('sistemasdesarrollados')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Sistemasdesarrollados::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(Sistemasdesarrollados::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Sistemasdesarrollado')));
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
		$sistemasdesarrollado = Sistemasdesarrollados::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('sistemasdesarrollado')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$sistemasdesarrollado = Sistemasdesarrollados::findOrFail($id);
		$data = Input::all();

		if($sistemasdesarrollado->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('sistemasdesarrollado')));
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
		$sistemasdesarrollado = Sistemasdesarrollados::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($sistemasdesarrollado->update($data))
		{
			$sistemasdesarrollado = Sistemasdesarrollados::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('sistemasdesarrollado')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
