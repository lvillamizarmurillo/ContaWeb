<?php 

/**
 * 
 */
require_once 'Config/db_connection/Connection.php'; //solo lo llama si no ha sido invicado.


class EmpresaModel 
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

	 function getDocumentsFailedData(){
        try {
            // Establecer la conexión a la base de datos
            $conn = (new Connection())->startConnection();

            // Consulta SQL para obtener datos de documentos fallidos y exitosos por empresa
            $sql = "SELECT e.razonsocial AS empresa, 
                           SUM(CASE WHEN est.exitoso = false THEN 1 ELSE 0 END) AS documentos_fallidos,
                           SUM(CASE WHEN est.exitoso = true THEN 1 ELSE 0 END) AS documentos_exitosos
                    FROM empresa e
                    JOIN numeracion n ON e.idempresa = n.idempresa
                    JOIN documento d ON n.idnumeracion = d.idnumeracion
                    JOIN estado est ON d.idestado = est.idestado
                    GROUP BY e.idempresa, e.razonsocial";

            // Preparar y ejecutar la consulta
            $exe = $conn->prepare($sql);
            $exe->execute();

            // Obtener los resultados como un array de objetos
            $result = $exe->fetchAll(PDO::FETCH_OBJ);

            return $result; // Devolver resultados
        } catch (PDOException $e) {
            // Capturar y manejar cualquier error de PDO
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return []; // Devolver un array vacío o manejar el error según sea necesario
        }
    }
}