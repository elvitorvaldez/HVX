<?php
include "conecta.php";
$query="LOAD DATA INFILE '/var/www/html/CRMcompras/sap_datos/MATERIAL.CSV' INTO TABLE Materiales CHARACTER SET latin1 FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES ('Proveedor','Material','Descripcion')";

?>