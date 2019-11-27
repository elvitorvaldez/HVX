<?php
/*
verifica_montos_minimos.php

Utilizamos WS para verificar que el pedido cumpla con las políticas de montos mínimos.

Enviamos los siguientes datos: (Org, Sector)
EJ (3101,01)

Devuelve la política de montos mínimos

*/
//Incluimos a nusoap.php
require '../libs/nusoap/nusoap.php';
require '../models/config.php';

//function verificaMontosMinimos($ispart,$ivkorg){
$ispart = "03";
$ivkorg = "3101";
//Creamos al cliente con la función nusoap_client();
    //Le pasamos como parámetros el WSDL y como segundo le indicamos que es un 'wsdl'

    //Calidad
    $client = new nusoap_client("http://DQASR3.helvexmx.gpohx.local:9910/sap/bc/srt/wsdl/flv_10002A111AD1/bndg_url/sap/bc/srt/rfc/sap/zsdws_b2b_distrib/300/zsdws_b2b_distrib/binding?sap-client=300", 'WSDL');
    
    //Producción
    //$client = new nusoap_client("http://CIDBR3.helvexmx.gpohx.local:8020/sap/bc/srt/wsdl/flv_10002A111AD1/bndg_url/sap/bc/srt/rfc/sap/zsdgf_helvex_hd/500/zsdgf_helvex_hd/binding?sap-client=500", 'wsdl');

    //Pasamos las credenciales para la conexión: (User, Pass, 'basic')
    $client->setCredentials("HX-PWEB", "#Zaq123wsX!", 'basic');
//    $client->setCredentials(WS_USER, WS_PASS, 'basic');
    
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
                "ISpart" => $ispart,
                "IVkorg" => $ivkorg,
                "IVtweg" => "",
                "TMontosMin" => array(
                    "item"=> array(
                        "Mandt" => "",
                        "Vkorg" => "",
                        "Spart" => "",
                        "Zonat" => "",
                        "Minimo" => "",
                        "Unidad" => "",
                        "Owner" => "",
                        "AltaDat" => "",
                        "ModifUrs" => "",
                        "ModifDat" => "",
                    )
                )
            );
    
    //En la variable $response guardamos la respuesta del WS
    //La función call recibe el nombre de la función y los parámetros en un arreglo
    
    //var_dump($params);
    //die();
    $response = $client->call('ZSdmfMontosMin',$params);

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
            //print_r($response);
            if($response['OReturn'] == 0){
                $politicas = array();
                $indice = 0;
                foreach ($response['TMontosMin'][item] as $key => $value){
                  //  print_r($value);
                //    echo "<br><hr><br>";
                    if($value['Vkorg'] == $ivkorg && $value['Spart']  == $ispart){
                        $politicas[$indice] = array($value['Zonat'],$value['Minimo'],$value['Unidad']);
                        $indice ++;
                    }
                }
                print_r($politicas);
                return $politicas;
            }else{
                return false;
            }
		
            //return $response;
        }
    }
//}