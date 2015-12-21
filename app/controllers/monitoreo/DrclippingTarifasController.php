<?php

class DrclippingTarifasController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$drclippingtarifas = DrclippingTarifa::all();

		return View::make('ws.json', array("resultado"=>compact('drclippingtarifas')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), DrclippingTarifa::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(DrclippingTarifa::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Drclippingtarifa')));
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
	public function show($idMedio)
	{
        $resultado=array();
		$drclippingtarifa = DrclippingTarifa::whereRaw('estado=1 AND baja_logica=1 AND medio_id=?',array($idMedio))->get();
        if(count($drclippingtarifa)>0)
        {
            foreach($drclippingtarifa as $tarifa)
            {
                $item=array();
                $item["id"]=$tarifa["id"];

                $dias="";
                $arrDias=explode(',',$tarifa["dia"]);
                for($i=0;$i<count($arrDias);$i++)
                {
                    switch($arrDias[$i])
                    {
                        case 1: $dias.="Lun"; break;
                        case 2: $dias.="Mar"; break;
                        case 3: $dias.="Mie"; break;
                        case 4: $dias.="Jue"; break;
                        case 5: $dias.="Vie"; break;
                        case 6: $dias.="Sab"; break;
                        case 7: $dias.="Dom"; break;
                    }
                    if($i<count($arrDias)-1)
                      $dias.=" - ";
                }
                $item["dia"]=$dias;


                $item["ubicacion"]=$tarifa["ubicacion"];
                $item["color"]=$tarifa["color"];
                $item["tarifa"]=$tarifa["tarifa"];
                array_push($resultado,$item);
            }
        }
        return json_encode($resultado);
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$drclippingtarifa = DrclippingTarifa::findOrFail($id);
		$data = Input::all();

		if($drclippingtarifa->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('drclippingtarifa')));
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
		$drclippingtarifa = DrclippingTarifa::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($drclippingtarifa->update($data))
		{
			$drclippingtarifa = DrclippingTarifa::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('drclippingtarifa')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}


    public function filtrada($idMedio,$idUbicacion,$idColor)
    {
        $drclippingtarifa = DrclippingTarifa::whereRaw('estado=1 AND baja_logica=1 AND medio_id=?',array($idMedio))->get();
        if(count($drclippingtarifa)>0)
        {
            $tarifa=$drclippingtarifa[0];
            return View::make('ws.json', array("resultado"=>compact('tarifa')));
        }
        else
        {
            $errores="No hay una tarifa referencial registrada";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

}
