#mysql --user=root --password=hx_pruebas CRM_Compras < script/test.sql



#if [ $# -ne 1 ]
# then
#  echo " TRUNCATE DatosSAP;>Sintaxis $0 script_sql"
#  exit
#fi

#file=$1




file="/var/www/html/CRMcompras/sap_datos/PROVEEDOR.CSV"
data="/var/www/html/CRMcompras/test.txt"
script="/var/www/html/CRMcompras/test.sql"

#validar que tenga HDR y TRL



count=`grep -c ^"\#" $file`

if [ $count -ne 2 ]
 then
  echo ""
  echo "Archivo invalido, sin encabezado y/o pie".
  echo ""
  exit
fi


grep -v ^"\#" $file > $data


echo "TRUNCATE DatosSAP;" > $script
echo "LOAD DATA INFILE '$data' " >> $script
echo "INTO TABLE DatosSAP " >> $script
echo "CHARACTER SET latin1 " >> $script
echo "FIELDS TERMINATED BY ',' " >> $script
echo "ENCLOSED BY '\"' LINES " >> $script
echo "TERMINATED BY '\r\n' " >> $script
echo "IGNORE 1 LINES" >> $script
echo "(Numero,RazonSocial,Nombre2,RFC,Calle,Poblacion,Pais,Region,CP," >> $script
echo " PerteneceaGrupoEmpresarial,Tel1,Tel2,TipoMaterialQueVende," >> $script
echo " ProductoQueVende,Divisa,OrdenesCompraAnioAnterior2Cantidad," >> $script
echo " OrdenesCompraAnioAnterior1Cantidad,OrdenesCompraAnioCantidad," >> $script
echo " OrdenesCompraAnioAnterior2Importe,OrdenesCompraAnioAnterior1Importe," >> $script
echo " OrdenesCompraAnioImporte,Moneda,PagosRelizadosAnioAnterior2," >> $script
echo " PagosRelizadosAnioAnterior1,PagosRelizadosAnio,PagosPorRealizar);" >> $script
echo "update DatosSAP set Numero = TRIM(LEADING '0' FROM Numero);" >> $script



mysql --user=root --password=hx_pruebas CRM_Compras < $script


