<?php
class Database {
    private $host = "localhost";
    private $port = "5432";
    private $db_name = "Contabilidad";
    private $username = "postgres";
    private $password = "123456789";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->exec("set names 'utf8'");
        } catch(PDOException $exception) {
            echo "No se pudo conectar a la base de datos: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
