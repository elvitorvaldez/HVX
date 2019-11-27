#mysql --user=root --password=hx_pruebas CRM_Compras < script/test.sql



#if [ $# -ne 1 ]
# then
#  echo " TRUNCATE DatosSAP;>Sintaxis $0 script_sql"
#  exit
#fi

#file=$1




file="/var/www/html/CRMcompras/sap_datos/MATERIAL.CSV"
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


echo "TRUNCATE Materiales;" > $script
echo "LOAD DATA INFILE '$data' " >> $script
echo "INTO TABLE Materiales " >> $script
echo "CHARACTER SET latin1 " >> $script
echo "FIELDS TERMINATED BY ',' " >> $script
echo "ENCLOSED BY '\"' LINES " >> $script
echo "TERMINATED BY '\r\n' " >> $script
echo "IGNORE 1 LINES" >> $script
echo "(Proveedor,Material,Descripcion)" >> $script



mysql --user=root --password=hx_pruebas CRM_Compras < $script


