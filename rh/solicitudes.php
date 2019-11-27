<?php
session_start();
require_once("conecta.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Solicitud de personal</title>
<?php
include "cabecera.php";

?>

</head>
<body>
 <center><h4>Solicitud de personal</h4></center>
<div class="content">
	<div class="formContainer">
  	   <form name="Form1" id="Form1" method="POST" action="solicitudes.php">
	   <label>Departamento:</label>
					<?php
							$query="Select * from Areas";
							$resultado=mysql_query($query);
							?>
				<select name="departamento" id="departamento" required>
					<option value=""> ---------- </option>
	   	<?
							while ($fila=mysql_fetch_array($resultado))
							{
								?>
								<option value="<?php echo utf8_encode($fila['nombre']);?>"><?php echo utf8_encode($fila['nombre']);?></option>
								<?
							}
						?>
	   	</select><br><br>
	   <label>Nombre del puesto: </label> <input type = "text" name="nombre" id="nombre" required><br><br>
	   <label for="descripcion">Descripción de la vacante: </label> <textarea  name="descripcion" id="descripcion" required></textarea> <br><br>
	   <label>Escolaridad: </label> <input type="text" name="escolaridad" id="escolaridad" required> <br><br>
	    <label>Años de experiencia: </label> <select name="experiencia" id="experiencia" required>
	   		<option value=""> ---------- </option>
	   		<option value="Recién egresado"> Recién egresado </option>
	   		<option value="Hasta 1 año"> Hasta 1 año </option>
	   		<option value="De 1 a 2 años"> De 1 a 2 años </option>
	   		<option value="De 2 a 4 años"> De 2 a 4 años </option>
	   		<option value="Más de 4 años"> Más de 4 años </option>
	   	</select><br><br>	
	   <label>Requisitos: </label> <textarea name="requisitos" id="requisitos" required></textarea> <br><br>
	   <label>Conocimientos: </label> <textarea name="conocimientos" id="conocimientos" required></textarea> <br><br>
	   <label>Tipo de puesto: </label> <select name="tipoPuesto" id="tipoPuesto" required>
	   		<option value=""> ---------- </option>
	   		<option value="Becario"> Becario </option>
	   		<option value="Tiempo completo"> Tiempo completo </option>
	   	</select><br><br>
	   <label>Titulado: </label> <select name="titulado" id="titulado" required>
	   		<option value=""> ---------- </option>
	   		<option value="Si"> Si </option>
	   		<option value="No necesariamente"> No necesariamente </option>
	   	</select><br><br>
	   	<input type = "hidden" name="correo" id="correo" value="<?php echo $_SESSION['correo'];?>" >
					<input type = "hidden" name="operacion" id="operacion" value="solicitud"> <br><br>
					<a name="ancla">&nbsp;</a>
						<div class="exito" id="alerta" style="display: none">
   			 <strong><div id="status"></div></strong> <div id="mensaje"></div>
  		 </div>
    	<div style="height: 10px"></div>	   
	    <button type="button" class="boton" onclick="enviarFormulario();">Guardar</button>
	    <a class="boton" href='dashboard.php' class="boton">Regresar</a>
 
</form>
</div>
  </div>
</div>

<script>
	
	function removeClass(elem, clase) {
  elem.className = elem.className.split(' ').filter(function(v) {
     return v!= clase;
   }).join(' ');
}

function addClass(elem, clase) {
  elem.className += ' '+clase;
}
	
	function enviarFormulario()
{
	var departamento= document.getElementById('departamento');
	var nombre = document.getElementById('nombre');
	var descripcion= document.getElementById('descripcion');
	var escolaridad= document.getElementById('escolaridad');
	var experiencia= document.getElementById('experiencia');
	var requisitos= document.getElementById('requisitos');
	var conocimientos= document.getElementById('conocimientos');
	var tipoPuesto= document.getElementById('tipoPuesto');
	var titulado= document.getElementById('titulado');

	if(departamento.value.trim() == "" || nombre.value.trim()=="" || descripcion.value.trim()=="" || escolaridad.value.trim()=="" || experiencia.value.trim()=="" || requisitos.value.trim()=="" || conocimientos.value.trim()=="" || tipoPuesto.value.trim()=="" || titulado.value.trim()=="")
	{
		document.getElementById("alerta").className = document.getElementById("alerta").className.replace(/\exito\b/,'peligro');
		document.getElementById("alerta").style.display="block";
		document.getElementById('mensaje').innerHTML='No se han capturado todos los campos';
  document.getElementById('status').innerHTML='Error!'; 
		return 0;	
	}
	else
	{
	document.getElementById("Form1").submit();
	}
}
</script>

<?php
if ($_POST['operacion']=="solicitud")
{
					
				 $departamento=utf8_decode($_POST['departamento']);
	    $nombre=utf8_decode($_POST['nombre']);
	    $descripcion=utf8_decode($_POST['descripcion']);
	    $escolaridad=utf8_decode($_POST['escolaridad']);
	    $experiencia=utf8_decode($_POST['experiencia']); 
	    $requisitos=utf8_decode($_POST['requisitos']);
	    $conocimientos=utf8_decode($_POST['conocimientos']);
	    $tipoPuesto=utf8_decode($_POST['tipoPuesto']);
	    $titulado=utf8_decode($_POST['titulado']); 
	    $correo=$_POST['correo'];
	    $query= "INSERT INTO Vacantes(Departamento,Puesto,Descripcion,Escolaridad,Experiencia,Requisitos,Conocimientos,TipoPuesto,Titulado,correoSolicitante,Status) ";
					$query.="VALUES ('$departamento','$nombre','$descripcion','$escolaridad','$experiencia','$requisitos','$conocimientos','$tipoPuesto','$titulado','$correo',1)";
	    
					
					
					if (mysql_query($query))
					{
								$mensaje="Se ha guardado la solicitud, en breve será redireccionado";
								$status="OK";
								$para = 'davidgro1982@nube.unadmexico.mx';
					$cabeceras = 'From: webmaster@recursoshumanoshvx.com.mx' . "\r\n" .
    'Reply-To: webmaster@recursoshumanoshvx.com.mx' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
					
					$titulo="Notificación de solicitud de personal";
	    $cuerpo='Se ha hecho una nueva solicitud de personal por parte de departamento de $departamento. Favor de acceder al sistema para ver el detalle de la misma en la sección "Solicitud de personal"';
					if (mail($para, $titulo, $cuerpo, $cabeceras))
					{
										$mensaje.=" y se ha enviado un correo electrónico a los involucrados";
					}
					$divAlerta='removeClass(document.getElementById("alerta"), "peligro"); addClass(document.getElementById("alerta"), "exito"); ';
					}
					else
					{
						$status="Error!";
						$mensaje=mysql_error();
						$divAlerta='document.getElementById("alerta").className = document.getElementById("alerta").className.replace(/\peligro\b/,"exito");';
					}
	
	echo '<script language="javascript">'.$divAlerta.'		
		document.getElementById("alerta").style.display="block";
		document.getElementById("mensaje").innerHTML="'.$mensaje.'";
  document.getElementById("status").innerHTML="'.$status.'";
		document.location.href = "#ancla"

  setTimeout(function(){ location.href ="dashboard.php";}, 2000);
		
	</script>';
}

?>
</body>
</html>







	   
	   