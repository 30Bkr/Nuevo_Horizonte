<?php
// app/controllers/alerts.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Función para establecer la alerta
function setAlert($type, $message, $redirect_path = null) {
    $_SESSION['alert'] = [
        'type' => $type,
        'message' => $message
    ];
    if ($redirect_path) {
        $_SESSION['alert_redirect'] = $redirect_path;
    }
}

// Función CRÍTICA para construir la URL de redirección final
function getAlertRedirect() {
    // Aseguramos que la variable BASE_URL esté disponible
    if (!defined('BASE_URL')) {
        // Fallback si la configuración no se cargó, aunque debería estarlo
        return '/'; 
    }
    
    if (isset($_SESSION['alert_redirect'])) { 
        $redirect_path = $_SESSION['alert_redirect'];
        unset($_SESSION['alert_redirect']);
        return BASE_URL . $redirect_path; 
    }
    // Redirección por defecto si no se establece una ruta específica
    return BASE_URL . '/admin/dashboard.php'; 
}

// Las funciones getAlert() y renderAlert() (que causaron problemas)
// se omiten intencionalmente por ahora para evitar conflictos.

// Función para mostrar la alerta SweetAlert2 en el footer y borrarla
function displayAndClearAlert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']); 
        
        $icon = match($alert['type']) {
            'success' => 'success',
            'error' => 'error',
            'warning' => 'warning',
            'info' => 'info',
            default => 'info',
        };

        $message_js = json_encode($alert['message']);
        
        echo "<script>";
        echo "Swal.fire({";
        echo "  icon: '{$icon}',";
        echo "  title: 'Operación ' + ('{$icon}' === 'success' ? 'Exitosa' : 'Fallida'),"; // Título dinámico
        echo "  html: {$message_js},";
        echo "  confirmButtonText: 'Aceptar'";
        echo "});";
        echo "</script>";
    }
}
?>