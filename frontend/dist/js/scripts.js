window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});


$(document).ready(function() {
    // Función para cargar opciones de empresas mediante AJAX
    function cargarEmpresas() {
        $.ajax({
            url: 'http://localhost/ContaWeb/backend/documents/info-document-complete',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data && data.resultados) {
                    var selectUpdate = $('#documentosSelect');
                    var deleteSelect = $('#deleteSelect');
                    selectUpdate.empty(); // Limpiar opciones existentes en el select
                    var tbody = $('#datatablesSimple tbody');
                    tbody.empty();
                    deleteSelect.empty();
    
                    // Iterar sobre los resultados recibidos
                    data.resultados.forEach(function(resultado) {
                        // Crear una opción para el select con los datos del resultado
                        var optionTextDocument = `${resultado.numero_documento} ${resultado.nombre_empresa} - ${resultado.tipo_documento} - ${resultado.estado_documento} - ( ${resultado.fecha_emision} )`;
                        var optionValueDocument = resultado.idnumeracion;
    
                        // Crear la opción y agregar atributos de datos (data-*) para almacenar prefijo
                        var option = $('<option>')
                            .attr('value', optionValueDocument)
                            .data('numeracionData', resultado.idnumeracion)
                            .data('numFactComplet', resultado.numero_documento)
                            .text(optionTextDocument);
    
                        


                        
                        var row = $('<tr>');
                        row.append($('<td>').text(resultado.numero_documento));
                        row.append($('<td>').text(resultado.nombre_empresa));
                        row.append($('<td>').text(resultado.tipo_documento));
                        row.append($('<td>').text(resultado.valor_base));
                        row.append($('<td>').text(resultado.valor_impuestos));
                        row.append($('<td>').text(resultado.estado_documento));
                        row.append($('<td>').text(resultado.fecha_emision));
                        tbody.append(row)
                        selectUpdate.append(option);
                        deleteSelect.append(option);
                    });
                } else {
                    // Si no hay resultados, mostrar un mensaje o dejar el select vacío
                    selectPost.append($('<option selected disabled>').text('No se encontraron numeraciones disponibles'));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error al obtener datos:', textStatus, errorThrown);
                // Mostrar mensaje de error en el select
                selectPost.append($('<option selected disabled>').text('Error al cargar las numeraciones'));
            }
        });
    }

    function cargarNumeraciones() {
        $.ajax({
            url: 'http://localhost/ContaWeb/backend/documents/info-numeration',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data && data.resultados) {
                    var selectPost = $('#NumeracionesSelect');
                    selectPost.empty(); // Limpiar opciones existentes en el select
    
                    // Iterar sobre los resultados recibidos
                    data.resultados.forEach(function(resultado) {
                        // Crear una opción para el select con los datos del resultado
                        var optionText = `${resultado.prefijo} ${resultado.tipo_documento} (${resultado.consecutivoinicial} - ${resultado.consecutivofinal}) ${resultado.empresa}`;
                        var optionValue = resultado.idnumeracion;
    
                        // Crear la opción y agregar atributos de datos (data-*) para almacenar prefijo
                        var option = $('<option>')
                            .attr('value', optionValue)
                            .data('prefijo', resultado.prefijo) // Almacenar el prefijo como atributo de datos
                            .text(optionText);
    
                        // Agregar la opción al select
                        selectPost.append(option);
                    });
                } else {
                    // Si no hay resultados, mostrar un mensaje o dejar el select vacío
                    selectPost.append($('<option selected disabled>').text('No se encontraron numeraciones disponibles'));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error al obtener datos:', textStatus, errorThrown);
                // Mostrar mensaje de error en el select
                selectPost.append($('<option selected disabled>').text('Error al cargar las numeraciones'));
            }
        });
    }

    // Función para cargar documento mediante AJAX
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
                var tbody = $('#response1 tbody');
                tbody.empty(); // Limpiar contenido existente
    
                if (data && data.resultados && data.resultados.length > 0) {
                    // Si hay resultados, pobla la tabla
                    data.resultados.forEach(function(resultado) {
                        var row = $('<tr>');
                        row.append($('<td>').text(resultado.numero_documento));
                        row.append($('<td>').text(resultado.nombre_empresa));
                        row.append($('<td>').text(resultado.tipo_documento));
                        row.append($('<td>').text(resultado.valor_base));
                        row.append($('<td>').text(resultado.valor_impuestos));
                        row.append($('<td>').text(resultado.estado_documento));
                        row.append($('<td>').text(resultado.fecha_emision));
                        tbody.append(row);
                    });
                } else {
                    // Si no hay resultados, mostrar un mensaje en la tabla
                    var messageRow = $('<tr>');
                    messageRow.append($('<td colspan="7" style="text-align:center;">').text('No se encontraron resultados.'));
                    tbody.append(messageRow);
                }
            },
            error: function() {
                console.log('Error al cargar empresas.');
                var tbody = $('#response1 tbody');
                tbody.empty(); // Limpiar contenido existente
                var messageRow = $('<tr>');
                messageRow.append($('<td colspan="2" style="text-align:center; color:red;">').text('Error al cargar los datos.'));
                tbody.append(messageRow);
            }
        });
    }

    // Función para guardar documento mediante AJAX
    function guardarDocumento() {
        var idnumeracion = $('#NumeracionesSelect').val(); // Obtener el valor seleccionado del select (idnumeracion)
        var idestado = $('#estado').val(); // Obtener el valor del input de estado (idestado)
        var numero = $('#numero').val(); // Obtener el número de documento
        var fecha = $('#fecha').val(); // Obtener la fecha desde el input
        var base = $('#base').val(); // Obtener la base desde el input
        var impuestos = $('#impuestos').val(); // Obtener los impuestos desde el input
    
        // Combinar el prefijo seleccionado con el número de documento
        var prefijoSeleccionado = $('#NumeracionesSelect option:selected').data('prefijo');
        var numeroCompleto = prefijoSeleccionado + numero;
    
        // Realizar la solicitud AJAX con los datos requeridos
        $.ajax({
            url: 'http://localhost/ContaWeb/backend/document/newDocument',
            method: 'POST',
            dataType: 'json',
            data: {
                idnumeracion: idnumeracion,
                idestado: idestado, // Usar idestado en lugar de idEstado
                numero: numeroCompleto,
                fecha: fecha,
                base: base,
                impuestos: impuestos
            },
            success: function(data) {
                // Mostrar mensaje de éxito en un alert
                cargarEmpresas();
                alert('Documento guardado correctamente, puedes verificarlo en la tabla principal cerrando la modal.');
    
                // Lógica adicional según sea necesario (por ejemplo, redireccionar a otra página)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Mostrar mensaje de error en un alert
                alert('Error al guardar el documento. Inténtelo de nuevo.');
            }
        });
    }

    function actualizarDocumento() {
        var idestado = $('#newestado').val(); // Obtener el valor del input de estado (idestado)
        var fecha = $('#fecha2').val(); // Obtener la fecha desde el input
        var base = $('#base2').val(); // Obtener la base desde el input
        var impuestos = $('#impuestos2').val(); // Obtener los impuestos desde el input
    
        // Combinar el prefijo seleccionado con el número de documento
        var idnumeracion = $('#documentosSelect option:selected').data('numeracionData');
        var numFactComplet = $('#documentosSelect option:selected').data('numFactComplet');
        // Realizar la solicitud AJAX con los datos requeridos
        $.ajax({
            url: 'http://localhost/ContaWeb/backend/document/updateDocument',
            method: 'PUT',
            dataType: 'json',
            data: {
                idnumeracion: idnumeracion,
                idestado: idestado, // Usar idestado en lugar de idEstado
                numero: numFactComplet,
                fecha: fecha,
                base: base,
                impuestos: impuestos
            },
            success: function(data) {
                // Mostrar mensaje de éxito en un alert
                cargarEmpresas();
                alert('Documento actualizado correctamente, puedes verificarlo en la tabla principal cerrando la modal.');
    
                // Lógica adicional según sea necesario (por ejemplo, redireccionar a otra página)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Mostrar mensaje de error en un alert
                alert('Error al actualizar el documento. Inténtelo de nuevo.');
            }
        });
    }

    function eliminarDocumento() {
    
        var idnumeracion = $('#deleteSelect option:selected').data('numeracionData');
        var numFactComplet = $('#deleteSelect option:selected').data('numFactComplet');

        console.log(idnumeracion);
        console.log(numFactComplet);
        // Realizar la solicitud AJAX con los datos requeridos
        $.ajax({
            url: 'http://localhost/ContaWeb/backend/document/deleteDocument',
            method: 'DELETE',
            dataType: 'json',
            data: {
                idnumeracion: idnumeracion,
                numero: numFactComplet
            },
            success: function(data) {
                // Mostrar mensaje de éxito en un alert
                cargarEmpresas();
                alert('Documento eliminado correctamente.');
    
                // Lógica adicional según sea necesario (por ejemplo, redireccionar a otra página)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Mostrar mensaje de error en un alert
                alert('Error al eliminar el documento. Inténtelo de nuevo.');
            }
        });
    }
    
    

    // Llamar a la función para cargar empresas al cargar la página
    cargarEmpresas();
    cargarNumeraciones();


    // Manejar envío del formulario para cargar documento
    $('#myFormEndpoint1').submit(function(e) {
        e.preventDefault();
        cargarDocumento();
        // Puedes agregar lógica adicional aquí si es necesario para el formulario
    });

    // Manejar envío del formulario para guardar documento
    $('#myFormEndpoint2').submit(function(e) {
        e.preventDefault();
        guardarDocumento();
        // Puedes agregar lógica adicional aquí si es necesario para el formulario
    });

    $('#myFormEndpoint3').submit(function(e) {
        e.preventDefault();
        actualizarDocumento();
        // Puedes agregar lógica adicional aquí si es necesario para el formulario
    });

    $('#myFormEndpoint4').submit(function(e) {
        e.preventDefault();
        eliminarDocumento();
        // Puedes agregar lógica adicional aquí si es necesario para el formulario
    });
});