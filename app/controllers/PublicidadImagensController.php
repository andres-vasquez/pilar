<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
class PublicidadImagensController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$publicidadimagens = Publicidadimagen::all();

		return View::make('ws.json', array("resultado"=>compact('publicidadimagens')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
        $imagen=Input::file('imagen');
        $nombre_imagen=time().$imagen->getClientOriginalName();
        $upload=$imagen->move('public/uploads',$nombre_imagen);
        if($upload)
        {
            $mensaje="ok";
            return View::make('ws.json', array("resultado"=>compact('mensaje')));
        }
        else
        {
            $errores="Error al subir imagen";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }

        $data = Input::all();
        return "ok";
        /*
		$validator = Validator::make($data = Input::all(), Publicidadimagen::$rules);
		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(Publicidadimagen::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Publicidadimagen')));
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
		$publicidadimagen = Publicidadimagen::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('publicidadimagen')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$publicidadimagen = Publicidadimagen::findOrFail($id);
		$data = Input::all();

		if($publicidadimagen->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('publicidadimagen')));
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
		$publicidadimagen = Publicidadimagen::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($publicidadimagen->update($data))
		{
			$publicidadimagen = Publicidadimagen::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('publicidadimagen')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
