    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
      <div class="p-3">
        <h5>Title</h5>
        <p>Sidebar content</p>
      </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <!-- To the right -->
      <div class="float-right d-none d-sm-inline">
        UPTEC "MS"
      </div>
      <!-- Default to the left -->
      <strong>Copyright &copy; 2025 Nuevo Horizonte.</strong> Todos los derechos reservados.
    </footer>
    </div>

    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->


    <!-- jQuery -->
    <script src="<?= URL; ?>/public/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= URL; ?>/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= URL; ?>/public/dist/js/adminlte.min.js"></script>
    <!-- Otros plugins -->
    <script src="<?= URL; ?>/public/plugins/select2/js/select2.full.min.js"></script>
    <script src="<?= URL; ?>/public/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= URL; ?>/public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <!-- Script para mejor manejo de notificaciones -->
    <script>
      // Función global para cerrar notificaciones
      function cerrarNotificacionGlobal(id) {
        const elemento = document.getElementById(id);
        if (elemento) {
          // Agregar animación de salida
          elemento.style.animation = 'desvanecerSalida 0.4s ease forwards';

          // Eliminar después de la animación
          setTimeout(() => {
            if (elemento.parentNode) {
              elemento.parentNode.removeChild(elemento);
            }
          }, 400);
        }
      }

      // Cerrar todas las notificaciones
      function cerrarTodasNotificaciones() {
        const notificaciones = document.querySelectorAll('.notificacion-toast');
        notificaciones.forEach(notif => {
          notif.style.animation = 'desvanecerSalida 0.4s ease forwards';
          setTimeout(() => {
            if (notif.parentNode) {
              notif.parentNode.removeChild(notif);
            }
          }, 400);
        });
      }

      // Si hay muchas notificaciones, espaciarlas
      document.addEventListener('DOMContentLoaded', function() {
        const notificaciones = document.querySelectorAll('.notificacion-toast');
        notificaciones.forEach((notif, index) => {
          notif.style.top = (20 + (index * 80)) + 'px';
        });
      });
    </script>
    </body>

    </html>