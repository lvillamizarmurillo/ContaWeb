// 
// Scripts
// 

$(document).ready(function() {
    // Función para cargar opciones de empresas mediante AJAX
    function cargarEmpresas() {
        $.ajax({
            url: 'http://localhost/ContaWeb/backend/documentsFailedData',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log('Datos de empresas recibidos:');
                console.log(data);
                // Aquí puedes procesar los datos recibidos como desees
            },
            error: function() {
                console.log('Error al cargar empresas.');
            }
        });
    }
    function cargarDocumento() {
        var numDocument = $('#InputNumeracion').val();
        $.ajax({
            url: 'http://localhost/ContaWeb/backend/document/numDocument',
            method: 'POST',
            dataType: 'json',
            data: {
                numDocument: numDocument
            },
            success: function(data) {
                console.log('Datos de empresas recibidos:');
                console.log(data);
                // Aquí puedes procesar los datos recibidos como desees
            },
            error: function() {
                console.log('Error al cargar empresas.');
            }
        });
    }
    


    // Llamar a la función para cargar empresas al cargar la página
    cargarEmpresas();

    // Manejar envío del formulario
    $('#myFormEndpoint1').submit(function(e) {
        e.preventDefault();
        cargarDocumento();
        // Puedes agregar lógica adicional aquí si es necesario para el formulario
    });
});



$(document).ready(function() {
    $('#updateDocumentoForm').submit(function(e) {
        e.preventDefault();

        var documentoId = $('#documentoId').val();
        var fecha = $('#fecha').val();
        var base = $('#base').val();
        var impuestos = $('#impuestos').val();

        // Realizar validaciones adicionales si es necesario

        // Realizar la solicitud PUT usando AJAX
        $.ajax({
            url: 'actualizar_documento.php',
            method: 'PUT',
            data: {
                iddocumento: documentoId,
                fecha: fecha,
                base: base,
                impuestos: impuestos
            },
            success: function(response) {
                $('#message').html('<p>Documento actualizado correctamente.</p>');
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                $('#message').html('<p>Error al actualizar documento: ' + errorMessage + '</p>');
            }
        });
    });
});