<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
$modelo=$_GET['modelo'];

$query="select videoComercial, videoInstalacion from datos where modelo = '$modelo'";
$con=mysqli_connect("localhost","root","hx_pruebas","catalogointeractivo");


$result = mysqli_query($con,$query);

while($row = $result->fetch_array(MYSQLI_ASSOC))
        {

        	$video['videoComercial'] = $row['videoComercial'];
            $video['videoInstalacion'] = $row['videoInstalacion'];
            $videos['videos'] = $video;
        }
$todos[] = $videos;
$json = json_encode($todos);

echo $json;
mysqli_close($con);


?>