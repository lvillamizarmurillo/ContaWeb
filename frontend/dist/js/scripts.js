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