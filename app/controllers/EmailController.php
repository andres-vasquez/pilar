<?php

class EmailController extends \BaseController 
{
    public function emailcontacto()
    {
        $data = Input::all();
        if(isset($data["nombre"]) && isset($data["email"]) && isset($data["mensaje"]))
        {
            Mail::send('template_email.email_contacto',array('nombre'=>$data["nombre"], 'email'=>$data["email"], 'mensaje'=>$data["mensaje"]),
                 function($message){
                    $message->to('info@boliviaontouch.com', 'Pagina web')->subject('Nuevo mensaje en la página web!');
            });

            $mensaje="Mensaje enviado";
            return View::make('ws.json', array("resultado"=>compact('mensaje')));    
        }
        else
        {
            $errores="Ingrese todos los datos";
            return View::make('ws.json_errores', array("errores"=>compact('errores')));
        }
    }

}
