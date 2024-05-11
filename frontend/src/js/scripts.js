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
    // Cargar opciones de empresas
    $.ajax({
        url: 'obtener_empresas.php',
        method: 'GET',
        success: function(data) {
            var empresas = JSON.parse(data);
            empresas.forEach(function(empresa) {
                $('#empresa').append($('<option>', {
                    value: empresa.idempresa,
                    text: empresa.identificacion + ' - ' + empresa.razonsocial
                }));
            });
        },
        error: function() {
            console.log('Error al cargar empresas.');
        }
    });

    // Manejar cambio de empresa para cargar numeraciones correspondientes
    $('#empresa').change(function() {
        var idEmpresa = $(this).val();

        // Cargar opciones de numeración según la empresa seleccionada
        $.ajax({
            url: 'obtener_numeraciones.php?idEmpresa=' + idEmpresa,
            method: 'GET',
            success: function(data) {
                var numeraciones = JSON.parse(data);
                $('#numeracion').empty(); // Limpiar opciones anteriores
                numeraciones.forEach(function(numeracion) {
                    $('#numeracion').append($('<option>', {
                        value: numeracion.idnumeracion,
                        text: numeracion.prefijo + ' (' + numeracion.description + ')'
                    }));
                });
            },
            error: function() {
                console.log('Error al cargar numeraciones.');
            }
        });
    });

    // Manejar envío del formulario
    $('#documentoForm').submit(function(e) {
        e.preventDefault();

        var formData = {
            idnumeracion: $('#numeracion').val(),
            idtipoDocumento: $('#tipoDocumento').val(),
            fecha: $('#fecha').val(),
            base: $('#base').val(),
            impuestos: $('#impuestos').val()
        };

        // Validaciones del lado del cliente (puedes agregar más según necesites)
        if (formData.base <= 0) {
            $('#message').html('<p>El valor base del documento debe ser mayor que cero.</p>');
            return;
        }
        if (formData.impuestos < 0 || formData.impuestos >= formData.base) {
            $('#message').html('<p>El valor de impuestos debe ser menor que la base y mayor o igual a cero.</p>');
            return;
        }

        // Validaciones adicionales y envío de datos al servidor
        $.ajax({
            url: 'guardar_documento.php',
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#message').html('<p>Documento guardado correctamente.</p>');
            },
            error: function() {
                $('#message').html('<p>Error al guardar documento.</p>');
            }
        });
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
