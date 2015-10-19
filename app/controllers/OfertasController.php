<?php

class OfertasController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$ofertas = Ofertas::all();

		return View::make('ws.json', array("resultado"=>compact('ofertas')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
        $data = Input::all();
        $data["sistema_id"]=Session::get('id_sistema');
        $data["aud_usuario_id"]=Session::get('id_usuario');
		$validator = Validator::make($data, Ofertas::$rules);
		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if($insert=Ofertas::create($data))
		{
            $id=$insert->id;

            $oferta_nueva = Ofertas::findOrFail($id);
            $datos = array();
            $datos["link"]="pilar/api/v1/ofertas/".Session::get('credencial')."/".$id."/web";

            if($oferta_nueva->update($datos))
                return View::make('ws.json', array("resultado"=>compact('Noticia')));
            else
            {
                $errores="Error al crear registro";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
			//return View::make('ws.json', array("resultado"=>compact('Oferta')));
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
		$oferta = Ofertas::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('oferta')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$oferta = Ofertas::findOrFail($id);
		$data = Input::all();

		if($oferta->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('oferta')));
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
		$oferta = Ofertas::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($oferta->update($data))
		{
			$oferta = Ofertas::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('oferta')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}


    public function apitodassinformato($app)
    {
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0) {
            $id_sistema = $sistemas[0]["id"];

            $ofertas=array();
            $ofertas_todos = Ofertas::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=?',array($id_sistema))->get();
            foreach ($ofertas_todos as $oferta)
            {
                $formato_ofertas = array();
                $formato_ofertas["id"] =$oferta["id"];
                $formato_ofertas["rubro_id"] =$oferta["rubro_id"];
                $formato_ofertas["rubro"] =$oferta["rubro"];
                $formato_ofertas["expositor_id"] =$oferta["expositor_id"];
                $formato_ofertas["expositor"] =$oferta["expositor"];

                if($oferta["expositor"]=="")
                    $formato_ofertas["empresa"] =$oferta["empresa"];
                else
                    $formato_ofertas["empresa"] =$oferta["expositor"];


                $formato_ofertas["empresa"] =$oferta["empresa"];
                $formato_ofertas["link"] =$oferta["link"];
                $formato_ofertas["fecha"]= date('d-m-Y H:i:s', strtotime($oferta["created_at"]));
                array_push($ofertas,$formato_ofertas);
            }
            return json_encode($ofertas);
            return View::make('ws.json', array("resultado"=>compact('catalogos')));
        }
        else
        {
            $errores="Error al obtener registro";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    public function web($app,$id_oferta,$metodo)
    {
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0) {
            $id_sistema = $sistemas[0]["id"];

            $oferta = Ofertas::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND id=?',array($id_sistema,$id_oferta))->get();
            if(sizeof($oferta)>0)
            {
                if($metodo=="web")
                {
                    $datos=$oferta[0];
                    return View::make('sitio.master.solo_html', array("resultado"=>compact('datos')));
                }
                else
                {
                    return $oferta[0]["html"];
                }
            }
            else
            {
                $errores="Error al obtener registro";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
        }
    }

    public function rubrosconoferta($app)
    {
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0)
        {
            $id_sistema = $sistemas[0]["id"];
            $query = DB::connection('Pilar')->select("SELECT rubro_id AS id, rubro AS nombre FROM ofertas WHERE sistema_id=".$id_sistema." GROUP BY rubro_id ORDER BY rubro");
            if (sizeof($query))
            {
                $resultado=array();
                foreach ($query as $data) {
                    $datos = array();
                    $datos["id"] = $data->id;
                    $datos["nombre"] = $data->nombre;
                    array_push($resultado, $datos);
                }
                return View::make('ws.json', array("resultado"=>compact('resultado')));
            }
            else
            {
                $errores="Error al obtener registros";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
        }
        else
        {
            $errores="Error con la credencial";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    public function expositoresporrubro($app,$id_rubro)
    {
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0)
        {
            $id_sistema = $sistemas[0]["id"];
            $query = DB::connection('Pilar')->select("SELECT rubro_id, rubro, expositor_id, expositor, link, empresa  FROM ofertas WHERE sistema_id=".$id_sistema." AND rubro_id=".$id_rubro." AND estado=1 AND baja_logica=1 ORDER BY expositor");
            if (sizeof($query))
            {
                $resultado=array();
                foreach ($query as $data) {
                    $datos = array();
                    $datos["id_expositor"] = $data->expositor_id;
                    $datos["expositor"] = $data->expositor;
                    $datos["empresa"] = $data->empresa;
                    $datos["id_rubro"] = $data->rubro_id;
                    $datos["rubro"] = $data->rubro;
                    $datos["link"] = $data->link;
                    array_push($resultado, $datos);
                }
                return View::make('ws.json', array("resultado"=>compact('resultado')));
            }
            else
            {
                $errores="Error al obtener registros";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
        }
        else
        {
            $errores="Error con la credencial";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }
}
