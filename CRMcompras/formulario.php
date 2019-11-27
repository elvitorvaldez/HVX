<?php
  session_start();
  require_once("conecta.php");
  include "cabecera.php";
  include "save.php";
?>

	<style>
  
.datosBase
{
 background: #ccc;
}
	.clear{clear:both;text-align:center}
	.izq{border-radius:10px 0 0 10px !important;}
	.der{border-radius:0 10px 10px 0 !important;}
	.columnaa{float:left;width:40%; text-align: right;}
	.columnab{float:left;width:20%; text-align: center; margin-top: 15%}
	.columnac{float:left;width:40%; text-align: left;}
  .imgIconoMasMenos{width:3%;}
	

    </style>
<script>
 
 function toogleDemo(capa, btn, texto)
{
 var elemento=document.getElementById(capa);
 var boton=document.getElementById(btn);
 var valor=elemento.style.display;
   if (valor=="none")
            {
              elemento.style.display = 'block';
              boton.innerText="Ocultar "+texto;
            }
            else{
             elemento.style.display = 'none';
              boton.innerText="Ver "+texto;
            }     
 
}

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
 
 
</script>



</head>
<body>

<?php
setlocale(LC_MONETARY, 'es_ES');
if ($_SESSION['nombre'])
{
  $idProveedor=$_GET['idProveedor'];

  $query="select Proveedores.*, DatosSAP.* from Proveedores inner join DatosSAP 
  on Proveedores.numProveedor = DatosSAP.Numero
  where numProveedor='$idProveedor'";
  $resultado=mysql_query($query);

  while ($fila = mysql_fetch_assoc($resultado)) {
   @$PaginaWeb=$fila['PaginaWeb'];
   @$TieneFiliales=$fila['TieneFiliales'];
   @$NombresFiliales=$fila['NombresFiliales'];
   @$FechaFundacion=$fila['FechaFundacion'];
   @$Contacto1=$fila['Contacto1'];
   @$Contacto2=$fila['Contacto2'];
   @$Contacto3=$fila['Contacto3'];
   @$Cargo1=$fila['Cargo1'];
   @$Cargo2=$fila['Cargo2'];
   @$Cargo3=$fila['Cargo3'];
   @$Correo1=$fila['Correo1'];
   @$Correo2=$fila['Correo2'];
   @$Correo3=$fila['Correo3'];
   @$ProveedorUnico=$fila['ProveedorUnico'];
   @$ProveedorPrincipalAlterno=$fila['ProveedorPrincipalAlterno'];
   @$ProveedorConfiable=$fila['ProveedorConfiable'];
   @$ProveedorCondicionado=$fila['ProveedorCondicionado'];
   @$CantidadEmpleados=$fila['CantidadEmpleados'];
   @$CantidadClientes=$fila['CantidadClientes'];
   @$VentasAnioAnterior2=$fila['VentasAnioAnterior2'];
   @$VentasAnioAnterior1=$fila['VentasAnioAnterior1'];
   @$FacilmenteSustituible=$fila['FacilmenteSustituible'];
   @$PartRelativaHelvexVentas=$fila['PartRelativaHelvexVentas'];
   @$TasaDeDescuento=$fila['TasaDeDescuento'];
   @$RazonSocial=$fila['RazonSocial'];
   @$RFC=$fila['RFC'];
   @$Calle=$fila['Calle'];
   @$Poblacion=$fila['Poblacion'];
   @$Region=$fila['Region'];
   @$CP=$fila['CP'];
   @$Tel1=$fila['Tel1'];
   @$Tel2=$fila['Tel2'];
   @$TipoMaterialQueVende=$fila['TipoMaterialQueVende'];
   @$PerteneceaGrupoEmpresarial=$fila['PerteneceaGrupoEmpresarial'];
   @$ProductoQueVende=$fila['ProductoQueVende'];
   @$OrdenesCompraAnioAnterior2Cantidad=$fila['OrdenesCompraAnioAnterior2Cantidad'];
   @$OrdenesCompraAnioAnterior1Cantidad=$fila['OrdenesCompraAnioAnterior1Cantidad'];
   @$OrdenesCompraAnioCantidad=$fila['OrdenesCompraAnioCantidad'];
   @$OrdenesCompraAnioAnterior2Importe=$fila['OrdenesCompraAnioAnterior2Importe'];
   @$OrdenesCompraAnioAnterior1Importe=$fila['OrdenesCompraAnioAnterior1Importe'];
   @$OrdenesCompraAnioImporte=$fila['OrdenesCompraAnioImporte'];
   @$Divisa=$fila['Divisa'];
   @$PagosRelizadosAnioAnterior2=$fila['PagosRelizadosAnioAnterior2'];
   @$PagosRelizadosAnioAnterior1=$fila['PagosRelizadosAnioAnterior1'];
   @$PagosRelizadosAnio=$fila['PagosRelizadosAnio'];
   @$PagosPorRealizar=$fila['PagosPorRealizar'];
   @$TieneSistemaCalidad=$fila['TieneSistemaCalidad'];
   @$ProblemasCalidad6Meses=$fila['ProblemasCalidad6Meses'];
	 @$Incrementos=$fila['Incremento'];
	 @$Incremento =explode ("|", $Incrementos);
  }

?>
</br>
<h3>Cat&aacute;logo de Proveedores</h3>
<h4>Datos para el proveedor <?php echo $idProveedor.' - '.$RazonSocial;?></h4>
<form name="Form1" id="Form1" method="POST" enctype="multipart/form-data" action="save.php">
<div class="accordionWrapper">
<div class="accordionItem open" id="ai1">
<h2 class="accordionItemHeading" onclick="toogleAc('ai1');">Datos Generales</h2>
<div class="accordionItemContent">
  Datos base <br>
              <table width="100%" style="text-align: center; border-top: 5px solid #123456; padding-top: 1%; margin-top: 1%;">
              <tr>
                <td><b>Raz&oacute;n Social</b></td>
                <td><input type="text" class="datosBase" name="RazonSocial" id="RazonSocial"
                     placeholder="Linea 1" disabled="disabled" value="<?php if (isset($RazonSocial)) {echo @utf8_decode($RazonSocial);}?>">
                </td>
              </tr>
              <tr>
                <td><b>RFC</b></td>
                <td>
                <input type="text" class="datosBase" disabled="disabled" name="RFC" id="RFC"
                     placeholder="Linea 2" value="<?php if (isset($RFC)) {echo @utf8_decode($RFC);}?>">
              </td>
              </tr>
              <td><b>Calle</b></td>
                <td>
                <input type="text" class="datosBase" disabled="disabled" name="Calle" id="Calle"
                     placeholder="Linea 3" value="<?php if (isset($Calle)) {echo @utf8_decode($Calle);}?>">
                </td>
              </tr>
              <td><b>Poblaci&oacute;n</b></td>
                <td>
                <input type="text" class="datosBase" disabled="disabled" name="Poblacion" id="Poblacion"
                      value="<?php if (isset($Poblacion)) {echo @utf8_decode($Poblacion);}?>">
                </td>
              </tr>
              <td><b>Regi&oacute;n</b></td>
                <td>
                <input type="text" class="datosBase" disabled="disabled" name="Region" id="Region"
                      value="<?php if (isset($Region)) {echo @$Region;}?>">
               </td>
              </tr>
              <td><b>C&oacute;digo Postal</b></td>
                <td>
                <input type="text" class="datosBase" disabled="disabled" name="CP" id="CP"
                      value="<?php if (isset($CP)) {echo @utf8_decode($CP);}?>">
               </td>
              </tr>
              <td><b>Tel&eacute;fono 1</b></td>
                <td>
                <input type="text" class="datosBase" disabled="disabled" name="Tel1" id="Tel1"
                      value="<?php if (isset($Tel1)) {echo @utf8_decode($Tel1);}?>">
               </td>
              </tr>
              <td><b>Tel&eacute;fono 2</b></td>
                <td>
                <input type="text" class="datosBase" disabled="disabled" name="Tel2" id="Tel2"
                      value="<?php if (isset($Tel2)) {echo @utf8_decode($Tel2);}?>">
               </td>
              </tr>
              <td><b>Pertenece a grupo empresarial</b></td>
                <td>
                <input type="text" class="datosBase" disabled="disabled" name="PerteneceaGrupoEmpresarial" id="PerteneceaGrupoEmpresarial" value="<?php if (isset($PerteneceaGrupoEmpresarial)) {echo @utf8_decode($PerteneceaGrupoEmpresarial);}?>">
               </td>
              </tr>
              <td><b>Producto que vende</b></td>
                <td>
                <input type="text" class="datosBase" disabled="disabled" name="ProductoQueVende" id="ProductoQueVende"
                      value="<?php if (isset($ProductoQueVende)) {echo @utf8_decode($ProductoQueVende);}?>">
               </td>
              </tr>
              <td><b>Divisa</b></td>
                <td>
                <input type="text" class="datosBase" disabled="disabled" name="OrdenesCompraAnioImporte" id="OrdenesCompraAnioImporte" value="<?php if (isset($Divisa)) {echo @utf8_decode($Divisa);}?>">
               </td>
              </tr>             
      </table>
</div>
</div>
<div class="accordionItem close" id="ai2">
<h2 class="accordionItemHeading" onclick="toogleAc('ai2');">Datos de Empresa</h2>
<div class="accordionItemContent">

  <input type="hidden" name="idProveedor" id="idProveedor" value="<?php echo $idProveedor;?>">
      <table width="100%" style="text-align: center; padding-top: 1%; margin-top: 1%;">
              <tr>
                <td><b>P&aacute;gina web</b></td>
                <td><input type="text" name="PaginaWeb" id="PaginaWeb"
           value="<?php if (isset($PaginaWeb)) {echo @utf8_decode($PaginaWeb);}?>">
    </td></tr>
    <tr>
     <td><b>Tiene filiales</b></td>
      <td><select name="tienenFiliales" id="tienenFiliales"
            value="<?php if (isset($TieneFiliales)) {echo @utf8_decode($TieneFiliales);}?>">
           <option value="">Seleccione</option>
           <option value="0" <?php if ($TieneFiliales=='0') {echo 'selected';} ?> >No</option>
           <option value="1">Si</option>
      </select>
    </td></tr>
    <tr>
     <td><b>Nombres de filiales</b></td>
      <td><input type="text" name="NombresFiliales" id="NombresFiliales"
           placeholder="Si tiene mas de uno, separe por comas" value="<?php if (isset($NombresFiliales)) {echo @utf8_decode($NombresFiliales);}?>">
    </td></tr>
    <tr><td><b>Fecha de fundaci&oacute;n</b></td>
      <td><input type="date" name="FechaFundacion" id="FechaFundacion"  value="<?php if (isset($FechaFundacion)) {echo @$FechaFundacion;}?>">
    </td></tr>
      </table>
</div>
</div>
<div class="accordionItem close" id="ai3">
<h2 class="accordionItemHeading" onclick="toogleAc('ai3');">Datos de Contacto</h2>
<div class="accordionItemContent">
 Contacto 1 <br>
   <table width="100%" style="text-align: center; padding-top: 1%; border-top: 5px solid #123456; margin-top: 1%;">
                 <tr>
                   <td><b>Nombre 1er contacto</b></td>
                   <td><input type="text" name="Contacto1" id="Contacto1"
           value="<?php if (isset($Contacto1)) {echo @utf8_decode($Contacto1);}?>">
       </td></tr>
       <tr>
        <td><b>Cargo 1er contacto</b></td>
         <td><input type="text" name="Cargo1" id="Cargo1"
           value="<?php if (isset($Cargo1)) {echo @utf8_decode($Cargo1);}?>">
       </td></tr>
       <tr>
        <td><b>Correo 1er contacto</b></td>
         <td><input type="email" name="Correo1" id="Correo1" value="<?php if (isset($Correo1)) {echo @utf8_decode($Correo1);}?>">
       </td></tr>       
   </table>
    <br></vr>Contacto 2 <br>
   <table width="100%" style="text-align: center; padding-top: 1%; border-top: 5px solid #123456; margin-top: 1%;">
                 <tr>
                   <td><b>Nombre 2o contacto</b></td>
                   <td><input type="text" name="Contacto2" id="Contacto2"
           value="<?php if (isset($Contacto2)) {echo @utf8_decode($Contacto2);}?>">
       </td></tr>
       <tr>
        <td><b>Cargo 2o contacto</b></td>
         <td><input type="text" name="Cargo2" id="Cargo2"
           value="<?php if (isset($Cargo2)) {echo @utf8_decode($Cargo2);}?>">
       </td></tr>
       <tr>
        <td><b>Correo 2o contacto</b></td>
         <td><input type="email" name="Correo2" id="Correo2" value="<?php if (isset($Correo2)) {echo @utf8_decode($Correo2);}?>">
       </td></tr>       
   </table>
   <br> Contacto 3 <br>
   <table width="100%" style="text-align: center; padding-top: 1%; border-top: 5px solid #123456; margin-top: 1%;">
                 <tr>
                   <td><b>Nombre 3er contacto</b></td>
                   <td><input type="text" name="Contacto3" id="Contacto3"
           value="<?php if (isset($Contacto3)) {echo @utf8_decode($Contacto3);}?>">
       </td></tr>
       <tr>
        <td><b>Cargo 3er contacto</b></td>
         <td><input type="text" name="Cargo3" id="Cargo3"
           value="<?php if (isset($Cargo3)) {echo @utf8_decode($Cargo3);}?>">
       </td></tr>
       <tr>
        <td><b>Correo 3er contacto</b></td>
         <td><input type="email" name="Correo3" id="Correo3" value="<?php if (isset($Correo3)) {echo @utf8_decode($Correo3);}?>">
       </td></tr>       
   </table>
   
</div>
</div>

<div class="accordionItem close" id="ai4">
<h2 class="accordionItemHeading" onclick="toogleAc('ai4');">Datos Financieros</h2>
<div class="accordionItemContent">
       Ventas     <br>
     <table width="100%" style="text-align: center; padding-top: 1%; border-top: 5px solid #123456; margin-top: 1%;">
         <tr>
             <td><b>Ventas a&ntilde;o anterior (2)</b></td>
             <td>
                 <input type="number" min=0 name="VentasAnioAnterior2" id="VentasAnioAnterior2" value="<?php if (isset($VentasAnioAnterior2) and trim($VentasAnioAnterior2)!=" ") {echo @$VentasAnioAnterior2;}?>">
             </td>
         </tr>
         <tr>
             <td><b>Ventas a&ntilde;o anterior (1)</b></td>
             <td>
                 <input type="number" min=0 name="VentasAnioAnterior1" id="VentasAnioAnterior1" value="<?php if (isset($VentasAnioAnterior1)  and trim($VentasAnioAnterior2)!=" ") {echo @$VentasAnioAnterior1;}?>">
             </td>
         </tr>
         <tr>
             <td><b>Particip. Relativa Hvx Vtas</b></td>
             <td>
                 <input type="number" name="PartRelativaHelvexVentas" id="PartRelativaHelvexVentas" value="<?php if (isset($PartRelativaHelvexVentas)) {echo @$PartRelativaHelvexVentas;}?>">
             </td>
         </tr>
         <tr>
             <td><b>Tasa de descuento</b></td>
             <td>
                 <input type="number" name="TasaDeDescuento" id="TasaDeDescuento" value="<?php if (isset($TasaDeDescuento)) {echo @$TasaDeDescuento;}?>">
             </td>
         </tr>
         <tr>
             <td><b>Tiene sistema de calidad certificado</b></td>
             <td>
                 <select name="TieneSistemaCalidad" id="TieneSistemaCalidad">
                     <option value="">Seleccione</option>
                     <option value="Si" <?php if (@$TieneSistemaCalidad=='Si' ) {echo 'selected';} ?> >Si</option>
                     <option value="No" <?php if (@$TieneSistemaCalidad=='No' ) {echo 'selected';} ?> >No</option>
                 </select>
             </td>
         </tr>
         <tr>
             <td><b>Problemas de calidad en los &uacute;ltimos 6 meses</b></td>
             <td>
                 <select name="ProblemasCalidad6Meses" id="ProblemasCalidad6Meses">
                     <option value="">Seleccione</option>
                     <option value="Si" <?php if (@$ProblemasCalidad6Meses=='Si' ) {echo 'selected';} ?> >Si</option>
                     <option value="No" <?php if (@$ProblemasCalidad6Meses=='No' ) {echo 'selected';} ?> >No</option>
                 </select>
             </td>
         </tr>
     </table>
     
     <!--<br>&Oacute;rdenes de compra
     <br>
     <table width="100%" style="text-align: center; padding-top: 1%; border-top: 5px solid #123456; margin-top: 1%;">
         <tr>
             <td><b>Cantidad de &Oacute;rdenes de compra a&ntilde;o <?php echo date('Y')-2;?></b></td>
             <td>
                 <input type="text" name="OrdenesCompraAnioAnterior2Cantidad" id="OrdenesCompraAnioAnterior2Cantidad" value="<?php if (isset($OrdenesCompraAnioAnterior2Cantidad)) {echo @$OrdenesCompraAnioAnterior2Cantidad;}?>">
             </td>
         </tr>
         <tr>
             <td>
                 <b>Importe de &oacute;rdenes de compra a&ntilde;o <?php echo date('Y')-2;?></b></td>
             <td>
                 <input type="text" name="OrdenesCompraAnioAnterior2Importe" id="OrdenesCompraAnioAnterior2Importe" placeholder="Linea 10" value="<?php if (isset($OrdenesCompraAnioAnterior2Importe)) {echo @$OrdenesCompraAnioAnterior2Importe;}?>">
             </td>
         </tr>
         <tr>
             <td><b>Cantidad de &Oacute;rdenes de compra a&ntilde;o <?php echo date('Y')-1;?></b></td>
             <td>
                 <input type="text" name="OrdenesCompraAnioAnterior1Cantidad" id="OrdenesCompraAnioAnterior1Cantidad" value="<?php if (isset($OrdenesCompraAnioAnterior1Cantidad)) {echo @$OrdenesCompraAnioAnterior1Cantidad;}?>">
             </td>
         </tr>
         <tr>
             <td><b>Importe de &oacute;rdenes de compra a&ntilde;o <?php echo date('Y')-1;?></b></td>
             <td>
                 <input type="text" name="OrdenesCompraAnioAnterior1Importe" id="OrdenesCompraAnioAnterior1Importe" placeholder="Linea 10" value="<?php if (isset($OrdenesCompraAnioAnterior1Importe)) {echo @$OrdenesCompraAnioAnterior1Importe;}?>">
             </td>
         </tr>
         <tr>
             <td><b>Cantidad de &Oacute;rdenes de compra a&ntilde;o Actual</b></td>
             <td>
                 <input type="text" name="OrdenesCompraAnioCantidad" id="OrdenesCompraAnioCantidad" value="<?php if (isset($OrdenesCompraAnioCantidad)) {echo @$OrdenesCompraAnioCantidad;}?>">
             </td>
         </tr>
         <tr>
             <td><b>Importe de &oacute;rdenes de compra a&ntilde;o actual</b></td>
             <td>
                 <input type="text" name="datosTecnologia" id="datosTecnologia10" placeholder="Linea 10" value="<?php if (isset($OrdenesCompraAnioImporte)) {echo $OrdenesCompraAnioImporte;}?>">
             </td>
         </tr>
     </table>
     
     <br>Pagos
     <br>
     
     <table width="100%" style="text-align: center; padding-top: 1%; border-top: 5px solid #123456; margin-top: 1%;">
         <tr>
             <td><b>Pagos realizados A&ntilde;o anterior 2</b></td>
             <td>
                 <input type="text" name="PagosRelizadosAnioAnterior2" id="PagosRelizadosAnioAnterior2" placeholder="Linea 10" value="<?php if (isset($PagosRelizadosAnioAnterior2)) {echo @$PagosRelizadosAnioAnterior2;}?>">
             </td>
         </tr>
         <tr>
             <td><b>Pagos realizados A&ntilde;o anterior 1</b></td>
             <td>
                 <input type="text" name="PagosRelizadosAnioAnterior1" id="PagosRelizadosAnioAnterior1" value="<?php if (isset($PagosRelizadosAnioAnterior1)) {echo @$PagosRelizadosAnioAnterior1;}?>">
             </td>
         </tr>
         <tr>
             <td><b>Pagos por realizar</b></td>
             <td>
                 <input type="text" name="PagosPorRealizar" id="PagosPorRealizar" value="<?php if (isset($PagosPorRealizar)) {echo @$PagosPorRealizar;}?>">
             </td>
         </tr>-->
     </table>


     
</div>
</div>

<div class="accordionItem close" id="ai5">
<h2 class="accordionItemHeading" onclick="toogleAc('ai5');">Datos del tipo de proveedor</h2>
<div class="accordionItemContent">
  <table width="100%" style="text-align: center; padding-top: 1%; border-top: 5px solid #123456; margin-top: 1%;">
     <tr>
        <td><b>Proveedor &uacute;nico</b></td>
        <td>
           <select name="ProveedorUnico" id="ProveedorUnico"
              value="<?php if (isset($ProveedorUnico)) {echo @utf8_decode($ProveedorUnico);}?>">
              <option value="">Seleccione</option>
              <option value="0" <?php if ($ProveedorUnico=='0') {echo 'selected';} ?> >No</option>
              <option value="1"<?php if ($ProveedorUnico=='1') {echo 'selected';} ?> >Si</option>
           </select>
        </td>
     <tr> 
     <tr>
        <td>
           <b>Proveedor principal alterno</b>
        </td>
        <td>
           <select name="ProveedorPrincipalAlterno" id="ProveedorPrincipalAlterno"
              value="<?php if (isset($ProveedorPrincipalAlterno)) {echo @utf8_decode($ProveedorPrincipalAlterno);}?>">
              <option value="">Seleccione</option>
              <option value="0" <?php if ($ProveedorPrincipalAlterno=='0') {echo 'selected';} ?> >No</option>
              <option value="1"<?php if ($ProveedorPrincipalAlterno=='1') {echo 'selected';} ?> >Si</option>
           </select>
        </td>
     <tr> 
     <tr>
        <td><b>Proveedor confiable</b></td>
        <td>
           <select name="ProveedorConfiable" id="ProveedorConfiable"
              value="<?php if (isset($ProveedorConfiable)) {echo @utf8_decode($ProveedorConfiable);}?>">
              <option value="">Seleccione</option>
              <option value="0" <?php if ($ProveedorConfiable=='0') {echo 'selected';} ?> >No</option>
              <option value="1"<?php if ($ProveedorConfiable=='1') {echo 'selected';} ?> >Si</option>
           </select>
        </td>
     <tr> 
     <tr>
        <td><b>Proveedor condicionado</b></td>
        <td>
           <select name="ProveedorCondicionado" id="ProveedorCondicionado"
              value="<?php if (isset($ProveedorCondicionado)) {echo @utf8_decode($ProveedorCondicionado);}?>">
              <option value="">Seleccione</option>
              <option value="0" <?php if ($ProveedorCondicionado=='0') {echo 'selected';} ?> >No</option>
              <option value="1"<?php if ($ProveedorCondicionado=='1') {echo 'selected';} ?> >Si</option>
           </select>
        </td>
     <tr> 
     <tr>
        <td>   
           <b>Facilidad o Posibilidad de sustituir al proveedor</b>
        </td>
        <td>
           <select name="FacilmenteSustituible" id="FacilmenteSustituible">
              <option value="">Seleccione</option>
              <option value="Alta" <?php if (@$FacilmenteSustituible=='Alta') {echo 'selected';} ?> >Alta</option>
              <option value="Media" <?php if (@$FacilmenteSustituible=='Media') {echo 'selected';} ?>>Media</option>
              <option value="Nula" <?php if (@$FacilmenteSustituible=='Nula') {echo 'selected';} ?>>Nula</option>
           </select>
        </td>
     <tr> 
     <tr>
        <td><b>Cantidad de empleados</b></td>
        <td>
           <input type="number" min=0 name="CantidadEmpleados" id="CantidadEmpleados" value="<?php if (isset($CantidadEmpleados)) {echo @$CantidadEmpleados;}?>">
        </td>
     <tr> 
     <tr>
        <td><b>Cantidad de clientes</b></td>
        <td>
           <input type="number" min=0 name="CantidadClientes" id="CantidadClientes" value="<?php if (isset($CantidadClientes)) {echo @$CantidadClientes;}?>">
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
  <input type="hidden" name="operacion" value="guardarDatos">
  <button id="Guardar" type="submit">Guardar</button>
  <!--<button id="Cancelar" type="button">Cancelar</button>-->
</form>
  
</div>

<br><br>

<div class="historialContainer">

<div id="c1" style="width: 49%; float: left;">
 
 <?php $url=$_SERVER['REQUEST_URI'];
$partes=explode("/",$url);
$action=$partes[sizeof($partes)-1];
//echo $action;
?>
        <h4>Agregar registro de historial</h4>        

<form name="FormHistorial" id="FormHistorial" onsubmit="agregarHistorial();" method="POST" enctype="multipart/form-data" action="<?php echo $action; ?>">
<table>
 <tr>
  <td><b>Fecha de negociacion</b></td>
  <td><input style="width:90%;" type="date" class="hh" required name="hhfechaNegociacion" id="hhfechaNegociacion">
      <input style="width:90%;" type="hidden" class='hh' value="<?php echo $_GET['idProveedor'];?>" name="hhidProveedor" id="hhidProveedor">
 </td>
 </tr>
 
  <tr>
  <td><b>Descripci&oacute;n</b></td>
  <td><input type="text" class="hh" required name="hhdescripcionHistorial" id="hhdescripcionHistorial">
 </td>
 </tr>
  
   <tr>
  <td><b>C&eacute;dula</b></td>
  <td><input type="file" class="hh" required name="hhcedula" id="hhcedula"></td>
 </tr>
    
 </table><br>
		<p id="ash"></p>
	
 

    
        
				<button name="botonHistorial" id="botonHistorial" type="submit" class="btn btn-primary">Guardar</button>
				<button type="button" name="botonCancelarHistorial" id="botonCancelarHistorial" onclick="cancelarHistorial();" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
    <input type="hidden" name="operacion" value="guardarHistorial">

	</form>







  


</div>



<div id="c2" style="width: 49%; float: left;">

<div class="form-group">
 
          
    <br>
        
    <br>
    <br>
  <div id="demo" style="display: none">
    <table id="TablaMateriales" class="table table-striped">
       <thead>
      <tr>
        <td>Id Material</td><td>Descripci&oacute;n</td>
      </tr>
       </thead>
       <tbody>
    <?php.
    $query="select Material, Descripcion from Materiales where Proveedor like '%".$idProveedor."%'";
    $resultado=mysql_query($query);

  while ($fila = mysql_fetch_assoc($resultado)) {
     ?><tr>
        <td><?php echo $fila['Material'];?></td><td><?php echo utf8_encode($fila['Descripcion']);?></td>
      </tr>
      <?
    }
    ?>
    </tbody>
 </table>
 </div>

<button type="button" style="width:25% !important" id="btnHistorial" onclick="toogleDemo('demo2','btnHistorial','Historial de Negociaciones');">Mostrar  historial de negociaciones</button>

  </div>
  </div>

<!--Tabla oculta-->

<div id="demo2" class="datagrid" style="display: none; width:50%; float: left; margin-top: 2%;">
 
                    <table>
                       <thead>
                      <tr>
                        <th>Fecha de negociaci&oacute;n</th><th>Descripci&oacute;n</th><th>C&eacute;dula</th><th></th>
                      </tr>
                       </thead>
                       <tbody>
                    <?php.
                    $query="select * from HistorialNegociaciones where numProveedor like '%".$idProveedor."%'and activo=1";
                    $resultado=mysql_query($query);

                  while ($fila = mysql_fetch_assoc($resultado)) {
                   $idHistorial=$fila['idHistorial'];
                     ?><tr>
                     <form name="FormBorraHistorial<?php echo $idHistorial;?>" action="save.php" method="POST">
                    
                        <td>
                          <input type="hidden" name="operacion" value="borrarHistorial">
                          <input type="hidden" name="idHistorial" value="<?php echo $idHistorial;?>">
                          <?php echo $fila['fechaNegociacion'];?>
                        </td>
                        <td><?php echo utf8_decode($fila['descripcion']);?></td>
                        <td><a target="_blank" href="uploads/historiales/<?php echo $fila['cedula'];?>"><?php echo $fila['cedula'];?></a></td>
                        <td>
                         <input type="hidden" name="idProveedor" value="<?php echo $idProveedor;?>"> 
                         <a href="#"><button type="submit" name="botonBorrarHistorial" id="botonBorrarHistorial" onclick="BorrarHistorial();">Borrar</button></a></td>
                     </form> 
                     </tr>
                      <?
                    }
                    ?>
                    </tbody>
                 </table>
<br>


</div>
<!-- fin tabla oculta -->

</div>

</div>

<br>


	

<script language="JavaScript">

function agregarHistorial()
{

 var fecha=document.getElementById("hhfechaNegociacion").value;
 var descripcion=document.getElementById("hhdescripcionHistorial").value;
 var archivo=document.getElementById("hhcedula").value;
 if(fecha.trim() =="" || descripcion.trim() =="" || archivo.trim()=="")
 {
  alert("Favor de llenar todos los datos y cargar un archivo de evidencia");
  return 0;
 }
 //alert("se supone que va a hacer submit");          
}


function BorrarHistorial()
{

 var r=confirm("Esta seguro de eliminar este registro de Historial?");
if (r==false)
 {
  return 0;
 }
 //alert("se supone que va a hacer submit");          
}



function cancelarHistorial()
{
 document.getElementById("FormHistorial").reset();
}


   /* $(document).ready(function() {
			




        $("#Form1").submit(function(event) {
            event.preventDefault();

            var parametros=new FormData($(this)[0]);
						parametros.append('operacion',"");
            $.ajax({
                    type: "POST",
                    url: "save.php",
                    data: parametros,
                    contentType: false,
                    processData: false
                    
                })
                .done(function(datos) {   
                var data = JSON.parse(datos);               
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

                    $(".datosBase").attr('disabled', 'disabled');
                    $("#Editar").removeAttr('disabled');
                    $("#Cancelar").attr('disabled', 'disabled');
                    $("#Guardar").attr('disabled', 'disabled');
										$("#Incremento1").attr('disabled', 'disabled');
										$("#Incremento2").attr('disabled', 'disabled');
										$("#Incremento3").attr('disabled', 'disabled');
										$("#Incremento4").attr('disabled', 'disabled');
										$("#Incremento5").attr('disabled', 'disabled');		
                     $('#mensaje').empty(); 
                     $('#mensaje').append(data.mensaje); 
                     $('#status').empty(); 
                     $('#status').append(data.status + "!"); 
                     $('#alerta').show(1000);
                         //  setTimeout(function(){  $('#alerta').hide(1000); }, 3000);
                         location.reload();
                });
        });

				




    });*/
</script>
<?php
 } 
 else {
header('Location: index.php');
exit;
 }
 ?>


	
      

</body>
</html>


