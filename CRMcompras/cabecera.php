<?php session_start();
if  (empty($_SESSION['usuario']))
{
	if (!(strpos($_SERVER['PHP_SELF'], "index.php")))
	{
		header('Location: index.php');
	}
}
?>
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


<?php
if ($_SESSION['nombre'] && $url[3]!=="index.php")
{

?>


    <div id="bienvenido"><h3 style="float: left;  padding: 10px 0;">Bienvenido <?php echo utf8_encode($_SESSION['nombre']);?></h3>
   <?php if ($_SESSION['admin']==1)
	 {?>
	 <h3 style="float: right;  padding: 10px 0;"><a class="linkGestion" href="gestionUsuarios.php">Gestión de usuarios</a></h3> 
	 <?}?>
	 </div>
	  <div id="menu">
        <ul>
            <li><a href="principal.php"><span class="home"></span>Inicio</a></li>
						<li><a href="../datosPCM/consultarPCM.php"><span class="news"></span>Exportar productos</a></li>
            
						<?php if (strpos($_SERVER['PHP_SELF'], "listar.php")) {?>
						<li>							
						<a href="buscar.php"><span class="b2b">
						<img src="../images/buscar.png" ></span>Búsqueda</a>						
						</li>
						<?php } else {?>
						<li><a href="listar.php"><span class="proved">
							<img src="../images/ico-proveedores.png" ></span>Proveedores
						</a></li>
						<?php }?>
            
            <li><a href="../rh/index.php" target="_blank"><span class="portfolio"></span>Recursos humanos</a></li>
            <li class="last"><a href="logout.php"><span class="salirico"><img src="../images/salir.svg" width="20px"></span>Cerrar Sesión</a></li>
        </ul>     
    </div> <!-- end of menu -->
</div>
<?php
}
?>