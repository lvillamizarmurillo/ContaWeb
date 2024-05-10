<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

require_once __DIR__ . '/../model/DocumentModel.php';
require_once __DIR__ . '/../services/DocumentService.php';

return function (App $app) {
    $app->get('/companies/with-more-failed-than-successful', function (Request $request, Response $response, $args) {
        // Obtener el contenedor de dependencias desde Slim
        $container = $app->getContainer();

        // Obtener una instancia de PDO desde el contenedor
        $pdo = $container->get(PDO::class); // Asumiendo que has registrado PDO en el contenedor

        // Crear una instancia del modelo usando PDO
        $documentModel = new DocumentModel($pdo);

        // Crear una instancia del servicio usando el modelo
        $documentService = new DocumentService($documentModel);

        // Obtener empresas con más documentos fallidos que exitosos
        $companies = $documentService->getCompaniesWithMoreFailedThanSuccessful();

        // Retornar respuesta JSON con las empresas
        $response->getBody()->write(json_encode($companies));
        return $response->withHeader('Content-Type', 'application/json');
    });
};
