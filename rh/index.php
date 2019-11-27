<?php
session_start();
include "cabecera.php";
?>

<body>
<?php
$url= (explode("/", $_SERVER["REQUEST_URI"]));
if ($url[2]=="")
{
    $url[2]="index.php";
}

if (isset($_SESSION['nombre']) && $url[2]=="index.php")
{
   echo "<script>location.href ='dashboard.php'</script>";
}
?>


<div class="loginContainer">

<div class="loginHeader">
  <div>Inicio de sesi&oacute;n</div><br>
  <img src="img/recluta.jpg" style="width:25%"></div>
<br>
  
<form role="form" name="loginForm" id="loginForm">
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
    <label for="passwd">Contrase&ntilde;a</label>
    </td><td>
    <input style="width:30%" type="password" id="passwd" id="passwd" 
           placeholder="Contrase&ntilde;a">

 </td></tr>
 <tr><td colspan="2">
  <br>
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



<script language="JavaScript">

$( document ).ready(function() {
    $( "#loginForm" ).submit(function( event ) {    
    event.preventDefault();
     $(this).serialize();
    $.ajax({
      method: "POST",
      url: "login.php",
      data: { user: $('#user').val(), passwd: $('#passwd').val() }
    })
      .done(function( msg ) {
        //alert(msg);
        if (msg.trim()!="")
        {
          $( "#fallo" ).show();
        }
        else
        {
          $( "#fallo" ).hide();
          $( "#exito" ).show();
          location.href="dashboard.php";
        }
      });
  });
});
</script>
</body>
</html>
