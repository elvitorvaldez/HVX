<?php
	require 'libs/PHPExcel/Classes/PHPExcel/IOFactory.php'; //Agregamos la librería 
	//require 'conexion.php'; //Agregamos la conexión
	
	//Variable con el nombre del archivo
	$nombreArchivo = 'ejemplo.xlsx';
	// Cargo la hoja de cálculo
	$objPHPExcel = PHPExcel_IOFactory::load($nombreArchivo);
	
	//Asigno la hoja de calculo activa
	$objPHPExcel->setActiveSheetIndex(0);
	//Obtengo el numero de filas del archivo
	$numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
	
	echo '<table border=1>';
	
	for ($i = 1; $i <= $numRows; $i++) {
		
		$nombre = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
		$precio = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
		//$existencia = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
		
		echo '<tr>';
		echo '<td>'. $nombre.'</td>';
		echo '<td>'. $precio.'</td>';
		//echo '<td>'. $existencia.'</td>';
		echo '</tr>';
		
		//$sql = "INSERT INTO productos (nombre, precio, existencia) VALUES('$nombre','$precio','$existencia')";
		//$result = $mysqli->query($sql);
	}
	
	echo '<table>';
?>