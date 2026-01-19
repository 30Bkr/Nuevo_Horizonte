<?php
// global/notifications.php - Sistema NATIVO de notificaciones con fondos personalizados

class Notification
{
    /**
     * Establece una notificación
     */
    public static function set($mensaje, $tipo = 'info')
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['mensaje'] = $mensaje;
        $_SESSION['icono'] = $tipo;
    }

    /**
     * Muestra la notificación si existe
     */
    public static function show()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['mensaje']) || empty($_SESSION['mensaje'])) {
            return;
        }

        $mensaje = $_SESSION['mensaje'];
        $tipo = $_SESSION['icono'] ?? 'info';

        // Colores según tipo - versión MEJORADA con gradientes
        $colores = [
            'success' => [
                'header' => '#28a745',    // Verde brillante para encabezado
                'body' => '#e8f5e9',      // Verde muy claro para cuerpo
                'text' => '#155724',      // Verde oscuro para texto
                'border' => '#1e7e34'     // Verde más oscuro para borde
            ],
            'error' => [
                'header' => '#dc3545',    // Rojo brillante
                'body' => '#f8d7da',      // Rojo muy claro
                'text' => '#721c24',      // Rojo oscuro
                'border' => '#c82333'     // Rojo más oscuro
            ],
            'warning' => [
                'header' => '#ffc107',    // Amarillo brillante
                'body' => '#fff3cd',      // Amarillo muy claro
                'text' => '#856404',      // Amarillo oscuro/marrón
                'border' => '#e0a800'     // Amarillo más oscuro
            ],
            'info' => [
                'header' => '#17a2b8',    // Azul brillante
                'body' => '#d1ecf1',      // Azul muy claro
                'text' => '#0c5460',      // Azul oscuro
                'border' => '#117a8b'     // Azul más oscuro
            ]
        ];

        // Color por defecto si el tipo no existe
        $default_colors = [
            'header' => '#17a2b8',
            'body' => '#f8f9fa',
            'text' => '#212529',
            'border' => '#6c757d'
        ];

        $colors = $colores[$tipo] ?? $default_colors;

        $icono = self::getIcono($tipo);
        $titulo = self::getTitulo($tipo);
        $idNotificacion = 'notif-' . uniqid();
        $mensajeSeguro = htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8');

        // HTML de la notificación MEJORADA
        echo <<<HTML
    <div id="{$idNotificacion}" class="global-notification" style="
      position: fixed;
      top: 70px;
      right: 20px;
      z-index: 1050;
      width: 350px;
      background: {$colors['body']};
      border-left: 4px solid {$colors['border']};
      border-radius: 6px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.15);
      animation: slideIn 0.4s ease;
      margin-bottom: 10px;
      overflow: hidden;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      border: 1px solid rgba(0,0,0,0.1);
    ">
      <!-- Encabezado con color fuerte -->
      <div style="
        padding: 12px 16px;
        background: {$colors['header']};
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 500;
      ">
        <div style="display: flex; align-items: center; gap: 10px;">
          <span style="font-size: 18px; font-weight: bold; filter: drop-shadow(0 1px 1px rgba(0,0,0,0.2));">{$icono}</span>
          <strong style="font-size: 16px; text-shadow: 0 1px 1px rgba(0,0,0,0.1);">{$titulo}</strong>
        </div>
        <button onclick="cerrarNotificacion('{$idNotificacion}')" style="
          background: rgba(255,255,255,0.2);
          border: none;
          color: white;
          font-size: 20px;
          cursor: pointer;
          width: 28px;
          height: 28px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          transition: all 0.2s;
          line-height: 1;
          padding: 0;
        " onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='scale(1.1)'" 
          onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='scale(1)'">
          &times;
        </button>
      </div>
      
      <!-- Cuerpo con color suave que coincide con el tipo -->
      <div style="
        padding: 16px;
        color: {$colors['text']};
        font-size: 14px;
        line-height: 1.5;
        background: {$colors['body']};
        border-top: 1px solid rgba(0,0,0,0.05);
      ">
        {$mensajeSeguro}
      </div>
    </div>
    
    <style>
      @keyframes slideIn {
        from { 
          transform: translateX(100%); 
          opacity: 0; 
        }
        to { 
          transform: translateX(0); 
          opacity: 1; 
        }
      }
      
      @keyframes fadeOut {
        from { 
          opacity: 1; 
          transform: translateX(0);
        }
        to { 
          opacity: 0; 
          transform: translateX(100%);
        }
      }
      
      /* Mejora para móviles */
      @media (max-width: 768px) {
        .global-notification {
          width: calc(100% - 40px) !important;
          left: 20px !important;
          right: 20px !important;
          top: 20px !important;
        }
        
        @keyframes slideIn {
          from { 
            transform: translateY(-20px); 
            opacity: 0; 
          }
          to { 
            transform: translateY(0); 
            opacity: 1; 
          }
        }
        
        @keyframes fadeOut {
          from { 
            opacity: 1; 
            transform: translateY(0);
          }
          to { 
            opacity: 0; 
            transform: translateY(-20px);
          }
        }
      }
    </style>
    
    <script>
      function cerrarNotificacion(id) {
        var elemento = document.getElementById(id);
        if (elemento) {
          elemento.style.animation = 'fadeOut 0.4s ease forwards';
          setTimeout(function() {
            if (elemento.parentNode) {
              elemento.parentNode.removeChild(elemento);
            }
          }, 400);
        }
      }
      
      // Auto-eliminar después de 5 segundos
      setTimeout(function() {
        cerrarNotificacion('{$idNotificacion}');
      }, 5000);
      
      // Cerrar al hacer clic fuera (opcional)
      document.addEventListener('click', function(e) {
        var notif = document.getElementById('{$idNotificacion}');
        if (notif && !notif.contains(e.target)) {
          cerrarNotificacion('{$idNotificacion}');
        }
      });
    </script>
HTML;

        // Limpiar después de mostrar
        unset($_SESSION['mensaje']);
        unset($_SESSION['icono']);
    }

    private static function getIcono($tipo)
    {
        $iconos = [
            'success' => '✓',
            'error' => '✗',
            'warning' => '⚠',
            'info' => 'ℹ'
        ];
        return $iconos[$tipo] ?? 'ℹ';
    }

    private static function getTitulo($tipo)
    {
        $titulos = [
            'success' => 'Éxito',
            'error' => 'Error',
            'warning' => 'Advertencia',
            'info' => 'Información'
        ];
        return $titulos[$tipo] ?? 'Notificación';
    }
}
