<?php

class ExpositoresController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$expositores = Expositore::all();

		return View::make('ws.json', array("resultado"=>compact('expositores')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Expositore::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(Expositore::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Expositore')));
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
		$expositore = Expositore::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('expositore')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$expositore = Expositore::findOrFail($id);
		$data = Input::all();

		if($expositore->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('expositore')));
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
		$expositore = Expositore::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($expositore->update($data))
		{
			$expositore = Expositore::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('expositore')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}


    /**
     * @return mixed
     */
    public function importar()
    {
        $app=Session::get('credencial');
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();

        $archivo=Input::file('filecsv');
        $nombre_archivo=time().$archivo->getClientOriginalName();
        $upload=$archivo->move('public/uploads/'.$sistemas[0]["nombre"].'/',$nombre_archivo);
        if($upload)
        {
            $file = "public/uploads/".$sistemas[0]["nombre"]."/".$nombre_archivo;
            $handle = fopen($file, 'r');

            $carga=array();

            while (($csv_row = fgetcsv($handle,1000,";"))!== false)
            {
                $contador=0;
                foreach ($csv_row as $row)
                {
                    $carga[$contador]=json_encode($row);
                    $contador++;
                    //array_push($carga,$row);
                    //$row = strtr($row, array("'" => "\\'", '"' => '\\"'));
                }

                           //store $drs[1] = id, $drs[2] = name....
            }
            fclose($handle);
            return View::make('ws.json', array("resultado"=>compact('carga')));

            /*$data = Input::all();
            $data["ruta"]='public/uploads/'.$sistemas[0]["nombre"].'/'.$nombre_imagen;
            $data["aud_usuario_id"]=Session::get('id_usuario');

            $validator = Validator::make($data, Publicidadimagen::$rules);
            if ($validator->fails())
            {
                $errores=$validator->messages()->first();
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }

            if(Publicidadimagen::create($data))
            {
                $carga=array();
                $carga["ruta"]=$data["ruta"];
                $carga["id"]=$data["tipo"].'_'.$data["sizex"].'x'.$data["sizey"];

                return View::make('ws.json', array("resultado"=>compact('carga')));
            }
            else
            {
                $errores="Error al crear registro";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }*/
        }
        else
        {
            $errores="Error al subir csv";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    public function apitodas($app)
    {

    }
}
