<?php

class SmsUsuariosController extends \BaseController
{

    /**
     * Muestra todas los registros
     *
     * @return Response
     */
    public function index()
    {
        $smsusuarios = Smsusuario::all();

        return View::make('ws.json', array("resultado" => compact('smsusuarios')));
    }


    /**
     * Creara un registro con los datos ingresados
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make($data = Input::all(), Smsusuario::$rules);

        if ($validator->fails()) {
            $errores = $validator->messages()->first();
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }

        if (Smsusuario::create($data)) {
            return View::make('ws.json', array("resultado" => compact('Smsusuario')));
        } else {
            $errores = "Error al crear registro";
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }
    }

    /**
     * Muestra el registro segun el ID ingresado.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $smsusuario = Smsusuario::findOrFail($id);
        return View::make('ws.json', array("resultado" => compact('smsusuario')));
    }


    /**
     * Actualiza el registro segun el id ingresado
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $smsusuario = Smsusuario::findOrFail($id);
        $data = Input::all();

        if ($smsusuario->update($data)) {
            return View::make('ws.json', array("resultado" => compact('smsusuario')));
        } else {
            $errores = "Error al actualizar registro";
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }
    }

    /**
     * Define baja logica a l registro indicado
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $smsusuario = Smsusuario::findOrFail($id);
        $data = array();

        $data["baja_logica"] = "0";
        $data["estado"] = "0";
        if ($smsusuario->update($data)) {
            $smsusuario = Smsusuario::findOrFail($id);
            return View::make('ws.json', array("resultado" => compact('smsusuario')));
        } else {
            $errores = "Error al eliminar registro";
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }
    }


    public function cantidad()
    {
        $query = SmsUsuario::whereRaw('estado=1 AND baja_logica=1')->get();
        $total = sizeof($query);
        return View::make('ws.json', array("resultado" => compact('total')));
    }

    public function sinformato()
    {
        $smsusuarios = Smsusuario::all();
        $resultado = array();
        foreach ($smsusuarios as $usuario)
        {
            $aux=array();
            $aux["id"]=$usuario["id"];
            $aux["nombre"]=$usuario["nombre"];
            $aux["username"]=$usuario["username"];

            if($usuario["celular"]!="")
                $aux["celular"]=$usuario["celular"];
            else
                $aux["celular"]=$usuario["digitos_celular"];

            $aux["email"]=$usuario["email"];
            $aux["estado_clabe"]=$usuario["estado_clabe"];
            $aux["estado_email"]=$usuario["estado_email"];

            if($usuario["ultimo_mensaje"]!="0000-00-00 00:00:00")
                $aux["ultimo_mensaje"]=date('d-m-Y',strtotime($usuario["ultimo_mensaje"]));
            else
                $aux["ultimo_mensaje"]="-";

            array_push($resultado,$aux);
        }

        return json_encode($resultado);
    }
}
