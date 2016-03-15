<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DrClippingClienteController extends \BaseController {

    public function reporteTipoMedioCiudad()
    {
        $estado=1;
        $reporte=array();
        $reporte["contenido"]=array();
        $aux=array();

        $id_sistema=8;
        $agrupador="Departamentos";
        $catalogos = Catalogo::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND agrupador=? ORDER BY CONVERT(value, UNSIGNED INTEGER)',array($id_sistema,$agrupador))->get();

        $columnas=0;
        $fila0=array();
        array_push($fila0,"Tipo Medio/Ciudad");
        foreach($catalogos as $catalogo)
        {
            array_push($fila0,$catalogo["label"]);
            $aux["Revista"][$catalogo["label"]]=0;
            $aux["Periódico"][$catalogo["label"]]=0;

            $columnas++;
        }
        //array_push($reporte["fila0"],$fila0);
        $reporte["columnas"]=$columnas;

        /*SELECT P.ciudad_id, P.ciudad, P.tipo_medio_id, P.tipo_medio, count(1) as cantidad FROM t_publicacion P
        INNER JOIN t_analisis A ON A.publicacion_id=P.id
        WHERE find_in_set('5', A.args)
        GROUP BY P.tipo_medio_id, P.ciudad_id*/
        
        $query = DB::connection('DrClipping')->select('SELECT ciudad_id, ciudad, tipo_medio_id, tipo_medio, count(1) as cantidad FROM t_publicacion GROUP BY tipo_medio_id, ciudad_id', array());
        if (sizeof($query) > 0){
            foreach($query as $fila){
                $aux[$fila->tipo_medio][$fila->ciudad]=$fila->cantidad;
            }
        }

        $fila1=array();
        array_push($fila1,"Revista");

        foreach($aux["Revista"] as $revistas) {
            array_push($fila1,$revistas);
        }
        //array_push($reporte["fila1"],$fila1);

        $fila2=array();
        array_push($fila2,"Periódico");
        foreach($aux["Periódico"] as $revistas) {
            array_push($fila2,$revistas);
        }

        array_push($reporte["contenido"],$fila0);
        array_push($reporte["contenido"],$fila1);
        array_push($reporte["contenido"],$fila2);

        return View::make('ws.json', array("resultado"=>compact('reporte')));
    }

    public function reportePorCiudad()
    {
        $estado=1;
        $reporte=array();
        $reporte["contenido"]=array();
        $aux=array();

        $id_sistema=8;
        $agrupador="Departamentos";
        $catalogos = Catalogo::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND agrupador=? ORDER BY CONVERT(value, UNSIGNED INTEGER)',array($id_sistema,$agrupador))->get();

        $columnas=0;
        $fila0=array();
        array_push($fila0,"Tipo Medio/Ciudad");

        foreach($catalogos as $catalogo)
        {
            array_push($fila0,$catalogo["label"]);
            $aux["Revista"][$catalogo["label"]]=0;
            $aux["Periódico"][$catalogo["label"]]=0;

            $columnas++;
        }

        //array_push($reporte["fila0"],$fila0);
        $reporte["columnas"]=$columnas;

        array_push($reporte["contenido"],$fila0);
        //array_push($reporte["contenido"],$fila1);
        //array_push($reporte["contenido"],$fila2);

        return View::make('ws.json', array("resultado"=>compact('reporte')));
    }

}
