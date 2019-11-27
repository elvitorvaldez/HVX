<?php
require_once 'cSAPConnectorConfig.php';

function ZHX_NUEVO_PEDIDO($CLASEPED, $DIVISION, $ID_SESION, $ITEM_CATEG, $KUNNR1, $KUNNR1_DES, $MATERIAL1, $ORD_REASON, $PLANT, $PURCH_NO_C, $REQ_QTY, $REQ_QTY1, $SALES_ORG, $SELECCION, $TARGET_QTY, $VL_TXT1, $VL_TXT2, $VL_TXT3, $VL_TXT4, $VL_TXT5, &$CUENTA, &$MENSAJE, &$SALESDOCUMENT, &$TEXT_CUENTA, &$COLLECT_NO)
{
	$o = cSAPConnectorConfig::getInstance();
	
	$result = null;
	$_InputParams = array(array("CLASEPED", $CLASEPED), array("DIVISION", $DIVISION), array("ID_SESION", $ID_SESION), array("ITEM_CATEG", $ITEM_CATEG), array("KUNNR1", $KUNNR1), array("KUNNR1_DES", $KUNNR1_DES), array("MATERIAL1", $MATERIAL1), array("ORD_REASON", $ORD_REASON), array("PLANT", $PLANT), array("PURCH_NO_C", $PURCH_NO_C), array("REQ_QTY", $REQ_QTY), array("REQ_QTY1", $REQ_QTY1), array("SALES_ORG", $SALES_ORG), array("SELECCION", $SELECCION), array("TARGET_QTY", $TARGET_QTY), array("VL_TXT1", $VL_TXT1), array("VL_TXT2", $VL_TXT2), array("VL_TXT3", $VL_TXT3), array("VL_TXT4", $VL_TXT4), array("VL_TXT5", $VL_TXT5), array("COLLECT_NO", $COLLECT_NO));
	$_OutputParams =  array(array("CUENTA", &$CUENTA), array("MENSAJE", &$MENSAJE), array("SALESDOCUMENT", &$SALESDOCUMENT), array("TEXT_CUENTA", &$TEXT_CUENTA));
	$_Tables = array("INTERFACE","RETURN1","TEXT");
	
	$conn = $o->params;
	
	//open connection
	$rfc = saprfc_open ($conn);
	
	if (! $rfc ) {
		if ($rfc == SAPRFC_EXCEPTION )
		{
			$rtn_error = array("EXCEPTION" => $rfc, "DESC" => saprfc_exception($conn));
		}
		else
		{
			$rtn_error = array("ERROR" => $rfc, "DESC" => saprfc_error($conn));
		}
		return $rtn_error;
	}
	
	$fce = saprfc_function_discover($rfc, "ZHX_NUEVO_PEDIDO");
	
	if (! $fce ){
		if ($fce == SAPRFC_EXCEPTION )
		{
			$rtn_error = array("EXCEPTION" => $fce, "DESC" => saprfc_exception($rfc));
		}
		else
		{
			$rtn_error = array("ERROR" => $fce, "DESC" => saprfc_error($rfc));
		}
		saprfc_close($rfc);
		return $rtn_error;
	}
	
	for($i=0; $i<count($_InputParams); $i++)
	{
		$subParam = $_InputParams[$i];
		saprfc_import($fce, $subParam[0], $subParam[1]);
	}
	
	//use it to view function info
	//saprfc_function_debug_info($fce);
	
	//tables initialize
	for($i=0; $i<count($_Tables); $i++)
	{
		saprfc_table_init ($fce,$_Tables[$i]);
	}
	
	$rc = saprfc_call_and_receive ($fce);
	if ($rc != SAPRFC_OK)
	{
		if ($rc == SAPRFC_EXCEPTION )
		{
			$rtn_error = array("EXCEPTION" => $rc, "DESC" => saprfc_exception($fce));
		}
		else
		{
			$rtn_error = array("ERROR" => $rc, "DESC" => saprfc_error($fce));
		}
		saprfc_function_free($fce);
		saprfc_close($rfc);
		return $rtn_error;
	}
	
	$tables = null;
	for($i=0; $i<count($_Tables); $i++)
	{
		$rowsCount = saprfc_table_rows ($fce, $_Tables[$i]);
		$tableRow = null;
		if($rowsCount != 0){
			for ($j=1; $j<=$rowsCount; $j++){
				$QTAB = saprfc_table_read ($fce,$_Tables[$i],$j);
				$tableRow[$j-1] = $QTAB;
			}		
		}
		
		$tables[$i-1] = $tableRow;
	}
	
	$result["TABLES"] = $tables;
	
	if($_OutputParams!=null)
	{
		for($i=0; $i<count($_OutputParams); $i++)
		{
			$_OutputParams[$i][1] = saprfc_export($fce, $_OutputParams[$i][0]);
		}
		
	}
	
	$result["OUTPARAMS"] = $_OutputParams;
	
	saprfc_function_free($fce);
	saprfc_close($rfc);
	return $result;
}
?>
