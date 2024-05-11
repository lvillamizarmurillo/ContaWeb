1. # Proyecto Backend PHP - Sistema de Gestión de Documentos Contables

   Este proyecto backend en PHP utiliza el patrón de diseño Modelo-Vista-Controlador (MVC) para gestionar documentos contables de empresas, almacenados en una base de datos PostgreSQL. Proporciona endpoints para consultas y operaciones sobre los documentos, numeraciones, estados y empresas.

   ## Configuración

   ### Requisitos Previos

   - Asegúrate de tener PHP instalado en tu sistema.
   - PostgreSQL debe estar instalado y configurado.

   ### Configuración de la Base de Datos

   1. Ejecuta el script `database_schema.sql` en tu base de datos PostgreSQL para crear las tablas necesarias y poblar datos iniciales.

   ### Configuración de la Aplicación

   1. Copia el archivo `.env.example` y renómbralo a `.env`. Luego, actualiza las variables de entorno con la configuración de tu base de datos.

   ### Ejecución

   1. Inicia tu servidor local PHP, por ejemplo, con el comando `php -S localhost:8000`.
   2. Accede a las rutas definidas en `routes/index.php` para interactuar con la aplicación.

   ## Uso

   Para ejecutar las consultas, sigue estos pasos:

   1. Ejecuta los scripts SQL uno por uno ubicados en la carpeta `backend/script/`.

      - Primero, ejecuta el script `esquema.sql`.
      - Luego, ejecuta el script `insertarDatos.sql`.
      - Por último, revisa el archivo `consultas.sql` para ver y ejecutar las consultas detalladas una por una.

   ---

   A continuación, te presento las consultas detalladas con comentarios:

   ---

   ### Consulta 1: Empresas con Más Documentos Fallidos que Exitosos

   Esta consulta lista las empresas que tienen más documentos fallidos que exitosos.

   ```sql
   SELECT e.idempresa, e.razonsocial,
          COUNT(CASE WHEN es.exitoso THEN 1 END) AS exitosos,
          COUNT(CASE WHEN NOT es.exitoso THEN 1 END) AS fallidos
   FROM empresa e
   INNER JOIN numeracion n ON e.idempresa = n.idempresa
   INNER JOIN documento d ON n.idnumeracion = d.idnumeracion
   INNER JOIN estado es ON d.idestado = es.idestado
   GROUP BY e.idempresa, e.razonsocial
   HAVING COUNT(CASE WHEN NOT es.exitoso THEN 1 END) > COUNT(CASE WHEN es.exitoso THEN 1 END);
   ```

   ### Consulta 2: Facturas, Notas Débito y Notas Crédito por Empresa entre Fechas

   Devuelve la cantidad de facturas, notas débito y notas crédito generadas por cada empresa en un rango de fechas específico.

   ```sql
   SELECT e.razonsocial,
          SUM(CASE WHEN td.description = 'Factura' THEN 1 ELSE 0 END) AS cantidad_facturas,
          SUM(CASE WHEN td.description = 'Debito' THEN 1 ELSE 0 END) AS cantidad_notas_debito,
          SUM(CASE WHEN td.description = 'Credito' THEN 1 ELSE 0 END) AS cantidad_notas_credito
   FROM empresa e
   LEFT JOIN numeracion n ON e.idempresa = n.idempresa
   LEFT JOIN documento d ON n.idnumeracion = d.idnumeracion
   LEFT JOIN tipodocumento td ON n.idtipodocumento = td.idtipodocumento
   WHERE d.fecha BETWEEN 'fecha_inicio' AND 'fecha_fin'
   GROUP BY e.razonsocial;
   ```

   ### Consulta 3: Cantidad de Documentos por Estado y Empresa

   Lista la cantidad de documentos en cada estado para cada empresa.

   ```sql
   SELECT e.razonsocial, es.description AS estado,
          COUNT(*) AS cantidad_documentos
   FROM empresa e
   LEFT JOIN numeracion n ON e.idempresa = n.idempresa
   LEFT JOIN documento d ON n.idnumeracion = d.idnumeracion
   LEFT JOIN estado es ON d.idestado = es.idestado
   GROUP BY e.razonsocial, es.description;
   ```

   ### Consulta 4: Empresas con Más de 3 Documentos No Exitosos

   Identifica las empresas que tienen más de 3 documentos en estados no exitosos.

   ```sql
   SELECT e.razonsocial
   FROM empresa e
   LEFT JOIN numeracion n ON e.idempresa = n.idempresa
   LEFT JOIN documento d ON n.idnumeracion = d.idnumeracion
   LEFT JOIN estado es ON d.idestado = es.idestado
   WHERE NOT es.exitoso
   GROUP BY e.razonsocial
   HAVING COUNT(*) > 3;
   ```

   ### Consulta 5: Documentos con Número o Fecha Fuera del Rango Permitido por la DIAN

   Muestra la cantidad de documentos por empresa que tienen números o fechas fuera del rango permitido por la DIAN.

   ```sql
   SELECT e.razonsocial,
          SUM(CASE WHEN d.fecha NOT BETWEEN n.vigenciainicial AND n.vigenciafinal THEN 1 ELSE 0 END) AS cantidad_fecha_fuera_rango,
          SUM(CASE WHEN d.idnumeracion IS NULL OR d.idnumeracion NOT BETWEEN n.consecutivoinicial AND n.consecutivofinal THEN 1 ELSE 0 END) AS cantidad_numero_fuera_rango
   FROM empresa e
   LEFT JOIN numeracion n ON e.idempresa = n.idempresa
   LEFT JOIN documento d ON n.idnumeracion = d.idnumeracion
   GROUP BY e.razonsocial;
   ```

   ### Consulta 6: Total de Dinero Recibido por Facturas por Empresa

   Calcula el total de dinero recibido (base + impuestos) solo para facturas por cada empresa.

   ```sql
   SELECT e.razonsocial,
          SUM(d.base + d.impuestos) AS total_dinero_recibido
   FROM empresa e
   LEFT JOIN numeracion n ON e.idempresa = n.idempresa
   LEFT JOIN documento d ON n.idnumeracion = d.idnumeracion
   LEFT JOIN tipodocumento td ON n.idtipodocumento = td.idtipodocumento
   WHERE td.description = 'Factura'
   GROUP BY e.razonsocial;
   ```

   ### Consulta 7: Identificar Números Completos Repetidos

   Busca números completos duplicados en la base de datos y cuenta sus repeticiones.

   ```sql
   SELECT prefijo || CAST(n.idnumeracion AS VARCHAR) AS numero_completo,
          COUNT(*) AS cantidad_repeticiones
   FROM numeracion n
   GROUP BY numero_completo
   HAVING COUNT(*) > 1;
   ```

   ---

   Para ejecutar estas consultas, simplemente copia y pega el código SQL en tu entorno de gestión de bases de datos PostgreSQL.

   ## Interfaz

   1. Accede a la carpeta del proyecto desde tu terminal.
   2. Utiliza el siguiente comando para iniciar el frontend: `npm run start`.
   3. Inicia el servidor Apache o levanta la API del backend.
   4. Utiliza los endpoints creados en la página de Dashboard.
   5. Para utilizar cada endpoint, haz clic en "Ver detalles" y completa los datos correspondientes en la interfaz.

   ![](frontend/src/assets/img/Dashboard)