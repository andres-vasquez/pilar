<?php

class DrClippingTagsController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$drclippingtags = DrClippingTag::whereRaw('estado=1 AND baja_logica!=0', array())->get();
		return View::make('ws.json', array("resultado"=>compact('drclippingtags')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
        $data = Input::all();
        $lstTags=json_decode($data["lstTags"],true);

        for($i=0;$i<count($lstTags);$i++)
        {
            if($insert=DrClippingTag::create($lstTags[$i]))
            {
                $id = $insert->id;
                $lstTags[$i]["id"]=$id;
            }
        }
        echo json_encode($lstTags);
        //return View::make('ws.json', array("resultado"=>compact('lstTags')));
	}

	/**
	 * Muestra el registro segun el ID ingresado.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$drclippingtag = DrClippingTag::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('drclippingtag')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$drclippingtag = DrClippingTag::findOrFail($id);
		$data = Input::all();

		if($drclippingtag->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('drclippingtag')));
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
		$drclippingtag = DrClippingTag::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($drclippingtag->update($data))
		{
			$drclippingtag = DrClippingTag::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('drclippingtag')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

}
