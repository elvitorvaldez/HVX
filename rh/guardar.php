<?php
	session_start();
	include 'conecta.php';
    $status="UPS";
    $mensaje="Error al guardar los datos";
    $titulo="";
    $operacion=$_POST['operacion'];
    $ejecuta=1;

    if ($operacion==="solicitud")
    {


	    $departamento=utf8_decode($_POST['departamento']);
	    $nombre=utf8_decode($_POST['nombre']);
	    $descripcion=utf8_decode($_POST['descripcion']);
	    $escolaridad=utf8_decode($_POST['escolaridad']);
	    $experiencia=utf8_decode($_POST['experiencia']); 
	    $requisitos=utf8_decode($_POST['requisitos']);
	    $conocimientos=utf8_decode($_POST['conocimientos']);
	    $tipoPuesto=$_POST['tipoPuesto'];
	    $titulado=$_POST['titulado']; 
	    $correo=$_POST['correo'];
	    $query= "INSERT INTO Vacantes(Departamento,Puesto,Descripcion,Escolaridad,Experiencia,Requisitos,Conocimientos,TipoPuesto,Titulado,correoSolicitante,Status) ";
	    $query.="VALUES ('$departamento','$nombre','$descripcion','$escolaridad','$experiencia','$requisitos','$conocimientos','$tipoPuesto','$titulado','$correo',1)";
	    //die($query);
	    $titulo="Notificación de solicitud de personal";
	    $cuerpo="Se ha hecho una nueva solicitud de personal por parte de departamento de $departamento. Favor de acceder al sistema para ver el detalle de la misma en la sección \"Solicitud de personal\"";

	}
		
	else if ($operacion==="aceptada")
    {
 
	    $id=$_POST['idSolicitud'];
	    $puesto=utf8_decode($_POST['puesto']);
	   
	    $query= "Update Vacantes set status = 2 where id = $id";
	    $titulo="Respuesta a su de solicitud de personal";
	    $cuerpo="Se ha aceptado su solicitud de personal para el puesto de $puesto, el departamento de R.R.H.H le estará informando del proceso";

	}

	else if ($operacion==="rechazada")
    {
    	$id=$_POST['idSolicitud'];
	    $puesto=utf8_decode($_POST['puesto']);
	    $motivo=utf8_decode($_POST['motivoRechazo']);
	    $query= "Update Vacantes set status = 3, motivoRechazo='$motivo' where id = $id";
	    $titulo="Respuesta a su de solicitud de personal";
	    $cuerpo="Se ha rechazado su solicitud de personal para el puesto de $puesto, debido a que $motivo";

	}

	else if ($operacion==="avanzar")
    {
 
	    $id=$_POST['idCandidato'];
	    $puesto=utf8_decode($_POST['puesto']);
	   
	    $query= "Update Candidatos set status = 4 where idCandidato = $id";
	    $titulo="Programar entrevista con candidato";
	    $cuerpo="Se ha considerado un candidato para el puesto de $puesto, el departamento de R.R.H.H le estará informando del proceso de entrevistas";

	}

	else if ($operacion==="rechazar")
    {
 
	    $id=$_POST['idCandidato'];
	    $puesto=utf8_decode($_POST['puesto']);
	   
	    $query= "Update Candidatos set status = 6 where idCandidato = $id";
	    $titulo="Candidato rechazado";
	    $cuerpo="Se ha rechazado a un candidato para el puesto de $puesto, pero el proceso de búsqueda continúa";

	}

	else if ($operacion==="contratar")
    {
 
	    $id=$_POST['idCandidato'];
	    $puesto=utf8_decode($_POST['puesto']);
	    //se cierra la vacante
	    $query= "Update Vacantes set status = 5 where id = $id";
	    mysql_query($query);
	    //ahora se procesa el candidato
	    $query= "Update Candidatos set status = 5 where idCandidato = $id";
	    $titulo="Un candidato ha sido aceptado";
	    $cuerpo="Se ha aceptado a un candidato para el puesto de $puesto, el departamento de R.R.H.H se encargará del proceso de contratación, se procede a cerrar la solicitud de personal";

	}


	 else if ($operacion==="agregarCandidato")
    {

    	
	    $nombre=utf8_decode($_POST['nombre']);
	    $puesto=utf8_decode($_POST['puesto']);
	    $puesto=trim($puesto);	
	    $telefonoLocal=utf8_decode($_POST['telLocal']);
	    $celular=utf8_decode($_POST['celular']);   
	    $escolaridad=utf8_decode($_POST['escolaridad']);
	    $dir_subida = 'uploads/';
	    $archivoBdd=basename($_FILES['cv']['name']);
	    
		$archivo_subir = $dir_subida . basename($_FILES['cv']['name']);
		$pos = strpos($_FILES['cv']['type'], "pdf");

		if ($pos===false)
	    {
          $ejecuta=0;
          $mensaje="El archivo no es un PDF o no es válido ".$_FILES['cv']['type'];
	    }
        else
        {
		    $correo=$_POST['correo'];
		    $query= "INSERT INTO Candidatos(Nombre, PuestoDeseado, TelefonoLocal, Celular, Escolaridad, CV, status)";
		    $query.="VALUES ('$nombre','$puesto','$telefonoLocal','$celular','$escolaridad','$archivoBdd',1)";
		    $titulo="Notificación de solicitud de personal";
		    $cuerpo="Se ha cargado un nuevo aspirante para el puesto de $puesto. Favor de acceder al sistema para ver el detalle de la misma";
		}
	 
	}
	
	$para      = 'davidgro1982@nube.unadmexico.mx';
	$cabeceras = 'From: webmaster@recursoshumanoshvx.com.mx' . "\r\n" .
    'Reply-To: webmaster@recursoshumanoshvx.com.mx' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();



	if ($ejecuta==1) 
	{   
	if (mysql_query($query))
	{
	  	$mensaje="Se ha guardado la solicitud";
	  	$status="OK";
	  	if (mail($para, $titulo, $cuerpo, $cabeceras))
        {
    	$mensaje.=" y se ha enviado un correo electrónico a los involucrados";
        }
        if (@$_FILES['cv']['tmp_name'])
        {
        	if (move_uploaded_file($_FILES['cv']['tmp_name'], $archivo_subir)) 
        	{
			    $mensaje.= ". El archivo es válido y se subió con éxito.\n";
			} else {
			    $mensaje.= ". Pero hubo un error al subir el archivo $archivo_subir\n";
			}
        }
	}
	else
	{
		$mensaje=mysql_error();
	}
    }

	

    
	


$data['mensaje']=$mensaje;
$data['status']=$status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($data);

?>