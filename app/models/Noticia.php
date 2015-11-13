<?php

class Noticia extends \Eloquent {
    protected $connection = 'Pilar';
	protected $table = "Noticias";
	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array("titular","descripcion","url_imagen","html","link",'estado','baja_logica','sistema_id','aud_usuario_id',"tags");
}