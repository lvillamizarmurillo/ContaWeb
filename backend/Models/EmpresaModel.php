<?php

class EmpresaModel {
    // Variables protegidas
    protected $dateStart;
    protected $dateEnd;
    protected $connect;

    // Constructor
    public function __construct()
    {
        // Inicializar la conexión a la base de datos
        $this->connect = (new Connection())->startConnection();
    }
    

    // Método para obtener datos de documentos fallidos y exitosos por empresa
    public function getDocumentsFailedData()
    {
        try {
            // Consulta SQL
            $sql = "SELECT e.idempresa, e.razonsocial,
                        COUNT(CASE WHEN es.exitoso THEN 1 END) AS exitosos,
                        COUNT(CASE WHEN NOT es.exitoso THEN 1 END) AS fallidos
                    FROM empresa e
                    INNER JOIN numeracion n ON e.idempresa = n.idempresa
                    INNER JOIN documento d ON n.idnumeracion = d.idnumeracion
                    INNER JOIN estado es ON d.idestado = es.idestado
                    GROUP BY e.idempresa, e.razonsocial
                    HAVING COUNT(CASE WHEN NOT es.exitoso THEN 1 END) > COUNT(CASE WHEN es.exitoso THEN 1 END)";

            // Preparar y ejecutar la consulta
            $exe = $this->connect->prepare($sql);
            $exe->execute();

            // Obtener los resultados como un array de objetos
            $result = $exe->fetchAll(PDO::FETCH_OBJ);
            echo $result;
            return $result; // Devolver resultados
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return []; // Devolver un array vacío o manejar el error según sea necesario
        }
    }
    
    public function getDocumentsFromEachCompanyData()
    {
        try {
            // Consulta SQL
            $sql = "SELECT e.razonsocial, es.description AS estado,
                        COUNT(*) AS cantidad_documentos
                    FROM empresa e
                    LEFT JOIN numeracion n ON e.idempresa = n.idempresa
                    LEFT JOIN documento d ON n.idnumeracion = d.idnumeracion
                    LEFT JOIN estado es ON d.idestado = es.idestado
                    GROUP BY e.razonsocial, es.description";

            // Preparar y ejecutar la consulta
            $exe = $this->connect->prepare($sql);
            $exe->execute();

            // Obtener los resultados como un array de objetos
            $result = $exe->fetchAll(PDO::FETCH_OBJ);
            echo $result;
            return $result; // Devolver resultados
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return []; // Devolver un array vacío o manejar el error según sea necesario
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
            $exe = $this->connect->prepare($sql);
            $exe->execute();

            // Obtener los resultados como un array de objetos
            $result = $exe->fetchAll(PDO::FETCH_OBJ);
            echo $result;
            return $result; // Devolver resultados
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
            $exe = $this->connect->prepare($sql);
            $exe->execute();

            // Obtener los resultados como un array de objetos
            $result = $exe->fetchAll(PDO::FETCH_OBJ);
            echo $result;
            return $result; // Devolver resultados
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
            $exe = $this->connect->prepare($sql);
            $exe->execute();

            // Obtener los resultados como un array de objetos
            $result = $exe->fetchAll(PDO::FETCH_OBJ);
            echo $result;
            return $result; // Devolver resultados
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
            $exe = $this->connect->prepare($sql);
            $exe->execute();

            // Obtener los resultados como un array de objetos
            $result = $exe->fetchAll(PDO::FETCH_OBJ);
            echo $result;
            return $result; // Devolver resultados
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return []; // Devolver un array vacío o manejar el error según sea necesario
        }
    }
    
    
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

            $stmtInsert = $this->connect->prepare($sqlInsert);
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
            $stmtStatistics = $this->connect->prepare($sqlStatistics);
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

            $stmtInsert = $this->connect->prepare($sqlInsert);
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
            $stmtStatistics = $this->connect->prepare($sqlStatistics);
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
                $stmtDelete = $this->connect->prepare($sqlDelete);
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
