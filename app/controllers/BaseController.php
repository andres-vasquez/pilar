<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}


    public function subirArchivoAWS()
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
            /* Mi cuenta de S3
             * $s3->putObject(array(
                'Bucket'     => $sistemas[0]["nombre"],
                'Key'        => $nombre_imagen,
                'SourceFile' => 'public/uploads/'.$sistemas[0]["nombre"].'/'.$nombre_imagen
            ));*/
            $s3->putObject(array(
                'Bucket'     => "sirius".$sistemas[0]["nombre"],
                'Key'        => $nombre_imagen,
                'SourceFile' => 'public/uploads/'.$sistemas[0]["nombre"].'/'.$nombre_imagen
            ));

            //$data["ruta_aws"]='https://s3-us-west-2.amazonaws.com/'.$sistemas[0]["nombre"].'/'.$nombre_imagen; MIO
            $data["ruta_aws"]='https://s3.amazonaws.com/sirius'.$sistemas[0]["nombre"].'/'.$nombre_imagen;
            return View::make('ws.json', array("resultado"=>compact('data')));
        }
        else
        {
            $errores="Error al subir imagen";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }
}
