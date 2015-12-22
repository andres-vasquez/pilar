<?php

class DrClippingAnalisi extends \Eloquent {

    protected $connection = 'DrClipping';
    protected $table = "t_analisis";
	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('aud_usuario_id','aud_usuario_mod_id','estado','baja_logica','usuario_id','publicacion_id',
        'tipo_noticia_id','tipo_noticia','nombre_publicacion','color_id','color','tamanio','cuerpo_id','cuerpo','dia',
        'tarifa_id','tarifa','valoracion_id','valoracion','comentario','args');
}