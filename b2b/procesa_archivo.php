<?php
libxml_use_internal_errors(true);
require 'libs/PHPExcel/Classes/PHPExcel/IOFactory.php'; //Agregamos la librería 
require 'controllers/obtener_datos_iniciales.php'; //Agregamos la librería
require 'controllers/obtener_info_material.php'; //Agregamos la librería
require 'controllers/validaciones_pedido.php'; //Agregamos la librería
require 'controllers/enviar_nuevo_pedido.php'; //Agregamos la librería
require 'libs/nusoap/nusoap.php';
require 'models/config.php';





function procesaArchivoExcel($nombreArchivo){	
	//Variable con el nombre del archivo
	$nombreArchivo = 'archivos/'.$nombreArchivo;
	// Cargo la hoja de cálculo
	$objPHPExcel = PHPExcel_IOFactory::load($nombreArchivo);
		
	//Asigno la hoja de calculo activa
	$objPHPExcel->setActiveSheetIndex(0);
	//Obtengo el numero de filas del archivo
	$numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
	
	$arreglo_datos = array();	
	$orden_compra = "";
	$proyecto = "";

	for ($i = 2; $i <= $numRows; $i++) {
			
		$c1 = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
		$c2 = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
		//$existencia = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
		array_push($arreglo_datos, array($c1, $c2));

		if($c1 == "Orden Compra"){
			$orden_compra = $c2;
		}
		if($c1 == "Proyecto"){
			$proyecto = $c2;
		}

	}

	$cliente = "1-01";
	echo "Archivo Encontrado: ".$nombreArchivo;
	echo "<hr>";
	echo "Cliente: ".$cliente;

	//$datos_iniciales = obtenerDatosInicialesSQL($cliente);
	$datos_iniciales = obtenerDatosIniciales($cliente);
        
        //validamos con array de datos iniciales
        array_push($datos_iniciales, $orden_compra,$proyecto);
	
	//print_r($datos_iniciales); echo "<br><br>";
	$region = $datos_iniciales[0];
	unset($datos_iniciales[0]);

	echo "<hr>";
	echo "Obtenemos datos para enviar pedido: ";
	print_r($datos_iniciales);

	foreach ($arreglo_datos as $key => $value) {
		if($value[0] == "Orden Compra"){
			echo "<hr><br>";
			echo "Grabando Pedido Final...";
			break;
		}

		$modelo = $value[0];
		$cantidad = $value[1];
		//$tipo = "01";
		$var_guarda = "1";

		if(validarCantidad($modelo, $cantidad)){
			$val_reg = validarRegion($region);

			echo "<hr>";
			echo "Validando Cantidades y politicas";

			$datos_iniciales[8] = $modelo;
			$datos_iniciales[9] =  $cantidad;
			$datos_iniciales[10] =  $tipo;
			$datos_iniciales[11] =  $var_guarda;
			
		}
		echo "<hr>Productos<br>"; 
		print_r($datos_iniciales);

		echo "<hr>";
		echo "Guardando Parte-Pedido en SAP...";
	}


}

function procesaArchivoXML($nombreArchivo){
   echo "<b>Procesando $nombreArchivo</b><br>";
	 $tipo = "01";
   $bloqueo = "";
   //$nomarch = str_replace("GILSA_SCI_01_", "", $nombreArchivo);
   $nomarch=$nombreArchivo;
   //$pos = strpos($nomarch, "_");
   //$nomarch=substr($nomarch, 0, $pos);
   $nomarch.="_".date("Y-m-d");
  
   $numarch=1;
  


        if (file_exists("archivos/arrival/".$nombreArchivo)) {  
            
            $xml = simplexml_load_file("archivos/arrival/".$nombreArchivo);   

                if ($xml === false) {
                     $errors = libxml_get_errors();

                foreach ($errors as $error) {
                    echo display_xml_error($error, $xml);
                }

                libxml_clear_errors();
                }
            else
            {
               
                    
                $ns = $xml->getNamespaces(true); 
         
                //$xml->registerXPathNamespace('c', $ns['ns1']);
                $xml->registerXPathNamespace('ns1', $ns['ns1']);



                  $EstatusOrden =  $xml->Cabecera->COC[0]['EstatusOrden'];


                  foreach ($xml->Cabecera->COC as $coc){       
                  $NumeroOrdenCompra = $coc['NumeroOrdenCompra']; 
                  $FechaOrdenCompra=$coc['FechaOrdenCompra'];
                  $EstatusOrden=$coc['EstatusOrden'];
                  $TerminosPago=$coc['TerminosPago'];
                  $Revision=$coc['Revision'];
                  $Extra1COC=$coc['Extra1COC'];
                  $Extra2COC=$coc['Extra2COC'];    
                  }



                  if (!$EstatusOrden)
                  {
                 foreach ($xml->xpath('//ns1:Documento//ns1:Cabecera//ns1:COC') as $coc){                   
                  $NumeroOrdenCompra = $coc['NumeroOrdenCompra']; 
                  $FechaOrdenCompra=$coc['FechaOrdenCompra'];
                  $EstatusOrden=$coc['EstatusOrden'];
                  $TerminosPago=$coc['TerminosPago'];
                  $Revision=$coc['Revision'];
                  $Extra1COC=$coc['Extra1COC'];
                  $Extra2COC=$coc['Extra2COC'];                    
                   }
                 }

              
                 if ($Extra2COC == "Orden Terceros")
                  {
                      $tipo = "04";
                      $bloqueo = "01";
                  }
          
     
              

                  //$Emisor = $xml->Control->CIN[0]['Emisor'];    
                  //$Receptor = $xml->Control->CIN[0]['Receptor']; 
                  $Receptor = "GILSACIN";  
                  $Emisor = "HELVEXCIN";    
                  $TipoDocumento = 'CO';

                  $Proveedor = $xml->Control->SCI[0]['Proveedor'];
                  $Distribuidor =  $xml->Control->SCI[0]['Distribuidor']; 

                  if (!$Proveedor)
                  {
                    foreach ($xml->xpath('//ns1:Documento//ns1:Control//ns1:SCI') as $sci){                   
                      $Proveedor = $sci['Proveedor']; 
                      $Distribuidor = $sci['Distribuidor'];                   
                    }
                  }
           

              foreach ($xml->Cabecera->FAA as $faa){ 
              $FacturarAD =  $xml->Cabecera->FAA[0]['FacturarAD']; 
              }

              if (!$FacturarAD)
              foreach ($xml->xpath('//ns1:Documento//ns1:Cabecera//ns1:FAA') as $faa){                   
                  $FacturarAD = $faa['FacturarAD'];    
              }



              foreach ($xml->Cabecera->ENA as $ena){ 
                  $EnviarAD = $ena['EnviarAD'];
                  $EnviarAP = $ena['EnviarAP'];
                  $ENombreLocalidad = $ena['ENombreLocalidad'];
                  $ECalle = $ena['ECalle'];
                  $ENumero = $ena['ENumero'];
                  $EInterior = $ena['EInterior'];
                  $EColonia = $ena['EColonia'];
                  $EMunicipio = $ena['EMunicipio'];
                  $ELocalidad = $ena['ELocalidad'];
                  $EEstado = $ena['EEstado'];
                  $ECodigoPostal = $ena['ECodigoPostal'];
                  $EPais = $ena['EPais']; 
              }

              

              if (!$EnviarAD)
              {
               foreach ($xml->xpath('//ns1:Documento//ns1:Cabecera//ns1:ENA') as $ena){                   
                  $EnviarAD = $ena['EnviarAD'];
                  $EnviarAP = $ena['EnviarAP'];
                  $ENombreLocalidad = $ena['ENombreLocalidad'];
                  $ECalle = $ena['ECalle'];
                  $ENumero = $ena['ENumero'];
                  $EInterior = $ena['EInterior'];
                  $EColonia = $ena['EColonia'];
                  $EMunicipio = $ena['EMunicipio'];
                  $ELocalidad = $ena['ELocalidad'];
                  $EEstado = $ena['EEstado'];
                  $ECodigoPostal = $ena['ECodigoPostal'];
                  $EPais = $ena['EPais']; 
               } 
              }

                if ($ENombreLocalidad == "PROYECTOS")
                  {
                      $tipo = "04";
                      $bloqueo = "01";
                  }


                $articulos[]=array();
                $indice=0;




            if ($xml->Detalle->ART)
            {
              foreach ($xml->Detalle->ART as $art){                              
                
                  $articulos[$indice][0] =$art['NumArtP'];
                  //echo "* Modelo * ".$articulos[$indice][0]."<br>";
                  $articulos[$indice][1] = $art['CantidadOrdenada'];
                  $articulos[$indice][2] = $art['PrecioUnitario'];
                  $articulos[$indice][4] = $art['Linea'];
                  $articulos[$indice][5] = $art['UDM'];
                  $articulos[$indice][6] = $art['NumArtD'];
                  $articulos[$indice][7] = $art['Descripcion'];
                  $articulos[$indice][8] = $art['FechaEntregaArt'];
                  $articulos[$indice][9] = $art['FechaEnvioArt'];
                  $articulos[$indice][10] = $art['FechaEnvioBackorder'];
                  $indice++;
              }
            }
            else 
            {
              foreach ($xml->xpath('//ns1:Documento//ns1:Detalle//ns1:ART') as $art){                              
                print_r($art);
                  $articulos[$indice][0] =$art['NumArtP'];
                  //echo "* Modelo * ".$articulos[$indice][0]."<br>";
                  $articulos[$indice][1] = $art['CantidadOrdenada'];
                  $articulos[$indice][2] = $art['PrecioUnitario'];
                  $articulos[$indice][4] = $art['Linea'];
                  $articulos[$indice][5] = $art['UDM'];
                  $articulos[$indice][6] = $art['NumArtD'];
                  $articulos[$indice][7] = $art['Descripcion'];
                  $articulos[$indice][8] = $art['FechaEntregaArt'];
                  $articulos[$indice][9] = $art['FechaEnvioArt'];
                  $articulos[$indice][10] = $art['FechaEnvioBackorder'];
                  $indice++;
              }
            }






            if ($xml->Resumen->RES)
            {
              foreach ($xml->Detalle->RES as $res){ 
                $MensajeLibreRES1 = $coc['MensajeLibreRES1']; 
                $MensajeLibreRES3=$coc['MensajeLibreRES3'];
              }                             
            }
            else
            {
       
              foreach ($xml->xpath('//ns1:Documento//ns1:Resumen//ns1:RES') as $res){                   
                  $MensajeLibreRES1 = $coc['MensajeLibreRES1']; 
                  $MensajeLibreRES3=$coc['MensajeLibreRES3'];
              }
            }

              //Buscar datos iniciales
              //$cliente="21-02";
              $cliente="1-01  ";
              $datos_iniciales = obtenerDatosIniciales($cliente);
              $grupovacio=array('org'=>'0','sec'=>'0');
              array_push($datos_iniciales, $grupovacio);
             
              $materiales=array();
              $productosExiste=1;
              $pasada=0;

              //print_r($articulos);
              //echo "<br>".$articulos[0][0];
              foreach ($articulos as $productos=>$valor)
              {
                //ws para buscar productos
                // echo "<br>".$valor[0]."<br>";

                if ($EstatusOrden=='M')
                {
                  $valor[0]="ya existe";
                }

                $material = validaMaterial($valor[0]);

                // echo "<br>El material de $valor[0] es";
                // print_r($material);
                // echo "<br>";
                 $statusArt=="";
                        
                        
                        
                if ($material[1]==0 && $material[2]==0)
                {
                    echo "<br>Producto $valor[0] no encontrado iteracion $pasada<br>";
                    $productosExiste=0;
                    $statusArt="AR";
                }
                else
                {
                  $statusArt="AA";
                }
                $articulos[$pasada]['statusArt'] = $statusArt; 
                $materiales[$pasada][0]=$material[1];
                $materiales[$pasada][1]=$material[2];
                $materiales[$pasada][2]=0;
                $articulos[$pasada][3]=$material[1].' - '.$material[2];
                $pasada++;
              }


              //validar si el cliente cuenta con permisos de comprar los productos 
                $pasada=0;
                foreach ($materiales as $datosMat)
                {
                    foreach ($datos_iniciales as $datos)
                    {
                        if ($datos['org']==$datosMat[0] && $datos['sec']==$datosMat[1])
                        {
                            $materiales[$pasada][2]=1;                       
                        }
                    }    
                    $pasada++;
                }


                foreach ($materiales as $datosMat)
                {
                        if ($datosMat[2]==0)
                        {
                            echo $datosMat[0]." - ".$datosMat[1]." no cuenta con permisos....";
                            //die();
                        }
                    
                }

                //validar políticas
                foreach ($articulos as $productos=>$valor)
                {
                    $modelo=$valor[0];
                    $cantidad=(int)$valor[1];
                    //$tipo = "01";
                    $var_guarda = "1";
                    //echo "<br>".$modelo." - ".$cantidad;
                    if(validarCantidad($modelo, $cantidad)){
                        $val_reg = validarRegion($region);

                        // echo "<hr>";
                        // echo "Validando Cantidades y politicas";

                        // $datos_iniciales[8] = $modelo;
                        // $datos_iniciales[9] =  $cantidad;
                        // $datos_iniciales[10] =  $tipo;
                        // $datos_iniciales[11] =  $var_guarda;
                        
                    }
                }
               
              //Se crean los grupos de acuerdo a los contenidos en el arreglo de datos iniciales
              
                       
              foreach ($datos_iniciales as $datos)
              {

                $grupo=$datos['org']." - ".$datos['sec']; 
                //echo $grupo."<br>";
                $grupos[$grupo]=array();
                  //rellenar arreglo de grupos
                    $itera=0;
                   foreach ($articulos as $key => $value) {
                //    echo "grupo ".$grupo." -- value ".$value[3]."<br>";
                    if($grupo==$value[3])
                    {                      
                        array_push($grupos[$grupo], $articulos[$itera]);                        
                    }
                    $itera++;
                    }

              }

            //Se guarda (envía) el pedido por bloques  
            $guardarNuevoPedido=array();
            
            $enviarNuevoPedido=array();            

            $iterar=0;

            //die("Bloqueo ".$bloqueo);

            foreach ($grupos as $groups => $value) {

                $elementos=sizeof($value);
                if ($elementos>0)
                {
                    //echo "<br><br><b>grupo $groups</b><br>";
                    $idSesion=generaIdSesion();
                    $idSesion=substr($idSesion, 1);
                    foreach ($articulos as $key => $valor) {
                                  
                       if ($groups==$valor[3])
                        {
                        

                        $modelo=$valor[0];
                        $cantidad=$valor[1];
                        $precio=$valor[2];
                        
                        $datosgrupo=explode("-",$valor[3]);

                        $gpo=trim($datosgrupo[0]);
                        
                        $sector=trim($datosgrupo[1]);



                        //echo "$gpo - $sector <br>";
                        // echo "<br>Datos<br><br>Cliebte $cliente<br><br>Sector $sector<br><br>Modelo $modelo<br><br>Cantidad $cantidad<br><br>sesion $idSesion<br><br>";
                        //echo "<br>Registros 1<br>";
                    
                        $enviarNuevoPedido[$iterar]=enviarNuevoPedido($bloqueo,$gpo,"",$cliente,$sector,"","","","","","",$cliente,$modelo,$tipo,"",$NumeroOrdenCompra,"",$cantidad,"3101",1,$idSesion,"","","","");
                        
                        //echo $iterar."   ".$enviarNuevoPedido[$iterar]["OMessage"]."***** <br>";
                        
                        //echo $statusArt."<br>";
                           // print_r($enviarNuevoPedido[$iterar]);
                           // echo"<hr><br>";
                        
                        $iterar++;
                        //echo"<br><br>***".$enviarNuevoPedido['TInterface']['item']['Material']."<br><br>";
                        
                         }



                    }
                    //echo "<br>Registro 4<br>";

                    $guardarNuevoPedido[$groups]=enviarNuevoPedido($bloqueo,$gpo,"",$cliente,$sector,"","","","","","",$cliente,$modelo,$tipo,"","","",$cantidad,"3101",4,$idSesion,"","","","");

                    array_push($guardarNuevoPedido[$groups], $gpo.' - '.$sector);  

                    // print_r($guardarNuevoPedido[$groups]);
                    //      echo"<hr><br>";

                        }
                
            }

            //print_r($guardarNuevoPedido);

              //crear xml
    //con base en las respuestas de inserción 4 (array guardarNuevoPedido)
    

    foreach ($guardarNuevoPedido as $respXgrupo => $resp) {
    
    $grupoActual=end($resp);
            
    $xml = new DomDocument('1.0', 'UTF-8');
 
    $documento = $xml->createElement('Documento');
    $documento->setAttribute('xmlns', "http://www.tredicom.com/ConfirmacionOrdenCompra");
    $documento->setAttribute('xmlns:xsd', "http://www.w3.org/2001/XMLSchema");    

    $control = $xml->createElement('Control');
    $control = $xml->appendChild($control);
 
    $cin = $xml->createElement('CIN');
    $sci = $xml->createElement('SCI');
    
     
    // Agregar un atributo al libro
    $cin->setAttribute('Emisor', "HELVEXCIN");
    $cin->setAttribute('Receptor', "GILSACIN");
    $cin->setAttribute('FechaCreacionDocumento', date('Y-m-d').'T'.date('H:i:s'));
    $cin->setAttribute('Bandera', 'T');
    $cin->setAttribute('Version', '1.0');
    $cin->setAttribute('NumDocumento', $idSesion);
    
    $sci->setAttribute('TipoDocumento', $TipoDocumento);
    $sci->setAttribute('Distribuidor', $Distribuidor);
    $sci->setAttribute('Proveedor', $Proveedor);
    
    $control->appendChild($cin);
    $control->appendChild($sci);
    $documento->appendChild($control);



    $cabecera = $xml->createElement('Cabecera');
    
     
    $cco = $xml->createElement('CCO');
    
    $cco->setAttribute('PropositoTransaccion', 'OR');
    $TipoConfirmacion="RE";
    if ($resp['OMessage']=="PEDIDO CREADO EXITOSAMENTE")
    {
      $TipoConfirmacion="AT";
    }
    
    if ($EstatusOrden =='M' && $resp['OMessage']=="ERROR PEDIDO NO CREADO")
    {
      $cco->setAttribute('Revision', $Revision);
      $resp['OMessage'].=" - ORDEN DE COMPRA YA PROCESADA";
    }

    $cco->setAttribute('TipoConfirmacion', $TipoConfirmacion);
    $cco->setAttribute('NumeroOrdenCompra', $NumeroOrdenCompra);
    $cco->setAttribute('FechaOrdenCompra', $FechaOrdenCompra);
    $cco->setAttribute('NumeroPedidoProveedor', (int)$resp['OSalesdocument']);
    $cco->setAttribute('FechaConfirmacion', date("Y-m-d"));
    $cco->setAttribute('FechaEntregaDistribuidor', $FechaOrdenCompra);
    $cco->setAttribute('FechaEntregaProveedor', $FechaOrdenCompra);
    $cco->setAttribute('TextoLibreOrden', $resp['OMessage']);
    
    $cabecera->appendChild($cco);
    


    $documento->appendChild($cabecera);




//DETALLE
    
    $detalle = $xml->createElement('Detalle');
    

//ART

    
      $iterar=0;     
      $montoacumulado=0; 
      foreach ($articulos as $productos){
  
        $monto=0;
        //print_r($enviarNuevoPedido[$iterar]);
        $grupoEnviado=$productos[3];

        //echo "<br>Grupos ".$grupoActual." vs ".$grupoEnviado." *** ".strcmp($grupoActual,$grupoEnviado)."<br>";
        if ($grupoActual==$grupoEnviado)
        {   
          
         echo "<br>Agregar ".$grupoActual." == ".$grupoEnviado."<br>";
         $monto=$productos[1]*(float)$productos[2];
         (float)$montoacumulado+=$monto;
        // print_r($productos);
        $art = $xml->createElement('ART');
        $art->setAttribute('Linea', $productos[4]);
        $art->setAttribute('CantidadOrdenada', $productos[1]);
        $art->setAttribute('UDM', $productos[5]);
        $art->setAttribute('NumArtD', $productos[6]);
        $art->setAttribute('NumArtP', $productos[0]);
        $art->setAttribute('Descripcion', $productos[7]);
        $art->setAttribute('PrecioUnitario', $productos[2]);
        // $art->setAttribute('TextoLibre', 'Texto Libre de Prueba');


        //CAR
    $car = $xml->createElement('CAR');

  
 
    $car->setAttribute('EstatusArticulo', $articulos[$iterar]['statusArt']);
    $car->setAttribute('Cantidad', $productos[1]);
    $car->setAttribute('CantidadBackorder', '0');
    if (isset($productos[9]))
    $car->setAttribute('FechaEnvioArt', $productos[9]);
    if (isset($productos[8]))
    $car->setAttribute('FechaEntregaArt', $productos[8]);
    if (isset($productos[10]))
    $car->setAttribute('FechaEnvioBackorder', $productos[10]);
    //$car->setAttribute('TextoLibre', 'Texto Libre de Prueba');
    
    
    $art->appendChild($car);

        $detalle->appendChild($art);
       }
      
    $iterar++;
   }
  

    $documento->appendChild($detalle);
    //Resumen
    
    $resumen = $xml->createElement('Resumen');
    $res = $xml->createElement('RES');
    $res->setAttribute('TotCantidadConfirmada', number_format($montoacumulado, 2, '.', ''));
    $resumen->appendChild($res);
    $documento->appendChild($resumen);
    $xml->appendChild($documento);
    //$documento->appendChild( $cabecera );
    
    $xml->formatOutput = true;
    $el_xml = $xml->saveXML();

    $xml->save('Salida/'.$nomarch.'_'.$numarch.'.xml');
    $numarch++;
    //Mostramos el XML puro
    echo '<p><b>El '.$nomarch.'_'.$numarch.'.xml XML ha sido creado</b></p>';
   
    //....Contenido en texto plano:</b></p>".htmlentities($el_xml)."<br/><hr>";
  }//fin foreach

    rename("archivos/arrival/".$nombreArchivo,"archivos/procesados/".$nombreArchivo) or die ("No lo movi&oacute;");
    echo "<p><b>El XML origen ha sido eliminado</b></p>";


            }
        } else {
            exit('Error abriendo archivo xml (como si no existiera).');
            $errors = libxml_get_errors();

                foreach ($errors as $error) {
                    echo display_xml_error($error, $xml);
                }

                libxml_clear_errors();
        }
        }

function procesaArchivoCSV($nombreArchivo){
    
    //$archivo = fopen("archivos/".$nombreArchivo,"r");
    $handle = fopen("archivos/".$nombreArchivo, "r");
    $linea = 0;
    $numero = 0;
    $datos_csv = array();
    $productos = array();
    $prods_pedido = array();
    $final_prods = array(
            "Z101-01" => array(),
            "Z101-02" => array(),
            "Z101-03" => array(),
            "Z101-04" => array(),
            "Z201-01" => array(),
            "Z201-02" => array(),
            "Z201-03" => array(),
        );
    
    while (($data = fgetcsv($handle)) !== FALSE) {
        
        if ($linea == 0){
            $datos_csv['idCliente'] = $data[0];
            $datos_csv['token'] = $data[1];
        }
        if($linea == 1){
            $datos_csv['idDireccion'] = $data[0];
            $datos_csv['orden'] = $data[1];
            $datos_csv['motivo'] = $data[2];
            $datos_csv['bidding'] = $data[3];
            $datos_csv['proyecto'] = $data[4];
            $datos_csv['amount'] = $data[5];
        }
        if($linea > 1){
            array_push($productos, array($data[0],$data[1]));
        }

        $linea ++;
        
    }
    //$datos_csv['productos'] = $productos;
    
    //Cerramos el archivo
    fclose($archivo);
    
    $idSesion = generaIdSesion();
    $datos_csv['idSesion'] = $idSesion;
    //La variable $datos_iniciales es un arreglo que guarda las organizaciones y sectores 
    //a los que tiene acceso un cliente
    $orgs_secs = obtenerDatosIniciales($datos_csv['idCliente']);
    
   /*****************************/
    foreach ($productos as $producto) {
        $material = validaMaterial($producto[0],$producto[1]);
        array_push($prods_pedido, $material);
    
    }

    foreach ($prods_pedido as $prodp){
        $final_prods[$prodp[1]."-".$prodp[2]]["org"] = $prodp[1];
        $final_prods[$prodp[1]."-".$prodp[2]]["sec"] = $prodp[2];
        foreach ($orgs_secs as $val_org){
            if($val_org['org'] == $prodp[1] && $val_org['sec'] == $prodp[2]){
                array_push($final_prods[$prodp[1]."-".$prodp[2]] , array($prodp[0],$prodp[3]));
            }
        }
        
        
    }
        
    foreach ($final_prods as $pedido) {
        if(!empty($pedido)){
            //echo "<br><hr><br>";
            $iclaseped = $pedido['org']; //Z101
            $icollectno = ""; //""
            $idestinatario= $datos_csv['idCliente']; //1-01
            $idivision = $pedido['sec']; //01
            $ihdrtxt1 = ""; //""
            $ihdrtxt2 = ""; //""
            $ihdrtxt3 = ""; //""
            $ihdrtxt4 = ""; //""
            $ihdrtxt5 = ""; //""
            $iitemcateg = ""; //""
            $ikunnr1 = $datos_csv['idCliente']; //"1-01"
            //$imaterial1 = ""; //"24"
            $iordreason= $datos_csv['orden']; //"01"
            $iplant = ""; //""
            $ipurchnoc = ""; //""
            $ireqqty = ""; //""
            $ireqqty1 = ""; //"33"
            $isalesorg = ""; //"3101"
            $iseleccion = ""; //"1||4"
            $isesion= ""; //"Z000000001"
            $itargetqty = ""; //""
            
            //print_r($pedido);
        }
        
    }
    echo "Archivo";
    
    $xml = new DOMDocument( "1.0", "UTF-8" );
 
    $documento = $xml->createElement('Documento');
    $control = $xml->createElement('Control');
    
    $cin = $xml->createElement('CIN');
    $sci = $xml->createElement('SCI');
    
     
    // Agregar un atributo al libro
    $cin->setAttribute('Emisor', 'PROVEEDOR ID');
    $cin->setAttribute('Receptor', 'GILSACIN');
    $cin->setAttribute('FechaCreacionDocumento', '2018-01-03T16:26:11');
    $cin->setAttribute('Bandera', 'T');
    $cin->setAttribute('Version', '1.0');
    $cin->setAttribute('NumDocumento', '00000020');
    
    $sci->setAttribute('TipoDocumento', 'CO');
    $sci->setAttribute('Distribuidor', 'GILSA_SCI_01');
    $sci->setAttribute('Proveedor', 'PROVEEDOR ID');
    
    $control->appendChild($cin);
    $control->appendChild($sci);
    $documento->appendChild($control);


    $cabecera = $xml->createElement('Cabecera');
    $cco = $xml->createElement('CCO');
    
    $cco->setAttribute('PropositoTransaccion', 'OR');
    $cco->setAttribute('TipoConfirmacion', 'AT');
    $cco->setAttribute('NumeroOrdenCompra', 'PO100');
    $cco->setAttribute('FechaOrdenCompra', '2018-01-03');
    $cco->setAttribute('NumeroPedidoProveedor', 'PV10000');
    $cco->setAttribute('FechaConfirmacion', '2018-01-03');
    $cco->setAttribute('FechaEntregaDistribuidor', '2018-01-03');
    $cco->setAttribute('FechaEntregaProveedor', '2018-01-03');
    $cco->setAttribute('TextoLibreOrden', 'Entrega de Mercancia Completa');
    
    $cabecera->appendChild($cco);
    
//DETALLE
    
    $detalle = $xml->createElement('Detalle');
    
//ART
    //foreach ($pedido as $linea){
      //  var_dump($linea);
        $art = $xml->createElement('ART');
        
        
        $art->setAttribute('Linea', '1');
        $art->setAttribute('CantidadOrdenada', '3810');
        $art->setAttribute('UDM', 'MT');
        $art->setAttribute('NumArtD', '28361P2');
        $art->setAttribute('NumArtP', 'AW287');
        $art->setAttribute('Descripcion', 'Muestra Prueba Marmol pulido Rec 90X90');
        $art->setAttribute('PrecioUnitario', '164.23');
        $art->setAttribute('TextoLibre', 'Texto Libre de Prueba');
    //}
    
    
    $detalle->appendChild($art);
    /*
    $art = $xml->createElement('ART');
    $art = $detalle->appendChild($art);
    
    $art->setAttribute('Linea', '1');
    $art->setAttribute('CantidadOrdenada', '3810');
    $art->setAttribute('UDM', 'MT');
    $art->setAttribute('NumArtD', '28361P2');
    $art->setAttribute('NumArtP', 'AW287');
    $art->setAttribute('Descripcion', 'Muestra Prueba Marmol pulido Rec 90X90');
    $art->setAttribute('PrecioUnitario', '164.23');
    $art->setAttribute('TextoLibre', 'Texto Libre de Prueba');
    */
    //CAR
    // $car = $xml->createElement('CAR');
    
    
    // $car->setAttribute('EstatusArticulo', 'AA');
    // $car->setAttribute('Cantidad', '3810');
    // $car->setAttribute('CantidadBackorder', '0');
    // $car->setAttribute('FechaEnvioArt', '2018-01-03');
    // $car->setAttribute('FechaEntregaArt', '2018-01-03');
    // $car->setAttribute('FechaEnvioBackorder', '2018-01-03');
    // $car->setAttribute('TextoLibre', 'Texto Libre de Prueba');
    
    // $art->appendChild($car);
    
    //Resumen
    
    $resumen = $xml->createElement('Resumen');
    
    
    $res = $xml->createElement('RES');
    $res = $resumen->appendChild($res);
    
    $res->setAttribute('TotCantidadConfirmada', '6350');
    $resumen->appendChild($res);
    
    
    $documento->appendChild($cabecera);
    $documento->appendChild($detalle);
    $documento->appendChild($resumen);


    $xml->appendChild($documento);



    $xml->formatOutput = true;
    $el_xml = $xml->saveXML();
    $xml->save('xmlTest.xml');
 
    //Mostramos el XML puro
    echo "<p><b>El XML ha sido creado....Contenido en texto plano:</b></p>".print_r($xml)."<br/><hr>";

  
        //enviarNuevoPedido($iclaseped,$icollectno,$idestinatario,$idivision,$ihdrtxt1,$ihdrtxt2,$ihdrtxt3,$ihdrtxt4,$ihdrtxt5,$iitemcateg,$ikunnr1,$imaterial1,$iordreason,$iplant,$ipurchnoc,$ireqqty,$ireqqty1,$isalesorg,$iseleccion,$isesion,$itargetqty)
    /*
        "IClaseped"=>$iclaseped, //Z101
        "ICollectNo"=>$icollectno, // ""
        "IDestinatario"=>$idestinatario, //1-01
        "IDivision"=>$idivision, // 01
        "IHdrTxt1"=>$ihdrtxt1, // ""
        "IHdrTxt2"=>$ihdrtxt2, // ""
        "IHdrTxt3"=>$ihdrtxt3, // ""
        "IHdrTxt4"=>$ihdrtxt4, // ""
        "IHdrTxt5"=>$ihdrtxt5, // ""
        "IItemCateg"=>$iitemcateg, // ""
        "IKunnr1"=>$ikunnr1, // "1-01"
        "IMaterial1"=>$imaterial1, // "24"
        "IOrdReason"=>$iordreason, // "01"
        "IPlant"=>$iplant, // ""
        "IPurchNoC"=>$ipurchnoc, // ""
        "IReqQty"=>$ireqqty, // ""
        "IReqQty1"=>$ireqqty1, // "33"
        "ISalesOrg"=>$isalesorg, // "3101"
        "ISeleccion"=>$iseleccion, // "1||4"
        "ISesion"=>$isesion, // "Z000001" Id_Sesion
        "ITargetQty"=>$itargetqty, // ""
     */
}

function generaIdSesion(){
    $file_in = fopen("models/idSesion.txt", "r");
    $linea = fgets($file_in);
    fclose($file_in);
    
    $id = (int) substr($linea, 1);
    $id+=1;
    $id_final = "Z".$id;
    
    $file_out = fopen("models/idSesion.txt", "w");
    fwrite($file_out, $id_final);
    fclose($file_out);
    
    return $id_final;
}
