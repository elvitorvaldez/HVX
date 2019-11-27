<?php
require_once ("conecta.php");
$retorno="Error al guardar los datos";
$status="UPS";

if ($_POST['operacion']=="guardarDatos")
{
@$idProveedor=$_POST['idProveedor'];
@$PaginaWeb=$_POST['PaginaWeb'];
@$TieneFiliales=$_POST['TieneFiliales'];
@$NombresFiliales=$_POST['NombresFiliales'];
@$FechaFundacion=$_POST['FechaFundacion'];
@$Contacto1=$_POST['Contacto1'];
@$Contacto2=$_POST['Contacto2'];
@$Contacto3=$_POST['Contacto3'];
@$Cargo1=$_POST['Cargo1'];
@$Cargo2=$_POST['Cargo2'];
@$Cargo3=$_POST['Cargo3'];
@$Correo1=$_POST['Correo1'];
@$Correo2=$_POST['Correo2'];
@$Correo3=$_POST['Correo3'];
@$ProveedorUnico=$_POST['ProveedorUnico'];
@$ProveedorPrincipalAlterno=$_POST['ProveedorPrincipalAlterno'];
@$ProveedorConfiable=$_POST['ProveedorConfiable'];
@$ProveedorCondicionado=$_POST['ProveedorCondicionado'];
@$CantidadEmpleados=$_POST['CantidadEmpleados'];
@$CantidadClientes=$_POST['CantidadClientes'];
@$VentasAnioAnterior2=$_POST['VentasAnioAnterior2'];
@$VentasAnioAnterior1=$_POST['VentasAnioAnterior1'];
@$FacilmenteSustituible=$_POST['FacilmenteSustituible'];
@$PartRelativaHelvexVentas=$_POST['PartRelativaHelvexVentas'];
@$TasaDeDescuento=$_POST['TasaDeDescuento'];
@$TieneSistemaCalidad=$_POST['TieneSistemaCalidad'];
@$ProblemasCalidad6Meses=$_POST['ProblemasCalidad6Meses'];
@$Incremento1=$_POST['Incremento1'];
@$Incremento2=$_POST['Incremento2'];
@$Incremento3=$_POST['Incremento3'];
@$Incremento4=$_POST['Incremento4'];
@$Incremento5=$_POST['Incremento5'];
@$Incremento=$Incremento1."|".$Incremento2."|".$Incremento3."|".$Incremento4."|".$Incremento5;




 $query="UPDATE Proveedores SET PaginaWeb='".$PaginaWeb."',TieneFiliales='".$TieneFiliales."',NombresFiliales='".$NombresFiliales."',FechaFundacion='".$FechaFundacion."',Contacto1='".$Contacto1."',Contacto2='".$Contacto2."',Contacto3='".$Contacto3."',Cargo1='".$Cargo1."',Cargo2='".$Cargo2."',Cargo3='".$Cargo3."',Correo1='".$Correo1."',Correo2='".$Correo2."',Correo3='".$Correo3."',ProveedorUnico='".$ProveedorUnico."',ProveedorPrincipalAlterno='".$ProveedorPrincipalAlterno."',ProveedorConfiable='".$ProveedorConfiable."',ProveedorCondicionado='".$ProveedorCondicionado."',CantidadEmpleados='".$CantidadEmpleados."',CantidadClientes='".$CantidadClientes."',VentasAnioAnterior2='".$VentasAnioAnterior2."',VentasAnioAnterior1='".$VentasAnioAnterior1."',FacilmenteSustituible='".$FacilmenteSustituible."',PartRelativaHelvexVentas='".$PartRelativaHelvexVentas."',TasaDeDescuento='".$TasaDeDescuento."',ProblemasCalidad6Meses='".$ProblemasCalidad6Meses."',TieneSistemaCalidad='".$TieneSistemaCalidad."',Incremento='".$Incremento."' where numProveedor='".$idProveedor."'";
  if (mysql_query($query))
  {
  	echo "<script>alert('Se han actualizado los datos del Proveedor');
   window.location='formulario.php?idProveedor=".$idProveedor."';
   </script>";
  	$status="OK";
  }


}

if ($_POST['operacion']=="agregarProveedor")
{
$RazonSocial = $_POST['RazonSocial'];
$RFC = $_POST['RFC'];
$Calle = $_POST['Calle'];
$Poblacion = $_POST['Poblacion'];
$Region = $_POST['Region'];
$CP = $_POST['CP'];
$Tel1 = $_POST['Tel1'];
$Tel2 = $_POST['Tel2'];
$PerteneceaGrupoEmpresarial = $_POST['PerteneceaGrupoEmpresarial'];
$ProductoQueVende = $_POST['ProductoQueVende'];
$Divisa = $_POST['Divisa'];

 $queryBusca="select * from DatosSAP where RazonSocial like '%$RazonSocial%' ";
 $buscaexe=mysql_query($queryBusca);
   $fila=mysql_fetch_array($buscaexe);
   $idProv=@$fila['Numero'];
   if (isset($idProv))
   {
    echo "<script>alert('Ya existe este proveedor cuyo ID es $idProv');
   window.history.back();
   </script>";
   }

   else{
  $query1="INSERT INTO DatosSAP(RazonSocial, RFC, Calle, Poblacion, Pais, Region, CP, PerteneceaGrupoEmpresarial, Tel1, Tel2, ProductoQueVende, Divisa)";
  $query1.="VALUES('$RazonSocial', '$RFC', '$Calle', '$Poblacion', '$Pais', '$Region', '$CP', '$PerteneceaGrupoEmpresarial', '$Tel1', '$Tel2', '$ProductoQueVende', '$Divisa')";


    if (mysql_query($query1))
    {
     $busca="select Numero from DatosSAP order by Numero Desc limit 1";
     $buscaexe=mysql_query($busca);
     $fila=mysql_fetch_array($buscaexe);
     $idProveedor=$fila['Numero'];
     $query2="Insert Into Proveedores (numProveedor) values ('".$idProveedor."')";
     mysql_query($query2);
     echo "<script>alert('Se ha creado el proveedor con ID $idProveedor');
     window.location='formulario.php?idProveedor=".$idProveedor."';
     </script>";
  
    }
   }

}

else if ($_REQUEST['operacion']=="borrarProveedor")
{
 $idProveedor=$_REQUEST['idProveedor'];
 $query1="Delete from DatosSAP where Numero='".$idProveedor."'";
  if (mysql_query($query1))
  {
   $query2="Delete from Proveedores where numProveedor='".$idProveedor."'";
   mysql_query($query1);
   $mensaje="Se ha eliminado al proveedor ".$idProveedor;
  }
  else
  {
   $mensaje="Error al eliminar el proveedor ".$idProveedor;
  }
  	echo "<script>alert('".$mensaje."');window.location='listar.php';</script>";
}

else if ($_POST['operacion']=="guardarHistorial")
{
		
	@$idProveedor=$_POST['hhidProveedor'];
	@$fechaNegociacion=$_POST['hhfechaNegociacion'];
  @$descripcion=$_POST['hhdescripcionHistorial'];
  @$archivo=$_FILES['hhcedula']['name'];
	

	$dir_subida = '/home/victorda/public_html/HVX/CRMcompras/uploads/historiales/';
  $archivo_subido = $dir_subida . basename($archivo);
	
	if (move_uploaded_file($_FILES['hhcedula']['tmp_name'], $archivo_subido)) {
  $query="INSERT INTO HistorialNegociaciones SET numProveedor='".$idProveedor."',fechaNegociacion='".$fechaNegociacion."',descripcion='".utf8_encode($descripcion)."',cedula='".$archivo."'";
  if (mysql_query($query))
  {
  	$retorno="Se han actualizado los datos";
  	$status="OK";
  }
    $retorno.= " y El fichero es válido y se subió con éxito.";
    	//echo "<script>alert('".$retorno."');cancelarHistorial();</script>";
} else {
    $retorno.= " y no se subió el archivo error #".$_FILES['hhcedula']['error'];
}
	 	//echo "<script>alert('".$retorno."');</script>";
   echo "<script>alert('".$retorno."');cancelarHistorial();</script>";
}

else if ($_POST['operacion']=="borrarHistorial")
{
 $idProveedor=$_POST['idProveedor'];
 $idHistorial=$_POST['idHistorial'];
  $query="Update HistorialNegociaciones set activo=0 where idHistorial='".$idHistorial."'";

  if (mysql_query($query))
  {
  	$retorno="Se ha eliminado el registro de historial";
  }
  else
  {
    $retorno="Ha ocurrido un error al eliminar el registro de historial";
  }
  
  	echo "<script>alert('".$retorno."');window.location='formulario.php?idProveedor=".$idProveedor."';cancelarHistorial();</script>";
}


else if ($_REQUEST['operacion']=="crearUsuario")
{
 $nombre=utf8_decode($_REQUEST['nombre']);
 $nickname=$_REQUEST['nickname'];
 $contrasenia1=$_REQUEST['contrasenia1'];
 $contrasenia2=$_REQUEST['contrasenia2'];
 $admin=$_REQUEST['admin'];
 
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
   $queryCrear="Insert into usuarios (username, password, nombre, admin) values ('$nickname','$contrasenia1','$nombre','$admin')";
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
 $queryCambiarRol="Update usuarios set admin='$admin' where username='$nickname'";
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

//$data['mensaje']=$retorno;
//
//$data['status']=$status;
//header('Content-type: application/json; charset=utf-8');
//echo json_encode($data);
//
//die();
?>