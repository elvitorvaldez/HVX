<?php
/*
obtener_datos_iniciales.php

A partir del número de Cliente (KUNNR) ej(1-01) llamamos al WS que nos devuelve las Organizaciones y Sectores a las que tiene acceso.
También nos devuelve las direcciones alternas.
*/
//Incluimos a nusoap.php
//require '../libs/nusoap/nusoap.php';
//require '../models/config.php';

function obtenerDatosIniciales($ikunnr){

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
                "IB2b" => "",
                "IKunnr" => $ikunnr,
                "IParvw" => "",
                "TClienteInfo" => array(
                    "item"=> array(
                        "Kunnr" => "",
                        "Vkorg" => "",
                        "Vtweg" => "",
                        "Spart" => "",
                        "Orgven" => "",
                        "Sector" => "",
                        "Canal" => "",
                        "Kunn2" => "",
                        "Parvw" => "",
                        "Name1" => "",
                        "Name2" => "",
                        "Stras" => "",
                        "TelNumber" => "",
                        "FaxNumber" => "",
                        "Stcd1" => "",
                        "PostCode1" => "",
                        "Land1" => "",
                        "City1" => "",
                        "Region" => "",
                        "Location" => "",
                        "Defpa" => "",
                    )
                )
            );
    
    //En la variable $response guardamos la respuesta del WS
    //La función call recibe el nombre de la función y los parámetros en un arreglo
    
    //var_dump($params);
    //die();
    $response = $client->call('ZSdmfClienteInfo',$params);

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
            if($response['OReturn'] == 0){
                $org_sec = array();
                $indice = 0;
                //print_r($response['OReturn']);
                //echo "<br>++++++++++++++++++++++++++++++++++++++++";
                foreach ($response['TClienteInfo']['item'] as $key => $value) {
                    //print_r($value);
                    //echo "<br>*************************************";
                    if($value['Vkorg'] !== "" && $value['Spart'] !== '07'){
                        switch ($value['Vkorg']){
                            case "3101":
                                $organizacion  = "Z101";
                                break;
                            case "2201":
                                $organizacion  = "Z201";
                                break;
                        }
                        $org_sec[$indice] = array("org" => $organizacion, "sec" => $value['Spart']);
                        $indice ++;
                    }
                    
                }
                
                //echo "<br><hr>";
                //print_r($org_sec);
                return $org_sec;
            }
        }
    }
}

