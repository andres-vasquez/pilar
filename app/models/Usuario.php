<?php

class Usuario extends \Eloquent {

	// Reglas
	public static $rules = [
		 'nombre' => 'required',
		 'password' => 'required'
	];

	//Mensajes de error
	protected static $messages = [
        'nombre.required' => 'El nombre es requerido',
        'password.required' => 'El pasword es requerido'
    ];

	// Campos modificables
	protected $fillable = array('password','nombre','email','estado','baja_logica');

}