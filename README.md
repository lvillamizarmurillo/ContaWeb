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

1. **Endpoints Disponibles**:
   - `/api/documentos`: Gestiona los documentos contables.
   - `/api/empresas`: Gestiona las empresas registradas.
   - `/api/numeraciones`: Gestiona las numeraciones autorizadas por la DIAN.
   - `/api/estados`: Gestiona los estados de validación de documentos.
2. **Ejemplos de Uso**:
   - Para listar todos los documentos: `GET /api/documentos`.
   - Para crear un nuevo documento: `POST /api/documentos`.

## Contribución

Si deseas contribuir a este proyecto, por favor sigue estos pasos:

1. Realiza un fork del repositorio.
2. Crea una nueva rama (`git checkout -b feature/nueva-funcionalidad`).
3. Realiza tus cambios y haz commit (`git commit -am 'Agregar nueva funcionalidad'`).
4. Haz push a la rama (`git push origin feature/nueva-funcionalidad`).
5. Crea un nuevo pull request.

## Autor

Creado por [Tu Nombre] - [Tu Correo Electrónico]

## Licencia

Este proyecto está bajo la Licencia MIT.