<?php

class Mapa extends \Eloquent {
    protected $connection = 'Pilar';
	protected $table = "Mapa";
	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('sistema_id','nombre','tipo','color','atributo1','atributo2','atributo3','json','estado','baja_logica');
}