<?php

class Ofertas extends \Eloquent {
    protected $connection = 'Pilar';
	protected $table = "ofertas";
	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('aud_usuario_id','aud_usuario_mod_id','estado','baja_logica','rubro_id','rubro','expositor_id','expositor','html','sistema_id','link','empresa');
}