<?php
class PCM_mirror
{
    var $credentials;
    var $link;
    var $qa = "http://10.31.111.2:29992";
    var $dev = "";
    var $prod = "http://10.31.111.4:29992";
    var $ambiente = "";
    var $listaPrecios = "";
    var $catalogo="online";
    public $strLang = "es";

    public function __construct()
    {
        $this->ambiente = $this->prod; //este es el valor que va a cambiar para el ambiente
        $this->link = mysql_connect("localhost", "root", "hx_pruebas") or die("Error: No es posible establecer la conexión");
        $this->credentials = "Inf_admin:H3LVEX123#"; //credenciales producción
	//    $this->credentials = "Inf_01:hugoroldan"; //credenciales QA
        mysql_select_db("PCM_mirror", $this->link) or die("Error: No se encuentra la base de datos");
        $this->getListPrice();
    }
    
    
    public function getListPrice()
    {
        $query = "select idListaPrecios from ListasPrecios where activa=1;";
        $resultQuery = mysql_query($query) or die("error " . mysql_error());
        $lp                 = mysql_fetch_array($resultQuery);
        $this->listaPrecios = $lp['idListaPrecios'];
    }
    
    public function doRequest($url, $lang)
    {

    	$headers = array('Accept: application/json','Content-Type: application/json');

		if (isset($lang) && $lang=='es')
        {
            $headers = array('Accept: application/json', 'Content-Type: application/json', 'Accept-Language: es');
        }



        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $this->credentials);
        curl_setopt($curl, CURLOPT_URL, $url);
        $response = curl_exec($curl);      

        curl_close($curl);
        $response = json_decode($response, true);
        return $response;
    }
    
    public function getCatalogs()
    {
        $url  = $this->ambiente . "/ws410/rest/catalogs/";
        $data = $this->doRequest($url);
        for ($a = 0; $a < sizeof($data['catalog']); $a++) {
            echo $data['catalog'][$a]['@id'] . "<br />";
            echo $data['catalog'][$a]['@pk'] . "<br />";
            echo $data['catalog'][$a]['@uri'] . "<br />";
            $id    = $data['catalog'][$a]['@id'];
            $pk    = $data['catalog'][$a]['@pk'];
            $uri   = $data['catalog'][$a]['@uri'];
            $query = "INSERT INTO CatCatalogos(nombreCatalogo, pk, url) VALUES ('$id','$pk','$uri');";
            echo '<br /><hr>';
            mysql_query($query) or die("error " . mysql_error());
        }
    }
    
    public function getCategories($idcatalog, $catalogName, $url)
    {
        $url      = $this->ambiente . "/ws410/rest/catalogs/" . $catalogName;
        $data     = $this->doRequest($url);
        $data     = json_decode($data, true);
        $saveData = array();
        $itera    = 0;
        $largo    = 0;
        $query    = "insert into CatCategorias (idCatalogo, nombreCategoria, pk, url) values ";
        if ($catalogName == "Helvex Peru") {
            $saveData[0][0] = ""; //type
            $saveData[0][1] = "Catalogo Staged";
            $saveData[0][2] = "8796093514329";
            $saveData[0][3] = $this->ambiente."/ws410/rest/catalogs/Helvex Peru/catalogversions/Catalogo Staged";
            $saveData[1][0] = ""; //type
            $saveData[1][1] = "Catalogo Online";
            $saveData[1][2] = "8796093547097";
            $saveData[1][3] = $this->ambiente."/ws410/rest/catalogs/Helvex Peru/catalogversions/Catalogo Online";
        } else {
            foreach ($data['catalogVersions']['catalogVersion'] as $datos => $valores) {
                if (isset($valores['@version'])) {
                    $saveData[$itera][0] = ""; //type
                    $saveData[$itera][1] = $valores['@version'];
                    $saveData[$itera][2] = $valores['@pk'];
                    $saveData[$itera][3] = $valores['@uri'];
                    echo $valores['@version'] . "  " . $valores['@pk'] . " " . $valores['@uri'] . "<br />";
                } else {
                    
                    // print_r($valores);
                    
                    if ($catalogName == "importcockpit") {
                        $saveData[0][$itera + 1] = $valores;
                    } else {
                        $saveData[0][$itera] = $valores;
                    }
                    
                    echo $valores . "<br />";
                    $largo = 1;
                }
                
                $itera++;
            }
        }
        
        if ($largo == 0) {
            $largo = sizeof($saveData);
        }
        
        for ($deploy = 0; $deploy < $largo; $deploy++) {
            $query .= "('" . $idcatalog . "','" . $saveData[$deploy][1] . "','" . $saveData[$deploy][2] . "','" . $saveData[$deploy][3] . "'),";
        }
        
        $query = substr($query, 0, -1);
        //echo $query;
        mysql_query($query) or die("error " . mysql_error());
    }
    
    public function agregarCategorias()
    {
        $query = "select * from CatCatalogos;";
        $resultQuery = mysql_query($query) or die("error " . mysql_error());
        while ($categorias = mysql_fetch_row($resultQuery)) {
            echo "$categorias[1] <br /><br />";
            $PCM->getCategories($categorias[0], $categorias[1], $categorias[3]);
            echo "<br /><hr><br />";
        }
    }
    
 public function getAllProducts()
    {   
        echo "Respaldar tabla productos\n";
        $backup = "create table IF NOT EXISTS Productos_backup like Productos;";
        mysql_query($backup) or die("error " . mysql_error());
        $backup2 = "insert into Productos_backup select * from Productos;";
        mysql_query($backup2) or die("error " . mysql_error());
        echo "Vaciar tabla productos\n";
        $truncate = "Truncate Productos;";
        mysql_query($truncate) or die("error " . mysql_error());
        $url = $this->ambiente . "/ws410/rest/products";
        echo "Consultando rest/products en $url\n";
        $data = $this->doRequest($url,"") or die("Error al ejecutar cURL de productos");
        echo "Leyendo datos del WS y guardando en bd\n";
        for ($a = 0; $a < sizeof($data['product']); $a++) {
            $subcadena = substr($data['product'][$a]['@uri'], 45) . "<br />";
            $base      = explode("/", $subcadena);
            $catalogo  = $base[0];
            $categoria = $base[2];
            $par       = $catalogo . " - " . $categoria;
            //echo $par . "<br />";
            if ($par == "helvexProductCatalog - Online") {
                $codigo = utf8_decode($data['product'][$a]['@code']);
                $pk     = $data['product'][$a]['@pk'];
                $uri    = utf8_encode($data['product'][$a]['@uri']);
                $uri=str_replace(" ", "%20", $uri);
                $query  = "INSERT INTO  Productos ( codigo ,  pk ,  catalogo ,  categoria ,  uri ) VALUES ('$codigo','$pk',15,30,'$uri');";
                //echo $query . '<br /><hr>';
                echo "Guardando Producto $codigo\n";
                mysql_query($query) or die("error " . mysql_error());
            }
        }
        echo "Se guardó el contenido del WS product en la tabla Productos exitosamete\n";
        $drop = "drop table Productos_backup;";
        mysql_query($drop) or die("error " . mysql_error());
        echo "Se borró el backup Productos exitosamete\n";
    }
    
    public function getProductDetail()
    {
    	$contadorProducto=0;
        echo "Respaldar tabla Productos Detalle\n";
        $backup = "create table IF NOT EXISTS ProductosDetalle_backup like ProductosDetalle;";
        mysql_query($backup) or die("error " . mysql_error());
        $backup2 = "insert into ProductosDetalle_backup select * from ProductosDetalle;";
        mysql_query($backup2) or die("error " . mysql_error());
        echo "Vaciando tabla ProductoDetalle\n";
        $truncate = "Truncate ProductosDetalle;";
        mysql_query($truncate) or die("error " . mysql_error());
        echo "Consultando tabla productos\n";
        //$query = "select * from Productos where codigo ='000000000000000024'";
        $query = "select distinct codigo, pk, catalogo, categoria, uri from Productos";
        $resultQuery = mysql_query($query) or die("error " . mysql_error());
        while ($productos = mysql_fetch_assoc($resultQuery)) 
        {
            $url = str_replace(" ", "%20", $productos['uri']);
            //echo $url . "<br /><br />";
            echo "Consultando web services para el producto ".$productos['codigo']."\n";
            @$data = $this->doRequest($url);

            if (!is_array($data)) {
                continue;
            }
            
			echo "Consultando web services en español para el producto $productos[1]\n";
			//obtener datos en español
            $datosEs = $this->doRequest($url,'es');
	
	   if (array_key_exists('unit', $datosEs)) {
                echo "	Consultando web services para obtener unidad de venta\n";
                @$arrRs             = $this->doRequest($datosEs['unit']['@uri'],'es');

		$data['unit_type'] = $arrRs['name'];               
            }

            //Obtener precio
            if (isset($data['europe1Prices']['priceRow'])) {
                echo "	Consultando precios para el producto $productos[1]\n";
             $itera=0;
                foreach ($data['europe1Prices']['priceRow'] as $arrTmp) {
                  $arrRs  = @$this->doRequest($arrTmp['@uri']);
                  //echo "		".$arrRs['userMatchQualifier']."==".$this->listaPrecios;
                  if (isset($arrRs['userMatchQualifier']) and $arrRs['userMatchQualifier']==$this->listaPrecios)
                  {
                  	//echo "		Precio hallado en iteración $itera\n";
                  	$data['precio'] = $arrRs['price'];
                  	$data['moneda']    = $arrRs['currency']['@isocode'];
                  	//echo "			Precio ".$data['precio']."\n";
                  }
                  else
                  	{$itera++;}
                }
                
                
                
                // $data['language_price'] = $this->getPriceByLang($data['ext_prices']);
                 
            }
            
            // Get media
            
            $arrValExt = array(
                'pdf',
                'jpg',
                'jpge'
            );
            if (isset($data['galleryImages']['mediaContainer']['@uri'])) {
                echo "	Consultando web service galería de imágenes\n";
                @$arrRs = $this->doRequest($data['galleryImages']['mediaContainer']['@uri']);
                
                // Has data
                $galeria = "";
                if (@array_key_exists('media', $arrRs['medias'])) {
                    echo "	Rellenando arreglo de imágenes\n";
                    foreach ($arrRs['medias']['media'] as $arrTmp1) {
                        $arrExt = explode('.', $arrTmp1['@code']);
                        $intIdx = count($arrExt) - 1;
                        $strExt = $arrExt[$intIdx];
                        if (!in_array($strExt, $arrValExt))
                            continue;
                        $arrTmp1['@viewURL'] = str_replace('&attachment=true', '&attachment=false', $arrTmp1['@downloadURL']);
                        $arrTmp1['@viewURL']=$this->ambiente.$arrTmp1['@viewURL'];
                        $galeria .= $arrTmp1['@viewURL'] . "|";
                        if ($strExt == 'pdf') {
                            $arrTmp1['@cType']          = $this->getPdfType($arrTmp1['@code']);
                            $data['ext_media']['pdf'][] = $arrTmp1;
                        } else
                            $data['ext_media']['jpg'][] = $arrTmp1;
                    }
                    $data['galeria'] = substr($galeria, 0, -1);
                }
                //unset($data['galleryImages']);
            }
            
            //Get Keywords

            $keywordsStringEs = "";
            $keywordsStringEn = "";
            if (isset($datosEs['keywords'])) {
                echo "	Consultando keywords\n";
                foreach ($datosEs['keywords'] as $kw) {

                	$uriKey=$kw['@uri'];
            
                	$arraykey=explode(';',$uriKey);
                	//print_r($arraykey);
                	$langKey=$arraykey[2];
                	//echo "\nIdioma: ".$langKey."\n";
                	if ($langKey=='es')
                	{
                		$keywordsStringEs .= $kw['@keyword'] . "|";
                	}
                	else if ($langKey=='en')
                	{
                		$keywordsStringEn .= $kw['@keyword'] . "|";
                	}
                    
                }
                $data['palabrasClaveEs'] = substr($keywordsStringEs, 0, -1);
                $data['palabrasClaveEn'] = substr($keywordsStringEn, 0, -1);
            }

            
            
            //Get supercategories
            



            
            if (isset($data['supercategories']['category'])) {
                echo "	Obteniendo supercategorias \n";
                $code      = "";
                $jerarquia = "";
                
               
                foreach ($data['supercategories']['category'] as $arrTmp1) {
                    echo "  Consultando web service para obtener supercategorias\n";
                    //@$arrRs = $this->doRequest($arrTmp1['@uri']);
                    //echo $arrTmp1['@code']."\n";

                    if (strlen($arrTmp1['@code'])==18)
                    {
                        $data['cadenacategoria']=$arrTmp1['@code'];
                    }

                    $laSubCategoria=$arrTmp1['@code'];
                    if ($laSubCategoria[0]=='T')
                    {
                        //echo "Es la version del catalogo\n";
                        $contador  = 2;
                    }
                    else if ($laSubCategoria[0]=='A')
                    {
                        //echo "Es la linea\n";
                        $contador  = 1;
                        $data['linea']=$laSubCategoria;
                    }
                    else if ($laSubCategoria[0]=='M')
                    {
                        //echo "Es la marca\n";
                        $contador  = 3;
                        $data['marca']=$laSubCategoria;
                    }
                    else if ($laSubCategoria[0]=='E')
                    {
                        //echo "Es el acabado\n";
                        $contador  = 0;
                        $data['acabado']=$laSubCategoria;
                    }
                    else if ($laSubCategoria[0]=='S')
                    {
                        //echo "Es el sector\n";
                        $contador  = 4;
                    }



                     if ($contador == 2) {
                    //     echo "		Consultando web services secundario obtener datos de supercategoría\n";
                    //     @$arrTmp2 = $this->doRequest($arrRs['@uri']);
                        
                        
                    //     foreach ($arrTmp2['allSupercategories']['category'] as $categoria) {
                    //         //echo $categoria['@code']."<br>";
                            
                            $jerarquia = $arrTmp1['@code'];
                    //     }
                         $code .= $jerarquia . '-';
                     } else  {
                        $precode = $arrTmp1['@code'];
                        $codeax  = $this->LookingForCatalog($contador, $precode);
                        $code .= utf8_encode($codeax) . '['.$precode.']-';
                    }
                    
                    
                    
                    //$code.= $code."-";
                    
                    $contador++;
                }
                $codeax  = $this->LookingForCatalog(4, $data['sector']);
                       $code.= $codeax."[".$data['sector']."]";
                $data['supercategoria'] = $code;
                //die($data['supercategoria']);
            }
           

		



            // Get others
            $otros  = array();
            //$cadenaOtros = "";
            $indice = 0;
            if (isset($data['others']['media'])) {
                echo "	Rellenando arreglo de otros (imagenes)\n";
                foreach ($data['others']['media'] as $arrTmp) {
                    $data['otros'][$indice] = str_replace('=true', '=false', $arrTmp['@downloadURL']);
                    $data['otros'][$indice]=$this->ambiente.$data['otros'][$indice];
                    //$cadenaOtros.=$data['otros'][$indice]."|";
                }
                //$cadenaOtros=substr($cadenaOtros, 0,-1);
            }
            
            // Get unit
            
            
            
            if (isset($data['normal']['media']['@downloadURL'])) {
                echo "	Rellenando variable normal (imagenes)\n";
                $data['ext_img'] = str_replace('=true', '=false', $data['normal']['media']['@downloadURL']);
                $data['ext_img'] = $this->ambiente.$data['ext_img'];
            }
            
            
            
            if (isset($data['detail']['media']['@downloadURL'])) {
                echo "	Rellenando variable detalle (imagenes)\n";
                $data['ext_detail'] = str_replace('=true', '=false', $data['detail']['media']['@downloadURL']);
                $data['ext_detail'] = $this->ambiente.$data['ext_detail'];
                
            }
            
            
            if (isset($data['picture']['@downloadURL'])) {
                echo "	Rellenando variable picture (imagenes)\n";
                $data['imagen'] = str_replace('=true', '=false', $data['picture']['@downloadURL']);
                $data['imagen'] = $this->ambiente.$data['imagen'];
            }

                       
            if (isset($data['thumbnail']['media']['@downloadURL'])) {
                echo "	Rellenando variable de image miniatura (imagenes)\n";
                $data['miniatura'] = str_replace('=true', '=false', $data['thumbnail']['media']['@downloadURL']);
                $data['miniatura'] = $this->ambiente.$data['miniatura'];
            }
            
            
            
            if (isset($data['thumbnails']['media'])) {
                echo "	Rellenando arreglo thumbnails (imagenes)\n";
                $thumbnails = "";
                foreach ($data['thumbnails']['media'] as $th) {
                    
                    $thumbnails .= $this->ambiente.$th . "|";
                }
                $thumbnails         = substr($thumbnails, 0, -1);
                $data['miniaturas'] = $thumbnails;

            }
            
            echo "	Validando status de vigencia\n";
            @$data['validityStatus'] = $this->ValidityStatus($data['validityStatus']);
            echo "	Validando tipo de producto\n";
            @$data['helvexProductType'] = $this->GetProductType($data['helvexProductType']);



	  if (isset($datosEs['dxf']['@downloadURL'])) {
                    echo "Rellenando dxf\n";
                        $datosEs['dxf']['@downloadURL'] = str_replace('&attachment=true', '&attachment=false', $datosEs['dxf']['@downloadURL']); 
		     }
            
 	  if (isset($datosEs['rfa']['@downloadURL'])) {
                    echo "Rellenando rfa\n";
                        $datosEs['rfa']['@downloadURL'] = str_replace('&attachment=true', '&attachment=false', $datosEs['rfa']['@downloadURL']); 
		     }

	  if (isset($datosEs['assemblyDrawing']['@downloadURL'])) {
                    echo "Rellenando hoja despiece\n";
                        $datosEs['assemblyDrawing']['@downloadURL'] = str_replace('&attachment=true', '&attachment=false', $datosEs['assemblyDrawing']['@downloadURL']); 
}


	  if (isset($datosEs['installationGuide']['@downloadURL'])) {
                    echo "Rellenando hoja instalacion\n";
                        $datosEs['installationGuide']['@downloadURL'] = str_replace('&attachment=true', '&attachment=false', $datosEs['installationGuide']['@downloadURL']); 

		     }

if (isset($datosEs['productSpecs']['@downloadURL'])) {
                    echo "Rellenando hoja especificacion\n";
                        $datosEs['productSpecs']['@downloadURL'] = str_replace('&attachment=true', '&attachment=false', $datosEs['productSpecs']['@downloadURL']); 

		     }


if (isset($datosEs['productWarranty']['@downloadURL'])) {
                    echo "Rellenando garantia de producto\n";
                        $datosEs['productWarranty']['@downloadURL'] = str_replace('&attachment=true', '&attachment=false', $datosEs['productWarranty']['@downloadURL']); 

		     }


if (isset($datosEs['productCertificate']['@downloadURL'])) {
                    echo "Rellenando certificado de producto\n";
                        $datosEs['productCertificate']['@downloadURL'] = str_replace('&attachment=true', '&attachment=false', $datosEs['productCertificate']['@downloadURL']); 

		     }

if (isset($datosEs['f2ddwg']['@downloadURL'])) {
                    echo "Rellenando f2ddwg\n";
                        $datosEs['f2ddwg']['@downloadURL'] = str_replace('&attachment=true', '&attachment=false', $datosEs['f2ddwg']['@downloadURL']); 

		     }


if (isset($datosEs['f3ddwg']['@downloadURL'])) {
                    echo "Rellenando f3ddwg\n";
                        $datosEs['f3ddwg']['@downloadURL'] = str_replace('&attachment=true', '&attachment=false', $datosEs['f3ddwg']['@downloadURL']); 

		     }

            $this->saveProductDetail($data, $datosEs);
            $contadorProducto++;
            echo "\n****************************  $contadorProducto productos guardados  ******************************************************\n\n";
            
        }
        echo "Se guardaron todos los productos en el detalle de productos\n";
        $drop = "drop table ProductosDetalle_backup;";
        mysql_query($drop) or die("error " . mysql_error());
        echo "Se borró el backup ProductosDetalle exitosamete\nFin del script\n";
        
    }
    
    private $arrCurrencyLangEquiv = array('es' => 'MXN', 'en' => 'USD');
    
    
    
    private function saveProductDetail($data,$datosEs)
    {
        
    	

        echo "	Guardar producto....\n";
        $queryIdProducto = "SELECT idProducto, categoria, catalogo FROM Productos WHERE codigo= '" . $data['@code'] . "';";
        @$Producto = mysql_query($queryIdProducto) or die(mysql_error());
        @$fila        = mysql_fetch_array($Producto);
        @$idProducto  = $fila['idProducto'];
        @$idCategoria = $fila['categoria'];
        @$idCatalogo  = $fila['catalogo'];
        
        
       
        @$data['name'] = utf8_decode($data['name']);
        @$data['salesArea'] = utf8_decode($data['salesArea']);
	    @$data['name'] = str_replace("'", "\'", $data['name']);
        @$data['description'] = str_replace("'", "\'", $data['description']);
        @$datosEs['description'] = str_replace("'", "\'", $datosEs['description']);
        @$datosEs['name'] = str_replace("'", "\'", $datosEs['name']);


	

        if (isset($datosEs['installationGuide']['@downloadURL']))
        	$datosEs['installationGuide']['@downloadURL']=$this->ambiente.$datosEs['installationGuide']['@downloadURL'];

               
        if (isset($datosEs['f2ddwg']['@downloadURL']))
        	$datosEs['f2ddwg']['@downloadURL']=$this->ambiente.$datosEs['f2ddwg']['@downloadURL'];

        if (isset($datosEs['f3ddwg']['@downloadURL']))
        	$datosEs['f3ddwg']['@downloadURL']=$this->ambiente.$datosEs['f3ddwg']['@downloadURL'];


	if (isset($datosEs['dxf']['@downloadURL']))
        	$datosEs['dxf']['@downloadURL']=$this->ambiente.$datosEs['dxf']['@downloadURL'];

               
        if (isset($datosEs['productWarranty']['@downloadURL']))
        	$datosEs['productWarranty']['@downloadURL']=$this->ambiente.$datosEs['productWarranty']['@downloadURL'];

        if (isset($datosEs['productCertificate']['@downloadURL']))
        	$datosEs['productCertificate']['@downloadURL']=$this->ambiente.$datosEs['productCertificate']['@downloadURL'];

	if (isset($datosEs['productSpecs']['@downloadURL']))
        	$datosEs['productSpecs']['@downloadURL']=$this->ambiente.$datosEs['productSpecs']['@downloadURL'];

               
        if (isset($datosEs['rfa']['@downloadURL']))
        	$datosEs['rfa']['@downloadURL']=$this->ambiente.$datosEs['rfa']['@downloadURL'];

        if (isset($datosEs['assemblyDrawing']['@downloadURL']))
        	$datosEs['assemblyDrawing']['@downloadURL']=$this->ambiente.$datosEs['assemblyDrawing']['@downloadURL'];




        $data['sector']=$this->LookingForCatalog(4, $data['sector']);

        $query  = "select nombreCatalogo from CatCatalogos where idCatalogo='$idCatalogo'";
            //echo "\n".$query."\n";
            $result = mysql_query($query);
            while ($row = mysql_fetch_array($result)) {
                $catalogo = $row["nombreCatalogo"];
            }

        $query  = "select nombreCategoria from CatCategorias where idCategoria='$idCategoria'";
            //echo "\n".$query."\n";
            $result = mysql_query($query);
            while ($row = mysql_fetch_array($result)) {
                $categoria = $row["nombreCategoria"];
            }

        $data['@code'] = ltrim($data['@code'], "0");

        $campos       = "idProducto, modelo, nombreProductoEs, nombreProductoEn, estatusVigencia, tipoProducto, sector, CatalogoVersion, statusAprobacion, areaVenta, EAN, descripcionEn, descripcionEs, detalles, logotipos, normales, imagen, imagenes, imagenesGaleria, otros_30, otros_65, otros_515, miniatura, miniaturas, imagenesMKT, especificaciones, atributos, comentarios, Categoria, referenciaProductos, guiaInstalacion, archivoRFA, hojaDespiece, hojaEspecificacion, 2Ddwg, 3Ddwg, fechaCreacion, fechaModificacion, statusAprobacionMKT, statusAprobacionCostos, statusAprobacionIngenieria, piApprovalStatus, supercategorias, precio, moneda, observacionesEn, observacionesEs, resumenEn, 	resumenEs, 	palabrasClaveEn,	palabrasClaveEs, idListaPrecios, descripcion1, descripcion2, descripcion3, onLineDesde, onLineHasta, Fabricante, codigoOtrosFabricantes, unidadContenido,unidadVenta, archivoDXF,garantiaProducto,certificadoProducto,stringSupercategoria,linea,marca,acabado";
        @$valores      = "$idProducto, '" . $data['@code'] . "', '" . $data['name'] . "', '" . $datosEs['name'] . "', '" . $data['validityStatus'] . "', '" . $data['helvexProductType'] . "', '" . $data['sector'] . "', '$catalogo', '" . $data['approvalStatus'] . "', '" . $data['salesArea'] . "', '" . $data['ean'] . "', '" . $data['description'] . "', '" . $datosEs['description'] . "', '" . $data['ext_detail'] . "', '" . $data['logo'] . "', '" . $data['ext_img'] . "', '" . $data['imagen'] . "', '" . $data['galeria'] . "', '" . $data['galeria'] . "', '" . $data['otros'][0] . "', '" . $data['otros'][1] . "', '" . $data['otros'][2] . "', '" . $data['miniatura'] . "', '" . $data['miniaturas'] . "', '" . $data['marketingImages'] . "', '" . $data['features'] . "', '" . $data['valueAttibutes'] . "', '" . $data['comments'] . "', '$categoria', '" . $data['productReferences'] . "', '" . $dataEs['installationGuide']['@downloadURL'] . "', '" . $datosEs['rfa']['@downloadURL'] . "', '" . $datosEs['assemblyDrawing']['@downloadURL'] . "', '" . $datosEs['productSpecs']['@downloadURL'] . "', '" . $datosEs['f2ddwg']['@downloadURL'] . "', '" . $datosEs['f3ddwg']['@downloadURL'] . "', '" . $data['creationtime'] . "', '" . $data['modifiedtime'] . "', '" . $data['marketingApprovalStatus'] . "', '" . $data['cstApprovalStatus'] . "', '" . $data['npeApprovalStatus'] . "', '" . $data['piApprovalStatus'] . "', '" . $data['supercategoria'] . "', '" . $data['precio'] . "', '" . $data['moneda'] . "', '" . $data['remarks'] . "', '" . $datosEs['remarks'] . "', '" . $data['summary'] . "', '" . $datosEs['summary'] . "' , '" . $data['palabrasClaveEn'] . "', '" . $data['palabrasClaveEs'] . "', '" . $this->listaPrecios . "', '" . $data['description1'] . "' , '" . $data['description2'] . "' , '" . $data['description3'] . "' , '" . $data['onlineDate'] . "' , '" . $data['offlineDate'] . "', '" . $data['manufacturerName'] . "', '" . $data['otherManufacturerId'] . "', '" . $data['contentUnit']['@code'] . "', '" . $data['unit_type']. "', '" . $datosEs['dxf']['@downloadURL']. "', '" . $datosEs['productWarranty']['@downloadURL']. "', '" . $datosEs['productCertificate']['@downloadURL']. "' , '" . $data['cadenacategoria']. "' , '" . $data['linea']. "' , '" . $data['marca']. "' , '" . $data['acabado']. "' ";
        $query        = "insert into ProductosDetalle ($campos) values ($valores)";
        //echo $query;
        echo "	Insertando producto " . $data['@code'] . " en tabla ProductoDetalle\n";
        mysql_query($query) or die(mysql_error());
        
        echo "\nSe guardó el producto en el detalle de producto\n";
    }
    
    
    //Looking For Validity Status
    private function ValidityStatus($status)
    {
        
        $query  = "select es from ValidityStatus where code='$status'";
        //echo $query."<br>";
        $result = mysql_query($query);
        $row    = mysql_fetch_array($result);
        //die($row['es']);
        return $row['es'];
    }
    
    
    private function GetProductType($ProductType)
    {
        
        $query = "select es from ProductType where code='$ProductType'";
        //echo $query."<br>";
        $result = mysql_query($query) or die(mysql_error());
        $row = mysql_fetch_array($result);
        //die($row['es']);
        return $row['es'];
    }
    
    
    //Looking For catalog Group
    private function LookingForCatalog($table, $info)
    {
        $campo="es";
        if ($table!=4)
        {
			$info    = substr($info, 1);
    		$campo="descripcion";
        }
        

        $retorno = "";
        $tabla   = "";
        switch ($table) {
            case 0:
                $tabla = "CatGpoArtExt";
            break;
            case 1:
                $tabla = "CatGpoArt";
                break;
            
            case 2:
                $tabla = "";
                break;
            
            case 3:
                $tabla = "CatGpoMat";
                break;
            case 4:
                $tabla = "CatSectores";
                break;
        }
        
        if ($tabla != "") {
            
            $query  = "select $campo as descripcion from $tabla where id='$info'";
            //echo "\n".$query."\n";
            $result = mysql_query($query);
            while ($row = mysql_fetch_array($result)) {
                $retorno = $row["descripcion"];
            }
            
        }
        
        return $retorno;
    }
    
    
    function __destruct()
    {
        mysql_close($this->link);
    }
}

$PCM = new PCM_mirror();

$PCM->getAllProducts();

$PCM->getProductDetail();

//system ("php /var/www/html/catalogointeractivo/cargarMateriales.php");
?>


