<?php

class DrClippingAnalisesController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$drclippinganalises = DrClippingAnalisi::all();

		return View::make('ws.json', array("resultado"=>compact('drclippinganalises')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), DrClippingAnalisi::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(DrClippingAnalisi::create($data))
		{
            $drclippingpublicacion = DrClippingPublicacion::findOrFail($data["publicacion_id"]);
            $datos = array();
            $datos["estado_tarea"] = "1";
            $drclippingpublicacion->update($datos);

			return View::make('ws.json', array("resultado"=>compact('Drclippinganalise')));
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
		$drclippinganalise = DrClippingAnalisi::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('drclippinganalise')));
	}

    public function porpublicacion($publicacion_id)
    {
        $analisis = DrClippingAnalisi::whereRaw('estado=1 AND baja_logica=1 AND publicacion_id=? ORDER BY created_at DESC LIMIT 1',array($publicacion_id))->get();
        return View::make('ws.json', array("resultado"=>compact('analisis')));
    }

	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$drclippinganalise = DrClippingAnalisi::findOrFail($id);
		$data = Input::all();

		if($drclippinganalise->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('drclippinganalise')));
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
		$drclippinganalise = DrClippingAnalisi::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($drclippinganalise->update($data))
		{
			$drclippinganalise = DrClippingAnalisi::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('drclippinganalise')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

    public function foto()
    {
        $data = Input::all();
        $publicacion_id=$data["publicacion_id"];
        $url=$data["url"];
        $foto=$data["foto"];
        $imagen=$data["imagen"];

        $url_amazon="https://s3.amazonaws.com/drclipping/";
        $url=str_replace($url_amazon,"",$url);

        $app=Session::get('credencial');
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();

        if (!file_exists('public/uploads/'.$sistemas[0]["nombre"])) {
            mkdir('public/uploads/'.$sistemas[0]["nombre"], 0777, true);
        }

        $output_file='public/uploads/'.$sistemas[0]["nombre"].'/'.$url;
        $imagen=str_replace("data:image/png;base64,","",$imagen);

        $ifp = fopen($output_file, "wb");
        fwrite($ifp, base64_decode($imagen));
        fclose($ifp);

        $nueva_url=str_replace(".png","_1",$url)."png";

        try
        {
            $s3 = AWS::get('s3');
            $s3->putObject(array(
                'Bucket'     => "drclipping",
                'Key'        => $nueva_url,
                'SourceFile' => $output_file
            ));

            $drclippingpublicacion = DrClippingPublicacion::findOrFail($publicacion_id);
            $datos=array();

            if($foto=="foto1")
                $datos["url_foto1"]=$url_amazon.$nueva_url;
            else
                $datos["url_foto2"]=$url_amazon.$nueva_url;

            $resultado=$url_amazon.$nueva_url;
            if($drclippingpublicacion->update($datos))
            {
                return View::make('ws.json', array("resultado"=>compact('resultado')));
            }
        }catch (Exception $ex)
        {
            $errores=$ex;
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }

    }



    /**************** AREA DE REPORTES ************************/

    public function graficoDashboard($ano,$mes)
    {
        $resultado = array();
        $resultado["publicaciones"]=array();
        $resultado["analisis"]=array();
        $query = DB::connection('DrClipping')->select('SELECT count(1) AS cantidad, DATE(created_at) as fecha FROM t_publicacion WHERE MONTH(created_at)=? AND YEAR(created_at)=? GROUP BY DATE(created_at)', array($mes,$ano));
        if (sizeof($query) > 0) {
            foreach ($query as $dato)
            {
                $aux = array();
                $aux["cantidad"] = $dato->cantidad;
                $aux["fecha"] = date('d-m-Y',strtotime($dato->fecha));
                array_push($resultado["publicaciones"], $aux);
            }
            DB::disconnect('DrClipping');
        }

        $query = DB::connection('DrClipping')->select('SELECT count(1) AS cantidad, DATE(created_at) as fecha FROM t_analisis WHERE MONTH(created_at)=? AND YEAR(created_at)=? GROUP BY DATE(created_at)', array($mes,$ano));
        if (sizeof($query) > 0) {
            foreach ($query as $dato)
            {
                $aux = array();
                $aux["cantidad"] = $dato->cantidad;
                $aux["fecha"] = date('d-m-Y',strtotime($dato->fecha));
                array_push($resultado["analisis"], $aux);
            }
            DB::disconnect('DrClipping');
        }

        return json_encode($resultado);
    }
}
