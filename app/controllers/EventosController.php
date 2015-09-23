<?php

class EventosController extends \BaseController {

    /**
     * Muestra todas los registros
     *
     * @return Response
     */
    public function index()
    {
        $eventos = Eventos::all();
        return View::make('ws.json', array("resultado"=>compact('eventos')));
    }


    /**
     * Creara un registro con los datos ingresados
     *
     * @return Response
     */
    public function store()
    {
        $data = Input::all();
        $data["sistema_id"]=Session::get('id_sistema');
        $data["aud_usuario_id"]=Session::get('id_usuario');
        $validator = Validator::make($data, Eventos::$rules);

        if ($validator->fails())
        {
            $errores=$validator->messages()->first();
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }

        if(Eventos::create($data))
        {
            return View::make('ws.json', array("resultado"=>compact('Evento')));
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
        $evento = Eventos::findOrFail($id);
        return View::make('ws.json', array("resultado"=>compact('evento')));
    }


    /**
     * Actualiza el registro segun el id ingresado
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $evento = Eventos::findOrFail($id);
        $data = Input::all();

        if($evento->update($data))
        {
            return View::make('ws.json', array("resultado"=>compact('evento')));
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
        $evento = Eventos::findOrFail($id);
        $data = array();

        $data["baja_logica"] = "0";
        $data["estado"] = "0";
        if($evento->update($data))
        {
            $evento = Eventos::findOrFail($id);
            return View::make('ws.json', array("resultado"=>compact('evento')));
        }
        else
        {
            $errores="Error al eliminar registro";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    public function subirImagenEvento()
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
                'Bucket'     => $sistemas[0]["nombre"],
                'Key'        => $nombre_imagen,
                'SourceFile' => 'public/uploads/'.$sistemas[0]["nombre"].'/'.$nombre_imagen
            ));
            $data["ruta_aws"]='https://s3-us-west-2.amazonaws.com/'.$sistemas[0]["nombre"].'/'.$nombre_imagen;
            return View::make('ws.json', array("resultado"=>compact('data')));
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

    public function sinformato()
    {
        $id_sistema=Session::get('id_sistema');

        $eventos_query = Eventos::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=?', array($id_sistema))->get();
        if (sizeof($eventos_query) > 0)
        {
            $resultado = array();
            foreach ($eventos_query as $eventos_q)
            {
                $aux = array();
                $aux["id"] = $eventos_q["id"];
                $aux["title"] = $eventos_q["nombre"];
                $aux["url"] = $eventos_q["lugar"];
                //$aux["class"] = $eventos_q["link"];
                $aux["start"] = $eventos_q["fecha_inicio"];
                $aux["end"] = $eventos_q["fecha_fin"];

                array_push($resultado, $aux);
            }
            return json_encode($resultado);
        } else {
            $errores = "Error al obtener registro";
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }
    }
}
