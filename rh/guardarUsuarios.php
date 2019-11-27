<?php
	session_start();
	include 'conecta.php';
    $status="UPS";
    $mensaje="Error al guardar los datos";
    $titulo="";
    $operacion=$_POST['operacion'];
    $ejecuta=1;

    if($_REQUEST['operacion']=="crearUsuario")
{
 $nombre=$_REQUEST['nombre'];
 $nickname=$_REQUEST['nickname'];
 $contrasenia1=$_REQUEST['contrasenia1'];
 $contrasenia2=$_REQUEST['contrasenia2'];
 $admin=$_REQUEST['admin'];
 $email=$_REQUEST['email'];
 
 //validar que el usuario no exista
 
  $buscaUsuario="select username from usuarios where username='".$nickname."'";
     $buscaexe=mysql_query($buscaUsuario);
     $fila=mysql_fetch_array($buscaexe);
     $username=@$fila['username'];
   $error=0;
   if (isset($username))
   {
    $error=1;
     echo "<script>alert('Ya existe el usuario $nickname');
     window.history.back();
     </script>";
   }
   
   //Validar que las contraseñas coincidan
   if (strcmp ($contrasenia1 , $contrasenia2 ) !== 0)
   {
    $error=1;
      echo "<script>alert('Las contraseñas no coinciden');
      window.history.back();
      </script>";
   }
   
   //validar que las contraseñas tengan al menos 8 caracteres

    if (strlen($contrasenia1) < 8)
   {
    $error=1;
      echo "<script>alert('La contraseña debe ser de por lo menos 8 caracteres');
      window.history.back();
      </script>";
   }
   
   if ($error==0)
   {
   $contrasenia1=md5($contrasenia1);
   $mensaje="Error al crear usuario, intente de nuevo";
   $queryCrear="Insert into usuarios (username, password, nombre, rol, correo) values ('$nickname','$contrasenia1','$nombre','$admin','$email')";
     if (mysql_query($queryCrear))
     {
       $mensaje="Usuario $nickname creado exitosamente";
     }
    echo "<script>alert('".$mensaje."');
      window.history.back(); limpiarFormulario('crearUsuarioForm');
      </script>";
   }
}
else if ($_REQUEST['operacion']=="cambiarPassword")
{
 $nickname=$_REQUEST['nickname'];
 $contrasenia1=$_REQUEST['contrasenia1'];
 $contrasenia2=$_REQUEST['contrasenia2'];
 $error=0;
    //Validar que las contraseñas coincidan
   if (strcmp ($contrasenia1 , $contrasenia2 ) !== 0)
   {
    $error=1;
      echo "<script>alert('Las contraseñas no coinciden');
      window.history.back();
      </script>";
   }
   
   //validar que las contraseñas tengan al menos 8 caracteres
   
    if (strlen($contrasenia1) < 8)
   {
    $error=1;
      echo "<script>alert('La contraseña debe ser de por lo menos 8 caracteres');
      window.history.back();
      </script>";
   }
   
   if ($error==0)
   {
    $contrasenia1=md5($contrasenia1);
   $mensaje="Error al actualizar contraseña, intente de nuevo";
   $queryCambiarContra="Update usuarios set password='$contrasenia1' where username='$nickname'";
     if (mysql_query($queryCambiarContra))
     {
       $mensaje="Contraseña para $nickname actualizada exitosamente";
     }
    echo "<script>alert('".$mensaje."');
      window.history.back(); limpiarFormulario('cambiarPasswordForm');
      </script>";
   }
 
}
else if ($_REQUEST['operacion']=="cambiarRol")
{
 $nickname=$_REQUEST['nickname'];
 $admin=$_REQUEST['admin'];
 $mensaje="Error al actualizar rol, intente de nuevo";
 $queryCambiarRol="Update usuarios set rol='$admin' where username='$nickname'";
     if (mysql_query($queryCambiarRol))
     {
       $mensaje="Rol para $nickname actualizado exitosamente";
     }
    echo "<script>alert('".$mensaje."');
      window.history.back(); limpiarFormulario('cambiarPasswordForm');
      </script>";
}
else if ($_REQUEST['operacion']=="eliminarUsuario")
{
 $nickname=$_REQUEST['nickname'];
 $mensaje="Error al eliminar usuario $nickname, intente de nuevo";
 $queryBorrar="Delete from usuarios where username='$nickname'";
     if (mysql_query($queryBorrar))
     {
       $mensaje="Se ha eliminado el usuario $nickname exitosamente";
     }
    echo "<script>alert('".$mensaje."');
      window.history.back(); limpiarFormulario('cambiarPasswordForm');
      </script>";
}
?>