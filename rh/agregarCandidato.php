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
 <center><h4>Agregar candidato</h4></center>
<div class="content">
	<div class="formContainer">
  	   <form name="FormCandidato" id="FormCandidato" method="POST">	  
	   <label>Nombre del candidato: </label> <input type = "text" name="nombre" id="nombre" required><br><br>
     <?php 
     $query="Select distinct Id, Puesto from Vacantes where Status <> 5";
     $resultado=mysql_query($query);

     ?>
	   <label>Puesto deseado: </label> <select name="puesto" id="puesto" required>
        <option value=""> ---------- </option>
        <?php while ($fila = mysql_fetch_assoc($resultado)) {   ?> 
        <option value="$fila['id']"><?php echo $fila['Puesto']?> </option>
        <?php }?>
      </select><br><br> 
      <label>Telefono Local: </label> <input type="tel" name="telLocal" id="telLocal" required pattern="[0-9]{8}" title="El formato de teléfono local es a 8 dígitos, sin caracteres"> <br><br>
      <label>Celular: </label> <input type="tel" name="celular" id="celular" required pattern="[0-9]{10}" title="El formato de teléfono celular es a 10 dígitos, sin caracteres"> <br><br>
	    <label>Escolaridad: </label> <input type="text" name="escolaridad" id="escolaridad" required> <br><br>
	     <label>Adjuntar CV: </label> <input type="file" name="cv" id="cv" required> <br><br>	  
	   	<input type = "hidden" name="correo" id="correo" value="<?php echo $_SESSION['correo'];?>" <br><br>
	   	 <div class="alert alert-success" id="alerta" style="display: none;">
   			 <strong><div id="status"></div></strong> <div id="mensaje"></div>
  		 </div>
    	<div style="height: 10px"></div>	   
	    <button type="submit">Guardar</button>
	    <a href='dashboard.php' class="boton">Regresar</a>
 
</form>
</div>
  </div>
</div>

<script>
$( document ).ready(function() {
$("#FormCandidato").submit(function(event) {
            event.preventDefault();

var nombre = $("#nombre").val();
var puesto = $("#puesto").val();
var telLocal = $("#telLocal").val();
var celular = $("#celular").val();
var escolaridad = $("#escolaridad").val();
            
            if (nombre.trim()=="" || puesto.trim()=="" || telLocal.trim()=="" || celular.trim()=="" || escolaridad.trim()=="")
              {
              alert("Favor de llenar todos los datos");
              return 0;
            } 



            var puesto = $("#puesto option:selected").text();
            if( document.getElementById("cv").files.length == 0 ){
              alert("Favor de cargar currículum");
              return 0;
            } 
           

            
            var parametros=new FormData($("#FormCandidato")[0]);            
						parametros.append('operacion',"agregarCandidato");
						parametros.append('puesto',puesto);
					
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
                           setTimeout(function(){  $('#alerta').hide(1000); 
                           	$("#FormCandidato")[0].reset();
                           }, 4000);

                });
        });
});
</script>
</body>
</html>







	   
	   