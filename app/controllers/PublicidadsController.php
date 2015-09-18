<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PublicidadsController extends \BaseController
{

    /**
     * Muestra todas los registros
     *
     * @return Response
     */
    public function index()
    {
        $publicidads = Publicidad::all();

        return View::make('ws.json', array("resultado" => compact('publicidads')));
    }


    /**
     * Creara un registro con los datos ingresados
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make($data = Input::all(), Publicidad::$rules);

        if ($validator->fails()) {
            $errores = $validator->messages()->first();
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }

        $data["sistema_id"] = Session::get('id_sistema');
        $data["aud_usuario_id"] = Session::get('id_usuario');
        if ($insert = Publicidad::create($data)) {
            $id = $insert->id;
            return View::make('ws.json', array("resultado" => compact('id')));
        } else {
            $errores = "Error al crear registro";
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }
    }

    /**
     * Muestra el registro segun el ID ingresado.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $publicidad = array();
        $publicidades_q = Publicidad::findOrFail($id);
        $aux = array();
        $aux["id"] = $publicidades_q["id"];
        $aux["nombre"] = $publicidades_q["nombre"];
        $aux["descripcion"] = $publicidades_q["descripcion"];
        $aux["link"] = $publicidades_q["link"];
        $aux["prioridad"] = $publicidades_q["prioridad"];
        $aux["tipo_publicidad"] = $publicidades_q["tipo_publicidad"];
        $aux["fecha_creacion"] = date('d-m-Y H:i:s', strtotime($publicidades_q["created_at"]));

        $aux["imagenes"] = array();
        $imagenes_query = PublicidadImagen::whereRaw('estado=1 AND baja_logica=1 AND publicidad_id=?', array($publicidades_q["id"]))->get();
        if (sizeof($imagenes_query) > 0) {
            foreach ($imagenes_query as $imagenes_q) {
                $aux_img = array();
                $aux_img["id"] = $imagenes_q["id"];
                $aux_img["tipo"] = $imagenes_q["tipo"];
                $aux_img["sizex"] = $imagenes_q["sizex"];
                $aux_img["sizey"] = $imagenes_q["sizey"];
                $aux_img["ruta"] = $imagenes_q["ruta"];
                $aux_img["fecha_creacion"] = date('d-m-Y H:i:s', strtotime($imagenes_q["created_at"]));

                array_push($aux["imagenes"], $aux_img);
            }

        }
        array_push($publicidad, $aux);
        return View::make('ws.json', array("resultado" => compact('publicidad')));
    }


    /**
     * Actualiza el registro segun el id ingresado
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $publicidad = Publicidad::findOrFail($id);
        $data = Input::all();

        if ($publicidad->update($data)) {
            return View::make('ws.json', array("resultado" => compact('publicidad')));
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
        $publicidad = Publicidad::findOrFail($id);
        $data = array();

        $data["baja_logica"] = "0";
        $data["estado"] = "0";
        if ($publicidad->update($data)) {
            $publicidad = Publicidad::findOrFail($id);
            return View::make('ws.json', array("resultado" => compact('publicidad')));
        } else {
            $errores = "Error al eliminar registro";
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }
    }


    public function apitodassinformato($app)
    {
        $sistemas = SistemasDesarrollados::whereRaw('app=?', array($app))->get();
        if (sizeof($sistemas) > 0) {
            $id_sistema = $sistemas[0]["id"];

            $publicidades_query = Publicidad::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? ORDER BY prioridad', array($id_sistema))->get();
            if (sizeof($publicidades_query) > 0) {
                $publicidad = array();
                foreach ($publicidades_query as $publicidades_q) {
                    $aux = array();
                    $aux["id"] = $publicidades_q["id"];
                    $aux["nombre"] = $publicidades_q["nombre"];
                    $aux["descripcion"] = $publicidades_q["descripcion"];
                    $aux["link"] = $publicidades_q["link"];
                    $aux["prioridad"] = $publicidades_q["prioridad"];
                    $aux["tipo_publicidad"] = $publicidades_q["tipo_publicidad"];
                    $aux["fecha_creacion"] = date('d-m-Y H:i:s', strtotime($publicidades_q["created_at"]));
                    array_push($publicidad, $aux);
                }
                return json_encode($publicidad);
            }
        }
    }


    public function apitodas($app)
    {
        $sistemas = SistemasDesarrollados::whereRaw('app=?', array($app))->get();
        if (sizeof($sistemas) > 0) {
            $id_sistema = $sistemas[0]["id"];

            $publicidades_query = Publicidad::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? ORDER BY prioridad', array($id_sistema))->get();
            if (sizeof($publicidades_query) > 0) {
                $publicidad = array();
                foreach ($publicidades_query as $publicidades_q) {
                    $aux = array();
                    $aux["id"] = $publicidades_q["id"];
                    $aux["nombre"] = $publicidades_q["nombre"];
                    $aux["descripcion"] = $publicidades_q["descripcion"];
                    $aux["link"] = $publicidades_q["link"];
                    $aux["prioridad"] = $publicidades_q["prioridad"];
                    $aux["tipo_publicidad"] = $publicidades_q["tipo_publicidad"];
                    $aux["fecha_creacion"] = date('d-m-Y H:i:s', strtotime($publicidades_q["created_at"]));

                    $aux["imagenes"] = array();
                    $imagenes_query = PublicidadImagen::whereRaw('estado=1 AND baja_logica=1 AND publicidad_id=?', array($publicidades_q["id"]))->get();
                    if (sizeof($imagenes_query) > 0) {
                        foreach ($imagenes_query as $imagenes_q) {
                            $aux_img = array();
                            $aux_img["id"] = $imagenes_q["id"];
                            $aux_img["tipo"] = $imagenes_q["tipo"];
                            $aux_img["sizex"] = $imagenes_q["sizex"];
                            $aux_img["sizey"] = $imagenes_q["sizey"];
                            $aux_img["ruta"] = $imagenes_q["ruta"];
                            $aux_img["fecha_creacion"] = date('d-m-Y H:i:s', strtotime($imagenes_q["created_at"]));

                            array_push($aux["imagenes"], $aux_img);
                        }

                    }

                    array_push($publicidad, $aux);
                }

                return View::make('ws.json', array("resultado" => compact('publicidad')));
            } else {
                $errores = "Error al obtener registro";
                return View::make('ws.json_errores', array("errores" => compact('errores')));
            }

        } else {
            $errores = "Error al obtener registros";
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }
    }

    public function apitipotamano($app, $tipo_publicidad, $tipo, $sizex, $sizey)
    {
        $sistemas = SistemasDesarrollados::whereRaw('app=?', array($app))->get();
        if (sizeof($sistemas) > 0) {
            $id_sistema = $sistemas[0]["id"];

            $publicidades_query = Publicidad::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND tipo_publicidad=? ORDER BY prioridad', array($id_sistema,$tipo_publicidad))->get();
            if (sizeof($publicidades_query) > 0) {
                $publicidad = array();
                foreach ($publicidades_query as $publicidades_q) {
                    $aux = array();
                    $aux["id"] = $publicidades_q["id"];
                    $aux["nombre"] = $publicidades_q["nombre"];
                    $aux["descripcion"] = $publicidades_q["descripcion"];
                    $aux["link"] = $publicidades_q["link"];
                    $aux["prioridad"] = $publicidades_q["prioridad"];
                    $aux["tipo_publicidad"] = $publicidades_q["tipo_publicidad"];
                    $aux["fecha_creacion"] = date('d-m-Y H:i:s', strtotime($publicidades_q["created_at"]));

                    $aux["imagenes"] = array();
                    $imagenes_query = PublicidadImagen::whereRaw('estado=1 AND baja_logica=1 AND publicidad_id=? AND tipo=? AND sizex=? AND sizey=?', array($publicidades_q["id"], $tipo, $sizex, $sizey))->get();
                    if (sizeof($imagenes_query) > 0) {
                        foreach ($imagenes_query as $imagenes_q) {
                            $aux_img = array();
                            $aux_img["id"] = $imagenes_q["id"];
                            $aux_img["tipo"] = $imagenes_q["tipo"];
                            $aux_img["sizex"] = $imagenes_q["sizex"];
                            $aux_img["sizey"] = $imagenes_q["sizey"];
                            $aux_img["ruta"] = $imagenes_q["ruta"];
                            $aux_img["fecha_creacion"] = date('d-m-Y H:i:s', strtotime($imagenes_q["created_at"]));

                            array_push($aux["imagenes"], $aux_img);
                        }
                        array_push($publicidad, $aux);
                    }
                }

                return View::make('ws.json', array("resultado" => compact('publicidad')));
            } else {
                $errores = "Error al obtener registro";
                return View::make('ws.json_errores', array("errores" => compact('errores')));
            }

        } else {
            $errores = "Error al obtener registros";
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }
    }

    public function apitipotamanoq($app, $tipo_publicidad, $tipo, $sizex, $sizey, $cantidad)
    {
        $contador = 0;
        $sistemas = SistemasDesarrollados::whereRaw('app=?', array($app))->get();
        if (sizeof($sistemas) > 0) {
            $id_sistema = $sistemas[0]["id"];

            $publicidades_query = Publicidad::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND tipo_publicidad=? ORDER BY prioridad DESC', array($id_sistema,$tipo_publicidad))->get();
            if (sizeof($publicidades_query) > 0) {
                $publicidad = array();
                foreach ($publicidades_query as $publicidades_q) {
                    $aux = array();
                    $aux["id"] = $publicidades_q["id"];
                    $aux["nombre"] = $publicidades_q["nombre"];
                    $aux["descripcion"] = $publicidades_q["descripcion"];
                    $aux["link"] = $publicidades_q["link"];
                    $aux["prioridad"] = $publicidades_q["prioridad"];
                    $aux["tipo_publicidad"] = $publicidades_q["tipo_publicidad"];
                    $aux["fecha_creacion"] = date('d-m-Y H:i:s', strtotime($publicidades_q["created_at"]));

                    $aux["imagenes"] = array();
                    $imagenes_query = PublicidadImagen::whereRaw('estado=1 AND baja_logica=1 AND publicidad_id=? AND tipo=? AND sizex=? AND sizey=?', array($publicidades_q["id"], $tipo, $sizex, $sizey))->get();
                    if (sizeof($imagenes_query) > 0) {
                        foreach ($imagenes_query as $imagenes_q) {
                            $aux_img = array();
                            $aux_img["id"] = $imagenes_q["id"];
                            $aux_img["tipo"] = $imagenes_q["tipo"];
                            $aux_img["sizex"] = $imagenes_q["sizex"];
                            $aux_img["sizey"] = $imagenes_q["sizey"];
                            $aux_img["ruta"] = $imagenes_q["ruta"];
                            $aux_img["fecha_creacion"] = date('d-m-Y H:i:s', strtotime($imagenes_q["created_at"]));

                            array_push($aux["imagenes"], $aux_img);
                        }
                        array_push($publicidad, $aux);
                        $contador++;

                        if ($contador >= $cantidad)
                            break;
                    }
                }

                return View::make('ws.json', array("resultado" => compact('publicidad')));
            } else {
                $errores = "Error al obtener registro";
                return View::make('ws.json_errores', array("errores" => compact('errores')));
            }

        } else {
            $errores = "Error al obtener registros";
            return View::make('ws.json_errores', array("errores" => compact('errores')));
        }
    }
}
