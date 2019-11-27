<?php
  session_start();
  require_once("conecta.php");
  include "cabecera.php";
  include "save.php";
?>

	<style>
  
	.clear{clear:both;text-align:center}
	.izq{border-radius:10px 0 0 10px !important;}
	.der{border-radius:0 10px 10px 0 !important;}
	.columnaa{float:left;width:40%; text-align: right;}
	.columnab{float:left;width:20%; text-align: center; margin-top: 15%}
	.columnac{float:left;width:40%; text-align: left;}
  .imgIconoMasMenos{width:3%;}
	
.accordionWrapper{
  
  color: #ffffff; 
  font-size: 18px; 
  font-weight: 400; 
  text-align: center; 
  background: #889ccf; 
  margin: 25px 5% 0 5%; 
  overflow: hidden; 
  padding: 20px; 
  border-radius: 35px 0px 35px 0px; 
  -moz-border-radius: 35px 0px 35px 0px; 
  -webkit-border-radius: 35px 0px 35px 0px; 
  border: 2px solid #5878ca;
}
.accordionItem{
    float:left;
    display:block;
    width:100%;    
    box-sizing: border-box;
    font-family:'Open-sans',Arial,sans-serif;
}
.accordionItemHeading{
    cursor:pointer;
    margin:0px 0px 10px 0px;
    padding:10px;
    background-color:#243E82;
    color:#fff;
    width:100%;
border-radius: 3px;
        box-sizing: border-box;
}

.open .accordionItemContent{
        padding: 20px;
        color: #123456;
    background-color: #abcdef;
    border: 1px solid #ddd;
    width: 100%;
    margin: 0px 0px 10px 0px;
    display:block;
    -webkit-transform: scaleY(1);
	transform: scaleY(1);
    -webkit-transform-origin: top;
	transform-origin: top;
	transition: -webkit-transform 0.4s ease;
	transition: transform 0.4s ease;
	transition: transform 0.4s ease, -webkit-transform 0.4s ease;
        box-sizing: border-box;
}

.open .accordionItemHeading{
    margin:0px;
        -webkit-border-top-left-radius: 3px;
    -webkit-border-top-right-radius: 3px;
    -moz-border-radius-topleft: 3px;
    -moz-border-radius-topright: 3px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    -webkit-border-bottom-right-radius: 0px;
    -webkit-border-bottom-left-radius: 0px;
    -moz-border-radius-bottomright: 0px;
    -moz-border-radius-bottomleft: 0px;
    border-bottom-right-radius: 0px;
    border-bottom-left-radius: 0px;
    background: #123456;
    color: #ffffff;
}

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
<h4>Agregar proveedor </h4>
</center>
<form name="Form1" id="Form1" method="POST" enctype="multipart/form-data" action="save.php">
<div class="accordionWrapper">
<div class="accordionItem open" id="ai1">
<h2 class="accordionItemHeading" onclick="toogleAc('ai1');">Datos Generales</h2>
<div class="accordionItemContent">
  Datos base <br>
              <table width="100%" style="text-align: center; border-top: 5px solid #123456; padding-top: 1%; margin-top: 1%;">
                
              <tr>
                <td><b>Raz&oacute;n Social</b></td>
                <td><input type="text" name="RazonSocial" id="RazonSocial" required="required">
                </td>
              </tr>
              <tr>
                <td><b>RFC</b></td>
                <td>
                <input type="text" name="RFC" id="RFC" required="required">
              </td>
              </tr>
              <td><b>Calle</b></td>
                <td>
                <input type="text" name="Calle" id="Calle" required="required">
                </td>
              </tr>
              <td><b>Poblaci&oacute;n</b></td>
                <td>
                <input type="text" name="Poblacion" id="Poblacion" required="required">
                </td>
              </tr>
              <td><b>Regi&oacute;n</b></td>
                <td>
                <input type="text" name="Region" id="Region" required="required">
               </td>
              </tr>
              <td><b>C&oacute;digo Postal</b></td>
                <td>
                <input type="text" name="CP" id="CP" required="required">
               </td>
              </tr>
              <td><b>Tel&eacute;fono 1</b></td>
                <td>
                <input type="text" name="Tel1" id="Tel1" required="required">
               </td>
              </tr>
              <td><b>Tel&eacute;fono 2</b></td>
                <td>
                <input type="text" name="Tel2" id="Tel2" required="required">
               </td>
              </tr>
              <td><b>Pertenece a grupo empresarial</b></td>
                <td>
                <input type="text" name="PerteneceaGrupoEmpresarial" id="PerteneceaGrupoEmpresarial" required="required">
               </td>
              </tr>
              <td><b>Producto que vende</b></td>
                <td>
                <input type="text" name="ProductoQueVende" id="ProductoQueVende" required="required">
               </td>
              </tr>
              <td><b>Divisa</b></td>
                <td>
                <input type="text" name="Divisa" id="Divisa" required="required">
               </td>
              </tr>             
      </table>
</div>
</div>






   <div class="alert alert-success" id="alerta" style="display: none;">
    <strong><div id="status"></div></strong> <div id="mensaje"></div>
   </div>
    <div style="height: 10px"></div>

  <!--<button class="btn btn-info" id="Editar">Editar</button>-->
  <input type="hidden" name="operacion" value="agregarProveedor">
  <button id="Guardar" type="submit">Guardar</button>
  <a href="listar.php" id="Cancelar" class="boton">Cancelar</a>
</form>
  
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


	
      

</body>
</html>


