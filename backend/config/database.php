<?php
require __DIR__ . '/../vendor/autoload.php'; // Cargar el autoloader de Composer
// Cargar variables de entorno desde un archivo .env de forma segura
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Obtener variables de entorno
$host = $_ENV['DB_HOST'] ?? null;
$port = $_ENV['DB_PORT'] ?? null;
$dbname = $_ENV['DBNAME'] ?? null;
$username = $_ENV['DB_USERNAME'] ?? null;
$password = $_ENV['DB_PASSWORD'] ?? null;

if ($host === null || $port === null || $dbname === null || $username === null || $password === null) {
    die('Error: Variables de entorno no configuradas correctamente, revisa las variables de la base de datos.');
}

try {
    // Crear una instancia de conexión PDO
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);

    // Configurar el modo de error y excepción
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retornar la instancia de conexión PDO
    return $pdo;
} catch (PDOException $e) {
    // Manejar errores de conexión
    die("Error en la conexión a la base de datos: " . $e->getMessage());
}

