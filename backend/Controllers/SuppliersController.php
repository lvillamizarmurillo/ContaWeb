<?php 


require_once 'Models/SupplierModel.php';
/**
 * 
 */
class SuppliersController extends SupplierModel
{

	
	function __construct()
	{
		# code...
	}

	public function renderSupplier(){
		$obj_Supplier = parent::getSupplier();
		//echo var_dump($obj_Suplier);
		
		include_once 'Views/Modules/Suppliers/index.php';

		
	}

	//Agregar
	public function addSupplier($nombre, $correo, $telefono, $direccion, $foto_ruta, 
	$foto_nombre, $fecha_creado, $fecha_modificado, $status){

	$this->prv_nombre = $nombre;
	$this->prv_correo = $correo;
	$this->prv_telefono = $telefono;
	$this->prv_direccion = $direccion;
	$this->prv_foto_ruta = $foto_ruta;
	$this->prv_foto_nombre = $foto_nombre;
	$this->prv_fecha_creado = $fecha_creado;
	$this->prv_fecha_modificado = $fecha_modificado;
	$this->prv_status = $status;
	parent::addSupplierM();
	//redirect();

		//inserto el array en las variables del modelo mediante getters y setters, y una vez cargada la información, inserto.
	}






}

if(isset($_GET['dir']) && $_GET['dir'] == 'suppliers' )
{
	$start = new SuppliersController();
	$start->renderSupplier();
}


if(isset($_POST['exe']) && $_POST['exe']=='add') {
	
		//echo 'En la Zona! ejecutado post!'.$_POST['nombre'].'<br>';


	$start = new SuppliersController();
	$start->addSupplier( //aquí se podría recorrer el post y se guarda en array associativo
		$_POST['nombre'],
		$_POST['correo'],
		$_POST['telefono'],
		$_POST['direccion'],
		$_POST['foto_ruta'],
		$_POST['foto_nombre'],
		$_POST['fecha_creado'],
		$_POST['fecha_modificado'],
		$_POST['status']
	);
}

















 ?>