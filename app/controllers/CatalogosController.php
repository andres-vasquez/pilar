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

                if($id_sistema==8 && $catalogo["idpadre"]!="0")
                {
                    $formato_catalogos["idPadre"] =$catalogo["idpadre"];
                    $catalogoPadre = Catalogo::findOrFail($catalogo["idpadre"]);
                    $formato_catalogos["padre"] =$catalogoPadre["label"];
                }


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

    public function thumbnail($app,$agrupador)
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

                $imagen="";
                $noticias_filtro = Noticia::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND FIND_IN_SET(?, tags) ORDER BY created_at DESC LIMIT 1',array($id_sistema,$catalogo["value"]))->get();
                if(sizeof($noticias_filtro)>0)
                foreach ($noticias_filtro as $noticia)
                    $imagen=$noticia["url_imagen"];

                $formato_catalogos["thumbnail"] =$imagen;
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

    public function listarCatalogos($app)
    {
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0)
        {
            $id_sistema=$sistemas[0]["id"];
            $catalogos=array();
            $catalogos_todos = Catalogo::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? GROUP BY agrupador',array($id_sistema))->get();
            foreach ($catalogos_todos as $catalogo)
            {
                $formato_catalogos = array();
                $formato_catalogos["agrupador"] =$catalogo["agrupador"];

                $request = Request::create('/api/v1/catalogos/'.$app.'/'.$catalogo["agrupador"], 'GET', array());
                $datos=array();
                $datos= json_decode(Route::dispatch($request)->getContent(),true);
                if($datos["intCodigo"]==1)
                {
                    $formato_catalogos["datos"]=$datos["resultado"]["catalogos"];
                }

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
}
