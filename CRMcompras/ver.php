<?php
  session_start();
  require_once("conecta.php");
  include "cabecera.php";
?>



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
	 @$DesdeCuandoEsVendedor=$fila['DesdeCuandoEsVendedor'];
	 @$CondicionesPago=$fila['Incremento'];
	 
  }

?>






<div class="">
    <h3><strong>Proveedor: </strong><?php echo $idProveedor.' - '.$RazonSocial;?></h3>


<div class="datagrid"><table>
<thead><tr><th>Datos Generales</th><th>Datos tipo proveedor</th></tr></thead>
<tbody>
<tr class="alt">
  <td> <div>
                  <span><b>Raz&oacute;n Social: </b></span>
                  <span><?php if (isset($RazonSocial)) {echo @utf8_encode($RazonSocial);}?></span>
                </div>
                <div>
                  <span><b>RFC: </b></i></span>
                  <span><?php if (isset($RFC)) {echo @utf8_decode($RFC);}?></span>
                </div>
                <div>
                  <span ><b>Calle: </b></span>
                  <span><?php if (isset($Calle)) {echo @utf8_decode($Calle);}?></span>
                </div>
                <div >
                  <span ><b>Poblaci&oacute;n: </b></span>
                  <span><?php if (isset($Poblacion)) {echo @utf8_encode($Poblacion);}?></span>
                </div>
                <div >
                  <span ><b>Regi&oacute;n: </b></span>
                  <span><?php if (isset($Region)) {echo @utf8_encode($Region);}?></span>
                </div>
                 <div >
                  <span ><b>C&oacute;digo Postal: </b></span>
                  <span><?php if (isset($CP)) {echo @utf8_decode($CP);}?></span>
                </div>
                <div >
                  <span ><b>Tel&eacute;fono 1: </b></span>
                  <span><?php if (isset($Tel1)) {echo @utf8_decode($Tel1);}?></span>
                </div>
                <div >
                  <span ><b>Tel&eacute;fono 2: </b></span>
                  <span><?php if (isset($Tel2)) {echo @utf8_decode($Tel2);}?></span>
                </div>
            <!--     <div >
                  <span ><b>Tipo de Material que vende</b></i></span>
                  <input style="width:90%" type="text" class="form-control" name="TipoMaterialQueVende" id="TipoMaterialQueVende" value="<?php //if (isset($TipoMaterialQueVende)) {//echo @$TipoMaterialQueVende;}?>">
                </div> -->
                <div >
                  <span ><b>Pertenece a grupo empresarial: </b></span>
                  <span><?php if (isset($PerteneceaGrupoEmpresarial)) {echo @utf8_decode($PerteneceaGrupoEmpresarial);}?></span>
                </div>
                    <div >
                  <span ><b>Producto que vende: </b></span>
                  <span><?php if (isset($ProductoQueVende)) {echo @utf8_decode($ProductoQueVende);}?></span>
                </div> 
                     <div >
                  <span ><b>Divisa: </b></span>
                  <span><?php if (isset($Divisa)) {echo @utf8_decode($Divisa);}?></span>
                </div> </td>
<td>
 <div >
                    <span ><b>Proveedor &uacute;nico: </b></span>
                    <span><?php $ProveedorUnico == 0 ? $punico="NO" : $punico="SI"; echo $punico;?></span>
                  </div> 

                  <div >
                    <span ><b>Proveedor principal alterno: </b></span>
                    <span><?php $ProveedorPrincipalAlterno == 0 ? $ppalt="NO" : $ppalt="SI"; echo $ppalt;?></span>
                  </div>  

                  <div >
                    <span ><b>Proveedor confiable: </b></span>
                    <span><?php $ProveedorConfiable == 0 ? $pconf="NO" : $pconf="SI"; echo $pconf;?></span>
                  </div> 
                  <div >
                    <span ><b>Proveedor condicionado: </b></span>
                   <span><?php $ProveedorCondicionado == 0 ? $pcond="NO" : $pcond="SI"; echo $pcond;?></span>
                  </div>   
                  <div >
                    <span ><b>Facilidad o Posibilidad de sustituir al proveedor: </b></span>
                    <span> <?php if (isset($FacilmenteSustituible)) {echo @utf8_decode($FacilmenteSustituible);} ?></span>
                  </div> 
                  <div >
                    <span ><b>Cantidad de empleados: </b></span>
                    <span><?php if (isset($CantidadEmpleados)) {echo @$CantidadEmpleados;}?></span>
                  </div>     
                  <div >
                    <span ><b>Cantidad de clientes: </b></span>
                    <span><?php if (isset($CantidadClientes)) {echo @$CantidadClientes;}?></span>
                  </div>
                  <div >
                    <span ><b>Desde cuando es nuestro vendedor: </b></span>
                    <span><?php if (isset($DesdeCuandoEsVendedor)) {echo @$DesdeCuandoEsVendedor;}?></span>
                  </div>
                  <div >
                    <span ><b>Condiciones de pago: </b></span>
                    <span><?php if (isset($CondicionesPago)) {echo @$CondicionesPago;}?></span>
                  </div>
</td></tr>
</tbody>
</table></div>
<br><hr><br>

<div class="datagrid"><table>
<thead><tr><th>Ventas</th><th>Órdenes de compra</th><th>Pagos</th></tr></thead>
<tbody>
<tr class="alt"><td><div >
                    <span ><b>A&ntilde;o  <?php echo date('Y')-2;?>:  </b></span>
                    <span><?php if (isset($VentasAnioAnterior2) and trim($VentasAnioAnterior2)!="") {echo '$'.@number_format($VentasAnioAnterior2,2);}?></span>
                  </div>  
                  <div >
                    <span ><b>A&ntilde;o  <?php echo date('Y')-1;?>: </b></span>
                    <span><?php if (isset($VentasAnioAnterior1)  and trim($VentasAnioAnterior2)!="") {echo '$'.@number_format($VentasAnioAnterior1,2);}?></span>
                  </div>       
                  <div >
                    <span ><b>Particip. Relativa Hvx Vtas: </b></span>
                    <span><?php if (isset($PartRelativaHelvexVentas)) {echo @$PartRelativaHelvexVentas;}?></span>
                  </div>  
                  <div >
                    <span ><b>Tasa de descuento: </b></span>
                    <span><?php if (isset($TasaDeDescuento)) {echo @$TasaDeDescuento;}?></span>
                  </div>  
                   <div >
                    <span ><b>Tiene sistema de calidad certificado: </b></span>
                    <span><?php $TieneSistemaCalidad == 0 ? $scalidad="NO" : $scalidad="SI"; echo $scalidad;?></span>
                  </div> 
                  <div >
                    <span ><b>Problemas de calidad en los &uacute;ltimos 6 meses: </b></span>
                    <span><?php $ProblemasCalidad6Meses == 0 ? $pcalidad="NO" : $pcalidad="SI"; echo $pcalidad;?></span>
                  </div> </td>
  <td><div >
                    <span><b>&Oacute;rdenes de Compra <?php echo date('Y')-2;?>: </b></span>
                    <span><?php if (isset($OrdenesCompraAnioAnterior2Cantidad)) {echo @$OrdenesCompraAnioAnterior2Cantidad;}?></span>
                  </div>  

                   <div >
                    <span ><b>Importe de O.C. <?php echo date('Y')-2;?>: </b></span>
                    <span><?php if (isset($OrdenesCompraAnioAnterior2Importe)) {echo '$'.@number_format($OrdenesCompraAnioAnterior2Importe,2);}?></span>
                  </div> 

                 <div >
                    <span ><b>&Oacute;rdenes de Compra <?php echo date('Y')-1;?>: </b></span>
                    <span><?php if (isset($OrdenesCompraAnioAnterior1Cantidad)) {echo @$OrdenesCompraAnioAnterior1Cantidad;}?></span>
                  </div> 

                  <div >
                    <span ><b>Importe de O.C. <?php echo date('Y')-1;?>: </b></span>
                    <span><?php if (isset($OrdenesCompraAnioAnterior1Importe)) {echo '$'.@number_format($OrdenesCompraAnioAnterior1Importe,2);}?></span>
                  </div> 

                  <div >
                    <span ><b>&Oacute;rdenes de Compra a&ntilde;o Actual: </b></span>
                    <span><?php if (isset($OrdenesCompraAnioCantidad)) {echo @$OrdenesCompraAnioCantidad;}?></span>
                  </div>  


                  <div >
                    <span ><b>Importe de O.C. a&ntilde;o Actual: </b></span>
                    <span><?php if (isset($OrdenesCompraAnioImporte)) {echo '$'.@number_format($OrdenesCompraAnioImporte,2);}?></span>
                  </div> </td>

  <td><div >
                    <span ><b>Pagos Realizados  <?php echo date('Y')-2;?>: </b></span>
                    <span><?php if (isset($PagosRelizadosAnioAnterior2)) {echo '$'.@number_format($PagosRelizadosAnioAnterior2,2);}?></span>
                  </div> 
                  <div >
                    <span ><b>Pagos realizados <?php echo date('Y')-1;?>: </b></span>
                    <span><?php if (isset($PagosRelizadosAnioAnterior1)) {echo '$'.@number_format($PagosRelizadosAnioAnterior1,2);}?></span>
                  </div> 

                  <div >
                    <span ><b>Pagos por realizar</b></span>
                    <span><?php if (isset($PagosPorRealizar)) {echo '$'.@number_format($PagosPorRealizar,2);}?></span>
                  </div> </td></tr>
</tbody>
</table></div>

<br><hr><br>
<div class="datagrid"><table>
<thead><tr><th>Datos de contactos</th><th>Datos base</th></tr></thead>
<tbody><tr class="alt"><td>
 <div >
                  <span ><b>Nombre 1er contacto: </b></span>
                  <span><?php if (isset($Contacto1)) {echo @utf8_decode($Contacto1);}?></span>
                </div>

                <div >
                  <span ><b>Cargo 1er contacto</b></span>
                  <span><?php if (isset($Cargo1)) {echo @utf8_decode($Cargo1);}?></span>
                </div>

                <div >
                  <span ><b>Correo 1er contacto</b></span>
                  <span><?php if (isset($Correo1)) {echo @utf8_decode($Correo1);}?></span>
                </div>  


                <div >
                  <span ><b>Nombre 2o contacto</b></i></span>
                  <span><?php if (isset($Contacto2)) {echo @utf8_decode($Contacto2);}?></span>
                </div>

                 <div >
                  <span ><b>Cargo 2o contacto</b></span>
                  <span><?php if (isset($Cargo2)) {echo @utf8_decode($Cargo2);}?></span>
                </div>

                <div >
                  <span ><b>Correo 2o contacto</b></span>
                  <span><?php if (isset($Correo2)) {echo @utf8_decode($Correo2);}?></span>
                </div> 


                <div >
                  <span ><b>Nombre 3er contacto</b></span>
                  <span><?php if (isset($Contacto3)) {echo @utf8_decode($Contacto3);}?></span>
                </div>


                <div >
                  <span ><b>Cargo 3er contacto</b></span>
                  <span><?php if (isset($Cargo3)) {echo @utf8_decode($Cargo3);}?></span>
                </div>


                <div >
                  <span ><b>Correo 3er contacto</b></span>
                  <span><?php if (isset($Correo3)) {echo @utf8_decode($Correo3);}?></span>
                </div></td>
                <td>

  <div >
                  <span ><b>P&aacute;gina web</b></span>
                  <span><?php if (isset($PaginaWeb)) {echo @utf8_decode($PaginaWeb);}?></span>
                </div>
                <div >
                  <span ><b>Tiene filiales</b></span>
                  <span><?php $TieneFiliales == 0 ? $filial="NO" : $filial="SI"; echo $filial;?></span>
                </div>
                <div >
                  <span ><b>Nombres de filiales</b></span>
                  <span><?php if (isset($NombresFiliales)) {echo @utf8_decode($NombresFiliales);}?></span>
                </div>
                <div >
                  <span ><b>Fecha de fundaci&oacute;n</b></span>
                  <span><?php if (isset($FechaFundacion)) {echo @$FechaFundacion;}?></span>
                </div>
                <br><br>
                <div>
          
                          <button type="button" style="width:25% !important" id="btnProductos" onclick="toogleDemo('demo','btnProductos','Productos asociados');" >Ver productos asociados</button>
                    <br>
                    
                  <div id="demo" style="display: none">
                      <br>
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
                 
                   <br>
                          <button type="button" style="width:25% !important" id="btnHistorial" onclick="toogleDemo('demo2','btnHistorial','Historial de Negociaciones');">Ver historial de negociaciones</button>

                 <div id="demo2" style="display: none">
                      <br>
                    <table>
                       <thead>
                      <tr>
                        <td>Fecha de negociaci&oacute;n</td><td>Descripci&oacute;n</td><td>C&eacute;dula</td>
                      </tr>
                       </thead>
                       <tbody>
                    <?php.
                    $query="select * from HistorialNegociaciones where numProveedor like '%".$idProveedor."%'";
                    $resultado=mysql_query($query);

                  while ($fila = mysql_fetch_assoc($resultado)) {
                     ?><tr>
                        <td><?php echo $fila['fechaNegociacion'];?></td><td><?php echo utf8_decode($fila['descripcion']);?></td>
                        <td><a target="_blank" href="/CRMcompras/uploads/historiales/<?php echo $fila['cedula'];?>"><?php echo $fila['cedula'];?></a></td>
                      </tr>
                      <?
                    }
                    ?>
                    </tbody>
                 </table>
                    
                 </div>
                 <br><br>
                 
                 
                  </div>

                </td></tr>
</tbody>
</table>

</div>
<br><hr><br>


  </div>


</div>



<script language="JavaScript">



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

    $(document).ready(function() {


      //$('#TablaMateriales').DataTable();





       



        $("#Form1").submit(function(event) {
            event.preventDefault();

            var parametros=new FormData($(this)[0]);
            $.ajax({
                    type: "POST",
                    url: "save.php",
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

                    $(".datosBase").attr('disabled', 'disabled');
                    $("#Editar").removeAttr('disabled');
                    $("#Cancelar").attr('disabled', 'disabled');
                    $("#Guardar").attr('disabled', 'disabled');
                     $('#mensaje').empty(); 
                     $('#mensaje').append(data.mensaje); 
                     $('#status').empty(); 
                     $('#status').append(data.status + "!"); 
                     $('#alerta').show(1000);
                           setTimeout(function(){  $('#alerta').hide(1000); }, 3000);

                });
        });




    });
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


