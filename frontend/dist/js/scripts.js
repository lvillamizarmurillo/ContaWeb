/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2024 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    //
// Scripts
//
var baseUrl = "http://localhost:81"
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
            url: baseUrl +'/ContaWeb/backend/documents/info-document-complete',
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
                        var optionUpdate = $('<option>')
                            .attr('value', optionValueDocument)
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
                        selectUpdate.append(optionUpdate);
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
            url: baseUrl +'/ContaWeb/backend/documents/info-numeration',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data && data.resultados) {
                    var selectPost = $('#NumeracionesSelect');
                    selectPost.empty();

                    // Iterar sobre los resultados recibidos
                    data.resultados.forEach(function(resultado) {
                        // Crear una opción para el select con los datos del resultado
                        var optionText = `${resultado.prefijo} ${resultado.tipo_documento} rango(${resultado.consecutivoinicial} - ${resultado.consecutivofinal})/(${resultado.vigenciainicial} - ${resultado.vigenciafinal})`;
                        var optionValue = resultado.idnumeracion;

                        // Crear la opción y agregar atributos de datos (data-*) para almacenar prefijo
                        var option = $('<option>')
                            .attr('value', optionValue)
                            .data('prefijo', resultado.prefijo)
                            .data('consecutivoInicial', resultado.consecutivoinicial)
                            .data('consecutivoFinal', resultado.consecutivofinal)
                            .data('vigenciaInicial', resultado.vigenciainicial)
                            .data('vigenciaFinal', resultado.vigenciafinal)
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
            url: baseUrl +'/ContaWeb/backend/document/numDocument',
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
    // Función para guardar documento mediante AJAX con validaciones
    function guardarDocumento() {
        var idnumeracion = $('#NumeracionesSelect').val();
        var idestado = $('#estado').val();
        var numero = $('#numero').val();
        var fecha = $('#fecha').val();
        var base = $('#base').val();
        var impuestos = $('#impuestos').val();

        // Realizar las validaciones antes de enviar la solicitud AJAX
        var prefijoSeleccionado = $('#NumeracionesSelect option:selected').data('prefijo');
        var consecutivoInicial = $('#NumeracionesSelect option:selected').data('consecutivoInicial');
        var consecutivoFinal = $('#NumeracionesSelect option:selected').data('consecutivoFinal');
        var vigenciaInicial = $('#NumeracionesSelect option:selected').data('vigenciaInicial');
        var vigenciaFinal = $('#NumeracionesSelect option:selected').data('vigenciaFinal');

        // Validar que el número del documento esté dentro del rango autorizado
        var numDocumento = parseInt(numero.replace(prefijoSeleccionado, ''), 10); // Extraer el número del documento
        if (numDocumento <= consecutivoInicial || numDocumento >= consecutivoFinal) {
            alert('El número de documento debe estar dentro del rango autorizado.');
            return;
        }

        // Validar que la fecha del documento esté dentro de la vigencia autorizada
        var fechaDocumento = new Date(fecha);
        if (fechaDocumento < new Date(vigenciaInicial) || fechaDocumento > new Date(vigenciaFinal)) {
            alert('La fecha del documento debe estar dentro de la fecha autorizada.');
            return;
        }

        // Validar que el número del documento no haya sido usado antes para la numeración seleccionada
        var documentosExistentes = $('#documentosSelect option').map(function() {
            return $(this).data('numFactComplet');
        }).get();
        if (documentosExistentes.includes(numero)) {
            alert('El número de documento ya ha sido utilizado para esta numeración.');
            return;
        }

        // Validar que el valor base del documento no sea menor o igual a cero
        if (parseFloat(base) <= 0) {
            alert('El valor base del documento debe ser mayor que cero.');
            return;
        }

        // Validar que los impuestos del documento sean menores que la base pero nunca menor a cero
        if (parseFloat(impuestos) >= parseFloat(base) || parseFloat(impuestos) < 0) {
            alert('Los impuestos deben ser menores que la base pero nunca menor a cero.');
            return;
        }

        // Realizar la solicitud AJAX si todas las validaciones pasan
        $.ajax({
            url: baseUrl +'/ContaWeb/backend/document/newDocument',
            method: 'POST',
            dataType: 'json',
            data: {
                idnumeracion: idnumeracion,
                idestado: idestado,
                numero: numero,
                fecha: fecha,
                base: base,
                impuestos: impuestos
            },
            success: function(data) {
                cargarEmpresas();
                alert('Documento guardado correctamente.');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error al guardar el documento. Inténtelo de nuevo.');
            }
        });
    }

    function actualizarDocumento() {
        var idestado = $('#newestado').val(); // Obtener el valor del input de estado (idestado)
        var fecha = $('#fecha2').val(); // Obtener la fecha desde el input
        var base = $('#base2').val(); // Obtener la base desde el input
        var impuestos = $('#impuestos2').val(); // Obtener los impuestos desde el input

        if (parseFloat(base) <= 0) {
            alert('El valor base del documento debe ser mayor que cero.');
            return;
        }

        // Validar que los impuestos del documento sean menores que la base pero nunca menor a cero
        if (parseFloat(impuestos) >= parseFloat(base) || parseFloat(impuestos) < 0) {
            alert('Los impuestos deben ser menores que la base pero nunca menor a cero.');
            return;
        }


        // Combinar el prefijo seleccionado con el número de documento
        var idnumeracion = $('#documentosSelect option:selected').data('numeracionData');
        var numFactComplet = $('#documentosSelect option:selected').data('numFactComplet');
        // Realizar la solicitud AJAX con los datos requeridos
        $.ajax({
            url: baseUrl +'/ContaWeb/backend/document/updateDocument',
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
            url: baseUrl +'/ContaWeb/backend/document/deleteDocument',
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
