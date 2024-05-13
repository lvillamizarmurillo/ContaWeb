/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2024 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 

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
            url: 'http://localhost/ContaWeb/backend/documents/info-numeration',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Manipula los datos recibidos para poblar la tabla
                if (data && data.resultados) {
                    var tbody = $('#datatablesSimple tbody');
                    tbody.empty(); // Limpiar contenido existente
    
                    data.resultados.forEach(function(resultado) {
                        var row = $('<tr>');
                        row.append($('<td>').text(resultado.empresa));
                        row.append($('<td>').text(resultado.tipo_documento));
                        row.append($('<td>').text(resultado.prefijo));
                        row.append($('<td>').text(resultado.consecutivoinicial));
                        row.append($('<td>').text(resultado.consecutivofinal));
                        tbody.append(row);
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error al obtener datos:', textStatus, errorThrown);
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
                var tbody = $('#response1 tbody');
                tbody.empty(); // Limpiar contenido existente
    
                if (data && data.resultados && data.resultados.length > 0) {
                    // Si hay resultados, pobla la tabla
                    data.resultados.forEach(function(resultado) {
                        var row = $('<tr>');
                        row.append($('<td>').text(resultado.empresa));
                        row.append($('<td>').text(resultado.tipo_documento));
                        tbody.append(row);
                    });
                } else {
                    // Si no hay resultados, mostrar un mensaje en la tabla
                    var messageRow = $('<tr>');
                    messageRow.append($('<td colspan="2" style="text-align:center;">').text('No se encontraron resultados.'));
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


    $('#myFormEndpoint1').submit(function(e) {
        e.preventDefault();
        cargarDocumento();
        // Puedes agregar lógica adicional aquí si es necesario para el formulario
    });


    $('#myFormEndpoint2').submit(function(e) {
        e.preventDefault();

        var documentoId = $('#documentoId').val();
        var fecha = $('#fecha').val();
        var base = $('#base').val();
        var impuestos = $('#impuestos').val();

    });
});