<?php
include "cabecera.php";
		$link = mysql_connect("localhost", "victorda_elvitorvaldez", "Magaganda01*") or die("Error: No es posible establecer la conexión");
		mysql_select_db("victorda_PCM_mirror", $link) or die("Error: No se encuentra la base de datos");
				$query="select COLUMN_NAME from INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA = 'victorda_PCM_mirror' and TABLE_NAME = 'ProductosDetalle' and COLUMN_NAME NOT in ('idProductoDetalle', 'idProducto')";
					$result=mysql_query($query);

?>

<!DOCTYPE HTML>
<head>
	<title>Lista de precios LP01012018</title>
	<style>
	select{width:180px; height:450px !important; margin:0 0 50px 0;border:1px solid #ccc;padding:10px;border-radius:10px 0 0 10px;}
	.clear{clear:both;text-align:center}
	.izq{border-radius:10px 0 0 10px !important;}
	.der{border-radius:0 10px 10px 0 !important;}
	.columnaa{float:left;width:40%; text-align: right;}
	.columnab{float:left;width:20%; text-align: center; margin-top: 15%}
	.columnac{float:left;width:40%; text-align: left;}

	</style>


</head>
<body>
	
<div class="container">	
<nav class="navbar navbar-default" style="overflow: hidden;">


<div style="clear:both"></div>

<div class="interno navbar-default" style="overflow: hidden;">
	<p>Selecione uno o mas campos del cuadro de la izquierda, y mu&eacute;valos a la derecha con ayuda de los botones.	Al finalizar su selecci&oacute;n, presione el bot&oacute;n <i>Descargar archivo</i> </p>
	<form action="productos.php" method="post" id="formulario" name="formulario">
		<div class="columnaa">
			
			<select name="origen[]" id="origen" multiple="multiple" size="8" >
				<?php 
					$indice=0;
					while ($fila=mysql_fetch_array($result)) {
						echo "<option value='".$fila['COLUMN_NAME']."'>".strtoupper($fila['COLUMN_NAME'])."</option>";
						$indice++;
					}
				?>
			</select>
		</div>
		<div class="columnab">
			<input type="button" class="boton" onclick="pasar();" class="izq" value="Agregar &raquo;"><input class="boton" type="button" onclick="quitar();" class="der" value="&laquo; Quitar"><br />
			<input type="button" class="boton" onclick="pasartodos();"class="izq" value="Todos &raquo;"><input class="boton" type="button" onclick="quitarTodos();" class="der" value="&laquo; Ninguno">
		</div>
		<div class="columnac">
			<select name="destino[]" id="destino" multiple="multiple" size="8"></select>	
		</div>
		<p class="clear"><input type="button" name="enviar" id="enviar" onclick="enviarFormulario();" class="" value="Descargar archivo"></p>
	</form>
</div>
</div>
	<script type="text/javascript">
		
function pasar() {
			var x = document.getElementById("origen");
			var y = document.getElementById("destino");
	
			
				var option = document.createElement("option");
				option.value = x.value;
				option.text = x.value.toUpperCase();
				
			if (option.value!="")
			{
				y.add(option);
				x.remove(x.selectedIndex);				
			}
			
			//return !$('#origen option:selected').remove().appendTo('#destino');
			} 		

function quitar() {
			var x = document.getElementById("destino");
			var y = document.getElementById("origen");
	
			
				var option = document.createElement("option");
				option.value = x.value;
				option.text = x.value.toUpperCase();
			if (option.value!="")
			{
				y.add(option);
				x.remove(x.selectedIndex);				
			}
			
			
			} 			
		

		 
		
		function pasartodos() {
			
			var obj = document.forms[0].origen;
			var y = document.getElementById("destino");
			
			for (i=0; i<obj.length; i+=1){
				
				opt=obj.options[i];			  			
				var option = document.createElement("option");
				option.value = opt.value;
				option.text = opt.value.toUpperCase();			
				y.add(option);
			}
			document.getElementById('origen').innerText = null;
		}
		
		function quitarTodos()
		{
			var obj = document.forms[0].destino;
			var y = document.getElementById("origen");
			
			for (i=0; i<obj.length; i+=1){
				
				opt=obj.options[i];			  			
				var option = document.createElement("option");
				option.value = opt.value;
				option.text = opt.value.toUpperCase();			
				y.add(option);
			}
			document.getElementById('destino').innerText = null;
		}
		
		
		function enviarFormulario()
		{
			var id=document.getElementById("destino");
			if(id.options.length==0)
			{
				alert("Elija al menos un campo para generar el reporte");
				return;
			}
			for (i=0; i<id.options.length; i++)
			 {
			  id.options[i].selected = true;
			 }
			document.getElementById('formulario').submit();
		}
		
	

	</script>
		
		
</body>
</html>

