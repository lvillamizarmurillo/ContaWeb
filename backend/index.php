<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

// Middleware para permitir CORS y JSON
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// Importar controladores
require_once 'config/database.php';
require_once 'controller/EmpresaController.php';

$database = new Database();
$db = $database->getConnection();
$empresaController = new EmpresaController($db);

$urlBase = '/ContaWeb/api';

// define las rutas papu
$app->get($urlBase . '/empresas', [$empresaController, 'read']);
//$app->get($urlBase . '/empresas/{id}', [$empresaController, 'readOne']);
//$app->post($urlBase . '/empresas', [$empresaController, 'create']);
//$app->put($urlBase . '/empresas/{id}', [$empresaController, 'update']);
//$app->delete($urlBase . '/empresas/{id}', [$empresaController, 'delete']);

$app->get($urlBase . '/documentsFailedData', [$empresaController, 'getDocumentsFailedData']);
$app->get($urlBase . '/documents/from-each-company', [$empresaController, 'getDocumentsFromEachCompanyData']);
$app->get($urlBase . '/documents/failed/more-than-three', [$empresaController, 'getDocumentsFailedMoreThanThreeData']);
$app->get($urlBase . '/documents/out-of-range', [$empresaController, 'getDocumentsOutOfRangeData']);
$app->get($urlBase . '/documents/total-invoice', [$empresaController, 'getDocumentstotalInvoiceData']);
$app->get($urlBase . '/documents/complete-repeated', [$empresaController, 'getNumberCompleteRepeatedData']);

$app->post($urlBase . '/empresas', [$empresaController, 'createEmpresa']);
$app->patch($urlBase . '/empresas/{id}', [$empresaController, 'updateEmpresa']);



//$app->get($urlBase . '/empresas/{fechaEntrada}/{fechaSalida}', [$empresaController, 'funcionContorlador']);

$app->run();
