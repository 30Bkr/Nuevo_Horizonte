document.addEventListener('DOMContentLoaded', function() {
    
    const loginForm = document.getElementById('loginForm');
    const alertsContainer = document.getElementById('loginAlerts');

    loginForm.addEventListener('submit', function(e) {
        e.preventDefault(); 
        const formData = new FormData(this);

        // Actualizamos la URL al NUEVO nombre del controlador
        fetch('controller_login.php', { 
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('¡Bienvenido! Redirigiendo...', 'success');
                setTimeout(() => {
                    // Redirigimos al dashboard (o index) de admin
                    window.location.href = '../admin/dashboard.php'; 
                }, 1500);
            } else {
                mostrarAlerta(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Ocurrió un error de conexión.', 'danger');
        });
    });

    // (La función mostrarAlerta sigue igual que en la respuesta anterior)
    function mostrarAlerta(message, type) {
        alertsContainer.innerHTML = '';
        const wrapper = document.createElement('div');
        wrapper.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show alert-login" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        alertsContainer.append(wrapper);
        setTimeout(() => {
            const alertNode = wrapper.querySelector('.alert');
            if (alertNode) {
                new bootstrap.Alert(alertNode).close();
            }
        }, 6000);
    }
});