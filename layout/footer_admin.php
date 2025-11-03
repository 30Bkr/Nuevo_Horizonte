                </div></div>
        </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2025-2026 Nuevo Horizonte.</strong> Todos los derechos reservados.
    </footer>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>

<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="../public/js/usuarios.js"></script> 

<?php 
// 1. Incluir el archivo de alertas (¡USAR RUTA ABSOLUTA!)
require_once($_SERVER['DOCUMENT_ROOT'] . '/nuevo_horizonte/app/controllers/alerts.php'); 

// 2. Mostrar cualquier alerta pendiente
displayAndClearAlert();
?>

</body>
</html>