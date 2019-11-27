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
                "IB2b" => "X",
                "IKunnr" => $kunnr,
                "IParvw" => "WE",
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
            //var_dump($response);
            //die();
            $archivo_csv = fopen('sucursales.csv', 'w');
            if($archivo_csv)
            {
                fputs($archivo_csv, "id,ADDRESS_ID_SAP_HELVEX,Nombre,IdenSucursal,Calle,Numero,Interior,Colonia,Localidad,Municipio,Estado,CP,Pais,Activo,Facturar A,Enviar A".PHP_EOL);
                foreach($response['TClienteInfo']['item'] as $res){
                    //if($res["CustPrice"] != 0){
                        fputs($archivo_csv, " , ,".$res["Name1"].",".$res["Kunnr"].",".$res["Stras"].", , ,".$res["Location"].", ,".$res["City1"].",".$res["Region"].",".$res["PostCode1"].",".$res["Land1"].", , ,".PHP_EOL);
                    //}                    
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