<?php
include_once 'model/EmpresaModel.php';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EmpresaController {
    private $conn;
    private $empresaModel;

    public function __construct($db) {
        $this->conn = $db;
        $this->empresaModel = new EmpresaModel($db);
    }
    ### ENDPOINTS ###
     public function getDocumentsforDelete(Request $request, Response $response) {
        $result = $this->empresaModel->getDocumentsforDelete();
        $num = $result->rowCount();

        if ($num > 0) {
            $empresas_arr = array();
            $empresas_arr["resultados"] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
//                var_dump( $row ); die;
                $empresa_item = array(
                    "nombre_empresa" => $nombre_empresa,
                    "numero" => $numero,
                    "tipo_documento" => $tipo_documento,
                    "estado_documento" => $estado_documento
                );
                array_push($empresas_arr["resultados"], $empresa_item);
            }
            $response->getBody()->write(json_encode($empresas_arr));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(array("message" => "No se encontraron empresas.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
    
    
    public function getDocumentsFailedData(Request $request, Response $response) {
        $result = $this->empresaModel->getDocumentsFailedData();
        $num = $result->rowCount();

        if ($num > 0) {
            $empresas_arr = array();
            $empresas_arr["resultados"] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
//                var_dump( $row ); die;
                $empresa_item = array(
                    "id" => $idempresa,
                    "nombre" => $razonsocial,
                    "exitosos" => $documentos_exitosos,
                    "fallidos" => $documentos_fallidos,
                );
                array_push($empresas_arr["resultados"], $empresa_item);
            }
            $response->getBody()->write(json_encode($empresas_arr));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(array("message" => "No se encontraron empresas.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
    
    public function getDocumentsForDateRangeData(Request $request, Response $response, array $args) {
        $dateStart = $args['dateStart'];
        $dateEnd = $args['dateEnd'];
        $result = $this->empresaModel->getDocumentsForDateRangeData($dateStart,$dateEnd);
        $num = $result->rowCount();
        if ($num > 0) {
            $empresas_arr = array();
            $empresas_arr["resultados"] = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $empresa_item = array(
                    "razonsocial" => $razonsocial,
                    "cantidad_facturas" => $total_facturas,
                    "cantidad_notas_debito" => $total_notas_debito,
                    "cantidad_notas_credito" => $total_notas_credito
                );
                array_push($empresas_arr["resultados"], $empresa_item);
            }
            $response->getBody()->write(json_encode($empresas_arr));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(array("message" => "Empresa no encontrada.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }



    public function getDocumentsFromEachCompanyData(Request $request, Response $response) {
        $result = $this->empresaModel->getDocumentsFromEachCompanyData();
        $num = $result->rowCount();

        if ($num > 0) {
            $empresas_arr = array();
            $empresas_arr["resultados"] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $empresa_item = array(
                    "razonsocial" => $razonsocial,
                    "estado" => $estado,
                    "cantidad_documentos" => $cantidad_documentos
                );
                array_push($empresas_arr["resultados"], $empresa_item);
            }
            $response->getBody()->write(json_encode($empresas_arr));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(array("message" => "No se encontraron datos.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }


    public function getDocumentsFailedMoreThanThreeData(Request $request, Response $response) {
        $result = $this->empresaModel->getDocumentsFailedMoreThanThreeData();
        $num = $result->rowCount();

        if ($num > 0) {
            $empresas_arr = array();
            $empresas_arr["resultados"] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $empresa_item = array(
                    "identificacion" => $identificacion,
                    "razonsocial" => $razonsocial
                );
                array_push($empresas_arr["resultados"], $empresa_item);
            }
            $response->getBody()->write(json_encode($empresas_arr));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(array("message" => "No se encontraron empresas con más fallos que éxitos.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }


    public function getDocumentsOutOfRangeData(Request $request, Response $response) {
        $result = $this->empresaModel->getDocumentsOutOfRangeData();
        $num = $result->rowCount();

        if ($num > 0) {
            $empresas_arr = array();
            $empresas_arr["resultados"] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $empresa_item = array(
                    "razonsocial" => $razonsocial,
                    "identificacion" => $identificacion,
                    "cantidad_documentos_fuera_de_rango" => $cantidad_documentos_fuera_de_rango
                );
                array_push($empresas_arr["resultados"], $empresa_item);
            }
            $response->getBody()->write(json_encode($empresas_arr));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(array("message" => "No se encontraron documentos fuera de rango.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }


    public function getDocumentstotalInvoiceData(Request $request, Response $response) {
        $result = $this->empresaModel->getDocumentstotalInvoiceData();
        $num = $result->rowCount();

        if ($num > 0) {
            $empresas_arr = array();
            $empresas_arr["resultados"] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row); //DESTRUCTURA EL ARRAY
//                $razonsocial = $razonsocial . " OTRAINFO";
                $empresa_item = array(
                    "razonsocial" => $razonsocial,
                    "identificacion" => $identificacion,
                    "total_facturas" => $total_facturas,
                    "total_notas_debito" => $total_notas_debito
                );
                array_push($empresas_arr["resultados"], $empresa_item);
            }
            $response->getBody()->write(json_encode($empresas_arr));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(array("message" => "No se encontraron ingresos de facturas.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }


    public function getNumberCompleteRepeatedData(Request $request, Response $response) {
        $result = $this->empresaModel->getNumberCompleteRepeatedData();
        $num = $result->rowCount();

        if ($num > 0) {
            $numeros_arr = array();
            $numeros_arr["resultados"] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $numero_item = array(
                    "cantidad_repeticiones" => $cantidad_repeticiones,
                    "numero_completo" => $numero_completo
                );
                array_push($numeros_arr["resultados"], $numero_item);
            }
            $response->getBody()->write(json_encode($numeros_arr));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(array("message" => "No se encontraron números completos repetidos.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
    
    public function postSearchDocumentData(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $result = $this->empresaModel->postSearchDocumentData($data['numDocument']);
        $num = $result->rowCount();

        if ($num > 0) {
            $numeros_arr = array();
            $numeros_arr["resultados"] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $numero_item = array(
                    "numero_documento" => $numero_documento,
                    "fecha_emision" => $fecha_emision,
                    "valor_base" => $valor_base,
                    "valor_impuestos" => $valor_impuestos,
                    "nombre_empresa" => $nombre_empresa,
                    "tipo_documento" => $tipo_documento,
                    "estado_documento" => $estado_documento
                );
                array_push($numeros_arr["resultados"], $numero_item);
            }
            $response->getBody()->write(json_encode($numeros_arr));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(array("message" => "No se encontraron números completos repetidos.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }

    public function getDocumentCompleteData(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $result = $this->empresaModel->getDocumentCompleteData();
        $num = $result->rowCount();

        if ($num > 0) {
            $numeros_arr = array();
            $numeros_arr["resultados"] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $numero_item = array(
                    "numero_documento" => $numero_documento,
                    "fecha_emision" => $fecha_emision,
                    "valor_base" => $valor_base,
                    "valor_impuestos" => $valor_impuestos,
                    "nombre_empresa" => $nombre_empresa,
                    "tipo_documento" => $tipo_documento,
                    "estado_documento" => $estado_documento,
                    "idnumeracion" => $idnumeracion
                );
                array_push($numeros_arr["resultados"], $numero_item);
            }
            $response->getBody()->write(json_encode($numeros_arr));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(array("message" => "No se encontraron números completos repetidos.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
    
    public function postNewDocumentData(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $result = $this->empresaModel->postNewDocumentData($data);

        if ($result) {
            // Documento creado con éxito
            $response->getBody()->write(json_encode(array("message" => "Documento creado con éxito.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201); // Código 201 para "Created"
        } else {
            // Error al crear el documento
            $response->getBody()->write(json_encode(array("message" => "Error al crear el documento.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500); // Código 500 para "Internal Server Error"
        }
    }
    
    public function getSearchNumeration(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $result = $this->empresaModel->getSearchNumeration();
        $num = $result->rowCount();

        if ($num > 0) {
            $numeros_arr = array();
            $numeros_arr["resultados"] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $numero_item = array(
                    "empresa" => $nombre_empresa,
                    "idnumeracion" => $idnumeracion,
                    "tipo_documento" => $tipo_documento,
                    "prefijo" => $prefijo,
                    "consecutivoinicial" => $consecutivoinicial,
                    "consecutivofinal" => $consecutivofinal,
                    "vigenciainicial" => $vigenciainicial,
                    "vigenciafinal" => $vigenciafinal
                );
                array_push($numeros_arr["resultados"], $numero_item);
            }
            $response->getBody()->write(json_encode($numeros_arr));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            // Error al crear el documento
            $response->getBody()->write(json_encode(array("message" => "Error al crear el documento.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500); // Código 500 para "Internal Server Error"
        }
    }
    
    public function putDocumentData(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $result = $this->empresaModel->putDocumentData($data);

        if ($result) {
            // Documento creado con éxito
            $response->getBody()->write(json_encode(array("message" => "Documento actualizado con éxito.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201); // Código 201 para "Created"
        } else {
            // Error al crear el documento
            $response->getBody()->write(json_encode(array("message" => "Error al actualizar el documento.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500); // Código 500 para "Internal Server Error"
        }
    }

    public function deleteDocumentData(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $result = $this->empresaModel->deleteDocumentData($data);

        if ($result) {
            // Documento creado con éxito
            $response->getBody()->write(json_encode(array("message" => "Documento eliminado con éxito.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201); // Código 201 para "Created"
        } else {
            // Error al crear el documento
            $response->getBody()->write(json_encode(array("message" => "Error al eliminar el documento.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500); // Código 500 para "Internal Server Error"
        }
    }
}
