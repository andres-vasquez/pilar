<?php

class RegistroGCMsController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$registrogcms = Registrogcm::all();

		return View::make('ws.json', array("resultado"=>compact('registrogcms')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
        $data = Input::all();
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($data["credencial"]))->get();
        if(sizeof($sistemas)>0) {
            $id_sistema = $sistemas[0]["id"];
            $data["sistema_id"]=$id_sistema;
            $data["aud_usuario_id"]="100";
        }

		$validator = Validator::make($data, Registrogcm::$rules);
		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

        $unico= RegistroGCM::whereRaw('sistema_id=? AND token=?',array($data["sistema_id"],$data["token"]))->get();
        if(sizeof($unico)==0) {
            if(Registrogcm::create($data))
            {
                return View::make('ws.json', array("resultado"=>compact('Registrogcm')));
            }
            else
            {
                $errores="Error al crear registro";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
        }
        else
        {
            $errores="Ya se encuentra registrado";
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
		$registrogcm = Registrogcm::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('registrogcm')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$registrogcm = Registrogcm::findOrFail($id);
		$data = Input::all();

		if($registrogcm->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('registrogcm')));
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
		$registrogcm = Registrogcm::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($registrogcm->update($data))
		{
			$registrogcm = Registrogcm::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('registrogcm')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
