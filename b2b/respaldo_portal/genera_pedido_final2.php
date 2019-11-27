<?php
$_Autentificar='ok';
include('encabezado.inc.am');
include('libs/smtp/smail.php');
include('libs/email_from_rcpts.inc.am');
require_once 'ZHX_NUEVO_PEDIDO.php';

if (!cuentaConServicio('backorder',$_SESSION['id_usuario'])){
	Header("Location: sin_permiso.html");  
}
if (isset($_REQUEST['SPO'])){
	$_REQUEST['SPO']=strtoupper($_REQUEST['SPO']);
}

$num_pedido_sap_var = ''; //Para poner mensaje de prueba junto a Nota en email

//Session Expiration
//$sess_expiration=session_cache_expire();
global $CONFIG;
$sess_expiration=$CONFIG["tiempo expiracion sesion"]/60;
$TimeBeforeExpiration=5;
$alertTime=($sess_expiration-$TimeBeforeExpiration)*60000;

//Se obtienen datos fundamentales para realizar el pedido
$recibe_pedido=(isset($_REQUEST['carga_hidden']))?$_REQUEST['carga_hidden']:'';
$org=(isset($_REQUEST['orgvtas_hidden']))?$_REQUEST['orgvtas_hidden']:'';
if ($org=="3101")
{
	$claseped="Z101";
}
else if ($org=="2201")
{
	$claseped="Z201";
}
else
{
	$claseped = "";
}

//Se determina si se tienen suficientes datos para continuar...
if( $recibe_pedido && $claseped )
{
	//Se obtienen todos los productos asociados con el pedido que se va a enviar a la BAPI
	$link = mysql_connect("localhost", "portal", "portal");
	mysql_select_db("SAP", $link);	
	$query="SELECT  pedidos_guardados_R3.id_pedido,
					pedidos_guardados_R3.cuenta,
					pedidos_guardados_R3.org_vtas,
					pedidos_guardados_R3.sector,
					pedidos_guardados_R3.orden_compra,
					pedidos_productos_R3.modelo,
					pedidos_productos_R3.cantidad 
			FROM pedidos_guardados_R3 INNER JOIN pedidos_productos_R3 
					ON pedidos_guardados_R3.id_pedido=pedidos_productos_R3.id_pedido 
			WHERE pedidos_guardados_R3.id_pedido=$recibe_pedido";
			
	$result = mysql_query($query,$link);
	if( $result && mysql_num_rows( $result ) ){
		$have_enough_data = true;
	}
	else
		$have_enough_data = false;
}
else
{
	$have_enough_data = false;
}
//FIN Se obtienen datos fundamentales para realizar el pedido

//Se obtiene informacion para enviar mensajes de email
$recibe_cliente=(isset($_REQUEST['recibe_cliente_hidden']))?$_REQUEST['recibe_cliente_hidden']:'';
$recibe_cliente2=$recibe_cliente;
$recibe_cliente=explode('||',$recibe_cliente);
$aux_recibe_cliente_1 = (isset($recibe_cliente[1]))?$recibe_cliente[1]:'';
$cliente=(isset($_REQUEST['cliente_hidden']))?$_REQUEST['cliente_hidden']:'';	
$org_txt=(isset($_REQUEST['org_txt']))?$_REQUEST['org_txt']:'';
$sec_txt=(isset($_REQUEST['sec_txt']))?$_REQUEST['sec_txt']:'';
$datoscliente=$cliente.' '.$aux_recibe_cliente_1.'|'.$org_txt.'|'.$sec_txt;
//FIN Se obtiene informacion para enviar mensajes de email

//Se construye la respuesta con el resultado
$response = "";

//Tratar de enviar pedido si se tienen todos los datos necesarios
if($have_enough_data == true )
{
	$direccion=(isset($_REQUEST['direccion_hidden']))?$_REQUEST['direccion_hidden']:'';
	$recibe_nota=(isset($_REQUEST['notas_hidden']))?$_REQUEST['notas_hidden']:'';
	$separa = wordwrap($recibe_nota, 132, "<br>\n");
	$chars2 = preg_split('/\n/', $separa, -1, PREG_SPLIT_OFFSET_CAPTURE);
        
	$orden_compra_final=(isset($_REQUEST['orden_compra_hidden']))?$_REQUEST['orden_compra_hidden']:'';
        
        $licitacion=(isset($_REQUEST['numero_licitacion_hidden']))?$_REQUEST['numero_licitacion_hidden']:'';
	
	$a1 = $a2 = $a3 = $a4 = $a5 = $a6 = $a7 = '';
	$ITEM_CATEG = $PLANT = $REQ_QTY = $TARGET_QTY = '';
	$ch2_00 = (isset($chars2[0][0]))?$chars2[0][0]:''; $ch2_10 = (isset($chars2[1][0]))?$chars2[1][0]:''; 
	$ch2_20 = (isset($chars2[2][0]))?$chars2[2][0]:''; $ch2_30 = (isset($chars2[3][0]))?$chars2[3][0]:''; 
	$ch2_40 = (isset($chars2[4][0]))?$chars2[4][0]:'';
	

	$SALESDOCUMENT='';
	//Para enviar correo a Desarrollo, si hay fallo
	$prov_res = '';
	$detalle = '';
	if($result){
		while($row=mysql_fetch_array($result)){
			$id_sesion=$row["id_pedido"];
			$a1=$row["cuenta"];
			$a2=$row["org_vtas"];
			$a3=$row["sector"];
			$a5='01';
			if ($a3==99){
				$a3='01';
				$a5='04'; 
			}
			
			$a4=$row["orden_compra"];
			$a6=$row["modelo"];
			$a7=$row["cantidad"];
			$a8='1';
			$detalle .= '<tr><td>'.$a6.'</td>'.'<td>'. $a7.'</td></tr>';
			$pedido2=ZHX_NUEVO_PEDIDO($claseped, $a3, $id_sesion, $ITEM_CATEG, $a1, $direccion,$a6, $a5, $PLANT, $a4, $REQ_QTY, $a7, $a2, $a8, $TARGET_QTY, $ch2_00, $ch2_10, $ch2_20, $ch2_30, $ch2_40, $CUENTA, $MENSAJE, $SALESDOCUMENT, $TEXT_CUENTA, $licitacion);
			//Servidor de correo electronico puede devolver error con \n
			$fnOutput = preg_replace('/\n+/','<br>',var_export($pedido2, true));
			$prov_res = $prov_res."<br>".$a6.": <br><pre>$fnOutput</pre><br>";
		}
	}
	
	$pedido3=ZHX_NUEVO_PEDIDO($claseped, $a3, $id_sesion, $ITEM_CATEG, $a1, $direccion, $a6, $a5, $PLANT, $a4, $REQ_QTY, $a7, $a2, 4, $TARGET_QTY, $ch2_00, $ch2_10, $ch2_20, $ch2_30, $ch2_40, $CUENTA, $MENSAJE, $SALESDOCUMENT, $TEXT_CUENTA, $licitacion);
	//Servidor de correo electronico puede devolver error con \n
	$fnOutput = preg_replace('/\n+/','<br>', var_export($pedido3,true));
	$prov_res .= "FINAL_DATA:<br><pre>$fnOutput</pre>";
	//Revisar errores
	foreach ($pedido3["TABLES"][0] as $value)
	{
		$type=$value["TYPE"];
		$id=$value["ID"];
		$number=$value["NUMBER"];
		$message=$value["MESSAGE"];
		$query3="INSERT into informes_pedidos_R3 (id_pedido, tipo, id_msn,numero,msn) values ('$recibe_pedido','$type','$id','$number','$message')";
		$result3 = mysql_query($query3,$link);
	}

	$query4="SELECT * FROM informes_pedidos_R3 where id_pedido=$recibe_pedido"; 
	
	$result4 = mysql_query($query4,$link);
	if($result4)
	{
		$mfinal = '';
		while($row_tmp=mysql_fetch_array($result4))
		{
			$m1=$row_tmp["id_pedido"];
			$m2=$row_tmp["tipo"];
			$m3=$row_tmp["id_msn"];
			$m4=$row_tmp["numero"];
			$m5=$row_tmp["msn"];
			$mc=$m1.' '.$m2.' '.$m3.' '.$m4.' '.$m5;
			$mfinal.=$mc;
		}
	}
	
	$mensaje=$pedido3["OUTPARAMS"][1][1];
	$num_pedido_sap=$pedido3["OUTPARAMS"][2][1];
	$num_pedido_sap= (int)$num_pedido_sap;
	
	$table='<table border=1>'.
				'<tr BGCOLOR="#CCCC99">'.
					'<td>Modelo</td>'.
					'<td>Cantidad</td>'.
				'</tr>';
	$table_end='</table>';
	
	$formato_nota='<font color=red><b>';
	$formato_nota2='</b></font>';
	$nota_final=$formato_nota.$recibe_nota.$formato_nota2;
	
	
	if (isset($mfinal) && $mfinal!=null && $num_pedido_sap > 0)
	{
		$mailinfo = "Portal Helvex: Nuevo Pedido Realizado <br>".' '."Pedido SAP: ".$num_pedido_sap. '' . "<br>Cliente: " . $recibe_cliente2 .'<br>Organizaci&oacute;n: '.$org_txt.'<br>Sector: '.$sec_txt.'<br><br> Usuario portal: ' . $_SESSION["nombre"] . ' ' . $_SESSION["apellido_paterno"] . ' ' .'<br><br>Detalle del Pedido:<br>'.$table.'<br>'.$detalle.$table_end;
		authMail($portal_sender, 'Portal HELVEX', $marketing_rep, '', '',"Portal - Nuevo Pedido ".$a1.' '.$num_pedido_sap, $mailinfo);
	}

	//Obteniendo datos para envio de correo

	//Se obtienen los codigos de Zona d Ventas, encargado de Cuentas por cobrar y zona de ventas para esta cuenta
	$sql="select vkgpr, busab, vkbur, stras, location, city1, post_code1 from Clientes_R3 where kunnr ='" . $a1 . "'";
	$result = mysql_query($sql);
	$row=mysql_fetch_array($result);
	
	//Obtengo los datos de contacto del Representante de Ventas
	/*$sql2="select * from t_contactos_cliente where campo='vkgpr' and cval='".str_replace(' ','',$row['vkgpr'])."'";
	$result2 = mysql_query($sql2);
	$row2=mysql_fetch_array($result2);*/
	//$email_rv=$row2['email'];

	//Obtengo los datos de contacto del gerente de zona de ventas
	/*$sql4="select * from t_contactos_cliente where campo='vkbur' and cval='".str_replace(' ','',$row['vkbur'])."'";
	$result4 = mysql_query($sql4);
	$row4=mysql_fetch_array($result4);*/
	//$email_ge=$row4['email'];	
		
	//Obtengo los datos de contacto del representante de Credito y Cobranzas
	$sql3="select * from t_contactos_cliente where campo='busab' and cval='".str_replace(' ','',$row['busab'])."'";
	$result3 = mysql_query($sql3);
	$row3=mysql_fetch_array($result3);
	$email_cyc = $row3["email"];
	$tel_cyc = $row3["tel"];
	
	//Obtengo los datos de contacto del administrativo de ventas 
	$sql5="select * from t_contactos_cliente where campo='admvta' and cval='".str_replace(' ','',$row['vkgpr'])."'";
	$result5 = mysql_query($sql5);
	$row5=mysql_fetch_array($result5);
	$email_adm_vta=$row5['email'];
	
	//Se ha especificado una nota en el pedido
	if ( strlen($recibe_nota) > 0 && strlen( trim( $recibe_nota ) ) > 0 )
	{
		$mailinfo = "Portal Helvex: Nota en Pedido<br>".' '.$nota_final . '<br>' . $datoscliente . '<br> N&uacute;mero de Pedido SAP: ' . $num_pedido_sap . ' ' . $table.'<br>'.$detalle.$table_end;
		//Correo para administracion de Ventas
		authMail($portal_sender, 'Portal HELVEX', $email_adm_vta, '', $sales_rep, "Nuevo Pedido Nota ".$num_pedido_sap_var, $mailinfo, $sales_sup);
	}
	//FIN Enviar correos de aviso acerca del resultado de enviar pedido
	
	if ( $num_pedido_sap==0 )
	{
		$table_head='<table border=1>'.
				'<tr BGCOLOR="#CCCC99">'.
					'<td>TYPE</td>'.
					'<td>ID</td>'.
					'<td>NUMBER</td>'.
					'<td>MESSAGE</td>'.
				'</tr>';

		$table_head2='<table border=1>'.
						'<tr BGCOLOR="#CCCC99">'.
							'<td>MENSAJE</td>'.
						'</tr>';
			
		$table_end='</table><br>';
		
		$per_prod_msg ="Error al realizar pedido. <br>".
						"Cliente: " . $recibe_cliente2 ."<br>".
						"Organizaci&oacute;n: ".$org_txt."<br>".
						"Sector: ".$sec_txt."<br><br>".
						"Usuario portal: ".$_SESSION["nombre"]." ".$_SESSION["apellido_paterno"]."<br><br>";
						
		$per_prod_msg = $per_prod_msg.'<b>Mensajes de R3:</b><br>'.$table_head;
		
		$b_is_block = FALSE;
		foreach ($pedido3["TABLES"][0] as $value)
		{
				$type=$value["TYPE"];
				$id=$value["ID"];
				$number=$value["NUMBER"];
				$message=$value["MESSAGE"];
				$per_prod_msg = $per_prod_msg.
								"<tr>".
									"<td>$type</td>".
							 		"<td>$id</td>".
							 		"<td>$number</td>".
							 		"<td>$message</td>".
							 	"</tr>";
				if( stristr($message, 'cliente bloq' ) || stristr($message, 'bloqueo de pedidos' ) )
					$b_is_block = TRUE;								
		}
		$per_prod_msg = $per_prod_msg.$table_end;
		
		if( isset($pedido3["OUTPARAMS"][1][1]) )
		{
			$per_prod_msg = $per_prod_msg.$table_head2.
								'<tr>'.
									'<td>'.$pedido3["OUTPARAMS"][1][1].'</td>'.
								'</tr>';
			$per_prod_msg = $per_prod_msg.$table_end;
		}
		
		//Correo para administracion de Ventas
		if( $b_is_block === FALSE )
		{
			$response = $response."	<font size=\"3\">*Por el momento su pedido no pudo ser procesado: Por favor p&oacute;ngase en contacto con su ejecutivo de Administraci&oacute;n de ventas para obtener m&aacute;s informaci&oacute;n.</font><br><br>";			
			authMail($portal_sender, 'Portal HELVEX', $email_adm_vta, '', '', "$recibe_cliente2: Error al intentar realizar pedido.", $per_prod_msg);
		}
		else //Correo para CyC
		{
			$response = $response."	<font size=\"3\">*Por el momento su pedido no pudo ser procesado: Por favor p&oacute;ngase en contacto con su ejecutivo de Cr&eacute;dito y Cobranzas para obtener m&aacute;s informaci&oacute;n.</font><br><br>";
			authMail($portal_sender, 'Portal HELVEX', $email_cyc, '', '', "$recibe_cliente2: Error al intentar realizar pedido.", $per_prod_msg);
		}		
		//Se envía correo a desarrollo con informacion de error, para debug
		authMail($portal_sender, 'Portal HELVEX', $info_devel  , 'Helvex Inf.', '', "$recibe_cliente2: Error al intentar realizar pedido.", $per_prod_msg.$prov_res.'<br><br>Detalle del Pedido:<br>recibe_pedido='.$recibe_pedido."<br>".$table.'<br>'.$detalle.$table_end);
	}
	else
	{
		$response = $response."	<center>
					<font size=\"2\">
						El n&uacute;mero de su pedido es:
					</font>
					<br>
					<a href=\"javascript:popitup3('./ordencompra.php?idPED=$num_pedido_sap&datoscliente=$datoscliente&SPO=$orden_compra_final','','','')\">
						<font size=\"3\" color=\"red\">
							$num_pedido_sap
						</font>
					</a>
					<br>
					<br>
					<font size=\"2\">
						Su orden de compra es:
					</font>
					<br>
					<font size='3' color='red'>
						$orden_compra_final
					</font>
					<br>
					<br>
					<br>
					<font size=\"3\">
						&iexcl;Gracias por preferir productos <font color='red'><b>HELVEX!</b></font>
						<br>
						<br>
					</font>
					<input type=\"Button\" onclick=\"javascript:location.href='./captura_pedido.html'\" value=\"&iquest;Desea hacer un nuevo pedido?\" >
				</center>
		";
		$query2 = "UPDATE pedidos_guardados_R3 SET estado_actual=0 WHERE id_pedido=$recibe_pedido";
		$result2 = mysql_query( $query2,$link );
	}
	//FIN Se construye la respuesta con el resultado
}//FIN Tratar de enviar pedido si se tienen todos los datos necesarios
else 
{
	$causa = "Ha ocurrido un error: Algunos datos correspondientes a su pedido se han perdido y no es posible continuar con su solicitud.";
	$response = $response."	<font size=\"3\">
				$causa
				<br>Le pedimos que mientras use la aplicaci&oacute;n de pedidos en este portal siga las siguientes recomendaciones:
				<ul>
					<li>No use los botones de navegaci&oacute;n de su Navegador de Internet; 
					en su lugar use los v&iacute;nculos en la barra de men&uacute del portal (Nuevo pedido, Backorder, Facturas, etc.)
					<li>No use el bot&oacute;n 'Recargar p&aacute;gina' de su navegador ni la tecla F5 de su teclado.
					<li>Recuerde que cualquier pedido que no sea completado ser&aacute; guardado para que pueda continuar con el proceso posteriormente.
					<li>Si su sesi&oacute;n ha expirado, ser&aacute; necesario que reinicie el proceso de enviar pedido.
				</ul>
			</font>
			<input type=\"Button\" onclick=\"javascript:location.href='./captura_pedido.html'\" value=\"Regresar a 'Nuevo pedido'.\" >
			<br>
			<br>";
			
	//Enviar correo a desarrollo informando el problema de perdida de datos:
	//Servidor de correo electronico puede devolver error con \n
	$reqOutput = preg_replace('/\n+/','<br>', var_export($_REQUEST,true));
	$sesOutput = preg_replace('/\n+/','<br>', var_export($_SESSION,true));
	$serOutput = preg_replace('/\n+/','<br>', var_export($_SERVER,true));
	
	$mail_body = "#Variables: (recibe_pedido=$recibe_pedido), (claseped=$claseped) <br>".
				  "#Request : <pre>$reqOutput</pre> <br>".
				  "#Sesi&oacute;n: <pre>$sesOutput</pre> <br>".
				  "#Server: <pre>$serOutput</pre>";
	
	 
	authMail($portal_sender, 'Portal HELVEX', $info_devel , 'Helvex Inf-Desarrollo', '' , "Se han perdido datos de sesion: genera_pedido_final_2.php", $mail_body);
	
} 

$_SESSION["genera_pedido_resp"] = $response;
header("Location:./genera_pedido_resultado.php");

?>
