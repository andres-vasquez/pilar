<?php

class Tecnobitacceso extends \Eloquent {

    protected $connection = 'Pilar';
    protected $table = "tecnobit_acceso";
	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('usuario_id','origen');
}