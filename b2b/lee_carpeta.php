<?php

require 'procesa_archivo.php';

$directorio = opendir("archivos/arrival"); //ruta actual
$archivos_excel = array();
$archivos_xml = array();

while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
{
    echo "<br><br>".$archivo;
    if (is_dir($archivo))//verificamos si es o no un directorio
    {
        //echo "[".$archivo . "]<br />"; //de ser un directorio lo envolvemos entre corchetes
    }
    else
    {
    	if(strripos($archivo, ".xlsx") || strripos($archivo, ".xls")){
    		//array_push($archivos_excel, $archivo);
    		//procesaArchivoExcel($archivo);
    	} 
    	else if (strripos($archivo, ".xml")){
    		//array_push($archivos_xml, $archivo);
            	procesaArchivoXML($archivo);
                //break;
    	}else if (strripos($archivo, ".csv")){
    		//array_push($archivos_xml, $archivo);
    		//procesaArchivoCSV($archivo);
                break;
    	}else{

    	}
    }
}

//print_r($archivos_xml);

?>