<?php

class SmsMensajesController extends \BaseController
{

    /**
     * Muestra todas los registros
     *
     * @return Response
     */
    public function index()
    {
        $smsmensajes = SmsMensaje::all();

        return View::make('ws.json', array("resultado" => compact('smsmensajes')));
    }


    /**
     * Creara un registro con los datos ingresados
     *
     * @return Response
     */
    public function store()
    {
        $archivo = Input::file('mensajes');
        $nombre_archivo = time() . $archivo->getClientOriginalName();
        $upload = $archivo->move('public/uploads/sms' . '/', $nombre_archivo);
        if ($upload) {
            $file = "public/uploads/sms" . "/" . $nombre_archivo;

            $columnas = array("numero", "mensaje", "marcacion", "fecha");
            $cantidadColumnas = count($columnas);
            $obj = array();

            $handle = fopen($file, 'r');
            if ($handle) {
                while (!feof($handle))
                {
                    $carga = array();
                    while (($csv_row = fgetcsv($handle, 3000000, ',')) !== false)
                    {
                        $contadorFilas = 0;
                        foreach ($csv_row as &$row)
                        {
                            $variasFilas = preg_split( '/\r\n|\r|\n/', $row);
                            for ($i = 0; $i < count($variasFilas); $i++)
                            {
                                $obj[$columnas[$contadorFilas]] = $variasFilas[$i];
                                $contadorFilas++;

                                if ($contadorFilas == $cantidadColumnas)
                                {
                                    $obj["aud_usuario_id"] = Session::get('id_usuario');
                                    $obj["sistema_id"] = Session::get('id_sistema');

                                    if($obj["numero"]!="numero") //TODO hacerlo dinamico
                                        array_push($carga,$obj);
                                    $contadorFilas = 0;
                                    $obj = array();
                                }
                            }
                        }

                    }
                }
                fclose($handle);

                $objResultado = array();
                $contadorInsert = 0;
                $contadorError = 0;
                $contadorExistentes = 0;

                foreach ($carga as $objCarga)
                {
                    $query = SmsMensaje::whereRaw('estado=1 AND baja_logica=1 AND numero=? AND fecha=?',array($objCarga["numero"],$objCarga["fecha"]))->get();
                    if(sizeof($query)==0)
                    {
                        if($insert=SmsMensaje::create($objCarga)) {
                            $id = $insert->id;
                            if($id>0)
                                $contadorInsert++;
                            else
                                $contadorError++;
                        }
                        else
                            $contadorError++;

                    }
                    else
                        $contadorExistentes++;
                }

                $objResultado["insertados"]=$contadorInsert;
                $objResultado["existentes"]=$contadorExistentes;
                $objResultado["error"]=$contadorError;
                return View::make('ws.json', array("resultado" => compact('objResultado')));

            } else {
                $errores = "Error al abrir el archivo";
                return View::make('ws.json_errores', array("errores" => compact('errores')));
            }
        } else {
            $errores = "Error al subir el archivo";
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }

        /*
		$validator = Validator::make($data = Input::all(), Smsmensaje::$rules);
		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(Smsmensaje::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Smsmensaje')));
		}
		else
		{

		}*/
    }

    /**
     * Muestra el registro segun el ID ingresado.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $smsmensaje = SmsMensaje::findOrFail($id);
        return View::make('ws.json', array("resultado" => compact('smsmensaje')));
    }


    /**
     * Actualiza el registro segun el id ingresado
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $smsmensaje = SmsMensaje::findOrFail($id);
        $data = Input::all();

        if ($smsmensaje->update($data)) {
            return View::make('ws.json', array("resultado" => compact('smsmensaje')));
        } else {
            $errores = "Error al actualizar registro";
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }
    }

    /**
     * Define baja logica a l registro indicado
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $smsmensaje = SmsMensaje::findOrFail($id);
        $data = array();

        $data["baja_logica"] = "0";
        $data["estado"] = "0";
        if ($smsmensaje->update($data)) {
            $smsmensaje = SmsMensaje::findOrFail($id);
            return View::make('ws.json', array("resultado" => compact('smsmensaje')));
        } else {
            $errores = "Error al eliminar registro";
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }
    }


    public function cantidad($todos)
    {
        if ($todos != "0") {
            $query = SmsMensaje::whereRaw('estado=1 AND baja_logica=1')->get();
            $total = sizeof($query);
            return View::make('ws.json', array("resultado" => compact('total')));
        } else {
            $mes = date('m');
            $query = SmsMensaje::whereRaw('estado=1 AND baja_logica=1 AND MONTH(fecha)=?', array($mes))->get();
            $total = sizeof($query);
            return View::make('ws.json', array("resultado" => compact('total')));
        }
    }

    public function graficoDashboard($mes)
    {
        $resultado = array();
        $query = DB::connection('Sms')->select('SELECT count(1) AS cantidad, DATE(fecha) as fecha FROM SmsMensaje WHERE MONTH(fecha)=? GROUP BY fecha', array($mes));
        if (sizeof($query) > 0) {
            foreach ($query as $dato)
            {
                $aux = array();
                $aux["cantidad"] = $dato->cantidad;
                $aux["fecha"] = date('d-m-Y',strtotime($dato->fecha));
                array_push($resultado, $aux);
            }
            DB::disconnect('Sms');
            return json_encode($resultado);
        }
        return json_encode($resultado);
    }
}
