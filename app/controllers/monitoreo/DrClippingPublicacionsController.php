<?php

class DrClippingPublicacionsController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$drclippingpublicacions = Drclippingpublicacion::all();

		return View::make('ws.json', array("resultado"=>compact('drclippingpublicacions')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Drclippingpublicacion::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(Drclippingpublicacion::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Drclippingpublicacion')));
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
		$drclippingpublicacion = Drclippingpublicacion::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('drclippingpublicacion')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$drclippingpublicacion = Drclippingpublicacion::findOrFail($id);
		$data = Input::all();

		if($drclippingpublicacion->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('drclippingpublicacion')));
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
		$drclippingpublicacion = Drclippingpublicacion::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($drclippingpublicacion->update($data))
		{
			$drclippingpublicacion = Drclippingpublicacion::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('drclippingpublicacion')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
