<?php
/*
obtener_datos_iniciales.php

A partir del número de Cliente (KUNNR) ej(1-01) llamamos al WS que nos devuelve las Organizaciones y Sectores a las que tiene acceso.
También nos devuelve las direcciones alternas.
*/
//Incluimos a nusoap.php
require '../libs/nusoap/nusoap.php';
require '../models/config.php';


//Creamos al cliente con la función nusoap_client();
    //Le pasamos como parámetros el WSDL y como segundo le indicamos que es un 'wsdl'
    $kunnr = $argv[1];
    $fecha = date("Y-m-d");
    //Calidad
    $client = new nusoap_client("http://DQASR3.helvexmx.gpohx.local:9910/sap/bc/srt/wsdl/flv_10002A111AD1/bndg_url/sap/bc/srt/rfc/sap/zsdws_b2b_distrib/300/zsdws_b2b_distrib/binding?sap-client=300", 'WSDL');
    
    //Producción
    //$client = new nusoap_client("http://CIDBR3.helvexmx.gpohx.local:8020/sap/bc/srt/wsdl/flv_10002A111AD1/bndg_url/sap/bc/srt/rfc/sap/zsdgf_helvex_hd/500/zsdgf_helvex_hd/binding?sap-client=500", 'wsdl');

    //Pasamos las credenciales para la conexión: (User, Pass, 'basic')
    $client->setCredentials("HX-PWEB", "#Zaq123wsX!", 'basic');
    //$client->setCredentials(WS_USER, WS_PASS, 'basic');
    
    // $client->setCredentials("HXCPINEDA", "Hvx_R3Q2017", 'basic');
    //$client->setCredentials("HX-WSETIQUE", "inicio_123", 'basic');

    //Si hay error en la conexión, lo obtenemos para saber que pasó
    $err = $client->getError();
    if ($err) {    
        return 8;
                //echo 'Error en Constructor' . $err ; 
    }
    //Creamos un arreglo con los parámetros que enviaremos 
    $params = array(
                "IDate" => $fecha,
                "IKunnr" => $kunnr,
                "ISpart" => "01",
                "IVkorg" => "3101",
                "IVtweg" => "10",
                "TListaPrecios" => array(
                    "item"=> array(
                        "Matnr"=>"",
                        "Kunnr"=>"",
                        "Vkorg"=>"",
                        "Spart"=>"",
                        "Vtweg"=>"",
                        "Qdate"=>"",
                        "Maktx"=>"",
                        "BasePrice"=>"",
                        "CustPrice"=>"",
                        "WdisPrice"=>"",
                        "Discount"=>"",
                        "Meins"=>"",
                        "Konwa"=>"",
                        "Mtart"=>"",
                        "Bismt"=>"",
                        "Mstav"=>"",
                        "Matkl"=>"",
                        "Wgbez"=>"",
                        "Prdha"=>"",
                        "PrdhaF"=>"",
                        "VtextF"=>"",
                        "PrdhaT"=>"",
                        "VtextT"=>"",
                        "PrdhaC"=>"",
                        "VtextC"=>"",
                        "AtwrtS"=>"",
                        "AtwtbS"=>"",
                        "Kondm"=>"",
                        "VtextGm"=>"",
                        "Extwg"=>"",
                        "Ewbez"=>"",
                        "Vmsta"=>"",
                        "AtwrtP"=>"",
                        "AtwtbP"=>"",
                        "Provent"=>"",
                        "Ean11"=>"",
                        "Stlan"=>"",
                    )
                ),
                "TReturn"=> array(
                    "item" => array(
                        "Type"=>"",
                        "Message"=>"",
                    )
                )
            );
    
    //En la variable $response guardamos la respuesta del WS
    //La función call recibe el nombre de la función y los parámetros en un arreglo
    
    //var_dump($params);
    //die();
    $response = $client->call('ZSdmfListaPrecios',$params);

    //Verificamos si hubo un error en la respuesta
    if ($client->fault) {
        echo '<br/>Fallo_Cli: <br><br>';
        print_r($response);
        return 6;
    } else {    // Chequea errores
        $err = $client->getError();
        if ($err) {     // Muestra el error
            echo '<br/>Error_Cli: ' . $err."<br><br>";
            print_r($client->getDebug());
            return 7;
        } else {        
            // Muestra el resultado, podemos tratar primero los datos o devolverlo tal cual
            $archivo_csv = fopen('lista.csv', 'w');
            if($archivo_csv)
            {
                fputs($archivo_csv, "id,SKU,Descripcion,PrecioLista,Precio Distribuidor,idProveedor,Activo,Fotografia,Especificacion,Video,Status,Unidad, Moneda,Categorizacion".PHP_EOL);
                foreach($response['TListaPrecios']['item'] as $res){
                    if($res["CustPrice"] != 0){
                        fputs($archivo_csv, " ,".$res["Matnr"].",".$res["Maktx"].", ,".$res["CustPrice"].",,,http://portal.helvex.com.mx/images/productos/".$res["Matnr"].".jpg,,,,".$res["Meins"].",".$res["Konwa"].",".PHP_EOL);
                    }
                    
                }
                fclose($archivo_csv);
            }else{
            
                echo "El archivo no existe o no se pudo crear";
            }
        //var_dump($response);
        //die();
        
                //return $org_sec;
            }
        }
    //}