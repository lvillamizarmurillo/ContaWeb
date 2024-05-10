
<?php 

include_once 'Views/Base/head.php';
include_once 'Views/Base/navbar.php';

?>


<!-- Inicio Contenido  -->

<style>
/* header */
.text-white-50 { color: rgba(255, 255, 255, .5); }
.bg-crimson { background: linear-gradient(40deg,#ffd86f,#fc6262) !important; }
.box-shadow { box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05); }
/* fin header */






</style>

<main role="main" class="container flex-fill " >
    <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-crimson rounded box-shadow ">
        <img class="mr-3" src="https://media.informabtl.com/wp-content/uploads/2015/08/proveedor.jpg" alt="" width="48" height="48">
        <div class="lh-100 " style="color: #474747">
            <h6 class="mb-0  lh-100">{Insertar}</h6>
            <small>{Insertar}</small>

        </div>
    </div>
    <button type="button" class="btn btn-info btn-rounded">Agregar Proveedor</button>

    <div class="my-3 p-3 bg-white rounded box-shadow table-responsive" >
        <h6 class="border-bottom border-gray pb-2 mb-0">Info</h6>
        <div class="media text-muted pt-3">


            <table class="table table-bordered table-responsive">
			  <thead>
			    <tr>
			      <th scope="col">Nombre</th>
			      <th scope="col">Correo</th>
			      <th scope="col">Teléfono</th>
			      <th scope="col">Dirección</th>
			      <th scope="col">Foto</th>
			      <th scope="col">Creado</th>
			      <th scope="col">Modificado</th>
			      <th scope="col">status</th>
			      <th scope="col">Opciones</th>
			    </tr>
			  </thead>
			  <tbody>
			  <?php foreach($obj_Supplier as $supplier_data){ ?>
			    <tr>
			      <td><?= $supplier_data->prv_nombre; ?></th>
			      <td><?= $supplier_data->prv_correo; ?></td>
			      <td><?= $supplier_data->prv_telefono; ?></td>
			      <td><?= $supplier_data->prv_direccion; ?></td>
			      <td><?= $supplier_data->prv_foto_ruta.$supplier_data->prv_foto_nombre; ?></th>
			      <td><?= $supplier_data->prv_fecha_creado; ?></td>
			      <td><?= $supplier_data->prv_fecha_modificado; ?></td>
			      <td><?= $supplier_data->prv_status; ?></td>
			      <td>
			      	<div class="row col-12">
			      		
			      	<div  class="col-lg-6">
			      		<button style="margin-right: 40px;" 
					  type="button"
					  class="btn btn   btn-floating "
					  data-ripple-color="dark"
					>
					  <i class="far fa-edit"></i>
					</button>

					</div>

					<div class="col-lg-6">
					<button 
					  type="button"
					  class="btn btn btn-danger btn-rounded btn-floating "
					  data-ripple-color="dark"
					>
					  <i class="far fa-trash-alt"></i>
					</button>
			      	</div>
			      	</div>
			      </td>	
			    </tr>
			    
			  <?php } ?>
			  </tbody>
			</table>

        </div>
        <small class="d-block text-right mt-3"> <a href="#">Reportar error</a> </small>
    </div>
    
</main>



<div><!-- Se puede enviar el action vacío y sería lo mismo -->
	<br><br><br><center><strong>Agregar</strong></center>
	<form action="suppliers" method="POST" name='form1'>
	  <label for="nombre">Nombre:</label>
	  <input type="text" id="nombre" name="nombre"><br><br>
	  <label for="correo">Correo:</label>
	  <input type="text" id="correo" name="correo"><br><br>
	  <label for="telefono">Telefono:</label>
	  <input type="text" id="telefono" name="telefono"><br><br>
	  <label for="direccion">Dirección:</label>
	  <input type="text" id="direccion" name="direccion"><br><br>
	  <label for="foto_ruta">Foto_ruta:</label>
	  <input type="text" id="foto_ruta" name="foto_ruta"><br><br>
	  <label for="foto_nombre">Foto_nombre:</label>
	  <input type="text" id="foto_nombre" name="foto_nombre"><br><br>
	  <label for="fecha_creado">Fecha_creado:</label>
	  <input type="text" id="fecha_creado" name="fecha_creado"><br><br>
	  <label for="fecha_modificado">Fecha_modificado:</label>
	  <input type="text" id="fecha_modificado" name="fecha_modificado"><br><br>
	  <label for="status">Status:</label>
	  <input type="text" id="status" name="status"><br><br>
	  <input type="submit" value="Enviar">
	  <input type="hidden" name='exe' value='add'>
	</form>
</div>

<div class="table-responsive">
  <table class="table"></table>
</div>
<!-- Fin Contenido -->






















<script>
	
document.querySelector("#title").innerHTML += '<a href=""> Proveedores</a>'

</script>


<!--  x.querySelector("#demo").innerHTML = "Hello World!"; -->

<?php 

include_once 'Views/Base/footer.php';

 ?>