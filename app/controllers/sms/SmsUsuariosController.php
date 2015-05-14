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
        $smsusuarios = SmsUsuario::all();

        return View::make('ws.json', array("resultado" => compact('smsusuarios')));
    }


    /**
     * Creara un registro con los datos ingresados
     *
     * @return Response
     */
    public function store()
    {
        $data = Input::All();
        if (isset($data["credencial"])) {
            $sistemas = SistemasDesarrollados::whereRaw('app=?', array($data["credencial"]))->get();
            if (sizeof($sistemas) > 0) {

                //Algoritmo de generacion de nombre -> Combinacion nombres apellidos
                $libre = "";
                $arrNombre = explode(" ", $data["nombre"]);
                for ($i = 0; $i < count($arrNombre); $i++) {
                    $propuesto = $this->caracteres($arrNombre[$i]) . $data["digitos_celular"];
                    $existe = SmsUsuario::whereRaw('username=?', array($propuesto))->get();
                    if (sizeof($existe) == 0) {
                        $libre = $propuesto;
                        break;
                    }
                }

                if ($libre == "") {
                    try {
                        if ($libre == "") {
                            $propuesto = $this->caracteres($arrNombre[0] . $arrNombre[1][0]) . $data["digitos_celular"]; //Nombre y primera letra primer apellido
                            $existe = SmsUsuario::whereRaw('username=?', array($propuesto))->get();
                            if (sizeof($existe) == 0) {
                                $libre = $propuesto;
                            }
                        }

                        if ($libre == "") {
                            $propuesto = $this->caracteres($arrNombre[0] . $arrNombre[2][0]) . $data["digitos_celular"]; //Nombre y primera letra primer apellido
                            $existe = SmsUsuario::whereRaw('username=?', array($propuesto))->get();
                            if (sizeof($existe) == 0) {
                                $libre = $propuesto;
                            }
                        }
                    } catch (Exception $e) {
                    }
                }

                //Agrega del 1 al 99 delante del numero. Caso extremo
                if ($libre == "") {
                    for ($j = 1; $j <= 99; $j++) {
                        if ($libre != "")
                            break;

                        for ($i = 0; $i < count($arrNombre); $i++) {
                            $propuesto = $j . $this->caracteres($arrNombre[$i]) . $data["digitos_celular"];
                            $existe = SmsUsuario::whereRaw('username=?', array($propuesto))->get();
                            if (sizeof($existe) == 0) {
                                $libre = $propuesto;
                                break;
                            }
                        }
                    }
                }

                //Datos del banco
                if ($data["clabe"] == "") {
                    $data["clabe"] = "000000000000000000";
                }

                $data["username"] = $libre;
                $data["aud_usuario_id"] = 99;

                $insert = SmsUsuario::create($data);
                $id = $insert->id;
                if ($id > 0) {
                    DB::connection('Sms')->statement("UPDATE SmsUsuario U SET U.banco_id = (SELECT B.id FROM SmsBanco B WHERE U.clabe LIKE CONCAT (B.indice, '%')) WHERE U.id=?", array($id));
                    DB::connection('Sms')->statement("UPDATE SmsUsuario U SET U.banco = (SELECT B.abreviacion FROM SmsBanco B WHERE U.clabe LIKE CONCAT (B.indice, '%')) WHERE U.id=?", array($id));
                    $smsusuario = SmsUsuario::findOrFail($id);
                    return View::make('ws.json', array("resultado" => compact('smsusuario')));
                } else {
                    $errores = "Error al crear registro";
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

    public function caracteres($string)
    {

        $string = trim($string);

        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
        );

        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
        );

        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
        );

        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
        );

        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C',),
            $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
            array("\\", "¨", "º", "-", "~",
                "#", "@", "|", "!", "\"",
                "·", "$", "%", "&", "/",
                "(", ")", "?", "'", "¡",
                "¿", "[", "^", "`", "]",
                "+", "}", "{", "¨", "´",
                ">", "< ", ";", ",", ":",
                ".", " "),
            '',
            $string
        );
        return $string;
    }

    /**
     * Muestra el registro segun el ID ingresado.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $smsusuario = SmsUsuario::findOrFail($id);
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
        $smsusuario = SmsUsuario::findOrFail($id);
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
        $smsusuario = SmsUsuario::findOrFail($id);
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
        $smsusuarios = SmsUsuario::all();
        $resultado = array();
        foreach ($smsusuarios as $usuario) {
            $aux = array();
            $aux["id"] = $usuario["id"];
            $aux["nombre"] = $usuario["nombre"];
            $aux["username"] = $usuario["username"];

            if ($usuario["celular"] != "")
                $aux["celular"] = $usuario["celular"];
            else
                $aux["celular"] = $usuario["digitos_celular"];

            $aux["email"] = $usuario["email"];
            $aux["estado_clabe"] = $usuario["estado_clabe"];
            $aux["estado_email"] = $usuario["estado_email"];

            if ($usuario["ultimo_mensaje"] != "0000-00-00 00:00:00")
                $aux["ultimo_mensaje"] = date('d-m-Y', strtotime($usuario["ultimo_mensaje"]));
            else
                $aux["ultimo_mensaje"] = "-";

            array_push($resultado, $aux);
        }

        return json_encode($resultado);
    }

    public function autenticacion()
    {
        $data = Input::All();
        if (isset($data["credencial"])) {
            $sistemas = SistemasDesarrollados::whereRaw('app=?', array($data["credencial"]))->get();
            if (sizeof($sistemas) > 0) {
                if (isset($data["email"]) && isset($data["username"])) {
                    $usuarioGral = SmsUsuario::whereRaw('username=? AND email=? AND estado=1 AND baja_logica=1', array($data["username"], $data["email"]))->get();
                    if (sizeof($usuarioGral)) {
                        $datos = array();
                        $datos["email"] = $usuarioGral[0]["email"];
                        $datos["cuenta_principal"] = $usuarioGral[0]["username"];
                        $datos["nombre"] = $usuarioGral[0]["nombre"];
                        $datos["nombre_deposito"] = $usuarioGral[0]["nombre_deposito"];
                        $datos["clabe"] = base64_encode($usuarioGral[0]["clabe"]);

                        if (isset($data["mes"]))
                            $mes = $data["mes"];
                        else
                            $mes = date('m');

                        if (isset($data["ano"]))
                            $ano = $data["ano"];
                        else
                            $ano = date('Y');

                        $datos["cuentas"] = array();
                        $cuentarPorMail = SmsUsuario::whereRaw('email=? AND estado=1 AND baja_logica=1', array($data["email"]))->get();
                        foreach ($cuentarPorMail as $cuenta) {
                            $usuario = array();
                            $usuario["id"] = $cuenta["id"];
                            $usuario["username"] = $cuenta["username"];
                            $usuario["digitos_celular"] = $cuenta["digitos_celular"];

                            $celular = $cuenta["celular"];

                            if ($celular == "")
                            {
                                $contador = 0;
                                $query = DB::connection('Sms')->select("SELECT DISTINCT numero FROM SmsMensaje WHERE numero LIKE CONCAT( '%', ? ) AND numero NOT IN ( SELECT DISTINCT celular FROM SmsUsuario)", array($cuenta["digitos_celular"]));
                                foreach ($query as $dato)
                                {
                                    if ($contador == 0)
                                    {
                                        $smsusuario = SmsUsuario::findOrFail($cuenta["id"]);
                                        $dataActualizar = array();
                                        $dataActualizar["celular"] = $dato->numero;

                                        if ($smsusuario->update($dataActualizar))
                                            $celular = $dato->numero;
                                    }
                                    $contador++;
                                }
                            }
                            $usuario["celular"] = $celular;


                            //$usuario["url"] = 'ws/SmsMensaje/cantidad/' . $ano . '/' . $mes . '/' . $cuenta["celular"];

                            $qmensajes = 0;
                            $usuario["mensajes"] = array();
                            try {
                                $request = Request::create('ws/SmsMensaje/cantidad/' . $ano . '/' . $mes . '/' . $celular, 'GET', array());
                                $mensajes = json_decode(Route::dispatch($request)->getContent(), true);
                                $qmensajes = $mensajes["resultado"]["total"];
                            } catch (Exception $e) {
                                $qmensajes = 0;
                            }
                            array_push($usuario["mensajes"], $qmensajes);

                            array_push($datos["cuentas"], $usuario);
                        }

                        $request = Request::create('ws/SmsConfiguracion/' . $ano . '/' . $mes, 'GET', array());
                        $configuraciones = json_decode(Route::dispatch($request)->getContent(), true);
                        $datos["configuraciones"] = array();
                        array_push($datos["configuraciones"], $configuraciones["resultado"]["config"]);

                        return View::make('ws.json', array("resultado" => compact('datos')));
                    } else {
                        $errores = "Ingreso no autorizado, email o código erróneo";
                        return View::make('ws.json_errores', array("errores" => compact('errores')));
                    }
                } else {
                    $errores = "Ingreso no autorizado, falta email o código";
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
