<?php

class Adjunto extends \Eloquent {

    protected $connection = 'Pilar';
    protected $table = "adjuntos";
	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('aud_usuario_id','aud_usuario_mod_id','estado','baja_logica','sistema_id','agrupador','nombre','ruta','ruta_aws','thumbnail','html','ruta_aws_2');
}