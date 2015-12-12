<?php

class DrClippingAnalisesController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$drclippinganalises = Drclippinganalise::all();

		return View::make('ws.json', array("resultado"=>compact('drclippinganalises')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Drclippinganalise::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(Drclippinganalise::create($data))
		{
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
		$drclippinganalise = Drclippinganalise::findOrFail($id);
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
		$drclippinganalise = Drclippinganalise::findOrFail($id);
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
		$drclippinganalise = Drclippinganalise::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($drclippinganalise->update($data))
		{
			$drclippinganalise = Drclippinganalise::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('drclippinganalise')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
