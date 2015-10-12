<?php

class Expositore extends \Eloquent {
    protected $connection = 'Pilar';
	protected $table = "expositores";
	// Reglas de validacion
	public static $rules = [
		 'nombre' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('sistema_id','nombre','direccion','pabellon','stand','website','fanpage','estado','baja_logica','aud_usuario_id','aud_usuario_mod_id','id_csv','telefono','fax','rubro','rubro_id','descripcion','rubro_especifico','ruta_aws');
}