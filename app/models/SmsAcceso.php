<?php

class SmsAcceso extends \Eloquent {
    protected $connection = 'Sms';
	protected $table = "SmsAcceso";
	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('usuario_id','imei','aud_usuario_id','aud_usuario_mod_id','estado','baja_logica');
}