<?php

class MapaTag extends \Eloquent {
    protected $connection = 'Pilar';
	protected $table = "Mapa_tag";
	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('estado','baja_logica');
}