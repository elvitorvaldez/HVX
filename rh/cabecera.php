<?php session_start();
if  (empty($_SESSION['usuario']))
{
	if (!(strpos($_SERVER['PHP_SELF'], "index.php")))
	{
		header('Location: index.php');
	}
}
?><!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Reclutamiento y selección</title>
	<script src="js/jquery.min.js"></script>

    <!-- JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
<!-- CSS -->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="css/styles.css" rel="stylesheet" type="text/css" />
</head>


<body>


<span id="top"></span>
<div id="wrapper">
  <div id="header">
        <div id="site_title">
            <h1>Recursos Humanos</</h1>
        </div>
  </div> <!-- end of header -->

   <?php if ($_SESSION['rol']==1)
	 {?>
	 <h3 style="float: right;  padding: 10px 0;"><a class="linkGestion" href="gestionUsuariosRh.php">Gestión de usuarios</a></h3> 
	 <?}?>

