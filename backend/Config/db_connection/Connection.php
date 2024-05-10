<?php 


class Connection{

	private $driver;
	private $host;
	private $db;
	private $user;
	private $pass;
	private $charset;



	public function __construct()
	{		
		$this->driver = constant('DRIVER');
		$this->host = constant('HOST');
		$this->port = constant('PORT');
		$this->db = constant('DB');
		$this->user = constant('USER');
		$this->pass = constant('PASS');
		$this->charset = constant('CHARSET');		
	}
	//Se maneja con instancias para que pueda ser utilizado por muchos usuarios sin generar conflicto como lo sería si fuera static, que puede generar excepciones o corromper datos si se trabaja en una misma inserción varios usuarios en la misma conexión abierta.
	function startConnection(){
		try{
			/*$dsn = "{$this->driver}:host={$this->host};port={$this->port};dbname={$this->db};charset={$this->charset}"; */
			
			$dsn = $this->driver.':host='.$this->host.';port='.$this->port.';dbname='.$this->db.';charset='.$this->charset;  //data_source_name/preparo la fuente de datos de la bd.

			#instancio/inicio la conexión
			$pdo_connection = new PDO($dsn, $this->user, $this->pass);

			return $pdo_connection; //retorno la conexión objeto pdo


		}catch(PDOException $e){

			echo '<br><br> <div style="font-family: Arial;
			font-size: 13px;
			text-decoration: none; 
			width:120%; margin-left:-10px;
			padding: 10px;
			background-color: #2175bc;
			color: #fff;"> 
			Error al iniciar la conexión: '.utf8_encode($e->getMessage()).'
			</div>'; #se codifica el error a utf8 para tildes, etc.
		}	





	}



}

 $start = new Connection(); //tests
 $start->startConnection();


 ?>