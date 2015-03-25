<?php

class AsignacionMenusController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$asignacionmenus = Asignacionmenus::all();
		return View::make('ws.json', array("resultado"=>compact('asignacionmenus')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Asignacionmenus::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(Asignacionmenus::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Asignacionmenus')));
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
		$asignacionmenus = Asignacionmenus::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('asignacionmenus')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$asignacionmenus = Asignacionmenus::findOrFail($id);
		$data = Input::all();

		if($asignacionmenus->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('asignacionmenus')));
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
		$asignacionmenus = Asignacionmenus::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($asignacionmenus->update($data))
		{
			$asignacionmenus = Asignacionmenus::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('asignacionmenus')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}


    public function datos($id_perfil)
    {
        $datos=array();
            $aux=array();
            $lstMenusAsignados=AsignacionMenu::whereRaw('perfil_id=? AND estado=1 AND baja_logica!=0', array($id_perfil))->get();
            foreach($lstMenusAsignados as $Menu)
            {
                //Obtenemos el nombre del sistema
                $request = Request::create('/ws/menu/'.$Menu["menu_id"], 'GET', array());

                $aux2=array();
                $aux2=json_decode(Route::dispatch($request)->getContent(),true);
                $aux["resultado"]["menus"]=$aux2["resultado"]["menus"];

                $datos_menus=$aux["resultado"];
                array_push($datos, $datos_menus);
            }

        //$menus = Menu::findOrFail($id);
        return View::make('ws.json', array("resultado"=>compact('datos')));
    }
}
