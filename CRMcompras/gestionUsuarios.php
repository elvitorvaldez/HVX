<?php
  session_start();
  require_once("conecta.php");
  include "cabecera.php";
  include "save.php";
?>

	<style>
 .tabla {
   width: 100%;
   border: 1px solid #999;
   color: #333;
   background-color: rgb(200, 200, 200);
}
.tabla th, .tabla td {
   text-align: center;
   vertical-align: top;
   border: 1px solid #000;
   border-spacing: 0;
}

.tabla td{
 font-size: medium;
}

.tabla th{
 background-color: #123456;
 color: #fff !important;
}
.tabla tr:nth-child(odd) {background-color: "PLATINO";}

.tabla tr:nth-child(even) {background-color: #9FA8DA;} 
	


    </style>




</head>
<body>

<?php
setlocale(LC_MONETARY, 'es_ES');
if ($_SESSION['nombre'])
{
 
?>
</br>
<center>
<h3>Cat&aacute;logo de Proveedores</h3>
<h4>Administración de usuarios </h4>
</center>

<div class="accordionWrapper">
 
 <div class="accordionItem open" id="ai0">
<h2 class="accordionItemHeading" onclick="toogleAc('ai0');">Listado de Usuarios</h2>
<div class="accordionItemContent">
 <form name="crearUsuarioForm" method="POST" action="save.php">
       <table class="tabla" width="100%" style="text-align: center; padding-top: 1%; border-top: 5px solid #123456; margin-top: 1%;">                
              <tr>
                <th>Nombre</th>
                <th>Nickname</th>
                <th>Rol</th>               
              </tr>
              <?php
                 $query="select nombre, username, admin from usuarios order by nombre";
                 $resultado=mysql_query($query);              
                 while ($fila = mysql_fetch_assoc($resultado)) {
                  $rol="Usuario de CRM Compras";
                  if ($fila['admin']==1)
                  {
                   $rol="Admistrador del CRM Compras";
                  }
                  ?>              
              <tr>
               <td><b><?= utf8_encode($fila['nombre']); ?></b></td>
                <td><b><?= $fila['username']; ?></b></td>
                <td><b><?= $rol; ?></b></td>  
              </tr>
             <?php } ?>
             
                      
      </table>
              <br>
               
 </form>
</div>
</div>
 
<div class="accordionItem close" id="ai1">
<h2 class="accordionItemHeading" onclick="toogleAc('ai1');">Crear Usuarios</h2>
<div class="accordionItemContent">
 <form name="crearUsuarioForm" method="POST" action="save.php">
               <table width="100%" style="text-align: center; padding-top: 1%; border-top: 5px solid #123456; margin-top: 1%;">                
              <tr>
                <td><b>Nombre</b></td>
                <td><input type="text" name="nombre" id="nombre" required="required" class="formularioAdmin">
                </td>
              </tr>
              <tr>
                <td><b>Nickname</b></td>
                <td>
                <input type="text" name="nickname" id="nickname" required="required" class="formularioAdmin">
              </td>
              </tr>
              <tr>
              <td><b>Contraseña</b></td>
                <td>
                <input type="password" name="contrasenia1" id="contrasenia1" required="required" class="formularioAdmin">
                </td>
              </tr>
               <tr>
              <td><b>Confirmar contraseña</b></td>
                <td>
                <input type="password" name="contrasenia2" id="contrasenia2" required="required" class="formularioAdmin">
                </td>
              </tr>
             <tr>
              <td><b>Permisos</b></td>
                <td>
                 <select name="admin" id="admin" required="required" class="formularioAdmin">
                  <option value="">------ Tipo de usuario -------</option>
                  <option value="0">Usuario de CRM Compras</option>
                  <option value="1">Administrador de CRM Compras</option>
                 </select>
               </td>
              </tr>
                      
      </table>
              <br>
                <input type="hidden" name="operacion" value="crearUsuario">
  <button id="btnCrear" type="submit">Crear Usuario</button>
 </form>
</div>
</div>

  <div class="accordionItem close" id="ai2">
  <h2 class="accordionItemHeading" onclick="toogleAc('ai2');">Cambiar Contraseña</h2>
  <div class="accordionItemContent">
    <form name="cambiarPasswordForm" method="POST" action="save.php">
              <table width="100%" style="text-align: center; padding-top: 1%; margin-top: 1%;">                
              <tr>
                <td><b>Nickname</b></td>
                <td>
                <select name="nickname" id="nickname" required="required" class="formularioAdmin">
                 <option value="">------ Usuario -------</option>
                 <?php $query="select username from usuarios";
                  $resultado=mysql_query($query);
              
                while ($fila = mysql_fetch_assoc($resultado)) {?>
                <option value="<?php echo $fila['username'];?>"><?php echo $fila['username'];?></option>
                <?php } ?>
                </select>
              </td>
              </tr>
              <tr>
              <td><b>Contraseña</b></td>
                <td>
                <input type="password" name="contrasenia1" id="contrasenia1" required="required" class="formularioAdmin">
                </td>
              </tr>
               <tr>
              <td><b>Confirmar contraseña</b></td>
                <td>
                <input type="password" name="contrasenia2" id="contrasenia2" required="required" class="formularioAdmin">
                </td>
              </tr>
            
      </table>
              <br>
                <input type="hidden" name="operacion" value="cambiarPassword">
  <button id="btnCambiarPassword" type="submit">Cambiar contraseña</button>
 </form>
  </div>
  </div>

<div class="accordionItem close" id="ai3">
<h2 class="accordionItemHeading" onclick="toogleAc('ai3');">Editar Permisos</h2>
<div class="accordionItemContent">
<form name="cambiarRolForm" method="POST" action="save.php">
              <table width="100%" style="text-align: center; padding-top: 1%; margin-top: 1%;">                
              <tr>
                <td><b>Nickname</b></td>
                <td>
                <select name="nickname" id="nickname" required="required" class="formularioAdmin">
                 <option value="">------ Usuario -------</option>
                 <?php $query="select username from usuarios";
                  $resultado=mysql_query($query);
              
                while ($fila = mysql_fetch_assoc($resultado)) {?>
                <option value="<?php echo $fila['username'];?>"><?php echo $fila['username'];?></option>
                <?php } ?>
                </select>
              </td>
              </tr>
             <tr>
              <td><b>Permisos</b></td>
                <td>
                 <select name="admin" id="admin" required="required" class="formularioAdmin">
                  <option value="">------ Tipo de usuario -------</option>
                  <option value="0">Usuario de CRM Compras</option>
                  <option value="1">Administrador de CRM Compras</option>
                 </select>
               </td>
              </tr>
            
      </table>
              <br>
                <input type="hidden" name="operacion" value="cambiarRol">
  <button id="btnCambiarRol" type="submit">Cambiar Rol</button>
 </form>
  
</div>
</div>

<div class="accordionItem close" id="ai4">
<h2 class="accordionItemHeading" onclick="toogleAc('ai4');">Eliminar Usuario</h2>
<div class="accordionItemContent">
  <form name="eliminarUsuarioForm" method="POST" action="save.php">
              <table width="100%" style="text-align: center; padding-top: 1%; margin-top: 1%;">                
              <tr>
                <td><b>Nickname</b></td>
                <td>
                <select name="nickname" id="nickname" required="required" class="formularioAdmin">
                 <option value="">------ Usuario -------</option>
                 <?php $query="select username from usuarios";
                  $resultado=mysql_query($query);
              
                while ($fila = mysql_fetch_assoc($resultado)) {?>
                <option value="<?php echo $fila['username'];?>"><?php echo $fila['username'];?></option>
                <?php } ?>
                </select>
              </td>
              </tr>
                        
      </table>
              <br>
                <input type="hidden" name="operacion" value="eliminarUsuario">
  <button id="btnEliminar" type="submit">Eliminar Usuario</button>
 </form>
</div>
</div>


    <div style="height: 10px"></div>


  
</div>

<br><br>



<br>


	

<?php
 } 
 else {
header('Location: index.php');
exit;
 }
 ?>


	<script>
 
function toogleAc(abuelo)
{
  var contenedor=document.getElementById(abuelo);
    var valor=contenedor.className;
    
   if (valor=="accordionItem close")
            {
              contenedor.classList.remove("close");
              contenedor.className = 'accordionItem open';
             
            }
            else{
             contenedor.classList.remove("open");
              contenedor.className = 'accordionItem close';
            
            }     
}
 
 function limpiarFormulario(formulario)
 {
  document.getElementById(formulario).reset();
 }
 
 
</script>
      

</body>
</html>


