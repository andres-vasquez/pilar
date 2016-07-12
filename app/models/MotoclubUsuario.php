<?php

class MotoclubUsuario extends \Eloquent {
	protected $connection = 'Pilar';
	protected $table = "motoclublapaz_usuarios";
	// Reglas de validacion
	public static $rules = [
		// 'title' => 'required'
	];

		//Mensajes de error
	protected static $messages = [
		//'title.required' => 'Mensaje de ejemplo'
	];
	
	// Campos a incluirse en insert y update
	protected $fillable = array('aud_usuario_id','aud_usuario_mod_id','estado','baja_logica',
		'nombre' ,
		'apellido' ,
		'email' ,
		'foto_piloto',
		'fecha_nacimiento' ,
		'celular' ,
		'telefono_fijo' ,
		'genero' ,
		'ci' ,
		'tipo_sangre' ,
		'alergias' ,
		'nombre_contacto' ,
		'celular_contacto' ,
		'telefono_fijo_contacto' ,
		'seguro' ,
		'foto_moto' ,
		'marca' ,
		'modelo' ,
		'anio' ,
		'placa' ,
		'cilindrada' ,
		'ocupacion' ,
		'nacionalidad' ,
		'foto_pago' ,
		'declaro' ,
	);
}

