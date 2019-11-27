<?php
session_start();
require_once("conecta.php");
?>

<?php
include "cabecera.php";
$query="Select Id, Departamento, Puesto from Vacantes where Status=2";
$resultado=mysql_query($query);
?>
 <center><h4>Candidatos en proceso</h4></center>
</div>
<div class="container" style="background-color: #aabbcc";text-align: center>

    
<div class="row">&nbsp;<br></div>

<table id="TablaAut" class="blueTable">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Departamento</th>
      <th scope="col">Puesto</th>
      <th scope="col">Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php 
  while ($fila = mysql_fetch_assoc($resultado)) {   ?> 
    <tr>
      <th scope="row"><?php echo $fila['Id'];?></th>
      <td><?php echo utf8_encode($fila['Departamento']); ?></td>
      <td><?php echo utf8_encode($fila['Puesto']); ?></td>
      <td>
        <a href="candidatosCasting.php?Id=<?php echo $fila['Id'];?>" class="btn btn-info">
          <img src="img/ver.svg" width="20px">
        </a>        
    </tr>
    <?php } ?>
  </tbody>
</table>
<div style="clear: both">&nbsp;</div>
<a class="boton" href='dashboard.php' class="btn btn-primary">Regresar</a><br><br>

</div>

    
</div> 







      




</body>
</html>


