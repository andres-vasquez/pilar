<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
class NoticiasController extends \BaseController {


	/**
	 * Muestra todas los registros
	 *
	 * @return Response
	 */
	public function index()
	{
		$noticias = Noticia::all();

		return View::make('ws.json', array("resultado"=>compact('noticias')));
	}


	/**
	 * Creara un registro con los datos ingresados
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Noticia::$rules);

		if ($validator->fails())
		{
			$errores=$validator->messages()->first();
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}

        $data["sistema_id"]=Session::get('id_sistema');
        $data["aud_usuario_id"]=Session::get('id_usuario');
		if($insert=Noticia::create($data))
		{
            $id=$insert->id;

            $noticia_nueva = Noticia::findOrFail($id);
            $datos = array();
            $datos["link"]="pilar/api/v1/noticias/".Session::get('credencial')."/".$id."/web";

            if($noticia_nueva->update($datos))
			    return View::make('ws.json', array("resultado"=>compact('Noticia')));
            else
            {
                $errores="Error al crear registro";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
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
        try
        {
            $noticia = Noticia::findOrFail($id);
            return View::make('ws.json', array("resultado"=>compact('noticia')));
        }
		catch(ModelNotFoundException $e)
        {
            $errores="Error al obtener registro";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }


	/**
	 * Actualiza el registro segun el id ingresado
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$noticia = Noticia::findOrFail($id);
		$data = Input::all();

		if($noticia->update($data))
		{
			return View::make('ws.json', array("resultado"=>compact('noticia')));
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
		$noticia = Noticia::findOrFail($id);
		$data = array();

		$data["baja_logica"] = "0";
		$data["estado"] = "0";
		if($noticia->update($data))
		{
			$noticia = Noticia::findOrFail($id);
			return View::make('ws.json', array("resultado"=>compact('noticia')));
		}
		else
		{
			$errores="Error al eliminar registro";
			return View::make('ws.json_errores', array("errores"=>compact('errores')));
		}
	}

    /** REST API */
    /**
     * @param $app Sistema
     * @return mixed JSON con TODAS las noticias
     */

    public function apitodas($app)
    {
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0)
        {
            $id_sistema=$sistemas[0]["id"];
            $noticias=array();
            $noticias_todas = Noticia::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? ORDER BY created_at DESC',array($id_sistema))->get();
            $noticias["total"]=count($noticias_todas);

            $noticias["noticias"]=array();
            foreach ($noticias_todas as $noticia) {
                $formato_noticias = array();
                $formato_noticias["id"] =$noticia["id"];
                $formato_noticias["titular"] =$noticia["titular"];
                $formato_noticias["descripcion"] =$noticia["descripcion"];
                $formato_noticias["url_imagen"] =$noticia["url_imagen"];
                $formato_noticias["link"] =$noticia["link"];
                $formato_noticias["tags"] =$noticia["tags"];
                $formato_noticias["fecha_creacion"]= date('d-m-Y H:i:s', strtotime($noticia["created_at"]));
                array_push($noticias["noticias"],$formato_noticias);
            }
            return View::make('ws.jsonNoticias', array("resultado"=>compact('noticias')));
        }
        else
        {
            $errores="Error al obtener noticias";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    /**
     * @param $app
     * @param $inicio
     * @param $fin
     * @return JSON de las noticias que esten dentro el rango
     */
    public function apirangos($app,$inicio,$fin)
    {
        $rango=$fin-$inicio+1;
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0)
        {
            $id_sistema=$sistemas[0]["id"];
            $noticias=array();
            $noticias_todas = Noticia::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? ORDER BY created_at DESC',array($id_sistema))->get();
            $noticias["total"]=count($noticias_todas);

            $noticias_filtro = Noticia::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? ORDER BY created_at DESC LIMIT ? OFFSET ? ',array($id_sistema,$rango,$inicio-1))->get();

            $noticias["noticias"]=array();
            foreach ($noticias_filtro as $noticia) {
                $formato_noticias = array();
                $formato_noticias["id"] =$noticia["id"];
                $formato_noticias["titular"] =$noticia["titular"];
                $formato_noticias["descripcion"] =$noticia["descripcion"];
                $formato_noticias["url_imagen"] =$noticia["url_imagen"];
                $formato_noticias["link"] =$noticia["link"];
                $formato_noticias["tags"] =$noticia["tags"];
                $formato_noticias["fecha_creacion"]= date('d-m-Y H:i:s', strtotime($noticia["created_at"]));
                array_push($noticias["noticias"],$formato_noticias);
            }
            return View::make('ws.jsonNoticias', array("resultado"=>compact('noticias')));
        }
        else
        {
            $errores="Error al obtener noticias";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    /**
     * @param $app
     * @param $inicio
     * @param $fin
     * @param $orden
     * @return JSON de las noticias que esten dentro el rango y con orden
     */
    public function apiorden($app,$inicio,$fin,$orden)
    {
        if($orden=="ASC" || $orden=="DESC")
        {
            $rango=$fin-$inicio+1;
            $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
            if(sizeof($sistemas)>0)
            {
                $id_sistema=$sistemas[0]["id"];
                $noticias=array();
                $noticias_todas = Noticia::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? ORDER BY created_at DESC',array($id_sistema))->get();
                $noticias["total"]=count($noticias_todas);

                if($orden=="DESC")
                    $noticias_filtro = Noticia::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? ORDER BY created_at DESC LIMIT ? OFFSET ? ',array($id_sistema,$rango,$inicio-1))->get();
                else
                    $noticias_filtro = Noticia::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? ORDER BY created_at ASC LIMIT ? OFFSET ? ',array($id_sistema,$rango,$inicio-1))->get();

                $noticias["noticias"]=array();
                foreach ($noticias_filtro as $noticia) {
                    $formato_noticias = array();
                    $formato_noticias["id"] =$noticia["id"];
                    $formato_noticias["titular"] =$noticia["titular"];
                    $formato_noticias["descripcion"] =$noticia["descripcion"];
                    $formato_noticias["url_imagen"] =$noticia["url_imagen"];
                    $formato_noticias["link"] =$noticia["link"];
                    $formato_noticias["tags"] =$noticia["tags"];
                    $formato_noticias["fecha_creacion"]= date('d-m-Y H:i:s', strtotime($noticia["created_at"]));
                    array_push($noticias["noticias"],$formato_noticias);
                }
                return View::make('ws.jsonNoticias', array("resultado"=>compact('noticias')));
            }
            else
            {
                $errores="Error al obtener noticias";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
        }
        else
        {
            $errores="Instruccion orden erronea";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }

    }

    /**
     * @param $app
     * @param $id_noticia
     * @param Vista web de las noticas segun metodo
     */
    public function web($app,$id_noticia,$metodo)
    {
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0) {
            $id_sistema = $sistemas[0]["id"];

            $noticia = Noticia::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND id=?',array($id_sistema,$id_noticia))->get();
            if(sizeof($noticia)>0)
            {
                if($metodo=="web")
                {
                    $datos=$noticia[0];
                    return View::make('sitio.master.noticia_web', array("resultado"=>compact('datos')));
                }
                else
                {
                    return $noticia[0]["html"];
                }
            }
            else
            {
                $errores="Error al obtener registro";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
        }
    }

    /**
     * @param $sistema
     * @param $inicio
     * @return Error en caso de llamar a la API incorrectamente
     */
    public function error($sistema,$inicio)
    {
        $errores="No ingreso la direccion correcta";
        return View::make('ws.json_errores', array("errores"=>compact('errores')));
    }


    public function seccionapitodas($id_seccion,$app)
    {
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0)
        {
            $id_sistema=$sistemas[0]["id"];
            $noticias=array();
            $noticias_todas = Noticia::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND FIND_IN_SET(?, tags) ORDER BY created_at DESC',array($id_sistema,$id_seccion))->get();
            $noticias["total"]=count($noticias_todas);

            $noticias["noticias"]=array();
            foreach ($noticias_todas as $noticia) {
                $formato_noticias = array();
                $formato_noticias["id"] =$noticia["id"];
                $formato_noticias["titular"] =$noticia["titular"];
                $formato_noticias["descripcion"] =$noticia["descripcion"];
                $formato_noticias["url_imagen"] =$noticia["url_imagen"];
                $formato_noticias["link"] =$noticia["link"];
                $formato_noticias["tags"] =$noticia["tags"];
                $formato_noticias["fecha_creacion"]= date('d-m-Y H:i:s', strtotime($noticia["created_at"]));
                array_push($noticias["noticias"],$formato_noticias);
            }
            return View::make('ws.jsonNoticias', array("resultado"=>compact('noticias')));
        }
        else
        {
            $errores="Error al obtener noticias";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    public function seccionapirangos($id_seccion,$app,$inicio,$fin)
    {
        $rango=$fin-$inicio+1;
        $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
        if(sizeof($sistemas)>0)
        {
            $id_sistema=$sistemas[0]["id"];
            $noticias=array();
            $noticias_todas = Noticia::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND FIND_IN_SET(?, tags) ORDER BY created_at DESC',array($id_sistema,$id_seccion))->get();
            $noticias["total"]=count($noticias_todas);

            $noticias_filtro = Noticia::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND FIND_IN_SET(?, tags) ORDER BY created_at DESC LIMIT ? OFFSET ? ',array($id_sistema,$id_seccion,$rango,$inicio-1))->get();

            $noticias["noticias"]=array();
            foreach ($noticias_filtro as $noticia) {
                $formato_noticias = array();
                $formato_noticias["id"] =$noticia["id"];
                $formato_noticias["titular"] =$noticia["titular"];
                $formato_noticias["descripcion"] =$noticia["descripcion"];
                $formato_noticias["url_imagen"] =$noticia["url_imagen"];
                $formato_noticias["link"] =$noticia["link"];
                $formato_noticias["tags"] =$noticia["tags"];
                $formato_noticias["fecha_creacion"]= date('d-m-Y H:i:s', strtotime($noticia["created_at"]));
                array_push($noticias["noticias"],$formato_noticias);
            }
            return View::make('ws.jsonNoticias', array("resultado"=>compact('noticias')));
        }
        else
        {
            $errores="Error al obtener noticias";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

    public function seccionapiorden($id_seccion,$app,$inicio,$fin,$orden)
    {
        if($orden=="ASC" || $orden=="DESC")
        {
            $rango=$fin-$inicio+1;
            $sistemas= SistemasDesarrollados::whereRaw('app=?',array($app))->get();
            if(sizeof($sistemas)>0)
            {
                $id_sistema=$sistemas[0]["id"];
                $noticias=array();
                $noticias_todas = Noticia::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND FIND_IN_SET(?, tags) ORDER BY created_at DESC',array($id_sistema,$id_seccion))->get();
                $noticias["total"]=count($noticias_todas);

                if($orden=="DESC")
                    $noticias_filtro = Noticia::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND FIND_IN_SET(?, tags) ORDER BY created_at DESC LIMIT ? OFFSET ? ',array($id_sistema,$id_seccion,$rango,$inicio-1))->get();
                else
                    $noticias_filtro = Noticia::whereRaw('estado=1 AND baja_logica=1 AND sistema_id=? AND FIND_IN_SET(?, tags) ORDER BY created_at ASC LIMIT ? OFFSET ? ',array($id_sistema,$id_seccion,$rango,$inicio-1))->get();

                $noticias["noticias"]=array();
                foreach ($noticias_filtro as $noticia) {
                    $formato_noticias = array();
                    $formato_noticias["id"] =$noticia["id"];
                    $formato_noticias["titular"] =$noticia["titular"];
                    $formato_noticias["descripcion"] =$noticia["descripcion"];
                    $formato_noticias["url_imagen"] =$noticia["url_imagen"];
                    $formato_noticias["link"] =$noticia["link"];
                    $formato_noticias["tags"] =$noticia["tags"];
                    $formato_noticias["fecha_creacion"]= date('d-m-Y H:i:s', strtotime($noticia["created_at"]));
                    array_push($noticias["noticias"],$formato_noticias);
                }
                return View::make('ws.jsonNoticias', array("resultado"=>compact('noticias')));
            }
            else
            {
                $errores="Error al obtener noticias";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
        }
        else
        {
            $errores="Instruccion orden erronea filtro";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }

    }
}

