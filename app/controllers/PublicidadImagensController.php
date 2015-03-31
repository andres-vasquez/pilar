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
        $app=Session::get('credencial');
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();


        $imagen=Input::file('imagen');
        $nombre_imagen=time().$imagen->getClientOriginalName();
        $upload=$imagen->move('public/uploads/'.$sistemas[0]["nombre"].'/',$nombre_imagen);
        if($upload)
        {
            $data = Input::all();
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
            }
        }
        else
        {
            $errores="Error al subir imagen";
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
		$publicidadimagen = PublicidadImagen::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($publicidadimagen->update($data))
		{
			$publicidadimagen = PublicidadImagen::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('publicidadimagen')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

    public function mostrarImagen($nombre_archivo)
    {
        $file="public/uploads/feicobol/".$nombre_archivo;

        $response = Response::make(File::get($file));
        $response->header('Content-Type', 'image/jpeg');
        $response->header('Content-Type', 'image/png');
        return $response;
    }

}
