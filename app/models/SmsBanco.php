<?php

class SmsBanco extends \Eloquent {
    protected $connection = 'Sms';
	protected $table = "SmsBanco";
	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('nombre','abreviacion','index','aud_usuario_id','aud_usuario_mod_id','estado','baja_logica');
}