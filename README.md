## Configuración y Ejecución de ContaWeb

### Clonar el Repositorio

1. Clonar el Repositorio:

   Comienza clonando el repositorio de ContaWeb desde GitHub. Abre una terminal y ejecuta los siguientes comandos:

   ```
   bashCopiar código git clone https://github.com/lvillamizarmurillo/ContaWeb.git
   ```

### Instalación y Configuración del Backend

1. **Instalar Dependencias con Composer:** Accede a la carpeta del backend de ContaWeb e instala las dependencias utilizando Composer:

   ```
   bashCopiar códigocd backend
   composer install
   ```

2. **Configurar la Conexión a la Base de Datos:** Abre el archivo `config/database.php` y asegúrate de configurar correctamente los detalles de conexión a la base de datos PostgreSQL:

   ```
   phpCopiar códigoprivate $host = "localhost";
   private $port = "5051";
   private $db_name = "contabilidad_diario";
   private $username = "postgres";
   private $password = "12345678";
   ```

### Base de Datos

1. Ejecutar los Scripts SQL:

   Utiliza pgAdmin 4 u otra herramienta para administrar PostgreSQL y ejecuta los siguientes scripts SQL ubicados en la carpeta 

   ```
   backend/scripts/sql
   ```

   :

   - `databaseStructure.sql`: Este script contiene la estructura inicial de la base de datos.
   - `insertData.sql`: Contiene los datos iniciales que se deben insertar en la base de datos.

### Instalación de PDO_PGSQL en Windows:

1. **Verificar PHP:** Asegúrate de tener PHP instalado en tu sistema. Puedes descargar PHP desde el sitio oficial: [PHP Downloads](https://www.php.net/downloads)

2. **Editar php.ini:** Localiza el archivo `php.ini` de tu instalación de PHP. Puedes encontrarlo en la carpeta donde está instalado PHP (por ejemplo, `C:\php`).

3. **Habilitar extensiones:** Abre `php.ini` con un editor de texto y busca la sección de extensiones. Asegúrate de descomentar (quitar el punto y coma `;`) la línea correspondiente a `extension=pdo_pgsql`.

   Ejemplo:

   ```
   ini
   Copiar código
   extension=pdo_pgsql
   ```

4. **Reiniciar el servidor web:** Después de realizar los cambios en `php.ini`, reinicia tu servidor web (por ejemplo, Apache) para que los cambios surtan efecto.

5. **Verificar la instalación:** Para confirmar que la extensión `pdo_pgsql` está habilitada, puedes crear un archivo `info.php` con el siguiente contenido:

   ```
   <?php
   phpinfo();
   ?>
   ```

​	Coloca este archivo en tu directorio web (`htdocs` si usas Apache). Abre este archivo en tu navegador 	(`http://localhost/info.php`) y busca la sección de `PDO drivers`. Deberías ver `pgsql` en la lista si la 	extensión se ha habilitado correctamente.

### Configuración de Apache y URLs

1. **Configurar Apache para PHP 8.3:** Asegúrate de tener configurado Apache para que funcione con PHP 8.3.

2. **Rutas y Redirecciones:** Todas las peticiones se redirigen a `index.php` gracias a la configuración en el archivo `.htaccess`. Por ejemplo, una URL válida sería:

   ```
   bash
   Copiar código
   http://localhost:80/ContaWeb/backend/documentsFailedData
   ```

### Verificación del Backend

Puedes cambiar el puerto en el siguiente archivo:

ContaWeb/frontend/src/js/script.js en :

![](/home/uwuntu/Documentos/ContaWeb/frontend/src/assets/img/variableGlobal.png)

Verificar el Funcionamiento del Backend:

Abre un navegador web y accede a una URL para verificar que el backend esté funcionando 		     correctamente:

```
bash
Copiar código
http://localhost:80/ContaWeb/backend/documentsFailedData
```

------

Estos pasos detallan cómo clonar el repositorio de ContaWeb, instalar las dependencias del backend, configurar la conexión a la base de datos PostgreSQL, ejecutar los scripts SQL necesarios, configurar Apache y verificar el funcionamiento del backend accediendo a una URL específica.

Asegúrate de adaptar los comandos y las rutas según la configuración y la estructura de tu entorno de desarrollo. Esta guía te permitirá configurar y ejecutar ContaWeb de manera efectiva para verificar el correcto funcionamiento del backend.

## Consultas SQL y Endpoints

A continuación, se detallan las consultas SQL junto con sus respectivas URLs, explicaciones y endpoints completos para el backend de ContaWeb.

### Consulta 1: Empresas con Más Documentos Fallidos que Exitosos

- **URL para Ver la Consulta:**
  http://localhost:80/ContaWeb/backend/documentsFailedData

- **Explicación:** Esta consulta lista las empresas que tienen más documentos fallidos que exitosos.

- **Endpoint Completo:**

  ```
  sqlCopiar códigoSELECT e.idempresa, e.identificacion, e.razonsocial,
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
  WHERE estadisticas.cantidad_documentos_fallidos > estadisticas.cantidad_documentos_exitosos;
  ```

### Consulta 2: Facturas, Notas Débito y Notas Crédito por Empresa entre Fechas

- **URL para Ver la Consulta:**
  http://localhost:80/ContaWeb/backend/documents/for-range-date/2020-01-01/2020-12-31

- **Explicación:** Devuelve la cantidad de facturas, notas débito y notas crédito generadas por cada empresa en un rango de fechas específico.

- **Endpoint Completo:**

  ```
  sqlCopiar códigoSELECT e.idempresa, e.identificacion, e.razonsocial,
         COUNT(CASE WHEN td.description = 'Factura' THEN 1 END) AS total_facturas,
         COUNT(CASE WHEN td.description = 'Debito' THEN 1 END) AS total_notas_debito,
         COUNT(CASE WHEN td.description = 'Credito' THEN 1 END) AS total_notas_credito
  FROM empresa e
  LEFT JOIN numeracion n ON e.idempresa = n.idempresa
  LEFT JOIN documento d ON n.idnumeracion = d.idnumeracion
  LEFT JOIN tipodocumento td ON n.idtipodocumento = td.idtipodocumento
  WHERE d.fecha BETWEEN '2020-01-01' AND '2020-12-31'
  GROUP BY e.idempresa, e.identificacion, e.razonsocial;
  ```

### Consulta 3: Cantidad de Documentos por Estado y Empresa

- **URL para Ver la Consulta:**
  http://localhost:80/ContaWeb/backend/documents/from-each-company

- **Explicación:** Lista la cantidad de documentos en cada estado para cada empresa.

- **Endpoint Completo:**

  ```
  sqlCopiar códigoSELECT e.idempresa, e.identificacion, e.razonsocial,
         es.description AS estado,
         COUNT(*) AS cantidad_documentos
  FROM empresa e
  INNER JOIN numeracion n ON e.idempresa = n.idempresa
  INNER JOIN documento d ON n.idnumeracion = d.idnumeracion
  INNER JOIN estado es ON d.idestado = es.idestado
  GROUP BY e.idempresa, e.identificacion, e.razonsocial, es.description
  ORDER BY e.idempresa, es.description;
  ```

### Consulta 4: Empresas con Más de 3 Documentos No Exitosos

- **URL para Ver la Consulta:**
  http://localhost:80/ContaWeb/backend/documents/failed/more-than-three

- **Explicación:** Identifica las empresas que tienen más de 3 documentos en estados no exitosos.

- **Endpoint Completo:**

  ```
  sqlCopiar códigoSELECT e.idempresa, e.identificacion, e.razonsocial
  FROM empresa e
  INNER JOIN numeracion n ON e.idempresa = n.idempresa
  INNER JOIN documento d ON n.idnumeracion = d.idnumeracion
  INNER JOIN estado es ON d.idestado = es.idestado
  WHERE es.exitoso = false
  GROUP BY e.idempresa
  HAVING COUNT(CASE WHEN es.exitoso = false THEN 1 END) > 3;
  ```

### Consulta 5: Documentos con Número o Fecha Fuera del Rango Permitido por la DIAN

- **URL para Ver la Consulta:**
  http://localhost:80/ContaWeb/backend/documents/out-of-range

- **Explicación:** Muestra la cantidad de documentos por empresa que tienen números o fechas fuera del rango permitido por la DIAN.

- **Endpoint Completo:**

  ```
  sqlCopiar códigoSELECT e.idempresa, e.identificacion, e.razonsocial,
         COUNT(*) AS cantidad_documentos_fuera_de_rango
  FROM empresa e
  INNER JOIN numeracion n ON e.idempresa = n.idempresa
  INNER JOIN documento d ON n.idnumeracion = d.idnumeracion
  WHERE substring(d.numero FROM '[0-9]+')::INTEGER < n.consecutivoinicial
     OR substring(d.numero FROM '[0-9]+')::INTEGER > n.consecutivofinal
     OR d.fecha < n.vigenciainicial
     OR d.fecha > n.vigenciafinal
  GROUP BY e.idempresa, e.identificacion, e.razonsocial
  ORDER BY e.idempresa;
  ```

### Consulta 6: Total de Dinero Recibido por Facturas por Empresa

- **URL para Ver la Consulta:**
  http://localhost:80/ContaWeb/backend/documents/total-invoice

- **Explicación:** Calcula el total de dinero recibido (base + impuestos) solo para facturas por cada empresa.

- **Endpoint Completo:**

  ```
  sqlCopiar códigoSELECT e.idempresa, e.identificacion, e.razonsocial,
         SUM(CASE WHEN td.description = 'Factura' THEN (d.base + d.impuestos) ELSE 0 END) AS total_facturas,
         SUM(CASE WHEN td.description = 'Debito' THEN (d.base + d.impuestos) ELSE 0 END) AS total_notas_debito
  FROM empresa e
  INNER JOIN numeracion n ON e.idempresa = n.idempresa
  INNER JOIN documento d ON n.idnumeracion = d.idnumeracion
  INNER JOIN tipodocumento td ON n.idtipodocumento = td.idtipodocumento
  GROUP BY e.idempresa, e.identificacion, e.razonsocial;
  ```

### Consulta 7: Identificar Números Completos Repetidos

- **URL para Ver la Consulta:**
  http://localhost:80/ContaWeb/backend/complete-repeated

- **Explicación:** Busca números completos duplicados en la base de datos y cuenta sus repeticiones.

- **Endpoint Completo:**

  ```
  sqlCopiar códigoSELECT CONCAT(n.prefijo, d.numero) AS numero_completo,
         COUNT(*) AS cantidad_repeticiones
  FROM numeracion n
  INNER JOIN documento d ON n.idnumeracion = d.idnumeracion
  GROUP BY numero_completo
  HAVING COUNT(*) > 1;
  ```

### Validaciones en los Endpoints

- Todos los endpoints incluyen validaciones para asegurar la integridad de los datos:
  - Se puede determinar a qué empresa pertenece la numeración utilizada para ingresar un documento.
  - Es posible identificar si la numeración usada es de factura, nota débito o nota crédito.
  - Se valida que el número del documento esté dentro del rango autorizado en la numeración.
  - Se valida que la fecha del documento se encuentre dentro de la vigencia autorizada en la numeración.
  - Se comprueba que el número del documento no haya sido usado previamente para la numeración seleccionada.
  - Se asegura que el valor base del documento no sea menor o igual a cero.
  - Los impuestos del documento siempre son menores que la base, pero nunca menores a cero.

------

Estos detalles proporcionan una visión completa de las consultas SQL disponibles en ContaWeb, junto con sus respectivos endpoints, URLs completas para acceder a cada consulta y las validaciones implementadas en los endpoints para mantener la consistencia y la integridad de los datos. Utiliza estas herramientas y consultas para realizar análisis avanzados dentro del sistema ContaWeb.

### Interfaz del Frontend:

![](/home/uwuntu/Documentos/ContaWeb/frontend/src/assets/img/Dashboard.png)

Una vez que el backend esté funcionando correctamente, puedes acceder a la interfaz del frontend abriendo manualmente con la url o de el siguiente archivo en la carpeta:

```
ContaWeb/frontend/dist/index.html
```

Carga Automática y Tabla Visual:

La interfaz del frontend se carga automáticamente utilizando AJAX, lo que permite una experiencia fluida sin necesidad de recargar la página. Además, presenta una tabla visual donde se listan los datos para un manejo más interactivo.

Asegúrate de ajustar las rutas y los nombres de archivos según la estructura real de tu proyecto. Esta guía proporciona una descripción detallada de los pasos necesarios para configurar, ejecutar y verificar ContaWeb, junto con las consultas SQL utilizadas y los endpoints disponibles.