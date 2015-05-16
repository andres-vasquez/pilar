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
	protected $fillable = array('identificador','usuario_id','tipo');
}