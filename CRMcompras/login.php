<?php
require_once ("conecta.php");
$user=$_POST['user'];
$passwd=md5($_POST['passwd']);
$retorno="bad";
 $query="select nombre from usuarios where username='$user' and password='$passwd'";
  $resultado=mysql_query($query);

 while ($fila = mysql_fetch_assoc($resultado)) {
    $retorno = "";
    session_start();
    $_SESSION['usuario']=$user;
    $_SESSION['nombre']=$fila['nombre'];
}
echo $retorno;
?>