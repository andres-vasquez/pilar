<?php

class Publicidad extends \Eloquent {
    protected $connection = 'Pilar';
	protected $table = "Publicidad";
	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('sistema_id','nombre','descripcion','link','prioridad','clicks','estado','baja_logica','aud_usuario_id','tipo_publicidad');
}