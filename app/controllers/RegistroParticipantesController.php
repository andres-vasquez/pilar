<?php

class RegistroParticipantesController extends \BaseController {

    /**
     * Muestra todas los registros
     *
     * @return Response
     */
    public function index()
    {
        $registroparticipantes = RegistroParticipante::all();

        $resultado = '<div class="col-lg-12"><table class="table table-bordered table-striped table-condensed">';
        $resultado .= '<tr>';
        $resultado .= '<td>Fecha</td>';
        $resultado .= '<td colspan="8">'.date('d-m-Y H:i:s').'</td>';
        $resultado .= '</tr>';
        $resultado .= '<tr>';
        $resultado .= '<td>id</td>';
        $resultado .= '<td>Nombre</td>';
        $resultado .= '<td>Apellido</td>';
        $resultado .= '<td>Ci</td>';
        $resultado .= '<td>Telefono</td>';
        $resultado .= '<td>Email</td>';
        $resultado .= '<td>Número entrada</td>';
        $resultado .= '<td>Stand favorito</td>';
        $resultado .= '<td>Fecha registro</td>';
        $resultado .= '</tr>';

        foreach($registroparticipantes as $participante)
        {
            $resultado .= '<tr>';
            $resultado .= '<td>'.$participante["id"].'</td>';
            $resultado .= '<td>'.$participante["nombre"].'</td>';
            $resultado .= '<td>'.$participante["apellido"].'</td>';
            $resultado .= '<td>'.$participante["ci"].'</td>';
            $resultado .= '<td>'.$participante["telefono"].'</td>';
            $resultado .= '<td>'.$participante["email"].'</td>';
            $resultado .= '<td>'.$participante["numero_entrada"].'</td>';
            $resultado .= '<td>'.$participante["empresa"].'</td>';
            $resultado .= '<td>'.date('d-m-Y H:i:s',strtotime($participante["created_at"])).'</td>';
            $resultado .= '</tr>';
        }

        $resultado .= '</table></div>';
        Session::put('output', $resultado);
        return Redirect::to('/output/excel');
        //return $resultado;
        //return View::make('ws.json', array("resultado"=>compact('registroparticipantes')));
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


        $validator = Validator::make($data, RegistroParticipante::$rules);
        if ($validator->fails())
        {
            $errores=$validator->messages()->first();
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }

        $unico= RegistroParticipante::whereRaw('sistema_id=? AND numero_entrada=?',array($data["sistema_id"],$data["numero_entrada"]))->get();
        /*if(sizeof($unico)==0)
        {*/
            if(RegistroParticipante::create($data))
            {
                return View::make('ws.json', array("resultado"=>compact('RegistroParticipante')));
            }
            else
            {
                $errores="Error al crear registro";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
        /*}
        else
        {
            $errores="El nuúmero de boleto ingresado ya se encuentra registrado";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }*/
    }

    /**
     * Muestra el registro segun el ID ingresado.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $registroparticipante = RegistroParticipante::findOrFail($id);
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
        $registroparticipante = RegistroParticipante::findOrFail($id);
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
        $registroparticipante = RegistroParticipante::findOrFail($id);
        $data = array();

        $data["baja_logica"] = "0";
        $data["estado"] = "0";
        if($registroparticipante->update($data))
        {
            $registroparticipante = RegistroParticipante::findOrFail($id);
            return View::make('ws.json', array("resultado"=>compact('registroparticipante')));
        }
        else
        {
            $errores="Error al eliminar registro";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

}
