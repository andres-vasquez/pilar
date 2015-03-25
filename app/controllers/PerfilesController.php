<?php

class PerfilesController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$perfiles = Perfiles::all();

		return View::make('ws.json', array("resultado"=>compact('perfiles')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Perfiles::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(Perfiles::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Perfiles')));
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
		$perfile = Perfiles::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('perfile')));
	}


	public function ArbolPerfiles()
	{	
		$sistemasdesarrollados = Sistemasdesarrollados::all();
		if(sizeof($sistemasdesarrollados)>0)
		{
			$sistemas=array();
			foreach ($sistemasdesarrollados as $sistema)
			{
				$aux=array();
				$aux["id"]=$sistema["id"];
				$aux["nombre"]=$sistema["nombre"];
				$aux["descripcion"]=$sistema["descripcion"];

				$aux["perfiles"]=array();
				$arrPerfiles=Perfiles::whereRaw('sistema_id=? AND estado=1 AND baja_logica!=0', array($sistema["id"]))->get();
				if(sizeof($arrPerfiles)>0)
				{
					foreach($arrPerfiles as $perfil)
					{
						$aux2=array();
						$aux2["id"]=$perfil["id"];
						$aux2["nombre"]=$perfil["nombre"];
						array_push($aux["perfiles"], $aux2);
					}
				}

				array_push($sistemas, $aux);
			}	
		}
		else
			$sistemas="";
		return View::make('ws.json', array("resultado"=>compact('sistemas')));	
	}

	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$perfile = Perfiles::findOrFail($id);
		$data = Input::all();

		if($perfile->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('perfile')));
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
		$perfile = Perfiles::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($perfile->update($data))
		{
			$perfile = Perfiles::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('perfile')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
