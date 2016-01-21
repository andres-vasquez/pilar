<?php

class DrClippingAnalisesController extends \BaseController {

    /**
     * Muestra todas los registros
     *
     * @return Response
     */
    public function index()
    {
        $drclippinganalises = DrClippingAnalisi::all();

        return View::make('ws.json', array("resultado"=>compact('drclippinganalises')));
    }


    /**
     * Creara un registro con los datos ingresados
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make($data = Input::all(), DrClippingAnalisi::$rules);

        if ($validator->fails())
        {
            $errores=$validator->messages()->first();
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }

        if(DrClippingAnalisi::create($data))
        {
            $drclippingpublicacion = DrClippingPublicacion::findOrFail($data["publicacion_id"]);
            $datos = array();
            $datos["estado_tarea"] = "1";
            $drclippingpublicacion->update($datos);

            return View::make('ws.json', array("resultado"=>compact('Drclippinganalise')));
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
        $drclippinganalise = DrClippingAnalisi::findOrFail($id);
        return View::make('ws.json', array("resultado"=>compact('drclippinganalise')));
    }

    public function porpublicacion($publicacion_id)
    {
        $analisis = DrClippingAnalisi::whereRaw('estado=1 AND baja_logica=1 AND publicacion_id=? ORDER BY created_at DESC LIMIT 1',array($publicacion_id))->get();
        return View::make('ws.json', array("resultado"=>compact('analisis')));
    }

    /**
     * Actualiza el registro segun el id ingresado
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $drclippinganalise = DrClippingAnalisi::findOrFail($id);
        $data = Input::all();

        if($drclippinganalise->update($data))
        {
            return View::make('ws.json', array("resultado"=>compact('drclippinganalise')));
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
        $drclippinganalise = DrClippingAnalisi::findOrFail($id);
        $data = array();

        $data["baja_logica"] = "0";
        $data["estado"] = "0";
        if($drclippinganalise->update($data))
        {
            $drclippinganalise = DrClippingAnalisi::findOrFail($id);
            return View::make('ws.json', array("resultado"=>compact('drclippinganalise')));
        }
        else
        {
            $errores="Error al eliminar registro";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    public function foto()
    {
        $data = Input::all();
        $publicacion_id=$data["publicacion_id"];
        $url=$data["url"];
        $foto=$data["foto"];
        $imagen=$data["imagen"];

        $url_amazon="https://s3.amazonaws.com/drclipping/";
        $url=str_replace($url_amazon,"",$url);

        $app=Session::get('credencial');
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();

        if (!file_exists('public/uploads/'.$sistemas[0]["nombre"])) {
            mkdir('public/uploads/'.$sistemas[0]["nombre"], 0777, true);
        }

        $output_file='public/uploads/'.$sistemas[0]["nombre"].'/'.$url;
        $imagen=str_replace("data:image/png;base64,","",$imagen);

        $ifp = fopen($output_file, "wb");
        fwrite($ifp, base64_decode($imagen));
        fclose($ifp);

        $nueva_url=str_replace(".png","_1",$url)."png";

        try
        {
            $s3 = AWS::get('s3');
            $s3->putObject(array(
                'Bucket'     => "drclipping",
                'Key'        => $nueva_url,
                'SourceFile' => $output_file
            ));

            $drclippingpublicacion = DrClippingPublicacion::findOrFail($publicacion_id);
            $datos=array();

            if($foto=="foto1")
                $datos["url_foto1"]=$url_amazon.$nueva_url;
            else
                $datos["url_foto2"]=$url_amazon.$nueva_url;

            $resultado=$url_amazon.$nueva_url;
            if($drclippingpublicacion->update($datos))
            {
                return View::make('ws.json', array("resultado"=>compact('resultado')));
            }
        }catch (Exception $ex)
        {
            $errores=$ex;
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }

    }



    /**************** AREA DE REPORTES ************************/

    public function graficoDashboard($ano,$mes)
    {
        $resultado = array();
        $resultado["publicaciones"]=array();
        $resultado["analisis"]=array();
        $query = DB::connection('DrClipping')->select('SELECT count(1) AS cantidad, DATE(created_at) as fecha FROM t_publicacion WHERE MONTH(created_at)=? AND YEAR(created_at)=? GROUP BY DATE(created_at)', array($mes,$ano));
        if (sizeof($query) > 0) {
            foreach ($query as $dato)
            {
                $aux = array();
                $aux["cantidad"] = $dato->cantidad;
                $aux["fecha"] = date('d-m-Y',strtotime($dato->fecha));
                array_push($resultado["publicaciones"], $aux);
            }
            DB::disconnect('DrClipping');
        }

        $query = DB::connection('DrClipping')->select('SELECT count(1) AS cantidad, DATE(created_at) as fecha FROM t_analisis WHERE MONTH(created_at)=? AND YEAR(created_at)=? GROUP BY DATE(created_at)', array($mes,$ano));
        if (sizeof($query) > 0) {
            foreach ($query as $dato)
            {
                $aux = array();
                $aux["cantidad"] = $dato->cantidad;
                $aux["fecha"] = date('d-m-Y',strtotime($dato->fecha));
                array_push($resultado["analisis"], $aux);
            }
            DB::disconnect('DrClipping');
        }

        return json_encode($resultado);
    }

    public function conteoEstado()
    {
        $resultado = array();
        $query = DB::connection('DrClipping')->select('SELECT count(0) as cantidad, estado_tarea FROM t_publicacion GROUP BY estado_tarea ORDER BY estado_tarea ASC', array());
        if (sizeof($query) > 0) {
            foreach ($query as $dato)
            {
                $aux = array();
                $aux["cantidad"] = $dato->cantidad;
                $aux["estado_tarea"] = $dato->estado_tarea;
                array_push($resultado, $aux);
            }
            DB::disconnect('DrClipping');
        }
        return json_encode($resultado);
    }

    public function conteoEstadoFiltro($filtro,$valor)
    {
        $resultado = array();

        switch($filtro) {
            case "empresa":
                $query = DB::connection('DrClipping')->select("SELECT count(0) as cantidad, estado_tarea FROM t_publicacion WHERE empresa LIKE '%".$valor."%' GROUP BY estado_tarea ORDER BY estado_tarea ASC", array());
                if (sizeof($query) > 0) {
                    foreach ($query as $dato)
                    {
                        $aux = array();
                        $aux["cantidad"] = $dato->cantidad;
                        $aux["estado_tarea"] = $dato->estado_tarea;
                        array_push($resultado, $aux);
                    }
                    DB::disconnect('DrClipping');
                }
                break;
            case "fecha":
                $fecha=date('d/m/Y',strtotime($valor));
                $query = DB::connection('DrClipping')->select("SELECT count(0) as cantidad, estado_tarea FROM t_publicacion WHERE fecha_publicacion=? GROUP BY estado_tarea ORDER BY estado_tarea ASC", array($valor));
                if (sizeof($query) > 0) {
                    foreach ($query as $dato)
                    {
                        $aux = array();
                        $aux["cantidad"] = $dato->cantidad;
                        $aux["estado_tarea"] = $dato->estado_tarea;
                        array_push($resultado, $aux);
                    }
                    DB::disconnect('DrClipping');
                }
                break;
            case "medio":
                $query = DB::connection('DrClipping')->select("SELECT count(0) as cantidad, estado_tarea FROM t_publicacion WHERE medio_id=? GROUP BY estado_tarea ORDER BY estado_tarea ASC", array($valor));
                if (sizeof($query) > 0) {
                    foreach ($query as $dato)
                    {
                        $aux = array();
                        $aux["cantidad"] = $dato->cantidad;
                        $aux["estado_tarea"] = $dato->estado_tarea;
                        array_push($resultado, $aux);
                    }
                    DB::disconnect('DrClipping');
                }
                break;
        }
        return json_encode($resultado);
    }


    public function dashboardContadores($criterio)
    {
        $resultado = array();
        $hoy=date('Y-m-d');
        switch($criterio)
        {
            case "revistas":
                $query = DB::connection('DrClipping')->select('SELECT count(0) as cantidad FROM t_publicacion WHERE tipo_medio_id=54 AND DATE(created_at)=?', array($hoy));
                if (sizeof($query) > 0) {
                    foreach ($query as $dato) {
                        $resultado["cantidad"] = $dato->cantidad;
                    }
                }
                else
                    $resultado["cantidad"] = 0;
                break;
            case "periodico":
                $query = DB::connection('DrClipping')->select('SELECT count(0) as cantidad FROM t_publicacion WHERE tipo_medio_id!=54 AND DATE(created_at)=?', array($hoy));
                if (sizeof($query) > 0) {
                    foreach ($query as $dato) {
                        $resultado["cantidad"] = $dato->cantidad;
                    }
                }
                else
                    $resultado["cantidad"] = 0;
                break;
            case "usuarios":
                $query = DB::connection('DrClipping')->select('SELECT DISTINCT usuario_id FROM t_publicacion WHERE DATE(created_at)=?', array($hoy));
                $cantidad=0;
                foreach ($query as $dato) {
                    $cantidad++;
                }
                $resultado["cantidad"] = $cantidad;
                break;
            case "total":
                $query = DB::connection('DrClipping')->select('SELECT count(0) as cantidad FROM t_publicacion WHERE DATE(created_at)=?', array($hoy));
                if (sizeof($query) > 0) {
                    foreach ($query as $dato) {
                        $resultado["cantidad"] = $dato->cantidad;
                    }
                }
                break;
        }
        return View::make('ws.json', array("resultado"=>compact('resultado')));
    }

    public function variablesReporte($criterio)
    {
        $resultado = array();
        switch($criterio)
        {
            case "researcher":
                //$usuarios = DrClippingUsuario::whereRaw('estado=1 AND baja_logica=1')->get();
                $usuarios = DrClippingUsuario::whereRaw('id>0')->get();
                foreach ($usuarios as $usuario) {
                    $aux = array();
                    $aux["id"] = $usuario["id"];
                    $aux["nombre"] = $usuario["nombre_completo"];
                    array_push($resultado, $aux);
                }
                break;
            case "analyst":
                //$usuarios = DB::connection('mysql')->select('SELECT u.id as id, u.nombre FROM usuarios u, asignacion_perfiles a, perfiles p where u.estado=1 AND u.baja_logica=1 AND u.id=a.usuario_id AND a.perfil_id=p.id AND a.perfil_id IN (18,19,20) and a.usuario_id!=13',array());
                $usuarios = DB::connection('mysql')->select('SELECT u.id as id, u.nombre FROM usuarios u, asignacion_perfiles a, perfiles p where u.id=a.usuario_id AND a.perfil_id=p.id AND a.perfil_id IN (18,19,20) and a.usuario_id!=13',array());
                foreach ($usuarios as $usuario) {
                    $aux = array();
                    $aux["id"] = $usuario->id;
                    $aux["nombre"] = $usuario->nombre;
                    array_push($resultado, $aux);
                }
                break;
            case "ciudades":
                $usuarios = DB::connection('mysql')->select("SELECT id, label as nombre FROM catalogos WHERE agrupador LIKE 'departamentos' AND sistema_id=8",array());
                foreach ($usuarios as $usuario) {
                    $aux = array();
                    $aux["id"] = $usuario->id;
                    $aux["nombre"] = $usuario->nombre;
                    array_push($resultado, $aux);
                }
                break;
        }
        return json_encode($resultado);
    }

    public function generarReporte()
    {
        $data=Input::All();

        if(isset($data["criterio"]) && isset($data["lstFitro"]) && isset($data["fecha_inicio"]) && isset($data["fecha_fin"]))
        {
            $criterio=$data["criterio"];
            $listaFiltro=$data["lstFitro"];
            $fechaInicio=date('Y-m-d', strtotime(str_replace('/', '-', $data["fecha_inicio"])));
            $fechaFin=date('Y-m-d', strtotime(str_replace('/', '-', $data["fecha_fin"])));

            $arrFiltros=explode(",", $listaFiltro);
            $sel="(";
            for($i=0;$i<count($arrFiltros);$i++)
            {
                $sel.=$arrFiltros[$i];
                if($i!=count($arrFiltros)-1)
                    $sel.=",";
            }
            $sel.=")";

            $resultadoHtml = '<div class="col-lg-12"><table class="table table-bordered table-striped table-condensed">';
            $resultado = array();
            switch($criterio)
            {
                case "researcher":

                    $resultadoHtml .= '<p><strong>Reporte de publicaciones por Researcher</strong></p>';
                    $resultadoHtml .= '<p>Del: '.$fechaInicio.'</p>';
                    $resultadoHtml .= '<p>Al: '.$fechaFin.'</p><br/>';

                    $reporte = DrClippingPublicacion::whereRaw('estado=1 AND baja_logica=1 AND usuario_id IN '.$sel.' AND created_at BETWEEN ? AND ?',array($fechaInicio,$fechaFin))->get();
                    if (sizeof($reporte))
                    {
                        //Cabecera
                        $resultadoHtml .= '<tr>';
                        $resultadoHtml .= '<td>ID</td>';
                        $resultadoHtml .= '<td>Usuario</td>';
                        $resultadoHtml .= '<td>Ciudad</td>';
                        $resultadoHtml .= '<td>Tipo Medio</td>';
                        $resultadoHtml .= '<td>Medio</td>';
                        $resultadoHtml .= '<td>Fecha publicación</td>';
                        $resultadoHtml .= '<td>Ubicación</td>';
                        $resultadoHtml .= '<td>Página</td>';
                        $resultadoHtml .= '<td>Empresa</td>';
                        $resultadoHtml .= '<td>Tipo Noticia</td>';
                        $resultadoHtml .= '<td>Estado</td>';
                        $resultadoHtml .= '</tr>';

                        foreach ($reporte as $fila) {
                            $aux = array();
                            $aux["id"] = $fila["id"];

                            $drclippingusuario = DrClippingUsuario::findOrFail($fila["usuario_id"]);
                            $aux["usuario"] = $drclippingusuario["nombre_completo"];

                            $aux["ciudad"] = $fila["ciudad"];
                            $aux["tipo_medio"] = $fila["tipo_medio"];
                            $aux["medio"] = $fila["medio"];
                            $aux["fecha_publicacion"] = $fila["fecha_publicacion"];
                            $aux["ubicacion"] = $fila["ubicacion"];
                            $aux["pagina"] = $fila["pagina"];
                            $aux["empresa"] = $fila["empresa"];
                            $aux["tipo_noticia"] = $fila["tipo_noticia"];

                            switch ((int)$fila["estado_tarea"]) {
                                case 0:
                                    $aux["estado_tarea"] = "Pendiente";
                                    break;
                                case 1:
                                    $aux["estado_tarea"] = "Analizada";
                                    break;
                                case 2:
                                    $aux["estado_tarea"] = "Rechazada";
                                    break;
                            }

                            $resultadoHtml .= '<tr>';
                            $resultadoHtml .= '<td>'.$aux["id"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["usuario"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["ciudad"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["tipo_medio"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["medio"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["fecha_publicacion"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["ubicacion"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["pagina"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["empresa"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["tipo_noticia"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["estado_tarea"].'</td>';
                            $resultadoHtml .= '</tr>';

                            array_push($resultado, $aux);
                        }
                    }else {
                        $resultadoHtml .= '<tr>';
                        $resultadoHtml .= '<td></td>';
                        $resultadoHtml .= '<td colspan="2">No se encontraron resultados</td>';
                        $resultadoHtml .= '</tr>';
                    }
                    break;
                case "analyst":
                    $resultadoHtml .= '<p><strong>Reporte de publicaciones analizadas por Analyst</strong></p>';
                    $resultadoHtml .= '<p>Del: '.$fechaInicio.'</p>';
                    $resultadoHtml .= '<p>Al: '.$fechaFin.'</p><br/>';

                    $reporte = DrClippingAnalisi::whereRaw('estado=1 AND baja_logica=1 AND usuario_id IN '.$sel.' AND created_at BETWEEN ? AND ?',array($fechaInicio,$fechaFin))->get();
                    if (sizeof($reporte))
                    {
                        //Cabecera
                        $resultadoHtml .= '<tr>';
                        $resultadoHtml .= '<td>ID</td>';
                        $resultadoHtml .= '<td>Usuario</td>';
                        $resultadoHtml .= '<td>Tipo Noticia</td>';
                        $resultadoHtml .= '<td>Nombre Publicación</td>';
                        $resultadoHtml .= '<td>Color</td>';
                        $resultadoHtml .= '<td>Tamaño</td>';
                        $resultadoHtml .= '<td>Cuerpo</td>';
                        $resultadoHtml .= '<td>Día</td>';
                        $resultadoHtml .= '<td>Tarifa</td>';
                        $resultadoHtml .= '<td>Valoración</td>';
                        $resultadoHtml .= '<td>Comentario</td>';
                        $resultadoHtml .= '<td>TAG 1</td>';
                        $resultadoHtml .= '<td>TAG 2</td>';
                        $resultadoHtml .= '<td>TAG 3</td>';
                        $resultadoHtml .= '<td>TAG 4</td>';
                        $resultadoHtml .= '<td>TAG 5</td>';
                        $resultadoHtml .= '</tr>';

                        foreach ($reporte as $fila) {
                            $aux = array();
                            $aux["id"] = $fila["id"];

                            $usuarios = DB::connection('mysql')->select('SELECT u.id as id, u.nombre FROM usuarios u where u.id=?',array($fila["usuario_id"]));

                            foreach ($usuarios as $usuario) {
                                $aux["usuario"] = $usuario->nombre;
                            }

                            $aux["tipo_noticia"] = $fila["tipo_noticia"];
                            $aux["nombre_publicacion"] = $fila["nombre_publicacion"];
                            $aux["color"] = $fila["color"];
                            $aux["tamanio"] = $fila["tamanio"];
                            $aux["cuerpo"] = $fila["cuerpo"];
                            $aux["dia"] = $fila["dia"];
                            $aux["tarifa"] = $fila["tarifa"];
                            $aux["valoracion"] = $fila["valoracion"];
                            $aux["comentario"] = $fila["comentario"];

                            $arrArgs=explode(",", $fila["args"]);
                            try{$aux["tag_1"] = $this->llenarTag($arrArgs[0]);}catch(Exception $ex){$aux["tag_2"] = "";}
                            try{$aux["tag_2"] = $this->llenarTag($arrArgs[1]);}catch(Exception $ex){$aux["tag_2"] = "";}
                            try{$aux["tag_3"] = $this->llenarTag($arrArgs[2]);}catch(Exception $ex){$aux["tag_3"] = "";}
                            try{$aux["tag_4"] = $this->llenarTag($arrArgs[3]);}catch(Exception $ex){$aux["tag_4"] = "";}
                            try{$aux["tag_5"] = $this->llenarTag($arrArgs[4]);}catch(Exception $ex){$aux["tag_5"] = "";}


                            $resultadoHtml .= '<tr>';
                            $resultadoHtml .= '<td>'.$aux["id"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["usuario"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["tipo_noticia"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["nombre_publicacion"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["color"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["tamanio"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["cuerpo"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["dia"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["tarifa"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["valoracion"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["comentario"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["tag_1"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["tag_2"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["tag_3"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["tag_4"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["tag_5"].'</td>';
                            $resultadoHtml .= '</tr>';

                            array_push($resultado, $aux);
                        }
                    }
                    else {
                        $resultadoHtml .= '<tr>';
                        $resultadoHtml .= '<td></td>';
                        $resultadoHtml .= '<td colspan="2">No se encontraron resultados</td>';
                        $resultadoHtml .= '</tr>';
                    }
                    break;
                case "ciudades":
                    $resultadoHtml .= '<p><strong>Reporte de publicaciones por ciudad</strong></p>';
                    $resultadoHtml .= '<p>Del: '.$fechaInicio.'</p>';
                    $resultadoHtml .= '<p>Al: '.$fechaFin.'</p><br/>';

                    $reporte = DrClippingPublicacion::whereRaw('estado=1 AND baja_logica=1 AND ciudad_id IN '.$sel.' AND created_at BETWEEN ? AND ?',array($fechaInicio,$fechaFin))->get();
                    if (sizeof($reporte)) {
                        //Cabecera
                        $resultadoHtml .= '<tr>';
                        $resultadoHtml .= '<td>ID</td>';
                        $resultadoHtml .= '<td>Usuario</td>';
                        $resultadoHtml .= '<td>Ciudad</td>';
                        $resultadoHtml .= '<td>Tipo Medio</td>';
                        $resultadoHtml .= '<td>Medio</td>';
                        $resultadoHtml .= '<td>Fecha publicación</td>';
                        $resultadoHtml .= '<td>Ubicación</td>';
                        $resultadoHtml .= '<td>Página</td>';
                        $resultadoHtml .= '<td>Empresa</td>';
                        $resultadoHtml .= '<td>Tipo Noticia</td>';
                        $resultadoHtml .= '<td>Estado</td>';
                        $resultadoHtml .= '</tr>';

                        foreach ($reporte as $fila) {
                            $aux = array();
                            $aux["id"] = $fila["id"];

                            $drclippingusuario = DrClippingUsuario::findOrFail($fila["usuario_id"]);
                            $aux["usuario"] = $drclippingusuario["nombre_completo"];

                            $aux["ciudad"] = $fila["ciudad"];
                            $aux["tipo_medio"] = $fila["tipo_medio"];
                            $aux["medio"] = $fila["medio"];
                            $aux["fecha_publicacion"] = $fila["fecha_publicacion"];
                            $aux["ubicacion"] = $fila["ubicacion"];
                            $aux["pagina"] = $fila["pagina"];
                            $aux["empresa"] = $fila["empresa"];
                            $aux["tipo_noticia"] = $fila["tipo_noticia"];

                            switch((int)$fila["estado_tarea"])
                            {
                                case 0: $aux["estado_tarea"]="Pendiente"; break;
                                case 1: $aux["estado_tarea"]="Analizada"; break;
                                case 2: $aux["estado_tarea"]="Rechazada"; break;
                            }

                            $resultadoHtml .= '<tr>';
                            $resultadoHtml .= '<td>'.$aux["id"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["usuario"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["ciudad"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["tipo_medio"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["medio"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["fecha_publicacion"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["ubicacion"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["pagina"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["empresa"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["tipo_noticia"].'</td>';
                            $resultadoHtml .= '<td>'.$aux["estado_tarea"].'</td>';
                            $resultadoHtml .= '</tr>';

                            array_push($resultado, $aux);
                        }
                    }
                    else
                    {
                        $resultadoHtml .= '<tr>';
                        $resultadoHtml .= '<td></td>';
                        $resultadoHtml .= '<td colspan="2">No se encontraron resultados</td>';
                        $resultadoHtml .= '</tr>';
                    }
                    break;
            }
            $resultadoHtml .= '</table></div><br/><br/><br/><br/><br/><br/>';
            Session::put('output', $resultadoHtml);
            return $resultadoHtml;
        }
        else
        {
            $errores="Datos incorrectos";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    public function llenarTag($tag){
        try
        {
            $drclippingTag = DrClippingTag::findOrFail($tag);
            return $drclippingTag["nombre"];
        }
        catch(Exception $ex)
        {
            return "";
        }
    }
}
