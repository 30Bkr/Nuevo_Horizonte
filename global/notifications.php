<?php
// global/notifications.php - Versión 100% NATIVA

class Notification
{
  public static function show()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    if (isset($_SESSION['mensaje']) && !empty($_SESSION['mensaje'])) {
      $mensaje = $_SESSION['mensaje'];
      $tipo = $_SESSION['icono'] ?? 'info';

      // Determinar color según el tipo de mensaje
      $colores = [
        'success' => '#28a745',  // Verde
        'error' => '#dc3545',    // Rojo
        'warning' => '#ffc107',  // Amarillo
        'info' => '#17a2b8'      // Azul
      ];

      $color = $colores[$tipo] ?? '#17a2b8';
      $icono = self::getIcono($tipo);
      $titulo = self::getTitulo($tipo);

      // ID único para la notificación
      $idNotificacion = 'notif-' . uniqid();

      // Generar HTML de la notificación
      echo '<div id="' . $idNotificacion . '" class="notificacion-toast">';
      echo '<div class="notificacion-cabecera" style="background: ' . $color . ';">';
      echo '<div class="notificacion-titulo">';
      echo '<span class="notificacion-icono">' . $icono . '</span>';
      echo '<strong>' . $titulo . '</strong>';
      echo '</div>';
      echo '<button class="notificacion-cerrar" onclick="cerrarNotificacion(\'' . $idNotificacion . '\')">&times;</button>';
      echo '</div>';
      echo '<div class="notificacion-cuerpo">';
      echo htmlspecialchars($mensaje);
      echo '</div>';
      echo '</div>';

      // CSS embebido para la notificación
      echo '<style>
            .notificacion-toast {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                width: 350px;
                background: #fe4053ff;
                border-left: 4px solid ' . $color . ';
                border-radius: 6px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.2);
                animation: deslizarEntrada 0.4s ease, desvanecerSalida 0.4s ease 4.6s;
                animation-fill-mode: forwards;
                overflow: hidden;
                font-family: Arial, sans-serif;
            }
            
            .notificacion-cabecera {
                padding: 12px 16px;
                color: white;
                display: flex;
                align-items: center;
                justify-content: space-between;
                font-size: 16px;
            }
            
            .notificacion-titulo {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            
            .notificacion-icono {
                font-size: 18px;
                font-weight: bold;
            }
            
            .notificacion-cerrar {
                background: rgba(255,255,255,0.2);
                border: none;
                color: white;
                font-size: 22px;
                cursor: pointer;
                width: 28px;
                height: 28px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: background 0.3s;
            }
            
            .notificacion-cerrar:hover {
                background: rgba(255,255,255,0.3);
            }
            
            .notificacion-cuerpo {
                padding: 16px;
                color: #333;
                font-size: 14px;
                line-height: 1.5;
            }
            
            @keyframes deslizarEntrada {
                from { 
                    transform: translateX(100%); 
                    opacity: 0; 
                }
                to { 
                    transform: translateX(0); 
                    opacity: 1; 
                }
            }
            
            @keyframes desvanecerSalida {
                from { 
                    transform: translateX(0); 
                    opacity: 1; 
                }
                to { 
                    transform: translateX(100%); 
                    opacity: 0; 
                }
            }
            
            /* Responsive */
            @media (max-width: 768px) {
                .notificacion-toast {
                    width: 90%;
                    left: 5%;
                    right: 5%;
                    top: 10px;
                }
                
                @keyframes deslizarEntrada {
                    from { 
                        transform: translateY(-100%); 
                        opacity: 0; 
                    }
                    to { 
                        transform: translateY(0); 
                        opacity: 1; 
                    }
                }
                
                @keyframes desvanecerSalida {
                    from { 
                        transform: translateY(0); 
                        opacity: 1; 
                    }
                    to { 
                        transform: translateY(-100%); 
                        opacity: 0; 
                    }
                }
            }
            </style>';

      // JavaScript para manejar la notificación
      echo '<script>
            function cerrarNotificacion(id) {
                var elemento = document.getElementById(id);
                if (elemento) {
                    elemento.style.animation = "desvanecerSalida 0.4s ease forwards";
                    setTimeout(function() {
                        if (elemento.parentNode) {
                            elemento.parentNode.removeChild(elemento);
                        }
                    }, 400);
                }
            }
            
            // Auto-eliminar después de 5 segundos
            setTimeout(function() {
                cerrarNotificacion("' . $idNotificacion . '");
            }, 5000);
            </script>';

      // Limpiar el mensaje después de mostrarlo
      unset($_SESSION['mensaje']);
      unset($_SESSION['icono']);
    }
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

  public static function set($mensaje, $tipo = 'info')
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $_SESSION['mensaje'] = $mensaje;
    $_SESSION['icono'] = $tipo;
  }

  // Método para mostrar notificaciones múltiples
  public static function mostrarTodas()
  {
    self::show();
  }
}
