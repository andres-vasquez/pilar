<?php

class SmsBancosController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$smsbancos = SmsBanco::all();

		return View::make('ws.json', array("resultado"=>compact('smsbancos')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), SmsBanco::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(SmsBanco::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Smsbanco')));
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
		$smsbanco = SmsBanco::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('smsbanco')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$smsbanco = SmsBanco::findOrFail($id);
		$data = Input::all();

		if($smsbanco->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('smsbanco')));
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
		$smsbanco = SmsBanco::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($smsbanco->update($data))
		{
			$smsbanco = SmsBanco::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('smsbanco')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}


    public function sinformato()
    {
        $smsbancos = SmsBanco::all();
        $resultado = array();
        foreach ($smsbancos as $banco)
        {
            $aux=array();
            $aux["id"]=$banco["id"];
            $aux["nombre"]=$banco["nombre"];
            $aux["abreviacion"]=$banco["abreviacion"];
            $aux["indice"]=$banco["indice"];
            $aux["fecha_creacion"]=date('d-m-Y',strtotime($banco["created_at"]));
            array_push($resultado,$aux);
        }

        return json_encode($resultado);
    }
}
