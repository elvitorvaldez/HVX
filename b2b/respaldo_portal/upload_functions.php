<?php
function generaArray($organizacion,$sector)
{
	$arrayzote= array();
	for ($i = 1; $i <= count($organizacion); $i++)
	{
		for ($j = 1; $j <= count($sector); $j++)
		{
			//$arrayzote[$organizacion[$i-1]][$sector[$j-1]]=array('vacio');
			$arrayzote[$organizacion[$i-1]][$sector[$j-1]]=null;
		}
	}
	return $arrayzote;
}

function llenaArray($estructura,$renglon)
{
	$org=$renglon[0];
	$sec=$renglon[1];
	$mod=$renglon[2];//modelo
	//modelo 2
	//mi_modelo 15
	//descipcion 3
	//cantidad 4
	//unidad 5
	//precio 6
	//importe 7
	//desc 8
	//total 9
	//borrar -
	//total2 10
	//iva 11
	//descuento2 12
	//iva_precio 13
	//moneda 14								
	
	//if ($estructura[$org][$sec][0]=='vacio')
	if( !isset( $estructura[$org][$sec][$mod] ) )
	{
		//$estructura[$org][$sec][0]=$renglon;
		$estructura[$org][$sec][$mod]=$renglon;
	}
	else
	{
		$estructura[$org][$sec][$mod][4]+=$renglon[4];
		$estructura[$org][$sec][$mod][7]+=$renglon[7];
		$estructura[$org][$sec][$mod][8]+=$renglon[8];
		$estructura[$org][$sec][$mod][9]+=$renglon[9];
		$estructura[$org][$sec][$mod][10]+=$renglon[10];
		$estructura[$org][$sec][$mod][13]+=$renglon[13];
	}
	return $estructura;
}

//Funcion que lee archivo de hoja de calculo, devuelve un arreglo con el contenido
//de modelo y cantidad encontrados
//function readSpreadSheet($excel_file_name_with_path)
function parseExcel($excel_file_name_with_path)
{
	$colname=array('modelo','cantidad');
	
	//Obtengo el tipo de archivo
	$inputFileType = PHPExcel_IOFactory::identify($excel_file_name_with_path);

	//createReaderForFile permite abrir xls o xlsx implicitamente (sin especificar de cual de ellos se trata pues :|)
	//este metodo solo es para identificar que tipo de archivo excell se va a leer, no lo carga
	//$xlsReader = PHPExcel_IOFactory::createReaderForFile( $excel_file_name_with_path );
	$xlsReader = PHPExcel_IOFactory::createReader( $inputFileType );

	//Solo nos interesan los datos
	$xlsReader->setReadDataOnly(true);

	if($xlsReader->canRead( $excel_file_name_with_path  ))
	{
		//Aqui ya se carga el archivo de excel en memoria
		$xls_contents = $xlsReader->load( $excel_file_name_with_path  );
		
		if(!$xls_contents)
		{
			return "";
		}
		else
		{
			$objWorksheet = $xls_contents->setActiveSheetIndex(0);
			$highestRow = $objWorksheet->getHighestRow();
			/*$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);*/
			for ($row = 1; $row <= $highestRow; ++$row)
			{
				for ($col = 0; $col < 2; ++$col)
				{
					$data = ($objWorksheet->getCellByColumnAndRow($col, $row)->getValue());
					//Los datos deben adaptarse al tipo de dato que se espera que tengan
					if(isset($data) && $data != '')
					{
						if( $colname[$col] == 'cantidad' ) 
							$valor = floatval($data);
						else
							$valor = $data;
					}
					else
					{
						if( $colname[$col] == 'cantidad' )
							$valor = floatval(0);
						else
							$valor = '0';
					}	
					$product[$row-1][$col] = $product[$row-1][$colname[$col]] = $valor;
				}
			}
			return $product;
		}	
		
	}
	else
	{
		return "";	
	}
}


//Funcion que valida los datos leidos del archivo Excel
function validateReadData($cliente, $products_read)
{
	global $estructura;
	$nwarnings = 0;
	$nerrors = 0;
	
	//Caracteres que seran removidos del modelo, para hacer consultas SQL
	$extras = array("-", "/", ".", " ");
	$curr_prod_id_full = "";
	
	$link=mysql_connect("localhost", "portal", "portal");
	if (!$link)
	{
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("SAP");

	//$cliente[0];
	//Se consulta la BD para obtener la org y sector a los que tiene acceso el cliente
	$sql0 ="SELECT vkorg,spart 
			FROM v_cta_org_sec 
			WHERE kunnr='".$cliente[0]."'";
	$result0 = mysql_query($sql0,$link);
	$var=array();
	if ($row0=mysql_fetch_array($result0))
	{
		do
		{
			$c=$row0['vkorg']."-".$row0['spart'];
			array_push($var, $c);
		}while ($row0=mysql_fetch_array($result0));
	
	}
	echo "	<script type='text/javascript'>
				function mostrar_ocultar_detalle()
				{
					var objDiv = document.getElementById('danalisis');
					var objTxt = document.getElementById('txt_link_analisis');
					var disp_st = objDiv.style.display;
					if(disp_st == 'none')
					{
						objDiv.style.display = 'block';
						objTxt.innerHTML='-Detalles del an&aacute;lisis (click para ocultar):';
					}
					else
					{
						objDiv.style.display = 'none';
						objTxt.innerHTML='+Detalles del an&aacute;lisis (click para mostrar)...';
					}
				}
			</script>
			<div style='text-align:left;' ><a href='javascript:mostrar_ocultar_detalle();'><font color='black'><span id='txt_link_analisis'>-Detalles del an&aacute;lisis (click para ocultar):</span></font></a></div>";
	echo "<div id='danalisis' style='width:90%;margin:auto auto;display:block;'>";
	
	//Validamos cada producto leido del archivo
	foreach ($products_read as $fila=>$dato)
	{
		$curr_prod_id_full = $dato['modelo'];
		$es_equiv = " ";
		//Se quitan caracteres separadores en el codigo del producto 
		$sinextras = str_replace($extras,"",$curr_prod_id_full);
		$sinespaciosMay=strtoupper(trim($sinextras));
		
		//se hace query en tabla productos_R3
		$sql = "SELECT matnr,vkorg,spart FROM Productos_R3 WHERE matnr_2='".$sinespaciosMay."'";
		//error_log($sql);
		$result = mysql_query($sql,$link);
		$num_rows = mysql_num_rows($result);
		
		//Si no se obtuvo nada, tal vez se trate de equivalencia
		if ($num_rows == FALSE || $num_rows == 0)
		{
			//Como la parte de equivalencias esta mal hecha
			// (el campo cuenta_grupo es ambiguo, deberia existir un campo para cuenta y otro para grupo ) hay que hacer consultas extras...
			//Obtener el o los grupos a los que pertenece el cliente en la tabla que viene de R3
			$cuenta_grupo_arr = array();
			$cuenta_grupo_filter = "cuenta_grupo = '".$cliente[0]."'";
			$sql_grp = "SELECT DISTINCT kdgrp 
						FROM Clientes_R3
						WHERE kunnr = '".$cliente[0]."'";
			$res_grp = mysql_query($sql_grp, $link);
			if( $res_grp )//Se logra obtener grupos para este numero de cliente
			{
				//Para cada grupo obtenido revisar la tabla de grupos asociada con la de equivalencias ...
				while( $curr_grp_row = mysql_fetch_array($res_grp) )
				{
					$sql_grupoGE = "SELECT grupo 
									FROM grupos_equivalencias 
									WHERE grupo = ".$curr_grp_row['kdgrp'];
					$res_grupoGR = mysql_query($sql_grupoGE, $link);
					if( $res_grupoGR )
					{
						while( $curr_grupoGE_row = mysql_fetch_array($res_grupoGR) )
						{
							array_push($cuenta_grupo_arr, $curr_grupoGE_row['grupo']);
						}
					}
				}
				//Para cada grupo obtenido de la ultima consulta, agregar un OR a la consulta SQL que buscara si el producto en cuestion es equivalencia
				//de cliente o de grupo de clientes
				$n_cuenta_grupo = count($cuenta_grupo_arr);
				for($icg = 0; $icg < $n_cuenta_grupo ; $icg++)
				{
					$cuenta_grupo_filter .= " OR cuenta_grupo = '".$cuenta_grupo_arr[$icg]."' ";
				}
			}

			$sqlequi = "SELECT * 
						FROM equivalencias 
						WHERE equivalencia='$curr_prod_id_full' AND
							  ($cuenta_grupo_filter)";

			$resultequi = mysql_query($sqlequi,$link);
			$num_rows_equi = mysql_num_rows($resultequi);

			//...tampoco se encuentra equivalencia, error
			if ($num_rows_equi=='' || $num_rows_equi==0)
			{
					if($dato['modelo'] != '0')
					{													
						echo "<div class='error'>El modelo: ".$dato['modelo']." no existe. Se elimina esta entrada.</div>";
						$nerrors++;
					}
					continue;
			}
			$es_equiv = $curr_prod_id_full;
			$row=mysql_fetch_array($resultequi);
			$curr_prod_id_full = $row['modelo'];
			$sinextras = str_replace($extras,"",$curr_prod_id_full);
			$sinespaciosMay=strtoupper(trim($sinextras));
			
		}

		//El modelo y/o equivalencia a mostrar en los mensajes, si se da el caso
		$model_to_display = $curr_prod_id_full;
		if($curr_prod_id_full != strtoupper(trim($dato['modelo'])))
			$model_to_display = $curr_prod_id_full." (".$dato['modelo'].")";

		//Verificamos que no vengan campos vacios (en este caso 0) o con decimales			
		if( $dato['cantidad'] == 0 || $dato['modelo'] == '0' )
		{
			echo "<div class='error'>Faltan datos: Modelo '".$dato['modelo']."' con cantidad '".$dato['cantidad']."'. Se elimina esta entrada.</div>";
			$nerrors++;
			continue;
		}
		
		//Evitar decimales, correcion automatica al numero mayor entero mas cercano
		if( fmod( $dato['cantidad'], 1 ) > 0 ) 
		{
			echo "<div class=\"warning\">La cantidad del modelo '$model_to_display' es incorrecta: '".$dato['cantidad']."'. ";
			$dato["cantidad"] = ceil($dato["cantidad"]);
			echo "Se ha cambiado la cantidad ordenada por '".$dato['cantidad']."', por favor rev&iacute;sela antes de enviar su pedido.</div>";
			$nwarnings++;
		}
		//TODO: Colocar esta sección en una etapa posterior a la recoleccion de todos los datos de la hoja de calculo
		//Buscar y validar multiplos definidos para este producto
		$sql2m="SELECT * 
				FROM productos_multiplos 
				WHERE productos='".$curr_prod_id_full."'";
		$result2m = mysql_query($sql2m,$link);
		if( $result2m && $row2m = mysql_fetch_array( $result2m ) )
		{ 
			do
			{
				$curr_multiplo = floatval($row2m["multiplo"]);
				$msg_class = "'info'";
				$msg_txt = "El Modelo: $model_to_display requiere m&uacute;ltiplos de $curr_multiplo unidades. Cantidad '".$dato["cantidad"]."'";
				$modulo = $dato["cantidad"] % $curr_multiplo;
				//Si es necesario se adecua la cantidad solicitada para estar acorde al multiplo definido
				if ($modulo != 0 || $dato["cantidad"] == 0)
				{
					$A = $curr_multiplo - $modulo;
					$dato["cantidad"] = $dato["cantidad"] + $A;
					$msg_txt .= " cambiada por '".$dato["cantidad"]."', por favor rev&iacute;sela antes de enviar su pedido.</div>";
					$msg_class = "'warning'";
					$nwarnings++;
				}
				else
				{
					$msg_txt .= " correcta.</div>";
				}
				echo ("<div class=$msg_class>".$msg_txt);
			}while ($row2m = mysql_fetch_array($result2m)); 
		}
		//FIN Buscar y validar multiplos definidos para este producto
		
		//Los datos que son validos o han sido adecuados para serlo ...
		//Se obtiene la org y sector del producto para validar que corresponda con los que tiene acceso el cliente
		$sql = "SELECT matnr,vkorg,spart 
				FROM Productos_R3 
				WHERE matnr_2='".$sinespaciosMay."'";
		$result = mysql_query($sql,$link);
		$row=mysql_fetch_array($result);
		$d=$row['vkorg']."-".$row['spart'];
		
		if(in_array($d,$var))
		{
			$V_KUNNR=$cliente[0]; //request del combo del cliente
			$V_KWMENG=$dato['cantidad']; //cantidad
			$V_MATNR=$row['matnr']; //producto
			$V_SPART=$row['spart']; //sector
			$V_VKORG=$row['vkorg']; //organizacion
			
			$V_DESCUENTO = $V_IMPORTE = $V_IMPORTE2 = $V_IVA = $V_MAKTX = $V_MATNR1 = $V_MEINS = $V_PRECNETO = $V_TOTPAGAR = $V_VALIVA = $V_VALNETO = $V_VALPDES_PT = $V_VALPDES_RF =$V_KONWA1= '';
			//TODO: Colocar esta sección en una etapa posterior a la recoleccion de todos los datos de la hoja de calculo
			//Se llama a la bapi de precios para obtener la info correspondiente a este producto
			$precios=ZHX_PRECIOS_MAT($V_KUNNR, $V_KWMENG, $V_MATNR, $V_SPART, $V_VKORG, $V_DESCUENTO, $V_IMPORTE, $V_IMPORTE2, $V_IVA, $V_MAKTX, $V_MATNR1, $V_MEINS, $V_PRECNETO, $V_TOTPAGAR, $V_VALIVA, $V_VALNETO, $V_VALPDES_PT, $V_VALPDES_RF,$V_KONWA1);
			$v_descuento=$precios["OUTPARAMS"][0][1];
			$v_importe=$precios["OUTPARAMS"][1][1];
			$v_importe2=$precios["OUTPARAMS"][2][1];
			$v_iva=$precios["OUTPARAMS"][3][1];
			$v_maktx=$precios["OUTPARAMS"][4][1];
			$v_matnr1=$precios["OUTPARAMS"][5][1];
			$v_meins=$precios["OUTPARAMS"][6][1];
			$v_precneto=$precios["OUTPARAMS"][7][1];
			$v_totpagar=$precios["OUTPARAMS"][8][1];
			$v_valiva=$precios["OUTPARAMS"][9][1];
			$v_valneto=$precios["OUTPARAMS"][10][1];
			$v_valpdes=$precios["OUTPARAMS"][11][1];
			$mon_ext=$precios["OUTPARAMS"][12][1];
			$im='./images/button_drop.png';
			//$concatena=$row['vkorg'].'|'.$row['spart'].'|'.$v_matnr1.'|'.$v_maktx.'|'.$V_KWMENG.'|'.$v_meins.'|'.$V_IMPORTE.'|'.$V_IMPORTE2.'|'.$v_descuento.'|'.$v_valneto.'|'.$v_totpagar.'|'.$v_valiva.'|'.$v_valpdes.'|'.$v_iva.'|'.$mon_ext.'|'.$es_equiv;
			//$res=explode('|',$concatena);
			//termina lectura de datos devueltos por bapi
			//$estructura=llenaArray($estructura,$res);
			
			$estructura = llenaArray( $estructura,array( 0=>	$row['vkorg'],
																											$row['spart'],
																											$v_matnr1,
																											$v_maktx,
																											$V_KWMENG,
																											$v_meins,
																											$V_IMPORTE,
																											$V_IMPORTE2,
																											$v_descuento,
																											$v_valneto,
																											$v_totpagar,
																											$v_valiva,
																											$v_valpdes,
																											$v_iva,
																											$mon_ext,
																											$es_equiv ) ); 
		}
		else
		{
			echo "<div class='error'>Usted no tiene acceso al producto '".$dato['modelo']."'. Se elimina esta entrada.</div>";
			$nerrors++;
		}
	}
	echo "</div>";
	if( $nwarnings > 0  || $nerrors > 0)
	{
		$txt_error = "$nerrors entrada".(($nerrors != 1)?"s":"")." eliminada".(($nerrors != 1)?"s":"");
		$txt_warn = "$nwarnings entrada".(($nwarnings != 1)?"s":"")." modificada".(($nwarnings != 1)?"s":"");
		echo ("<div class='info'><b>Se han realizado los siguientes cambios a su pedido original: $txt_error y $txt_warn. Revise los datos siguientes antes de enviar este pedido.</b></div>");
	}
	else
	{
		echo "<div class='info'><b>Su pedido est&aacute; listo para ser enviado, le recomendamos revisarlo antes de que lo env&iacute;e.</b></div>";
	}
}
//END Funcion que valida los datos leidos del archivo Excel

//Funcion que carga el archivo a la clase ExcelReader para su procesamiento
function loadFile($cliente)
{
	global $orgArr;
	global $secArr;
	global $estructura;
	global $datoscliente;
	global $error;
	$max_allowed_fsize = 204800; //200KB
	$link=mysql_connect("localhost", "portal", "portal");
	if (!$link)
	{
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("SAP");
	
	//Procesando el contenido leido del archivo excel
	$allowed_extensions = array("xls", "xlsx", "ods");
	$is_invalid_extension = 0;
	$fname_fext_arr = explode(".",strtolower($_FILES["file"]["name"]));
	$ext_elem = end($fname_fext_arr);
	if( !in_array( $ext_elem, $allowed_extensions ) )
		$is_invalid_extension  = 1;
	if( ( $is_invalid_extension == 0 &&	stristr($_FILES["file"]["type"], "application/" )) && 
		( $_FILES["file"]["size"] <= $max_allowed_fsize  ) )
	{
		if ($_FILES["file"]["error"] > 0)
		{
			echo "<div class='error'>El archivo no se recibi&oacute; exitosamente.<br>Codigo de error: " . $_FILES["file"]["error"].".</div>";
			$error = 1;
		}
		else
		{
			if (file_exists("upload/" . $_FILES["file"]["name"]))
			{
				echo "<div class='error'>".$_FILES["file"]["name"]." Ya existe el archivo.</div>";
				$error = 1;
			}
			else
			{
				$nombre_guardado=md5(time().$_FILES["file"]["name"]);
				$nombre_guardado.=".tmp";
				move_uploaded_file($_FILES["file"]["tmp_name"],	"./upload/" . $nombre_guardado);
				echo "	<div class=\"success\">
							Archivo ".$_FILES["file"]["name"]." recibido exitosamente. Enseguida se analizar&aacute;n los datos encontrados. 
						</div>";
			}
			
			echo "<div class=\"info\">
					<b>Si durante el an&aacute;lisis el sistema encuentra problemas con los datos de su pedido, intentar&aacute; corregirlos autom&aacute;ticamente; si usted no est&aacute; de acuerdo con los cambios propuestos, modifique el archivo '" . $_FILES["file"]["name"]."' e intente cargarlo nuevamente.</b>
				  </div>";
			
			$filename="./upload/".$nombre_guardado;
			//Se lee el archivo ...
			$prod=parseExcel($filename);
			if( isset( $prod ) && is_array( $prod ) )
			{
				validateReadData($cliente, $prod);
				
				//divs para mostrar el numero de pedido resultante de SAP
				for ($d=0;$d<8;$d++)
				{
					echo "<div id='$d' name='$d' align='center' style='font-size:14px; visibility:hidden'>"; 
					echo "</div>";
				}
				
				$num_ids=0;
				foreach ($orgArr as $key=>$organizacion)
				{
					foreach ($secArr as $key1=>$sector)
					{
						//if ($estructura[$organizacion][$sector][0]!='vacio')
						if( is_array( $estructura[$organizacion][$sector] ) && count( $estructura[$organizacion][$sector] ) )
						{
							echo "<div style='text-align:left'>";
							$num_ids=$num_ids+1;
							switch ($organizacion)
							{
								case 3101:	$organizacion_txt="Helvex Nacional";
											break;
								case 2201:	$organizacion_txt="Prisa Nacional";
											break;
							}
				
							switch ($sector)
							{
								case 01:	$sector_txt="Llaves y Accesorios";
											break;
								case 02:	$sector_txt="Refacciones";
											break;
								case 03:	$sector_txt="Muebles Cer&aacute;micos";
											break;
								case 04:	$sector_txt="Altmans";
											break;
							}
							
							$datoscliente[$organizacion.$sector]=$cliente[0].' '.$cliente[1]. '|' .$organizacion_txt. '|' .$sector_txt. '|' .$organizacion. '|' .$sector;
				
							$sql="Select stras_we , location_we, city1_we, region_we, post_code1_we,land1_we  from direcciones_alternas_R3 where kunnr='$cliente[0]' AND vkorg=$organizacion AND spart=$sector AND defpa='X'";
							$res = mysql_query ($sql,$link);
							if ($row = mysql_fetch_array($res))
							{ 
								do
								{
									echo "	<font style='font-size:12px'>
												<br>
													<strong>Direcci&oacute;n de Entrega:</strong>
												<br>".
												$row["stras_we"].
										"		<br>".
												$row["location_we"].
										"		<br>".
												$row["city1_we"].', '.$row["region_we"].' '.$row["land1_we"].', '.$row["post_code1_we"].
										"	</font>";
								}while ($row = mysql_fetch_array($res)); 
							}
				
							echo "	<br>
									<font size=2 color=gray>
										<strong>Organizaci&oacute;n: ".$organizacion_txt."</strong>
									</font>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<font size=2 color=gray>
										<strong>Divisi&oacute;n: ".$sector_txt."</strong>
									</font>
							
									<div id='transparency".$organizacion.$sector."' width='100%'>
										<div id='gridboxtxt_".$organizacion.$sector."' width='100%' >
										</div>
										<div id='gridbox_".$organizacion.$sector."' class='grid_container' width='100%'>
										</div>
									</div>
									<br><hr width='70%' height='1'><br>";
							echo "</div>";
						}
					}
				}
			}
			else
			{
				echo "<div class=\"error\">No se ha conseguido recuperar datos del archivo, verifique que tenga el formato correcto.</div>";
				$error = 1;
			}
		}//end subio
	}//end archivo valido
	else
	{
		$problem = ".";
		if( stristr($_FILES["file"]["type"], "application/" ) || $is_invalid_extension == 1 )
			$problem = ": no es una hoja de c&aacute;lculo de Excel.";
		else if( $_FILES["file"]["size"] > $max_allowed_fsize )
		 	$problem =": el tama&ntilde;o m&aacute;ximo permitido es de ".($max_allowed_fsize/1024)." KB.";
 
		echo "<div class='error'>Error en el archivo$problem</div>";
		$error = 1;
	}
}
//END Funcion que carga el archivo a la clase ExcelReader para su procesamiento
?>
