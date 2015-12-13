<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DrClippingAccesosController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$drclippingaccesos = DrClippingAcceso::all();

		return View::make('ws.json', array("resultado"=>compact('drclippingaccesos')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), DrClippingAcceso::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(DrClippingAcceso::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Drclippingacceso')));
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
		$drclippingacceso = DrClippingAcceso::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('drclippingacceso')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$drclippingacceso = DrClippingAcceso::findOrFail($id);
		$data = Input::all();

		if($drclippingacceso->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('drclippingacceso')));
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
		$drclippingacceso = DrClippingAcceso::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($drclippingacceso->update($data))
		{
			$drclippingacceso = DrClippingAcceso::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('drclippingacceso')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
