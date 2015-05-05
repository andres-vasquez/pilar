<?php

class SmsConfiguracion extends \Eloquent {
    protected $connection = 'Sms';
	protected $table = "SmsConfiguracion";
	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('ganancia','fecha_inicio','fecha_fin','fni_mensaje','ffn_mensaje', 'mensaje_pago','aud_usuario_id','aud_usuario_mod_id','estado','baja_logica');
}