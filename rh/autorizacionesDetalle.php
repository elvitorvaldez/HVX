<?php
session_start();
require_once("conecta.php");
$id=$_GET['Id'];
$query="Select * from Vacantes where Id=$id";
$resultado=mysql_query($query);
$fila = mysql_fetch_array($resultado);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Autorización de personal</title>
<?php
include "cabecera.php";
?>

</head>
<body>
<center><h4>Autorización de personal</h4></center>
<div class="content">
	<div class="formContainer">
  		<form method="POST" id="FormAutorizar">
  			<input type = "hidden" name="idSolicitud" id="idSolicitud" value="<?php echo $id;?>">
		    <label>Departamento: </label><?php echo utf8_encode($fila['Departamento']);?><br>
		    <label>Nombre del puesto: </label> <?php echo utf8_encode($fila['Puesto']);?><br>
		    <input type = "hidden" name="puesto" id="puesto" value="<?php echo utf8_encode($fila['Puesto']);?>">
		    <label>Descripción de la vacante: </label> <?php echo utf8_encode($fila['Descripcion']);?><br>
		    <label>Escolaridad: </label><?php echo utf8_encode($fila['Escolaridad']);?><br>
		    <label>Años de experiencia: </label> <?php echo utf8_encode($fila['Experiencia']);?><br>   		
		    <label>Requisitos: </label> <?php echo utf8_encode($fila['Requisitos']);?><br>
		    <label>Conocimientos: </label> <?php echo utf8_encode($fila['Conocimientos']);?><br>
		    <label>Tipo de puesto: </label> <?php echo utf8_encode($fila['TipoPuesto']);?><br>
		    <label>Titulado: </label><?php echo $fila['Titulado'];?><br><br>
		    <input type = "hidden" name="correo" id="correo" value="<?php echo utf8_encode($fila['Correo']);?>">
  		 	<div class="alert alert-success" id="alerta" style="display: none;">
   			 <strong><div id="status"></div></strong> <div id="mensaje"></div>
  			 </div>
             <div class="alert alert-success" id="alerta" style="display: none;">
   			 <strong><div id="status"></div></strong> <div id="mensaje"></div>
  		 </div>
    	<div style="height: 10px"></div>
	   		 <button type="button" name="btnAutorizar" id="btnAutorizar">Autorizar</button>
	   		 <button type="button" id="btnRechazar">Rechazar</button>
	   		 <a href='dashboard.php' class="boton">Regresar</a> 
	   		 &nbsp;&nbsp;<div id="ocultarMotivo" style="display: none;"><label>Motivo de rechazo: </label><input type = "text" name="motivoRechazo" id="motivoRechazo"><button type="button" id="btnRechazar2" class="btn btn-danger">Enviar</button></div>
		</form>
	</div>
</div>

 

</div>

<script type="text/javascript">
$("#btnAutorizar").click(function(event) {
           
            var parametros=new FormData($("#FormAutorizar")[0]);
						parametros.append('operacion',"aceptada");
						
            $.ajax({
                    type: "POST",
                    url: "guardar.php",
                    data: parametros,
                    contentType: false,
                    processData: false
                    
                })
                .done(function(data) {  
                    if (data.status=="OK")
                    {
                      $("#alerta").removeClass("alert-danger");
                       $("#alerta").addClass("alert-success");
                    }
                    else
                    {
                      $("#alerta").removeClass("alert-success");
                       $("#alerta").addClass("alert-danger");
                    }
                   
                     $('#mensaje').empty(); 
                     $('#mensaje').append(data.mensaje); 
                     $('#status').empty(); 
                     $('#status').append(data.status + "!"); 
                     
                     $('#alerta').show(1000);
                           setTimeout(function(){  $('#alerta').hide(1000);  location.href ="autorizaciones.php";}, 3000);

                });
        });


$("#btnRechazar").click(function(event) {
           
           $( "#ocultarMotivo" ).toggle();

        });

$("#btnRechazar2").click(function(event) {
           
           $( ".target" ).toggle();
           if ($( "#motivoRechazo" ).val()=="")
           {
           	$('#mensaje').empty(); 
                     $('#mensaje').append("Favor de especificar un motivo"); 
                     $('#status').empty(); 
                     $('#status').append("UPS!"); 
                     
                     $('#alerta').show(1000);
                           setTimeout(function(){  $('#alerta').hide(1000);  location.href ="autorizaciones.php";}, 3000);
                           return 0;
           }

            var parametros=new FormData($("#FormAutorizar")[0]);
						parametros.append('operacion',"rechazada");
						
            $.ajax({
                    type: "POST",
                    url: "guardar.php",
                    data: parametros,
                    contentType: false,
                    processData: false
                    
                })
                .done(function(data) {  
                    if (data.status=="OK")
                    {
                      $("#alerta").removeClass("alert-danger");
                       $("#alerta").addClass("alert-success");
                    }
                    else
                    {
                      $("#alerta").removeClass("alert-success");
                       $("#alerta").addClass("alert-danger");
                    }
                   
                     $('#mensaje').empty(); 
                     $('#mensaje').append(data.mensaje); 
                     $('#status').empty(); 
                     $('#status').append(data.status + "!"); 
                     
                     $('#alerta').show(1000);
                           setTimeout(function(){  $('#alerta').hide(1000);  location.href ="autorizaciones.php";}, 3000);

                });
        });

</script>
</body>
</html>







	   
	   