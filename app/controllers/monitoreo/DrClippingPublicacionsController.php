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

    public function publicaciones($estado,$inicio,$fin)
    {
        $rango=$fin-$inicio+1;

        $publicaciones=array();
        $publicaciones_todas = DrClippingPublicacion::whereRaw('estado=1 AND baja_logica=1 AND estado_tarea=? ORDER BY created_at DESC',array($estado))->get();
        $publicaciones["total"]=count($publicaciones_todas);

        $publicaciones_filtro = DrClippingPublicacion::whereRaw('estado=1 AND baja_logica=1 AND estado_tarea=? ORDER BY created_at DESC LIMIT ? OFFSET ? ',array($estado,$rango,$inicio-1))->get();

        $publicaciones["publicacion"]=$publicaciones_filtro;
        return View::make('ws.json', array("resultado"=>compact('publicaciones')));
    }

}
