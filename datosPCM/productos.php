<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
ini_set('memory_limit', '-1');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Productos.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<?php
$link = mysqli_connect("localhost", "victorda_elvitorvaldez", "Magaganda01*") or die("Error: No es posible establecer la conexión");
mysqli_select_db($link,"victorda_PCM_mirror") or die("Error: No se encuentra la base de datos");
$campos = "";
$rotulos = array();
$rotulos = $_REQUEST['destino'];

foreach ($rotulos as $fields) {
    $campos .= $fields . ",";
}

$inc=0;
foreach ($rotulos as $layout=>$valor)
{
        if ($valor=="modelo"){$rotulos[$inc]="Modelo";}
    else if ($valor=="nombreProductoEn"){$rotulos[$inc]="Identificador (es)";}
    else if ($valor=="nombreProductoEs"){$rotulos[$inc]="Identificador (en)";}
    else if ($valor=="estatusVigencia"){$rotulos[$inc]="Estatus de Vigencia";}
    else if ($valor=="tipoProducto"){$rotulos[$inc]="Tipo de Producto";}
    else if ($valor=="sector"){$rotulos[$inc]="Sector";}
    else if ($valor=="CatalogoVersion"){$rotulos[$inc]="Version de catalogo";}
    else if ($valor=="statusAprobacion"){$rotulos[$inc]="Status Aprobacion";}
    else if ($valor=="onLineDesde"){$rotulos[$inc]="onLine desde";}
    else if ($valor=="onLineHasta"){$rotulos[$inc]="onLine hasta";}
    else if ($valor=="areaVenta"){$rotulos[$inc]="area de venta";}
    else if ($valor=="EAN"){$rotulos[$inc]="EAN";}
    else if ($valor=="descripcionEn"){$rotulos[$inc]="Descripcion (en)";}
    else if ($valor=="descripcionEs"){$rotulos[$inc]="Descripcion (es)";}
    else if ($valor=="resumenEn"){$rotulos[$inc]="Resumen (en)";}
    else if ($valor=="resumenEs"){$rotulos[$inc]="Resumen (es)";}
    else if ($valor=="detalles"){$rotulos[$inc]="Detalles";}
    else if ($valor=="logotipos"){$rotulos[$inc]="logotipos";}
    else if ($valor=="normales"){$rotulos[$inc]="normales";}
    else if ($valor=="imagen"){$rotulos[$inc]="imagen";}
    else if ($valor=="imagenes"){$rotulos[$inc]="imagenes";}
    else if ($valor=="imagenesGaleria"){$rotulos[$inc]="imagenes Galeria";}
    else if ($valor=="otros_30"){$rotulos[$inc]="otros (30 px)";}
    else if ($valor=="otros_65"){$rotulos[$inc]="otros (65 px)";}
    else if ($valor=="otros_515"){$rotulos[$inc]="otros (515 px)";}
    else if ($valor=="miniatura"){$rotulos[$inc]="miniatura";}
    else if ($valor=="miniaturas"){$rotulos[$inc]="miniaturas";}
    else if ($valor=="imagenesMKT"){$rotulos[$inc]="imagenes MKT";}
    else if ($valor=="especificaciones"){$rotulos[$inc]="especificaciones";}
    else if ($valor=="atributos"){$rotulos[$inc]="atributos";}
    else if ($valor=="comentarios"){$rotulos[$inc]="comentarios";}
    else if ($valor=="idListaPrecios"){$rotulos[$inc]="Lista de Precios";}
    else if ($valor=="Categoria"){$rotulos[$inc]="Categoria";}
    else if ($valor=="referenciaProductos"){$rotulos[$inc]="referencia Productos";}
    else if ($valor=="urlVideo"){$rotulos[$inc]="Url Video";}
    else if ($valor=="videoInstalacion"){$rotulos[$inc]="video de Instalacion";}
    else if ($valor=="guiaInstalacion"){$rotulos[$inc]="guia de Instalacion";}
    else if ($valor=="archivoRFA"){$rotulos[$inc]="archivo RFA";}
    else if ($valor=="certificadoProducto"){$rotulos[$inc]="certificado de Producto";}
    else if ($valor=="garantiaProducto"){$rotulos[$inc]="garantiade Producto";}
    else if ($valor=="descripcion1"){$rotulos[$inc]="Descripcion 1";}
    else if ($valor=="descripcion2"){$rotulos[$inc]="Descripcion 2";}
    else if ($valor=="descripcion3"){$rotulos[$inc]="Descripcion 3";}
    else if ($valor=="hojaDespiece"){$rotulos[$inc]="hoja de Despiece";}
    else if ($valor=="hojaEspecificacion"){$rotulos[$inc]="hoja de Especificacion";}
    else if ($valor=="2Ddwg"){$rotulos[$inc]="archivo 2Ddwg";}
    else if ($valor=="3Ddwg"){$rotulos[$inc]="archivo 3Ddwg";}
    else if ($valor=="archivoDXF"){$rotulos[$inc]="archivo DXF";}
    else if ($valor=="Fabricante"){$rotulos[$inc]="Fabricante";}
    else if ($valor=="codigoOtrosFabricantes"){$rotulos[$inc]="Codigo Otros Fabricantes";}
    else if ($valor=="observacionesEn"){$rotulos[$inc]="observaciones (en)";}
    else if ($valor=="observacionesEs"){$rotulos[$inc]="observaciones (es)";}
    else if ($valor=="palabrasClaveEn"){$rotulos[$inc]="Palabras Clave (en)";}
    else if ($valor=="palabrasClaveEs"){$rotulos[$inc]="Palabras Clave (es)";}
    else if ($valor=="fechaCreacion"){$rotulos[$inc]="Fecha de Creacion";}
    else if ($valor=="fechaModificacion"){$rotulos[$inc]="Fecha de Modificacion";}
    else if ($valor=="statusAprobacionMKT"){$rotulos[$inc]="status de Aprobacion MKT";}
    else if ($valor=="statusAprobacionCostos"){$rotulos[$inc]="status de Aprobacion Costos";}
    else if ($valor=="statusAprobacionIngenieria"){$rotulos[$inc]="status de Aprobacion Ingenieria";}
    else if ($valor=="piApprovalStatus"){$rotulos[$inc]="statys PI";}
    else if ($valor=="supercategorias"){$rotulos[$inc]="Supercategorias";}
    else if ($valor=="precio"){$rotulos[$inc]="Precio";}
    else if ($valor=="moneda"){$rotulos[$inc]="Moneda";}
    else if ($valor=="unidadContenido"){$rotulos[$inc]="Unidad de contenido";}
    else if ($valor=="unidadVenta"){$rotulos[$inc]="Unidad de venta";}
    else if ($valor=="stringSupercategoria"){$rotulos[$inc]="Supercategoría";}
    else if ($valor=="linea"){$rotulos[$inc]="Línea";}
    else if ($valor=="marca"){$rotulos[$inc]="Marca";}
    else if ($valor=="acabado"){$rotulos[$inc]="Acabado";}
    
 $inc++;
} 

$campos = substr($campos, 0, -1);
$query  = "select $campos from ProductosDetalle where estatusVigencia<>''";
$result = mysqli_query($link, $query);

$i = 0;

while ($row = mysqli_fetch_array($result)) {
    $rawdata[$i] = $row;
    $i++;
}

$close = mysqli_close($link);

echo '<table class="" width="100%" border="1" style="text-align:center;">';
$columnas = count($rawdata[0]) / 2;
$filas    = count($rawdata);


//Añadimos los titulos

for ($i = 0; $i < count($rotulos); $i = $i + 1) {
    echo "<th style='text-align:center'><b>" . $rotulos[$i] . "</b></th>";

}

for ($i = 0; $i < $filas; $i++) {
    
    echo "<tr>";
    for ($j = 0; $j < $columnas; $j++) {
        echo "<td>" . utf8_encode($rawdata[$i][$j]) . "</td>";
        
    }
    echo "</tr>";
}

echo '</table>';

?>
