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
            //$data["ruta_aws"]='https://s3.amazonaws.com/sirius'.$sistemas[0]["nombre"].'/'.$nombre_imagen;
            $data["ruta_aws"]='https://sirius'.$sistemas[0]["nombre"].'s3.amazonaws.com/'.$nombre_imagen;
            return View::make('ws.json', array("resultado"=>compact('data')));
        }
        else
        {
            $errores="Error al subir imagen";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    public function cantidad($app,$modulo,$agrupador)
    {
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0)
        {
            $id_sistema=$sistemas[0]["id"];
            switch($modulo)
            {
                case "noticias":
                    $query = DB::connection('Pilar')->select('SELECT count(1) AS cantidad FROM Noticias WHERE estado=1 AND baja_logica=1 AND sistema_id=?',array($id_sistema));
                    break;
                case "eventos":
                    $query = DB::connection('Pilar')->select('SELECT count(1) AS cantidad FROM eventos WHERE estado=1 AND baja_logica=1 AND sistema_id=?',array($id_sistema));
                    break;
                case "publicidad":
                    $query = DB::connection('Pilar')->select('SELECT count(1) AS cantidad FROM Publicidad WHERE estado=1 AND baja_logica=1 AND tipo_publicidad=? AND sistema_id=?',array($agrupador,$id_sistema));
                    break;
                case "expositores":
                    $query = DB::connection('Pilar')->select('SELECT count(1) AS cantidad FROM expositores WHERE estado=1 AND baja_logica=1 AND sistema_id=?',array($id_sistema));
                    break;
                case "ofertas":
                    $query = DB::connection('Pilar')->select('SELECT count(1) AS cantidad FROM ofertas WHERE estado=1 AND baja_logica=1 AND sistema_id=?',array($id_sistema));
                    break;
                case "concurso":
                    $query = DB::connection('Pilar')->select('SELECT count(1) AS cantidad FROM participantes_concurso WHERE estado=1 AND baja_logica=1 AND sistema_id=?',array($id_sistema));
                    break;
                default:
                    $errores="Error al obtener modulo";
                    return View::make('ws.json_errores', array("errores"=>compact('errores')));
                    break;
            }

            foreach ($query as $dato) {
                $total = $dato->cantidad;
                return View::make('ws.json', array("resultado" => compact('total')));
            }
        }
        else
        {
            $errores="Error al obtener cantidades";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    public function subirAdjuntoAWS()
    {
        $app=Session::get('credencial');
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();

        $imagen=Input::file('adjunto');
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
            //$data["ruta_aws"]='https://s3.amazonaws.com/sirius'.$sistemas[0]["nombre"].'/'.$nombre_imagen;
            $data["ruta_aws"]='https://sirius'.$sistemas[0]["nombre"].'s3.amazonaws.com/'.$nombre_imagen;
            return View::make('ws.json', array("resultado"=>compact('data')));
        }
        else
        {
            $errores="Error al subir imagen";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }
}
