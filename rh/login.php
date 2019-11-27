<?php
require_once ("conecta.php");
$user=$_POST['user'];
$passwd=md5($_POST['passwd']);
$retorno="bad";
 $query="select * from usuarios where username='$user' and password='$passwd'";
  $resultado=mysql_query($query);

 while ($fila = mysql_fetch_assoc($resultado)) {
    $retorno = "";
    session_start();
    $_SESSION['usuario']=utf8_decode($user);
    $_SESSION['nombre']=$fila['nombre'];
    $_SESSION['correo']=$fila['correo'];
    $_SESSION['rol']=$fila['rol'];
}
echo $retorno;
?>