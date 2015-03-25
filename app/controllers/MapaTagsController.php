<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
class MapaTagsController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$mapatags = Mapatag::all();

		return View::make('ws.json', array("resultado"=>compact('mapatags')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Mapatag::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(Mapatag::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Mapatag')));
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
		$mapatag = Mapatag::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('mapatag')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$mapatag = Mapatag::findOrFail($id);
		$data = Input::all();

		if($mapatag->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('mapatag')));
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
		$mapatag = Mapatag::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($mapatag->update($data))
		{
			$mapatag = Mapatag::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('mapatag')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
