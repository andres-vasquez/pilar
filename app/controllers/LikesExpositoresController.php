<?php

class LikesExpositoresController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$likesexpositores = Likesexpositore::all();

		return View::make('ws.json', array("resultado"=>compact('likesexpositores')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
        $data = Input::all();
        $app=$data["sistema"];
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0) {
            $data["sistema_id"] = $sistemas[0]["id"];
            $data["aud_usuario_id"]=Session::get('id_usuario');
            $validator = Validator::make($data, Likesexpositore::$rules);

            if ($validator->fails())
            {
                $errores=$validator->messages()->first();
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }

            if(Likesexpositore::create($data))
            {
                return View::make('ws.json', array("resultado"=>compact('Likesexpositore')));
            }
            else
            {
                $errores="Error al crear registro";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
        }
		else
		{
			$errores="Error al crear registro en variable sistema";
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
		$likesexpositore = Likesexpositore::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('likesexpositore')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$likesexpositore = Likesexpositore::findOrFail($id);
		$data = Input::all();

		if($likesexpositore->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('likesexpositore')));
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
		$likesexpositore = Likesexpositore::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($likesexpositore->update($data))
		{
			$likesexpositore = Likesexpositore::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('likesexpositore')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

    public function apiconteo($app)
    {
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0) {
            $id_sistema = $sistemas[0]["id"];
        }
        else
        {
            $errores="Error al crear registro en variable sistema";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    public function apitodas($app)
    {
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0) {
            $id_sistema = $sistemas[0]["id"];
        }
        else
        {
            $errores="Error al crear registro en variable sistema";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

}
