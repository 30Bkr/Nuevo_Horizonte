<?php
// alerts.php

/**
 * Inicia la sesión si no está iniciada.
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Establece una alerta en la sesión.
 */
function setAlert($type, $message, $redirect_url = null) {
    $_SESSION['alert'] = [
        'type' => $type,
        'message' => $message
    ];
    if ($redirect_url) {
        $_SESSION['alert_redirect'] = $redirect_url;
    }
}

/**
 * Obtiene la URL de redirección guardada en la sesión y la limpia.
 */
function getAlertRedirect() {
    if (isset($_SESSION['alert_redirect'])) {
        $redirect_path = $_SESSION['alert_redirect'];
        
        unset($_SESSION['alert_redirect']); 
        
        return BASE_URL . $redirect_path; 
    }
    return BASE_URL . '/admin/dashboard.php';
}

// ------------------------------------------------------------------
// ¡¡FUNCIONES CORREGIDAS AÑADIDAS EN EL ÁMBITO GLOBAL!!
// ------------------------------------------------------------------

/**
 * Recupera la alerta de la sesión sin borrarla (Usada en la vista).
 */
function getAlert() {
    return $_SESSION['alert'] ?? null;
}

/**
 * Función auxiliar para renderizar el mensaje de alerta en la vista (Usada por getAlert).
 */
function renderAlert(array $alert) {
    if (empty($alert) || !isset($alert['type'], $alert['message'])) {
        return '';
    }
    $type = htmlspecialchars($alert['type']);
    $message = htmlspecialchars($alert['message']);
    
    return "<div class='alert alert-{$type} alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                {$message}
            </div>";
}

// ------------------------------------------------------------------

/**
 * Muestra el script de SweetAlert2 si existe una alerta en la sesión, y la limpia.
 * * Se llama desde el layout/footer_admin.php
 */
function displayAndClearAlert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        
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
        echo "  title: 'Operación Exitosa',";
        echo "  html: {$message_js},";
        echo "  confirmButtonText: 'Aceptar'";
        echo "});";
        echo "</script>";

        // Limpiar la alerta de la sesión después de mostrarla
        unset($_SESSION['alert']);
    }
}

// Final del archivo