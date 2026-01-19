<?php
// global/auto_notifications.php - Auto-cargar notificaciones

// Esta función se llamará automáticamente al incluir este archivo
function autoLoadNotifications()
{
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  // Solo cargar si no se ha cargado ya en esta ejecución
  if (!defined('NOTIFICATIONS_AUTO_LOADED')) {
    define('NOTIFICATIONS_AUTO_LOADED', true);

    // Si hay notificaciones en sesión, mostrar al final de la ejecución
    if (!empty($_SESSION['notificaciones'])) {
      // Usar output buffering para capturar la salida
      ob_start();

      // Registrar función para mostrar al final
      register_shutdown_function(function () {
        // Obtener el contenido actual
        $content = ob_get_clean();

        // Incluir y mostrar notificaciones
        require_once __DIR__ . '/notifications.php';
        ob_start();
        Notification::show();
        $notifications = ob_get_clean();

        // Inyectar notificaciones al inicio del body
        if (strpos($content, '<body') !== false) {
          $content = preg_replace(
            '/(<body[^>]*>)/',
            '$1' . $notifications,
            $content
          );
        } else {
          // Si no encuentra body, agregar al inicio
          $content = $notifications . $content;
        }

        echo $content;
      });
    }
  }
}

// Ejecutar automáticamente
autoLoadNotifications();
