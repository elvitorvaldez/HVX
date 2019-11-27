<?php
session_start();
include "cabecera.php";
?>

<body>
<div class="loginContainer">

<div class="loginHeader"><img src="../images/helvex_logo.svg" style="width:25%">
</div>

    <div>Inicio de sesi&oacute;n</div><br>
<form role="form" method="POST" name="loginForm" id="loginForm" action="index.php">
<table id="tablaLogin" cellspacing="5" cellpadding="5">
  <tr>
    <td>
    <label for="user">Usuario</label>
  </td><td>
    <input style="width:30%" type="text" name="user" id="user"
           placeholder="Introduce tu nombre de usuario">
    </td>
  </tr><tr>
    <td>
    <label for="password">Contrase&ntilde;a</label>
    </td><td>
    <input style="width:30%" type="password" name="password" id="password" 
           placeholder="Contrase&ntilde;a">
    
    <input type="hidden" name="logged" value="logged">

 </td></tr>
 <tr><td colspan="2">
  <button id="login" type="submit" class="btn btn-default">Entrar</button>
</td></tr>
<tr>
  <td colspan="2">
  <div style="clear:both; height: 10px;">&nbsp;</div>
  <div id ="exito" class="alert alert-success" style="display:none">
  <strong>Login OK!</strong> En breve ser&aacute; redireccionado.
</div>

<div id ="fallo" class="alert alert-danger" style="display:none">
  <strong>Error!</strong> Usuario o contrase&ntilde;a inv&aacute;lidos.
</div>
</td></tr>
</table>
</form>


</div>

<?php

if ($_POST['logged']=="logged")
{
 
 
 require_once ("conecta.php");
$user=$_POST['user'];
$passwd=md5($_POST['password']);
$retorno="bad";
 $query="select nombre, admin from usuarios where username='$user' and password='$passwd'";
  $resultado=mysql_query($query);

$fila = mysql_fetch_assoc($resultado);
   if (isset($fila['nombre']))
   {
    session_start();
    $_SESSION['usuario']=$user;
    $_SESSION['nombre']=$fila['nombre'];
    $_SESSION['admin']=$fila['admin'];
    echo "<script language='JavaScript'>";
    echo "document.getElementById('fallo').style.display = 'none';";
    echo "document.getElementById('exito').style.display = 'block';";
    echo "location.href='principal.php';";
    echo "</script>";
   }
   else 
   {
     echo "<script language='JavaScript'>";
     echo "document.getElementById('fallo').style.display = 'block';";
     echo "</script>";
   }

}
?>


</body>
</html>
