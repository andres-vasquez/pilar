<?php

class MotoclubUsuariosController extends \BaseController {

	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$motoclubusuarios = Motoclubusuario::all();

		return View::make('ws.json', array("resultado"=>compact('motoclubusuarios')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Motoclubusuario::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

		if(Motoclubusuario::create($data))
		{
			return View::make('ws.json', array("resultado"=>compact('Motoclubusuario')));
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
		$motoclubusuario = Motoclubusuario::findOrFail($id);
		return View::make('ws.json', array("resultado"=>compact('motoclubusuario')));
	}


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$motoclubusuario = Motoclubusuario::findOrFail($id);
		$data = Input::all();

		if($motoclubusuario->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('motoclubusuario')));
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
		$motoclubusuario = Motoclubusuario::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($motoclubusuario->update($data))
		{
			$motoclubusuario = Motoclubusuario::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('motoclubusuario')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

	public function sinformato(){
		$usuarios_query = MotoclubUsuario::whereRaw('estado=1 AND baja_logica=1', array())->get();
		if (sizeof($usuarios_query) > 0) {
			$usuarios = array();
			foreach ($usuarios_query as $usuario) {
				$aux = array();
				$aux["id"] = $usuario["id"];

				$aux["nombre"] = $usuario["nombre"];
				$aux["apellido"] = $usuario["apellido"];
				$aux["email"] = $usuario["email"];
				$aux["foto_piloto"] = $usuario["foto_piloto"];
				$aux["fecha_nacimiento"] = $usuario["fecha_nacimiento"];
				$aux["celular"] = $usuario["celular"];
				$aux["telefono_fijo"] = $usuario["telefono_fijo"];
				$aux["genero"] = $usuario["genero"];
				$aux["ci"] = $usuario["ci"];
				$aux["tipo_sangre"]=$usuario["tipo_sangre"];
				$aux["alergias"]=$usuario["alergias"];
				$aux["nombre_contacto"]=$usuario["nombre_contacto"];
				$aux["celular_contacto"]=$usuario["celular_contacto"];
				$aux["telefono_fijo_contacto"]=$usuario["telefono_fijo_contacto"];
				$aux["seguro"]=$usuario["seguro"];
				$aux["foto_moto"]=$usuario["foto_moto"];
				$aux["marca"]=$usuario["marca"];
				$aux["modelo"]=$usuario["modelo"];
				$aux["anio"]=$usuario["anio"];
				$aux["placa"]=$usuario["placa"];
				$aux["cilindrada"]=$usuario["cilindrada"];
				$aux["ocupacion"]=$usuario["ocupacion"];
				$aux["nacionalidad"]=$usuario["nacionalidad"];
				$aux["foto_pago"]=$usuario["foto_pago"];
				$aux["declaro"]=$usuario["declaro"];

				$aux["fecha_creacion"] = date('d-m-Y H:i:s', strtotime($usuario["created_at"]));
				array_push($usuarios, $aux);
			}
			return json_encode($usuarios);
			return View::make('ws.json', array("resultado" => compact('usuarios')));
		}
		else
		{
			$errores = "Error al obtener datos";
			return View::make('ws.json_errores', array("errores" => compact('errores')));
		}
	}
}
