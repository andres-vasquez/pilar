<?php

class SmsConfiguracionsController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
        $config=array();
        $rangos = DB::connection('Sms')->select('SELECT MAX(MONTH(fecha)) as maximo, MAX(YEAR(fecha)) as amaximo, MIN(MONTH(fecha)) as minimo,MIN(YEAR (fecha)) as aminimo  FROM SmsMensaje');
        foreach ($rangos as $minmax) {
            $config["mes_max"] = $minmax->maximo;
            $config["mes_min"] = $minmax->minimo;
            $config["ano_max"] = $minmax->amaximo;
            $config["ano_min"] = $minmax->aminimo;
        }

        $querySms = SmsMensaje::whereRaw('estado=1 AND baja_logica=1 ORDER BY fecha DESC LIMIT 1')->get();
        foreach($querySms as $sms)
        {
            $config["ultimo_mensaje"]=date('d-m-Y H:i:s',strtotime($sms["fecha"]));
        }

        $query = SmsConfiguracion::whereRaw('estado=1 AND baja_logica=1 ORDER BY created_at DESC LIMIT 1')->get();
        foreach($query as $objConfig)
        {
            $config["ganancia"]=$objConfig["ganancia"];
            $config["mensaje_pago"]=$objConfig["mensaje_pago"];
            $config["fecha_inicio"]=date('d-m-Y',strtotime($objConfig["fecha_inicio"]));

            if($objConfig["ffn_mensaje"]!="0000-00-00 00:00:00")
                $config["valido_mensaje"]=date('d-m-Y',strtotime($objConfig["ffn_mensaje"]));
            else
                $config["valido_mensaje"]="Indefinido";

            $config["ganancia"]=$objConfig["ganancia"];
        }
		return View::make('ws.json', array("resultado"=>compact('config')));
	}

    public function mostrarpormes($ano,$mes)
    {
        $config=array();
        $rangos = DB::connection('Sms')->select('SELECT MAX(MONTH(fecha)) as maximo, MAX(YEAR(fecha)) as amaximo, MIN(MONTH(fecha)) as minimo,MIN(YEAR (fecha)) as aminimo  FROM SmsMensaje');
        foreach ($rangos as $minmax) {
            $config["mes_max"] = $minmax->maximo;
            $config["mes_min"] = $minmax->minimo;
            $config["ano_max"] = $minmax->amaximo;
            $config["ano_min"] = $minmax->aminimo;
        }

        $querySms = SmsMensaje::whereRaw('estado=1 AND baja_logica=1 ORDER BY fecha DESC LIMIT 1')->get();
        foreach($querySms as $sms)
        {
            $config["ultimo_mensaje"]=date('d-m-Y H:i:s',strtotime($sms["fecha"]));
        }

        $fecha=date('Y-m-d H:i:s',strtotime($ano."-".$mes."-"."01"));
        $config["fecha"]=$fecha;
        $query = SmsConfiguracion::whereRaw('estado=1 AND baja_logica=1 AND fecha_inicio <= ? ORDER BY id DESC LIMIT 1',array($fecha))->get();
        foreach($query as $objConfig)
        {
            $config["ganancia"]=$objConfig["ganancia"];
            $config["mensaje_pago"]=$objConfig["mensaje_pago"];
            $config["fecha_inicio"]=date('d-m-Y',strtotime($objConfig["fecha_inicio"]));

            if($objConfig["ffn_mensaje"]!="0000-00-00 00:00:00")
                $config["valido_mensaje"]=date('d-m-Y',strtotime($objConfig["ffn_mensaje"]));
            else
                $config["valido_mensaje"]="Indefinido";

            $config["ganancia"]=$objConfig["ganancia"];
        }
        return View::make('ws.json', array("resultado"=>compact('config')));
    }

	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), SmsConfiguracion::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(SmsConfiguracion::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Smsconfiguracion')));
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
		$smsconfiguracion = SmsConfiguracion::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('smsconfiguracion')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$smsconfiguracion = SmsConfiguracion::findOrFail($id);
		$data = Input::all();

		if($smsconfiguracion->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('smsconfiguracion')));
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
		$smsconfiguracion = SmsConfiguracion::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($smsconfiguracion->update($data))
		{
			$smsconfiguracion = SmsConfiguracion::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('smsconfiguracion')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}


    public function editar()
    {
        $config=array();
        $query = SmsConfiguracion::whereRaw('estado=1 AND baja_logica=1 ORDER BY created_at DESC LIMIT 1')->get();
        foreach($query as $objConfig)
        {
            $config["ganancia"]=$objConfig["ganancia"];
            $config["fecha_inicio"]=$objConfig["fecha_inicio"];
            $config["fecha_fin"]=$objConfig["fecha_fin"];

            $config["mensaje_pago"]=$objConfig["mensaje_pago"];
            $config["fni_mensaje"]=$objConfig["fni_mensaje"];
            $config["ffn_mensaje"]=$objConfig["ffn_mensaje"];
        }

        $data=Input::All();
        if(isset($data["ganancia"]))
        {
            $config["ganancia"]=$data["ganancia"];
            $config["fecha_inicio"]=date('Y-m-d H:i:s',strtotime($data["fecha_inicio"]));
            $config["fecha_fin"]=date('Y-m-d H:i:s',strtotime($data["fecha_fin"]));
        }
        else if(isset($data["mensaje"]))
        {
            $config["mensaje_pago"]=$data["mensaje"];
            $config["fni_mensaje"]=date('Y-m-d H:i:s',strtotime($data["fecha_inicio"]));
            $config["ffn_mensaje"]=date('Y-m-d H:i:s',strtotime($data["fecha_fin"]));
        }

        if(SmsConfiguracion::create($config))
        {
            return View::make('ws.json', array("resultado"=>compact('Smsconfiguracion')));
        }
        else
        {
            $errores="Error al crear registro";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }
}
