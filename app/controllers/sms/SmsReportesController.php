<?php

class SmsReportesController extends \BaseController
{

    public function listados($tipo)
    {
        $resultado = array();
        switch ($tipo) {
            case "usuarios":
                $query = SmsUsuario::whereRaw('estado=1 AND baja_logica=1')->get();
                if (sizeof($query)) {
                    foreach ($query as $data) {
                        $datos = array();
                        $datos["id"] = $data["id"];
                        $datos["nombre"] = $data["username"];
                        array_push($resultado, $datos);
                    }
                }
                break;
            case "email":
                $query = SmsUsuario::whereRaw("estado=1 AND baja_logica=1 AND email!=''")->get();
                if (sizeof($query)) {
                    foreach ($query as $data) {
                        $datos = array();
                        $datos["id"] = $data["id"];
                        $datos["nombre"] = $data["email"];
                        array_push($resultado, $datos);
                    }
                }
                break;
            case "banco":
                $query = DB::connection('Sms')->select("SELECT DISTINCT banco_id AS id, banco AS abreviacion FROM SmsUsuario WHERE banco != ''");
                if (sizeof($query)) {
                    foreach ($query as $data) {
                        $datos = array();
                        $datos["id"] = $data->id;
                        $datos["nombre"] = $data->abreviacion;
                        array_push($resultado, $datos);
                    }
                }
                break;
        }
        if ($resultado != null)
            return View::make('ws.json', array("resultado" => compact('resultado')));
        else {
            $errores = "No hay datos";
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }
    }

    public function reporte()
    {
        $data = Input::All();

        $resultado = '<div class="col-lg-12"><table class="table table-bordered table-striped table-condensed">';
        $fecha_fin = date('Y-m-d', strtotime(str_replace('/', '-', $data["fecha_fin"])));
        $fecha_inicio = date('Y-m-d', strtotime(str_replace('/', '-', $data["fecha_inicio"])));

        switch ($data["tipo_reporte"]) {
            case 1: // Reporte de mensajes por usuario

                if ($data["seleccionado"] != "0") {
                    //Cabecera
                    $resultado = '<div class="col-lg-6"><table class="table table-bordered table-striped table-condensed">';
                    $resultado .= '<tr>';
                    $resultado .= '<td>Fecha</td>';
                    $resultado .= '<td>Cantidad de SMS</td>';
                    $resultado .= '</tr>';

                    $id_seleccinado = $data["seleccionado"];
                    $query = DB::connection('Sms')->select("SELECT COUNT( 1 ) AS cantidad, DATE( M.fecha ) AS fecha FROM SmsMensaje M INNER JOIN SmsUsuario U ON M.numero = U.celular WHERE DATE( M.fecha ) BETWEEN DATE(?) AND DATE(?) AND U.id = ? GROUP BY DATE( M.fecha ) DESC", array($fecha_inicio, $fecha_fin, $id_seleccinado));
                    if (sizeof($query)) {
                        foreach ($query as $dato) {
                            $resultado .= '<tr>';
                            $resultado .= '<td>' . $dato->fecha . '</td>';
                            $resultado .= '<td>' . $dato->cantidad . '</td>';
                            $resultado .= '</tr>';
                        }
                    } else {
                        $resultado .= '<tr>';
                        $resultado .= '<td colspan="2">No se encontraron resultados</td>';
                        $resultado .= '</tr>';
                    }
                } else {
                    //Cabecera
                    $resultado = '<div class="col-lg-6"><table class="table table-bordered table-striped table-condensed col-lg-6 col-md-6 col-sm-12">';
                    $resultado .= '<tr>';
                    $resultado .= '<td>Fecha</td>';
                    $resultado .= '<td>Cantidad de SMS</td>';
                    $resultado .= '</tr>';

                    $query = DB::connection('Sms')->select("SELECT COUNT( 1 ) AS cantidad, DATE( M.fecha ) AS fecha FROM SmsMensaje M INNER JOIN SmsUsuario U ON M.numero = U.celular WHERE DATE( M.fecha ) BETWEEN DATE(?) AND DATE(?) AND U.username = ? GROUP BY DATE( M.fecha ) DESC", array($fecha_inicio, $fecha_fin, $data["busqueda"]));
                    if (sizeof($query)) {
                        foreach ($query as $dato) {
                            $resultado .= '<tr>';
                            $resultado .= '<td>' . $dato->fecha . '</td>';
                            $resultado .= '<td>' . $dato->cantidad . '</td>';
                            $resultado .= '</tr>';
                        }
                    } else {
                        $resultado .= '<tr>';
                        $resultado .= '<td colspan="2">No se encontraron resultados</td>';
                        $resultado .= '</tr>';
                    }
                }

                break;
            case 2:// Reporte de mensajes por email
                //Cabecera
                $resultado = '<div class="col-lg-6"><table class="table table-bordered table-striped table-condensed">';
                $resultado .= '<tr>';
                $resultado .= '<td>Usuario</td>';
                $resultado .= '<td>Fecha</td>';
                $resultado .= '<td>Cantidad de SMS</td>';
                $resultado .= '</tr>';

                $cuentarPorMail = SmsUsuario::whereRaw('email=? AND estado=1 AND baja_logica=1', array($data["busqueda"]))->get();
                if (sizeof($cuentarPorMail)) {
                    foreach ($cuentarPorMail as $cuenta) {
                        $id_seleccinado = $cuenta["id"];
                        $usuario = $cuenta["username"];

                        $query = DB::connection('Sms')->select("SELECT COUNT( 1 ) AS cantidad, DATE( M.fecha ) AS fecha FROM SmsMensaje M INNER JOIN SmsUsuario U ON M.numero = U.celular WHERE DATE( M.fecha ) BETWEEN DATE(?) AND DATE(?) AND U.id = ? GROUP BY DATE( M.fecha ) DESC", array($fecha_inicio, $fecha_fin, $id_seleccinado));
                        if (sizeof($query)) {
                            foreach ($query as $dato) {
                                $resultado .= '<tr>';
                                $resultado .= '<td>' . $usuario . '</td>';
                                $resultado .= '<td>' . $dato->fecha . '</td>';
                                $resultado .= '<td>' . $dato->cantidad . '</td>';
                                $resultado .= '</tr>';
                            }
                        } else {
                            $resultado .= '<tr>';
                            $resultado .= '<td>' . $usuario . '</td>';
                            $resultado .= '<td colspan="2">No se encontraron resultados</td>';
                            $resultado .= '</tr>';
                        }
                    }
                }
                break;
            case 3:// Reporte de mensajes por banco

                if ($data["seleccionado"] != "0") {
                    $id_seleccinado = $data["seleccionado"];
                    $query_bancos = SmsUsuario::whereRaw('banco_id=? AND estado=1 AND baja_logica=1', array($id_seleccinado))->get();
                } else
                    $query_bancos = SmsUsuario::whereRaw('banco=? AND estado=1 AND baja_logica=1', array($data["busqueda"]))->get();

                if (sizeof($query_bancos))
                {
                    //Cabecera
                    $resultado = '<div class="col-lg-12"><table class="table table-bordered table-striped table-condensed">';
                    $resultado .= '<tr>';
                    $resultado .= '<td>Banco</td>';
                    $resultado .= '<td>Coódigo</td>';
                    $resultado .= '<td>Nombre</td>';
                    $resultado .= '<td>Nombre Depoósito</td>';
                    $resultado .= '<td>Celular</td>';
                    $resultado .= '<td>Clabe</td>';
                    $resultado .= '<td>Cantidad/SMS</td>';
                    $resultado .= '<td>TOTAL</td>';
                    $resultado .= '</tr>';

                    foreach ($query_bancos as $dato)
                    {
                        $resultado .= '<tr>';
                        $resultado .= '<td>'.$dato["banco"].'</td>';
                        $resultado .= '<td>'.$dato["username"].'</td>';
                        $resultado .= '<td>'.$dato["nombre"].'</td>';
                        $resultado .= '<td>'.$dato["nombre_deposito"].'</td>';
                        $resultado .= '<td>'.$dato["celular"].'</td>';
                        $resultado .= '<td>'.$dato["clabe"].'</td>';


                        $fecha=date('Y-m-d H:i:s',strtotime(date('Y',strtotime($fecha_inicio))."-".date('m',strtotime($fecha_inicio))."-"."01"));
                        $ganancia=0;
                        $queryCosto = SmsConfiguracion::whereRaw('estado=1 AND baja_logica=1 AND fecha_inicio <= ? ORDER BY id DESC LIMIT 1',array($fecha))->get();
                        foreach($queryCosto as $objConfig) {
                            $ganancia = $objConfig["ganancia"];
                        }

                        $query = DB::connection('Sms')->select("SELECT COUNT(1) AS cantidad FROM SmsMensaje M INNER JOIN SmsUsuario U ON M.numero = U.celular WHERE DATE( M.fecha ) BETWEEN DATE(?) AND DATE(?) AND U.id = ?", array($fecha_inicio, $fecha_fin, $dato["id"]));
                        if (sizeof($query)) {
                            foreach ($query as $datocantidad) {
                                $resultado .= '<td>'.$datocantidad->cantidad.'</td>';
                                $apagar=$ganancia*$datocantidad->cantidad;
                                $resultado .= '<td>'.number_format((float)$apagar, 2, '.', '').'</td>';
                            }
                        }
                        else
                        {
                            $resultado .= '<td>0</td>';
                        }
                        $resultado .= '</tr>';
                    }
                } else {
                    $resultado .= '<tr>';
                    $resultado .= '<td colspan="2">No se encontraron usuarios con ese banco</td>';
                    $resultado .= '</tr>';
                }

                break;
            case 4: // Reporte de usuarios

                //Cabecera
                $resultado .= '<tr>';
                $resultado .= '<td>Id</td>';
                $resultado .= '<td>Código</td>';
                $resultado .= '<td>Nombre</td>';
                $resultado .= '<td>Nombre depoósito</td>';
                $resultado .= '<td>Diígitos celular</td>';
                $resultado .= '<td>Celular</td>';
                $resultado .= '<td>Cuenta</td>';
                $resultado .= '<td>Banco</td>';
                $resultado .= '<td>Email</td>';
                $resultado .= '<td>Estado</td>';
                $resultado .= '</tr>';

                //Cuerpo
                $smsusuarios = SmsUsuario::all();
                if (sizeof($smsusuarios) > 0) {
                    foreach ($smsusuarios as $usuario) {
                        $resultado .= '<tr>';
                        $resultado .= '<td>' . $usuario["id"] . '</td>';
                        $resultado .= '<td>' . $usuario["username"] . '</td>';
                        $resultado .= '<td>' . $usuario["nombre"] . '</td>';
                        $resultado .= '<td>' . $usuario["nombre_deposito"] . '</td>';
                        $resultado .= '<td>' . $usuario["digitos_celular"] . '</td>';
                        $resultado .= '<td>' . $usuario["celular"] . '</td>';
                        $resultado .= '<td>' . $usuario["clabe"] . '</td>';
                        $resultado .= '<td>' . $usuario["banco"] . '</td>';
                        $resultado .= '<td>' . $usuario["email"] . '</td>';

                        $estado = "Activo";
                        if ($usuario["estado"] != "1")
                            $estado = "Inactivo";

                        $resultado .= '<td>' . $estado . '</td>';
                        $resultado .= '</tr>';
                    }
                }
                break;
        }
        $resultado .= '</table></div>';
        Session::put('output', $resultado);
        return $resultado;
    }
}