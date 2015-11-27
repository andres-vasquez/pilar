<?php

class CatalogosController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index($app,$agrupador)
	{
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0)
        {
            $id_sistema=$sistemas[0]["id"];
            $catalogos=array();
            $catalogos_todos = Catalogo::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND agrupador=? ORDER BY CONVERT(value, UNSIGNED INTEGER)',array($id_sistema,$agrupador))->get();
            foreach ($catalogos_todos as $catalogo)
            {
                $formato_catalogos = array();
                $formato_catalogos["id"] =$catalogo["id"];
                $formato_catalogos["label"] =$catalogo["label"];
                $formato_catalogos["value"] =$catalogo["value"];
                $formato_catalogos["value2"] =$catalogo["value2"];
                $formato_catalogos["fecha_creacion"]= date('d-m-Y H:i:s', strtotime($catalogo["created_at"]));
                array_push($catalogos,$formato_catalogos);
            }
            return View::make('ws.json', array("resultado"=>compact('catalogos')));
        }
        else
        {
            $errores="Error al obtener catalogos";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Catalogo::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(Catalogo::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Catalogo')));
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
		$catalogo = Catalogo::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('catalogo')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$catalogo = Catalogo::findOrFail($id);
		$data = Input::all();

		if($catalogo->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('catalogo')));
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
		$catalogo = Catalogo::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($catalogo->update($data))
		{
			$catalogo = Catalogo::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('catalogo')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
