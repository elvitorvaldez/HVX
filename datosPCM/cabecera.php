<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>CRM Compras</title>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
</head>


<body>


<span id="top"></span>
<div id="wrapper">
  <div id="header">  <br>     
        <h1 style="">CRM Compras</h1>
  </div> <!-- end of header -->





    <div id="bienvenido"><h3 style="float: left;  padding: 10px 0;">Bienvenido <?php echo utf8_encode($_SESSION['nombre']);?></h3> </div>
    <div id="menu">
        <ul>
            <li><a href="../CRMcompras/principal.php"><span class="home"></span>Inicio</a></li>
						<li><a href="consultarPCM.php"><span class="news"></span>Exportar productos</a></li>
 
						<li><a href="../CRMcompras/listar.php"><span class="proved">
							<img src="../images/ico-proveedores.png" ></span>Proveedores
						</a></li>
            
            <li><a href="../rh/index.php" target="_blank"><span class="portfolio"></span>Recursos humanos</a></li>
            <li class="last"><a href="../CRMcompras/logout.php"><span class="salirico"><img src="../images/salir.svg" width="20px"></span>Cerrar Sesión</a></li>
        </ul>     
    </div> <!-- end of menu -->
</div>
