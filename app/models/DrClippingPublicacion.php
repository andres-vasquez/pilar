<?php

class DrClippingPublicacion extends \Eloquent {

    protected $connection = 'DrClipping';
    protected $table = "t_publicacion";

	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('aud_usuario_id','aud_usuario_mod_id','estado','baja_logica','usuario_id','ciudad_id',
        'ciudad','tipo_medio_id','tipo_medio','medio_id','medio','fecha_publicacion','ubicacion_id','ubicacion',
        'pagina','empresa','tipo_noticia_id','tipo_noticia','url_foto1','url_foto2','estado_tarea');
}