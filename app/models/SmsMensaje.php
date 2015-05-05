<?php

class SmsMensaje extends \Eloquent {
    protected $connection = 'Sms';
	protected $table = "SmsMensaje";
	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('numero','mensaje','marcacion','fecha','aud_usuario_id','aud_usuario_mod_id','estado','baja_logica');
}