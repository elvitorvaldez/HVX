
TRUNCATE DatosSAP;


LOAD DATA INFILE '/var/www/html/CRMcompras/sap_datos/PROVEEDOR.CSV' INTO TABLE DatosSAP CHARACTER SET latin1 FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES
(Numero,RazonSocial,Nombre2,RFC,Calle,Poblacion,Pais,Region,CP,PerteneceaGrupoEmpresarial,Tel1,Tel2,TipoMaterialQueVende,ProductoQueVende,Divisa,OrdenesCompraAnioAnterior2Cantidad,OrdenesCompraAnioAnterior1Cantidad,OrdenesCompraAnioCantidad,OrdenesCompraAnioAnterior2Importe,OrdenesCompraAnioAnterior1Importe,OrdenesCompraAnioImporte,Moneda,PagosRelizadosAnioAnterior2,PagosRelizadosAnioAnterior1,PagosRelizadosAnio,PagosPorRealizar)


