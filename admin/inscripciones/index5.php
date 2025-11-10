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
<link rel="stylesheet" href="<?= URL; ?>/admin/inscripciones/styles/style2.css">

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
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="primer_nombre_r" class="form-label-elegante required-field">Primer Nombre</label>
                    <input type="text" name="primer_nombre_r" class="form-control form-control-elegante" placeholder="Primer nombre" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="segundo_nombre_r" class="form-label-elegante">Segundo Nombre</label>
                    <input type="text" name="segundo_nombre_r" class="form-control form-control-elegante" placeholder="Segundo nombre">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="primer_apellido_r" class="form-label-elegante required-field">Primer Apellido</label>
                    <input type="text" name="primer_apellido_r" class="form-control form-control-elegante" placeholder="Primer apellido" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="segundo_apellido_r" class="form-label-elegante">Segundo Apellido</label>
                    <input type="text" name="segundo_apellido_r" class="form-control form-control-elegante" placeholder="Segundo apellido">
                  </div>
                </div>
              </div>

              <div class="row form-row-spaced">
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="cedula_r" class="form-label-elegante required-field">Cédula de Identidad</label>
                    <input type="number" name="cedula_r" class="form-control form-control-elegante" placeholder="Ej: 12345678" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="correo_r" class="form-label-elegante required-field">Correo Electrónico</label>
                    <input type="email" name="correo_r" class="form-control form-control-elegante" placeholder="ejemplo@correo.com" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="fecha_nac_r" class="form-label-elegante required-field">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nac_r" class="form-control form-control-elegante" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="lugar_nac_r" class="form-label-elegante required-field">Lugar de Nacimiento</label>
                    <input type="text" name="lugar_nac_r" class="form-control form-control-elegante" placeholder="Ciudad, Estado" required>
                  </div>
                </div>
              </div>

              <h5 class="section-title">Información de Contacto</h5>
              <div class="row form-row-spaced">
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="telefono_r" class="form-label-elegante required-field">Teléfono Personal</label>
                    <input type="text" name="telefono_r" class="form-control form-control-elegante" placeholder="Ej: 0412-1234567" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="telefono_hab_r" class="form-label-elegante required-field">Teléfono de Habitación</label>
                    <input type="text" name="telefono_hab_r" class="form-control form-control-elegante" placeholder="Ej: 0212-1234567" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="sexo_r" class="form-label-elegante required-field">Sexo</label>
                    <select name="sexo_r" class="form-control form-control-elegante" required>
                      <option value="">Seleccione...</option>
                      <option value="Masculino">Masculino</option>
                      <option value="Femenino">Femenino</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group-elegante">
                    <label for="nacionalidad_r" class="form-label-elegante required-field">Nacionalidad</label>
                    <input type="text" name="nacionalidad_r" class="form-control form-control-elegante" placeholder="Ej: Venezolana" required>
                  </div>
                </div>
              </div>

              <h5 class="section-title">Información Laboral</h5>
              <div class="row form-row-spaced">
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="profesion_r" class="form-label-elegante required-field">Profesión</label>
                    <select name="profesion_r" class="form-control form-control-elegante" required>
                      <option value="">Seleccione...</option>
                      <option value="Licenciado/a">Licenciado/a</option>
                      <option value="Ingeniero/a">Ingeniero/a</option>
                      <option value="Doctor/a">Doctor/a</option>
                      <option value="Bachiller">Bachiller</option>
                      <option value="Técnico">Técnico</option>
                      <option value="Ama de casa">Ama de casa</option>
                      <option value="Obrero">Obrero</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="ocupacion_r" class="form-label-elegante required-field">Ocupación</label>
                    <input type="text" name="ocupacion_r" class="form-control form-control-elegante" placeholder="Profesión u oficio" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="lugar_trabajo_r" class="form-label-elegante required-field">Lugar de Trabajo</label>
                    <input type="text" name="lugar_trabajo_r" class="form-control form-control-elegante" placeholder="Empresa o institución" required>
                  </div>
                </div>
              </div>

              <h5 class="section-title">Dirección del Representante</h5>
              <div class="row form-row-spaced">
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="estado_r" class="form-label-elegante required-field">Estado</label>
                    <select name="estado_r" class="form-control form-control-elegante" required onchange="cargarMunicipios(this.value, 'municipio_r')">
                      <option value="">Seleccione...</option>
                      <?php
                      // Aquí deberías cargar los estados desde tu base de datos
                      // Ejemplo estático:
                      ?>
                      <option value="1">Miranda</option>
                      <option value="2">Distrito Capital</option>
                      <option value="3">La Guaira</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="municipio_r" class="form-label-elegante required-field">Municipio</label>
                    <select name="municipio_r" class="form-control form-control-elegante" required onchange="cargarParroquias(this.value, 'parroquia_r')">
                      <option value="">Seleccione...</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="parroquia_r" class="form-label-elegante required-field">Parroquia</label>
                    <select name="parroquia_r" class="form-control form-control-elegante" required>
                      <option value="">Seleccione...</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row form-row-spaced">
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="direccion_r" class="form-label-elegante required-field">Dirección Completa</label>
                    <input type="text" name="direccion_r" class="form-control form-control-elegante" placeholder="Dirección completa" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="calle_r" class="form-label-elegante required-field">Calle/Avenida</label>
                    <input type="text" name="calle_r" class="form-control form-control-elegante" placeholder="Nombre de la calle" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="casa_r" class="form-label-elegante required-field">Casa/Edificio</label>
                    <input type="text" name="casa_r" class="form-control form-control-elegante" placeholder="Número o nombre" required>
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
              <div class="form-group-elegante mb-4">
                <label class="form-label-elegante required-field">Parentesco con el Representante</label>
                <select name="parentesco_global" class="form-control form-control-elegante" required>
                  <option value="">Seleccione...</option>
                  <option value="Madre">Madre</option>
                  <option value="Padre">Padre</option>
                  <option value="Abuelo/a">Abuelo/a</option>
                  <option value="Tío/a">Tío/a</option>
                  <option value="Hermano/a">Hermano/a</option>
                  <option value="Otro">Otro</option>
                </select>
              </div>

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

              <!-- Información de Inscripción -->
              <div class="row mt-4">
                <div class="col-md-6">
                  <div class="form-group-elegante">
                    <label for="periodo_inscripcion" class="form-label-elegante required-field">Periodo Escolar</label>
                    <select name="periodo_inscripcion" class="form-control form-control-elegante" required>
                      <option value="">Seleccione...</option>
                      <?php
                      // Aquí deberías cargar los periodos desde tu base de datos
                      ?>
                      <option value="1">Año Escolar 2024-2025</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group-elegante">
                    <label for="fecha_inscripcion" class="form-label-elegante required-field">Fecha de Inscripción</label>
                    <input type="date" name="fecha_inscripcion" class="form-control form-control-elegante" value="<?= date('Y-m-d'); ?>" required>
                  </div>
                </div>
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

  async function validarCedula() {
    const representanteRegistrado = document.querySelector('input[name="representanteRegistrado"]:checked').value;

    if (representanteRegistrado === 'si') {
      const cedula = document.getElementById('cedulaValidacion').value;
      if (!cedula) {
        alert('Por favor ingrese la cédula del representante');
        return;
      }

      try {
        let formData = new FormData();
        formData.append('cedula', cedula);

        let response = await fetch('/final/app/controllers/inscripciones/validar.php', {
          method: 'POST',
          body: formData
        });

        let data = await response.json();

        if (data.existe) {
          // Representante existe
          representanteExistente = true;
          document.getElementById('resultadoExistente').style.display = 'block';
          document.getElementById('resultadoExistente').className = 'validation-result validation-success';
          document.getElementById('resultadoNuevo').style.display = 'none';
          document.getElementById('infoRepresentanteExistente').textContent =
            `Cédula ${cedula} - ${data.nombre_completo}`;

          // Saltar al paso 3 (alumnos)
          showStep(3);
        } else {
          // Representante no existe
          representanteExistente = false;
          document.getElementById('resultadoNuevo').style.display = 'block';
          document.getElementById('resultadoNuevo').className = 'validation-result validation-warning';
          document.getElementById('resultadoExistente').style.display = 'none';

          // Llenar automáticamente la cédula en el paso 2
          document.querySelector('input[name="cedula_r"]').value = cedula;

          // Ir al paso 2 (registro representante)
          showStep(2);
        }
      } catch (error) {
        console.error('Error de conexión:', error);
        alert('Error de conexión: ' + error.message);
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
        
        <h6 class="section-title">Información Personal</h6>
        <div class="row form-row-spaced">
          <div class="col-md-3">
            <div class="form-group-elegante">
              <label for="primer_nombre_a${contadorAlumnos}" class="form-label-elegante required-field">Primer Nombre</label>
              <input type="text" name="primer_nombre_a[]" class="form-control form-control-elegante" placeholder="Primer nombre" required>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group-elegante">
              <label for="segundo_nombre_a${contadorAlumnos}" class="form-label-elegante">Segundo Nombre</label>
              <input type="text" name="segundo_nombre_a[]" class="form-control form-control-elegante" placeholder="Segundo nombre">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group-elegante">
              <label for="primer_apellido_a${contadorAlumnos}" class="form-label-elegante required-field">Primer Apellido</label>
              <input type="text" name="primer_apellido_a[]" class="form-control form-control-elegante" placeholder="Primer apellido" required>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group-elegante">
              <label for="segundo_apellido_a${contadorAlumnos}" class="form-label-elegante">Segundo Apellido</label>
              <input type="text" name="segundo_apellido_a[]" class="form-control form-control-elegante" placeholder="Segundo apellido">
            </div>
          </div>
        </div>

        <div class="row form-row-spaced">
          <div class="col-md-3">
            <div class="form-group-elegante">
              <label for="cedula_a${contadorAlumnos}" class="form-label-elegante required-field">Cédula</label>
              <input type="number" name="cedula_a[]" class="form-control form-control-elegante" placeholder="Cédula del alumno" required>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group-elegante">
              <label for="fecha_nac_a${contadorAlumnos}" class="form-label-elegante required-field">Fecha Nacimiento</label>
              <input type="date" name="fecha_nac_a[]" class="form-control form-control-elegante" required>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group-elegante">
              <label for="sexo_a${contadorAlumnos}" class="form-label-elegante required-field">Sexo</label>
              <select name="sexo_a[]" class="form-control form-control-elegante" required>
                <option value="">Seleccione...</option>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group-elegante">
              <label for="nacionalidad_a${contadorAlumnos}" class="form-label-elegante required-field">Nacionalidad</label>
              <input type="text" name="nacionalidad_a[]" class="form-control form-control-elegante" placeholder="Nacionalidad" required>
            </div>
          </div>
        </div>

        <div class="row form-row-spaced">
          <div class="col-md-4">
            <div class="form-group-elegante">
              <label for="lugar_nac_a${contadorAlumnos}" class="form-label-elegante required-field">Lugar de Nacimiento</label>
              <input type="text" name="lugar_nac_a[]" class="form-control form-control-elegante" placeholder="Ciudad, Estado" required>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group-elegante">
              <label for="telefono_a${contadorAlumnos}" class="form-label-elegante">Teléfono Personal</label>
              <input type="text" name="telefono_a[]" class="form-control form-control-elegante" placeholder="Opcional">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group-elegante">
              <label for="correo_a${contadorAlumnos}" class="form-label-elegante">Correo Electrónico</label>
              <input type="email" name="correo_a[]" class="form-control form-control-elegante" placeholder="Opcional">
            </div>
          </div>
        </div>

        <h6 class="section-title">Información Académica</h6>
        <div class="row form-row-spaced">
          <div class="col-md-6">
            <div class="form-group-elegante">
              <label for="nivel_a${contadorAlumnos}" class="form-label-elegante required-field">Nivel/Grado</label>
              <select name="nivel_a[]" class="form-control form-control-elegante" required>
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
          <div class="col-md-6">
            <div class="form-group-elegante">
              <label for="seccion_a${contadorAlumnos}" class="form-label-elegante required-field">Sección</label>
              <select name="seccion_a[]" class="form-control form-control-elegante" required>
                <option value="">Seleccione...</option>
                <option value="A">Sección A</option>
                <option value="B">Sección B</option>
                <option value="C">Sección C</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Información de Salud -->
        <h6 class="section-title">Información de Salud</h6>
        <div class="row form-row-spaced">
          <div class="col-md-12">
            <div class="form-group-elegante">
              <label for="patologias_a${contadorAlumnos}" class="form-label-elegante">Patologías/Alergias</label>
              <textarea name="patologias_a[]" class="form-control form-control-elegante" rows="2" placeholder="Indique cualquier condición médica, alergia o patología conocida (opcional)"></textarea>
            </div>
          </div>
        </div>
      </div>
    `;

    document.getElementById('contenedorAlumnos').innerHTML += alumnoHTML;
    document.getElementById('totalAlumnos').textContent = contadorAlumnos;
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

      // Actualizar el botón de eliminar
      const btnEliminar = alumno.querySelector('button');
      if (btnEliminar) {
        btnEliminar.setAttribute('onclick', `eliminarAlumno(${nuevoNumero})`);
      }
    });
  }

  // Funciones para cargar municipios y parroquias (debes implementar el backend)
  async function cargarMunicipios(estadoId, selectId) {
    if (!estadoId) return;

    try {
      let response = await fetch(`/final/app/controllers/ubicaciones/cargar_municipios.php?estado_id=${estadoId}`);
      let municipios = await response.json();

      const select = document.querySelector(`select[name="${selectId}"]`);
      select.innerHTML = '<option value="">Seleccione...</option>';

      municipios.forEach(municipio => {
        select.innerHTML += `<option value="${municipio.id}">${municipio.nombre}</option>`;
      });
    } catch (error) {
      console.error('Error cargando municipios:', error);
    }
  }

  async function cargarParroquias(municipioId, selectId) {
    if (!municipioId) return;

    try {
      let response = await fetch(`/final/app/controllers/ubicaciones/cargar_parroquias.php?municipio_id=${municipioId}`);
      let parroquias = await response.json();

      const select = document.querySelector(`select[name="${selectId}"]`);
      select.innerHTML = '<option value="">Seleccione...</option>';

      parroquias.forEach(parroquia => {
        select.innerHTML += `<option value="${parroquia.id}">${parroquia.nombre}</option>`;
      });
    } catch (error) {
      console.error('Error cargando parroquias:', error);
    }
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