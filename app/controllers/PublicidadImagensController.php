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
		$publicidadimagens = PublicidadImagen::all();

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

            $s3 = AWS::get('s3');
            $s3->putObject(array(
                'Bucket'     => "sirius".$sistemas[0]["nombre"],
                'Key'        => $nombre_imagen,
                'SourceFile' => 'public/uploads/'.$sistemas[0]["nombre"].'/'.$nombre_imagen
            ));
            $data["ruta_aws"]='https://s3-us-west-2.amazonaws.com/'.'sirius'.$sistemas[0]["nombre"].'/'.$nombre_imagen;

            $validator = Validator::make($data, PublicidadImagen::$rules);
            if ($validator->fails())
            {
                $errores=$validator->messages()->first();
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }

            if(PublicidadImagen::create($data))
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
		$publicidadimagen = PublicidadImagen::findOrFail($id);
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
        $app=Session::get('credencial');
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();

        $imagen=Input::file('imagen');
        $nombre_imagen=time().$imagen->getClientOriginalName();
        $upload=$imagen->move('public/uploads/'.$sistemas[0]["nombre"].'/',$nombre_imagen);
        if($upload)
        {
            $publicidadimagen = PublicidadImagen::findOrFail($id);
            $data = Input::all();
            $data["ruta"]='public/uploads/'.$sistemas[0]["nombre"].'/'.$nombre_imagen;
            $data["aud_usuario_mod_id"]=Session::get('id_usuario');

            $s3 = AWS::get('s3');
            $s3->putObject(array(
                'Bucket'     => 'sirius'.$sistemas[0]["nombre"],
                'Key'        => $nombre_imagen,
                'SourceFile' => 'public/uploads/'.$sistemas[0]["nombre"].'/'.$nombre_imagen
            ));
            $data["ruta_aws"]='https://s3-us-west-2.amazonaws.com/sirius'.$sistemas[0]["nombre"].'/'.$nombre_imagen;

            if($publicidadimagen->update($data))
            {
                $carga=array();
                $carga["ruta"]=$data["ruta"];
                $carga["id"]=$data["tipo"].'_'.$data["sizex"].'x'.$data["sizey"];

                return View::make('ws.json', array("resultado"=>compact('carga')));
            }
            else
            {
                $errores="Error al actualizar registro";
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
        $file="public/uploads/ferias/".$nombre_archivo;

        $response = Response::make(File::get($file));
        $response->header('Content-Type', 'image/jpeg');
        $response->header('Content-Type', 'image/png');
        return $response;
    }

    public function subirAWS()
    {
        $app=Session::get('credencial');
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();


        $imagen=Input::file('imagen');
        $nombre_imagen=time()."_".$this->TratarNombre($imagen->getClientOriginalName());
        $upload=$imagen->move('public/uploads/'.$sistemas[0]["nombre"].'/',$nombre_imagen);
        if($upload)
        {
            $data = Input::all();
            $data["ruta"]=$sistemas[0]["nombre"].'/'.$nombre_imagen;
            $data["aud_usuario_id"]=Session::get('id_usuario');
            
            /*$s3 = AWS::get('s3');
            $s3->putObject(array(
                'Bucket'     => $sistemas[0]["nombre"],
                'Key'        => $nombre_imagen,
                'SourceFile' => 'public/uploads/'.$sistemas[0]["nombre"].'/',$nombre_imagen,
            ));*/

            $s3 = AWS::get('s3');
            $s3->putObject(array(
                'Bucket'     => 'sirius'.$sistemas[0]["nombre"],
                'Key'        => $nombre_imagen,
                'SourceFile' => 'public/uploads/'.$sistemas[0]["nombre"].'/'.$nombre_imagen
            ));
        }
        else
        {
            $errores="Error al subir imagen";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    public function TratarNombre($string)
    {
        $string = trim($string); $string = str_replace( array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string );
        $string = str_replace( array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string );
        $string = str_replace( array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string );
        $string = str_replace( array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string );
        $string = str_replace( array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string );
        $string = str_replace( array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string );
        $string = str_replace( array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">“, “< ", ";", ",", ":", " "), '', $string );
        return $string;
    }
}
