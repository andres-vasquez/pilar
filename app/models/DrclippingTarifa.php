<?php

class DrclippingTarifa extends \Eloquent {

    protected $connection = 'DrClipping';
    protected $table = "p_tarifa";

	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('aud_usuario_id','aud_usuario_mod_id','estado','baja_logica','medio_id','color_id','color'
    ,'dia','ubicacion','ubicacion_id','tarifa');
}