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

    public function read(Request $request, Response $response) {
        $result = $this->empresaModel->read();
        $num = $result->rowCount();

        if ($num > 0) {
            $empresas_arr = array();
            $empresas_arr["resultados"] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $empresa_item = array(
                    "id" => $id,
                    "nombre" => $nombre,
                    "direccion" => $direccion
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

    public function readOne(Request $request, Response $response, array $args) {
        $id = $args['id'];
        $result = $this->empresaModel->readOne($id);
        if ($result) {
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(array("message" => "Empresa no encontrada.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }


    public function create(Request $request, Response $response) {
        $data = json_decode($request->getBody());

        if (!empty($data->nombre) && !empty($data->direccion)) {
            $this->empresaModel->nombre = $data->nombre;
            $this->empresaModel->direccion = $data->direccion;

            if ($this->empresaModel->create()) {
                $response->getBody()->write(json_encode(array("message" => "La empresa fue creada exitosamente.")));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
            } else {
                $response->getBody()->write(json_encode(array("message" => "No se pudo crear la empresa.")));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(503);
            }
        } else {
            $response->getBody()->write(json_encode(array("message" => "No se pudo crear la empresa. Datos incompletos.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    public function update(Request $request, Response $response, array $args) {
        $data = json_decode($request->getBody());
        $id = $args['id']; // Obtener el ID de la URL

        if (!empty($id) && !empty($data->nombre) && !empty($data->direccion)) {
            $this->empresaModel->id = $id;
            $this->empresaModel->nombre = $data->nombre;
            $this->empresaModel->direccion = $data->direccion;

            if ($this->empresaModel->update()) {
                $response->getBody()->write(json_encode(array("message" => "La empresa fue actualizada exitosamente.")));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } else {
                $response->getBody()->write(json_encode(array("message" => "No se pudo actualizar la empresa.")));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(503);
            }
        } else {
            $response->getBody()->write(json_encode(array("message" => "No se pudo actualizar la empresa. Datos incompletos.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    public function delete(Request $request, Response $response, array $args) {
        $id = $args['id']; // Obtener el ID de la URL

        if (!empty($id)) {
            $this->empresaModel->id = $id;

            if ($this->empresaModel->delete()) {
                $response->getBody()->write(json_encode(array("message" => "La empresa fue eliminada exitosamente.")));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } else {
                $response->getBody()->write(json_encode(array("message" => "No se pudo eliminar la empresa.")));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(503);
            }
        } else {
            $response->getBody()->write(json_encode(array("message" => "No se pudo eliminar la empresa. Datos incompletos.")));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }



    ### ENDPOINTS ###

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
                    "exitosos" => $exitosos,
                    "fallidos" => $fallidos,
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
                    "cantidad_fecha_fuera_rango" => $cantidad_fecha_fuera_rango,
                    "cantidad_numero_fuera_rango" => $cantidad_numero_fuera_rango
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
                    "total_dinero_recibido" => $total_dinero_recibido
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
                    "idempresa" => $idempresa,
                    "numerocompleto" => $numerocompleto,
                    "repeticiones" => $repeticiones
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



    // POST DE REFERENCIA

    // Controlador para manejar la solicitud POST para crear una nueva empresah
    public function createEmpresa(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $empresaId = $this->empresaModel->insertEmpresa($data['identificacion'], $data['razonsocial']);

        if ($empresaId) {
            $response->getBody()->write(json_encode(['message' => 'Empresa creada con éxito', 'id' => $empresaId]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al crear la empresa']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    // Controlador para manejar la solicitud PATCH para actualizar una empresa
    public function updateEmpresa(Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        $id = $args['id']; // Asumiendo que el ID viene como parámetro en la URL, significa que ludwing se la come ;)

        if ($this->empresaModel->updateEmpresa($id, $data['identificacion'], $data['razonsocial'])) {
            $response->getBody()->write(json_encode(['message' => 'Empresa actualizada con éxito']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al actualizar la empresa']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }












}
