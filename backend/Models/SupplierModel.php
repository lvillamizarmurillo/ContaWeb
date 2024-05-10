<?php 

require_once 'Config/db_connection/Connection.php';

Class SupplierModel extends Connection
{
	protected $prv_id;
	protected $prv_nombre;
	protected $prv_correo;
	protected $prv_telefono;
	protected $prv_direccion;
	protected $prv_foto_ruta;
	protected $prv_foto_nombre;
	protected $prv_fecha_creado;
	protected $prv_fecha_modificado;
	protected $prv_status;


	protected function getSupplier()
	{
		$conn = (new Connection())->startConnection();
		$sql = 'SELECT * FROM proveedores';
		$exe = $conn->prepare($sql);
		$exe->execute();

		$getSupplier_response = $exe->FetchALL(PDO::FETCH_OBJ);
		return $getSupplier_response;

	}

	protected function addSupplierM(){
		
		try{

		echo "ENTRAMOS!";
		$conn = (new Connection())->startConnection();
		$sql = 'INSERT INTO proveedores(prv_nombre, prv_correo, prv_telefono, prv_direccion, prv_foto_ruta, prv_foto_nombre, prv_fecha_creado, prv_fecha_modificado, prv_status) 
			values(:nombre, :correo, :telefono, :direccion, :foto_ruta, :foto_nombre, :fecha_creado,
			:fecha_modificado, :status)';
		$exe = $conn->prepare($sql);

		$exe->bindParam(':nombre', $this->prv_nombre);
		$exe->bindParam(':correo', $this->prv_correo);
		$exe->bindParam(':telefono', $this->prv_telefono);
		$exe->bindParam(':direccion', $this->prv_direccion);
		$exe->bindParam(':foto_ruta', $this->prv_foto_ruta);
		$exe->bindParam(':foto_nombre', $this->prv_foto_nombre);
		$exe->bindParam(':fecha_creado', $this->prv_fecha_creado);
		$exe->bindParam(':fecha_modificado', $this->prv_fecha_modificado);
		$exe->bindParam(':status', $this->prv_status);
		$validate = $exe->execute();

		if($validate){
			echo "Ingresado correctamente!, devolvió: ".$validate." ".var_dump($_POST['fecha_creado']);
		}else {
			echo "Ha ocurrido un error al ejecutar, revisa la consulta!, devolvió: false".var_dump($this->prv_status);
		}
		return $validate;

		} catch (Exception $e) {
			die("Error UserModel->add v3 : ".$e->getMessage());
		}


	}




}



 ?>