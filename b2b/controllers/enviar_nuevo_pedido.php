<?php
/*
enviar_nuevo_pedido.php

Utilizamos WS para enviar el nuevo pedido a SAP.

Se debes enviar los productos uno por uno 

Enviamos los siguientes datos: 
*Clase_pedido: Z101=>Helvex || Z201=> Proyecta (Clase de Pedido en SAP), 
*Sector: 01 || 04 || 09 || ETC. (Sector al que pertenecen los productos), 
*Id_SESION: A0000001 (Consecutivo generado para enviar el pedido), 
*ITEM_CATEG: "" (Campo Vacío), 
*Cuenta: 1-01 => El Surtidor (Cuenta del cliente que levanta el pedido),
*Direccion: 1-01 (Dirección de envío del pedido),
*Modelo: 24 (SKU del producto Helvex),
*Tipo: 01 => Normal|| 04 => Proyecto (Tipo de Pedido, se utiliza solamente el Normal),
*PLANT: "" (Campo Vacío)
*Orden de Compra: 012345 (Si el cliente tiene un numero de orden se manda aquí)
*REQ_QTY: "" (Campo Vacío)
*Cantidad: 15 (Número de unidades que se pidieron de el producto)
*Organización de Ventas: 3101 => Helvex || 2201 => Proyecta (Org. de Ventas a la que corresponde el pedido)
*Número de Control: 1 => Guardar en memoria | 4 => Grabar pedido (Numero de control que indica a SAP si el producto lo guarda en memoria [1] o si ya debe grabar el pedido [4])
*TARGET_QTY: "" (Campo Vacío)
*HdrTxt1: "" (Campo para texto de Notas)
*HdrTxt2: "" (Campo para texto de Notas)
*HdrTxt3: "" (Campo para texto de Notas)
*HdrTxt4: "" (Campo para texto de Notas)
*HdrTxt5: "" (Campo para texto de Notas)
*Cuenta: Null (Campo Vacío)
*Mensaje: Null (Campo Vacío)
*Documento de Ventas: "" (Campo Vacío)
*TXT_CUENTA: "" (Campo Vacío)
*Licitación: (Número de Licitación)

EJ (Z101,01,A0000001,"",1-01,1-01,24,01,"","","",20,3101,1,"","","","","","","","","","")

Devuelve:
Confirmación de HEADER
Confirmación por cada producto Agregado
Numero de Pedido

*/
//Incluimos a nusoap.php
//require '../libs/nusoap/nusoap.php';
//require '../models/config.php';

function enviarNuevoPedido($ibloqueoen,$iclaseped,$icollectno,$idestinatario,$idivision,$ihdrtxt1,$ihdrtxt2,$ihdrtxt3,$ihdrtxt4,$ihdrtxt5,$iitemcateg,$ikunnr1,$imaterial1,$iordreason,$iplant,$ipurchnoc,$ireqqty,$ireqqty1,$isalesorg,$iseleccion,$isesion,$itargetqty){

//Creamos al cliente con la función nusoap_client();
    //Le pasamos como parámetros el WSDL y como segundo le indicamos que es un 'wsdl'

    //Calidad
    $client = new nusoap_client("http://DQASR3.helvexmx.gpohx.local:9910/sap/bc/srt/wsdl/flv_10002A111AD1/bndg_url/sap/bc/srt/rfc/sap/zsdws_b2b_distrib/300/zsdws_b2b_distrib/binding?sap-client=300", 'WSDL');
    
    //Producción
    //$client = new nusoap_client("http://CIDBR3.helvexmx.gpohx.local:8020/sap/bc/srt/wsdl/flv_10002A111AD1/bndg_url/sap/bc/srt/rfc/sap/zsdgf_helvex_hd/500/zsdgf_helvex_hd/binding?sap-client=500", 'wsdl');

    //Pasamos las credenciales para la conexión: (User, Pass, 'basic')
    //$client->setCredentials(WS_USER, WS_PASS, 'basic');
    
    // $client->setCredentials("HXCPINEDA", "Hvx_R3Q2017", 'basic');
    $client->setCredentials("HX-PWEB", "#Zaq123wsX!", 'basic');

    //Si hay error en la conexión, lo obtenemos para saber que pasó
    $err = $client->getError();
    if ($err) {    
        return 8;
                //echo 'Error en Constructor' . $err ; 
    }
    //Creamos un arreglo con los parámetros que enviaremos 
    $params = //array("ZSdmfFuncsPedido" => 
                    array(
                        "IBloqueoEn"=>$ibloqueoen, //vacío si el tipo es 01, y 01 si el tipo es 04
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
                        "TInterface"=> array(
                            "item" => array(
                                "Mandt" => "", // ""
                                "Posnr" => "", // ""
                                "NoCliente" => "", // ""
                                "IdSesion" => "", // ""
                                "Flag" => "", // ""
                                "DocType" => "", // ""
                                "SalesOrg" => "", // ""
                                "DistrChan" => "", // ""
                                "Division" => "", // ""
                                "Material" => "", // ""
                                "Plant" => "", // ""
                                "TargetQty" => "", // ""
                                "ItemCateg" => "", // ""
                                "ReqQty" => "", // ""
                                "OrdReason" => "", // ""
                                "PurchNoC" => "", // ""
                                "NoClienteDes" => "" // ""
                            )
                        ),
                        "TReturn1" => array(
                            "item" => array(
                                "Type" => "", // ""
                                "Id" => "", // ""
                                "Number" => "", // ""
                                "Message" => "", // ""
                                "LogNo" => "", // ""
                                "LogMsgNo" => "", // ""
                                "MessageV1" => "", // ""
                                "MessageV2" => "", // ""
                                "MessageV3" => "", // ""
                                "MessageV4" => "", // ""
                                "Parameter" => "", // ""
                                "Row" => "", // ""
                                "Field" => "", // ""
                                "System" => "" // ""
                            )
                        )
                //)
            );
    
    //En la variable $response guardamos la respuesta del WS
    //La función call recibe el nombre de la función y los parámetros en un arreglo
    
    //var_dump($params);
    //die();
    $response = $client->call('ZSdmfFuncsPedido',$params);

    //Verificamos si hubo un error en la respuesta
    if ($client->fault) {
        echo '<br/>Fallo_Cli: <br><br>';
        return 6;
    } else {    // Chequea errores
        $err = $client->getError();
        if ($err) {     // Muestra el error
            echo '<br/>Error_Cli: ' . $err."<br><br>";
            print_r($client->getDebug());
            return 7;
        } else {        
        // Muestra el resultado, podemos tratar primero los datos o devolverlo tal cual
        	
		//var_dump($response);
		//die();
            return $response;
        }
    }
}