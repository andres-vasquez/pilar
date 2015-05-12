<?php

class SmsUsuario extends \Eloquent {
    protected $connection = 'Sms';
	protected $table = "SmsUsuario";
	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('username','nombre','nombre_deposito','digitos_celular','celular','clabe','estado_clabe','banco_id','banco','email','estado_email','ultimo_mensaje','aud_usuario_id','aud_usuario_mod_id','estado','baja_logica');
}