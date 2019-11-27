<?php
/*
validaciones_pedido.php

Funciones para validar algunos datos del pedido
1.- La cantidad máxima de un articulo que se puede pedir son 3000
*/

function validarCantidad($modelo, $cantidad){
	if(is_numeric($cantidad)){
		if ($cantidad <= 3000) {
			return true;
		}else{
			echo "La cantidad debe ser menor a 3000";
			return false;
		}
	} else{
		echo "La cantidad que ingresaste no es un número";
		return false;
	}
}

function validarRegion($region){
	if($region != "DF"){
		$is_local = "F";
	}else{
		$is_local = "L";		
	}
	return $is_local;
}