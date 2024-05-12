<?php
class EmpresaModel {
    private $conn;
    private $table_name = "empresa";

    public $id;
    public $nombre;
    public $direccion;

    public function __construct($db) {
        $this->conn = $db;
    }

    
    public function read() {
        $query = "SELECT id, nombre, direccion FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT id, nombre, direccion FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row : false;
    }

    
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nombre=:nombre, direccion=:direccion";
        $stmt = $this->conn->prepare($query);  // aquí lo que hace es como meter la consulta en un cajón o transacción para proceder a ejecutarla

        // limpiar datos por si le meten un sql inyectshion :v
        $this->nombre=htmlspecialchars(strip_tags($this->nombre));
        $this->direccion=htmlspecialchars(strip_tags($this->direccion));

        // aquí vincula los valores de las propiedades de la clase a los del string donde va cosas como :nombre , :direccion, izquierda clave, derecha valor
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":direccion", $this->direccion);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nombre = :nombre, direccion = :direccion WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->nombre=htmlspecialchars(strip_tags($this->nombre));
        $this->direccion=htmlspecialchars(strip_tags($this->direccion));
        
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":direccion", $this->direccion);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        $this->id=htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


    ### CONSULTAS ###

    public function getDocumentsFailedData()
    {
        try {
            $query = "SELECT e.idempresa, e.razonsocial,
                        COUNT(CASE WHEN es.exitoso THEN 1 END) AS exitosos,
                        COUNT(CASE WHEN NOT es.exitoso THEN 1 END) AS fallidos
                    FROM empresa e
                    INNER JOIN numeracion n ON e.idempresa = n.idempresa
                    INNER JOIN documento d ON n.idnumeracion = d.idnumeracion
                    INNER JOIN estado es ON d.idestado = es.idestado
                    GROUP BY e.idempresa, e.razonsocial
                    HAVING COUNT(CASE WHEN NOT es.exitoso THEN 1 END) > COUNT(CASE WHEN es.exitoso THEN 1 END)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;


        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return []; // Devolver un array vacío o manejar el error según sea necesario
        }
    }



    public function getDocumentsFromEachCompanyData() {
        try {
            $sql = "SELECT e.razonsocial, es.description AS estado,
                    COUNT(*) AS cantidad_documentos
                FROM empresa e
                LEFT JOIN numeracion n ON e.idempresa = n.idempresa
                LEFT JOIN documento d ON n.idnumeracion = d.idnumeracion
                LEFT JOIN estado es ON d.idestado = es.idestado
                GROUP BY e.razonsocial, es.description";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return [];
        }
    }
    
    public function getDocumentsForRangeOfDateDataDinmic() {
        try {
            $sql = "SELECT e.razonsocial,
                        SUM(CASE WHEN td.description = 'Factura' THEN 1 ELSE 0 END) AS cantidad_facturas,
                        SUM(CASE WHEN td.description = 'Debito' THEN 1 ELSE 0 END) AS cantidad_notas_debito,
                        SUM(CASE WHEN td.description = 'Credito' THEN 1 ELSE 0 END) AS cantidad_notas_credito
                 FROM empresa e
                 LEFT JOIN numeracion n ON e.idempresa = n.idempresa
                 LEFT JOIN documento d ON n.idnumeracion = d.idnumeracion
                 LEFT JOIN tipodocumento td ON n.idtipodocumento = td.idtipodocumento
                 WHERE d.fecha BETWEEN '2023-01-01' AND '2023-12-31'
                 GROUP BY e.razonsocial";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return [];
        }
    }



    public function getDocumentsFailedMoreThanThreeData()
    {
        try {
            // Consulta SQL
            $sql = "SELECT e.razonsocial
                    FROM empresa e
                    LEFT JOIN numeracion n ON e.idempresa = n.idempresa
                    LEFT JOIN documento d ON n.idnumeracion = d.idnumeracion
                    LEFT JOIN estado es ON d.idestado = es.idestado
                    WHERE NOT es.exitoso
                    GROUP BY e.razonsocial
                    HAVING COUNT(*) > 3";

            // Preparar y ejecutar la consulta
            $exe = $this->conn->prepare($sql);
            $exe->execute();

            return $exe; // Devolver resultados
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return []; // Devolver un array vacío o manejar el error según sea necesario
        }
    }

    public function getDocumentsOutOfRangeData()
    {
        try {
            // Consulta SQL
            $sql = "SELECT e.razonsocial,
                SUM(CASE WHEN d.fecha NOT BETWEEN n.vigenciainicial AND n.vigenciafinal THEN 1 ELSE 0 END) AS cantidad_fecha_fuera_rango,
                SUM(CASE WHEN d.idnumeracion IS NULL OR d.idnumeracion NOT BETWEEN n.consecutivoinicial AND n.consecutivofinal THEN 1 ELSE 0 END) AS cantidad_numero_fuera_rango
            FROM empresa e
            LEFT JOIN numeracion n ON e.idempresa = n.idempresa
            LEFT JOIN documento d ON n.idnumeracion = d.idnumeracion
            GROUP BY e.razonsocial";

            // Preparar y ejecutar la consulta
            $exe = $this->conn->prepare($sql);
            $exe->execute();

            // Obtener los resultados como un array de objetos

            return $exe; // Devolver resultados
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return []; // Devolver un array vacío o manejar el error según sea necesario
        }
    }

    public function getDocumentstotalInvoiceData()
    {
        try {
            // Consulta SQL
            $sql = "SELECT e.razonsocial,
                SUM(d.base + d.impuestos) AS total_dinero_recibido
            FROM empresa e
            LEFT JOIN numeracion n ON e.idempresa = n.idempresa
            LEFT JOIN documento d ON n.idnumeracion = d.idnumeracion
            LEFT JOIN tipodocumento td ON n.idtipodocumento = td.idtipodocumento
            WHERE td.description = 'Factura'
            GROUP BY e.razonsocial";

            // Preparar y ejecutar la consulta
            $exe = $this->conn->prepare($sql);
            $exe->execute();

            // Obtener los resultados como un array de objetos
            return $exe; // Devolver resultados
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return []; // Devolver un array vacío o manejar el error según sea necesario
        }
    }

    public function getNumberCompleteRepeatedData()
    {
        try {
            // Consulta SQL
            $sql = "SELECT numeracion.idempresa,
                numeracion.prefijo || numeracion.consecutivoinicial AS numerocompleto,
                COUNT(*) AS repeticiones
            FROM numeracion
            GROUP BY numeracion.idempresa, numerocompleto
            HAVING COUNT(*) > 1";

            // Preparar y ejecutar la consulta
            $exe = $this->conn->prepare($sql);
            $exe->execute();

            // Obtener los resultados como un array de objetos
            return $exe; // Devolver resultados
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return []; // Devolver un array vacío o manejar el error según sea necesario
        }
    }


    // EJEMPLO POST
    // insertar uno nuevo
    // Método para insertar una nueva empresa
    public function insertEmpresa($identificacion, $razonsocial) {
        $sql = "INSERT INTO empresa (identificacion, razonsocial) VALUES (:identificacion, :razonsocial)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':identificacion', $identificacion);
        $stmt->bindParam(':razonsocial', $razonsocial);
        if ($stmt->execute()) {
            return $this->conn->lastInsertId(); // Retorna el ID de la empresa insertada
        } else {
            return false;
        }
    }



    // Método para actualizar una empresa existente
    public function updateEmpresa($id, $identificacion, $razonsocial) {
        $sql = "UPDATE empresa SET identificacion = :identificacion, razonsocial = :razonsocial WHERE idempresa = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':identificacion', $identificacion);
        $stmt->bindParam(':razonsocial', $razonsocial);
        return $stmt->execute();
    }



    // ================ ESTO EMPIEZA PAILAS TOCA REVISAR
    // Método para obtener datos de documentos en un rango de fechas específico por empresa
    public function postSaveNewDocumentData()
    {
        try {
            $idnumeracion = $_POST['idnumeracion'];
            $fecha = $_POST['fecha'];
            $base = $_POST['base'];
            $impuestos = $_POST['impuestos'];

            // Validaciones adicionales (puedes implementar aquí)

            // Insertar un nuevo documento en la base de datos
            $sqlInsert = "INSERT INTO documento (idnumeracion, idestado, fecha, base, impuestos) 
                          VALUES (:idnumeracion, 1, :fecha, :base, :impuestos)";

            $stmtInsert = $this->conn->prepare($sqlInsert);
            $stmtInsert->bindParam(':idnumeracion', $idnumeracion);
            $stmtInsert->bindParam(':fecha', $fecha);
            $stmtInsert->bindParam(':base', $base);
            $stmtInsert->bindParam(':impuestos', $impuestos);
            $stmtInsert->execute();




            // Consulta SQL para obtener estadísticas actualizadas
            $sqlStatistics = "SELECT e.razonsocial,
                    SUM(CASE WHEN td.description = 'Factura' THEN 1 ELSE 0 END) AS cantidad_facturas,
                    SUM(CASE WHEN td.description = 'Debito' THEN 1 ELSE 0 END) AS cantidad_notas_debito,
                    SUM(CASE WHEN td.description = 'Credito' THEN 1 ELSE 0 END) AS cantidad_notas_credito
                FROM empresa e
                LEFT JOIN numeracion n ON e.idempresa = n.idempresa
                LEFT JOIN documento d ON n.idnumeracion = d.idnumeracion
                LEFT JOIN tipodocumento td ON n.idtipodocumento = td.idtipodocumento
                WHERE d.fecha BETWEEN :start_date AND :end_date
                GROUP BY e.razonsocial";

            // Preparar y ejecutar la consulta para obtener estadísticas actualizadas
            $stmtStatistics = $this->conn->prepare($sqlStatistics);
            $stmtStatistics->bindParam(':start_date', $this->dateStart);
            $stmtStatistics->bindParam(':end_date', $this->dateEnd);
            $stmtStatistics->execute();

            // Obtener los resultados como un array de objetos
            $result = $stmtStatistics->fetchAll(PDO::FETCH_OBJ);

            // Devolver los resultados
            return $result;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return []; // Devolver un array vacío o manejar el error según sea necesario
        }
    }
    public function putSaveDocumentIdData(){
        try {
            // Obtener los datos del formulario o de la solicitud (supongamos que están en $_POST)
            $idnumeracion = $_POST['idnumeracion'];
            $fecha = $_POST['fecha'];
            $base = $_POST['base'];
            $impuestos = $_POST['impuestos'];

            // Validaciones adicionales (puedes implementar aquí)

            // Insertar un nuevo documento en la base de datos
            $sqlInsert = "INSERT INTO documento (idnumeracion, idestado, fecha, base, impuestos) 
                          VALUES (:idnumeracion, 1, :fecha, :base, :impuestos)";

            $stmtInsert = $this->conn->prepare($sqlInsert);
            $stmtInsert->bindParam(':idnumeracion', $idnumeracion);
            $stmtInsert->bindParam(':fecha', $fecha);
            $stmtInsert->bindParam(':base', $base);
            $stmtInsert->bindParam(':impuestos', $impuestos);
            $stmtInsert->execute();

            // Consulta SQL para obtener estadísticas actualizadas
            $sqlStatistics = "SELECT e.razonsocial,
                      SUM(CASE WHEN td.description = 'Factura' THEN 1 ELSE 0 END) AS cantidad_facturas,
                      SUM(CASE WHEN td.description = 'Debito' THEN 1 ELSE 0 END) AS cantidad_notas_debito,
                      SUM(CASE WHEN td.description = 'Credito' THEN 1 ELSE 0 END) AS cantidad_notas_credito
                FROM empresa e
                LEFT JOIN numeracion n ON e.idempresa = n.idempresa
                LEFT JOIN documento d ON n.idnumeracion = d.idnumeracion
                LEFT JOIN tipodocumento td ON n.idtipodocumento = td.idtipodocumento
                WHERE d.fecha BETWEEN :start_date AND :end_date
                GROUP BY e.razonsocial";

            // Preparar y ejecutar la consulta para obtener estadísticas actualizadas
            $stmtStatistics = $this->conn->prepare($sqlStatistics);
            $stmtStatistics->bindParam(':start_date', $this->dateStart);
            $stmtStatistics->bindParam(':end_date', $this->dateEnd);
            $stmtStatistics->execute();

            // Obtener los resultados como un array de objetos
            $result = $stmtStatistics->fetchAll(PDO::FETCH_OBJ);

            // Devolver los resultados
            return $result;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return []; // Devolver un array vacío o manejar el error según sea necesario
        }
    }

    public function deleteDocumentIdData(){
        try {
            // Verificar si se recibió el ID del documento a eliminar
            if (isset($_POST['documentId'])) {
                // Obtener el ID del documento desde el formulario
                $documentId = $_POST['documentId'];

                // Consulta SQL para eliminar el documento
                $sqlDelete = "DELETE FROM documento WHERE iddocumento = :documentId";

                // Preparar y ejecutar la consulta de eliminación
                $stmtDelete = $this->conn->prepare($sqlDelete);
                $stmtDelete->bindParam(':documentId', $documentId, PDO::PARAM_INT);
                $stmtDelete->execute();

                // Redirigir a una página de éxito o mostrar un mensaje
                header('Location: deleteSuccess.php');
                exit();
            } else {
                // Si no se proporcionó el ID del documento, mostrar un mensaje de error
                echo "ID del documento no especificado.";
            }
        } catch (PDOException $e) {
            // Manejar errores de la base de datos
            echo "Error al eliminar el documento: " . $e->getMessage();
        }
    }




}
