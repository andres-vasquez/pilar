<?php

class DrClippingAcceso extends \Eloquent {


    protected $connection = 'DrClipping';
    protected $table = "t_acceso";

	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('aud_usuario_id','aud_usuario_mod_id','estado','baja_logica',
        'usuario_id','imei','fecha_telefono','version_apk','datos_telefono');
}