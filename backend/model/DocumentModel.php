<?php

// Cargar variables de entorno desde un archivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Obtener configuración del servidor desde las variables de entorno
$hostname = $_ENV['HOST'];
$port = $_ENV['PORT'];

// Crear el servidor
$server = sprintf('%s:%s', $hostname, $port);

// Función para manejar las solicitudes HTTP
function handleRequest() {
    // Lógica para manejar las solicitudes HTTP
    echo '¡Hola Mundo desde PHP!';
}

// Ejecutar el servidor
$socket = stream_socket_server('tcp://' . $server, $errno, $errstr);

if (!$socket) {
    die("$errstr ($errno)\n");
}

echo "Servidor PHP en ejecución en http://$server\n";

while ($conn = stream_socket_accept($socket)) {
    // Leer y procesar la solicitud HTTP
    $request = fread($conn, 1024);
    handleRequest();
    fwrite($conn, "HTTP/1.1 200 OK\r\nContent-Type: text/html\r\n\r\nHello World");
    fclose($conn);
}

// Cerrar el socket del servidor
fclose($socket);