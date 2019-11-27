<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
$modelo=$_GET['modelo'];
$query="select modelo,nombreProductoEn,imagen, hojaDespiece, hojaEspecificacion, guiaInstalacion, certificadoProducto from ProductosDetalle where modelo = '$modelo'";
// echo $query;
$con=mysqli_connect("localhost","root","hx_pruebas","PCM_mirror");

$result = mysqli_query($con,$query);

while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $datospcm['modelo'] =  utf8_encode($row['modelo']);
           //$datospcm['nombreProductoEn'] =  utf8_encode($row['nombreProductoEn']);
            $datospcm['imagen'] = utf8_encode($row['imagen']);
            $datospcm['hojaDespiece'] = utf8_encode($row['hojaDespiece']);
            $datospcm['hojaEspecificacion'] =  utf8_encode($row['hojaEspecificacion']);
            $datospcm['guiaInstalacion'] =  utf8_encode($row['guiaInstalacion']);
            $datospcm['certificadoProducto'] =  utf8_encode($row['certificadoProducto']);

           // $datos['DatosPCM'] = $datospcm;
        }

mysqli_close($con);


$query="select DescripcionCorta from materiales where Material = '$modelo'";

$con=mysqli_connect("localhost","root","hx_pruebas","catalogointeractivo");
$result = mysqli_query($con,$query);
while($renglon = $result->fetch_array(MYSQLI_ASSOC))
        {
            
            $datospcm['nombreProducto'] =  utf8_encode($renglon['DescripcionCorta']);
        }

 $datos['DatosPCM'] = $datospcm;



$query="select datosTecnicos,datosDiseno,datosFuncionamiento, datosTecnologia, imgMedidas1, imgMedidas2, imgMedidas3, imgFuncionamiento1, imgFuncionamiento2, imgFuncionamiento3, imgTecnologia1, imgTecnologia2, imgTecnologia3, videoComercial, videoInstalacion from datos where modelo = '$modelo'";
$con=mysqli_connect("localhost","root","hx_pruebas","catalogointeractivo");


$result = mysqli_query($con,$query);

while($row = $result->fetch_array(MYSQLI_ASSOC))
        {

        	  @$datosTecnicos=explode('|', $row['datosTecnicos']);
			  @$datosTecnologia=explode('|', $row['datosTecnologia']);
			  @$datosDisenio=explode('|', $row['datosDiseno']);
			  @$datosFuncionamiento=explode('|', $row['datosFuncionamiento']);

            $datos['datosTecnicos'] =  $datosTecnicos;
            $datos['datosTecnicos']=array_filter($datos['datosTecnicos'], "strlen");
            $datos['datosDiseno'] =  $datosDisenio;
            $datos['datosDiseno']=array_filter($datos['datosDiseno'], "strlen");
            $datos['datosFuncionamiento'] = $datosFuncionamiento;
            $datos['datosFuncionamiento']=array_filter($datos['datosFuncionamiento'], "strlen");
            $datos['datosTecnologia'] = $datosTecnologia;
            $datos['datosTecnologia']=array_filter($datos['datosTecnologia'], "strlen");
            $datos['imgMedidas'][] =  $row['imgMedidas1'];
            $datos['imgMedidas'][] =  $row['imgMedidas2'];
            $datos['imgMedidas'][] =  $row['imgMedidas3'];
            $datos['imgMedidas']=array_filter($datos['imgMedidas'], "strlen");
            $datos['imgFuncionamiento'][] =  $row['imgFuncionamiento1'];
            $datos['imgFuncionamiento'][] =  $row['imgFuncionamiento2'];
            $datos['imgFuncionamiento'][] =  $row['imgFuncionamiento3'];
            $datos['imgFuncionamiento']=array_filter($datos['imgFuncionamiento'], "strlen");
            $datos['imgTecnologia'][] =  $row['imgTecnologia1'];
            $datos['imgTecnologia'][] =  $row['imgTecnologia2'];
            $datos['imgTecnologia'][] =  $row['imgTecnologia3'];
            $datos['imgTecnologia']=array_filter($datos['imgTecnologia'], "strlen");            
            $videos['videoComercial'] = $row['videoComercial'];
            $videos['videoInstalacion'] = $row['videoInstalacion'];
            $datos['videos']=array_filter($videos, "strlen");

        }

mysqli_close($con);
$todos[] = $datos;
$json = json_encode($todos);


echo $json;

?>