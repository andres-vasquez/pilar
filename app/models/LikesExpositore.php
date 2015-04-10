<?php

class LikesExpositore extends \Eloquent {
    protected $connection = 'Pilar';
	protected $table = "LikesExpositores";
	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('sistema_id','expositor_id','imei','aud_usuario_id','aud_usuario_mod_id', 'estado','baja_logica');
}