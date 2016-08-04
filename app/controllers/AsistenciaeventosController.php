<?php

class AsistenciaeventosController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$asistenciaeventos = Asistenciaevento::all();

		return View::make('ws.json', array("resultado"=>compact('asistenciaeventos')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Asistenciaevento::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(Asistenciaevento::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Asistenciaevento')));
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
		$asistenciaevento = Asistenciaevento::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('asistenciaevento')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$asistenciaevento = Asistenciaevento::findOrFail($id);
		$data = Input::all();

		if($asistenciaevento->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('asistenciaevento')));
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
		$asistenciaevento = Asistenciaevento::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($asistenciaevento->update($data))
		{
			$asistenciaevento = Asistenciaevento::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('asistenciaevento')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
