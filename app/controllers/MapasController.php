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
        $data=Input::all();
        $contador=0;
        for($i=0;$i<count($data);$i++)
        {
            $datos=$data[$i];
            $validator = Validator::make($datos, Mapa::$rules);
            if ($validator->fails())
            {
                $errores=$validator->messages()->first();
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }

            $datos["aud_usuario_id"] = Session::get('id_usuario');
            $datos["sistema_id"] = Session::get('id_sistema');
            if(Mapa::create($datos))
            {
                $contador++;
            }
            else
            {
                $errores="Error al crear registro";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
        }

        return View::make('ws.json', array("resultado"=>compact('contador')));
        /*
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
		}*/
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

    public function apitodas($app)
    {
        $sistemas = SistemasDesarrollados::whereRaw('app=?', array($app))->get();
        if (sizeof($sistemas) > 0) {
            $id_sistema = $sistemas[0]["id"];

            $mapas_query = Mapa::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? GROUP BY tipo', array($id_sistema))->get();
            if (sizeof($mapas_query) > 0)
            {
                $mapas = array();
                foreach ($mapas_query as $mapa)
                {
                    $categoria=array();
                    $categoria["tipo"]=$mapa["tipo"];
                    $categoria["capa"]=array();

                    $aux = array();
                    if($mapa["tipo"]=="marker")
                    {
                        $aux["id"] = $mapa["id"];
                        //$aux["tipo"] = $mapa["tipo"];
                        $aux["nombre"] = $mapa["nombre"];
                        $aux["icono"] = $mapa["icono"];
                        $aux["punto"] = $mapa["json"];
                    }
                    else if($mapa["tipo"]=="circulo")
                    {
                        $aux["id"] = $mapa["id"];
                        //$aux["tipo"] = $mapa["tipo"];
                        $aux["nombre"] = $mapa["nombre"];
                        $aux["color"] = $mapa["color"];
                        $aux["radio"] = $mapa["atributo2"];
                        $aux["centro"] = json_decode($mapa["atributo1"]);
                    }
                    else if($mapa["tipo"]=="poligono")
                    {
                        $aux["id"] = $mapa["id"];
                        //$aux["tipo"] = $mapa["tipo"];
                        $aux["nombre"] = $mapa["nombre"];
                        $aux["color"] = $mapa["color"];
                        $aux["punto"] = json_decode($mapa["json"]);
                    }
                    else if($mapa["tipo"]=="rectangulo")
                    {
                        $aux["id"] = $mapa["id"];
                        //$aux["tipo"] = $mapa["tipo"];
                        $aux["nombre"] = $mapa["nombre"];
                        $aux["color"] = $mapa["color"];
                        $aux["noreste"] = json_decode($mapa["atributo1"]);
                        $aux["sudoeste"] = json_decode($mapa["atributo2"]);
                    }
                    array_push($categoria["capa"], $aux);
                    array_push($mapas, $categoria);
                }
                return json_encode($mapas);
                return View::make('ws.json', array("resultado" => compact('expositores')));
            }
            else
            {
                $errores="No hay datos";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
        }
    }

}
