<?php 

/**
 * 
 */
require_once 'Config/db_connection/Connection.php'; //solo lo llama si no ha sido invicado.


class UserModel 
{
	//pendiente pasar las variables protegidas a privadas con metodos públicos getters y setters.
	protected $usu_nombre;
	protected $usu_apellido;

	protected $connect;
	
	function __construct()
	{
		# code...
		//$this->connect = new Connection();


	}

	function getUser() //v1
	{		
		$conn = (new Connection())->startConnection();
		$sql = "SELECT * FROM usuarios";
		$exe = $conn->prepare($sql);
		$exe->execute();
		$getUser_response = $exe->FetchALL(PDO::FETCH_OBJ); //me devuelve un array de objetos del volcado de prepare
		//return $getUser_response
		return $getUser_response;
	}

	protected function getUserMulti($tables, $wheres) //v3 multifuncional todo tipo
	{		
		$conn = (new Connection())->startConnection();
		$sql = "SELECT * FROM {$tables} {$wheres}";
		$exe = $conn->prepare($sql);
		$exe->execute();
		$getUser_response = $exe->FetchALL(PDO::FETCH_OBJ); //me devuelve un array de objetos del volcado de execute
		//return $exe->FetchALL(PDO::FETCH_OBJ)
		var_dump($getUser_response);
	}


	function addUser() //v1 se realiza con los datos capturados de las variables de esta clase.
	{
		$conn = (new Connection())->startConnection();
		$sql = "INSERT INTO usuarios(usu_nombre, usu_apellido) 
		VALUES(:nombre, :apellido)";
		$exe = $conn->prepare($sql);
		$exe->bindParam(':nombre', $this->usu_nombre);
		$exe->bindParam(':apellido', $this->usu_apellido);
		$exe->execute();


	}


	function addUserv2($data) //v2 array,se realiza con los datos que se envían de la vista capturados de un formulario mediante post al controller.
	{
		try {
			$conn = (new Connection())->startConnection();
			$sql = "INSERT INTO usuarios(usu_nombre, usu_apellido) 
			VALUES(:nombre, :apellido)";
			$exe = $conn->prepare($sql);
			$exe->bindParam(':nombre', $data['Nombre']);
			$exe->bindParam(':apellido', $data['Apellido']);
			$exe->execute();
			return $exe;
		} catch (Exception $e) {
			die("Error UserModel->add v3 : ".$e->getMessage());
		}

	}





}




 ?>