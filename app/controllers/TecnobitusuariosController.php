<?php

class TecnobitusuariosController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$tecnobitusuarios = Tecnobitusuario::all();

		return View::make('ws.json', array("resultado"=>compact('tecnobitusuarios')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
        $data = Input::All();
        if (isset($data["credencial"])) {
            $sistemas = SistemasDesarrollados::whereRaw('app=?', array($data["credencial"]))->get();
            if (sizeof($sistemas) > 0) {

                $validator = Validator::make($data = Input::all(), Tecnobitusuario::$rules);
                if ($validator->fails()) {
                    $errores = $validator->messages()->first();
                    return View::make('ws.json_errores', array("errores" => compact('errores')));
                }

                if ($insert = Tecnobitusuario::create($data)) {
                    $id = $insert->id;

                    if(isset($data["origen"]))
                        $origen=$data["origen"];
                    else
                        $origen="android";

                    try
                    {
                        $request1 = Request::create('ws/tecnobit/acceso/'.$id.'/'.$origen, 'GET', array());
                        $acceso = json_decode(Route::dispatch($request1)->getContent(), true);
                    }
                    catch (Exception $e){}

                    return View::make('ws.json', array("resultado" => compact('id')));
                } else {
                    $errores = "Error al crear registro";
                    return View::make('ws.json_errores', array("errores" => compact('errores')));
                }
            } else {
                $errores = "Ingreso no autorizado, credencial inválida";
                return View::make('ws.json_errores', array("errores" => compact('errores')));
            }
        } else {
            $errores = "Ingreso no autorizado";
            return View::make('ws.json_errores', array("errores" => compact('errores')));
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
		$tecnobitusuario = Tecnobitusuario::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('tecnobitusuario')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$tecnobitusuario = Tecnobitusuario::findOrFail($id);
		$data = Input::all();

		if($tecnobitusuario->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('tecnobitusuario')));
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
		$tecnobitusuario = Tecnobitusuario::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($tecnobitusuario->update($data))
		{
			$tecnobitusuario = Tecnobitusuario::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('tecnobitusuario')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

    public function autenticacion()
    {
        $data = Input::All();
        if (isset($data["credencial"])) {
            $sistemas = SistemasDesarrollados::whereRaw('app=?', array($data["credencial"]))->get();
            if (sizeof($sistemas) > 0)
            {
                if (isset($data["email"]) && isset($data["password"]))
                {
                    //Ingreso con usuario y pass
                    $usuarioGral = Tecnobitusuario::whereRaw('email=? AND password=? AND estado=1 AND baja_logica=1', array($data["email"],$data["password"]))->get();
                    if (sizeof($usuarioGral))
                    {
                        $datos = array();
                        $datos["email"] = $usuarioGral[0]["email"];
                        $datos["nombre"] = $usuarioGral[0]["nombre"];
                        $datos["id_usuario"] = $usuarioGral[0]["id"];

                        if(isset($data["origen"]))
                            $origen=$data["origen"];
                        else
                            $origen="android";

                        try
                        {
                            $request1 = Request::create('ws/tecnobit/acceso/'.$datos["id_usuario"].'/'.$origen, 'GET', array());
                            $acceso = json_decode(Route::dispatch($request1)->getContent(), true);
                        }
                        catch (Exception $e){}


                        return View::make('ws.json', array("resultado" => compact('datos')));
                    }
                    else
                    {
                        $errores = "Ingreso no autorizado, email o password erróneo";
                        return View::make('ws.json_errores', array("errores" => compact('errores')));
                    }
                }
                else if(isset($data["id_fb"]))
                {
                    //Ingreso con Facebook
                    $usuarioGral = Tecnobitusuario::whereRaw('id_fb=? AND estado=1 AND baja_logica=1', array($data["id_fb"]))->get();
                    if (sizeof($usuarioGral))
                    {
                        $datos = array();
                        $datos["email"] = $usuarioGral[0]["email"];
                        $datos["nombre"] = $usuarioGral[0]["nombre"];
                        $datos["id_usuario"] = $usuarioGral[0]["id"];

                        if(isset($data["origen"]))
                            $origen=$data["origen"];
                        else
                            $origen="android";

                        try
                        {
                            $request1 = Request::create('ws/tecnobit/acceso/'.$datos["id_usuario"].'/'.$origen, 'GET', array());
                            $acceso = json_decode(Route::dispatch($request1)->getContent(), true);
                        }
                        catch (Exception $e){}


                        return View::make('ws.json', array("resultado" => compact('datos')));
                    }
                    else
                    {
                        $errores = "Ingreso no autorizado, email o password erróneo";
                        return View::make('ws.json_errores', array("errores" => compact('errores')));
                    }
                }
                else {
                    $errores = "Ingreso no autorizado, faltan datos";
                    return View::make('ws.json_errores', array("errores" => compact('errores')));
                }
            } else {
                $errores = "Ingreso no autorizado, credencial inválida";
                return View::make('ws.json_errores', array("errores" => compact('errores')));
            }

        } else {
            $errores = "Ingreso no autorizado";
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }
    }

    public function sinformato()
    {
        $smsusuarios = Tecnobitusuario::whereRaw('estado=1 AND baja_logica=1')->get();
        $resultado = array();
        foreach ($smsusuarios as $usuario) {
            $aux = array();
            $aux["id"] = $usuario["id"];
            $aux["nombre"] = $usuario["nombre"];
            $aux["email"] = $usuario["email"];

            if($usuario["id_fb"]=="0")
                $aux["acceso"] = "email";
            else
                $aux["acceso"] = "facebook";

            $aux["dispositivo"]="";
            $aux["ultimo_acceso"]="";

            $acceso = DB::connection('Pilar')->select('SELECT created_at, origen  FROM tecnobit_acceso WHERE usuario_id=? LIMIT 1',array($usuario["id"]));
            foreach ($acceso as $tipo) {
                $aux["dispositivo"]=$tipo->origen;
                $aux["ultimo_acceso"]=$tipo->created_at;
            }

            if($aux["ultimo_acceso"]=="")
                $aux["ultimo_acceso"]="-";

            if($aux["dispositivo"]=="")
                $aux["dispositivo"]="-";

            array_push($resultado, $aux);
        }

        return json_encode($resultado);
    }
}
