<?php

class PublicidadImagen extends \Eloquent {
    protected $connection = 'Pilar';
	protected $table = "Publicidad_imagenes";
	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('publicidad_id','tipo','sizex','sizey','ruta','aud_usuario_id','estado','baja_logica');
}