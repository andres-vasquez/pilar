<?php

class TecnobitaccesosController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$tecnobitaccesos = Tecnobitacceso::all();

		return View::make('ws.json', array("resultado"=>compact('tecnobitaccesos')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
    public function store($usuario_id,$origen)
    {
        $data=array();
        $data["usuario_id"]=$usuario_id;
        $data["origen"]=$origen;

        $validator = Validator::make($data, Tecnobitacceso::$rules);

        if ($validator->fails())
        {
            $errores=$validator->messages()->first();
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }

        if(Tecnobitacceso::create($data))
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
		$tecnobitacceso = Tecnobitacceso::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('tecnobitacceso')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$tecnobitacceso = Tecnobitacceso::findOrFail($id);
		$data = Input::all();

		if($tecnobitacceso->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('tecnobitacceso')));
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
		$tecnobitacceso = Tecnobitacceso::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($tecnobitacceso->update($data))
		{
			$tecnobitacceso = Tecnobitacceso::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('tecnobitacceso')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
