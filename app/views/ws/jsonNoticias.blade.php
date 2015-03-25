<?php 
	$respuesta=array();
	$respuesta["intCodigo"]='1';
	$respuesta["resultado"]=$resultado["noticias"];
	echo json_encode($respuesta);
?>