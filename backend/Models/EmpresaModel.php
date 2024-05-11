<?php

class EmpresaModel 
{
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

    // Getters y Setters
    public function getDateStart()
    {
        return $this->dateStart;
    }

    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
    }

    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;
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
    
    public function getdocumentsFailedMoreThanThreeData()
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
    
    public function getdocumentsOutOfRangeData()
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
    
    public function getdocumentstotalInvoiceData()
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
    
    public function numberCompleteRepeatedData()
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
    public function getDocumentsInDateEspecificData()
    {
        try {
            // Consulta SQL utilizando las fechas almacenadas en las propiedades
            $sql = "SELECT e.razonsocial,
                        SUM(CASE WHEN td.description = 'Factura' THEN 1 ELSE 0 END) AS cantidad_facturas,
                        SUM(CASE WHEN td.description = 'Debito' THEN 1 ELSE 0 END) AS cantidad_notas_debito,
                        SUM(CASE WHEN td.description = 'Credito' THEN 1 ELSE 0 END) AS cantidad_notas_credito
                    FROM empresa e
                    LEFT JOIN numeracion n ON e.idempresa = n.idempresa
                    LEFT JOIN documento d ON n.idnumeracion = d.idnumeracion
                    LEFT JOIN tipodocumento td ON n.idtipodocumento = td.idtipodocumento
                    WHERE d.fecha BETWEEN :start_date AND :end_date
                    GROUP BY e.razonsocial";

            // Preparar y ejecutar la consulta con parámetros
            $exe = $this->connect->prepare($sql);
            $exe->bindParam(':start_date', $this->dateStart);
            $exe->bindParam(':end_date', $this->dateEnd);
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
}
