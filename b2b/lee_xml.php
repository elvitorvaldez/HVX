<?php
/*
**
*Carga un archivo XML que está dentro de un directorio y lo guarda en una variable

simplexml_load_file( $ruta_xml, $clase, $opciones, $ns, $prefijo);

    - $ruta_xml (Obligatorio): En esta variable debemos pasar la ruta del XML que queremos cargar.
    - $clase (Opcional): Especifica la clase del objeto donde se carga el XML.
    - $opciones (Opcional): Configuración de los parámetros adicionales de la librería libxml, más info aquí.
    - $ns (Opcional): Valor para especificar un namespace o URI.
    - $prefijo (Opcional): TRUE si es namespace y FALSE si es URI.
**
**
*Carga texto en formato XML guardado en una variable (utiliza los mismos parámetros)

simplexml_load_string( $variable_xml, $clase, $opciones, $ns, $prefijo);

*/
	
//$carga_xml = simplexml_load_file("/ruta_del_xml/nombre.xml");
$myXMLData =
"<?xml version='1.0' encoding='UTF-8'?>
<note>
<to>Tove</to>
<from>Jani</from>
<heading>Reminder</heading>
<body>Don't forget me this weekend!</body>
</note>";

$xml=simplexml_load_string($myXMLData) or die("Error: Cannot create object");
print_r($xml);

?> 