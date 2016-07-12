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
		$archivo = Input::file('usuarios');
		$nombre_archivo = time() . $archivo->getClientOriginalName();
		$upload = $archivo->move('public/uploads/motoclublapaz' . '/', $nombre_archivo);
		if ($upload) {
			$file = "public/uploads/motoclublapaz" . "/" . $nombre_archivo;

			$columnas = array("Attending Count","First Name","Last Name","Email","Created","Foto del piloto","Fecha de Nacimiento","Celular","TelÃ©fono fijo","GÃ©nero","Documento de identidad","Tipo de sangre","Alergias","Nombre persona de contacto","Celular contacto","TelÃ©fono fijo contacto","Seguro","Foto de la moto","Marca","Modelo","AÃ±o","Placa","Foto recibo de pago","Declaro que, mi participaciÃ³n en el evento de referencia se realiza bajo mi total responsabilidad y libero a los organizadores de toda responsabilidad por siniestro o accidente que pudiera sufrir mi persona, el vehÃ­culo o daÃ±os a terceros causados por Ã©ste en el transcurso de la travesÃ­a.","OcupaciÃ³n","Nacionalidad","Cilindrada");
			$cantidadColumnas = count($columnas);
			$obj = array();

			$handle = fopen($file, 'r');
			if ($handle) {
				while (!feof($handle))
				{
					$carga = array();
					while (($csv_row = fgetcsv($handle, 3000000, ',')) !== false)
					{
						$contadorFilas = 0;
						foreach ($csv_row as &$row)
						{
							$variasFilas = preg_split( '/\r\n|\r|\n/', $row);
							for ($i = 0; $i < count($variasFilas); $i++)
							{
								$obj[$columnas[$contadorFilas]] = $variasFilas[$i];
								$contadorFilas++;

								if ($contadorFilas == $cantidadColumnas)
								{
									$obj["aud_usuario_id"] = Session::get('id_usuario');
									array_push($carga,$obj);
									$contadorFilas = 0;
									$obj = array();
								}
							}
						}

					}
				}
				fclose($handle);

				$objResultado = array();
				$contadorInsert = 0;
				$contadorError = 0;

				if(sizeof($carga)>0){
					MotoclubUsuario::truncate();
				}

				$contador=0;
				foreach ($carga as $objCarga)
				{
					if($contador!=0){
						$data=array();

						//$data["id"]=["Attending Count"];
						$data["nombre"]=$objCarga["First Name"];
						$data["apellido"]=$objCarga["Last Name"];
						$data["email"]=$objCarga["Email"];
						$data["created_at"]=$objCarga["Created"];
						$data["foto_piloto"]=$objCarga["Foto del piloto"];
						$data["fecha_nacimiento"]=$objCarga["Fecha de Nacimiento"];
						$data["celular"]=$objCarga["Celular"];
						$data["telefono_fijo"]=$objCarga["TelÃ©fono fijo"];
						$data["genero"]=$objCarga["GÃ©nero"];
						$data["ci"]=$objCarga["Documento de identidad"];
						$data["tipo_sangre"]=$objCarga["Tipo de sangre"];
						$data["alergias"]=$objCarga["Alergias"];
						$data["nombre_contacto"]=$objCarga["Nombre persona de contacto"];
						$data["celular_contacto"]=$objCarga["Celular contacto"];
						$data["telefono_fijo_contacto"]=$objCarga["TelÃ©fono fijo contacto"];
						$data["seguro"]=$objCarga["Seguro"];
						$data["foto_moto"]=$objCarga["Foto de la moto"];
						$data["marca"]=$objCarga["Marca"];
						$data["modelo"]=$objCarga["Modelo"];
						$data["anio"]=$objCarga["AÃ±o"];
						$data["placa"]=$objCarga["Placa"];
						$data["cilindrada"]=$objCarga["Cilindrada"];
						$data["ocupacion"]=$objCarga["OcupaciÃ³n"];
						$data["nacionalidad"]=$objCarga["Nacionalidad"];
						$data["foto_pago"]=$objCarga["Foto recibo de pago"];
						$data["declaro"]=$objCarga["Declaro que, mi participaciÃ³n en el evento de referencia se realiza bajo mi total responsabilidad y libero a los organizadores de toda responsabilidad por siniestro o accidente que pudiera sufrir mi persona, el vehÃ­culo o daÃ±os a terceros causados por Ã©ste en el transcurso de la travesÃ­a."];

						$data["aud_usuario_id"] = Session::get('id_usuario');

						if($insert=MotoclubUsuario::create($data)) {
							$id = $insert->id;
							if($id>0)
								$contadorInsert++;
							else
								$contadorError++;
						}
						else
							$contadorError++;
					}
					else{
						$contador++;
					}
				}

				$objResultado["insertados"]=$contadorInsert;
				$objResultado["error"]=$contadorError;
				return View::make('ws.json', array("resultado" => compact('objResultado')));

			} else {
				$errores = "Error al abrir el archivo";
				return View::make('ws.json_errores', array("errores" => compact('errores')));
			}
		} else {
			$errores = "Error al subir el archivo";
			return View::make('ws.json_errores', array("errores" => compact('errores')));
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

	public function sinformato()
	{
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
				$aux["fecha_nacimiento"] = date('d-m-Y', strtotime($usuario["fecha_nacimiento"]));
				$aux["celular"] = $usuario["celular"];
				$aux["telefono_fijo"] = $usuario["telefono_fijo"];
				$aux["genero"] = $usuario["genero"];
				$aux["ci"] = $usuario["ci"];
				$aux["tipo_sangre"] = $usuario["tipo_sangre"];
				$aux["alergias"] = $usuario["alergias"];
				$aux["nombre_contacto"] = $usuario["nombre_contacto"];
				$aux["celular_contacto"] = $usuario["celular_contacto"];
				$aux["telefono_fijo_contacto"] = $usuario["telefono_fijo_contacto"];
				$aux["seguro"] = $usuario["seguro"];
				$aux["foto_moto"] = $usuario["foto_moto"];
				$aux["marca"] = $usuario["marca"];
				$aux["modelo"] = $usuario["modelo"];
				$aux["anio"] = $usuario["anio"];
				$aux["placa"] = $usuario["placa"];
				$aux["cilindrada"] = $usuario["cilindrada"];
				$aux["ocupacion"] = $usuario["ocupacion"];
				$aux["nacionalidad"] = $usuario["nacionalidad"];
				$aux["foto_pago"] = $usuario["foto_pago"];
				$aux["declaro"] = $usuario["declaro"];

				$aux["fecha_creacion"] = date('d-m-Y', strtotime($usuario["created_at"]));
				array_push($usuarios, $aux);
			}
			return json_encode($usuarios);
			//return View::make('ws.json', array("resultado" => compact('usuarios')));
		} else {
			$errores = "Error al obtener datos";
			return View::make('ws.json_errores', array("errores" => compact('errores')));
		}
	}

	public function auth()
	{
		$data = Input::All();
		if (isset($data["credencial"])) {
			$sistemas = SistemasDesarrollados::whereRaw('app=?', array($data["credencial"]))->get();
			if (sizeof($sistemas) > 0) {
				if (isset($data["email"]) && isset($data["ci"])) {
					$usuarioGral = MotoclubUsuario::whereRaw('email=? AND MD5(CONVERT(ci, SIGNED))=? AND estado=1 AND baja_logica=1', array($data["email"], $data["ci"]))->get();
					if (sizeof($usuarioGral)) {
						$datos = array();
						$datos["email"] = $usuarioGral[0]["email"];
						$datos["id_usuario"] = $usuarioGral[0]["id_usuario"];
						$datos["foto_piloto"] = $usuarioGral[0]["foto_piloto"];
						$datos["foto_moto"] = $usuarioGral[0]["foto_moto"];
						$datos["fecha_nacimiento"] = $usuarioGral[0]["fecha_nacimiento"];
						$datos["telefono_fijo"] = $usuarioGral[0]["telefono_fijo"];
						$datos["celular"] = $usuarioGral[0]["celular"];
						$datos["marca"] = $usuarioGral[0]["marca"];
						$datos["modelo"] = $usuarioGral[0]["modelo"];
						$datos["placa"] = $usuarioGral[0]["placa"];
						$datos["anio"] = $usuarioGral[0]["anio"];
						$datos["seguro"] = $usuarioGral[0]["seguro"];

						return View::make('ws.json', array("resultado" => compact('datos')));
					} else {
						$errores = "Ingreso no autorizado, email o ci erróneo";
						return View::make('ws.json_errores', array("errores" => compact('errores')));
					}
				} else {
					$errores = "Ingreso no autorizado, falta email o ci";
					return View::make('ws.json_errores', array("errores" => compact('errores')));
				}
			} else {
				$errores = "Ingreso no autorizado, credencial inválida";
				return View::make('ws.json_errores', array("errores" => compact('errores')));
			}

		} else {
			$errores = "Ingreso no autorizado";
			return View::make('ws.json_errores', array("errores" => compact('errores')));
		}
	}

	public function busqueda(){
		$data = Input::All();
		if (isset($data["credencial"])) {
			$sistemas = SistemasDesarrollados::whereRaw('app=?', array($data["credencial"]))->get();
			if (sizeof($sistemas) > 0) {
				if (isset($data["valor"]) && isset($data["criterio"])) {

					$datos=array();

					switch($data["criterio"]){
						case "nombre":
							$obj=MotoclubUsuario::where('nombre','LIKE','%'.$data["valor"].'%')->get();
							if(sizeof($obj))
								array_push($datos,$obj);

							$obj=MotoclubUsuario::where('apellido','LIKE','%'.$data["valor"].'%')->get();
							if(sizeof($obj))
								array_push($datos,$obj);
							break;
						case "placa":
							$obj=MotoclubUsuario::where('placa','LIKE','%'.$data["valor"].'%')->get();
							if(sizeof($obj))
								array_push($datos,$obj);
							break;
						case "email":
							$obj=MotoclubUsuario::where('email','LIKE','%'.$data["valor"].'%')->get();
							if(sizeof($obj))
								array_push($datos,$obj);
							break;
					}

					if(sizeof($datos)){
						$usuarios=array();

						foreach ($datos as $primerNivel) {
							foreach($primerNivel as $usuario){

								$aux = array();
								$aux["id"] = $usuario["id"];

								$aux["nombre"] = $usuario["nombre"];
								$aux["apellido"] = $usuario["apellido"];
								$aux["email"] = $usuario["email"];
								$aux["celular"] = $usuario["celular"];
								$aux["telefono_fijo"] = $usuario["telefono_fijo"];
								$aux["ci"] = $usuario["ci"];
								$aux["tipo_sangre"] = $usuario["tipo_sangre"];
								$aux["alergias"] = $usuario["alergias"];
								$aux["nombre_contacto"] = $usuario["nombre_contacto"];
								$aux["celular_contacto"] = $usuario["celular_contacto"];
								$aux["telefono_fijo_contacto"] = $usuario["telefono_fijo_contacto"];
								$aux["seguro"] = $usuario["seguro"];
								$aux["marca"] = $usuario["marca"];
								$aux["modelo"] = $usuario["modelo"];
								$aux["placa"] = $usuario["placa"];
								$aux["nacionalidad"] = $usuario["nacionalidad"];

								$aux["fecha_creacion"] = date('d-m-Y', strtotime($usuario["created_at"]));
								array_push($usuarios, $aux);
							}
						}

						return View::make('ws.json', array("resultado" => compact('usuarios')));
					} else {
						$errores = "Búsqueda sin resultados";
						return View::make('ws.json_errores', array("errores" => compact('errores')));
					}
				} else {
					$errores = "Ingreso no autorizado, falta criterio o valor";
					return View::make('ws.json_errores', array("errores" => compact('errores')));
				}
			} else {
				$errores = "Ingreso no autorizado, credencial inválida";
				return View::make('ws.json_errores', array("errores" => compact('errores')));
			}

		} else {
			$errores = "Ingreso no autorizado";
			return View::make('ws.json_errores', array("errores" => compact('errores')));
		}
	}
}


