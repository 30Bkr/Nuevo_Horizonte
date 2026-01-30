<?php
// Incluir layouts
include_once("/xampp/htdocs/final/layout/layaout1.php");

// Verificar permisos de administrador
if ($_SESSION['usuario_rol_id'] != 1) {
  header('Location: /final/admin/index.php');
  exit();
}

// Incluir clases necesarias
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/users.php");
require_once '/xampp/htdocs/final/global/notifications.php';
?>

<style>
  /* Estilos para el modal de cambiar contraseña */
  .strength-bar {
    height: 8px;
    border-radius: 4px;
    background: #eee;
    overflow: hidden;
    margin-top: 5px;
  }

  .strength-fill {
    height: 100%;
    width: 0%;
    border-radius: 4px;
    transition: width 0.3s, background-color 0.3s;
  }

  .requirement-item {
    margin-bottom: 5px;
    padding-left: 25px;
    position: relative;
  }

  .requirement-item:before {
    content: "✗";
    position: absolute;
    left: 0;
    color: #dc3545;
  }

  .requirement-item.valid:before {
    content: "✓";
    color: #28a745;
  }

  .password-input-group {
    position: relative;
  }

  .toggle-password-btn {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
  }

  .toggle-password-btn:hover {
    color: #007bff;
  }

  .user-card {
    transition: all 0.3s ease;
    border-radius: 10px;
    overflow: hidden;
  }

  .user-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
  }

  .user-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
  }

  .role-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
  }

  .role-admin {
    background-color: #dc3545;
    color: white;
  }

  .role-docente {
    background-color: #007bff;
    color: white;
  }

  .status-active {
    color: #28a745;
  }

  .status-inactive {
    color: #6c757d;
  }

  .password-expired {
    color: #ffc107;
    font-weight: bold;
  }

  .action-buttons .btn {
    padding: 5px 10px;
    margin: 2px;
    font-size: 12px;
  }

  .search-box {
    position: relative;
  }

  .search-box .form-control {
    padding-right: 40px;
  }

  .search-box i {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
  }

  .table-responsive {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
  }

  .table tbody tr:hover {
    background-color: #f8f9fa;
  }
</style>

<style>
  /* Estilos específicos para el modal de cambiar contraseña */
  .modal-password {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  }

  .modal-password .modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-bottom: none;
    padding: 1.5rem;
  }

  .modal-password .modal-title {
    font-weight: 600;
    font-size: 1.3rem;
  }

  .modal-password .modal-body {
    padding: 2rem;
  }

  .modal-password .modal-footer {
    border-top: 1px solid #eee;
    padding: 1.5rem 2rem;
  }

  .password-strength-container {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    margin-top: 15px;
  }

  .strength-bar {
    height: 10px;
    border-radius: 5px;
    background: #e9ecef;
    overflow: hidden;
    margin: 10px 0;
    position: relative;
  }

  .strength-fill {
    height: 100%;
    width: 0%;
    border-radius: 5px;
    transition: all 0.5s ease;
    position: relative;
    overflow: hidden;
  }

  .strength-fill::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    animation: shine 2s infinite;
  }

  @keyframes shine {
    0% {
      transform: translateX(-100%);
    }

    100% {
      transform: translateX(100%);
    }
  }

  .requirement-list {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
  }

  .requirement-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    padding: 8px 10px;
    border-radius: 6px;
    transition: all 0.3s ease;
  }

  .requirement-item:hover {
    background: rgba(0, 0, 0, 0.02);
  }

  .requirement-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    font-size: 12px;
    transition: all 0.3s ease;
  }

  .requirement-icon.invalid {
    background: #fee;
    color: #dc3545;
    border: 1px solid #f5c6cb;
  }

  .requirement-icon.valid {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
  }

  .requirement-text {
    flex: 1;
    font-size: 14px;
  }

  .password-input-wrapper {
    position: relative;
    margin-bottom: 5px;
  }

  .password-toggle-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
  }

  .password-toggle-btn:hover {
    background: #f8f9fa;
    color: #007bff;
  }

  .form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
  }

  .form-label i {
    margin-right: 8px;
    width: 20px;
    text-align: center;
  }

  .form-control-lg {
    border-radius: 10px;
    padding: 12px 50px 12px 15px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
  }

  .form-control-lg:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }

  .match-feedback {
    padding: 8px 12px;
    border-radius: 6px;
    margin-top: 8px;
    font-size: 14px;
    display: flex;
    align-items: center;
    animation: fadeIn 0.3s ease;
  }

  .match-feedback.valid {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
  }

  .match-feedback.invalid {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .force-change-checkbox {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
  }

  .force-change-checkbox .form-check-label {
    font-weight: 600;
    color: #856404;
  }

  .strength-label {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
  }

  .strength-label span {
    font-weight: 600;
    font-size: 14px;
  }

  .strength-score {
    font-weight: bold;
    font-size: 14px;
  }
</style>

<div class="content-wrapper">
  <div class="content">
    <div class="container-fluid">
      <!-- Header -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">
              <i class="fas fa-users mr-2"></i>
              Gestión de Usuarios
            </h4>
            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/final/admin/index.php">Inicio</a></li>
                <li class="breadcrumb-item active">Usuarios</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <!-- Filtros y Búsqueda -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="search-box">
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre, usuario o cédula...">
                    <i class="fas fa-search"></i>
                  </div>
                </div>
                <!-- <div class="col-md-6">
                  <div class="form-group mb-0">
                    <select id="roleFilter" class="form-control">
                      <option value="">Todos los roles</option>
                      <option value="1">Administradores</option>
                      <option value="2">Docentes</option>
                    </select>
                  </div>
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla de Usuarios -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0">
                <i class="fas fa-list mr-2"></i>
                Lista de Usuarios
              </h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover" id="usersTable">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Usuario</th>
                      <th>Nombre Completo</th>
                      <th>Cédula</th>
                      <th>Rol</th>
                      <th>Estado</th>
                      <th>Contraseña</th>
                      <th>Último Cambio</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $userModel = new Usuarios();
                    $usuarios = $userModel->listarAll();

                    if (empty($usuarios)):
                    ?>
                      <tr>
                        <td colspan="9" class="text-center">
                          <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle mr-2"></i>
                            No hay usuarios registrados en el sistema.
                          </div>
                        </td>
                      </tr>
                      <?php
                    else:
                      $contador = 1;
                      foreach ($usuarios as $usuario):
                        // Determinar color del rol
                        $roleClass = ($usuario['id_rol'] == 1) ? 'role-admin' : 'role-docente';
                        $roleText = ($usuario['id_rol'] == 1) ? 'Administrador' : 'Docente';

                        // Determinar estado
                        $statusClass = ($usuario['estatus'] == 1) ? 'status-active' : 'status-inactive';
                        $statusText = ($usuario['estatus'] == 1) ? 'Activo' : 'Inactivo';

                        // Verificar si requiere cambio de contraseña
                        $passwordStatus = $userModel->requiereCambioContrasena($usuario['id_usuario']);
                        $passwordText = $passwordStatus ? 'Requiere cambio' : 'Actualizada';
                        $passwordClass = $passwordStatus ? 'password-expired' : 'text-success';

                        // Formatear fecha de último cambio
                        $lastChange = !empty($usuario['fecha_ultimo_cambio'])
                          ? date('d/m/Y H:i', strtotime($usuario['fecha_ultimo_cambio']))
                          : 'Nunca';
                      ?>
                        <tr data-id="<?php echo $usuario['id_usuario']; ?>">
                          <td><?php echo $contador++; ?></td>
                          <td>
                            <strong><?php echo htmlspecialchars($usuario['usuario']); ?></strong>
                          </td>
                          <td>
                            <?php
                            echo htmlspecialchars($usuario['primer_nombre'] . ' ' .
                              ($usuario['segundo_nombre'] ? $usuario['segundo_nombre'] . ' ' : '') .
                              $usuario['primer_apellido'] . ' ' .
                              ($usuario['segundo_apellido'] ? $usuario['segundo_apellido'] : ''));
                            ?>
                          </td>
                          <td><?php echo htmlspecialchars($usuario['cedula']); ?></td>
                          <td>
                            <span class="role-badge <?php echo $roleClass; ?>">
                              <?php echo $roleText; ?>
                            </span>
                          </td>
                          <td>
                            <span class="<?php echo $statusClass; ?>">
                              <i class="fas fa-circle mr-1"></i>
                              <?php echo $statusText; ?>
                            </span>
                          </td>
                          <td>
                            <span class="<?php echo $passwordClass; ?>">
                              <i class="fas fa-key mr-1"></i>
                              <?php echo $passwordText; ?>
                            </span>
                          </td>
                          <td>
                            <small class="text-muted"><?php echo $lastChange; ?></small>
                          </td>
                          <td>
                            <div class="action-buttons">
                              <button class="btn btn-sm btn-primary change-password-btn"
                                data-id="<?php echo $usuario['id_usuario']; ?>"
                                data-name="<?php echo htmlspecialchars($usuario['primer_nombre'] . ' ' . $usuario['primer_apellido']); ?>">
                                <i class="fas fa-key"></i> Cambiar
                              </button>

                              <!-- <button class="btn btn-sm btn-warning force-reset-btn"
                                data-id="<?php echo $usuario['id_usuario']; ?>"
                                data-name="<?php echo htmlspecialchars($usuario['primer_nombre'] . ' ' . $usuario['primer_apellido']); ?>">
                                <i class="fas fa-sync-alt"></i> Forzar
                              </button> -->

                              <!-- <?php if ($usuario['id_usuario'] != $_SESSION['usuario_id']): ?>
                                <button class="btn btn-sm <?php echo $usuario['estatus'] == 1 ? 'btn-danger' : 'btn-success'; ?> toggle-status-btn"
                                  data-id="<?php echo $usuario['id_usuario']; ?>"
                                  data-status="<?php echo $usuario['estatus']; ?>"
                                  data-name="<?php echo htmlspecialchars($usuario['primer_nombre'] . ' ' . $usuario['primer_apellido']); ?>">
                                  <i class="fas <?php echo $usuario['estatus'] == 1 ? 'fa-ban' : 'fa-check'; ?>"></i>
                                  <?php echo $usuario['estatus'] == 1 ? 'Inactivar' : 'Activar'; ?>
                                </button>
                              <?php endif; ?> -->
                            </div>
                          </td>
                        </tr>
                    <?php
                      endforeach;
                    endif;
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal para cambiar contraseña -->
<!-- Modal para cambiar contraseña (versión simplificada) -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
          <i class="fas fa-key mr-2"></i>
          <span id="modalUserName"></span>
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="adminChangePasswordForm" action="/final/app/admin_cambiar_contrasena.php" method="post">
        <div class="modal-body">
          <input type="hidden" name="user_id" id="modalUserId">

          <!-- Opción para forzar cambio -->
          <div class="form-group form-check mb-4">
            <input type="checkbox" class="form-check-input" id="forzarCambio" name="forzar_cambio" value="1">
            <label class="form-check-label" for="forzarCambio">
              <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
              <strong>Forzar al usuario a cambiar la contraseña en el próximo inicio de sesión</strong>
              <small class="d-block text-muted">El usuario no podrá acceder al sistema hasta que cambie su contraseña</small>
            </label>
          </div>

          <div class="form-group">
            <label for="admin_new_password" class="font-weight-bold">
              <i class="fas fa-key mr-2"></i>Nueva Contraseña *
            </label>
            <div class="password-input-group">
              <input type="password"
                class="form-control form-control-lg"
                id="admin_new_password"
                name="nueva_contrasena"
                required
                placeholder="Ingresa la nueva contraseña"
                minlength="8">
              <button type="button" class="toggle-password-btn" data-target="admin_new_password">
                <i class="fas fa-eye"></i>
              </button>
            </div>

            <div class="mt-3">
              <small class="text-muted d-block mb-2">Fortaleza de la contraseña:</small>
              <div class="strength-bar">
                <div class="strength-fill" id="admin-strength-fill"></div>
              </div>
              <div class="d-flex justify-content-between mt-1">
                <small id="admin-strength-text" class="font-weight-bold">Muy débil</small>
                <small id="admin-strength-score">0/5</small>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="admin_confirm_password" class="font-weight-bold">
              <i class="fas fa-check-double mr-2"></i>Confirmar Nueva Contraseña *
            </label>
            <div class="password-input-group">
              <input type="password"
                class="form-control form-control-lg"
                id="admin_confirm_password"
                name="confirmar_contrasena"
                required
                placeholder="Confirma la nueva contraseña">
              <button type="button" class="toggle-password-btn" data-target="admin_confirm_password">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <div id="admin-match-feedback" class="mt-2"></div>
          </div>

          <div class="card mt-4">
            <div class="card-header bg-info text-white">
              <h5 class="card-title mb-0">
                <i class="fas fa-clipboard-check mr-2"></i>
                Requisitos de Seguridad
              </h5>
            </div>
            <div class="card-body">
              <div class="requirement-item" id="admin-req-length">Mínimo 8 caracteres</div>
              <div class="requirement-item" id="admin-req-upper">Al menos 1 letra mayúscula (A-Z)</div>
              <div class="requirement-item" id="admin-req-lower">Al menos 1 letra minúscula (a-z)</div>
              <div class="requirement-item" id="admin-req-number">Al menos 1 número (0-9)</div>
              <div class="requirement-item" id="admin-req-special">Al menos 1 carácter especial (!@#$%^&*()-_=+{}[]:;,<.>)</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-2"></i>
            Cancelar
          </button>
          <button type="submit" class="btn btn-primary" id="admin-submit-btn">
            <i class="fas fa-save mr-2"></i>
            Cambiar Contraseña
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal para confirmar forzar cambio -->
<div class="modal fade" id="forceResetModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-warning">
          <i class="fas fa-exclamation-triangle mr-2"></i>
          Forzar Cambio de Contraseña
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="forceResetForm" action="/final/app/forzar_cambio_contrasena.php" method="post">
        <div class="modal-body">
          <input type="hidden" name="user_id" id="forceUserId">

          <div class="alert alert-warning">
            <i class="fas fa-info-circle mr-2"></i>
            <span id="forceUserName"></span> deberá cambiar su contraseña en el próximo inicio de sesión.
          </div>

          <div class="form-group">
            <label>¿Está seguro de forzar el cambio de contraseña?</label>
            <p class="text-muted">El usuario no podrá acceder al sistema hasta que cambie su contraseña.</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-warning">
            <i class="fas fa-sync-alt mr-2"></i>
            Forzar Cambio
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal para confirmar cambio de estado -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="toggleStatusTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="toggleStatusForm" action="/final/app/toggle_usuario_status.php" method="post">
        <div class="modal-body">
          <input type="hidden" name="user_id" id="statusUserId">
          <input type="hidden" name="new_status" id="newStatus">

          <div id="toggleStatusMessage"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn" id="toggleStatusBtn">
            <!-- El contenido se llena con JS -->
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // ===== MODAL CAMBIAR CONTRASEÑA =====
    const changePasswordBtns = document.querySelectorAll('.change-password-btn');
    const changePasswordModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));

    changePasswordBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        const userId = this.getAttribute('data-id');
        const userName = this.getAttribute('data-name');

        document.getElementById('modalUserId').value = userId;
        document.getElementById('modalUserName').textContent =
          `Cambiar Contraseña - ${userName}`;

        // Resetear formulario
        document.getElementById('adminChangePasswordForm').reset();
        resetStrengthMeter();
        resetRequirements();
        document.getElementById('admin-match-feedback').innerHTML = '';

        changePasswordModal.show();
      });
    });

    // ===== VALIDACIÓN DE CONTRASEÑA EN MODAL =====
    const adminPasswordInput = document.getElementById('admin_new_password');
    const adminConfirmInput = document.getElementById('admin_confirm_password');
    const adminStrengthFill = document.getElementById('admin-strength-fill');
    const adminStrengthText = document.getElementById('admin-strength-text');
    const adminStrengthScore = document.getElementById('admin-strength-score');
    const adminMatchFeedback = document.getElementById('admin-match-feedback');

    // Requisitos
    const adminReqLength = document.getElementById('admin-req-length');
    const adminReqUpper = document.getElementById('admin-req-upper');
    const adminReqLower = document.getElementById('admin-req-lower');
    const adminReqNumber = document.getElementById('admin-req-number');
    const adminReqSpecial = document.getElementById('admin-req-special');

    const strengthColors = [
      '#dc3545', // Muy débil
      '#ff6b6b', // Débil
      '#ffc107', // Regular
      '#51cf66', // Buena
      '#28a745' // Excelente
    ];

    const strengthLabels = [
      'Muy débil',
      'Débil',
      'Regular',
      'Buena',
      'Excelente'
    ];

    // Mostrar/ocultar contraseña
    document.querySelectorAll('.toggle-password-btn').forEach(button => {
      button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = this.querySelector('i');

        if (input.type === 'password') {
          input.type = 'text';
          icon.classList.remove('fa-eye');
          icon.classList.add('fa-eye-slash');
        } else {
          input.type = 'password';
          icon.classList.remove('fa-eye-slash');
          icon.classList.add('fa-eye');
        }
      });
    });

    // Validar contraseña en tiempo real
    adminPasswordInput.addEventListener('input', validateAdminPassword);
    adminConfirmInput.addEventListener('input', checkAdminPasswordMatch);

    function validateAdminPassword() {
      const password = adminPasswordInput.value;

      // Verificar requisitos
      const hasLength = password.length >= 8;
      const hasUpper = /[A-Z]/.test(password);
      const hasLower = /[a-z]/.test(password);
      const hasNumber = /[0-9]/.test(password);
      const hasSpecial = /[!@#$%^&*()\-_=+{}\[\]:;,<.>]/.test(password);

      // Actualizar indicadores visuales
      updateRequirement(adminReqLength, hasLength);
      updateRequirement(adminReqUpper, hasUpper);
      updateRequirement(adminReqLower, hasLower);
      updateRequirement(adminReqNumber, hasNumber);
      updateRequirement(adminReqSpecial, hasSpecial);

      // Calcular fortaleza (0-4)
      let strength = 0;
      if (hasLength) strength++;
      if (hasUpper) strength++;
      if (hasLower) strength++;
      if (hasNumber) strength++;
      if (hasSpecial) strength++;

      // Actualizar barra y texto
      const percentage = (strength / 5) * 100;
      adminStrengthFill.style.width = percentage + '%';
      adminStrengthFill.style.backgroundColor = strengthColors[strength];
      adminStrengthText.textContent = strengthLabels[strength];
      adminStrengthText.style.color = strengthColors[strength];
      adminStrengthScore.textContent = `${strength}/5`;

      // Verificar coincidencia si ya hay confirmación
      if (adminConfirmInput.value) {
        checkAdminPasswordMatch();
      }
    }

    function checkAdminPasswordMatch() {
      const password = adminPasswordInput.value;
      const confirm = adminConfirmInput.value;

      if (confirm.length === 0) {
        adminMatchFeedback.innerHTML = '';
        return;
      }

      if (password === confirm) {
        adminMatchFeedback.innerHTML = '<span class="text-success"><i class="fas fa-check-circle mr-1"></i> Las contraseñas coinciden</span>';
      } else {
        adminMatchFeedback.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle mr-1"></i> Las contraseñas no coinciden</span>';
      }
    }

    function updateRequirement(element, isValid) {
      if (isValid) {
        element.classList.add('valid');
      } else {
        element.classList.remove('valid');
      }
    }

    function resetStrengthMeter() {
      adminStrengthFill.style.width = '0%';
      adminStrengthFill.style.backgroundColor = strengthColors[0];
      adminStrengthText.textContent = strengthLabels[0];
      adminStrengthText.style.color = strengthColors[0];
      adminStrengthScore.textContent = '0/5';
    }

    function resetRequirements() {
      [adminReqLength, adminReqUpper, adminReqLower, adminReqNumber, adminReqSpecial]
      .forEach(el => el.classList.remove('valid'));
    }

    // Validar formulario antes de enviar
    document.getElementById('adminChangePasswordForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const password = adminPasswordInput.value;
      const confirm = adminConfirmInput.value;

      // Verificar coincidencia
      if (password !== confirm) {
        alert('Error: Las contraseñas no coinciden');
        adminConfirmInput.focus();
        return false;
      }

      // Verificar requisitos
      const hasLength = password.length >= 8;
      const hasUpper = /[A-Z]/.test(password);
      const hasLower = /[a-z]/.test(password);
      const hasNumber = /[0-9]/.test(password);
      const hasSpecial = /[!@#$%^&*()\-_=+{}\[\]:;,<.>]/.test(password);

      if (!hasLength || !hasUpper || !hasLower || !hasNumber || !hasSpecial) {
        alert('Error: La contraseña no cumple con todos los requisitos de seguridad');
        adminPasswordInput.focus();
        return false;
      }

      // Mostrar loading
      const submitBtn = document.getElementById('admin-submit-btn');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';
      submitBtn.disabled = true;

      // Enviar formulario
      this.submit();
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let currentUserId = null;
    let currentUserName = null;

    // ===== FILTRADO Y BÚSQUEDA =====
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const tableRows = document.querySelectorAll('#usersTable tbody tr');

    function filterTable() {
      const searchTerm = searchInput.value.toLowerCase();
      const roleValue = roleFilter.value;

      tableRows.forEach(row => {
        const userName = row.cells[2].textContent.toLowerCase();
        const userCedula = row.cells[3].textContent.toLowerCase();
        const userUsuario = row.cells[1].textContent.toLowerCase();
        const userRole = row.cells[4].querySelector('.role-badge').textContent;
        const roleId = userRole === 'Administrador' ? '1' : '2';

        const matchesSearch = searchTerm === '' ||
          userName.includes(searchTerm) ||
          userCedula.includes(searchTerm) ||
          userUsuario.includes(searchTerm);

        const matchesRole = roleValue === '' || roleId === roleValue;

        row.style.display = (matchesSearch && matchesRole) ? '' : 'none';
      });
    }

    searchInput.addEventListener('input', filterTable);
    roleFilter.addEventListener('change', filterTable);

    // ===== MODAL CAMBIAR CONTRASEÑA =====
    const changePasswordBtns = document.querySelectorAll('.change-password-btn');
    const changePasswordModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));

    changePasswordBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        currentUserId = this.getAttribute('data-id');
        currentUserName = this.getAttribute('data-name');

        document.getElementById('modalUserId').value = currentUserId;
        document.getElementById('modalUserName').textContent =
          `Cambiar Contraseña - ${currentUserName}`;

        // Resetear formulario
        document.getElementById('adminChangePasswordForm').reset();
        resetStrengthMeter();
        resetRequirements();
        document.getElementById('admin-match-feedback').innerHTML = '';

        changePasswordModal.show();
      });
    });

    // ===== VALIDACIÓN DE CONTRASEÑA EN MODAL =====
    const adminPasswordInput = document.getElementById('admin_new_password');
    const adminConfirmInput = document.getElementById('admin_confirm_password');
    const adminStrengthFill = document.getElementById('admin-strength-fill');
    const adminStrengthText = document.getElementById('admin-strength-text');
    const adminStrengthScore = document.getElementById('admin-strength-score');
    const adminMatchFeedback = document.getElementById('admin-match-feedback');

    // Requisitos
    const adminReqLength = document.getElementById('admin-req-length');
    const adminReqUpper = document.getElementById('admin-req-upper');
    const adminReqLower = document.getElementById('admin-req-lower');
    const adminReqNumber = document.getElementById('admin-req-number');
    const adminReqSpecial = document.getElementById('admin-req-special');

    const strengthColors = [
      '#dc3545', // Muy débil
      '#ff6b6b', // Débil
      '#ffc107', // Regular
      '#51cf66', // Buena
      '#28a745' // Excelente
    ];

    const strengthLabels = [
      'Muy débil',
      'Débil',
      'Regular',
      'Buena',
      'Excelente'
    ];

    // Mostrar/ocultar contraseña
    document.querySelectorAll('.toggle-password-btn').forEach(button => {
      button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = this.querySelector('i');

        if (input.type === 'password') {
          input.type = 'text';
          icon.classList.remove('fa-eye');
          icon.classList.add('fa-eye-slash');
        } else {
          input.type = 'password';
          icon.classList.remove('fa-eye-slash');
          icon.classList.add('fa-eye');
        }
      });
    });

    // Validar contraseña en tiempo real
    adminPasswordInput.addEventListener('input', validateAdminPassword);
    adminConfirmInput.addEventListener('input', checkAdminPasswordMatch);

    function validateAdminPassword() {
      const password = adminPasswordInput.value;

      // Verificar requisitos
      const hasLength = password.length >= 8;
      const hasUpper = /[A-Z]/.test(password);
      const hasLower = /[a-z]/.test(password);
      const hasNumber = /[0-9]/.test(password);
      const hasSpecial = /[!@#$%^&*()\-_=+{}\[\]:;,<.>]/.test(password);

      // Actualizar indicadores visuales
      updateRequirement(adminReqLength, hasLength);
      updateRequirement(adminReqUpper, hasUpper);
      updateRequirement(adminReqLower, hasLower);
      updateRequirement(adminReqNumber, hasNumber);
      updateRequirement(adminReqSpecial, hasSpecial);

      // Calcular fortaleza (0-4)
      let strength = 0;
      if (hasLength) strength++;
      if (hasUpper) strength++;
      if (hasLower) strength++;
      if (hasNumber) strength++;
      if (hasSpecial) strength++;

      // Actualizar barra y texto
      const percentage = (strength / 5) * 100;
      adminStrengthFill.style.width = percentage + '%';
      adminStrengthFill.style.backgroundColor = strengthColors[strength];
      adminStrengthText.textContent = strengthLabels[strength];
      adminStrengthText.style.color = strengthColors[strength];
      adminStrengthScore.textContent = `${strength}/5`;

      // Verificar coincidencia si ya hay confirmación
      if (adminConfirmInput.value) {
        checkAdminPasswordMatch();
      }
    }

    function checkAdminPasswordMatch() {
      const password = adminPasswordInput.value;
      const confirm = adminConfirmInput.value;

      if (confirm.length === 0) {
        adminMatchFeedback.innerHTML = '';
        return;
      }

      if (password === confirm) {
        adminMatchFeedback.innerHTML = '<span class="text-success"><i class="fas fa-check-circle mr-1"></i> Las contraseñas coinciden</span>';
      } else {
        adminMatchFeedback.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle mr-1"></i> Las contraseñas no coinciden</span>';
      }
    }

    function updateRequirement(element, isValid) {
      if (isValid) {
        element.classList.add('valid');
      } else {
        element.classList.remove('valid');
      }
    }

    function resetStrengthMeter() {
      adminStrengthFill.style.width = '0%';
      adminStrengthFill.style.backgroundColor = strengthColors[0];
      adminStrengthText.textContent = strengthLabels[0];
      adminStrengthText.style.color = strengthColors[0];
      adminStrengthScore.textContent = '0/5';
    }

    function resetRequirements() {
      [adminReqLength, adminReqUpper, adminReqLower, adminReqNumber, adminReqSpecial]
      .forEach(el => el.classList.remove('valid'));
    }

    // Validar formulario antes de enviar
    document.getElementById('adminChangePasswordForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const password = adminPasswordInput.value;
      const confirm = adminConfirmInput.value;

      // Verificar coincidencia
      if (password !== confirm) {
        Notification.show('Error: Las contraseñas no coinciden', 'error');
        adminConfirmInput.focus();
        return false;
      }

      // Verificar requisitos
      const hasLength = password.length >= 8;
      const hasUpper = /[A-Z]/.test(password);
      const hasLower = /[a-z]/.test(password);
      const hasNumber = /[0-9]/.test(password);
      const hasSpecial = /[!@#$%^&*()\-_=+{}\[\]:;,<.>]/.test(password);

      if (!hasLength || !hasUpper || !hasLower || !hasNumber || !hasSpecial) {
        Notification.show('Error: La contraseña no cumple con todos los requisitos de seguridad', 'error');
        adminPasswordInput.focus();
        return false;
      }

      // Mostrar loading
      const submitBtn = document.getElementById('admin-submit-btn');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';
      submitBtn.disabled = true;

      // Enviar formulario
      this.submit();
    });

    // ===== MODAL FORZAR CAMBIO =====
    const forceResetBtns = document.querySelectorAll('.force-reset-btn');
    const forceResetModal = new bootstrap.Modal(document.getElementById('forceResetModal'));

    forceResetBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        const userId = this.getAttribute('data-id');
        const userName = this.getAttribute('data-name');

        document.getElementById('forceUserId').value = userId;
        document.getElementById('forceUserName').textContent =
          `¿Forzar cambio de contraseña para ${userName}?`;

        forceResetModal.show();
      });
    });

    // ===== MODAL CAMBIAR ESTADO =====
    const toggleStatusBtns = document.querySelectorAll('.toggle-status-btn');
    const toggleStatusModal = new bootstrap.Modal(document.getElementById('toggleStatusModal'));

    toggleStatusBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        const userId = this.getAttribute('data-id');
        const currentStatus = this.getAttribute('data-status');
        const userName = this.getAttribute('data-name');
        const newStatus = currentStatus == '1' ? '0' : '1';

        document.getElementById('statusUserId').value = userId;
        document.getElementById('newStatus').value = newStatus;

        const isActivating = newStatus == '1';
        const actionText = isActivating ? 'Activar' : 'Inactivar';
        const modalTitle = isActivating ?
          `<i class="fas fa-check text-success mr-2"></i> Activar Usuario` :
          `<i class="fas fa-ban text-danger mr-2"></i> Inactivar Usuario`;

        const message = isActivating ?
          `<div class="alert alert-success">
                        <i class="fas fa-info-circle mr-2"></i>
                        ¿Activar la cuenta de <strong>${userName}</strong>?
                        <br><small class="text-muted">El usuario podrá acceder al sistema nuevamente.</small>
                    </div>` :
          `<div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        ¿Inactivar la cuenta de <strong>${userName}</strong>?
                        <br><small class="text-muted">El usuario no podrá acceder al sistema.</small>
                    </div>`;

        const btnClass = isActivating ? 'btn-success' : 'btn-danger';
        const btnText = isActivating ?
          '<i class="fas fa-check mr-2"></i> Activar' :
          '<i class="fas fa-ban mr-2"></i> Inactivar';

        document.getElementById('toggleStatusTitle').innerHTML = modalTitle;
        document.getElementById('toggleStatusMessage').innerHTML = message;

        const toggleBtn = document.getElementById('toggleStatusBtn');
        toggleBtn.className = `btn ${btnClass}`;
        toggleBtn.innerHTML = btnText;

        toggleStatusModal.show();
      });
    });

    // ===== NOTIFICACIONES =====
    const Notification = {
      show: function(message, type = 'info') {
        const alertClass = {
          'success': 'alert-success',
          'error': 'alert-danger',
          'warning': 'alert-warning',
          'info': 'alert-info'
        } [type] || 'alert-info';

        const icon = {
          'success': 'fa-check-circle',
          'error': 'fa-times-circle',
          'warning': 'fa-exclamation-triangle',
          'info': 'fa-info-circle'
        } [type] || 'fa-info-circle';

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
        alertDiv.innerHTML = `
                    <i class="fas ${icon} mr-2"></i>
                    ${message}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                `;

        // Insertar al inicio del contenedor principal
        const container = document.querySelector('.container-fluid');
        container.insertBefore(alertDiv, container.firstChild);

        // Auto-eliminar después de 5 segundos
        setTimeout(() => {
          if (alertDiv.parentNode) {
            alertDiv.remove();
          }
        }, 5000);
      }
    };
  });
</script>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
?>