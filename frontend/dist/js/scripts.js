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

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('myFormEndpoint1');

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de forma predeterminada

        // Recopilar los datos del formulario
        const formData = new FormData(form);

        // Enviar los datos utilizando AJAX
        fetch('url_del_servidor', {
            method: 'POST', // Método HTTP (puede ser POST, GET, etc.)
            body: formData // Datos del formulario
        })
        .then(response => response.json()) // Convertir la respuesta a JSON
        .then(data => {
            // Manejar la respuesta del servidor
            console.log('Respuesta del servidor:', data);
            // Puedes actualizar la interfaz aquí si es necesario
        })
        .catch(error => {
            console.error('Error al enviar el formulario:', error);
        });
    });
});
