<?php
include_once("/xampp/htdocs/final/layout/layaout1.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir los controladores necesarios
include_once("/xampp/htdocs/final/app/controllers/personas/personas.php");
include_once("/xampp/htdocs/final/app/controllers/estudiantes/estudiantes.php");
include_once("/xampp/htdocs/final/app/controllers/representantes/representantes.php");
include_once("/xampp/htdocs/final/app/controllers/ubicaciones/ubicaciones.php");
include_once("/xampp/htdocs/final/app/controllers/inscripciones/inscripciones.php");
include_once("/xampp/htdocs/final/app/conexion.php");

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  $profesionesController = new RepresentanteController($pdo);
  $profesiones = $profesionesController->obtenerProfesiones();
  $ubicacionController = new UbicacionController($pdo);
  $estados = $ubicacionController->obtenerEstados();
  $inscripcionesController = new InscripcionController($pdo);
  $periodos = $inscripcionesController->obtenerPeriodosActivos();
} catch (PDOException $e) {
  die("Error de conexión: " . $e->getMessage());
}
?>
<style>
  .estudiante-card {
    transition: all 0.3s ease;
    cursor: pointer;
  }

  .estudiante-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .estudiante-card.selected {
    border: 3px solid #007bff !important;
    background-color: #f8f9fa !important;
    transform: scale(1.02);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
  }

  .btn-seleccionar-estudiante {
    transition: all 0.3s ease;
  }

  .btn-seleccionar-estudiante:hover {
    transform: scale(1.05);
  }
</style>

<style>
  .alert-success {
    border-left: 4px solid #28a745;
  }

  .alert-danger {
    border-left: 4px solid #dc3545;
  }

  .alert-info {
    border-left: 4px solid #17a2b8;
  }

  .btn:disabled {
    cursor: not-allowed;
    opacity: 0.6;
  }

  /* Animación para las cards de estudiantes */
  .estudiante-card {
    transition: all 0.3s ease;
  }

  .estudiante-card.selected {
    transform: scale(1.02);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
  }
</style>
<style>
  .step {
    display: none;
  }

  .step.active {
    display: block;
  }

  .nav-pills .nav-link.active {
    background-color: #007bff;
    color: white;
  }

  .nav-pills .nav-link.disabled {
    color: #6c757d;
    pointer-events: none;
  }

  .btn-step {
    margin: 0 5px;
  }

  .estudiante-card {
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .estudiante-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }

  .estudiante-card.selected {
    border: 3px solid #007bff;
    background-color: #f8f9fa;
  }

  .estudiante-info {
    font-size: 0.9rem;
  }
</style>

<div class="content-wrapper">
  <div class="content">
    <br>
    <div class="container">
      <div class="row">
        <h1>Reinscripción de Estudiante</h1>
      </div>
      <br>

      <!-- Indicador de Pasos -->
      <div class="row mb-4">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <ul class="nav nav-pills nav-justified" id="stepIndicator">
                <li class="nav-item">
                  <a class="nav-link active" id="step1-tab" href="javascript:void(0)">
                    <strong>Paso 1:</strong> Validar Representante
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link disabled" id="step2-tab" href="javascript:void(0)">
                    <strong>Paso 2:</strong> Seleccionar Estudiante
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link disabled" id="step3-tab" href="javascript:void(0)">
                    <strong>Paso 3:</strong> Datos de Reinscripción
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <form id="form-reinscripcion">
        <!-- PASO 1: VALIDAR REPRESENTANTE -->
        <div class="step active" id="step1">
          <div class="row">
            <div class="col-md-12">
              <div class="card card-outline card-primary">
                <div class="card-header">
                  <h3 class="card-title"><b>Paso 1: Validar Representante</b></h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="cedula_representante">Cédula del Representante</label>
                        <input type="number" id="cedula_representante" class="form-control" placeholder="Ingrese la cédula del representante">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group" style="margin-top: 32px;">
                        <button type="button" id="btn-validar-representante" class="btn btn-primary">Validar Representante</button>
                      </div>
                    </div>
                  </div>
                  <div id="resultado-validacion" class="mt-3"></div>

                  <!-- Botones de navegación -->
                  <div class="row mt-4">
                    <div class="col-md-12 text-right">
                      <button type="button" class="btn btn-primary btn-step" id="btn-next-to-step2" style="display: none;">
                        Siguiente <i class="fas fa-arrow-right"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- PASO 2: SELECCIONAR ESTUDIANTE -->
        <div class="step" id="step2">
          <div class="row">
            <div class="col-md-12">
              <div class="card card-outline card-warning">
                <div class="card-header">
                  <h3 class="card-title"><b>Paso 2: Seleccionar Estudiante</b></h3>
                </div>
                <div class="card-body">
                  <input type="hidden" name="representante_existente" id="representante_existente" value="1">
                  <input type="hidden" name="id_representante_existente" id="id_representante_existente" value="">
                  <input type="hidden" name="id_direccion_repre" id="id_direccion_repre" value="">

                  <div id="info-representante" class="alert alert-info mb-4" style="display: none;">
                    <h5><i class="fas fa-user"></i> Información del Representante</h5>
                    <div id="datos-representante"></div>
                  </div>

                  <div id="lista-estudiantes" class="row">
                    <!-- Aquí se cargarán las tarjetas de estudiantes -->
                  </div>

                  <!-- Botones de navegación -->
                  <div class="row mt-4">
                    <div class="col-md-12 text-right">
                      <button type="button" class="btn btn-secondary btn-step" id="btn-back-to-step1">
                        <i class="fas fa-arrow-left"></i> Anterior
                      </button>
                      <button type="button" class="btn btn-primary btn-step" id="btn-next-to-step3" style="display: none;">
                        Siguiente <i class="fas fa-arrow-right"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- PASO 3: DATOS DE REINSCRIPCIÓN -->
        <div class="step" id="step3">
          <div class="row">
            <div class="col-md-12">
              <div class="card card-outline card-success">
                <div class="card-header">
                  <h3 class="card-title"><b>Paso 3: Datos de Reinscripción</b></h3>
                </div>
                <div class="card-body">
                  <input type="hidden" name="estudiante_existente" id="estudiante_existente" value="1">
                  <input type="hidden" name="id_estudiante_existente" id="id_estudiante_existente" value="">
                  <input type="hidden" name="id_direccion_est" id="id_direccion_est" value="">

                  <!-- Información del estudiante seleccionado -->
                  <div id="info-estudiante-seleccionado" class="alert alert-success mb-4">
                    <h5><i class="fas fa-user-graduate"></i> Estudiante Seleccionado</h5>
                    <div id="datos-estudiante-seleccionado"></div>
                  </div>

                  <!-- INFORMACIÓN ACADÉMICA -->
                  <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title"><b>Información Académica</b></h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <!-- <div class="col-md-4">
                          <div class="form-group">
                            <label for="id_periodo">Período Académico</label>
                            <select name="id_periodo" class="form-control" required>
                              <option value="">Seleccionar Período</option>
                              <?php
                              echo "<option value='1' selected>Año Escolar 2024-2025</option>";
                              ?>
                            </select>
                          </div>
                        </div> -->
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="id_periodo">Período Académico</label>
                            <select name="id_periodo" class="form-control" required>
                              <option value="">Seleccionar Período</option>
                              <?php
                              if (!empty($periodos)) {
                                foreach ($periodos as $periodo) {
                                  $selected = ($periodo['estatus'] == 1) ? 'selected' : '';
                                  echo "<option value='{$periodo['id_periodo']}' $selected>{$periodo['descripcion_periodo']}</option>";
                                }
                              } else {
                                echo "<option value=''>No hay períodos disponibles</option>";
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="id_nivel">Nivel/Grado</label>
                            <select name="id_nivel" id="id_nivel" class="form-control" required>
                              <option value="">Seleccionar Nivel</option>
                              <?php
                              $niveles = [1 => 'Primer Grado', 2 => 'Segundo Grado'];
                              foreach ($niveles as $id => $nivel) {
                                echo "<option value='$id'>$nivel</option>";
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="id_seccion">Sección</label>
                            <select name="id_seccion" class="form-control" required>
                              <option value="">Seleccionar Sección</option>
                              <?php
                              $secciones = [1 => 'Sección A', 2 => 'Sección B'];
                              foreach ($secciones as $id => $seccion) {
                                echo "<option value='$id'>$seccion</option>";
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="3" placeholder="Observaciones adicionales..."></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Información del parentesco -->
                  <div class="row mt-3">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="parentesco">Parentesco con el Estudiante</label>
                        <input type="text" name="parentesco" id="parentesco" class="form-control" required readonly>
                      </div>
                    </div>
                  </div>
                  <!-- Botones de navegación y envío -->
                  <div class="row mt-4">
                    <div class="col-md-12 text-right">
                      <button type="button" class="btn btn-secondary btn-step" id="btn-back-to-step2">
                        <i class="fas fa-arrow-left"></i> Anterior
                      </button>
                      <button type="submit" class="btn btn-success btn-step" id="btn-submit">
                        <i class="fas fa-save"></i> Registrar Reinscripción
                      </button>
                      <button type="button" class="btn btn-danger btn-step" id="btn-cancelar">
                        <i class="fas fa-times"></i> Cancelar
                      </button>
                    </div>
                  </div>


                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
      <div id="mensaje-resultado" class="mt-3" style="display: none;"></div>
    </div>
  </div>
</div>

<script>
  // Event listener para el envío del formulario con Fetch
  document.getElementById('form-reinscripcion').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevenir envío tradicional

    // Validar que todos los campos requeridos estén llenos
    const requiredFields = this.querySelectorAll('[required]');
    let valid = true;

    requiredFields.forEach(field => {
      if (!field.value.trim()) {
        valid = false;
        field.classList.add('is-invalid');
      } else {
        field.classList.remove('is-invalid');
      }
    });

    // Validar que se haya seleccionado un estudiante
    const idEstudiante = document.getElementById('id_estudiante_existente').value;
    if (!idEstudiante) {
      alert('Por favor seleccione un estudiante');
      valid = false;
    }

    if (!valid) {
      alert('Por favor complete todos los campos requeridos');
      return;
    }

    // Mostrar loading en el botón
    const submitBtn = document.getElementById('btn-submit');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
    submitBtn.disabled = true;

    // Obtener datos del formulario
    const formData = new FormData(this);

    // Agregar datos adicionales necesarios
    formData.append('id_estudiante_existente', document.getElementById('id_estudiante_existente').value);
    formData.append('representante_existente', '1');
    formData.append('estudiante_existente', '1');

    // Enviar con Fetch
    fetch('/final/app/controllers/inscripciones/reinscripciong2.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
      })
      .then(data => {
        // Restaurar botón
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;

        const mensajeDiv = document.getElementById('mensaje-resultado');

        if (data.success) {
          // Éxito
          mensajeDiv.innerHTML = `
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> ¡Éxito!</h5>
                    ${data.message}<br>
                    <strong>Estudiante:</strong> ${data.estudiante_nombre || ''}<br>
                    <strong>ID de Reinscripción:</strong> ${data.id_inscripcion}
                </div>
            `;
          mensajeDiv.style.display = 'block';

          // Redirigir después de 3 segundos
          setTimeout(() => {
            window.location.href = 'http://localhost/final/admin/index.php';
          }, 3000);

        } else {
          // Error
          mensajeDiv.innerHTML = `
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Error</h5>
                    ${data.message}
                </div>
            `;
          mensajeDiv.style.display = 'block';
        }

        // Hacer scroll al mensaje
        mensajeDiv.scrollIntoView({
          behavior: 'smooth'
        });
      })
      .catch(error => {
        // Restaurar botón
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;

        // Error de conexión
        const mensajeDiv = document.getElementById('mensaje-resultado');
        mensajeDiv.innerHTML = `
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Error de conexión</h5>
                No se pudo conectar con el servidor. Intente nuevamente.
            </div>
        `;
        mensajeDiv.style.display = 'block';
        mensajeDiv.scrollIntoView({
          behavior: 'smooth'
        });

        console.error('Error:', error);
      });
  });
</script>

<script>
  // Event listener para el envío del formulario con Fetch
  document.getElementById('form-reinscripcion').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevenir envío tradicional

    // Validar que todos los campos requeridos estén llenos
    const requiredFields = this.querySelectorAll('[required]');
    let valid = true;

    requiredFields.forEach(field => {
      if (!field.value.trim()) {
        valid = false;
        field.classList.add('is-invalid');
      } else {
        field.classList.remove('is-invalid');
      }
    });

    // Validar que se haya seleccionado un estudiante
    const idEstudiante = document.getElementById('id_estudiante_existente').value;
    if (!idEstudiante) {
      alert('Por favor seleccione un estudiante');
      valid = false;
    }

    if (!valid) {
      alert('Por favor complete todos los campos requeridos');
      return;
    }

    // Mostrar loading en el botón
    const submitBtn = document.getElementById('btn-submit');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
    submitBtn.disabled = true;

    // Obtener datos del formulario
    const formData = new FormData(this);

    // Agregar datos adicionales necesarios
    formData.append('id_estudiante_existente', document.getElementById('id_estudiante_existente').value);
    formData.append('representante_existente', '1');
    formData.append('estudiante_existente', '1');

    // Enviar con Fetch
    fetch('/final/app/controllers/inscripciones/reinscripciong2.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
      })
      .then(data => {
        // Restaurar botón
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;

        const mensajeDiv = document.getElementById('mensaje-resultado');

        if (data.success) {
          // Éxito
          mensajeDiv.innerHTML = `
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> ¡Éxito!</h5>
                    ${data.message}<br>
                    <strong>Estudiante:</strong> ${data.estudiante_nombre || ''}<br>
                    <strong>ID de Reinscripción:</strong> ${data.id_inscripcion}
                </div>
            `;
          mensajeDiv.style.display = 'block';

          // Redirigir después de 3 segundos
          setTimeout(() => {
            window.location.href = 'http://localhost/final/admin/index.php';
          }, 3000);

        } else {
          // Error
          mensajeDiv.innerHTML = `
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Error</h5>
                    ${data.message}
                </div>
            `;
          mensajeDiv.style.display = 'block';
        }

        // Hacer scroll al mensaje
        mensajeDiv.scrollIntoView({
          behavior: 'smooth'
        });
      })
      .catch(error => {
        // Restaurar botón
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;

        // Error de conexión
        const mensajeDiv = document.getElementById('mensaje-resultado');
        mensajeDiv.innerHTML = `
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Error de conexión</h5>
                No se pudo conectar con el servidor. Intente nuevamente.
            </div>
        `;
        mensajeDiv.style.display = 'block';
        mensajeDiv.scrollIntoView({
          behavior: 'smooth'
        });

        console.error('Error:', error);
      });
  });

  // El resto del código JavaScript que ya tienes
  document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 3;
    let estudiantesData = [];

    // Función para limpiar mensajes de resultado
    function limpiarMensajes() {
      const mensajeDiv = document.getElementById('mensaje-resultado');
      mensajeDiv.style.display = 'none';
      mensajeDiv.innerHTML = '';
    }

    // Funciones para navegación entre pasos
    function showStep(step) {
      document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
      document.getElementById(`step${step}`).classList.add('active');

      document.querySelectorAll('#stepIndicator .nav-link').forEach((link, index) => {
        if (index + 1 === step) {
          link.classList.add('active');
        } else if (index + 1 < step) {
          link.classList.remove('active', 'disabled');
          link.classList.add('completed');
        } else {
          link.classList.remove('active', 'completed');
          link.classList.add('disabled');
        }
      });

      currentStep = step;
    }

    // Event listeners para botones de navegación
    document.getElementById('btn-next-to-step2').addEventListener('click', function() {
      limpiarMensajes();
      showStep(2);
    });

    document.getElementById('btn-next-to-step3').addEventListener('click', function() {
      limpiarMensajes();
      showStep(3);
    });

    document.getElementById('btn-back-to-step1').addEventListener('click', function() {
      limpiarMensajes();
      showStep(1);
    });

    document.getElementById('btn-back-to-step2').addEventListener('click', function() {
      limpiarMensajes();
      showStep(2);
    });

    // Event listener para el botón cancelar
    document.getElementById('btn-cancelar').addEventListener('click', function() {
      limpiarMensajes();

      // Opcional: Mostrar confirmación antes de cancelar
      if (confirm('¿Está seguro de que desea cancelar la reinscripción? Los datos no guardados se perderán.')) {
        window.location.href = 'http://localhost/final/admin/index.php';
      }
    });

    // Validar representante
    document.getElementById('btn-validar-representante').addEventListener('click', function() {
      const cedula = document.getElementById('cedula_representante').value;
      if (!cedula) {
        alert('Por favor ingrese la cédula del representante');
        return;
      }
      validarRepresentante(cedula);
    });

    function validarRepresentante(cedula) {
      const formData = new FormData();
      formData.append('cedula', cedula);

      fetch('/final/app/controllers/representantes/validar2.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          const resultado = document.getElementById('resultado-validacion');
          const nextButton = document.getElementById('btn-next-to-step2');

          if (data.existe) {
            resultado.innerHTML = `
                <div class="alert alert-success">
                    <strong>Representante encontrado:</strong> ${data.nombre_completo}
                    <br>Se encontraron ${data.total_estudiantes || 0} estudiante(s) asociado(s).
                </div>
                `;

            // Guardar datos del representante
            document.getElementById('representante_existente').value = '1';
            document.getElementById('id_direccion_repre').value = data.id_direccion;
            document.getElementById('id_representante_existente').value = data.id_representante;

            // Mostrar información del representante en el paso 2
            document.getElementById('datos-representante').innerHTML = `
                <strong>Nombre:</strong> ${data.nombre_completo}<br>
                <strong>Cédula:</strong> ${data.cedula}<br>
                <strong>Teléfono:</strong> ${data.telefono || 'No registrado'}
                `;
            document.getElementById('info-representante').style.display = 'block';

            // Cargar estudiantes del representante
            cargarEstudiantesRepresentante(data.id_representante);

            nextButton.style.display = 'inline-block';

          } else {
            resultado.innerHTML = `
                <div class="alert alert-info">
                    <strong>Representante no encontrado.</strong> Por favor introduzca cédula de identidad válida.
                </div>
                `;
            document.getElementById('representante_existente').value = '0';
          }
        })
        .catch(error => {
          console.error('Error:', error);
          document.getElementById('resultado-validacion').innerHTML = `
            <div class="alert alert-danger">
                Error al validar el representante. Intente nuevamente.
            </div>
            `;
        });
    }

    function cargarEstudiantesRepresentante(idRepresentante) {
      const formData = new FormData();
      formData.append('id_representante', idRepresentante);

      fetch('/final/app/controllers/estudiantes/estudiantes_por_representante.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          estudiantesData = data.estudiantes || [];
          mostrarEstudiantes(estudiantesData);
        })
        .catch(error => {
          console.error('Error:', error);
          document.getElementById('lista-estudiantes').innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger">
                Error al cargar los estudiantes. Intente nuevamente.
                </div>
            </div>
            `;
        });
    }

    function mostrarEstudiantes(estudiantes) {
      const container = document.getElementById('lista-estudiantes');

      if (estudiantes.length === 0) {
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-warning">
                    No se encontraron estudiantes asociados a este representante.
                </div>
            </div>
            `;
        return;
      }

      console.log("Estudiantes para reinscripción:", estudiantes);

      let html = '';
      estudiantes.forEach(estudiante => {
        // Manejar datos que pueden ser null/undefined
        const nivel = estudiante.nombre_nivel || 'No asignado';
        const seccion = estudiante.nom_seccion || '';
        const nivelSeccion = seccion ? ` - ${seccion}` : '';
        const periodoAnterior = estudiante.periodo_anterior_desc || 'Sin historial';

        // Estado basado en inscripción en período ACTIVO
        const estado = estudiante.estado_inscripcion || 'No inscrito';
        const badgeClass = (estado === 'Inscrito') ? 'badge-success' : 'badge-warning';

        html += `
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card estudiante-card" data-id="${estudiante.id_estudiante}" 
                    style="cursor: pointer; border: 1px solid #dee2e6; transition: all 0.3s ease;">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">${estudiante.primer_nombre} ${estudiante.primer_apellido}</h5>
                    </div>
                    <div class="card-body estudiante-info">
                        <p class="mb-1"><strong>Cédula:</strong> ${estudiante.cedula}</p>
                        <p class="mb-1"><strong>Último Nivel:</strong> ${nivel}${nivelSeccion}</p>
                        <p class="mb-1"><strong>Período Anterior:</strong> ${periodoAnterior}</p>
                        <p class="mb-1"><strong>Parentesco:</strong> ${estudiante.parentesco}</p>
                        <p class="mb-0"><strong>Estado Actual:</strong> 
                            <span class="badge ${badgeClass}">
                                ${estado}
                            </span>
                        </p>
                    </div>
                    <div class="card-footer text-center">
                        <button type="button" class="btn btn-primary btn-sm btn-seleccionar-estudiante" 
                                data-id="${estudiante.id_estudiante}">
                            <i class="fas fa-sync-alt"></i> Reinscribir
                        </button>
                    </div>
                </div>
            </div>
            `;
      });

      container.innerHTML = html;

      // Agregar event listeners a los botones de selección
      document.querySelectorAll('.btn-seleccionar-estudiante').forEach(button => {
        button.addEventListener('click', function(e) {
          e.stopPropagation();
          const idEstudiante = this.getAttribute('data-id');
          console.log("Botón clickeado, ID estudiante:", idEstudiante);
          seleccionarEstudiante(idEstudiante);
        });
      });

      // También permitir selección haciendo click en toda la card
      document.querySelectorAll('.estudiante-card').forEach(card => {
        card.addEventListener('click', function(e) {
          if (!e.target.closest('.btn-seleccionar-estudiante')) {
            const idEstudiante = this.getAttribute('data-id');
            console.log("Card clickeada, ID estudiante:", idEstudiante);
            seleccionarEstudiante(idEstudiante);
          }
        });
      });

      console.log("Event listeners agregados a", document.querySelectorAll('.estudiante-card').length, "cards");
    }

    function seleccionarEstudiante(idEstudiante) {
      console.log("Intentando seleccionar estudiante ID:", idEstudiante);
      console.log("Datos disponibles:", estudiantesData);

      const estudiante = estudiantesData.find(e => e.id_estudiante == idEstudiante);

      if (estudiante) {
        console.log("Estudiante encontrado:", estudiante);

        // Remover selección anterior
        document.querySelectorAll('.estudiante-card').forEach(card => {
          card.classList.remove('selected');
          card.style.border = '1px solid #dee2e6';
        });

        // Marcar como seleccionado
        const cardSeleccionada = document.querySelector(`.estudiante-card[data-id="${idEstudiante}"]`);
        if (cardSeleccionada) {
          cardSeleccionada.classList.add('selected');
          cardSeleccionada.style.border = '3px solid #007bff';
          cardSeleccionada.style.backgroundColor = '#f8f9fa';
        }

        // Actualizar datos del formulario
        document.getElementById('id_estudiante_existente').value = estudiante.id_estudiante;
        document.getElementById('id_direccion_est').value = estudiante.id_direccion || '';
        document.getElementById('parentesco').value = estudiante.parentesco;

        // Mostrar información del estudiante seleccionado
        const nivelAnterior = estudiante.nombre_nivel || 'No asignado';
        const numNivelAnterior = estudiante.num_nivel;
        const periodoAnterior = estudiante.periodo_anterior_desc || 'Sin historial';

        document.getElementById('datos-estudiante-seleccionado').innerHTML = `
                <strong>Nombre completo:</strong> ${estudiante.primer_nombre} ${estudiante.segundo_nombre || ''} ${estudiante.primer_apellido} ${estudiante.segundo_apellido || ''}<br>
                <strong>Cédula:</strong> ${estudiante.cedula}<br>
                <strong>Fecha de nacimiento:</strong> ${estudiante.fecha_nac || 'No registrada'}<br>
                <strong>Parentesco:</strong> ${estudiante.parentesco}<br>
                <strong>Último nivel cursado:</strong> ${nivelAnterior} (${periodoAnterior})
            `;

        // Pre-seleccionar nivel siguiente si existe nivel anterior
        if (numNivelAnterior) {
          const siguienteNivel = parseInt(numNivelAnterior) + 1;
          document.getElementById('id_nivel').value = siguienteNivel;
          console.log("Nivel siguiente seleccionado:", siguienteNivel);
        } else {
          document.getElementById('id_nivel').value = 1;
          console.log("Nivel por defecto seleccionado: 1");
        }

        // Mostrar botón para continuar
        document.getElementById('btn-next-to-step3').style.display = 'inline-block';
        console.log("Botón siguiente mostrado");

        document.getElementById('btn-next-to-step3').scrollIntoView({
          behavior: 'smooth',
          block: 'center'
        });

      } else {
        console.error("Estudiante no encontrado en los datos. ID:", idEstudiante);
        alert('Error: No se pudo encontrar la información del estudiante seleccionado.');
      }
    }
  });
</script>

<!-- <script>
  document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 3;
    let estudiantesData = [];

    // Función para limpiar mensajes de resultado
    function limpiarMensajes() {
      const mensajeDiv = document.getElementById('mensaje-resultado');
      mensajeDiv.style.display = 'none';
      mensajeDiv.innerHTML = '';
    }

    // Funciones para navegación entre pasos
    function showStep(step) {
      document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
      document.getElementById(`step${step}`).classList.add('active');

      document.querySelectorAll('#stepIndicator .nav-link').forEach((link, index) => {
        if (index + 1 === step) {
          link.classList.add('active');
        } else if (index + 1 < step) {
          link.classList.remove('active', 'disabled');
          link.classList.add('completed');
        } else {
          link.classList.remove('active', 'completed');
          link.classList.add('disabled');
        }
      });

      currentStep = step;
    }

    // Event listeners para botones de navegación
    document.getElementById('btn-next-to-step2').addEventListener('click', function() {
      limpiarMensajes();
      showStep(2);
    });

    document.getElementById('btn-next-to-step3').addEventListener('click', function() {
      limpiarMensajes();
      showStep(3);
    });

    document.getElementById('btn-back-to-step1').addEventListener('click', function() {
      limpiarMensajes();
      showStep(1);
    });

    document.getElementById('btn-back-to-step2').addEventListener('click', function() {
      limpiarMensajes();
      showStep(2);
    });

    // Event listener para el botón cancelar
    document.getElementById('btn-cancelar').addEventListener('click', function() {
      limpiarMensajes();

      // Opcional: Mostrar confirmación antes de cancelar
      if (confirm('¿Está seguro de que desea cancelar la reinscripción? Los datos no guardados se perderán.')) {
        window.location.href = 'http://localhost/final/admin/index.php';
      }
    });

    // Validar representante
    document.getElementById('btn-validar-representante').addEventListener('click', function() {
      const cedula = document.getElementById('cedula_representante').value;
      if (!cedula) {
        alert('Por favor ingrese la cédula del representante');
        return;
      }
      validarRepresentante(cedula);
    });

    function validarRepresentante(cedula) {
      const formData = new FormData();
      formData.append('cedula', cedula);

      fetch('/final/app/controllers/representantes/validar2.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          const resultado = document.getElementById('resultado-validacion');
          const nextButton = document.getElementById('btn-next-to-step2');

          if (data.existe) {
            resultado.innerHTML = `
              <div class="alert alert-success">
                <strong>Representante encontrado:</strong> ${data.nombre_completo}
                <br>Se encontraron ${data.total_estudiantes || 0} estudiante(s) asociado(s).
              </div>
            `;

            // Guardar datos del representante
            document.getElementById('representante_existente').value = '1';
            document.getElementById('id_direccion_repre').value = data.id_direccion;
            document.getElementById('id_representante_existente').value = data.id_representante;

            // Mostrar información del representante en el paso 2
            document.getElementById('datos-representante').innerHTML = `
              <strong>Nombre:</strong> ${data.nombre_completo}<br>
              <strong>Cédula:</strong> ${data.cedula}<br>
              <strong>Teléfono:</strong> ${data.telefono || 'No registrado'}
            `;
            document.getElementById('info-representante').style.display = 'block';

            // Cargar estudiantes del representante
            cargarEstudiantesRepresentante(data.id_representante);

            nextButton.style.display = 'inline-block';

          } else {
            resultado.innerHTML = `
              <div class="alert alert-info">
                <strong>Representante no encontrado.</strong> Por favor introduzca cédula de identidad válida.
              </div>
            `;
            document.getElementById('representante_existente').value = '0';
          }
        })
        .catch(error => {
          console.error('Error:', error);
          document.getElementById('resultado-validacion').innerHTML = `
            <div class="alert alert-danger">
              Error al validar el representante. Intente nuevamente.
            </div>
          `;
        });
    }

    function cargarEstudiantesRepresentante(idRepresentante) {
      const formData = new FormData();
      formData.append('id_representante', idRepresentante);

      fetch('/final/app/controllers/estudiantes/estudiantes_por_representante.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          estudiantesData = data.estudiantes || [];
          mostrarEstudiantes(estudiantesData);
        })
        .catch(error => {
          console.error('Error:', error);
          document.getElementById('lista-estudiantes').innerHTML = `
            <div class="col-12">
              <div class="alert alert-danger">
                Error al cargar los estudiantes. Intente nuevamente.
              </div>
            </div>
          `;
        });
    }

    function mostrarEstudiantes(estudiantes) {
      const container = document.getElementById('lista-estudiantes');

      if (estudiantes.length === 0) {
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-warning">
                    No se encontraron estudiantes asociados a este representante.
                </div>
            </div>
        `;
        return;
      }

      console.log("Estudiantes para reinscripción:", estudiantes);

      let html = '';
      estudiantes.forEach(estudiante => {
        // Manejar datos que pueden ser null/undefined
        const nivel = estudiante.nombre_nivel || 'No asignado';
        const seccion = estudiante.nom_seccion || '';
        const nivelSeccion = seccion ? ` - ${seccion}` : '';
        const periodoAnterior = estudiante.periodo_anterior_desc || 'Sin historial';

        // Estado basado en inscripción en período ACTIVO
        const estado = estudiante.estado_inscripcion || 'No inscrito';
        const badgeClass = (estado === 'Inscrito') ? 'badge-success' : 'badge-warning';

        html += `
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card estudiante-card" data-id="${estudiante.id_estudiante}" 
                     style="cursor: pointer; border: 1px solid #dee2e6; transition: all 0.3s ease;">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">${estudiante.primer_nombre} ${estudiante.primer_apellido}</h5>
                    </div>
                    <div class="card-body estudiante-info">
                        <p class="mb-1"><strong>Cédula:</strong> ${estudiante.cedula}</p>
                        <p class="mb-1"><strong>Último Nivel:</strong> ${nivel}${nivelSeccion}</p>
                        <p class="mb-1"><strong>Período Anterior:</strong> ${periodoAnterior}</p>
                        <p class="mb-1"><strong>Parentesco:</strong> ${estudiante.parentesco}</p>
                        <p class="mb-0"><strong>Estado Actual:</strong> 
                            <span class="badge ${badgeClass}">
                                ${estado}
                            </span>
                        </p>
                    </div>
                    <div class="card-footer text-center">
                        <button type="button" class="btn btn-primary btn-sm btn-seleccionar-estudiante" 
                                data-id="${estudiante.id_estudiante}">
                            <i class="fas fa-sync-alt"></i> Reinscribir
                        </button>
                    </div>
                </div>
            </div>
        `;
      });

      container.innerHTML = html;

      // Agregar event listeners a los botones de selección
      document.querySelectorAll('.btn-seleccionar-estudiante').forEach(button => {
        button.addEventListener('click', function(e) {
          e.stopPropagation();
          const idEstudiante = this.getAttribute('data-id');
          console.log("Botón clickeado, ID estudiante:", idEstudiante);
          seleccionarEstudiante(idEstudiante);
        });
      });

      // También permitir selección haciendo click en toda la card
      document.querySelectorAll('.estudiante-card').forEach(card => {
        card.addEventListener('click', function(e) {
          if (!e.target.closest('.btn-seleccionar-estudiante')) {
            const idEstudiante = this.getAttribute('data-id');
            console.log("Card clickeada, ID estudiante:", idEstudiante);
            seleccionarEstudiante(idEstudiante);
          }
        });
      });

      console.log("Event listeners agregados a", document.querySelectorAll('.estudiante-card').length, "cards");
    }

    function seleccionarEstudiante(idEstudiante) {
      console.log("Intentando seleccionar estudiante ID:", idEstudiante);
      console.log("Datos disponibles:", estudiantesData);

      const estudiante = estudiantesData.find(e => e.id_estudiante == idEstudiante);

      if (estudiante) {
        console.log("Estudiante encontrado:", estudiante);

        // Remover selección anterior
        document.querySelectorAll('.estudiante-card').forEach(card => {
          card.classList.remove('selected');
          card.style.border = '1px solid #dee2e6';
        });

        // Marcar como seleccionado
        const cardSeleccionada = document.querySelector(`.estudiante-card[data-id="${idEstudiante}"]`);
        if (cardSeleccionada) {
          cardSeleccionada.classList.add('selected');
          cardSeleccionada.style.border = '3px solid #007bff';
          cardSeleccionada.style.backgroundColor = '#f8f9fa';
        }

        // Actualizar datos del formulario
        document.getElementById('id_estudiante_existente').value = estudiante.id_estudiante;
        document.getElementById('id_direccion_est').value = estudiante.id_direccion || '';
        document.getElementById('parentesco').value = estudiante.parentesco;

        // Mostrar información del estudiante seleccionado
        const nivelAnterior = estudiante.nombre_nivel || 'No asignado';
        const numNivelAnterior = estudiante.num_nivel;
        const periodoAnterior = estudiante.periodo_anterior_desc || 'Sin historial';

        document.getElementById('datos-estudiante-seleccionado').innerHTML = `
            <strong>Nombre completo:</strong> ${estudiante.primer_nombre} ${estudiante.segundo_nombre || ''} ${estudiante.primer_apellido} ${estudiante.segundo_apellido || ''}<br>
            <strong>Cédula:</strong> ${estudiante.cedula}<br>
            <strong>Fecha de nacimiento:</strong> ${estudiante.fecha_nac || 'No registrada'}<br>
            <strong>Parentesco:</strong> ${estudiante.parentesco}<br>
            <strong>Último nivel cursado:</strong> ${nivelAnterior} (${periodoAnterior})
        `;

        // Pre-seleccionar nivel siguiente si existe nivel anterior
        if (numNivelAnterior) {
          const siguienteNivel = parseInt(numNivelAnterior) + 1;
          document.getElementById('id_nivel').value = siguienteNivel;
          console.log("Nivel siguiente seleccionado:", siguienteNivel);
        } else {
          document.getElementById('id_nivel').value = 1;
          console.log("Nivel por defecto seleccionado: 1");
        }

        // Mostrar botón para continuar
        document.getElementById('btn-next-to-step3').style.display = 'inline-block';
        console.log("Botón siguiente mostrado");

        document.getElementById('btn-next-to-step3').scrollIntoView({
          behavior: 'smooth',
          block: 'center'
        });

      } else {
        console.error("Estudiante no encontrado en los datos. ID:", idEstudiante);
        alert('Error: No se pudo encontrar la información del estudiante seleccionado.');
      }
    }
  });
</script> -->

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>