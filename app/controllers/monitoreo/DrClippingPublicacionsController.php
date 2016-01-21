<?php

class DrClippingPublicacionsController extends \BaseController {

    /**
     * Muestra todas los registros
     *
     * @return Response
     */
    public function index()
    {
        $drclippingpublicacions = DrClippingPublicacion::all();

        return View::make('ws.json', array("resultado"=>compact('drclippingpublicacions')));
    }


    /**
     * Creara un registro con los datos ingresados
     *
     * @return Response
     */
    public function store()
    {
        $data = Input::all();
        if(isset($data["objPublicacion"]))
        {
            $datos=json_decode($data["objPublicacion"],true);


            $validator = Validator::make($datos, DrClippingPublicacion::$rules);
            if ($validator->fails())
            {
                $errores=$validator->messages()->first();
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }

            $insert = DrClippingPublicacion::create($datos);
            $id = $insert->id;
            if($id>0)
            {
                return View::make('ws.json', array("resultado"=>compact('id')));
            }
            else
            {
                $errores="Error al crear registro";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
        }
        else
        {
            $errores="Faltan parámetros de entrada";
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
        $drclippingpublicacion = DrClippingPublicacion::findOrFail($id);
        return View::make('ws.json', array("resultado"=>compact('drclippingpublicacion')));
    }


    /**
     * Actualiza el registro segun el id ingresado
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $drclippingpublicacion = DrClippingPublicacion::findOrFail($id);
        $data = Input::all();

        if($drclippingpublicacion->update($data))
        {
            return View::make('ws.json', array("resultado"=>compact('drclippingpublicacion')));
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
        $drclippingpublicacion = DrClippingPublicacion::findOrFail($id);
        $data = array();

        $data["baja_logica"] = "0";
        $data["estado"] = "0";
        if($drclippingpublicacion->update($data))
        {
            $drclippingpublicacion = DrClippingPublicacion::findOrFail($id);
            return View::make('ws.json', array("resultado"=>compact('drclippingpublicacion')));
        }
        else
        {
            $errores="Error al eliminar registro";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    public function rechazar($id)
    {
        $recibido=Input::All();
        $drclippingpublicacion = DrClippingPublicacion::findOrFail($id);

        $data = array();
        $data["estado_tarea"] = "2";
        $data["observaciones"] = $recibido["observaciones"];
        if($drclippingpublicacion->update($data))
        {
            $drclippingpublicacion = DrClippingPublicacion::findOrFail($id);
            return View::make('ws.json', array("resultado"=>compact('drclippingpublicacion')));
        }
        else
        {
            $errores="Error al eliminar registro";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    public function publicaciones($estado,$inicio,$fin)
    {
        $rango=$fin-$inicio+1;

        $publicaciones=array();
        $query = DB::connection('DrClipping')->select('SELECT count(0) as cantidad FROM t_publicacion WHERE estado=1 AND baja_logica=1 AND estado_tarea=?', array($estado));
        if (sizeof($query) > 0) {
            foreach ($query as $dato) {
                $publicaciones["total"] = $dato->cantidad;
            }
        }
        $publicaciones_filtro = DrClippingPublicacion::whereRaw('estado=1 AND baja_logica=1 AND estado_tarea=? ORDER BY id ASC LIMIT ? OFFSET ? ',array($estado,$rango,$inicio-1))->get();

        $publicaciones["publicacion"]=$publicaciones_filtro;
        return View::make('ws.json', array("resultado"=>compact('publicaciones')));
    }



    public function publicacionesFiltro($estado,$inicio,$fin,$filtro,$valor)
    {
        $rango=$fin-$inicio+1;

        $publicaciones=array();
        switch($filtro)
        {
            case "empresa":
                $query = DB::connection('DrClipping')->select("SELECT count(0) as cantidad FROM t_publicacion WHERE empresa LIKE '%".$valor."%' AND estado=1 AND baja_logica=1 AND estado_tarea=?", array($estado));
                if (sizeof($query) > 0) {
                    foreach ($query as $dato) {
                        $publicaciones["total"] = $dato->cantidad;
                    }
                }

                $publicaciones_filtro = DrClippingPublicacion::whereRaw("estado=1 AND baja_logica=1 AND estado_tarea=?  AND empresa LIKE '%".$valor."%' ORDER BY id ASC LIMIT ? OFFSET ? ",array($estado,$rango,$inicio-1))->get();
                $publicaciones["publicacion"]=$publicaciones_filtro;
                break;
            case "fecha":
                $fecha=date('d/m/Y',strtotime($valor));
                $query = DB::connection('DrClipping')->select("SELECT count(0) as cantidad FROM t_publicacion WHERE fecha_publicacion=? AND estado=1 AND baja_logica=1 AND estado_tarea=?", array($fecha,$estado));
                if (sizeof($query) > 0) {
                    foreach ($query as $dato) {
                        $publicaciones["total"] = $dato->cantidad;
                    }
                }

                $publicaciones_filtro = DrClippingPublicacion::whereRaw("estado=1 AND baja_logica=1 AND estado_tarea=?  AND fecha_publicacion=? ORDER BY id ASC LIMIT ? OFFSET ? ",array($estado,$fecha,$rango,$inicio-1))->get();
                $publicaciones["publicacion"]=$publicaciones_filtro;

                break;
            case "medio":
                $query = DB::connection('DrClipping')->select("SELECT count(0) as cantidad FROM t_publicacion WHERE medio_id=? AND estado=1 AND baja_logica=1 AND estado_tarea=?", array($valor,$estado));
                if (sizeof($query) > 0) {
                    foreach ($query as $dato) {
                        $publicaciones["total"] = $dato->cantidad;
                    }
                }

                $publicaciones_filtro = DrClippingPublicacion::whereRaw("estado=1 AND baja_logica=1 AND estado_tarea=?  AND medio_id=? ORDER BY id ASC LIMIT ? OFFSET ? ",array($estado,$valor,$rango,$inicio-1))->get();
                $publicaciones["publicacion"]=$publicaciones_filtro;
                break;
        }
        return View::make('ws.json', array("resultado"=>compact('publicaciones')));
    }
}
