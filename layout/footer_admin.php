
<?php
// ... (código existente) ...
?>
    </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2025 <a href="#">Nuevo Horizonte</a>.</strong>
        Todos los derechos reservados.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>

<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> 
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script> 

<script>
    /**
     * Muestra una alerta de notificación temporal (tipo AdminLTE/Bootstrap).
     * @param {string} message Mensaje a mostrar.
     * @param {string} type Tipo de alerta: 'success', 'danger', 'warning', 'info'.
     */
    window.mostrarAlertaGlobal = function(message, type) {
        // Podrías usar Toastr o SweetAlert2, pero implementamos un mensaje simple por ahora
        // que es lo que el código espera para continuar.
        
        // Usaremos SweetAlert2 ya que lo importaste en el header
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: type,
            title: message,
            showConfirmButton: false,
            timer: 3000
        });
    };
</script>

<script src="<?php echo BASE_URL; ?>/public/js/gestion_usuarios.js"></script>

<script>
    // Configuración global de DataTables al español
    $.extend(true, $.fn.dataTable.defaults, {
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        "responsive": true
    });
</script>

</body>
</html>