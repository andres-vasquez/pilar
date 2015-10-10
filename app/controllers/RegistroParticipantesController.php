<?php

class RegistroParticipantesController extends \BaseController {

    /**
     * Muestra todas los registros
     *
     * @return Response
     */
    public function index()
    {
        $registroparticipantes = Registroparticipante::all();

        return View::make('ws.json', array("resultado"=>compact('registroparticipantes')));
    }


    /**
     * Creara un registro con los datos ingresados
     *
     * @return Response
     */
    public function store()
    {
        $data = Input::all();
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($data["credencial"]))->get();
        if(sizeof($sistemas)>0) {
            $id_sistema = $sistemas[0]["id"];
            $data["sistema_id"]=$id_sistema;
            $data["aud_usuario_id"]="100";
        }


        $validator = Validator::make($data, Registroparticipante::$rules);
        if ($validator->fails())
        {
            $errores=$validator->messages()->first();
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }

        $unico= Registroparticipante::whereRaw('sistema_id=? AND numero_entrada=?',array($data["sistema_id"],$data["numero_entrada"]))->get();
        if(sizeof($unico)==0)
        {
            if(Registroparticipante::create($data))
            {
                return View::make('ws.json', array("resultado"=>compact('Registroparticipante')));
            }
            else
            {
                $errores="Error al crear registro";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
        }
        else
        {
            $errores="El nuúmero de boleto ingresado ya se encuentra registrado";
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
        $registroparticipante = Registroparticipante::findOrFail($id);
        return View::make('ws.json', array("resultado"=>compact('registroparticipante')));
    }


    /**
     * Actualiza el registro segun el id ingresado
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $registroparticipante = Registroparticipante::findOrFail($id);
        $data = Input::all();

        if($registroparticipante->update($data))
        {
            return View::make('ws.json', array("resultado"=>compact('registroparticipante')));
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
        $registroparticipante = Registroparticipante::findOrFail($id);
        $data = array();

        $data["baja_logica"] = "0";
        $data["estado"] = "0";
        if($registroparticipante->update($data))
        {
            $registroparticipante = Registroparticipante::findOrFail($id);
            return View::make('ws.json', array("resultado"=>compact('registroparticipante')));
        }
        else
        {
            $errores="Error al eliminar registro";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

}
