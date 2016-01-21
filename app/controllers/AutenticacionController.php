<?php

class AutenticacionController extends \BaseController
{

    /**
     * [index description] Autenticacion
     * @return [type] Objeto con la lista de acceossos [description]
     */
    public function index()
    {
        $parametros = Input::all();

        if(isset($parametros["email"]) && isset($parametros["password"]))
        {
            $email=$parametros["email"];
            $password=$parametros["password"];

            if($email!="" && $password!="")
            {
                $usuario=Usuario::whereRaw('email=? AND password=?',array($email,$password))->get();
                if(sizeof($usuario)>0)
                {
                    if($usuario[0]["estado"]!="1")
                    {
                        $errores="Usuario deshabilitado";
                        return View::make('ws.json_errores', array("errores"=>compact('errores')));
                    }
                    else if($usuario[0]["baja_logica"]=="0")
                    {
                        $errores="Usuario eliminado";
                        return View::make('ws.json_errores', array("errores"=>compact('errores')));
                    }
                    else
                    {
                        $request = Request::create('/ws/autenticacion/'.$usuario[0]["id"], 'GET', array());
                        $datos=array();
                        $datos= json_decode(Route::dispatch($request)->getContent(),true);
                        $datos["usuario"]=$usuario[0]["nombre"];

                        Session::put('id_usuario',$usuario[0]["id"]);
                        Session::put('accesos',$datos);



                        return View::make('ws.json', array("resultado"=>compact('datos')));
                    }
                }
                else
                {
                    $errores="Email o Contraseña inválidos";
                    return View::make('ws.json_errores', array("errores"=>compact('errores')));
                }
            }
            else
            {
                $errores="Ingrese Email y Contraseña";
                return View::make('ws.json_errores', array("errores"=>compact('errores')));
            }
        }
        else
        {
            $errores="Ingrese Email y Contraseña";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }



    /**
     * Funcion que devuelve los datos de acceso del usuario
     * GET /autenticacion
     *
     * @return Json
     */
    public function datos($id_usuario)
    {
        $datos=array();
        $lstPerfilesAsignados = AsignacionPerfiles::whereRaw('usuario_id=? AND estado=1 AND baja_logica!=0', array($id_usuario))->get();

        Session::put('perfiles',$lstPerfilesAsignados);
        foreach ($lstPerfilesAsignados as $Perfil)
        {
            //Obtenemos los datos del perfil
            $datos_perfil=array();
            $request = Request::create('/ws/perfil/'.$Perfil["perfil_id"], 'GET', array());
            $aux=array();
            $aux= json_decode(Route::dispatch($request)->getContent(),true);

            //Obtenemos el nombre del sistema
            $request = Request::create('/ws/sistemas/'.$aux["resultado"]["perfile"]["sistema_id"], 'GET', array());
            $aux2=array();
            $aux2=json_decode(Route::dispatch($request)->getContent(),true);
            $aux["resultado"]["perfile"]["sistema"]=$aux2["resultado"]["sistemasdesarrollado"];

            $datos_perfil=$aux["resultado"];

            array_push($datos, $datos_perfil);
        }

        return View::make('ws.json', array("resultado"=>compact('datos')));
    }


    //Administrador
    public function enlace($sistema)
    {
        $sistemas_query = SistemasDesarrollados::whereRaw('nombre=?', array($sistema))->get();
        if (sizeof($sistemas_query) > 0)
        {
            foreach ($sistemas_query as $sistemas)
            {
                $id_sistema = $sistemas["id"];
                Session::put('id_sistema', $id_sistema); //Obtiene el ID del sistema
                Session::put('credencial',$sistemas["app"]);//Obtiene la credencial
                Session::put('nombre_sistema',$sistema);//Guarda el nombrel del sistema

                $id_tipo_sistema = $sistemas["tipo_sistema_id"];

                //Obtiene el arbol de accesos
                $jsonAccesos = Session::get('accesos');
                if($jsonAccesos["intCodigo"]=="1") // Si tiene accesos
                {
                    $jsonDatos = $jsonAccesos["resultado"]["datos"];
                    if (count($jsonDatos) > 0) {
                        $sistemas = array();
                        for ($i = 0; $i < count($jsonDatos); $i++) // Carga los datos de sistemas
                        {
                            if($jsonDatos[$i]["perfile"]["sistema_id"]==$id_sistema)
                            {
                                //Definicion de permisos en la capa administrador -> CRUD = 1111
                                if($jsonDatos[$i]["perfile"]["id"]=="1")
                                    Session::put("permisos","1111");
                                else
                                    Session::put("permisos","0000");
                                //TODO definir permisos dinamicamente por perfil

                                //Obtiene los perfiles y carga el ultimo
                                $perfile = $jsonDatos[$i]["perfile"];
                                $request = Request::create('/ws/menus/perfil/'.$perfile["id"], 'GET', array());
                                $resultado_menus=json_decode(Route::dispatch($request)->getContent(),true);

                                if($resultado_menus["intCodigo"]=="1")
                                {
                                    $menus=array();
                                    $jsonMenus=$resultado_menus["resultado"]["datos"];
                                    for($j=0;$j<count($jsonMenus);$j++)
                                    {
                                        array_push($menus,$jsonMenus[$j]["menus"]);
                                    }

                                    //Carga los menus en la variable de sesion
                                    Session::put('menus',$menus);

                                    //Redirecciona a la carpeta raiz del sistema o si tuviera grupo al grupo (Ej. Ferias)
                                    if($id_tipo_sistema==0)
                                        return Redirect::to('/' . $sistema);
                                    else
                                    {
                                        $tipo_sistemas_query = Tipossistemas::whereRaw('id=?', array($id_tipo_sistema))->get();
                                        if (sizeof($tipo_sistemas_query) > 0)
                                        {
                                            foreach ($tipo_sistemas_query as $tipo_sistemas) {
                                                $directorio = $tipo_sistemas["directorio"];
                                                return Redirect::to('/' . $directorio);
                                                break;
                                            }
                                        }
                                    }
                                }
                                else
                                    return Redirect::to('/login');//Si no tiene acceso al modulo
                                break;
                            }
                        }
                    }
                    else
                        return Redirect::to('/login'); //Si no tiene perfiles
                }
                else
                    return Redirect::to('/login'); // Si no tiene accesos
            }
            return Redirect::to('/login');
        }
        else
        {
            return Redirect::to('/login');
        }
    }
}