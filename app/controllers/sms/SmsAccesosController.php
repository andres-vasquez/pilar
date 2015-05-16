<?php

class SmsAccesosController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$smsaccesos = SmsAcceso::all();
		return View::make('ws.json', array("resultado"=>compact('smsaccesos')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store($usuario_id,$identificador,$tipo)
	{
        $data=array();
        $data["usuario_id"]=$usuario_id;
        $data["identificador"]=$identificador;
        $data["tipo"]=$tipo;

		$validator = Validator::make($data, SmsAcceso::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(Smsacceso::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Smsacceso')));
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
		$smsacceso = Smsacceso::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('smsacceso')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$smsacceso = Smsacceso::findOrFail($id);
		$data = Input::all();

		if($smsacceso->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('smsacceso')));
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
		$smsacceso = Smsacceso::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($smsacceso->update($data))
		{
			$smsacceso = Smsacceso::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('smsacceso')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
