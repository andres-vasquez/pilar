<?php

class HomeController extends BaseController
{


    public function showWelcome()
    {
        return View::make('hello');
    }

    public function descargas()
    {
        $data = Input::All();
        $path = $data["path"];
        $type = $data["type"];
        $nombre = $data["nombre"];

        $file = public_path() . "/public/" . $path;
        $headers = array('Content-Type: ' . $type);
        return Response::download($file, $nombre, $headers);
    }
}
