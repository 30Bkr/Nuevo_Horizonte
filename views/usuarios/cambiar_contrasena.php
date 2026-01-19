<?php
// Incluir layouts
include_once("/xampp/htdocs/final/layout/layaout1.php");

// Incluir clases necesarias
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/users.php");
include_once("/xampp/htdocs/final/app/password_validator.php");

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
  header('Location: /final/login/index.php');
  exit();
}

$user = new Usuarios();
$requiereCambio = $user->requiereCambioContrasena($_SESSION['usuario_id']);
$forzado = isset($_GET['forzado']) || $requiereCambio;

// Si es administrador y no es forzado, redirigir al dashboard
if ($_SESSION['usuario_rol_id'] == 1 && !$forzado) {
  header('Location: /final/admin/index.php');
  exit();
}
?>

<style>
  .card-password {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    border: none;
  }

  .card-password:hover {
    transform: translateY(-5px);
  }

  .password-icon {
    font-size: 3rem;
    opacity: 0.7;
  }

  .bg-change-password {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
  }

  .bg-requirements {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
  }

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

  .info-box {
    border-left: 4px solid #17a2b8;
    padding: 15px;
    margin-bottom: 20px;
    background-color: #e8f4f8;
  }

  .info-box.warning {
    border-left-color: #ffc107;
    background-color: #fff9e6;
  }

  .info-box.danger {
    border-left-color: #dc3545;
    background-color: #f8d7da;
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
              <i class="fas fa-key mr-2"></i>
              <?php echo $forzado ? 'Cambio de Contraseña Requerido' : 'Cambiar Contraseña'; ?>
            </h4>
            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="/final/admin/index.php">Inicio</a></li>
                <li class="breadcrumb-item active"><?php echo $forzado ? 'Cambio Requerido' : 'Cambiar Contraseña'; ?></li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <?php if ($forzado): ?>
        <div class="row mb-4">
          <div class="col-12">
            <div class="info-box warning">
              <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x text-warning mr-3"></i>
                <div>
                  <h5 class="mb-1">¡Atención!</h5>
                  <p class="mb-0">Por motivos de seguridad, debes cambiar tu contraseña antes de continuar usando el sistema.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <div class="row">
        <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
          <div class="card card-password">
            <div class="card-header bg-change-password">
              <h3 class="card-title mb-0 text-white">
                <i class="fas fa-user-shield mr-2"></i>
                Seguridad de la cuenta
              </h3>
            </div>
            <div class="card-body">
              <form id="formCambiarContrasena" action="/final/app/cambiar_contrasena.php" method="post">
                <?php if (!$forzado): ?>
                  <div class="form-group">
                    <label for="contrasena_actual" class="font-weight-bold">
                      <i class="fas fa-lock mr-2"></i>Contraseña Actual *
                    </label>
                    <div class="password-input-group">
                      <input type="password"
                        class="form-control form-control-lg"
                        id="contrasena_actual"
                        name="contrasena_actual"
                        required
                        placeholder="Ingresa tu contraseña actual">
                      <button type="button" class="toggle-password-btn" data-target="contrasena_actual">
                        <i class="fas fa-eye"></i>
                      </button>
                    </div>
                  </div>
                <?php endif; ?>

                <div class="form-group">
                  <label for="nueva_contrasena" class="font-weight-bold">
                    <i class="fas fa-key mr-2"></i>Nueva Contraseña *
                  </label>
                  <div class="password-input-group">
                    <input type="password"
                      class="form-control form-control-lg"
                      id="nueva_contrasena"
                      name="nueva_contrasena"
                      required
                      placeholder="Ingresa tu nueva contraseña"
                      minlength="8">
                    <button type="button" class="toggle-password-btn" data-target="nueva_contrasena">
                      <i class="fas fa-eye"></i>
                    </button>
                  </div>

                  <div class="mt-3">
                    <small class="text-muted d-block mb-2">Fortaleza de la contraseña:</small>
                    <div class="strength-bar">
                      <div class="strength-fill" id="strength-fill"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                      <small id="strength-text" class="font-weight-bold">Muy débil</small>
                      <small id="strength-score">0/5</small>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="confirmar_contrasena" class="font-weight-bold">
                    <i class="fas fa-check-double mr-2"></i>Confirmar Nueva Contraseña *
                  </label>
                  <div class="password-input-group">
                    <input type="password"
                      class="form-control form-control-lg"
                      id="confirmar_contrasena"
                      name="confirmar_contrasena"
                      required
                      placeholder="Confirma tu nueva contraseña">
                    <button type="button" class="toggle-password-btn" data-target="confirmar_contrasena">
                      <i class="fas fa-eye"></i>
                    </button>
                  </div>
                  <div id="match-feedback" class="mt-2"></div>
                </div>

                <div class="card mt-4 mb-4">
                  <div class="card-header bg-requirements">
                    <h5 class="card-title mb-0 text-white">
                      <i class="fas fa-clipboard-check mr-2"></i>
                      Requisitos de Seguridad
                    </h5>
                  </div>
                  <div class="card-body">
                    <div class="requirement-item" id="req-length">Mínimo 8 caracteres</div>
                    <div class="requirement-item" id="req-upper">Al menos 1 letra mayúscula (A-Z)</div>
                    <div class="requirement-item" id="req-lower">Al menos 1 letra minúscula (a-z)</div>
                    <div class="requirement-item" id="req-number">Al menos 1 número (0-9)</div>
                    <div class="requirement-item" id="req-special">Al menos 1 carácter especial (!@#$%^&*()-_=+{}[]:;,<.>)</div>
                  </div>
                </div>

                <div class="form-group mt-4">
                  <button type="submit" class="btn btn-primary btn-lg btn-block" id="submit-btn">
                    <i class="fas fa-save mr-2"></i>
                    <?php echo $forzado ? 'Continuar' : 'Cambiar Contraseña'; ?>
                  </button>

                  <?php if (!$forzado): ?>
                    <a href="/final/admin/index.php" class="btn btn-secondary btn-lg btn-block mt-2">
                      <i class="fas fa-times mr-2"></i>
                      Cancelar
                    </a>
                  <?php endif; ?>
                </div>
              </form>
            </div>
            <div class="card-footer">
              <small class="text-muted">
                <i class="fas fa-info-circle mr-1"></i>
                Para mayor seguridad, recomendamos cambiar tu contraseña periódicamente.
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const nuevaInput = document.getElementById('nueva_contrasena');
    const confirmInput = document.getElementById('confirmar_contrasena');
    const strengthFill = document.getElementById('strength-fill');
    const strengthText = document.getElementById('strength-text');
    const strengthScore = document.getElementById('strength-score');
    const matchFeedback = document.getElementById('match-feedback');
    const form = document.getElementById('formCambiarContrasena');
    const submitBtn = document.getElementById('submit-btn');

    // Elementos de requisitos
    const reqLength = document.getElementById('req-length');
    const reqUpper = document.getElementById('req-upper');
    const reqLower = document.getElementById('req-lower');
    const reqNumber = document.getElementById('req-number');
    const reqSpecial = document.getElementById('req-special');

    // Colores para fortaleza
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
    nuevaInput.addEventListener('input', function() {
      const password = this.value;

      // Verificar requisitos
      const hasLength = password.length >= 8;
      const hasUpper = /[A-Z]/.test(password);
      const hasLower = /[a-z]/.test(password);
      const hasNumber = /[0-9]/.test(password);
      const hasSpecial = /[!@#$%^&*()\-_=+{}\[\]:;,<.>]/.test(password);

      // Actualizar indicadores visuales
      updateRequirement(reqLength, hasLength);
      updateRequirement(reqUpper, hasUpper);
      updateRequirement(reqLower, hasLower);
      updateRequirement(reqNumber, hasNumber);
      updateRequirement(reqSpecial, hasSpecial);

      // Calcular fortaleza (0-4)
      let strength = 0;
      if (hasLength) strength++;
      if (hasUpper) strength++;
      if (hasLower) strength++;
      if (hasNumber) strength++;
      if (hasSpecial) strength++;

      // Actualizar barra y texto
      const percentage = (strength / 5) * 100;
      strengthFill.style.width = percentage + '%';
      strengthFill.style.backgroundColor = strengthColors[strength];
      strengthText.textContent = strengthLabels[strength];
      strengthText.style.color = strengthColors[strength];
      strengthScore.textContent = `${strength}/5`;

      // Verificar coincidencia si ya hay confirmación
      if (confirmInput.value) {
        checkPasswordMatch();
      }
    });

    // Verificar coincidencia de contraseñas
    confirmInput.addEventListener('input', checkPasswordMatch);

    function checkPasswordMatch() {
      const password = nuevaInput.value;
      const confirm = confirmInput.value;

      if (confirm.length === 0) {
        matchFeedback.innerHTML = '';
        return;
      }

      if (password === confirm) {
        matchFeedback.innerHTML = '<span class="text-success"><i class="fas fa-check-circle mr-1"></i> Las contraseñas coinciden</span>';
      } else {
        matchFeedback.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle mr-1"></i> Las contraseñas no coinciden</span>';
      }
    }

    function updateRequirement(element, isValid) {
      if (isValid) {
        element.classList.add('valid');
      } else {
        element.classList.remove('valid');
      }
    }

    // Validar formulario antes de enviar
    form.addEventListener('submit', function(e) {
      e.preventDefault();

      // Mostrar loading
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';
      submitBtn.disabled = true;

      // Validaciones básicas
      const password = nuevaInput.value;
      const confirm = confirmInput.value;

      // Verificar coincidencia
      if (password !== confirm) {
        alert('Error: Las contraseñas no coinciden');
        confirmInput.focus();
        resetSubmitButton(originalText);
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
        nuevaInput.focus();
        resetSubmitButton(originalText);
        return false;
      }

      // Enviar formulario
      this.submit();
    });

    function resetSubmitButton(originalText) {
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
    }

    // Inicializar validación
    if (nuevaInput.value) {
      nuevaInput.dispatchEvent(new Event('input'));
    }
  });
</script>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
?>