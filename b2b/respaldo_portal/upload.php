<!DOCTYPE html  >
<?php
$_Autentificar='ok';
include('header_footer_portal.php');
if (!cuentaConServicio('datos cliente',$_SESSION['id_usuario']))
{
	Header("Location: sin_permiso.html");
}
require_once './Classes/PHPExcel/IOFactory.php';
require_once 'upload_functions.php';
//global $CONFIG;
include ('ZHX_PRECIOS_MAT.php'); //bapi que traera los precios por material y cantidad
//Bandera de error
$error = 0;
//Esta variable es fundamental, guardara un arreglo de todos los ids de pedidos que seran enviados a SAP
$_SESSION['orders_ids_from_xls'] = array();
//Obtengo los datos de la pagina anterior
$cuenta=$_REQUEST['CustNumber'];
$oc=$_REQUEST['oc'];
$licitacion=$_REQUEST['id_licitacion'];

//$cliente=explode("||",$cuenta);
$cliente = array(0=>$cuenta);
$sql_query = "SELECT DISTINCT name1,name2  FROM SAP.Clientes_R3 WHERE kunnr = '$cuenta'";
$res = mysql_query($sql_query);
if($res)
{
	$row = mysql_fetch_array($res);
	$cliente[1]= $row["name1"].' '.$row["name2"];
	$cuenta .= "||".$cliente[1];
}

//pasar desde una variable global:
$orgArr= array('3101','2201');
$secArr= array('01','02','03','04');

$estructura=generaArray($orgArr,$secArr);
$datoscliente = array();

?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<title>Helvex - Nuevo Pedido Archivo</title>

		<?php set_sess_expiration_alert(); ?>
		
		<link rel="STYLESHEET" type="text/css" href="./dhtmlx/dhtmlx.css">
		<link rel="stylesheet" type="text/css" href="styles/estilos.css">
		<link rel="stylesheet" type="text/css" href="styles/estilos_improved.css">
		
		<script src="dhtmlx/dhtmlx.js" type="text/javascript" ></script>		
		<script src="js/prototype.js" type="text/javascript"></script>
		<script language="javascript">
			var sct=0;
			var stt=0;
			var http_request = false;
			var policy_error_flag = false;
			
			var browser=navigator.appName;
			function CommaFormatted(amount)
			{
			        var delimiter = ","; // replace comma if desired
			        var a = amount.split('.',2)
			        var d = a[1];
			        var i = parseInt(a[0]);
			        if(isNaN(i)) { return ''; }
			        var minus = '';
			        if(i < 0) { minus = '-'; }
			        i = Math.abs(i);
			        var n = new String(i);
			        var a = [];
			        while(n.length > 3)
			        {
			                var nn = n.substr(n.length-3);
			                a.unshift(nn);
			                n = n.substr(0,n.length-3);
			        }
			        if(n.length > 0) { a.unshift(n); }
			        n = a.join(delimiter);
			        if(d.length < 1) { amount = n; }
			        else { amount = n + '.' + d; }
			        amount = minus + amount;
			        return amount;
			}
			
			var newwindow = '';
			function popitup3(url,ar1,ar2,ar3)
			{
				url=url+'?'+ar1+'&'+ar2+'&'+ar3;
			if (newwindow && !newwindow.closed && newwindow.location)
			{
			    newwindow.location.href = url;
			    newwindow.focus();
			}
			else
			{
				newwindow=window.open(url,'htmlname','width=1100,height=550,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,status=no');
				newwindow.moveTo(100,100);}
			}
		</script>
	</head>

	<body>
		<script language="JavaScript1.2" src="js/load_provide_support.js" type="text/javascript"></script>
		
		<div class="main_area" id="dmain">
			<div class="main_banner" id="page_banner">
				<img alt="" border="0" src="<?php echo( get_rand_image('Banners1024', 'jpg', 'Banner1024X60') );?>" width="100%" />
			</div>
			<div class="menu" id="dmenu">
			<?php
				if(isset($_SESSION['id_usuario']))
				{	
					get_header(__FILE__);				
				}
			?>				
			</div>
			
			<div class="welcome_div" id="dwelcome">
				<div class="welcome_image">
					<img alt="" border="0" src="images/llave.gif" width="17" height="13"  >
				</div>
				<div class="welcome_name">
				<?php
					get_greeting();
				?>
				</div>
				<div class="welcome_date">
				<?php
					get_date();
				?>
				</div>
			</div>
			
			<div class="data_container" id="dproductos" style="position:relative; z-index: 0;">
				<div class="folder_all" id="dfolder_head">
					<div style="width:100px" class="folder_title" id="dsection_title" >
						<img alt="" width="8" height="19" src="../../images/boton_izq.gif" >
						<span class="folder_title_text">Nuevo pedido</span>
					</div>
					<div class="folder_sides">
						<img alt="" width="7" height="22" src="../../images/boton_der.gif" >
					</div>
					<div class="sess_close">
						<a href="<?php echo $_SERVER['PHP_SELF']; ?>?logout=logout">
							Cerrar sesi&oacute;n
						</a>
					</div>
				</div>

				<div class="operation_name" id="doperation">
					<p><img alt="" src="../../images/right1.gif" >Datos del pedido en archivo de hoja de c&aacute;lculo</p>
				</div>

				<br><br>
				
				<div class="data_filter" id="dmodelo_filter">
					<?php
					if( !isset($cliente[0]) || !isset($cliente[1]) )
					{
						echo("<div class='error'>
									No ha especificado una cuenta. Por favor <a href='./nvopedido_archivo.html'>regrese a la p&aacute;gina anterior</a> e intente de nuevo.
								</div>");	
						$error = 1;						
					}
					else
					{
						echo( "{$cliente[0]} {$cliente[1]}<br>Orden de Compra: $oc<br>Proyecto Especificacion: $licitacion<br>" );
					}
					?>
				</div>
				
				<br>
				
				<div class="dhtmlx_container" id="prod_listing" style="width:90%">
					
					<div style='font-size:120%; text-align:left;'>
					<?php
					if( isset($cliente[0]) && isset($cliente[1]) )
						loadFile($cliente);
					?>
					</div>
					
					<?php
					if( isset($cliente[0]) && isset($cliente[1]) )
					{
					?>
					<script type="text/javascript"> 
						//Funciones comunes a todos los dhtmlxGrid
						function init_dhtmlx_grid( gridObject )
						{
							gridObject.setImagePath("./images/");
							gridObject.enableMultiline(true);
							gridObject.setInitWidths("85,85,300,80,0,83,95,95,95,0,0,0,0,0,0");   	
							gridObject.setHeader("Modelo,Mi Modelo,Descripci&oacute;n, Cant Ordenada,Unidad,Precio LP,Importe Bruto,Descuento,Importe Neto,Borrar,Total2,IVA,descuento2,iva_precio,moneda");
							gridObject.setColAlign("center,center,left,center,center,right,right,right,right,center,center,center,center,center,center")
							gridObject.setColumnIds("modelo|mi_modelo|descipcion|cantidad|unidad|precio|importe|desc|total|borrar|total2|iva|descuento2|iva_precio|moneda");
							gridObject.setColumnHidden(4,true);
							gridObject.setColumnHidden(9,true);
							gridObject.setColumnHidden(10,true);
							gridObject.setColumnHidden(11,true);
							gridObject.setColumnHidden(12,true);
							gridObject.setColumnHidden(13,true);
							gridObject.setColumnHidden(14,true);
							gridObject.setNumberFormat("0,000.00",5);
							gridObject.setNumberFormat("0,000.00",6);
							gridObject.setNumberFormat("0,000.00",7);
							gridObject.setNumberFormat("0,000.00",8);
							gridObject.setSkin("helvex");
							gridObject.setColSorting("str,str,str,int,str,int,int,int,int,str,int,int,int,int,none");
							gridObject.setDelimiter("|");
							gridObject.setColTypes("ro|ro|ro|ro|ro|ron|ron[=c3*c5]|ron[=c3*c5*c12]|ron[=c6-c7]|ro|price|ro|price|price|ro"); 
							gridObject.enableAutoHeight(true);
							gridObject.init();
						}
						function init_dhtmlxDP(dpObject, gridObject)
						{					
							dpObject.enableDataNames();
							dpObject.setTransactionMode("POST",true);
							dpObject.setUpdateMode("off");
							dpObject.init( gridObject );
						}
						function calculateFooterValues(gridObject, gridName_suffix)
						{
							var out=0,out2=0,out3=0,valiva=0;
						 
							var arQ = document.getElementById("ar_q"+gridName_suffix);
							num=parseInt( sumColumn( gridObject, 3 ) );
							arQ.innerHTML = num;
							sct=sct+num;
							
							var brQ = document.getElementById( "br_q"+gridName_suffix );
							brQ.innerHTML = CommaFormatted( sumColumn( gridObject, 6 ) );
								
							var crQ = document.getElementById( "cr_q"+gridName_suffix );
							crQ.innerHTML = CommaFormatted( sumColumn( gridObject, 7 ) );
								
							var erQ = document.getElementById( "er_q"+gridName_suffix );
							erQ.innerHTML = CommaFormatted( sumColumn( gridObject, 8 ) );
							
							var frQ = document.getElementById( "fr_q"+gridName_suffix );
							out=sumColumn( gridObject, 8 )*0.16000;
							out=out.toFixed(2);
							frQ.innerHTML = CommaFormatted( out );
							
							var grQ = document.getElementById( "gr_q"+gridName_suffix );
							out2=sumColumn( gridObject, 8 )*1.16;
							out2 = out2.toFixed(2);
							out2s = parseFloat(out2);
							grQ.innerHTML = CommaFormatted(out2);
							
							stt=stt+out2s;
							return true;
						}
							
						function sumColumn(gridObject, ind)
						{
							var out = 0;
							for( var i=0; i < gridObject.getRowsNum(); i++ )
							{
								out+= parseFloat( gridObject.cells2( i, ind ).getValue() )
							}
							out = out.toFixed(2);
							return out;
						}

						function sumProdQty(gridObject, col, prefix, mod_col)
						{
							var out = 0;
							for(var i=0;i<gridObject.getRowsNum();i++)
							{
								//Se evita sumar los modelos que empiecen con prefix
								if( !(gridObject.cells2(i,mod_col).getValue()[0] === prefix) )
									out+= parseFloat(gridObject.cells2(i,col).getValue())
							}
							out = out.toFixed(2);
							return out;
						}						

						function get_and_validate_policies(gridObject, kunnr_delivery, kunnr, org, sec)
						{

							var client_id_for_addr= kunnr;
							var use_dir_alterna = 0;
							var dir_alt_id = kunnr_delivery;
							var c = org;
							var s = sec;

							var url = "";
							var policy = 0;
							var order_region = 0;

							if (client_id_for_addr != dir_alt_id)
							{
								client_id_for_addr = dir_alt_id;
								use_dir_alterna = 1;
							}
							
							url="verificar_region.php";
							new Ajax.Request(
								url,
								{
									method: 'get',
									asynchronous: false,
									parameters: { kunnr:client_id_for_addr, vkorg:c, spart:s, is_dir_alterna:use_dir_alterna },
									onSuccess: function(transport)
												{
													order_region = transport.responseText;
												}
								}
							);
							var ord_is_local = (order_region === "F")?0:1;
							
							url = "get_order_qty_policy.php";
							new Ajax.Request(
							url,
							{
								method: 'get',
								asynchronous: false,
								parameters: {org:c, sec:s, is_local:ord_is_local},
								onSuccess: function(transport)
											{
												//alert(transport.responseText);
												policy = transport.responseText
											}
							});
							
							if(policy[0] != "-" )
							{
								var p = policy.split("|");
								var cant = parseInt(p[0]);
								var unidades = "";
								var col_to_sum = 8;
								var total = 0;
								
								if(p[1] == "PESOS")
								{
									unidades = "$";
									col_to_sum = 8;
									total = sumColumn(gridObject, col_to_sum);
								}
								else if (p[1] == "PIEZA")
								{
									unidades = "piezas";
									col_to_sum = 3;
									total = sumProdQty(gridObject, col_to_sum, "A", 0)
								}
								
								if(total < cant)
								{
									var msg="";
									switch( col_to_sum )
									{
										case 3: msg = cant+" "+unidades+" (no cuentan los asientos)";
												break;
										case 8: msg = unidades+cant+" MXN";
												break;
										default:break;
									}
									return( "Su pedido debe ser mayor a "+msg+". Por favor corrija los datos necesarios en su archivo y vuelva a intentarlo." );
									
								}
							}
							return "";
						}

						function print_message_to_div ( divToPrintResult, message )
						{
							divCentral = document.getElementById(divToPrintResult);
							divCentral.innerHTML= '	<div align="center" style="position:relative; visibility:visible; width:100%; " class="errorPolicy">'+
												  '		<img src="./images/stop-icon.png" align="middle" alt="stop-icon" height="40px" width="40px" />'+message+
												  '	</div>';
						}
							
							
						function sendDataForDP( dpObject, szIdSuffix, arrResultDivs )
						{
							variable=dpObject.sendData();
							arrResultDivs.push('gridboxtxt_'+szIdSuffix);
							if (browser.indexOf("Microsoft") >= 0)
							{
								document.getElementById('transparency'+szIdSuffix).className = "transparenteabajo";
								document.getElementById('gridbox_'+szIdSuffix).style.filter='alpha(opacity=50)';
								document.getElementById('gridbox_'+szIdSuffix).style.zIndex='-2';
							}
							else
							{
								document.getElementById('transparency'+szIdSuffix).className = "transparenteabajo"; //PARA MOZILLA
							}
						}
						
						function retrieveOrdIdFromSessVar()
						{
							var x = "";
							var url = './getsessvar_orders_ids_from_xls.php';
							new Ajax.Request(	url,
													{
														method: 'post',
														asynchronous: false,														
														onSuccess: function(transport) {x = transport.responseText.split("|");	}
													}
											);
							return x;
						}
						function execute_bapi_ajax_req( szOrderId, divToPrintResult, dc )
						{
							divCentral = document.getElementById(divToPrintResult);
							divCentral.innerHTML = "<img src='./images/loading.gif' alt='Enviando pedido' />";
                                                        var num_licitacion = "<?php echo $licitacion; ?>";
                                                        var url2 = "./bapi_pedido_archivo.php?VarNumPed="+szOrderId+"&NumLicitacion="+num_licitacion;
							new Ajax.Request(url2,
															{
																method: 'post',
																asynchronous: false,
																onComplete: function(transport) {
																	if( transport.status === 200 ){
																		var ord_compra = "<?php echo $oc; ?>";
																		var x2 = transport.responseText;
																		//divCentral = document.getElementById(divToPrintResult);
																		if(x2 === '0')
																		{
																			divCentral.innerHTML= '	<div align="center" style="position:relative; visibility:visible; width:100%; top:5px" class="transparentearriba">'+
																				  '		<h2><span>Por el momento su pedido no pudo ser procesado: Por favor p&oacute;ngase en contacto con su ejecutivo de Cr&eacute;dito y Cobranzas para obtener m&aacute;s informaci&oacute;n.' + 
																				  '		 </span></h2>'+
																				  '	</div>';																					
																		}
																		else if(x2 === '-1')
																		{
																			divCentral.innerHTML= '	<div align="center" style="position:relative; visibility:visible; width:100%; top:5px" class="transparentearriba">'+
																				  '		<h2><span>Por el momento su pedido no pudo ser procesado: Por favor p&oacute;ngase en contacto con su ejecutivo de Administraci&oacute;n de ventas para obtener m&aacute;s informaci&oacute;n.' + 
																				  '		 </span></h2>'+
																				  '	</div>';			
																		}
																		else
																		{
																			divCentral.innerHTML= '	<div align="center" style="position:relative; visibility:visible; width:100%; top:5px" class="transparentearriba">'+
																				  '		<h2><span>El n&uacute;mero de su pedido es: '+
																				  '			<a href="javascript:popitup3(\'./ordencompra.php\',\'idPED='+x2+'\',\'datoscliente='+dc+'\' ,\'SPO='+ord_compra+'\')">'+x2+'</a>'+ 
																				  '		 </span></h2>'+
																				  '	</div>';
																		}
																	}
																	else{
																		divCentral.innerHTML= '	<div align="center" style="position:relative; visibility:visible; width:100%; top:5px" class="transparentearriba">'+
																			  '		<h2><span>Ocurri&oacute; un error de comunicaci&oacute;n con el servidor: '+
																					transport.status +
																				'		 </span></h2>'+
																				'	</div>';																
																	}
																}
															});
						}
						
						function deleteDBOrderData( gridObject, dpObject, szIdSuffix )
						{
							if (browser.indexOf("Microsoft") >= 0)
							{
								document.getElementById('transparency'+szIdSuffix).className = "";
								document.getElementById('gridbox_'+szIdSuffix).style.filter='';
								document.getElementById('gridbox_'+szIdSuffix).style.zIndex='';
							}
							else
							{
								document.getElementById('transparency'+szIdSuffix).className = ""; //PARA MOZILLA
							}
							var rowsArr =  gridObject.getAllRowIds("|").split("|");
							for( i=0; i<rowsArr.length; i++ )
							{
								dpObject.setUpdated(rowsArr[i], true, "inserted");
							}
						}
						
						function rollbackChanges(idsPedidosArr)
						{
							//Operaciones de Rollback en la BD
							<?php
							foreach ($orgArr as $key=>$value)
							{
								foreach ($secArr as $key1=>$value1)
								{
									//if ($estructura[$value][$value1][0]!='vacio')
									if( is_array( $estructura[$value][$value1] ) && count( $estructura[$value][$value1] > 0 ) )
									{
										echo( "deleteDBOrderData(mygrid$value$value1, myDataProcessor$value$value1, '$value$value1');" );
									}
								}
							}
							?> 
							
							var url = "./borra_datos_pedido_archivo.php?ids="+idsPedidosArr.join("|")+"&op=delete";
							new Ajax.Request( url, { method: 'get', asynchronous: true } );
						}
						
						function enviarTodoDP()
						{
							if( policy_error_flag == true)
							{
								alert("No se puede realizar la operaci\363n porque existen pol\355ticas de ventas incumplidas en su(s) pedido(s).")	
								return 0;
							}
							
							var resultDivsArray = new Array();
							var idsPedidosArr = new Array();
							var datosClienteArr = new Array();								
							<?php
							//Para cada grid generado previamente se llama al respectivo DP para guardar los datos en pedidos diferentes,
							//mas adelante se recuperan los ids generados por cada DP para llamar a la BAPI...
							foreach ($orgArr as $key=>$value)
							{
								foreach ($secArr as $key1=>$value1)
								{
									//if ($estructura[$value][$value1][0]!='vacio')
									if( is_array( $estructura[$value][$value1] ) && count( $estructura[$value][$value1] > 0 ) )
									{
										echo( "	sendDataForDP(myDataProcessor$value$value1, '$value$value1', resultDivsArray);
												datosClienteArr.push('".$datoscliente[$value.$value1]."');
											");
									}
								}
							}
							?>
							if (confirm("\277Esta usted seguro de enviar el/los pedido(s)?"))
							{
								idsPedidosArr = retrieveOrdIdFromSessVar();
								if( idsPedidosArr != '#' && (idsPedidosArr.length ==  resultDivsArray.length) && ( idsPedidosArr.length == datosClienteArr.length))
								{
									for ( var i=0; i<datosClienteArr.length; i++)
									{										
										var curr_cli = datosClienteArr[i].split('|');
										var curr_org = new String(curr_cli[3]);
										var curr_sec = new String(curr_cli[4]);
										var id_ped =0,idx_div=-1;
										//Busco en el arreglo de info de pedidos guardados, cual coincide con la org y sector actual
										for( var idx = 0; idx<idsPedidosArr.length; idx++ )
										{
											var pedido_meta = idsPedidosArr[idx].split('#');
											if( curr_org==pedido_meta[1] && curr_sec== pedido_meta[2] )
											{
												id_ped = pedido_meta[0];
												break;
											}
										}
										//Ahora busco en el arr de DIVs para colocar avisos de cada pedido, cual corresponde con la org y sec actual
										for( var idx = 0; idx<resultDivsArray.length; idx++)
										{
											if( resultDivsArray[idx].indexOf( curr_org.concat( curr_sec ) ) > 0 )
											{
												idx_div = idx;
												break;
											}
										}
										if( id_ped > 0 && idx_div >=0 )
										{
											execute_bapi_ajax_req( id_ped, resultDivsArray[idx_div], datosClienteArr[i] );
										}
									}
									alert("Solicitud de pedido concluida. Tome nota de sus c\363digos de confirmaci\363n o error para consultas posteriores.");
									document.getElementById("some_name").disabled=true;
									document.getElementById('npa').innerHTML = "<a href='nvopedido_archivo.html'> &iquest;Desea realizar otro pedido mediante archivo Excel? </a>";									
								}
								else
								{
									rollbackChanges(idsPedidosArr);
									alert("Ha ocurrido un error de comunicaci\363n. Por favor haga clic en el bot\363n de Enviar Pedido nuevamente.");
								}
							}
							else
							{
								idsPedidosArr = retrieveOrdIdFromSessVar();
								rollbackChanges(idsPedidosArr);
							}
							
						}
						
						<?php
						//DEFINICION DINAMICA Y CARGA DE GRID(S) CON DATOS LEIDOS DEL ARCHIVO
						$link=mysql_connect("localhost", "portal", "portal");
						if (!$link)
						{
							die('Could not connect: ' . mysql_error());
						}
						mysql_select_db("SAP");

						foreach ($orgArr as $key=>$organizacion)
						{
							foreach ($secArr as $key1=>$sector)
							{
								//if ($estructura[$organizacion][$sector][0]!='vacio')
								if( is_array( $estructura[$organizacion][$sector] ) && count( $estructura[$organizacion][$sector] ) > 0 )
								{
						?>
									mygrid<?php echo $organizacion.$sector;?> = new dhtmlXGridObject('gridbox_<?php echo $organizacion.$sector; ?>');
									init_dhtmlx_grid( mygrid<?php echo $organizacion.$sector;?> );
									myDataProcessor<?php echo $organizacion.$sector;?> = new dataProcessor("dataprocessors/dataprocessor_nvopedido_archivo.php?id_session=<?php echo $_SESSION['id_usuario'];?>&cliente=<?php echo $cliente[0];?>&org=<?php echo $organizacion; ?>&sector=<?php echo $sector; ?>&oc=<?php echo $oc; ?>");
									init_dhtmlxDP(myDataProcessor<?php echo $organizacion.$sector;?>, mygrid<?php echo $organizacion.$sector;?>);

									mygrid<?php echo $organizacion.$sector;?>.attachFooter("&nbsp;|#cspan|Totales|<div id='ar_q<?php echo $organizacion.$sector;?>'>0</div>|#cspan|&nbsp;|<div id='br_q<?php echo $organizacion.$sector;?>'>0</div>| <div id='cr_q<?php echo $organizacion.$sector;?>'>0</div>|&nbsp;|#cspan|#cspan|#cspan|#cspan", ["","","text-align:right;","text-align:center;","","","text-align:right;","text-align:right;"]);
									mygrid<?php echo $organizacion.$sector;?>.attachFooter("&nbsp;|#cspan|#cspan|#cspan|#cspan|#cspan|#cspan|<b>Subtotal $</b>|<div id='er_q<?php echo $organizacion.$sector;?>'>0</div>",["","","","","","","","text-align:right;","text-align:right;"]);
									mygrid<?php echo $organizacion.$sector;?>.attachFooter("&nbsp;|#cspan|#cspan|#cspan|#cspan|#cspan|#cspan|<b>IVA $</b>|<div id='fr_q<?php echo $organizacion.$sector;?>'>0</div>",["","","","","","","","text-align:right;","text-align:right;"]);
									mygrid<?php echo $organizacion.$sector;?>.attachFooter("&nbsp;|#cspan|#cspan|#cspan|#cspan|#cspan|#cspan|<b>Total $</b>|<div id='gr_q<?php echo $organizacion.$sector;?>'>0</div>",["","","","","","","","text-align:right;","text-align:right;"]);
									
						<?php 
									foreach($estructura[$organizacion][$sector] as $current_prod)
									{
										$descr_modelo=$current_prod[3];
										$str_encoding = mb_detect_encoding($descr_modelo);
										if( $str_encoding != 'ASCII')
											$descr_modelo = htmlentities ($descr_modelo);
										$descr=addslashes($descr_modelo);
						?>
										var lastrow = mygrid<?php echo $organizacion.$sector;?>.rowsCol.length ;
										mygrid<?php echo $organizacion.$sector;?>.addRow((new Date()).valueOf(),
																							"<?php echo $current_prod[2]; ?>"+"|"+
																							"<?php echo $current_prod[15]; ?>"+"|"+
																							"<?php echo $descr; ?>"+"|"+
																							"<?php echo $current_prod[4]; ?>"+"|"+
																							"<?php echo $current_prod[5]; ?>"+"|"+
																							"<?php echo $current_prod[6]; ?>"+"|"+
																							"<?php echo $current_prod[7]; ?>"+"|"+
																							"<?php echo $current_prod[8]; ?>"+"|"+
																							"<?php echo $current_prod[9]; ?>"+"|"+
																							"-"+"|"+
																							"<?php echo $current_prod[10]; ?>"+"|"+
																							"<?php echo $current_prod[11]; ?>"+"|"+
																							"<?php echo $current_prod[12]; ?>"+"|"+
																							"<?php echo $current_prod[13]; ?>"+"|"+
																							"<?php echo $current_prod[14]; ?>",
																							lastrow);
									<?php
									}//cada array de producto
									?>
									calculateFooterValues(mygrid<?php echo $organizacion.$sector;?>, <? echo $organizacion.$sector;?>);
									//Verificando politicas de ventas
									var policy_validate = get_and_validate_policies(mygrid<?php echo $organizacion.$sector;?>, '<?php echo $cliente[0]?>', '<?php echo $cliente[0]?>', '<? echo $organizacion;?>', '<? echo $sector;?>');
									if( !(policy_validate === "") )
									{
										print_message_to_div ( 'gridboxtxt_<?echo $organizacion.$sector ?>', policy_validate )
										policy_error_flag = true;
									}
								<?php
							 	} //diferente de vacio
							}//foreach sector
						}//foreach organizacion
						?>
					</script>
					<?php
					}
					?>
					
					<form id="npa" name="npa" method="post" action="" style="text-align:right;">
						<br><br>
						<?php
						if($error == 0)
						{
						?>
						<input type="button" onclick="javascript:location.href='./nvopedido_archivo.html' " value="Cancelar">
						<input type="button" id="some_name" name="some_name" value="Enviar Pedido(s)" onclick="enviarTodoDP();">
						<?php
						}
						else
						{
						?>
						<input type="button" onclick="javascript:location.href='./nvopedido_archivo.html'" value="<< Regresar a seleccionar archivo" >
						<?php
						}
						?>
					</form>					
					
				</div>
				<div class="message_to_user">
				</div>
			</div>
			<div class="footer" id="dfooter">
				<div class="footer_l">
					<span class="hlink">
						<?php get_footer(__FILE__, 1); ?>
					</span>
				</div>
				<div class="footer_r">
					<?php get_footer(__FILE__, 2); ?>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="js/load_google_analytics.js"></script>										
	</body>
</html>
