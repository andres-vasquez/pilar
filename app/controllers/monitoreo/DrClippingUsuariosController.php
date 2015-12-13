<?php

class DrClippingUsuariosController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$drclippingusuarios = DrClippingUsuario::all();

		return View::make('ws.json', array("resultado"=>compact('drclippingusuarios')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), DrClippingUsuario::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

        $existe = DrClippingUsuario::whereRaw('email=?', array($data["email"]))->get();
        if (sizeof($existe) == 0)
        {
            if (DrClippingUsuario::create($data)) {
                return View::make('ws.json', array("resultado" => compact('Drclippingusuario')));
            } else {
                $errores = "Error al crear registro";
                return View::make('ws.json_errores', array("errores" => compact('errores')));
            }
        }
        else {
            $errores = "El email está vinculado a otra cuenta.";
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
		$drclippingusuario = DrClippingUsuario::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('drclippingusuario')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$drclippingusuario = DrClippingUsuario::findOrFail($id);
		$data = Input::all();

		if($drclippingusuario->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('drclippingusuario')));
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
		$drclippingusuario = DrClippingUsuario::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($drclippingusuario->update($data))
		{
			$drclippingusuario = DrClippingUsuario::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('drclippingusuario')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

    public function sinformato()
    {
        $smsusuarios = DrClippingUsuario::whereRaw('estado=1 AND baja_logica=1')->get();
        $resultado = array();
        foreach ($smsusuarios as $usuario) {
            $aux = array();
            $aux["id"] = $usuario["id"];
            $aux["email"] = $usuario["email"];
            $aux["nombre_completo"] = $usuario["nombre_completo"];
            $aux["celular"] = $usuario["celular"];
            $aux["imei"] = $usuario["imei"];

            $aux["ultimo_acceso"]="";
            $acceso = DB::connection('DrClipping')->select('SELECT created_at  FROM t_acceso WHERE usuario_id=? ORDER BY created_at DESC LIMIT 1',array($usuario["id"]));
            foreach ($acceso as $fecha) {
                $aux["ultimo_acceso"]=$fecha->created_at;
            }

            array_push($resultado, $aux);
        }

        return json_encode($resultado);
    }

    public function autenticacion()
    {
        $data = Input::all();
        if(isset($data["email"]) && isset($data["pass"]))
        {
            if(isset($data["imei"]))
            {
                $email=$data["email"];
                $password=$data["pass"];

                $usuario=DrClippingUsuario::whereRaw('email=? AND password=?',array($email,$password))->get();
                if(sizeof($usuario)>0)
                {
                    if($usuario[0]["estado"]!="1")
                    {
                        $errores="Usuario deshabilitado";
                        return View::make('ws.json_errores', array("errores"=>compact('errores')));
                    }
                    else if($usuario[0]["baja_logica"]=="0")
                    {
                        $errores="Usuario eliminado";
                        return View::make('ws.json_errores', array("errores"=>compact('errores')));
                    }
                    else
                    {
                        $imei=md5($usuario[0]["imei"]);
                        if($imei==$data["imei"])
                        {
                            Input::all()["usuario_id"]=$usuario[0]["id"];

                            $request = Request::create('/ws/drclipling/acceso', 'POST', array(
                                'usuario_id'=>$usuario[0]["id"],
                                'imei'=>$usuario[0]["imei"],
                                'fecha_telefono'=>$data["fecha_telefono"],
                                'version_apk'=>$data["version_apk"],
                                'datos_telefono'=>$data["datos_telefono"],
                                'aud_usuario_id'=>1
                            ));

                            $datos=array();
                            Request::replace($request->input());
                            json_decode(Route::dispatch($request)->getContent(),true);

                            $datos["nombre_completo"]=$usuario[0]["nombre_completo"];
                            $datos["email"]=$usuario[0]["email"];
                            $datos["id_usuario"]=$usuario[0]["id"];
                            return View::make('ws.json', array("resultado"=>compact('datos')));
                        }
                        else
                        {
                            $errores="Dispositivo no permitido. Registre el IMEI de su dispositivo";
                            return View::make('ws.json_errores', array("errores"=>compact('errores')));
                        }
                    }
                }
                else
                {
                    $errores="Email o contraseña incorrectos";
                    return View::make('ws.json_errores', array("errores"=>compact('errores')));
                }
            }
            else
            {
                $errores="Error al decodificar Imei";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
        }
        else
        {
            $errores="Ingrese Email y Contraseña";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }

    }
}
