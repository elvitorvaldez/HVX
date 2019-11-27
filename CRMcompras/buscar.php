<?php
session_start();
require_once("conecta.php");

include "cabecera.php";
?>

<head>
 
<script type="text/javascript">
Paginador = function(divPaginador, tabla, tamPagina)
{
    this.miDiv = divPaginador; //un DIV donde irán controles de paginación
    this.tabla = tabla;           //la tabla a paginar
    this.tamPagina = tamPagina; //el tamaño de la página (filas por página)
    this.pagActual = 1;         //asumiendo que se parte en página 1
    this.paginas = Math.floor((this.tabla.rows.length - 1) / this.tamPagina); //¿?
    this.SetPagina = function(num)
    {
     
        if (num < 0 || num > this.paginas)
            return;
 
        this.pagActual = num;
        document.getElementById("paginas").innerHTML = "Página "+ this.pagActual + " de "+ this.paginas;
        var min = 1 + (this.pagActual - 1) * this.tamPagina;
        var max = min + this.tamPagina - 1;
 
        for(var i = 1; i < this.tabla.rows.length; i++)
        {
            if (i < min || i > max)
                this.tabla.rows[i].style.display = 'none';
            else
                this.tabla.rows[i].style.display = '';
        }
        this.miDiv.firstChild.rows[0].cells[1].innerHTML = this.pagActual;
       
    }
 
    this.Mostrar = function()
    {
        //Crear la tabla
        var tblPaginador = document.createElement('table');
 
        //Agregar una fila a la tabla
        var fil = tblPaginador.insertRow(tblPaginador.rows.length);
 
        //Ahora, agregar las celdas que serán los controles
        var ant = fil.insertCell(fil.cells.length);
        ant.innerHTML = 'Anterior';
        ant.className = 'pag_btn'; //con eso le asigno un estilo
          var self = this;
        
        ant.onclick = function()
        {
            if (self.pagActual == 1)
                return;
            self.SetPagina(self.pagActual - 1);
        } 
 
        var num = fil.insertCell(fil.cells.length);
        num.innerHTML = ''; //en rigor, aún no se el número de la página
        num.className = 'pag_num';
 
        var sig = fil.insertCell(fil.cells.length);
        sig.innerHTML = 'Siguiente';
        sig.className = 'pag_btn';
        sig.id = 'pag_btn';
        sig.onclick = function()
        {
            if (self.pagActual == self.paginas)
                return;
            self.SetPagina(self.pagActual + 1);
        }
 
        //Como ya tengo mi tabla, puedo agregarla al DIV de los controles
        this.miDiv.appendChild(tblPaginador);
 
        //¿y esto por qué?
        if (this.tabla.rows.length - 1 > this.paginas * this.tamPagina)
            this.paginas = this.paginas + 1;
 
        this.SetPagina(this.pagActual);
        
    }
}
</script> 
 
 
<style type="text/css">
.pag_btn {
    border: solid 1px;
    border-color: rgb(0, 0, 255);
    color: rgb(0, 0, 0);
    cursor:pointer;
    /*background-color: rgb(255, 255, 255);*/
}
 
.pag_btn_des {
    border: solid 1px;
    border-color: rgb(200, 200, 200);
    color: rgb(200, 200, 200);
    background-color: rgb(245, 245, 245);
}
 
.pag_num {
    border: solid 1px;
    border-color: rgb(0, 0, 255);
    color: rgb(255, 255, 255);
    background-color: rgb(0, 0, 255);
}

table {
   width: 100%;
   border: 1px solid #999;
   color: #333;
   background-color: rgb(200, 200, 200);
}
th, td {
   text-align: center;
   vertical-align: top;
   border: 1px solid #000;
   border-spacing: 0;
}
table tr:nth-child(odd) {background-color: "PLATINO";}

table tr:nth-child(even) {background-color: #9FA8DA;}
</style>


</head>
   <br>  <center><h4>Buscar proveedor</h4></center>

   
<div class="datagrid" style="margin-left: 20%; margin-right: 20%"><table>
<thead><tr><th>Criterios de búsqueda</th></tr></thead>
<tbody>
<tr class="alt">
  <td>
   <form name="buscar" method="POST" action="buscar.php">
    <table style="border: hidden !important">
     <tr style="border: hidden !important">
      <td style="border: hidden !important"><b>ID Proveedor </b></td><td><input type="text" name="idProveedor"></td>
     </tr>
     <tr>
      <td style="border: hidden !important"><b>Nombre Proveedor </b></td><td><input type="text" name="nombreProveedor"></td>
     </tr>
     <tr style="border: hidden !important">
      <input type="hidden" name="elSubmit" value="submit">
      <td colspan=2><button type="submit">Buscar</button></td>
     </tr>
    </table>
    </form>
</td>
</tr>
</table>
</div>
   

   






<?php
if (isset($_POST['elSubmit']))
{
 $idProveedor=@trim($_POST['idProveedor']);
 $nombreProveedor=@trim($_POST['nombreProveedor']);
 if ($idProveedor!="" && $nombreProveedor!="")
 {
  $query="select * from DatosSAP where Numero = '$idProveedor' and RazonSocial like '%$nombreProveedor%'";
 }
 else if ($idProveedor!="" && $nombreProveedor=="")
 {
  $query="select * from DatosSAP where Numero = '$idProveedor'";
 }
  else if ($idProveedor=="" && $nombreProveedor!="")
 {
   $query="select * from DatosSAP where RazonSocial like '%$nombreProveedor%' ";
 }
  else if ($idProveedor=="" && $nombreProveedor=="")
 {
  echo "<center>No ha especificado un criterio de búsqueda</center>";
  die();
 }

$resultado=mysql_query($query);
?>



 <div id="contenedorTabla">     
<div id="paginas">&nbsp;</div>
<div id="paginador">
<table id="tblDatos" border="1" align="center" cellspacing="0" width="100%">  
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Clave proveedor</th>
      <th scope="col">Raz&oacute;n social</th>
      <th scope="col">Poblaci&oacute;n</th>
      <th scope="col">Regi&oacute;n</th>
      <th scope="col">Ver</th>
      <?php if ($_SESSION['admin']==1) { ?>
      <th scope="col">Editar</th>
      <th scope="col">Eliminar</th>
      <?php }?>
    </tr>
  </thead>
 <tbody>

<?php  
 $i=1;
  while ($fila = mysql_fetch_assoc($resultado)) {    
  ?>
    <tr>
      <th scope="row"><?php echo $i; $i++?></th>
      <td><?php echo $fila['Numero'];?></td>
      <td><?php echo utf8_encode($fila['RazonSocial']);?></td>
      <td><?php echo utf8_encode($fila['Poblacion']);?></td>
      <td><?php echo utf8_encode($fila['Region']);?></td>
      <td scope="col"><a href="ver.php?idProveedor=<?php echo $fila['Numero'];?>" class="btn btn-info"><img src="../images/ver.svg" width="20px"></a></td>
      <?php if ($_SESSION['admin']==1) { ?>
      <td scope="col"><a href="formulario.php?idProveedor=<?php echo $fila['Numero'];?>" class="btn btn-info"><img src="../images/editar.svg" width="20px"></a></td>
      <td scope="col"><a onclick="borrarProveedor(<?php echo $fila['Numero'];?>)" href="#"><img src="../images/icono-eliminar.png" width="20px"></a></td>
      <?php } ?>
    </tr>
<?php } ?>
  </tbody>
</table>
</div>



        

    

    
</div> <!-- end of wrapper -->


  </div>
  <?php
}

?>

<script type="text/javascript">
var p = new Paginador(
    document.getElementById('paginador'),
    document.getElementById('tblDatos'),
    10
);
p.Mostrar();

function borrarProveedor(idProveedor)
{
 var accion = confirm("¿Está seguro de eliminar el proveedor con id " + idProveedor +"?");
 if (accion == true) {
   window.location="save.php?operacion=borrarProveedor&idProveedor="+idProveedor;
}
}
</script>
   




</body>
</html>


