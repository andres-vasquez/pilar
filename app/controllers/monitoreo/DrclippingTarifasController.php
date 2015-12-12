<?php

class DrclippingTarifasController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$drclippingtarifas = Drclippingtarifa::all();

		return View::make('ws.json', array("resultado"=>compact('drclippingtarifas')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Drclippingtarifa::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(Drclippingtarifa::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Drclippingtarifa')));
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
		$drclippingtarifa = Drclippingtarifa::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('drclippingtarifa')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$drclippingtarifa = Drclippingtarifa::findOrFail($id);
		$data = Input::all();

		if($drclippingtarifa->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('drclippingtarifa')));
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
		$drclippingtarifa = Drclippingtarifa::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($drclippingtarifa->update($data))
		{
			$drclippingtarifa = Drclippingtarifa::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('drclippingtarifa')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
