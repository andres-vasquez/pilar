<?php

class DrClippingUsuario extends \Eloquent {

    protected $connection = 'DrClipping';
    protected $table = "t_usuario";

	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('aud_usuario_id','aud_usuario_mod_id','estado','baja_logica','email','password',
        'nombre_completo','celular','imei');
}