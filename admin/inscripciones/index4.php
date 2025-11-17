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
              <h5 class="section-title">Rol</h5>
              <div class="row form-row-spaced">
                <label for="rol" class="form-label-elegante required-field">Rol</label>
                <select name="rol" class="form-control form-control-elegante" required>
                  <option value="">Seleccione...</option>
                  <option value="REPRESENTANTE">Representante</option>
                  <option value="PROFESOR">Profesor</option>
                </select>
              </div>
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
                <div class="col-md-4">
                  <div class="from-group-elegant">
                    <label for="sexor" class="form-label-elegante required-field">Profesión</label>
                    <select name="sexor" class="form-control form-control-elegante" required>
                      <option value="">Seleccione...</option>
                      <option value="M">Licenciado/a</option>
                      <option value="F">Ingeniero/a</option>
                      <option value="F">Bachiller</option>
                      <option value="F">Ama de casa</option>
                      <option value="F">Obrero</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="ocupacionr" class="form-label-elegante required-field">Ocupación</label>
                    <input type="text" name="ocupacionr" class="form-control form-control-elegante" placeholder="Profesión u oficio" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="lugar_trabajor" class="form-label-elegante required-field">Lugar de Trabajo</label>
                    <input type="text" name="lugar_trabajor" class="form-control form-control-elegante" placeholder="Empresa o institución" required>
                  </div>
                </div>
              </div>

              <h5 class="section-title">Dirección del Representante</h5>
              <div class="row form-row-spaced">
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="estador" class="form-label-elegante required-field">Estado</label>
                    <input type="text" name="estador" class="form-control form-control-elegante" placeholder="Nombre del estado" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="municipior" class="form-label-elegante required-field">Municipio</label>
                    <input type="text" name="municipior" class="form-control form-control-elegante" placeholder="Nombre de la parroquía" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="parroquiar" class="form-label-elegante required-field">Parroquía</label>
                    <input type="text" name="parroquiar" class="form-control form-control-elegante" placeholder="Nombre de la parroquía" required>
                  </div>
                </div>
              </div>
              <div class="row form-row-spaced">
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="direccionr" class="form-label-elegante required-field">Direccion</label>
                    <input type="text" name="direccionr" class="form-control form-control-elegante" placeholder="Nombre de la calle" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group-elegante">
                    <label for="caller" class="form-label-elegante required-field">Calle/Avenida</label>
                    <input type="text" name="caller" class="form-control form-control-elegante" placeholder="Nombre de la calle" required>
                  </div>
                </div>
                <div class="col-md-4">
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

  async function validarCedula() {
    const representanteRegistrado = document.querySelector('input[name="representanteRegistrado"]:checked').value;

    if (representanteRegistrado === 'si') {
      const cedula = document.getElementById('cedulaValidacion').value;
      if (!cedula) {

        alert('Por favor ingrese la cédula del representante', cedula);
        return;
      }
      alert(typeof(cedula))


      let formData = new FormData();
      formData.append('cedula', cedula);
      try {
        let responseCiR = await fetch('/final/app/controllers/inscripciones/validar.php', {
          method: 'POST',
          body: formData
        });
        let representante = await responseCiR;
        console.log('respuesta cruda: ', representante);
        let data;
        try {
          data = JSON.parse(representante);
          console.log('Json valido ', data);
          location.reload();
        } catch (error) {
          console.error('Erro parsenado JSON: ', error);
          console.error('Respuesta no es JSON válido: ', representante.substring(0, 100));

        }
      } catch (error) {
        console.error('🔥 Error de conexión:', error);
        alert('Error de conexión: ' + error.message);
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