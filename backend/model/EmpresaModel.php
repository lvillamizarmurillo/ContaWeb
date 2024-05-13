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
            $sql ="SELECT e.idempresa, e.identificacion, e.razonsocial,
                        estadisticas.cantidad_documentos_exitosos AS documentos_exitosos,
                        estadisticas.cantidad_documentos_fallidos AS documentos_fallidos
                FROM empresa e
                INNER JOIN (
                     SELECT n.idempresa,
                            SUM(CASE WHEN es.exitoso = true THEN 1 ELSE 0 END) AS cantidad_documentos_exitosos,
                            SUM(CASE WHEN es.exitoso = false THEN 1 ELSE 0 END) AS cantidad_documentos_fallidos
                     FROM numeracion n
                     INNER JOIN documento d ON n.idnumeracion = d.idnumeracion
                     INNER JOIN estado es ON d.idestado = es.idestado
                     GROUP BY n.idempresa
                ) AS estadisticas ON e.idempresa = estadisticas.idempresa
                WHERE estadisticas.cantidad_documentos_fallidos > estadisticas.cantidad_documentos_exitosos";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt;


        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return []; // Devolver un array vacío o manejar el error según sea necesario
        }
    }

    
    public function getDocumentsForDateRangeData($dateStart,$dateEnd) {
        try {
            $sql ="SELECT e.idempresa, e.identificacion, e.razonsocial,
                        COUNT(CASE WHEN td.description = 'Factura' THEN 1 END) AS total_facturas,
                        COUNT(CASE WHEN td.description = 'Debito' THEN 1 END) AS total_notas_debito,
                        COUNT(CASE WHEN td.description = 'Credito' THEN 1 END) AS total_notas_credito
                FROM empresa e
                LEFT JOIN numeracion n ON e.idempresa = n.idempresa
                LEFT JOIN documento d ON n.idnumeracion = d.idnumeracion
                LEFT JOIN tipodocumento td ON n.idtipodocumento = td.idtipodocumento
                WHERE d.fecha BETWEEN '".$dateStart."' AND '".$dateEnd."'
                GROUP BY e.idempresa, e.identificacion, e.razonsocial";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return [];
        }
    }


    public function getDocumentsFromEachCompanyData() {
        try {
            $sql ="SELECT e.idempresa, e.identificacion, e.razonsocial,
                    es.description AS estado,
                    COUNT(*) AS cantidad_documentos
            FROM empresa e
            INNER JOIN numeracion n ON e.idempresa = n.idempresa
            INNER JOIN documento d ON n.idnumeracion = d.idnumeracion
            INNER JOIN estado es ON d.idestado = es.idestado
            GROUP BY e.idempresa, e.identificacion, e.razonsocial, es.description
            ORDER BY e.idempresa, es.description";
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
            $sql = "SELECT e.idempresa, e.identificacion, e.razonsocial
                FROM empresa e
                INNER JOIN numeracion n ON e.idempresa = n.idempresa
                INNER JOIN documento d ON n.idnumeracion = d.idnumeracion
                INNER JOIN estado es ON d.idestado = es.idestado
                WHERE es.exitoso = false
                GROUP BY e.idempresa
                HAVING COUNT(CASE WHEN es.exitoso = false THEN 1 END) > 3";

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
            $sql = "SELECT e.idempresa, e.identificacion, e.razonsocial,
                            COUNT(*) AS cantidad_documentos_fuera_de_rango
                    FROM empresa e
                    INNER JOIN numeracion n ON e.idempresa = n.idempresa
                    INNER JOIN documento d ON n.idnumeracion = d.idnumeracion
                    WHERE substring(d.numero FROM '[0-9]+')::INTEGER < n.consecutivoinicial
                        OR substring(d.numero FROM '[0-9]+')::INTEGER > n.consecutivofinal
                        OR d.fecha < n.vigenciainicial
                        OR d.fecha > n.vigenciafinal
                    GROUP BY e.idempresa, e.identificacion, e.razonsocial
                    ORDER BY e.idempresa";

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
            $sql = "SELECT e.idempresa, e.identificacion, e.razonsocial,
                    SUM(CASE WHEN td.description = 'Factura' THEN (d.base + d.impuestos) ELSE 0 END) AS total_facturas,
                    SUM(CASE WHEN td.description = 'Debito' THEN (d.base + d.impuestos) ELSE 0 END) AS total_notas_debito
            FROM empresa e
            INNER JOIN numeracion n ON e.idempresa = n.idempresa
            INNER JOIN documento d ON n.idnumeracion = d.idnumeracion
            INNER JOIN tipodocumento td ON n.idtipodocumento = td.idtipodocumento
            GROUP BY e.idempresa, e.identificacion, e.razonsocial";

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
            $sql = "SELECT CONCAT(n.prefijo, d.numero) AS numero_completo,
                            COUNT(*) AS cantidad_repeticiones
                    FROM numeracion n
                    INNER JOIN documento d ON n.idnumeracion = d.idnumeracion
                    GROUP BY numero_completo
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


    public function postSearchDocumentData($numDomuent)
    {
        try {
            $sql = "SELECT e.idempresa, e.identificacion, e.razonsocial,
                    td.description AS tipo_documento
                    FROM empresa e
                    INNER JOIN numeracion n ON e.idempresa = n.idempresa
                    INNER JOIN documento d ON n.idnumeracion = d.idnumeracion
                    INNER JOIN tipodocumento td ON n.idtipodocumento = td.idtipodocumento
                    WHERE d.numero = '".$numDomuent."';
";

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
    
    public function getSearchNumeration()
    {
        try {
            // Consulta SQL
            $sql = "SELECT e.razonsocial AS nombre_empresa,
                            td.description AS tipo_documento,
                            n.prefijo,
                            n.consecutivoinicial,
                            n.consecutivofinal,
                            n.idnumeracion
                    FROM numeracion n
                    INNER JOIN empresa e ON n.idempresa = e.idempresa
                    INNER JOIN tipodocumento td ON n.idtipodocumento = td.idtipodocumento";

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
    
    public function postNewDocumentData($data){
        try {
            $sql = "INSERT INTO documento (idnumeracion, idestado, numero, fecha, base, impuestos) 
                    VALUES (:idnumeracion, :idestado, :numero, :fecha, :base, :impuestos)";

            // Preparar y ejecutar la consulta con los valores
            $exe = $this->conn->prepare($sql);
            $exe->bindParam(':idnumeracion', $data['idnumeracion']);
            $exe->bindParam(':idestado', $data['idestado']);
            $exe->bindParam(':numero', $data['numero']);
            $exe->bindParam(':fecha', $data['fecha']);
            $exe->bindParam(':base', $data['base']);
            $exe->bindParam(':impuestos', $data['impuestos']);
            $exe->execute();

            return $exe;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return []; // Devolver un array vacío o manejar el error según sea necesario
        }
    }
  
    public function putDocumentData($data){
        try {
            $sql = "UPDATE documento SET idestado = :idestado, fecha = :fecha, base = :base, impuestos = :impuestos
                WHERE idnumeracion = :idnumeracion AND numero = :numero";

            // Preparar y ejecutar la consulta con los valores
            $exe = $this->conn->prepare($sql);
            $exe->bindParam(':idnumeracion', $data['idnumeracion']);
            $exe->bindParam(':idestado', $data['idestado']);
            $exe->bindParam(':numero', $data['numero']);
            $exe->bindParam(':fecha', $data['fecha']);
            $exe->bindParam(':base', $data['base']);
            $exe->bindParam(':impuestos', $data['impuestos']);
            $exe->execute();

            return $exe;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return []; // Devolver un array vacío o manejar el error según sea necesario
        }
    }
    
    public function deleteDocumentData($data){
        try {
            // Consulta SQL para eliminar el documento por idnumeracion
            $sql = "DELETE FROM documento WHERE idnumeracion = :idnumeracion AND numero = :numero";

            // Preparar y ejecutar la consulta con el idnumeracion proporcionado
            $exe = $this->conn->prepare($sql);
            $exe->bindParam(':idnumeracion', $data['idnumeracion']);
            $exe->bindParam(':numero', $data['numero']);
            $exe->execute();

            return $exe->rowCount(); // Devolver la cantidad de filas afectadas
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return false; // Devolver false u otra señal de error según sea necesario
        }
    }
}
