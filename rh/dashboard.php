<?php

session_start();
require_once("conecta.php");
?>

<?php
include "cabecera.php";
$padding=10;
?>

<?php
$ancho=0;
if ($_SESSION['rol']=='2' || $_SESSION['rol']=='3')
{
 $ancho=100/4;
}
else if ($_SESSION['rol']=='1')
{
 $ancho=100/6;
}
else if ($_SESSION['rol']=='4')
{
 $ancho=100/5;
}
if ($_SESSION['rol']=='5')
{
 $ancho=100/3;
}
?>

<style>
 .casilla
 {
  width: <?php echo $ancho;?>%;
  float: left;
  height: 100%
  
 }
 
 .casilla:hover {
  background-color: #345586;
}
 
</style>


 <center><h4>Reclutamiento y selección</h4></center>
</div>
<div class="contenedorIconos" style="background:#517FC3; width:100%; height: 130px;  margin-top: 8%" >

<div class="casilla">
 <a class="liga" href="dashboard.php">
  <div class="">
        <span class="ico"><center><img src="img/home.png" width=50px" style="padding-top: 10%;"><br><br>Inicio<br><br></center></span>
  </div>
 </a></div>


 <?php
 if ($_SESSION['rol']=='1' || $_SESSION['rol']=='4' || $_SESSION['rol']=='5') {?>
 <div class="casilla">
  <a href="solicitudes.php">
  <div class="">
        <span class="ico"><center><img src="img/SolicitudPersonal.png" width=48px" style="padding-top: 10%;"><br><br>Solicitar personal<br><br></center></span>
  </div>
  </a>
 </div>
 <?php } ?>
 
 




 
<?php
  if ($_SESSION['rol']=='1' || $_SESSION['rol']=='2' || $_SESSION['rol']=='4') {
    $query="select count(*) as cuantos from Vacantes where status=1";
  $resultado=mysql_query($query);
  $fila=mysql_fetch_array($resultado);
  $conteo=(int)$fila['cuantos']; ?>
   <div class="casilla">
  <a href="autorizaciones.php">
  <div class="" <?php if ($conteo>0) {echo "style='background-color:#ff0000 !important'";}?>>

        <span class="ico"><center><?php if ($conteo>0) {echo $conteo;}?><img src="img/autorizar.ico" width=48px" style="padding-top: 10%;"><br><br>Autorizaciones<br><br></center></span>
  </div>
  </a>
  </div>

   <?php } ?>
   
   
  <?php
  if ($_SESSION['rol']=='1' || $_SESSION['rol']=='2' || $_SESSION['rol']=='3') {
     $query="select count(*) as cuantos from Candidatos where status=1";
  $resultado=mysql_query($query);
  $fila=mysql_fetch_array($resultado);
  $conteo=(int)$fila['cuantos']; ?>
  <div class="casilla">
 <a href="procesos.php">
  <div class="" <?php if ($conteo>0) {echo "style='background-color:#ff0000 !important'";}?>
        <span class="ico"><center><?php if ($conteo>0) {echo $conteo;}?><img src="img/reclutamiento.png" width="48px" style="padding-top: 10%;"><br><br>Procesos<br><br></center></span>
  </div>
 </a>
 </div>
  <?php } ?>
  
  <?php
  if ($_SESSION['rol']=='1' || $_SESSION['rol']=='3' || $_SESSION['rol']=='4') {
    $query="select count(*) as cuantos from Candidatos where status=4";
  $resultado=mysql_query($query);
  $fila=mysql_fetch_array($resultado);
  $conteo=(int)$fila['cuantos']; ?>
  <div class="casilla">
 <a href="aceptaciones.php">
  <div class="" <?php if ($conteo>0) {echo "style='background-color:#ff0000 !important'";}?>>

        <span class="ico"><center><?php if ($conteo>0) {echo $conteo;}?><img src="img/seleccion.png" width=48px" style="padding-top: 10%;"><br><br>Aceptaciones<br><br></center></span>

  </div>
 </a>
  </div>
  <?php } ?>
  
    <div class="casilla">
 <a href="logout.php">
  <div class="">
        <span class="ico"><center><img src="img/salir.svg" width=48px" style="padding-top: 10%;"><br><br>Cerrar sesión<br><br></center></span>
  </div>
 </a>
 </div>



    </div>



</body>
</html>


