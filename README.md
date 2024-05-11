# Proyecto Backend PHP - Sistema de Gestión de Documentos Contables

Este proyecto backend en PHP utiliza el patrón de diseño Modelo-Vista-Controlador (MVC) para gestionar documentos contables de empresas, almacenados en una base de datos PostgreSQL. Proporciona endpoints para consultas y operaciones sobre los documentos, numeraciones, estados y empresas.

## Estructura del Proyecto

El proyecto sigue una estructura de carpetas organizada de la siguiente manera:

- **`config/`**: Contiene archivos de configuración para la conexión a la base de datos y otras configuraciones relevantes.
- **`controller/`**: Incluye controladores PHP que manejan la lógica de negocio y las interacciones con los modelos y servicios.
- **`middleware/`**: Almacena middleware, como autenticación JWT en caso de ser necesario en el futuro.
- **`routes/`**: Define las rutas de la aplicación y conecta las solicitudes HTTP a los controladores correspondientes.
- **`scripts/`**: Contiene scripts SQL para inicializar la estructura de la base de datos y poblar datos iniciales.
- **`services/`**: Contiene la lógica de los servicios PHP que interactúan con la base de datos y proporcionan datos a los controladores.
- **`.env`**: Archivo de configuración para variables de entorno sensibles, como credenciales de la base de datos.

## Configuración

1. **Requisitos Previos**:
   - PHP instalado en tu sistema.
   - PostgreSQL instalado y configurado.
2. **Configuración de la Base de Datos**:
   - Ejecuta el script `database_schema.sql` en tu base de datos PostgreSQL para crear las tablas y poblar datos iniciales.
3. **Configuración de la Aplicación**:
   - Copia el archivo `.env.example` y renómbralo a `.env`. Actualiza las variables de entorno con la configuración de tu base de datos.
4. **Ejecución**:
   - Inicia tu servidor local PHP (por ejemplo, con `php -S localhost:8000`).
   - Accede a las rutas definidas en `routes/index.php` para interactuar con la aplicación.

## Uso

1. Para ejecutar las consultas unicamente debes ejecutar los scriptis, de los 3 archivo que hay en la carpeta backend/script/*.sql

   -Primero copiar y ejecutar el esquema.sql

   -Luego copiar y ejecutar el insertarDatos.sql

   -Por ultimo mirar en el ultimo archivo consultas.sql donde se visualizan todas las consultas o puedes seguir con el readme donde aparece una por una

---

Claro, aquí te presento las 7 consultas con comentarios para tu archivo README, enumeradas y explicadas:

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

1. ngresa por teminal a la carpeta del proyecto

2. Usa el siguiente comando para inicializar el frontend `npm run start` 

3. Iniciar con Apache o levantar el api del backend.

4. Usar los endpoints creados en la pagina de Dashboard.

5. Para usar cada endpotn usa el ver detalles y llenar los datos que correspondan

   ![](/home/uwuntu/.config/Typora/typora-user-images/image-20240511112135915.png)