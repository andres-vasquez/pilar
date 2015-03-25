<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
class PublicidadsController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$publicidads = Publicidad::all();

		return View::make('ws.json', array("resultado"=>compact('publicidads')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Publicidad::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

        $data["sistema_id"]=Session::get('id_sistema');
        $data["aud_usuario_id"]=Session::get('id_usuario');
		if($insert=Publicidad::create($data))
		{
            $id=$insert->id;
			return View::make('ws.json', array("resultado"=>compact('id')));
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
		$publicidad = Publicidad::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('publicidad')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$publicidad = Publicidad::findOrFail($id);
		$data = Input::all();

		if($publicidad->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('publicidad')));
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
		$publicidad = Publicidad::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($publicidad->update($data))
		{
			$publicidad = Publicidad::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('publicidad')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
