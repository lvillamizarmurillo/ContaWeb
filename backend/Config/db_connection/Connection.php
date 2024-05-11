<?php 


class Connection {
    private $driver;
    private $host;
    private $port;
    private $db;
    private $user;
    private $pass;
    private $charset; // Definir la propiedad $charset

    public function __construct()
    {
        $this->driver = constant('DRIVER');
        $this->host = constant('HOST');
        $this->port = constant('PORT');
        $this->db = constant('DB');
        $this->user = constant('USER');
        $this->pass = constant('PASS');
        $this->charset = constant('CHARSET'); // Asignar valor a $charset
    }

    public function startConnection()
    {
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db};user={$this->user};password={$this->pass}";
            $pdo_connection = new PDO($dsn);

            // Configurar la codificación de caracteres
            $pdo_connection->exec("SET NAMES '{$this->charset}'");

            return $pdo_connection;
        } catch (PDOException $e) {
            echo '<br><br> <div style="font-family: Arial; font-size: 13px; text-decoration: none; width:120%; margin-left:-10px; padding: 10px; background-color: #2175bc; color: #fff;"> 
                Error al iniciar la conexión: ' . utf8_encode($e->getMessage()) . '
                </div>';
        }
    }
}

// Uso de la clase Connection
$start = new Connection();
$pdo = $start->startConnection();




 ?>