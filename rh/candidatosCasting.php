<?php
session_start();
require_once("conecta.php");
$idVacante=$_GET['Id'];
?>

<?php
include "cabecera.php";
$query0="Select Puesto, Departamento from Vacantes where Id=$idVacante and status = 2";
$resultado0=mysql_query($query0);
$fila0 = mysql_fetch_assoc($resultado0);
$puesto=$fila0['Puesto'];
$departamento=$fila0['Departamento'];
$query="Select * from Candidatos where PuestoDeseado='$puesto' and status = 4";
$resultado=mysql_query($query);
?>

<script src="js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="css/jquery.dataTables.min.css">



 <center><h4>Procesar candidatos</h4></center>
</div>
<div class="container" style="background-color: #aabbcc";text-align: center>

    
<div class="row">&nbsp;<br></div>

<table id="TablaAut" class="blueTable">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Nombre</th>
      <th scope="col">Puesto deseado</th>
      <th scope="col">Teléfono Local</th>
      <th scope="col">Celular</th>
      <th scope="col">Escolaridad</th>
      <th scope="col">Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php 
  while ($fila = mysql_fetch_assoc($resultado)) {   ?> 
    <tr>
      <th scope="row"><?php echo $fila['idCandidato'];?></th>
      <td><?php echo utf8_encode($fila['Nombre']); ?></td>
      <td><?php echo utf8_encode($fila['PuestoDeseado']); ?></td>
      <td><?php echo utf8_encode($fila['TelefonoLocal']); ?></td>
      <td><?php echo utf8_encode($fila['Celular']); ?></td>
      <td><?php echo utf8_encode($fila['Escolaridad']); ?></td>
      <td>
        <a target="_blank" href="uploads/<?php echo $fila['CV'];?>">
          <img src="img/ver.svg" width="20px">
        </a>
        <a class="imgAceptar" data-id="<?php echo $fila['idCandidato'];?>" data-puesto="<?php echo $fila['PuestoDeseado'];?>"><img src="img/aceptar.png" width="20px"></a>
        <a class="imgRechazar" data-id="<?php echo $fila['idCandidato'];?>" data-puesto="<?php echo $fila['PuestoDeseado'];?>"><img src="img/rechazar.png" width="20px"></a>
    </tr>
    <?php } ?>
  </tbody>
</table>
<div style="clear: both">&nbsp;</div>
<a href='dashboard.php' class="boton">Regresar</a><br><br>

</div>

    
</div> 


  
<script type = "text/javascript" >
    $(document).ready(function() {
   //     $('#TablaAut').DataTable();

        $(".imgAceptar").click(function(event) {

            var idCandidato = $(this).attr("data-id");
            var puesto = $(this).attr("data-puesto");
            $.ajax({
                    type: "POST",
                    url: "guardar.php",
                    data:{operacion:"contratar", idCandidato: idCandidato, puesto: puesto}
                })
                .done(function(data) {
                    if (data.status == "OK") {
                       // $("#alerta").removeClass("alert-danger");
                       // $("#alerta").addClass("alert-success");
                       location.reload();
                    } else {
                        //$("#alerta").removeClass("alert-success");
                        //$("#alerta").addClass("alert-danger");
                        alert("Error al actualizar status");
                    }


                });
        });



        $(".imgRechazar").click(function(event) {

            var idCandidato = $(this).attr("data-id");
            var puesto = $(this).attr("data-puesto");
            $.ajax({
                    type: "POST",
                    url: "guardar.php",
                    data:{operacion:"rechazar", idCandidato: idCandidato, puesto: puesto}
                })
                .done(function(data) {
                    if (data.status == "OK") {
                       // $("#alerta").removeClass("alert-danger");
                       // $("#alerta").addClass("alert-success");
                       location.reload();
                    } else {
                        //$("#alerta").removeClass("alert-success");
                        //$("#alerta").addClass("alert-danger");
                        alert("Error al actualizar status");
                    }


                });

        });

    });





</script>






      




</body>
</html>


