<?php
session_start();
require_once("conecta.php");
?>
<!DOCTYPE HTML>
<head>
	<title>Grupos en cat&aacte;logo</title>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="http://malsup.github.com/jquery.form.js"></script> 
	<style>
	.clear{clear:both;text-align:center}
	.izq{border-radius:10px 0 0 10px !important;}
	.der{border-radius:0 10px 10px 0 !important;}
	.columnaa{float:left;width:40%; text-align: right;}
	.columnab{float:left;width:20%; text-align: center; margin-top: 15%}
	.columnac{float:left;width:40%; text-align: left;}


.suggest-element{
margin-left:5px;
margin-top:5px;
width:350px;
cursor:pointer;
}
#suggestions {
width:350px;
height:150px;
overflow: auto;
}



	</style>

<!-- CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
<!-- Tema opcional -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous">
<!-- JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
</head>
<body>

<?php
if ($_SESSION['nombre'])
{
?>


<div class="container">
<nav class="navbar navbar-default" style="overflow: hidden;">
    	<img src="http://intranet.helvex.com.mx/actividades/images/logoHvx.jpg" width="19%">
      <h2 style="float: right">Bienvenido <?php echo $_SESSION['nombre'];?></h2>      
    	
</nav>
     <p><a href="listar.php">Regresar</a></p>
<h3>Cat&aacute;logo Helvex</h3>
<h4>GRUPOS</h4>

<form name="Form1" id="Form1" method="POST" enctype="multipart/form-data">
<div id="c1" style="width: 49%; float: left; margin-left: 25%;">
   

  <div class="panel panel-default">
    <div class="panel-heading">Agregar grupo</div>
    <div class="panel-body">
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-addon">&nbsp;Descripci&oacute;n</span>
            <input type="text" class="form-control" name="descripcion1" id="descripcion1"
           placeholder="Descripci&oacute;n" required>
          </div>          
          <div class="input-group">
            <span class="input-group-addon">&nbsp;Color</span>
            <input type="color" class="form-control" name="color1" id="color1">
          </div>
          <div class="input-group">
           <span class="input-group-addon">&nbsp;Status</span>
            <select class="form-control" name="status1" id="status1" required>
              <option value=""> - Seleccione - </option>
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
          </div>
           <div class="input-group">
            <span class="input-group-addon">&nbsp;Imagen</span><input type="file" class="form-control" name="imgGrupo1" id="imgGrupo1"></div>
    </div>
    </div>
    <div class="panel-footer">
        <button id="guardarGrupo" name="guardarGrupo" type="submit" class="btn btn-info">Guardar</button>
        <div id="waitSave" class="alert alert-warning" style="margin-top: 10px;display: none">
            <strong>Espere</strong> Se est&aacute;n guardando los cambios.
          </div>
          <div id="okSave" class="alert alert-success" style="margin-top: 10px;display: none">
            <strong>Hecho</strong> Se han guardado los cambios.
          </div>
          <div id="failSave" class="alert alert-danger" style="margin-top: 10px;display: none">
            <strong>Error!</strong> <p id="errorSaveText">Ha ocurrido un error, intente de nuevo</p>
          </div>
          <!-- <button id="Cancelar" type="button" class="btn btn-danger">Cancelar</button> -->
    </div>
  </div>
  </div>
</form>


<form name="Form2" id="Form2" method="POST" enctype="multipart/form-data">
<div id="c2" style="width: 49%; float: left; margin-left: 25%;">
   

  <div class="panel panel-default">
    <div class="panel-heading">Editar grupo</div>
    <div class="panel-body">
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-addon">&nbsp;Descripci&oacute;n</span>
            <select class="form-control" name="descripcion2" id="descripcion2">
              <option value="">- - Seleccione - - </option>
              <?php 
              // $query="select IdGpoArt, descripcion from cat_GpoArt where tipo='1'";
              $query="select IdGpoArt, descripcion from cat_GpoArt";
              $respuesta=mysql_query($query);
              while ($fila = mysql_fetch_array($respuesta, MYSQL_NUM)) {
                ?>
                  <option value="<?php echo $fila[0];?>"> <?php echo $fila[1];?></option>;  
              <?php }
              ?>
            </select>
          </div>          
          <div class="input-group">
            <span class="input-group-addon">&nbsp;Color</span>
            <input type="color" class="form-control" name="color2" id="color2"
           placeholder="Descripción 1">
          </div>
          <div class="input-group">
            <span class="input-group-addon">&nbsp;Status</span>
            <select class="form-control" id="status2" name="status2">
              <option value=""> - Seleccione - </option>
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
            <input type="hidden" id="idGpoArt">
          </div>
          <div class="input-group">
            <span class="input-group-addon">&nbsp;Imagen</span>
              <p id="imagenpath"></p>
            <input type="file" class="form-control" name="imgGrupo2" id="imgGrupo2" placeholder="aish"></div>
    </div>
    </div>
    <div class="panel-footer">
        <button id="EditarGrupo" type="submit" class="btn btn-info">Guardar</button>
          <div id="waitEdit" class="alert alert-warning" style="margin-top: 10px;display: none">
            <strong>Espere</strong> Se est&aacute;n guardando los cambios.
          </div>
          <div id="okEdit" class="alert alert-success" style="margin-top: 10px;display: none">
            <strong>Hecho</strong> Se han guardado los cambios.
          </div>
          <div id="failEdit" class="alert alert-danger" style="margin-top: 10px;display: none">
            <strong>Error!</strong><p id="errorEditText">Ha ocurrido un error, intente de nuevo</p>
          </div>
          <!-- <button id="Cancelar" type="button" class="btn btn-danger">Cancelar</button> -->
    </div>
  </div>
  </div>
</form>

<div id="c3" style="width: 49%; float: left; margin-left: 25%;">
   

  <div class="panel panel-default">
    <div class="panel-heading">Agregar productos a grupo</div>
    <div class="panel-body">
        <div class="form-group">
                   
          
          <div class="input-group">
            <span class="input-group-addon">&nbsp;Grupo</span>
            <select class="form-control" name="descripcion3" id="descripcion3">
              <option value="">- - Seleccione - - </option>
              <?php 
              $query="select IdGpoArt, descripcion from cat_GpoArt where tipo='1'";
              $respuesta=mysql_query($query);
              while ($fila = mysql_fetch_array($respuesta, MYSQL_NUM)) {
                ?>
                  <option value="<?php echo $fila[0];?>"> <?php echo $fila[1];?></option>;  
              <?php }
              ?>
            </select>
          </div>

           <div style="clear:both; height: 10px;">&nbsp;</div>
    <from>
        <!-- Trigger the modal with a button -->

        <button type="button" id="AgregarProds" class="btn btn-success pull-right" data-toggle="modal" data-target="#myModal" disabled>Agregar</button>
          <input type="hidden" id="ListaPro" name="ListaPro" value="" required />
        <table id="TablaPro" class="table">
            <thead>
                <tr>
                    <th>Clave Producto</th>  
                    <th>Nombre Producto</th>                
                    <th>Acci&oacute;n</th>
                </tr>
            </thead>
            <tbody id="ProSelected"><!--Ingreso un id al tbody-->
                <tr>
             
                </tr>
            </tbody>
        </table>
<!--Agregue un boton en caso de desear enviar los productos para ser procesados-->
               <!--  <div class="form-group">
                    <button type="submit" id="guardar" name= "guardar" class="btn btn-lg btn-default pull-right">Guardar</button>
                </div> -->
    </from>

        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">

            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Agregar producto al grupo</h4>
                    </div>
                    <div class="modal-body" >
                      <form name="productForm">
                         <div class="input-group" style="overflow: hidden; width:100%">
                          
                          <input type="text" class="form-control" name="producto" id="producto"
                         placeholder="Producto" required style="width:50%">
                         <div id="suggestions"></div>
                         <button id="buscarProducto" type="button" class="btn btn-info pull-left" style="margin-left: 15px"><i class="glyphicon glyphicon-search" style="float:left;"></i></button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!--Uso la funcion onclick para llamar a la funcion en javascript-->
                        <!-- <button type="submit" onclick="agregarProducto()" class="btn btn-default" data-dismiss="modal">Agregar</button> -->
                    </div>
                  </form>
                </div>

            </div>
        </div>



    </div>
    </div>
    <div class="panel-footer">
    	  <button id="guardarProductos" type="button" class="btn btn-info">Guardar</button>
    </div>
  </div>
  </div>

</div>

 </div>

  
 <div style="clear:both; height: 10px;">&nbsp;</div>

</div>


    

<script language="JavaScript">

  var ip = [];
  var ipt;


function RefrescaProducto() {
  
    var i = 0;
    $('#guardar').attr('disabled', 'disabled'); //Deshabilito el Boton Guardar
    $('.iProduct').each(function(index, element) {

        i++;
        ip.push({
            id_pro: $(this).attr("id")
        });
    });
    // Si la lista de Productos no es vacia Habilito el Boton Guardar
    if (i > 0) {
        $('#guardar').removeAttr('disabled', 'disabled');
    }
    var ipt = JSON.stringify(ip); //Convierto la Lista de Productos a un JSON para procesarlo en el controlador
    $('#ListaPro').val(encodeURIComponent(ipt));
}

function agregarProducto(material,descripcion) {
    //event.preventDefault();
    var newtr = '<tr class="item"  data-id="' + material + '">';
    newtr = newtr + '<td class="iProduct"  id="' + material + '">' + material + '</td>';
    newtr = newtr + '<td class="iDescription" >' + descripcion + '</td>';
    newtr = newtr + '<td><button type="button" id="button-'+material+'" class="btn btn-danger btn-xs remove-item"><i class="fa fa-times"></i></button></td></tr>';

    $('#ProSelected').append(newtr); //Agrego el Producto al tbody de la Tabla con el id=ProSelected


    RefrescaProducto(); //Refresco Productos

    $('.remove-item').off().click(function(e) {
        var id=$(this).attr('id');
        id=id.replace("button-","");

        for (a=0; a<ip.length; a++)
        {
          if (ip[a]['id_pro'] == id)
            {
              delete ip[a];
            }
        }
        $(this).parent('td').parent('tr').remove(); //En accion elimino el Producto de la Tabla
        if ($('#ProSelected tr.item').length == 0)
            $('#ProSelected .no-item').slideDown(300);
        RefrescaProducto();
    });
    $('.iProduct').off().change(function(e) {
        RefrescaProducto();
    });
}

 $(document).ready(function() {

    $("#descripcion2").change(function() {
        $.ajax({
                type: "POST",
                url: "saveGroups.php",
                data: {
                    operacion: 'cargarDatosGrupo',
                    idGrupo: $("#descripcion2").val(),
                }
            })
            .done(function(msg) {
                $("#color2").attr('value', '#'+msg.color);
                $("#status2").val(msg.status);
                $("#idGpoArt").val(msg.idGpoArt);
                $("#imagenpath").empty();
                $("#imagenpath").append(msg.imagen);

                $("#status2 option").each(function() {
                    if ($(this).attr('value') == msg.status) {
                        $(this).prop("selected", "selected");
                    }
                });
                // $("#Cancelar").attr('disabled','disabled');
                // $("#Guardar").attr('disabled','disabled');
            });
    });



    $("#descripcion3").change(function() {
         ip.splice(0, ip.length);
      if ($(this).val()=="")
      {
        $("#AgregarProds").prop('disabled', true);
      }
      else
      {
        $("#AgregarProds").prop('disabled', false);
      }

        $.ajax({
                type: "POST",
                dataType: "json",
                url: "saveGroups.php",
                data: {
                    operacion: 'consultarDatosGrupo',
                    idGrupo: $("#descripcion3").val(),
                }
            })
            .done(function(msg) {
                $('#ProSelected').empty();
                for(var key in msg)
                {                  
                  agregarProducto(msg[key]['material'], msg[key]['descripcion']);
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
              alert(errorThrown);
              
            });
    });



   $("#Form2").submit(function(event) {
      event.preventDefault();
   
        $('#waitEdit').show();

       var formData = new FormData($("#Form2")[0]);
        formData.append("operacion", "editarGrupo");

        $.ajax({
                type: "POST",
                url: "saveGroups.php",
                contentType: false,
                data: formData,
                processData: false,
                mimeType: 'multipart/form-data',
                contentType: false
                // data: {
                //     operacion: 'guardarGrupo',
                //     descripcion: $("#descripcion1").val(),
                //     color: $("#color1").val(),
                //     status: $("#status1").val(),
                // }
            })
            .done(function(msg) {
              
                $('#waitEdit').hide(500);
                if (msg.length > 1) {
                   $('#errorEditText').empty();
                   $('#errorEditText').append(msg);
                    $('#failEdit').show(1000);
                    setTimeout(function() {
                        $('#failEdit').hide(1000);
                    }, 3000);
                    
                } else {
                    $('#okEdit').show(1000);
                    setTimeout(function() {
                        $('#okEdit').hide(1000);
                    }, 3000);                
                }
                location.reload();
            });
    });



    $("#buscarProducto").click(function(event) {
        //event.preventDefault();
        if ($("#producto").val() == "") {
            alert("Debe capturar un producto");
            return;
        }
        $('#waitSearch').show();

        $.ajax({
                type: "POST",
                url: "saveGroups.php",
                data: {
                    operacion: 'buscarProducto',
                    producto: $("#producto").val()
                }
            })
            .done(function(msg) {
                $('#waitSearch').hide(500);
                if (msg.Material.length > 1) {
                    agregarProducto(msg.Material, msg.DescripcionCorta);
                    $("#producto").val("");
                } else {
                    alert("Producto no existe");
                }
            });
    });


    $("#guardarProductos").click(function(event) {
        event.preventDefault();
        if (ip=="" || $("#descripcion3").val()=="")
        {
          alert("Favor de llenar todos los campos");
          return;
        }
        $('#waitSearch').show();
        $.ajax({
                type: "POST",
                url: "saveGroups.php",
                data: {
                    operacion: 'guardarProductos',
                    idGpoArt: $("#descripcion3").val(),
                    productos: ip
                }
            })
            .done(function(msg) {
                $('#waitSearch').hide(500);
                if (msg.trim()!="") {
                    //alert(msg);
                    alert("Error al guardar");
                    //location.reload();
                } else {
                    alert("Productos agregados ok");
                    location.reload();
                }
            });
    });

     $("#Form1").submit(function(event) {
    
      var datos=$("#Form1").serialize();


        event.preventDefault();
        $('#waitSave').show();
        var formData = new FormData($("#Form1")[0]);
        formData.append("operacion", "guardarGrupo");

        $.ajax({
                type: "POST",
                url: "saveGroups.php",
                contentType: false,
                data: formData,
                processData: false,
                mimeType: 'multipart/form-data',
                contentType: false
                // data: {
                //     operacion: 'guardarGrupo',
                //     descripcion: $("#descripcion1").val(),
                //     color: $("#color1").val(),
                //     status: $("#status1").val(),
                // }
            })
            .done(function(msg) {
                $('#waitSave').hide(500);
                if (msg.length > 1) {
                   $('#errorSaveText').empty();
                   $('#errorSaveText').append(msg);
                    $('#failSave').show(1000);                    
                    setTimeout(function() {
                        $('#failSave').hide(1000);
                    }, 3000);                    
                } else {
                    $('#okSave').show(1000);
                    setTimeout(function() {
                        $('#okSave').hide(1000);
                        location.reload();
                    }, 3000);

                }
                
            });
    });



$('#producto').keypress(function(){
        //Obtenemos el value del input
        var product = $(this).val();        
        //var dataString = 'product='+service;
        //Le pasamos el valor del input al ajax
        $.ajax({
            type: "POST",
            url: "saveGroups.php",
            data: {
                    operacion: 'predictivo',
                    producto: product,
                }
            })
            .done(function(data) {
                //Escribimos las sugerencias que nos manda la consulta
                $('#suggestions').fadeIn(1000).html(data);
                //Al hacer click en algua de las sugerencias
                $('.suggest-element').on('click', function(){
                    //Obtenemos la id unica de la sugerencia pulsada
                    var id = $(this).attr('id');
                    //Editamos el valor del input con data de la sugerencia pulsada
                    $('#service').val($('#'+id).attr('data'));
                    //Hacemos desaparecer el resto de sugerencias
                    $('#suggestions').fadeOut(1000);
                });              
           
         });         
});

});
</script>
<?php
 } 
 else {
header('Location: index.php');
exit;
 }
 ?>


</body>
</html>