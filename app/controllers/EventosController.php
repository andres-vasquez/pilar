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

        if($insert=Eventos::create($data))
        {
            $id=$insert->id;
            $evento_nuevo = Eventos::findOrFail($id);
            $datos = array();
            $datos["link"]="pilar/api/v1/eventos/".Session::get('credencial')."/".$id."/web";

            if($evento_nuevo->update($datos))
                return View::make('ws.json', array("resultado"=>compact('Noticia')));
            else
            {
                $errores="Error al crear registro";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
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
            // Mi cuenta de S3
            $s3->putObject(array(
                'Bucket'     => $sistemas[0]["nombre"],
                'Key'        => $nombre_imagen,
                'SourceFile' => 'public/uploads/'.$sistemas[0]["nombre"].'/'.$nombre_imagen
            ));

            /*SIRIUS
            $s3->putObject(array(
                'Bucket'     => "sirius".$sistemas[0]["nombre"],
                'Key'        => $nombre_imagen,
                'SourceFile' => 'public/uploads/'.$sistemas[0]["nombre"].'/'.$nombre_imagen
            ));

            //$data["ruta_aws"]='https://s3-us-west-2.amazonaws.com/'.$sistemas[0]["nombre"].'/'.$nombre_imagen; MIO
            //$data["ruta_aws"]='https://s3.amazonaws.com/sirius'.$sistemas[0]["nombre"].'/'.$nombre_imagen;
            $data["ruta_aws"]='https://sirius'.$sistemas[0]["nombre"].'.s3.amazonaws.com/'.$nombre_imagen;
            */

            $data["ruta_aws"]='https://s3.amazonaws.com/'.$sistemas[0]["nombre"].'/'.$nombre_imagen;
            //$data["ruta_aws"]='https://s3-us-west-2.amazonaws.com/'.$sistemas[0]["nombre"].'/'.$nombre_imagen;
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
            $resultado["success"]="1";
            $resultado["result"]=array();

            foreach ($eventos_query as $eventos_q)
            {
                $aux = array();
                $aux["id"] = $eventos_q["id"];
                $aux["title"] = $eventos_q["nombre"];
                //$aux["url"] = $eventos_q["lugar"];
                $aux["class"] = "event-important";
                $aux["start"] = strtotime($eventos_q["fecha_inicio"])."000";
                $aux["end"] = strtotime($eventos_q["fecha_fin"])."000";

                array_push($resultado["result"], $aux);
            }

            if(sizeof($resultado!=0))
                return json_encode($resultado);
            else
            {
                $resultado = array();
                $resultado["success"]="0";
                $resultado["result"]=array();
                return json_encode($resultado);
            }
        }
        else
        {
            $resultado = array();
            $resultado["success"]="0";
            $resultado["result"]=array();
            return json_encode($resultado);
        }
    }

    /**
     * @param $app
     * @param $id_noticia
     * @param Vista web de las noticas segun metodo
     */
    public function web($app,$id_evento,$metodo)
    {
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0) {
            $id_sistema = $sistemas[0]["id"];

            $eventos_query = Eventos::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND id=?',array($id_sistema,$id_evento))->get();
            if(sizeof($eventos_query)>0)
            {
                if($metodo=="web")
                {
                    $datos=$eventos_query[0];
                    return View::make('sitio.master.evento_web', array("resultado"=>compact('datos')));
                }
                else
                {
                    return $eventos_query[0]["html"];
                }
            }
            else
            {
                $errores="Error al obtener registro";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
        }
    }


    /*
     * Devuelve todos los eventos
     */
    public function apieventos($app)
    {
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0) {
            $id_sistema = $sistemas[0]["id"];

            $eventos_query = Eventos::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=?', array($id_sistema))->get();
            if (sizeof($eventos_query) > 0) {
                $resultado = array();

                foreach ($eventos_query as $eventos_q) {
                    $aux = array();
                    $aux["id"] = $eventos_q["id"];
                    $aux["nombre"] = $eventos_q["nombre"];
                    $aux["lugar"] = $eventos_q["lugar"];
                    $aux["fecha_inicio"] = $eventos_q["fecha_inicio"];
                    $aux["fecha_fin"] = $eventos_q["fecha_fin"];
                    $aux["descripcion"] = $eventos_q["descripcion"];
                    $aux["link"] = $eventos_q["link"];
                    $aux["imagen_aws"] = $eventos_q["imagen_aws"];

                    $aux["lat"] = $eventos_q["lat"];
                    $aux["lon"] = $eventos_q["lon"];
                    $aux["tipo_evento"] = $eventos_q["tipo_evento"];

                    $aux["fecha"] = date('d-m-Y',strtotime($eventos_q["fecha_inicio"]));
                    $aux["hora_inicio"] = date('H:i',strtotime($eventos_q["fecha_inicio"]));
                    array_push($resultado, $aux);
                }
                return View::make('ws.json', array("resultado"=>compact('resultado')));
            } else {
                $errores = "Error al obtener registro";
                return View::make('ws.json_errores', array("errores" => compact('errores')));
            }
        }
        else
        {
            $errores="Error al obtener registro credencial invalida";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    /*
     *  Devuelve los eventos a partir de la fecha indicada
     */
    public function apiporfecha($app,$fecha)
    {
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0) {
            $id_sistema = $sistemas[0]["id"];

            $eventos_query = Eventos::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=?', array($id_sistema))->get();
            if (sizeof($eventos_query) > 0) {
                $resultado = array();

                foreach ($eventos_query as $eventos_q)
                {
                    if(strtotime($fecha)<=strtotime($eventos_q["fecha_inicio"])) {
                        $aux = array();
                        $aux["id"] = $eventos_q["id"];
                        $aux["nombre"] = $eventos_q["nombre"];
                        $aux["lugar"] = $eventos_q["lugar"];
                        $aux["fecha_inicio"] = $eventos_q["fecha_inicio"];
                        $aux["fecha_fin"] = $eventos_q["fecha_fin"];
                        $aux["descripcion"] = $eventos_q["descripcion"];
                        $aux["link"] = $eventos_q["link"];
                        $aux["imagen_aws"] = $eventos_q["imagen_aws"];

                        $aux["lat"] = $eventos_q["lat"];
                        $aux["lon"] = $eventos_q["lon"];
                        $aux["tipo_evento"] = $eventos_q["tipo_evento"];

                        $aux["fecha"] = date('d-m-Y',strtotime($eventos_q["fecha_inicio"]));
                        $aux["hora_inicio"] = date('H:i',strtotime($eventos_q["fecha_inicio"]));
                        array_push($resultado, $aux);
                    }
                }
                return View::make('ws.json', array("resultado"=>compact('resultado')));
            } else {
                $errores = "Error al obtener registro";
                return View::make('ws.json_errores', array("errores" => compact('errores')));
            }
        }
        else
        {
            $errores="Error al obtener registro credencial invalida";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }
}
