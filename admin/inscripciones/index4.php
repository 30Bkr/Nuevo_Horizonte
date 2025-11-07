<?php
include_once("/xampp/htdocs/final/layout/layaout1.php");
include_once("/xampp/htdocs/final/app/persona.php");
include_once("/xampp/htdocs/final/app/controllers/roles/roles.php");
include_once("/xampp/htdocs/final/app/controllers/cursos/cursos.php");

$cursos = new Cursos();
$listaGrados = $cursos->mostrarGrados();
$listaAnos = $cursos->mostrarAños();
$roles = new Roles();
$listarRoles = $roles->listar();
$docente = new Persona();
?>
<style>
  .card-elegante {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 25px;
    transition: all 0.3s ease;
  }

  .card-elegante:hover {
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12);
  }

  .card-header-elegante {
    background: linear-gradient(135deg, #2e7d32, #4caf50);
    color: white;
    border-bottom: none;
    padding: 20px 25px;
    border-radius: 12px 12px 0 0 !important;
  }

  .card-header-elegante h3 {
    margin: 0;
    font-weight: 600;
    font-size: 1.3rem;
  }

  .card-body-elegante {
    padding: 30px;
    background: #fafafa;
  }

  .form-group-elegante {
    margin-bottom: 20px;
  }

  .form-label-elegante {
    font-weight: 600;
    color: #37474f;
    margin-bottom: 8px;
    font-size: 0.9rem;
    display: block;
  }

  .form-control-elegante {
    border: 1.5px solid #e0e0e0;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: white;
  }

  .form-control-elegante:focus {
    border-color: #2e7d32;
    box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.1);
  }

  .btn-elegante {
    border-radius: 8px;
    padding: 12px 30px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    font-size: 0.95rem;
  }

  .btn-primary-elegante {
    background: linear-gradient(135deg, #2e7d32, #4caf50);
    color: white;
  }

  .btn-primary-elegante:hover {
    background: linear-gradient(135deg, #1b5e20, #2e7d32);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
  }

  .btn-secondary-elegante {
    background: #6c757d;
    color: white;
  }

  .btn-secondary-elegante:hover {
    background: #5a6268;
    transform: translateY(-1px);
  }

  .btn-outline-elegante {
    background: transparent;
    border: 2px solid #2e7d32;
    color: #2e7d32;
  }

  .btn-outline-elegante:hover {
    background: #2e7d32;
    color: white;
  }

  .btn-info-elegante {
    background: linear-gradient(135deg, #0288d1, #29b6f6);
    color: white;
  }

  .btn-info-elegante:hover {
    background: linear-gradient(135deg, #0277bd, #0288d1);
    transform: translateY(-1px);
  }

  .required-field::after {
    content: " *";
    color: #d32f2f;
  }

  /* Estilos para el modo secuencial horizontal */
  .step-container {
    display: none;
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.4s ease;
  }

  .step-container.active {
    display: block;
    opacity: 1;
    transform: translateX(0);
  }

  .step-indicator {
    display: flex;
    justify-content: center;
    margin-bottom: 40px;
    position: relative;
  }

  .step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    margin: 0 25px;
    cursor: pointer;
  }

  .step-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
    border: 2px solid #e0e0e0;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  }

  .step.active .step-circle {
    background: #2e7d32;
    border-color: #2e7d32;
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
  }

  .step.completed .step-circle {
    background: #4caf50;
    border-color: #4caf50;
  }

  .step-number {
    color: #9e9e9e;
    font-weight: 600;
    font-size: 1.1rem;
  }

  .step.active .step-number,
  .step.completed .step-number {
    color: white;
  }

  .step-text {
    font-weight: 500;
    color: #9e9e9e;
    text-align: center;
    font-size: 0.85rem;
    transition: all 0.3s ease;
  }

  .step.active .step-text {
    color: #2e7d32;
    font-weight: 600;
  }

  .step-line {
    position: absolute;
    top: 25px;
    height: 2px;
    background: #e0e0e0;
    z-index: 1;
    transition: all 0.4s ease;
  }

  .step-line.progress {
    background: linear-gradient(90deg, #4caf50, #2e7d32);
  }

  .navigation-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 40px;
    padding: 25px 0;
    border-top: 1px solid #e8f5e9;
  }

  .form-row-spaced {
    margin-bottom: 15px;
  }

  .section-title {
    color: #2e7d32;
    font-weight: 600;
    margin-bottom: 25px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e8f5e9;
  }

  .alumno-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    border-left: 4px solid #0288d1;
    transition: all 0.3s ease;
  }

  .alumno-section:hover {
    background: #e3f2fd;
  }

  .alumno-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e0e0e0;
  }

  .validation-result {
    padding: 15px;
    border-radius: 8px;
    margin: 15px 0;
    display: none;
  }

  .validation-success {
    background: #e8f5e9;
    border: 1px solid #4caf50;
    color: #2e7d32;
  }

  .validation-warning {
    background: #fff3e0;
    border: 1px solid #ff9800;
    color: #ef6c00;
  }

  .floating-stats {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 1000;
  }

  .stat-card {
    background: white;
    border-radius: 10px;
    padding: 12px 16px;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
    margin-bottom: 8px;
    text-align: center;
    border: 1px solid #e0e0e0;
  }

  .stat-number {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2e7d32;
  }

  .stat-label {
    font-size: 0.75rem;
    color: #757575;
    font-weight: 500;
  }

  .direccion-alumno {
    display: none;
    background: #f1f8e9;
    border-radius: 8px;
    padding: 15px;
    margin-top: 15px;
    border: 1px solid #c5e1a5;
  }

  .direccion-alumno.active {
    display: block;
  }

  .radio-group {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
  }

  .radio-option {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
  }

  .radio-option input[type="radio"] {
    margin: 0;
  }
</style>

<div class="content-wrapper">
  <div class="content">
    <br>
    <div class="container-fluid">
      <div class="row mb-4">
        <div class="col-md-12">
          <h1 class="h2 font-weight-bold text-dark">Inscripción de Alumnos</h1>
          <p class="text-muted">Complete el proceso de inscripción paso a paso</p>
        </div>
      </div>

      <!-- Estadísticas Flotantes -->
      <div class="floating-stats">
        <div class="stat-card">
          <div class="stat-number" id="totalAlumnos">0</div>
          <div class="stat-label">Alumnos</div>
        </div>
        <div class="stat-card">
          <div class="stat-number" id="pasoActual">1</div>
          <div class="stat-label">Paso Actual</div>
        </div>
      </div>

      <!-- Indicador de Pasos -->
      <div class="step-indicator">
        <div class="step active" data-step="1">
          <div class="step-circle">
            <span class="step-number">1</span>
          </div>
          <div class="step-text">Validación<br>Representante</div>
        </div>
        <div class="step-line" id="line1-2" style="width: calc(25% - 100px); left: 25%;"></div>

        <div class="step" data-step="2">
          <div class="step-circle">
            <span class="step-number">2</span>
          </div>
          <div class="step-text">Datos del<br>Representante</div>
        </div>
        <div class="step-line" id="line2-3" style="width: calc(25% - 100px); left: 50%;"></div>

        <div class="step" data-step="3">
          <div class="step-circle">
            <span class="step-number">3</span>
          </div>
          <div class="step-text">Datos del<br>Alumno</div>
        </div>
        <div class="step-line" id="line3-4" style="width: calc(25% - 100px); left: 75%;"></div>

        <div class="step" data-step="4">
          <div class="step-circle">
            <span class="step-number">4</span>
          </div>
          <div class="step-text">Confirmación</div>
        </div>
      </div>

      <form action="http://localhost/final/app/controllers/inscripciones/inscripciong.php" method="post" id="for">

        <!-- Paso 1: Validación de Representante -->
        <div class="step-container active" id="step1">
          <div class="card card-elegante">
            <div class="card-header-elegante">
              <h3><i class="fas fa-search mr-2"></i>Paso 1: Validación del Representante</h3>
            </div>
            <div class="card-body-elegante">
              <div class="row justify-content-center">
                <div class="col-md-8">
                  <div class="form-group-elegante text-center">
                    <label class="form-label-elegante">
                      <i class="fas fa-question-circle mr-2"></i>¿El representante está registrado en el sistema?
                    </label>
                    <div class="radio-group justify-content-center">
                      <div class="radio-option">
                        <input type="radio" id="repRegistradoSi" name="representanteRegistrado" value="si" checked>
                        <label for="repRegistradoSi">Sí, está registrado</label>
                      </div>
                      <div class="radio-option">
                        <input type="radio" id="repRegistradoNo" name="representanteRegistrado" value="no">
                        <label for="repRegistradoNo">No, es nuevo</label>
                      </div>
                    </div>
                  </div>

                  <div id="cedulaContainer">
                    <div class="form-group-elegante text-center">
                      <label for="cedulaValidacion" class="form-label-elegante required-field">
                        <i class="fas fa-id-card mr-2"></i>Ingrese la Cédula del Representante
                      </label>
                      <input type="number" id="cedulaValidacion" class="form-control form-control-elegante text-center"
                        placeholder="Ej: 12345678" style="font-size: 1.2rem;">
                      <small class="text-muted">Ingrese el número de cédula para verificar si el representante ya está registrado</small>
                    </div>
                  </div>

                  <div class="validation-result" id="resultadoExistente">
                    <i class="fas fa-check-circle mr-2"></i>
                    <strong>Representante encontrado:</strong>
                    <span id="infoRepresentanteExistente"></span>
                  </div>

                  <div class="validation-result" id="resultadoNuevo">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Representante no registrado:</strong>
                    Proceda a registrar la información del representante.
                  </div>

                  <div class="text-center mt-4">
                    <button type="button" class="btn btn-primary-elegante btn-elegante" onclick="validarCedula()" id="btnValidar">
                      <i class="fas fa-search mr-2"></i>Validar y Continuar
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Paso 2: Datos del Representante -->
        <div class="step-container" id="step2">
          <div class="card card-elegante">
            <div class="card-header-elegante">
              <h3><i class="fas fa-user-tie mr-2"></i>Paso 2: Datos del Representante</h3>
            </div>
            <div class="card-body-elegante">
              <h5 class="section-title">Información Personal</h5>
              <div class="row form-row-spaced">
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="nombresr" class="form-label-elegante required-field">Nombres</label>
                    <input type="text" name="nombresr" class="form-control form-control-elegante" placeholder="Ingrese los nombres" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="apellidosr" class="form-label-elegante required-field">Apellidos</label>
                    <input type="text" name="apellidosr" class="form-control form-control-elegante" placeholder="Ingrese los apellidos" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="cedular" class="form-label-elegante required-field">Cédula de Identidad</label>
                    <input type="number" name="cedular" class="form-control form-control-elegante" placeholder="Ej: 12345678" required>
                  </div>
                </div>
              </div>

              <div class="row form-row-spaced">
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="correor" class="form-label-elegante required-field">Correo Electrónico</label>
                    <input type="email" name="correor" class="form-control form-control-elegante" placeholder="ejemplo@correo.com" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="fecha_nacr" class="form-label-elegante required-field">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacr" class="form-control form-control-elegante" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="lugar_nacr" class="form-label-elegante required-field">Lugar de Nacimiento</label>
                    <input type="text" name="lugar_nacr" class="form-control form-control-elegante" placeholder="Ciudad, Estado" required>
                  </div>
                </div>
              </div>

              <h5 class="section-title">Información de Contacto</h5>
              <div class="row form-row-spaced">
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="telefonor" class="form-label-elegante required-field">Teléfono Personal</label>
                    <input type="text" name="telefonor" class="form-control form-control-elegante" placeholder="Ej: 0412-1234567" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="telefono_habr" class="form-label-elegante required-field">Teléfono de Habitación</label>
                    <input type="text" name="telefono_habr" class="form-control form-control-elegante" placeholder="Ej: 0212-1234567" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="sexor" class="form-label-elegante required-field">Sexo</label>
                    <select name="sexor" class="form-control form-control-elegante" required>
                      <option value="">Seleccione...</option>
                      <option value="M">Masculino</option>
                      <option value="F">Femenino</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="nacionalidadr" class="form-label-elegante required-field">Nacionalidad</label>
                    <input type="text" name="nacionalidadr" class="form-control form-control-elegante" placeholder="Ej: Venezolana" required>
                  </div>
                </div>
              </div>

              <h5 class="section-title">Información Laboral</h5>
              <div class="row form-row-spaced">
                <div class="col-md-6">
                  <div class="form-group-elegante">
                    <label for="ocupacionr" class="form-label-elegante required-field">Ocupación</label>
                    <input type="text" name="ocupacionr" class="form-control form-control-elegante" placeholder="Profesión u oficio" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group-elegante">
                    <label for="lugar_trabajor" class="form-label-elegante required-field">Lugar de Trabajo</label>
                    <input type="text" name="lugar_trabajor" class="form-control form-control-elegante" placeholder="Empresa o institución" required>
                  </div>
                </div>
              </div>

              <h5 class="section-title">Dirección del Representante</h5>
              <div class="row form-row-spaced">
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="estador" class="form-label-elegante required-field">Estado</label>
                    <input type="text" name="estador" class="form-control form-control-elegante" placeholder="Nombre del estado" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="parroquiar" class="form-label-elegante required-field">Parroquía</label>
                    <input type="text" name="parroquiar" class="form-control form-control-elegante" placeholder="Nombre de la parroquía" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="caller" class="form-label-elegante required-field">Calle/Avenida</label>
                    <input type="text" name="caller" class="form-control form-control-elegante" placeholder="Nombre de la calle" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="casar" class="form-label-elegante required-field">Casa/Edificio</label>
                    <input type="text" name="casar" class="form-control form-control-elegante" placeholder="Número o nombre" required>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Paso 3: Datos del Alumno -->
        <div class="step-container" id="step3">
          <div class="card card-elegante">
            <div class="card-header-elegante">
              <h3><i class="fas fa-user-graduate mr-2"></i>Paso 3: Datos del Alumno/Hijo</h3>
            </div>
            <div class="card-body-elegante">
              <div id="contenedorAlumnos">
                <!-- Los alumnos se agregarán aquí dinámicamente -->
              </div>

              <div class="text-center mt-4">
                <button type="button" class="btn btn-info-elegante btn-elegante" onclick="agregarAlumno()">
                  <i class="fas fa-plus-circle mr-2"></i>Agregar Otro Alumno/Hijo
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Paso 4: Confirmación -->
        <div class="step-container" id="step4">
          <div class="card card-elegante">
            <div class="card-header-elegante">
              <h3><i class="fas fa-check-circle mr-2"></i>Paso 4: Confirmación</h3>
            </div>
            <div class="card-body-elegante text-center">
              <i class="fas fa-clipboard-check fa-3x text-success mb-3"></i>
              <h4 class="text-success">¡Revisión Completa!</h4>
              <p class="lead">Se han registrado los datos de <strong id="totalAlumnosConfirmacion">0</strong> alumno(s)</p>
              <p class="text-muted">Verifique que toda la información sea correcta antes de proceder con el registro.</p>

              <div class="alert alert-info mt-4">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Información importante:</strong> Al hacer clic en "Confirmar Registro",
                se guardarán todos los datos del representante y los alumnos inscritos.
              </div>
            </div>
          </div>
        </div>

        <!-- Botones de Navegación -->
        <div class="navigation-buttons">
          <button type="button" class="btn btn-outline-elegante btn-elegante" id="btnPrev" onclick="prevStep()" style="display: none;">
            <i class="fas fa-arrow-left mr-2"></i>Anterior
          </button>
          <div class="ml-auto">
            <button type="button" class="btn btn-primary-elegante btn-elegante" id="btnNext" onclick="nextStep()">
              Siguiente<i class="fas fa-arrow-right ml-2"></i>
            </button>
            <button type="submit" class="btn btn-success btn-elegante" id="btnSubmit" style="display: none;">
              <i class="fas fa-save mr-2"></i>Confirmar Registro
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  let currentStep = 1;
  const totalSteps = 4;
  let contadorAlumnos = 0;
  let representanteExistente = false;

  // Mostrar/ocultar campo de cédula según selección
  document.getElementById('repRegistradoSi').addEventListener('change', function() {
    document.getElementById('cedulaContainer').style.display = 'block';
    document.getElementById('btnValidar').innerHTML = '<i class="fas fa-search mr-2"></i>Validar y Continuar';
  });

  document.getElementById('repRegistradoNo').addEventListener('change', function() {
    document.getElementById('cedulaContainer').style.display = 'none';
    document.getElementById('btnValidar').innerHTML = '<i class="fas fa-arrow-right mr-2"></i>Continuar con Registro';
    document.getElementById('resultadoExistente').style.display = 'none';
    document.getElementById('resultadoNuevo').style.display = 'none';
  });

  function showStep(step) {
    // Ocultar todos los pasos
    document.querySelectorAll('.step-container').forEach(container => {
      container.classList.remove('active');
    });

    // Actualizar indicadores de pasos
    document.querySelectorAll('.step').forEach((stepElement, index) => {
      const stepNumber = index + 1;
      if (stepNumber < step) {
        stepElement.classList.add('completed');
        stepElement.classList.remove('active');
      } else if (stepNumber === step) {
        stepElement.classList.add('active');
        stepElement.classList.remove('completed');
      } else {
        stepElement.classList.remove('active', 'completed');
      }
    });

    // Actualizar líneas de progreso
    document.querySelectorAll('.step-line').forEach((line, index) => {
      if (index + 1 < step) {
        line.classList.add('progress');
      } else {
        line.classList.remove('progress');
      }
    });

    // Mostrar paso actual
    setTimeout(() => {
      document.getElementById(`step${step}`).classList.add('active');
    }, 200);

    // Actualizar botones de navegación
    document.getElementById('btnPrev').style.display = step > 1 ? 'block' : 'none';
    document.getElementById('btnNext').style.display = step < totalSteps ? 'block' : 'none';
    document.getElementById('btnSubmit').style.display = step === totalSteps ? 'block' : 'none';

    // Actualizar estadísticas
    document.getElementById('pasoActual').textContent = step;

    // Si estamos en el paso 4, actualizar la confirmación
    if (step === 4) {
      document.getElementById('totalAlumnosConfirmacion').textContent = contadorAlumnos;
    }

    currentStep = step;
  }

  function nextStep() {
    if (currentStep < totalSteps) {
      if (validateStep(currentStep)) {
        showStep(currentStep + 1);
      }
    }
  }

  function prevStep() {
    if (currentStep > 1) {
      showStep(currentStep - 1);
    }
  }

  function validateStep(step) {
    let isValid = true;

    switch (step) {
      case 1:
        const representanteRegistrado = document.querySelector('input[name="representanteRegistrado"]:checked').value;
        if (representanteRegistrado === 'si') {
          const cedula = document.getElementById('cedulaValidacion').value;
          if (!cedula) {
            alert('Por favor ingrese la cédula del representante');
            isValid = false;
          }
        }
        break;
      case 2:
        const requiredFields = document.querySelectorAll('#step2 [required]');
        requiredFields.forEach(field => {
          if (!field.value.trim()) {
            isValid = false;
            field.style.borderColor = '#d32f2f';
          } else {
            field.style.borderColor = '';
          }
        });
        if (!isValid) {
          alert('Por favor complete todos los campos obligatorios del representante');
        }
        break;
      case 3:
        if (contadorAlumnos === 0) {
          alert('Debe agregar al menos un alumno/hijo');
          isValid = false;
        }
        break;
    }

    return isValid;
  }

  function validarCedula() {
    const representanteRegistrado = document.querySelector('input[name="representanteRegistrado"]:checked').value;

    if (representanteRegistrado === 'si') {
      const cedula = document.getElementById('cedulaValidacion').value;
      if (!cedula) {
        alert('Por favor ingrese la cédula del representante');
        return;
      }

      // Simular validación con el backend
      const cedulaExistente = Math.random() > 0.5; // Simulación aleatoria

      if (cedulaExistente) {
        // Representante existe
        representanteExistente = true;
        document.getElementById('resultadoExistente').style.display = 'block';
        document.getElementById('resultadoExistente').className = 'validation-result validation-success';
        document.getElementById('resultadoNuevo').style.display = 'none';
        document.getElementById('infoRepresentanteExistente').textContent =
          `Cédula ${cedula} - Juan Pérez (Ya registrado en el sistema)`;

        // Saltar al paso 3 (alumnos)
        showStep(3);
      } else {
        // Representante no existe
        representanteExistente = false;
        document.getElementById('resultadoNuevo').style.display = 'block';
        document.getElementById('resultadoNuevo').className = 'validation-result validation-warning';
        document.getElementById('resultadoExistente').style.display = 'none';

        // Llenar automáticamente la cédula en el paso 2
        document.querySelector('input[name="cedular"]').value = cedula;

        // Ir al paso 2 (registro representante)
        showStep(2);
      }
    } else {
      // Representante nuevo, ir directamente al paso 2
      representanteExistente = false;
      showStep(2);
    }
  }

  function agregarAlumno() {
    contadorAlumnos++;
    const alumnoHTML = `
            <div class="alumno-section" id="alumno${contadorAlumnos}">
                <div class="alumno-header">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-child mr-2"></i>Alumno/Hijo ${contadorAlumnos}
                    </h5>
                    ${contadorAlumnos > 1 ? `
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarAlumno(${contadorAlumnos})">
                        <i class="fas fa-times"></i>
                    </button>
                    ` : ''}
                </div>
                <div class="row form-row-spaced">
                    <div class="col-md-4">
                        <div class="form-group-elegante">
                            <label for="nombresAlumno${contadorAlumnos}" class="form-label-elegante required-field">Nombres</label>
                            <input type="text" name="nombresAlumno[]" class="form-control form-control-elegante" placeholder="Nombres del alumno" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group-elegante">
                            <label for="apellidosAlumno${contadorAlumnos}" class="form-label-elegante required-field">Apellidos</label>
                            <input type="text" name="apellidosAlumno[]" class="form-control form-control-elegante" placeholder="Apellidos del alumno" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group-elegante">
                            <label for="cedulaAlumno${contadorAlumnos}" class="form-label-elegante required-field">Cédula</label>
                            <input type="number" name="cedulaAlumno[]" class="form-control form-control-elegante" placeholder="Cédula del alumno" required>
                        </div>
                    </div>
                </div>
                <div class="row form-row-spaced">
                    <div class="col-md-3">
                        <div class="form-group-elegante">
                            <label for="fechaNacimientoAlumno${contadorAlumnos}" class="form-label-elegante required-field">Fecha Nacimiento</label>
                            <input type="date" name="fechaNacimientoAlumno[]" class="form-control form-control-elegante" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group-elegante">
                            <label for="sexoAlumno${contadorAlumnos}" class="form-label-elegante required-field">Sexo</label>
                            <select name="sexoAlumno[]" class="form-control form-control-elegante" required>
                                <option value="">Seleccione...</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group-elegante">
                            <label for="nacionalidadAlumno${contadorAlumnos}" class="form-label-elegante required-field">Nacionalidad</label>
                            <input type="text" name="nacionalidadAlumno[]" class="form-control form-control-elegante" placeholder="Nacionalidad" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group-elegante">
                            <label for="gradoAlumno${contadorAlumnos}" class="form-label-elegante required-field">Grado</label>
                            <select name="gradoAlumno[]" class="form-control form-control-elegante" required>
                                <option value="">Seleccione...</option>
                                <option value="1">1er Grado</option>
                                <option value="2">2do Grado</option>
                                <option value="3">3er Grado</option>
                                <option value="4">4to Grado</option>
                                <option value="5">5to Grado</option>
                                <option value="6">6to Grado</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Sección de dirección del alumno -->
                <div class="form-group-elegante">
                    <label class="form-label-elegante required-field">¿El alumno vive en la misma casa del representante?</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="mismaCasaSi${contadorAlumnos}" name="mismaCasa${contadorAlumnos}" value="si" checked onchange="toggleDireccionAlumno(${contadorAlumnos})">
                            <label for="mismaCasaSi${contadorAlumnos}">Sí, vive en la misma dirección</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="mismaCasaNo${contadorAlumnos}" name="mismaCasa${contadorAlumnos}" value="no" onchange="toggleDireccionAlumno(${contadorAlumnos})">
                            <label for="mismaCasaNo${contadorAlumnos}">No, tiene dirección diferente</label>
                        </div>
                    </div>
                </div>

                <div class="direccion-alumno" id="direccionAlumno${contadorAlumnos}">
                    <h6 class="section-title">Dirección del Alumno</h6>
                    <div class="row form-row-spaced">
                        <div class="col-md-3">
                            <div class="form-group-elegante">
                                <label for="estadoAlumno${contadorAlumnos}" class="form-label-elegante">Estado</label>
                                <input type="text" name="estadoAlumno[]" class="form-control form-control-elegante" placeholder="Nombre del estado">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-elegante">
                                <label for="parroquiaAlumno${contadorAlumnos}" class="form-label-elegante">Parroquía</label>
                                <input type="text" name="parroquiaAlumno[]" class="form-control form-control-elegante" placeholder="Nombre de la parroquía">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-elegante">
                                <label for="calleAlumno${contadorAlumnos}" class="form-label-elegante">Calle/Avenida</label>
                                <input type="text" name="calleAlumno[]" class="form-control form-control-elegante" placeholder="Nombre de la calle">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group-elegante">
                                <label for="casaAlumno${contadorAlumnos}" class="form-label-elegante">Casa/Edificio</label>
                                <input type="text" name="casaAlumno[]" class="form-control form-control-elegante" placeholder="Número o nombre">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

    document.getElementById('contenedorAlumnos').innerHTML += alumnoHTML;
    document.getElementById('totalAlumnos').textContent = contadorAlumnos;
  }

  function toggleDireccionAlumno(numeroAlumno) {
    const mismaCasa = document.querySelector(`input[name="mismaCasa${numeroAlumno}"]:checked`).value;
    const direccionDiv = document.getElementById(`direccionAlumno${numeroAlumno}`);

    if (mismaCasa === 'no') {
      direccionDiv.classList.add('active');
      // Hacer obligatorios los campos de dirección
      const camposDireccion = direccionDiv.querySelectorAll('input');
      camposDireccion.forEach(campo => {
        campo.required = true;
      });
    } else {
      direccionDiv.classList.remove('active');
      // Quitar obligatoriedad de los campos de dirección
      const camposDireccion = direccionDiv.querySelectorAll('input');
      camposDireccion.forEach(campo => {
        campo.required = false;
      });
    }
  }

  function eliminarAlumno(numero) {
    if (confirm('¿Está seguro de eliminar este alumno?')) {
      document.getElementById(`alumno${numero}`).remove();
      contadorAlumnos--;
      document.getElementById('totalAlumnos').textContent = contadorAlumnos;
      reordenarAlumnos();
    }
  }

  function reordenarAlumnos() {
    const alumnos = document.querySelectorAll('.alumno-section');
    alumnos.forEach((alumno, index) => {
      const nuevoNumero = index + 1;
      const header = alumno.querySelector('h5');
      header.innerHTML = `<i class="fas fa-child mr-2"></i>Alumno/Hijo ${nuevoNumero}`;
      alumno.id = `alumno${nuevoNumero}`;

      // Actualizar también los radio buttons y direcciones
      const radios = alumno.querySelectorAll('input[type="radio"]');
      radios.forEach(radio => {
        const oldName = radio.getAttribute('name');
        const newName = oldName.replace(/\d+$/, nuevoNumero);
        radio.setAttribute('name', newName);
        radio.setAttribute('id', radio.getAttribute('id').replace(/\d+$/, nuevoNumero));
        radio.setAttribute('onchange', `toggleDireccionAlumno(${nuevoNumero})`);
      });

      const labels = alumno.querySelectorAll('label');
      labels.forEach(label => {
        const oldFor = label.getAttribute('for');
        if (oldFor) {
          label.setAttribute('for', oldFor.replace(/\d+$/, nuevoNumero));
        }
      });

      const direccionDiv = alumno.querySelector('.direccion-alumno');
      if (direccionDiv) {
        direccionDiv.setAttribute('id', `direccionAlumno${nuevoNumero}`);
      }
    });
  }

  // Inicializar
  document.addEventListener('DOMContentLoaded', function() {
    showStep(1);

    // Permitir navegación haciendo clic en los pasos
    document.querySelectorAll('.step').forEach(step => {
      step.addEventListener('click', function() {
        const stepNumber = parseInt(this.getAttribute('data-step'));
        if (stepNumber < currentStep) {
          showStep(stepNumber);
        }
      });
    });

    // Agregar primer alumno automáticamente cuando se llega al paso 3
    const observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
          if (document.getElementById('step3').classList.contains('active') && contadorAlumnos === 0) {
            agregarAlumno();
          }
        }
      });
    });

    observer.observe(document.getElementById('step3'), {
      attributes: true,
      attributeFilter: ['class']
    });
  });
</script>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>