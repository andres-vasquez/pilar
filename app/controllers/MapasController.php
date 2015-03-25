<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
class MapasController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$mapas = Mapa::all();

		return View::make('ws.json', array("resultado"=>compact('mapas')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Mapa::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(Mapa::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Mapa')));
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
		$mapa = Mapa::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('mapa')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$mapa = Mapa::findOrFail($id);
		$data = Input::all();

		if($mapa->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('mapa')));
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
		$mapa = Mapa::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($mapa->update($data))
		{
			$mapa = Mapa::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('mapa')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
