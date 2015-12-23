<?php

class DrClippingAnalisesController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$drclippinganalises = DrClippingAnalisi::all();

		return View::make('ws.json', array("resultado"=>compact('drclippinganalises')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), DrClippingAnalisi::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(DrClippingAnalisi::create($data))
		{
            $drclippingpublicacion = DrClippingPublicacion::findOrFail($data["publicacion_id"]);
            $datos = array();
            $datos["estado_tarea"] = "1";
            $drclippingpublicacion->update($datos);

			return View::make('ws.json', array("resultado"=>compact('Drclippinganalise')));
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
		$drclippinganalise = DrClippingAnalisi::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('drclippinganalise')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$drclippinganalise = DrClippingAnalisi::findOrFail($id);
		$data = Input::all();

		if($drclippinganalise->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('drclippinganalise')));
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
		$drclippinganalise = DrClippingAnalisi::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($drclippinganalise->update($data))
		{
			$drclippinganalise = DrClippingAnalisi::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('drclippinganalise')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
