<?php
session_start();
require_once("conecta.php");
$query="select * from DatosSAP limit 200";
$resultado=mysql_query($query);
include "cabecera.php";
?>

<div style="width: 100%; padding-top: 3%">
 <img src="../images/helvex_banner.jpg" width=100%>
</div>




        

    

    
</div> <!-- end of wrapper -->


  


</body>
</html>


