<?php
/*
obtener_precio_material.php

Utilizamos WS para obtener el precio que vamos a utilizar.

Enviamos los siguientes datos: (Cliente, Cantidad, Material, Sector, Organizacion)
EJ (1-01,5,24,01,3101)

*/
//Incluimos a nusoap.php
//require '../libs/nusoap/nusoap.php';
//require '../models/config.php';

function validaMaterial($matnr,$cant){    
//Creamos al cliente con la función nusoap_client();
    //Le pasamos como parámetros el WSDL y como segundo le indicamos que es un 'wsdl'

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
                "IMatnr" => $matnr,
                "TProductoInfo" => array(
                    "item"=> array(
                        "Matnr"=>"",
                        "Werks"=>"",
                        "Vkorg"=>"",
                        "Vtweg"=>"",
                        "Maktx"=>"",
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
                        "Meins"=>"",
                        "Spart"=>"",
                        "Kondm"=>"",
                        "VtextGm"=>"",
                        "Extwg"=>"",
                        "Ewbez"=>"",
                        "Vmsta"=>"",
                        "AtwrtP"=>"",
                        "AtwtbP"=>"",
                        "Provent"=>"",
                        "Ean11"=>"",
                        "Kbetr"=>"",
                        "Konwa"=>"",
                        "Stlan"=>"",
                        )
                    ),
                "TReturn" => array(
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
    $response = $client->call('ZSdmfProductoInfo', $params);

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
            $producto = array();
            if($response['OReturn'] == 0){
                array_push($producto,$matnr);
                $org = $response['TProductoInfo']['item'][0]['Vkorg'];
                if($org == "3101"){$org = "Z101";}elseif($org == "2201"){$org = "Z201";}
                array_push($producto,$org);
                array_push($producto,$response['TProductoInfo']['item'][0]['Spart']);
                array_push($producto,$cant);
            }else{
                array_push($producto,$matnr);
                array_push($producto,"0");
                array_push($producto,"0");
                array_push($producto,"0");
            }
                        
            return $producto;
        }
    }
}