<?php

class AdjuntosController extends \BaseController {

    /**
     * Muestra todas los registros
     *
     * @return Response
     */
    public function index()
    {
        $adjuntos = Adjunto::all();

        return View::make('ws.json', array("resultado"=>compact('adjuntos')));
    }


    /**
     * Creara un registro con los datos ingresados
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make($data = Input::all(), Adjunto::$rules);
        if ($validator->fails())
        {
            $errores=$validator->messages()->first();
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }

        if(Adjunto::create($data))
        {
            return View::make('ws.json', array("resultado"=>compact('Adjunto')));
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
        $adjunto = Adjunto::findOrFail($id);
        return View::make('ws.json', array("resultado"=>compact('adjunto')));
    }


    /**
     * Actualiza el registro segun el id ingresado
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $adjunto = Adjunto::findOrFail($id);
        $data = Input::all();

        if($adjunto->update($data))
        {
            return View::make('ws.json', array("resultado"=>compact('adjunto')));
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
        $adjunto = Adjunto::findOrFail($id);
        $data = array();

        $data["baja_logica"] = "0";
        $data["estado"] = "0";
        if($adjunto->update($data))
        {
            $adjunto = Adjunto::findOrFail($id);
            return View::make('ws.json', array("resultado"=>compact('adjunto')));
        }
        else
        {
            $errores="Error al eliminar registro";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    public function sin_formato($app,$agrupador)
    {
        $sistemas = SistemasDesarrollados::whereRaw('app=?', array($app))->get();
        if (sizeof($sistemas) > 0) {
            $id_sistema = $sistemas[0]["id"];

            $adjuntos_query = Adjunto::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND agrupador=?', array($id_sistema,$agrupador))->get();
            if (sizeof($adjuntos_query) > 0) {
                $publicidad = array();
                foreach ($adjuntos_query as $adjuntos_q) {
                    $aux = array();
                    $aux["id"] = $adjuntos_q["id"];
                    $aux["nombre"] = $adjuntos_q["nombre"];
                    $aux["ruta"] = $adjuntos_q["ruta"];
                    $aux["ruta_aws"] = $adjuntos_q["ruta_aws"];
                    $aux["fecha_creacion"] = date('d-m-Y H:i:s', strtotime($adjuntos_q["created_at"]));
                    array_push($publicidad, $aux);
                }
                return json_encode($publicidad);
            }
        }
    }

    public function apiadjuntos($agrupador,$app)
    {
        $sistemas = SistemasDesarrollados::whereRaw('app=?', array($app))->get();
        if (sizeof($sistemas) > 0)
        {
            $id_sistema = $sistemas[0]["id"];

            if($agrupador=="tecnobit_portada")
                $adjuntos_query = Adjunto::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND agrupador=? ORDER BY id DESC LIMIT 1', array($id_sistema,$agrupador))->get();
            else
                $adjuntos_query = Adjunto::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND agrupador=?', array($id_sistema,$agrupador))->get();

            if (sizeof($adjuntos_query) > 0) {

                $adjuntos = array();
                foreach ($adjuntos_query as $adjuntos_q) {
                    $aux = array();
                    $aux["id"] = $adjuntos_q["id"];
                    $aux["nombre"] = $adjuntos_q["nombre"];
                    $aux["ruta"] = $adjuntos_q["ruta"];
                    $aux["ruta_aws"] = $adjuntos_q["ruta_aws"];

                    if($agrupador=="tecnobit_revista")
                    {
                        $aux["ruta_aws_2"] = $adjuntos_q["ruta_aws_2"];
                        $aux["thumbnail"] = $adjuntos_q["thumbnail"];
                        $aux["link"] = "pilar/apitecnobit/v1/revista/".$app."/".$adjuntos_q["id"]."";
                    }
                    else if($agrupador=="tecnobit_slider")
                    {
                        $aux["link"] = $adjuntos_q["link"];
                    }


                    $aux["fecha_creacion"] = date('d-m-Y H:i:s', strtotime($adjuntos_q["created_at"]));
                    array_push($adjuntos, $aux);
                }
                return View::make('ws.json', array("resultado"=>compact('adjuntos')));
            }
            else
            {
                $errores="No se encontraron datos";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
        }
        else
        {
            $errores="Credencial invaálida";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    public function web($app,$id_revista)
    {
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0) {
            $id_sistema = $sistemas[0]["id"];

            $revista = Adjunto::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND id=?',array($id_sistema,$id_revista))->get();
            if(sizeof($revista)>0)
            {
                $datos=$revista[0];
                return View::make('sitio.master.solo_html', array("resultado"=>compact('datos')));

            }
            else
            {
                $errores="Error al obtener registro";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
        }
    }
}
