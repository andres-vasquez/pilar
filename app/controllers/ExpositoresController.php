<?php

class ExpositoresController extends \BaseController
{

    /**
     * Muestra todas los registros
     *
     * @return Response
     */
    public function index()
    {
        $expositores = Expositore::all();

        return View::make('ws.json', array("resultado" => compact('expositores')));
    }


    /**
     * Creara un registro con los datos ingresados
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make($data = Input::all(), Expositore::$rules);

        if ($validator->fails()) {
            $errores = $validator->messages()->first();
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }

        if (Expositore::create($data)) {
            return View::make('ws.json', array("resultado" => compact('Expositore')));
        } else {
            $errores = "Error al crear registro";
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }
    }

    /**
     * Muestra el registro segun el ID ingresado.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $expositore = Expositore::findOrFail($id);
        return View::make('ws.json', array("resultado" => compact('expositore')));
    }


    /**
     * Actualiza el registro segun el id ingresado
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $expositore = Expositore::findOrFail($id);
        $data = Input::all();

        if ($expositore->update($data)) {
            return View::make('ws.json', array("resultado" => compact('expositore')));
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
        $expositore = Expositore::findOrFail($id);
        $data = array();

        $data["baja_logica"] = "0";
        $data["estado"] = "0";
        if ($expositore->update($data)) {
            $expositore = Expositore::findOrFail($id);
            return View::make('ws.json', array("resultado" => compact('expositore')));
        } else {
            $errores = "Error al eliminar registro";
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }
    }


    /**
     * @return mixed
     */
    public function importar()
    {
        $app = Session::get('credencial');
        $sistemas = SistemasDesarrollados::whereRaw('app=?', array($app))->get();

        $archivo = Input::file('filecsv');
        $nombre_archivo = time() . $archivo->getClientOriginalName();
        $upload = $archivo->move('public/uploads/' . $sistemas[0]["nombre"] . '/', $nombre_archivo);
        if ($upload) {
            $file = "public/uploads/" . $sistemas[0]["nombre"] . "/" . $nombre_archivo;
            $columnas = array("nombre", "direccion", "pabellon", "stand", "website", "fanpage");
            $cantidadColumnas = count($columnas);
            $objResultado = array();
            $contadorFinal = 0;
            $obj = array();

            $handle = fopen($file, 'r');
            if ($handle)
            {
                while (!feof($handle))
                {
                    while (($csv_row = fgetcsv($handle, 3000, ';')) !== false)
                    {
                        Expositore::truncate();
                        $contadorFilas = 0;
                        foreach ($csv_row as &$row) {
                            $variasFilas = explode("\r", $row);
                            for ($i = 0; $i < count($variasFilas); $i++) {
                                $obj[$columnas[$contadorFilas]] = $variasFilas[$i];
                                $contadorFilas++;
                                if ($contadorFilas == $cantidadColumnas) {
                                    $contadorFilas = 0;

                                    $obj["aud_usuario_id"] = Session::get('id_usuario');
                                    $obj["sistema_id"] = Session::get('id_sistema');

                                    $validator = Validator::make($data = $obj, Expositore::$rules);
                                    if ($validator->fails()) {
                                        $errores = $validator->messages()->first();
                                        return View::make('ws.json_errores', array("errores" => compact('errores')));
                                    }

                                    if (Expositore::create($data)) {
                                        $contadorFinal++;
                                    }
                                    $obj = array();
                                }
                            }
                        }

                        $carga = array();
                        $carga["cantidad"] = $contadorFinal;
                        return View::make('ws.json', array("resultado" => compact('carga')));
                    }

                }
            } else {
                return "no hay";
            }
            /*$data = Input::all();
            $data["ruta"]='public/uploads/'.$sistemas[0]["nombre"].'/'.$nombre_imagen;
            $data["aud_usuario_id"]=Session::get('id_usuario');

            $validator = Validator::make($data, Publicidadimagen::$rules);
            if ($validator->fails())
            {
                $errores=$validator->messages()->first();
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }

            if(Publicidadimagen::create($data))
            {
                $carga=array();
                $carga["ruta"]=$data["ruta"];
                $carga["id"]=$data["tipo"].'_'.$data["sizex"].'x'.$data["sizey"];

                return View::make('ws.json', array("resultado"=>compact('carga')));
            }
            else
            {
                $errores="Error al crear registro";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }*/
        } else {
            $errores = "Error al subir csv";
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }
    }

    public function apitodas($app)
    {
        $sistemas = SistemasDesarrollados::whereRaw('app=?', array($app))->get();
        if (sizeof($sistemas) > 0) {
            $id_sistema = $sistemas[0]["id"];

            $expositores_query = Expositore::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=?', array($id_sistema))->get();
            if (sizeof($expositores_query) > 0) {
                $expositores = array();
                foreach ($expositores_query as $expositor) {
                    $aux = array();
                    $aux["id"] = $expositor["id"];
                    $aux["nombre"] = $expositor["nombre"];
                    $aux["direccion"] = $expositor["direccion"];
                    $aux["pabellon"] = $expositor["pabellon"];
                    $aux["stand"] = $expositor["stand"];
                    $aux["website"] = $expositor["website"];
                    $aux["fanpage"] = $expositor["fanpage"];
                    $aux["fecha_creacion"] = date('d-m-Y H:i:s', strtotime($expositor["created_at"]));
                    array_push($expositores,$aux);
                }
                return View::make('ws.json', array("resultado"=>compact('expositores')));
            }
        }

    }

    public function apitodassinformato($app)
    {
        $sistemas = SistemasDesarrollados::whereRaw('app=?', array($app))->get();
        if (sizeof($sistemas) > 0) {
            $id_sistema = $sistemas[0]["id"];

            $expositores_query = Expositore::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=?', array($id_sistema))->get();
            if (sizeof($expositores_query) > 0) {
                $expositores = array();
                foreach ($expositores_query as $expositor) {
                    $aux = array();
                    $aux["id"] = $expositor["id"];
                    $aux["nombre"] = $expositor["nombre"];
                    $aux["direccion"] = $expositor["direccion"];
                    $aux["pabellon"] = $expositor["pabellon"];
                    $aux["stand"] = $expositor["stand"];
                    $aux["website"] = $expositor["website"];
                    $aux["fanpage"] = $expositor["fanpage"];
                    $aux["fecha_creacion"] = date('d-m-Y H:i:s', strtotime($expositor["created_at"]));
                    array_push($expositores,$aux);
                }
                return json_encode($expositores);
                return View::make('ws.json', array("resultado"=>compact('expositores')));
            }
        }

    }
}
