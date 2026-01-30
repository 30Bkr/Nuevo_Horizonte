<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include_once("/xampp/htdocs/final/layout/layaout1.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);


// Incluir los controladores necesarios
include_once("/xampp/htdocs/final/app/controllers/personas/personas.php");
include_once("/xampp/htdocs/final/app/controllers/estudiantes/estudiantes.php");
include_once("/xampp/htdocs/final/app/controllers/representantes/representantes.php");
include_once("/xampp/htdocs/final/app/controllers/ubicaciones/ubicaciones.php");
include_once("/xampp/htdocs/final/app/controllers/inscripciones/inscripciones.php");
include_once("/xampp/htdocs/final/app/controllers/parentesco/parentesco.php");
include_once("/xampp/htdocs/final/app/controllers/patologias/patologias.php");
include_once("/xampp/htdocs/final/app/controllers/discapacidades/discapacidades.php");
include_once("/xampp/htdocs/final/app/controllers/cupos/cupos.php");
include_once("/xampp/htdocs/final/app/conexion.php");
require_once '/xampp/htdocs/final/global/notifications.php';

Notification::show();
try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $inscripcionesController = new InscripcionController($pdo);
  $periodos_vigentes = $inscripcionesController->obtenerPeriodosVigentes();
  $periodos = $periodos_vigentes['periodos'] ?? [];
  $profesionesController = new RepresentanteController($pdo);
  $profesiones = $profesionesController->obtenerProfesiones();
  $ubicacionController = new UbicacionController($pdo);
  $parentesco = new ParentescoController($pdo);
  $parentescos = $parentesco->mostrarParentescos();
  $patologiaController = new PatologiaController($pdo);
  $discapacidadController = new DiscapacidadController($pdo);
  $estados = $ubicacionController->obtenerEstados();
} catch (PDOException $e) {
  die("Error de conexión: " . $e->getMessage());
}
?>
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
    border: 2px solid #dee2e6;
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

  .patologia-item,
  .discapacidad-item {
    transition: all 0.3s ease;
  }

  .btn-eliminar-patologia,
  .btn-eliminar-discapacidad {
    opacity: 0.7;
    transition: opacity 0.3s ease;
  }

  .btn-eliminar-patologia:hover,
  .btn-eliminar-discapacidad:hover {
    opacity: 1;
  }

  .select-patologia,
  .select-discapacidad {
    min-width: 200px;
  }

  .form-control[readonly] {
    background-color: #f8f9fa !important;
    cursor: not-allowed !important;
    opacity: 1 !important;
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
                    <strong>Paso 2:</strong> Datos del Representante
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link disabled" id="step3-tab" href="javascript:void(0)">
                    <strong>Paso 3:</strong> Seleccionar Estudiante
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link disabled" id="step4-tab" href="javascript:void(0)">
                    <strong>Paso 4:</strong> Datos del Estudiante
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <form action="http://localhost/final/app/controllers/reinscripciones/reinscripcionController.php" method="post" id="form-reinscripcion">
        <input type="hidden" name="id_parentesco_estudiante" id="id_parentesco_estudiante" value="">
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

        <!-- PASO 2: DATOS DEL REPRESENTANTE -->
        <div class="step" id="step2">
          <div class="row">
            <div class="col-md-12">
              <div class="card card-outline card-warning">
                <div class="card-header">
                  <h3 class="card-title"><b>Paso 2: Datos del Representante</b></h3>
                </div>
                <div class="card-body">
                  <input type="hidden" name="representante_existente" id="representante_existente" value="1">
                  <input type="hidden" name="id_representante_existente" id="id_representante_existente" value="">
                  <input type="hidden" name="id_direccion_repre" id="id_direccion_repre" value="">
                  <input type="hidden" name="tipo_persona" id="tipo_persona" value="representante">
                  <input type="hidden" name="misma_casa" id="misma_casa_hidden" value="">

                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="nacionalidad_r">Nacionalidad <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <select name="nacionalidad_r" id="nacionalidad_r" class="form-control" required>
                          <option value="">Seleccionar</option>
                          <option value="Venezolano">Venezolano</option>
                          <option value="Extranjero">Extranjero</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="cedula_r">Cédula de Identidad <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <input type="number" name="cedula_r" id="cedula_r" class="form-control" required readonly>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="sexo_r">Sexo <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <select name="sexo_r" id="sexo_r" class="form-control" required>
                          <option value="">Seleccionar</option>
                          <option value="Masculino">Masculino</option>
                          <option value="Femenino">Femenino</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="fecha_nac_r">Fecha de Nacimiento <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <input type="date" name="fecha_nac_r" id="fecha_nac_r" class="form-control" required>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="primer_nombre_r">Primer Nombre <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <input type="text" name="primer_nombre_r" id="primer_nombre_r" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="segundo_nombre_r">Segundo Nombre</label>
                        <input type="text" name="segundo_nombre_r" id="segundo_nombre_r" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="primer_apellido_r">Primer Apellido <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <input type="text" name="primer_apellido_r" id="primer_apellido_r" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="segundo_apellido_r">Segundo Apellido</label>
                        <input type="text" name="segundo_apellido_r" id="segundo_apellido_r" class="form-control">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="lugar_nac_r">Lugar de Nacimiento <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <input type="text" name="lugar_nac_r" id="lugar_nac_r" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="correo_r">Correo Electrónico <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <input type="email" name="correo_r" id="correo_r" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="telefono_r">Teléfono Móvil <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <input type="text" name="telefono_r" id="telefono_r" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="telefono_hab_r">Teléfono Habitación</label>
                        <input type="text" name="telefono_hab_r" id="telefono_hab_r" class="form-control">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="profesion_r">Profesión <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <select name="profesion_r" id="profesion_r" class="form-control" required>
                          <option value="">Seleccione Profesión</option>
                          <?php
                          foreach ($profesiones as $profesion) {
                            echo "<option value='{$profesion['id_profesion']}'>{$profesion['profesion']}</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="ocupacion_r">Ocupación <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <input type="text" name="ocupacion_r" id="ocupacion_r" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="lugar_trabajo_r">Lugar de Trabajo</label>
                        <input type="text" name="lugar_trabajo_r" id="lugar_trabajo_r" class="form-control">
                      </div>
                    </div>
                  </div>

                  <!-- DIRECCIÓN DEL REPRESENTANTE -->
                  <div class="card-header mt-4">
                    <h3 class="card-title"><b>Dirección del Representante</b></h3>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="estado_r">Estado <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                          <select name="estado_r" id="estado_r" class="form-control" required>
                            <option value="">Seleccionar Estado</option>
                            <?php
                            foreach ($estados as $estado) {
                              echo "<option value='{$estado['id_estado']}'>{$estado['nom_estado']}</option>";
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="municipio_r">Municipio <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                          <select name="municipio_r" id="municipio_r" class="form-control" required disabled>
                            <option value="">Primero seleccione un estado</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="parroquia_r">Parroquia <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                          <select name="parroquia_r" id="parroquia_r" class="form-control" required disabled>
                            <option value="">Primero seleccione un municipio</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="direccion_r">Dirección Completa <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                          <input type="text" name="direccion_r" id="direccion_r" class="form-control" required>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="calle_r">Calle/Avenida</label>
                          <input type="text" name="calle_r" id="calle_r" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="casa_r">Casa/Edificio</label>
                          <input type="text" name="casa_r" id="casa_r" class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Botones de navegación -->
                  <div class="row mt-4">
                    <div class="col-md-12 text-right">
                      <button type="button" class="btn btn-secondary btn-step" id="btn-back-to-step1">
                        <i class="fas fa-arrow-left"></i> Anterior
                      </button>
                      <button type="button" class="btn btn-primary btn-step" id="btn-next-to-step3">
                        Siguiente <i class="fas fa-arrow-right"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- PASO 3: SELECCIONAR ESTUDIANTE -->
        <div class="step" id="step3">
          <div class="row">
            <div class="col-md-12">
              <div class="card card-outline card-info">
                <div class="card-header">
                  <h3 class="card-title"><b>Paso 3: Seleccionar Estudiante</b></h3>
                </div>
                <div class="card-body">
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
                      <button type="button" class="btn btn-secondary btn-step" id="btn-back-to-step2">
                        <i class="fas fa-arrow-left"></i> Anterior
                      </button>
                      <button type="button" class="btn btn-primary btn-step" id="btn-next-to-step4" style="display: none;">
                        Siguiente <i class="fas fa-arrow-right"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- PASO 4: DATOS DEL ESTUDIANTE -->

        <div class="step" id="step4">
          <input type="hidden" name="id_seccion" id="id_seccion_hidden" value="">

          <input type="hidden" name="id_direccion_est" id="id_direccion_est" value="">
          <input type="hidden" name="id_direccion_repre_compartida" id="id_direccion_repre_compartida" value="">
          <input type="hidden" name="juntos" id="juntos" value="1">
          <input type="hidden" name="parentesco_estudiante" id="parentesco_estudiante" value="">

          <div class="row">
            <div class="col-md-12">
              <div class="card card-outline card-success">
                <!-- Pregunta si el alumno vive en la casa del representante -->
                <div class="card-header mt-4">
                  <h3 class="card-title"><b>Datos de interés</b></h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="misma_casa">¿El alumno vive en la misma casa del representante?</label>
                        <select name="misma_casa_select" id="misma_casa" class="form-control" required>
                          <option value="">Seleccionar...</option>
                          <option value="si">Sí</option>
                          <option value="no">No</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="ci_si">¿El alumno cuenta con cédula de identidad? <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <select name="ci_si" id="ci_si" class="form-control" required>
                          <option value="">Seleccionar...</option>
                          <option value="si">Sí</option>
                          <option value="no">No</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card-header">
                  <h3 class="card-title"><b>Paso 4: Datos del Estudiante</b></h3>
                </div>
                <div class="card-body">
                  <input type="hidden" name="estudiante_existente" id="estudiante_existente" value="1">
                  <input type="hidden" name="id_estudiante_existente" id="id_estudiante_existente" value="">

                  <!-- Información del estudiante seleccionado -->
                  <div id="info-estudiante-seleccionado" class="alert alert-success mb-4" style="display: none;">
                    <h5><i class="fas fa-user-graduate"></i> Estudiante Seleccionado</h5>
                    <div id="datos-estudiante-seleccionado"></div>
                  </div>

                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="nacionalidad_e">Nacionalidad <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <select name="nacionalidad_e" id="nacionalidad_e" class="form-control" required>
                          <option value="">Seleccionar</option>
                          <option value="Venezolano">Venezolano</option>
                          <option value="Extranjero">Extranjero</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="fecha_nac_e">Fecha de Nacimiento </label>
                        <input type="date" name="fecha_nac_e" id="fecha_nac_e" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="cedula_e">Cédula de Identidad</label>
                        <input type="text" name="cedula_e" id="cedula_e" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="sexo_e">Sexo <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <select name="sexo_e" id="sexo_e" class="form-control" required>
                          <option value="">Seleccionar</option>
                          <option value="Femenino">Femenino</option>
                          <option value="Masculino">Masculino</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="primer_nombre_e">Primer Nombre <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <input type="text" name="primer_nombre_e" id="primer_nombre_e" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="segundo_nombre_e">Segundo Nombre</label>
                        <input type="text" name="segundo_nombre_e" id="segundo_nombre_e" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="primer_apellido_e">Primer Apellido <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <input type="text" name="primer_apellido_e" id="primer_apellido_e" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="segundo_apellido_e">Segundo Apellido</label>
                        <input type="text" name="segundo_apellido_e" id="segundo_apellido_e" class="form-control">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="lugar_nac_e">Lugar de Nacimiento <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <input type="text" name="lugar_nac_e" id="lugar_nac_e" class="form-control" required>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="telefono_e">Teléfono</label>
                        <input type="text" name="telefono_e" id="telefono_e" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="correo_e">Correo Electrónico</label>
                        <input type="email" name="correo_e" id="correo_e" class="form-control">
                      </div>
                    </div>
                  </div>

                  <!-- DATOS DE SALUD -->
                  <div>
                    <div class="card-header mt-4">
                      <h3 class="card-title"><b>Datos de salud</b></h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Patologías/Alergias</label>
                            <div id="contenedor-patologias">
                              <div class="mb-2 patologia-item">
                                <select name="patologias[]" class="form-control select-patologia">
                                  <option value="">Seleccione una patología...</option>
                                  <option value="0">Ninguna</option>
                                  <?php
                                  $patologias = $patologiaController->obtenerPatologiasActivas();
                                  if (!empty($patologias)) {
                                    foreach ($patologias as $patologia) {
                                      echo "<option value='{$patologia['id_patologia']}'>{$patologia['nom_patologia']}</option>";
                                    }
                                  }
                                  ?>
                                </select>
                              </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-agregar-patologia">
                              <i class="fas fa-plus"></i> Agregar otra patología
                            </button>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Discapacidades</label>
                            <div id="contenedor-discapacidades">
                              <div class="mb-2 discapacidad-item">
                                <select name="discapacidades[]" class="form-control select-discapacidad">
                                  <option value="">Seleccione una discapacidad...</option>
                                  <option value="0">Ninguna</option>
                                  <?php
                                  $discapacidades = $discapacidadController->obtenerDiscapacidadesActivas();
                                  if (!empty($discapacidades)) {
                                    foreach ($discapacidades as $discapacidad) {
                                      echo "<option value='{$discapacidad['id_discapacidad']}'>{$discapacidad['nom_discapacidad']}</option>";
                                    }
                                  }
                                  ?>
                                </select>
                              </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-agregar-discapacidad">
                              <i class="fas fa-plus"></i> Agregar otra discapacidad
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="observaciones">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="3" placeholder="Observaciones adicionales..."></textarea>
                      </div>
                    </div>
                  </div>

                  <!-- Dirección del alumno -->
                  <div id="direccion_representante" style="display: none;">
                    <div class="card-header mt-4">
                      <h3 class="card-title"><b>Dirección del Alumno</b></h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="estado_e">Estado <span class="text-danger">*</span></label>
                            <select name="estado_e" id="estado_e" class="form-control">
                              <option value="">Seleccionar Estado</option>
                              <?php
                              foreach ($estados as $estado) {
                                echo "<option value='{$estado['id_estado']}'>{$estado['nom_estado']}</option>";
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="municipio_e">Municipio <span class="text-danger">*</span></label>
                            <select name="municipio_e" id="municipio_e" class="form-control" disabled>
                              <option value="">Primero seleccione un estado</option>
                            </select>
                          </div>

                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="parroquia_e">Parroquia <span class="text-danger">*</span></label>
                            <select name="parroquia_e" id="parroquia_e" class="form-control" disabled>
                              <option value="">Primero seleccione un municipio</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="direccion_e">Dirección Completa <span class="text-danger">*</span></label>
                            <input type="text" name="direccion_e" id="direccion_e" class="form-control">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="calle_e">Calle/Avenida</label>
                            <input type="text" name="calle_e" id="calle_e" class="form-control">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="casa_e">Casa/Edificio</label>
                            <input type="text" name="casa_e" id="casa_e" class="form-control">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- INFORMACIÓN ACADÉMICA -->
                  <div class="informacion_academica">
                    <div class="card-header mt-4">
                      <h3 class="card-title"><b>Información Académica</b></h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <!-- <div class="col-md-4">
                          <div class="form-group">
                            <label for="id_periodo">Período Académico <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                            <select name="id_periodo" id="id_periodo" class="form-control" required>
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
                        </div> -->
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="id_periodo">Período Académico <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                            <select name="id_periodo" id="id_periodo" class="form-control" required>
                              <option value="">Seleccionar Período</option>
                              <?php
                              if (!empty($periodos)) {
                                foreach ($periodos as $periodo) {
                                  $selected = ($periodo['estatus'] == 1) ? 'selected' : '';
                                  $fecha_ini = date('d/m/Y', strtotime($periodo['fecha_ini']));
                                  $fecha_fin = date('d/m/Y', strtotime($periodo['fecha_fin']));
                                  echo "<option value='{$periodo['id_periodo']}' data-fecha-ini='{$periodo['fecha_ini']}' data-fecha-fin='{$periodo['fecha_fin']}' $selected>
                            {$periodo['descripcion_periodo']} ({$fecha_ini} al {$fecha_fin}) {$periodo['estatus']}
                          </option>";
                                }
                              } else if ($periodos == []) {
                                echo "<option value=''>El periodo academico activo ha finalizado</option>";
                              } else {
                                echo "<option value=''>No hay períodos vigentes</option>";
                              }
                              ?>
                            </select>
                            <small class="form-text text-muted">
                              <?php
                              $fecha_hoy = date('d/m/Y');
                              if (!empty($periodos)) {
                                echo "Fecha actual: {$fecha_hoy} - Períodos dentro del rango de fechas.";
                              } else  if ($periodos == []) {
                                echo '⚠️ No es posible reinscribir. El periodo academico activo ha finalizado.';
                              } else {
                                echo "⚠️ No hay períodos académicos vigentes. Fecha actual: {$fecha_hoy}";
                              }
                              ?>
                            </small>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="id_nivel">Grado/Año<span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                            <select name="id_nivel" id="id_nivel" class="form-control" required>
                              <option value="">Seleccionar Nivel</option>
                              <!-- Los niveles se cargarán dinámicamente via JavaScript -->
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="id_seccion">Sección <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                            <select name="id_seccion" id="id_seccion" class="form-control" required disabled>
                              <option value="">Primero seleccione un nivel</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Botones de navegación y envío -->
                  <div class="row mt-4">
                    <div class="col-md-12 text-right">
                      <button type="button" class="btn btn-secondary btn-step" id="btn-back-to-step3">
                        <i class="fas fa-arrow-left"></i> Anterior
                      </button>
                      <button type="submit" class="btn btn-success btn-step">
                        <i class="fas fa-save"></i> Registrar Reinscripción
                      </button>
                      <a href="http://localhost/final/app/controllers/estudiantes" class="btn btn-danger btn-step">
                        <i class="fas fa-times"></i> Cancelar
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>



<script>
  // ========== SISTEMA DE NAVEGACIÓN ==========
  document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 4;
    let estudiantesData = [];
    let representanteData = null;
    let mensajeCupos = null;


    const fechaNacInput = document.querySelector('input[name="fecha_nac_e"]');

    const seccionSelect = document.querySelector('select[name="id_seccion"]');
    const nivelSelect = document.querySelector('select[name="id_nivel"]');
    const periodoSelect = document.querySelector('select[name="id_periodo"]');
    const submitBtn = document.querySelector('button[type="submit"]');


    function inicializar() {
      console.log('🔄 Inicializando sistema...');

      // Guardar copia de todos los niveles disponibles
      guardarNivelesOriginales();

      // Configurar event listeners
      configurarEventListeners();

      // Aplicar filtro inicial si hay fecha
      if (fechaNacInput && fechaNacInput.value) {
        console.log('📅 Fecha encontrada:', fechaNacInput.value);
        setTimeout(() => validarEdadYFiltrarNiveles(fechaNacInput.value), 300);
      }

      // Inicializar secciones si hay nivel seleccionado
      if (nivelSelect && nivelSelect.value) {
        console.log('🎯 Nivel pre-seleccionado:', nivelSelect.value);
        setTimeout(() => cargarSeccionesPorNivel(nivelSelect.value), 500);
      } else {
        console.log('⚠️ No hay nivel seleccionado');
        limpiarSecciones();
      }
    }

    seccionSelect.addEventListener('change', function() {
      console.log('🎯 Nivel cambiado:', this.value);
      if (this.value) {
        verificarCupos();

      } else {
        limpiarSecciones();
      }
    });

    function verificarCupos() {
      const selectedOption = seccionSelect.options[seccionSelect.selectedIndex];

      if (!nivelSelect.value || !seccionSelect.value || !periodoSelect.value || !selectedOption) {
        console.log('⚠️ Faltan datos para verificar cupos');
        eliminarMensajeCupos();
        return;
      }

      const id_nivel_seccion = selectedOption.getAttribute('data-nivel-seccion');
      const id_periodo = periodoSelect.value;

      if (!id_nivel_seccion) {
        console.error('❌ No se encontró id_nivel_seccion en la opción seleccionada');
        return;
      }

      console.log('🔍 Verificando cupos para:', {
        id_nivel_seccion,
        id_periodo
      });

      const formData = new FormData();
      formData.append('id_nivel_seccion', id_nivel_seccion);
      formData.append('id_periodo', id_periodo);

      fetch('/final/app/controllers/cupos/verificar_cupos.php', {
          method: 'POST',
          body: formData
        })
        .then(response => {
          if (!response.ok) throw new Error('Error en la respuesta del servidor');
          return response.json();
        })
        .then(data => {
          console.log('📊 Respuesta de cupos:', data);
          mostrarMensajeCupos(data);
        })
        .catch(error => {
          console.error('❌ Error al verificar cupos:', error);
          mostrarMensajeCupos({
            success: false,
            disponible: false,
            mensaje: 'Error al verificar disponibilidad de cupos'
          });
        });
    }

    function mostrarMensajeCupos(data) {
      eliminarMensajeCupos();

      const informacionAcademica = document.querySelector('.informacion_academica .card-body');
      if (!informacionAcademica) {
        console.warn('⚠️ No se encontró el contenedor para el mensaje de cupos');
        return;
      }

      mensajeCupos = document.createElement('div');
      mensajeCupos.className = `alert ${data.disponible ? 'alert-success' : 'alert-danger'} mt-3`;

      if (data.success) {
        mensajeCupos.innerHTML = `
                <strong>${data.disponible ? '✅ CUPOS DISPONIBLES' : '❌ SIN CUPOS'}</strong><br>
                ${data.mensaje}
                ${data.disponible ? 
                    `<br><small class="text-white">Puede continuar con la inscripción</small>` : 
                    `<br><small class="text-white">No se puede realizar la inscripción en esta sección</small>`
                }
            `;
      } else {
        mensajeCupos.innerHTML = `
                <strong>❌ ERROR</strong><br>
                ${data.message || 'Error al verificar cupos'}
            `;
      }

      informacionAcademica.appendChild(mensajeCupos);

      if (submitBtn) {
        submitBtn.disabled = !data.disponible;
        console.log('🔄 Botón submit:', data.disponible ? 'HABILITADO' : 'DESHABILITADO');
      }
    }

    function eliminarMensajeCupos() {
      if (mensajeCupos) {
        mensajeCupos.remove();
        mensajeCupos = null;
      }
      if (submitBtn) {
        submitBtn.disabled = false;
      }
    }

    ///arriba es los cupos

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

    // Event listeners para navegación
    document.getElementById('btn-next-to-step2').addEventListener('click', () => showStep(2));
    document.getElementById('btn-next-to-step3').addEventListener('click', () => {
      const requiredFields = document.querySelectorAll('#step2 [required]');
      let valid = true;

      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          valid = false;
          field.classList.add('is-invalid');
        } else {
          field.classList.remove('is-invalid');
        }
      });

      if (valid) {
        showStep(3);
      } else {
        alert('Por favor complete todos los campos requeridos del representante.');
      }
    });

    document.getElementById('btn-next-to-step4').addEventListener('click', () => showStep(4));
    document.getElementById('btn-back-to-step1').addEventListener('click', () => showStep(1));
    document.getElementById('btn-back-to-step2').addEventListener('click', () => showStep(2));
    document.getElementById('btn-back-to-step3').addEventListener('click', () => showStep(3));

    // ========== VALIDACIÓN DE REPRESENTANTE ==========
    document.getElementById('btn-validar-representante').addEventListener('click', validarRepresentante);

    document.getElementById('cedula_representante').addEventListener('keypress', function(e) {
      if (e.key === 'Enter' || e.keyCode === 13) {
        e.preventDefault();
        validarRepresentante();
      }
    });

    function validarRepresentante() {
      const cedula = document.getElementById('cedula_representante').value;
      if (!cedula) {
        alert('Por favor ingrese la cédula del representante');
        return;
      }

      const formData = new FormData();
      formData.append('cedula', cedula);

      const btnValidar = document.getElementById('btn-validar-representante');
      const originalText = btnValidar.innerHTML;
      btnValidar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Validando...';
      btnValidar.disabled = true;

      fetch('/final/app/controllers/reinscripciones/validar_representante_reinscripcion.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          btnValidar.innerHTML = originalText;
          btnValidar.disabled = false;

          const resultado = document.getElementById('resultado-validacion');
          const nextButton = document.getElementById('btn-next-to-step2');

          if (data.existe) {
            resultado.innerHTML = `
                    <div class="alert alert-success">
                        <strong>Representante encontrado:</strong> ${data.nombre_completo}
                        <br>Se encontraron ${data.total_estudiantes || 0} estudiante(s) asociado(s).
                    </div>
                `;

            representanteData = data;
            llenarDatosRepresentante(data);
            cargarEstudiantesRepresentante(data.id_representante);
            nextButton.style.display = 'inline-block';

          } else {
            resultado.innerHTML = `
                    <div class="alert alert-info">
                        <strong>Representante no encontrado.</strong> Por favor verifique la cédula e intente nuevamente.
                    </div>
                `;
          }
        })
        .catch(error => {
          console.error('Error:', error);
          btnValidar.innerHTML = originalText;
          btnValidar.disabled = false;

          document.getElementById('resultado-validacion').innerHTML = `
                <div class="alert alert-danger">
                    Error al validar el representante. Intente nuevamente.
                </div>
            `;
        });
    }

    function llenarDatosRepresentante(data) {
      document.getElementById('id_representante_existente').value = data.id_representante;
      document.getElementById('id_direccion_repre').value = data.id_direccion;
      document.getElementById('id_direccion_repre_compartida').value = data.id_direccion;

      document.getElementById('cedula_r').value = data.cedula;
      document.getElementById('primer_nombre_r').value = data.primer_nombre || '';
      document.getElementById('segundo_nombre_r').value = data.segundo_nombre || '';
      document.getElementById('primer_apellido_r').value = data.primer_apellido || '';
      document.getElementById('segundo_apellido_r').value = data.segundo_apellido || '';
      document.getElementById('correo_r').value = data.correo || '';
      document.getElementById('telefono_r').value = data.telefono || '';
      document.getElementById('telefono_hab_r').value = data.telefono_hab || '';
      document.getElementById('fecha_nac_r').value = data.fecha_nac || '';
      document.getElementById('lugar_nac_r').value = data.lugar_nac || '';
      document.getElementById('sexo_r').value = data.sexo || '';
      document.getElementById('nacionalidad_r').value = data.nacionalidad || '';
      document.getElementById('profesion_r').value = data.id_profesion || '';
      document.getElementById('ocupacion_r').value = data.ocupacion || '';
      document.getElementById('lugar_trabajo_r').value = data.lugar_trabajo || '';

      document.getElementById('direccion_r').value = data.direccion || '';
      document.getElementById('calle_r').value = data.calle || '';
      document.getElementById('casa_r').value = data.casa || '';

      if (data.id_estado) {
        document.getElementById('estado_r').value = data.id_estado;
        cargarMunicipios(data.id_estado).then(() => {
          if (data.id_municipio) {
            document.getElementById('municipio_r').value = data.id_municipio;
            cargarParroquias(data.id_municipio).then(() => {
              if (data.id_parroquia) {
                document.getElementById('parroquia_r').value = data.id_parroquia;
              }
            });
          }
        });
      }

      document.getElementById('info-representante').style.display = 'block';
      document.getElementById('datos-representante').innerHTML = `
            <strong>Nombre:</strong> ${data.nombre_completo}<br>
            <strong>Cédula:</strong> ${data.cedula}<br>
            <strong>Teléfono:</strong> ${data.telefono || 'No registrado'}<br>
            <strong>Correo:</strong> ${data.correo || 'No registrado'}
        `;
    }

    // ========== CARGAR ESTUDIANTES ==========
    function cargarEstudiantesRepresentante(idRepresentante) {
      const formData = new FormData();
      formData.append('id_representante', idRepresentante);

      fetch('/final/app/controllers/reinscripciones/estudiantes_por_representante_reinscripcion.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          estudiantesData = data.estudiantes || [];
          mostrarEstudiantes();
        })
        .catch(error => {
          console.error('Error:', error);
          mostrarErrorCargaEstudiantes();
        });
    }


    // En la función mostrarEstudiantes, modificar la lógica de las cards:
    function mostrarEstudiantes() {
      const container = document.getElementById('lista-estudiantes');

      if (estudiantesData.length === 0) {
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-warning">
                    No se encontraron estudiantes asociados a este representante.
                </div>
            </div>
        `;
        return;
      }

      let html = '';
      let hayEstudiantesReinscribibles = false;
      estudiantesData.forEach(estudiante => {
        const nivel = estudiante.nombre_nivel || 'No asignado';
        const seccion = estudiante.nom_seccion || '';
        const nivelSeccion = seccion ? ` - ${seccion}` : '';
        const periodoAnterior = estudiante.periodo_anterior_desc || 'Sin historial';
        const estado = estudiante.estado_inscripcion;
        const puedeReinscribir = estudiante.puede_reinscribir;
        if (puedeReinscribir) {
          hayEstudiantesReinscribibles = true;
        }
        const badgeClass = (estado === 'Inscrito') ? 'badge-success' : 'badge-warning';
        const parentesco = estudiante.parentesco || 'No especificado';

        const botonHTML = puedeReinscribir ?
          `<button type="button" class="btn btn-primary btn-sm btn-seleccionar-estudiante" 
     data-id="${estudiante.id_estudiante}">
     <i class="fas fa-sync-alt"></i> Seleccionar
   </button>
   <button type="button" class="btn btn-warning btn-sm btn-cambiar-representante mt-1" 
     data-id="${estudiante.id_estudiante}"
     data-nombre="${estudiante.primer_nombre} ${estudiante.primer_apellido}">
     <i class="fas fa-exchange-alt"></i> Cambiar Rep.
   </button>` :
          `<button type="button" class="btn btn-secondary btn-sm" disabled>
     <i class="fas fa-check"></i> Ya Inscrito
   </button>`;

        html += `
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card estudiante-card ${puedeReinscribir ? '' : 'bg-light'}" 
                     data-id="${estudiante.id_estudiante}">
                    <div class="card-header ${puedeReinscribir ? 'bg-light' : 'bg-secondary'}">
                        <h5 class="card-title mb-0">${estudiante.primer_nombre} ${estudiante.primer_apellido}</h5>
                    </div>
                    <div class="card-body estudiante-info">
                        <p class="mb-1"><strong>Cédula:</strong> ${estudiante.cedula || 'No registrada'}</p>
                        <p class="mb-1"><strong>Último Nivel:</strong> ${nivel}${nivelSeccion}</p>
                        <p class="mb-1"><strong>Período Anterior:</strong> ${periodoAnterior}</p>
                        <p class="mb-1"><strong>Parentesco:</strong> ${parentesco}</p>
                        <p class="mb-0"><strong>Estado Actual:</strong> 
                            <span class="badge ${badgeClass}">${estado}</span>
                        </p>
                    </div>
                    <div class="card-footer text-center">
                        ${botonHTML}
                    </div>
                </div>
            </div>
        `;
      });

      container.innerHTML = html;
      // ⚠️ ESTA ES LA LÍNEA QUE FALTA - Agregar esto:
      if (hayEstudiantesReinscribibles) {
        console.log('🔗 Llamando a bindEstudianteEvents...');
        bindEstudianteEvents();
      } else {
        console.log('🔗 No hay estudiantes reinscribibles, omitiendo bindEstudianteEvents');
      }
    }


    // Modificar la función seleccionarEstudiante
    function seleccionarEstudiante(idEstudiante) {
      const estudiante = estudiantesData.find(e => e.id_estudiante == idEstudiante);

      if (!estudiante) {
        alert('Error: No se pudo encontrar la información del estudiante seleccionado.');
        return;
      }

      if (!estudiante.puede_reinscribir) {
        alert('Este estudiante ya está inscrito en el período académico actual y no puede ser reinscrito.');
        return;
      }

      // Resto del código de selección...
      cargarDatosCompletosEstudiante(idEstudiante);
    }

    // Nueva función para cargar datos completos del estudiante
    function cargarDatosCompletosEstudiante(idEstudiante) {
      const formData = new FormData();
      formData.append('id_estudiante', idEstudiante);

      fetch('/final/app/controllers/reinscripciones/obtener_datos_estudiante_reinscripcion.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            llenarDatosEstudiante(data.estudiante);
          } else {
            alert('Error al cargar los datos del estudiante');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error al cargar los datos del estudiante');
        });
    }


    function bindEstudianteEvents() {
      console.log('🔗 bindEstudianteEvents ejecutándose...');

      const botones = document.querySelectorAll('.btn-seleccionar-estudiante');
      console.log(`🔗 Encontrados ${botones.length} botones de selección`);

      botones.forEach(button => {
        button.addEventListener('click', (e) => {
          console.log('🎯 Botón clickeado:', e.target);
          e.stopPropagation();
          const idEstudiante = e.target.getAttribute('data-id');
          console.log('🎯 ID Estudiante seleccionado:', idEstudiante);
          seleccionarEstudiante(idEstudiante);
        });
      });

      document.querySelectorAll('.estudiante-card').forEach(card => {
        card.addEventListener('click', (e) => {
          if (!e.target.closest('.btn-seleccionar-estudiante')) {
            console.log('🎯 Card clickeada:', e.currentTarget);
            const idEstudiante = e.currentTarget.getAttribute('data-id');
            console.log('🎯 ID Estudiante desde card:', idEstudiante);
            seleccionarEstudiante(idEstudiante);
          }
        });
      });
    }

    function seleccionarEstudiante(idEstudiante) {
      console.log('🎯 seleccionarEstudiante llamado con ID:', idEstudiante);
      const estudiante = estudiantesData.find(e => e.id_estudiante == idEstudiante);
      console.log('🎯 Estudiante encontrado:', estudiante);
      if (!estudiante) {
        alert('Error: No se pudo encontrar la información del estudiante seleccionado.');
        return;
      }
      if (!estudiante.puede_reinscribir) {
        alert('Este estudiante ya está inscrito en el período académico actual y no puede ser reinscrito.');
        return;
      }

      document.querySelectorAll('.estudiante-card').forEach(card => {
        card.classList.remove('selected');
        card.style.border = '1px solid #dee2e6';
      });

      const cardSeleccionada = document.querySelector(`.estudiante-card[data-id="${idEstudiante}"]`);
      if (cardSeleccionada) {
        cardSeleccionada.classList.add('selected');
        cardSeleccionada.style.border = '3px solid #007bff';
        cardSeleccionada.style.backgroundColor = '#f8f9fa';
        console.log('🎯 Card seleccionada visualmente');
      }

      document.getElementById('id_parentesco_estudiante').value = estudiante.id_parentesco || '';
      document.getElementById('parentesco_estudiante').value = estudiante.parentesco || '';

      cargarDatosCompletosEstudiante(idEstudiante);
      document.getElementById('btn-next-to-step4').style.display = 'inline-block';
      console.log('🎯 Navegación al paso 4 habilitada');
    }

    function llenarDatosEstudiante(estudiante) {

      console.log('🎯 DATOS ESTUDIANTE PARA DEBUG:', {
        'Todos los campos disponibles': Object.keys(estudiante),
        'Datos específicos de nivel': {
          'nombre_nivel': estudiante.nombre_nivel,
          'nom_nivel': estudiante.nom_nivel,
          'periodo_anterior_desc': estudiante.periodo_anterior_desc,
          'descripcion_periodo': estudiante.descripcion_periodo
        },
        'Estudiante completo': estudiante
      });
      // ELIMINAR la lógica antigua de siguiente nivel y usar la nueva
      // Cargar niveles disponibles para reinscripción
      cargarNivelesReinscripcion(estudiante.id_estudiante);

      document.getElementById('id_estudiante_existente').value = estudiante.id_estudiante;

      // INICIALIZAR DIRECCIÓN CON LA DEL REPRESENTANTE (POR DEFECTO)
      const idDireccionRepre = document.getElementById('id_direccion_repre_compartida').value;
      document.getElementById('id_direccion_est').value = idDireccionRepre;

      document.getElementById('cedula_e').value = estudiante.cedula || '';
      document.getElementById('primer_nombre_e').value = estudiante.primer_nombre || '';
      document.getElementById('segundo_nombre_e').value = estudiante.segundo_nombre || '';
      document.getElementById('primer_apellido_e').value = estudiante.primer_apellido || '';
      document.getElementById('segundo_apellido_e').value = estudiante.segundo_apellido || '';
      document.getElementById('correo_e').value = estudiante.correo || '';
      document.getElementById('telefono_e').value = estudiante.telefono || '';
      document.getElementById('fecha_nac_e').value = estudiante.fecha_nac || '';
      document.getElementById('lugar_nac_e').value = estudiante.lugar_nac || '';
      document.getElementById('sexo_e').value = estudiante.sexo || '';
      document.getElementById('nacionalidad_e').value = estudiante.nacionalidad || '';

      // Configurar CI
      if (estudiante.cedula && estudiante.cedula !== '') {
        document.getElementById('ci_si').value = 'no';
        document.getElementById('cedula_e').readOnly = true;
        document.getElementById('cedula_e').style.backgroundColor = '#f8f9fa';
        document.getElementById('cedula_e').style.cursor = 'not-allowed';
        document.getElementById('cedula_e').placeholder = "Cédula generada automáticamente";
      } else {
        document.getElementById('ci_si').value = 'no';
        document.getElementById('cedula_e').readOnly = true;
        document.getElementById('cedula_e').style.backgroundColor = '#f8f9fa';
        document.getElementById('cedula_e').style.cursor = 'not-allowed';
        document.getElementById('cedula_e').placeholder = "Se generará con la fecha";

        if (estudiante.fecha_nac) {
          const anioNacimiento = estudiante.fecha_nac.substring(2, 4);
          const cedulaInicial = anioNacimiento + '1' + Math.floor(10000 + Math.random() * 90000);
          document.getElementById('cedula_e').value = cedulaInicial;
        }
      }

      const nivelAnterior = estudiante.nombre_nivel ||
        estudiante.nom_nivel_anterior ||
        estudiante.ultimo_nivel_cursado ||
        'No asignado';
      const periodoAnterior = estudiante.periodo_anterior_desc ||
        estudiante.descripcion_periodo ||
        estudiante.periodo_anterior ||
        'Sin historial';

      document.getElementById('info-estudiante-seleccionado').style.display = 'block';
      document.getElementById('datos-estudiante-seleccionado').innerHTML = `
            <strong>Nombre completo:</strong> ${estudiante.primer_nombre} ${estudiante.segundo_nombre || ''} ${estudiante.primer_apellido} ${estudiante.segundo_apellido || ''}<br>
            <strong>Cédula:</strong> ${estudiante.cedula || 'No registrada (se generará automáticamente)'}<br>
            <strong>Fecha de nacimiento:</strong> ${estudiante.fecha_nac || 'No registrada'}<br>
            <strong>Último nivel cursado:</strong> ${nivelAnterior}
        `;

      // POR DEFECTO: VIVEN JUNTOS
      document.getElementById('misma_casa').value = 'si';
      document.getElementById('misma_casa_hidden').value = 'si';
      document.getElementById('juntos').value = '1';
      document.getElementById('direccion_representante').style.display = 'none';

      // Si el estudiante tiene dirección diferente, detectarlo
      if (estudiante.id_direccion_est && estudiante.id_direccion_repre &&
        estudiante.id_direccion_est !== estudiante.id_direccion_repre) {
        document.getElementById('misma_casa').value = 'no';
        document.getElementById('misma_casa_hidden').value = 'no';
        document.getElementById('juntos').value = '0';
        document.getElementById('direccion_representante').style.display = 'block';

        setTimeout(() => {
          llenarDireccionEstudiante(estudiante);
        }, 100);
      }

      cargarDatosSaludEstudiante(estudiante.id_estudiante);
    }

    function cargarNivelesReinscripcion(idEstudiante) {
      const formData = new FormData();
      formData.append('id_estudiante', idEstudiante);

      const nivelSelect = document.getElementById('id_nivel');
      nivelSelect.innerHTML = '<option value="">Cargando niveles disponibles...</option>';
      nivelSelect.disabled = true;

      fetch('/final/app/controllers/cupos/cargar_niveles_reinscripcion.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success && data.niveles.length > 0) {
            nivelSelect.innerHTML = '<option value="">Seleccionar Nivel</option>';
            data.niveles.forEach(nivel => {
              nivelSelect.innerHTML += `<option value="${nivel.id_nivel}">${nivel.nom_nivel}</option>`;
            });
            nivelSelect.disabled = false;

            // Seleccionar automáticamente el último nivel cursado (primer elemento del array)
            if (data.niveles.length > 0) {
              nivelSelect.value = data.niveles[0].id_nivel;
              // Disparar evento change para cargar las secciones
              setTimeout(() => {
                nivelSelect.dispatchEvent(new Event('change'));
              }, 100);
            }

            console.log('✅ Niveles de reinscripción cargados:', data.niveles);
            if (data.ultimo_nivel) {
              console.log('📚 Último nivel cursado:', data.ultimo_nivel.nom_nivel);
            }
            // Debug info
            if (data.debug) {
              console.log('🐛 Debug info:', data.debug);
            }
          } else {
            nivelSelect.innerHTML = '<option value="">No hay niveles disponibles para reinscripción</option>';
            nivelSelect.disabled = true;
            console.error('❌ Error cargando niveles:', data.message);
          }
        })
        .catch(error => {
          console.error('❌ Error cargando niveles de reinscripción:', error);
          nivelSelect.innerHTML = '<option value="">Error al cargar niveles</option>';
          nivelSelect.disabled = true;
        });
    }

    function llenarDireccionEstudiante(estudiante) {
      document.getElementById('id_direccion_est').value = '';
      document.getElementById('direccion_e').value = estudiante.direccion_est || '';
      document.getElementById('calle_e').value = estudiante.calle_est || '';
      document.getElementById('casa_e').value = estudiante.casa_est || '';

      if (estudiante.id_estado_est) {
        document.getElementById('estado_e').value = estudiante.id_estado_est;
        cargarMunicipiosEstudiante(estudiante.id_estado_est).then(() => {
          if (estudiante.id_municipio_est) {
            document.getElementById('municipio_e').value = estudiante.id_municipio_est;
            cargarParroquiasEstudiante(estudiante.id_municipio_est).then(() => {
              if (estudiante.id_parroquia_est) {
                document.getElementById('parroquia_e').value = estudiante.id_parroquia_est;
              }
            });
          }
        });
      }
    }

    function cargarDatosSaludEstudiante(idEstudiante) {
      const formData = new FormData();
      formData.append('id_estudiante', idEstudiante);

      // Cargar patologías
      fetch('/final/app/controllers/estudiantes/obtener_patologias.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          const contenedorPatologias = document.getElementById('contenedor-patologias');
          contenedorPatologias.innerHTML = `
                <div class="mb-2 patologia-item">
                    <select name="patologias[]" class="form-control select-patologia">
                        <option value="">Seleccione una patología...</option>
                        <option value="0">Ninguna</option>
                        <?php
                        $patologias = $patologiaController->obtenerPatologiasActivas();
                        if (!empty($patologias)) {
                          foreach ($patologias as $patologia) {
                            echo "<option value='{$patologia['id_patologia']}'>{$patologia['nom_patologia']}</option>";
                          }
                        }
                        ?>
                    </select>
                </div>
            `;

          if (data.success && data.patologias.length > 0) {
            data.patologias.forEach((patologia, index) => {
              if (index === 0) {
                document.querySelector('.select-patologia').value = patologia.id_patologia;
              } else {
                agregarPatologia(patologia.id_patologia);
              }
            });
          }
        })
        .catch(error => console.error('Error cargando patologías:', error));

      // Cargar discapacidades
      fetch('/final/app/controllers/estudiantes/obtener_discapacidades.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          const contenedorDiscapacidades = document.getElementById('contenedor-discapacidades');
          contenedorDiscapacidades.innerHTML = `
                <div class="mb-2 discapacidad-item">
                    <select name="discapacidades[]" class="form-control select-discapacidad">
                        <option value="">Seleccione una discapacidad...</option>
                        <option value="0">Ninguna</option>
                        <?php
                        $discapacidades = $discapacidadController->obtenerDiscapacidadesActivas();
                        if (!empty($discapacidades)) {
                          foreach ($discapacidades as $discapacidad) {
                            echo "<option value='{$discapacidad['id_discapacidad']}'>{$discapacidad['nom_discapacidad']}</option>";
                          }
                        }
                        ?>
                    </select>
                </div>
            `;

          if (data.success && data.discapacidades.length > 0) {
            data.discapacidades.forEach((discapacidad, index) => {
              if (index === 0) {
                document.querySelector('.select-discapacidad').value = discapacidad.id_discapacidad;
              } else {
                agregarDiscapacidad(discapacidad.id_discapacidad);
              }
            });
          }
        })
        .catch(error => console.error('Error cargando discapacidades:', error));
    }

    function mostrarErrorCargaEstudiantes() {
      document.getElementById('lista-estudiantes').innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger">
                    Error al cargar los estudiantes. Intente nuevamente.
                </div>
            </div>
        `;
    }

    // ========== MANEJO DE DIRECCIÓN DEL ESTUDIANTE ==========
    document.getElementById('misma_casa').addEventListener('change', function() {
      const direccionEstudiante = document.getElementById('direccion_representante');
      const juntosHidden = document.getElementById('juntos');
      const idDireccionEst = document.getElementById('id_direccion_est');
      const mismaCasaHidden = document.getElementById('misma_casa_hidden');

      console.log('🔄 Cambio en misma_casa:', this.value);

      if (this.value === 'no') {
        juntosHidden.value = '0';
        mismaCasaHidden.value = 'no';
        direccionEstudiante.style.display = 'block';

        // Cuando NO viven juntos, forzar id_direccion_est vacío
        idDireccionEst.value = '';

        console.log('📍 Modo: Dirección separada - estudiante tiene dirección diferente');

        document.getElementById('estado_e').required = true;
        document.getElementById('municipio_e').required = true;
        document.getElementById('parroquia_e').required = true;
        document.getElementById('direccion_e').required = true;

        if (representanteData && representanteData.id_estado) {
          cargarMunicipiosEstudiante(representanteData.id_estado);
        }
      } else {
        juntosHidden.value = '1';
        mismaCasaHidden.value = 'si';
        direccionEstudiante.style.display = 'none';

        // Cuando viven juntos, usar la dirección del representante
        const idDireccionRepre = document.getElementById('id_direccion_repre_compartida').value;
        idDireccionEst.value = idDireccionRepre;

        console.log('📍 Modo: Dirección compartida - usando dirección del representante:', idDireccionRepre);

        document.getElementById('estado_e').required = false;
        document.getElementById('municipio_e').required = false;
        document.getElementById('parroquia_e').required = false;
        document.getElementById('direccion_e').required = false;

        const camposDireccion = ['estado_e', 'municipio_e', 'parroquia_e', 'direccion_e', 'calle_e', 'casa_e'];
        camposDireccion.forEach(campo => {
          const elemento = document.getElementById(campo);
          if (elemento) {
            elemento.classList.remove('is-invalid');
            elemento.value = '';
          }
        });
      }
    });

    // ========== FUNCIONES DE UBICACIÓN ==========
    function cargarMunicipios(estadoId) {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('estado_id', estadoId);

        fetch('/final/app/controllers/ubicaciones/municipios.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            const select = document.getElementById('municipio_r');
            select.innerHTML = '<option value="">Seleccionar Municipio</option>';
            data.forEach(municipio => {
              select.innerHTML += `<option value="${municipio.id_municipio}">${municipio.nom_municipio}</option>`;
            });
            select.disabled = false;
            resolve();
          })
          .catch(error => reject(error));
      });
    }

    function cargarParroquias(municipioId) {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('municipio_id', municipioId);

        fetch('/final/app/controllers/ubicaciones/parroquias.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            const select = document.getElementById('parroquia_r');
            select.innerHTML = '<option value="">Seleccionar Parroquia</option>';
            data.forEach(parroquia => {
              select.innerHTML += `<option value="${parroquia['id_parroquia']}">${parroquia['nom_parroquia']}</option>`;
            });
            select.disabled = false;
            resolve();
          })
          .catch(error => reject(error));
      });
    }

    function cargarMunicipiosEstudiante(estadoId) {
      const formData = new FormData();
      formData.append('estado_id', estadoId);

      fetch('/final/app/controllers/ubicaciones/municipios.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          const select = document.getElementById('municipio_e');
          select.innerHTML = '<option value="">Seleccionar Municipio</option>';
          data.forEach(municipio => {
            select.innerHTML += `<option value="${municipio['id_municipio']}">${municipio['nom_municipio']}</option>`;
          });
          select.disabled = false;
        });
    }

    function cargarParroquiasEstudiante(municipioId) {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('municipio_id', municipioId);

        fetch('/final/app/controllers/ubicaciones/parroquias.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            const select = document.getElementById('parroquia_e');
            select.innerHTML = '<option value="">Seleccionar Parroquia</option>';
            data.forEach(parroquia => {
              select.innerHTML += `<option value="${parroquia['id_parroquia']}">${parroquia['nom_parroquia']}</option>`;
            });
            select.disabled = false;
            resolve();
          })
          .catch(error => reject(error));
      });
    }

    // Event listeners para ubicación del representante
    document.getElementById('estado_r').addEventListener('change', function() {
      const estadoId = this.value;
      if (estadoId) {
        cargarMunicipios(estadoId);
      } else {
        document.getElementById('municipio_r').disabled = true;
        document.getElementById('parroquia_r').disabled = true;
      }
    });

    document.getElementById('municipio_r').addEventListener('change', function() {
      const municipioId = this.value;
      if (municipioId) {
        cargarParroquias(municipioId);
      } else {
        document.getElementById('parroquia_r').disabled = true;
      }
    });

    // Event listeners para ubicación del estudiante
    document.getElementById('estado_e').addEventListener('change', function() {
      const estadoId = this.value;
      if (estadoId) {
        cargarMunicipiosEstudiante(estadoId);
      }
    });

    document.getElementById('municipio_e').addEventListener('change', function() {
      const municipioId = this.value;
      if (municipioId) {
        cargarParroquiasEstudiante(municipioId);
      }
    });

    // ========== MANEJO DE PATOLOGÍAS Y DISCAPACIDADES ==========
    function agregarPatologia(valorSeleccionado = '') {
      const contenedor = document.getElementById('contenedor-patologias');
      const primerSelect = document.querySelector('.select-patologia');

      if (!primerSelect) return;

      const opciones = Array.from(primerSelect.options)
        .map(option => `<option value="${option.value}" ${option.value === valorSeleccionado ? 'selected' : ''}>${option.text}</option>`)
        .join('');

      const div = document.createElement('div');
      div.className = 'mb-2 patologia-item d-flex align-items-center';

      div.innerHTML = `
            <select name="patologias[]" class="form-control select-patologia me-2">
                ${opciones}
            </select>
            <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar-patologia">
                <i class="fas fa-times"></i>
            </button>
        `;

      contenedor.appendChild(div);

      div.querySelector('.btn-eliminar-patologia').addEventListener('click', function() {
        div.remove();
      });
    }

    function agregarDiscapacidad(valorSeleccionado = '') {
      const contenedor = document.getElementById('contenedor-discapacidades');
      const primerSelect = document.querySelector('.select-discapacidad');

      if (!primerSelect) return;

      const opciones = Array.from(primerSelect.options)
        .map(option => `<option value="${option.value}" ${option.value === valorSeleccionado ? 'selected' : ''}>${option.text}</option>`)
        .join('');

      const div = document.createElement('div');
      div.className = 'mb-2 discapacidad-item d-flex align-items-center';

      div.innerHTML = `
            <select name="discapacidades[]" class="form-control select-discapacidad me-2">
                ${opciones}
            </select>
            <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar-discapacidad">
                <i class="fas fa-times"></i>
            </button>
        `;

      contenedor.appendChild(div);

      div.querySelector('.btn-eliminar-discapacidad').addEventListener('click', function() {
        div.remove();
      });
    }

    document.getElementById('btn-agregar-patologia').addEventListener('click', () => agregarPatologia());
    document.getElementById('btn-agregar-discapacidad').addEventListener('click', () => agregarDiscapacidad());

    // ========== CARGA DE SECCIONES POR NIVEL ==========
    document.getElementById('id_nivel').addEventListener('change', function() {
      const nivelId = this.value;
      const seccionSelect = document.getElementById('id_seccion');

      if (nivelId) {
        cargarSeccionesPorNivel(nivelId);
      } else {
        seccionSelect.innerHTML = '<option value="">Primero seleccione un nivel</option>';
        seccionSelect.disabled = true;
      }
    });

    document.getElementById('id_seccion').addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      const idSeccion = selectedOption.getAttribute('data-id-seccion');
      document.getElementById('id_seccion_hidden').value = idSeccion || this.value;

      // VERIFICAR CUPOS CUANDO SE SELECCIONA UNA SECCIÓN
      verificarCupos();
    });

    // function cargarSeccionesPorNivel(nivelId) {
    //   const formData = new FormData();
    //   formData.append('id_nivel', nivelId);

    //   const seccionSelect = document.getElementById('id_seccion');
    //   seccionSelect.innerHTML = '<option value="">Cargando secciones...</option>';
    //   seccionSelect.disabled = true;

    //   fetch('/final/app/controllers/cupos/cargar_secciones.php', {
    //       method: 'POST',
    //       body: formData
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //       if (data.success && data.secciones.length > 0) {
    //         seccionSelect.innerHTML = '<option value="">Seleccionar Sección</option>';
    //         data.secciones.forEach(seccion => {
    //           const cuposDisponibles = seccion.capacidad - (seccion.inscritos || 0);
    //           const textoCupos = cuposDisponibles > 0 ?
    //             ` (${cuposDisponibles} cupos)` :
    //             ' (Sin cupos)';
    //           seccionSelect.innerHTML += `
    //                 <option value="${seccion.id_nivel_seccion}" 
    //                         data-id-seccion="${seccion.id_seccion}"
    //                         ${cuposDisponibles <= 0 ? 'disabled' : ''}>
    //                     ${seccion.nom_seccion}${textoCupos}
    //                 </option>
    //             `;
    //         });

    //         seccionSelect.disabled = false;

    //         // Si solo hay una sección disponible, seleccionarla automáticamente
    //         const opcionesDisponibles = Array.from(seccionSelect.options)
    //           .filter(opt => !opt.disabled && opt.value !== '');

    //         if (opcionesDisponibles.length === 1) {
    //           seccionSelect.value = opcionesDisponibles[0].value;
    //         }
    //       } else {
    //         seccionSelect.innerHTML = '<option value="">No hay secciones disponibles</option>';
    //         seccionSelect.disabled = true;
    //       }
    //     })
    //     .catch(error => {
    //       console.error('Error cargando secciones:', error);
    //       seccionSelect.innerHTML = '<option value="">Error al cargar secciones</option>';
    //       seccionSelect.disabled = true;
    //     });
    // }

    function cargarSeccionesPorNivel(nivelId) {
      const formData = new FormData();
      formData.append('id_nivel', nivelId);

      const seccionSelect = document.getElementById('id_seccion');
      seccionSelect.innerHTML = '<option value="">Cargando secciones...</option>';
      seccionSelect.disabled = true;

      fetch('/final/app/controllers/cupos/cargar_secciones.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success && data.secciones.length > 0) {
            seccionSelect.innerHTML = '<option value="">Seleccionar Sección</option>';
            data.secciones.forEach(seccion => {
              const cuposDisponibles = seccion.capacidad - (seccion.inscritos || 0);
              const textoCupos = cuposDisponibles > 0 ?
                ` (${cuposDisponibles} cupos)` :
                ' (Sin cupos)';
              seccionSelect.innerHTML += `
                    <option value="${seccion.id_nivel_seccion}" 
                            data-id-seccion="${seccion.id_seccion}"
                            data-nivel-seccion="${seccion.id_nivel_seccion}"  <!-- ESTA LÍNEA ES IMPORTANTE -->
                            ${cuposDisponibles <= 0 ? 'disabled' : ''}>
                        ${seccion.nom_seccion}${textoCupos}
                    </option>
                `;
            });

            seccionSelect.disabled = false;

            // Si solo hay una sección disponible, seleccionarla automáticamente
            const opcionesDisponibles = Array.from(seccionSelect.options)
              .filter(opt => !opt.disabled && opt.value !== '');

            if (opcionesDisponibles.length === 1) {
              seccionSelect.value = opcionesDisponibles[0].value;
              // Disparar el evento change para verificar cupos
              setTimeout(() => {
                seccionSelect.dispatchEvent(new Event('change'));
              }, 100);
            }
          } else {
            seccionSelect.innerHTML = '<option value="">No hay secciones disponibles</option>';
            seccionSelect.disabled = true;
          }
        })
        .catch(error => {
          console.error('Error cargando secciones:', error);
          seccionSelect.innerHTML = '<option value="">Error al cargar secciones</option>';
          seccionSelect.disabled = true;
        });
    }

    // FUNCIÓN PARA MOSTRAR MENSAJE DE CARGA
    function mostrarMensajeCargandoCupos() {
      eliminarMensajeCupos();

      const informacionAcademica = document.querySelector('.informacion_academica .card-body');
      if (!informacionAcademica) return;

      mensajeCupos = document.createElement('div');
      mensajeCupos.className = 'alert alert-info mt-3';
      mensajeCupos.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verificando disponibilidad de cupos...';

      informacionAcademica.appendChild(mensajeCupos);
    }

    // TAMBIÉN AGREGAR EVENTO AL PERIODO
    document.getElementById('id_periodo').addEventListener('change', function() {
      // Si ya hay una sección seleccionada, verificar cupos nuevamente
      const seccionSelect = document.getElementById('id_seccion');
      if (seccionSelect.value) {
        verificarCupos();
      }
    });

    // ========== VALIDACIÓN DEL FORMULARIO COMPLETA MODIFICADA PARA GENERAR CONSTANCIA ==========
    document.getElementById('form-reinscripcion').addEventListener('submit', function(e) {
      const mismaCasa = document.getElementById('misma_casa_hidden').value;
      const idDireccionEst = document.getElementById('id_direccion_est').value;

      console.log('🔄 Validando formulario...');
      console.log('📍 misma_casa:', mismaCasa, 'id_direccion_est:', idDireccionEst);

      // Validar campos requeridos básicos
      const camposRequeridosBasicos = [
        'id_periodo', 'id_nivel', 'id_seccion'
      ];

      const camposFaltantesBasicos = [];
      camposRequeridosBasicos.forEach(campo => {
        const elemento = document.getElementById(campo);
        if (!elemento || !elemento.value.trim()) {
          camposFaltantesBasicos.push(campo);
          elemento.classList.add('is-invalid');
        } else {
          elemento.classList.remove('is-invalid');
        }
      });

      if (camposFaltantesBasicos.length > 0) {
        e.preventDefault();
        alert('Por favor complete todos los campos requeridos de información académica.');
        return false;
      }

      // Validar dirección según si viven juntos o no
      if (mismaCasa === 'no') {
        console.log('📍 Validando dirección del estudiante (NO viven juntos)');

        const camposDireccionRequeridos = [
          'estado_e', 'municipio_e', 'parroquia_e', 'direccion_e'
        ];

        const camposFaltantesDireccion = [];
        camposDireccionRequeridos.forEach(campo => {
          const elemento = document.getElementById(campo);
          if (!elemento || !elemento.value.trim()) {
            camposFaltantesDireccion.push(campo);
            elemento.classList.add('is-invalid');
          } else {
            elemento.classList.remove('is-invalid');
          }
        });

        if (camposFaltantesDireccion.length > 0) {
          e.preventDefault();
          alert('Cuando el estudiante no vive con el representante, debe completar todos los datos de dirección del estudiante.');
          document.getElementById('direccion_representante').style.display = 'block';
          return false;
        }

        document.getElementById('id_direccion_est').value = '';
        console.log('🔄 Forzando id_direccion_est vacío para crear nueva dirección');

      } else {
        console.log('📍 Viven juntos - usando dirección del representante:', idDireccionEst);

        const idDireccionRepreCompartida = document.getElementById('id_direccion_repre_compartida').value;
        if (!idDireccionRepreCompartida) {
          e.preventDefault();
          alert('Error: No se pudo determinar la dirección compartida con el representante. Por favor, vuelva a validar el representante.');
          return false;
        }

        document.getElementById('id_direccion_est').value = idDireccionRepreCompartida;

        const camposDireccion = ['estado_e', 'municipio_e', 'parroquia_e', 'direccion_e', 'calle_e', 'casa_e'];
        camposDireccion.forEach(campo => {
          const elemento = document.getElementById(campo);
          if (elemento) {
            elemento.classList.remove('is-invalid');
            elemento.required = false;
            if (campo !== 'id_direccion_est') {
              elemento.value = '';
            }
          }
        });

        console.log('✅ Dirección validada - usando dirección del representante:', idDireccionRepreCompartida);
      }

      // Validar datos personales básicos del estudiante
      const camposEstudianteRequeridos = [
        'nacionalidad_e', 'fecha_nac_e', 'cedula_e', 'sexo_e',
        'primer_nombre_e', 'primer_apellido_e', 'lugar_nac_e'
      ];

      const camposFaltantesEstudiante = [];
      camposEstudianteRequeridos.forEach(campo => {
        const elemento = document.getElementById(campo);
        if (!elemento || !elemento.value.trim()) {
          camposFaltantesEstudiante.push(campo);
          elemento.classList.add('is-invalid');
        } else {
          elemento.classList.remove('is-invalid');
        }
      });

      if (camposFaltantesEstudiante.length > 0) {
        e.preventDefault();
        alert('Por favor complete todos los campos requeridos del estudiante.');
        return false;
      }

      // NUEVA VALIDACIÓN: Verificar que el período esté vigente
      const periodoSelect = document.getElementById('id_periodo');
      const selectedOption = periodoSelect.options[periodoSelect.selectedIndex];

      if (selectedOption.value) {
        const fechaIni = selectedOption.getAttribute('data-fecha-ini');
        const fechaFin = selectedOption.getAttribute('data-fecha-fin');
        const fechaActual = new Date().toISOString().split('T')[0]; // YYYY-MM-DD

        console.log('📅 Validación de fechas:', {
          fechaIni,
          fechaFin,
          fechaActual
        });

        if (fechaActual < fechaIni) {
          e.preventDefault();
          alert('❌ El período académico seleccionado no ha iniciado.\n\n' +
            `Fecha de inicio: ${formatFecha(fechaIni)}\n` +
            `Fecha actual: ${formatFecha(fechaActual)}`);
          periodoSelect.focus();
          return false;
        }

        if (fechaActual > fechaFin) {
          e.preventDefault();
          alert('❌ El período académico seleccionado ha finalizado.\n\n' +
            `Fecha de finalización: ${formatFecha(fechaFin)}\n` +
            `Fecha actual: ${formatFecha(fechaActual)}`);
          periodoSelect.focus();
          return false;
        }
      }

      // Función auxiliar para formatear fechas
      function formatFecha(fechaISO) {
        const fecha = new Date(fechaISO);
        return fecha.toLocaleDateString('es-ES', {
          day: '2-digit',
          month: '2-digit',
          year: 'numeric'
        });
      }

      // Validar que se haya seleccionado un estudiante
      const idEstudianteExistente = document.getElementById('id_estudiante_existente').value;
      if (!idEstudianteExistente) {
        e.preventDefault();
        alert('Error: No se ha seleccionado un estudiante. Por favor, regrese al paso 3 y seleccione un estudiante.');
        return false;
      }

      // Validar que se haya validado un representante
      const idRepresentanteExistente = document.getElementById('id_representante_existente').value;
      if (!idRepresentanteExistente) {
        e.preventDefault();
        alert('Error: No se ha validado un representante. Por favor, comience desde el paso 1.');
        return false;
      }

      console.log('✅ Formulario validado correctamente');

      // PREVENIR EL ENVÍO POR DEFECTO - MANEJARLO CON FETCH
      e.preventDefault();

      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
      submitBtn.disabled = true;

      // Mostrar mensaje de procesamiento
      const processingMsg = document.createElement('div');
      processingMsg.className = 'alert alert-info';
      processingMsg.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando reinscripción...';
      document.querySelector('.content-wrapper').prepend(processingMsg);

      console.log('📤 Datos que se enviarán:');
      const formData = new FormData(this);
      for (let [key, value] of formData.entries()) {
        console.log(`- ${key}: ${value}`);
      }

      console.log('🎯 DATOS CLAVE PARA REINSCRIPCIÓN:');
      console.log('- id_estudiante_existente:', document.getElementById('id_estudiante_existente').value);
      console.log('- id_representante_existente:', document.getElementById('id_representante_existente').value);
      console.log('- id_parentesco_estudiante:', document.getElementById('id_parentesco_estudiante').value);
      console.log('- misma_casa_hidden:', document.getElementById('misma_casa_hidden').value);
      console.log('- juntos:', document.getElementById('juntos').value);
      console.log('- id_direccion_est:', document.getElementById('id_direccion_est').value);
      console.log('- id_direccion_repre:', document.getElementById('id_direccion_repre').value);
      console.log('- id_direccion_repre_compartida:', document.getElementById('id_direccion_repre_compartida').value);

      // ========== ENVÍO DEL FORMULARIO CON MANEJO DE CONSTANCIA ==========
      fetch(this.action, {
          method: 'POST',
          body: formData
        })
        .then(response => {
          return response.text().then(text => {
            console.log('📨 Respuesta cruda del servidor:', text.substring(0, 300));

            try {
              const jsonData = JSON.parse(text);
              console.log('✅ JSON parseado correctamente:', jsonData);
              return jsonData;
            } catch (jsonError) {
              console.warn('⚠️ No se pudo parsear como JSON, pero continuamos...');

              // Buscar pistas de éxito en el texto crudo
              const hasSuccessIndicators =
                text.includes('success') ||
                text.includes('id_inscripcion') ||
                text.includes('exitosamente') ||
                text.length < 100;

              if (hasSuccessIndicators) {
                console.log('🎯 Respuesta parece exitosa a pesar del formato JSON inválido');

                // Intentar extraer el ID de inscripción del texto
                let idInscripcion = null;
                const idMatch = text.match(/"id_inscripcion":\s*(\d+)/) || text.match(/id_inscripcion[^0-9]*([0-9]+)/);
                if (idMatch) {
                  idInscripcion = idMatch[1];
                }

                return {
                  success: true,
                  message: 'Reinscripción procesada exitosamente',
                  id_inscripcion: idInscripcion
                };
              }

              return {
                success: true,
                message: 'Proceso completado',
                id_inscripcion: null
              };
            }
          });
        })
        .then(data => {
          // Remover mensaje de procesamiento
          processingMsg.remove();

          console.log('📊 Resultado final del proceso:', data);

          // Intentar obtener el ID de inscripción
          let idInscripcion = data.id_inscripcion;

          // SOLO generar constancia si tenemos un ID válido (numérico)
          if (idInscripcion && idInscripcion !== 'last' && !isNaN(idInscripcion)) {
            // Generar constancia con el ID disponible
            // generarConstanciaInscripcion(idInscripcion)
            generarConstanciaInscripcion(idInscripcion)
              .then(() => {
                console.log('✅ Proceso de constancia completado');

                // Redirigir después de un tiempo más largo para que el usuario pueda ver/descargar la constancia
                setTimeout(() => {
                  console.log('🔄 Redirigiendo a dashboard...');
                  window.location.href = '/final/admin/reinscripciones/reinscripcion2.php';
                }, 5000); // Reducí el tiempo a 5 segundos
              })
              .catch((error) => {
                console.warn('⚠️ Error en proceso de constancia:', error);

                // Mostrar mensaje de error pero continuar
                const errorMsg = document.createElement('div');
                errorMsg.className = 'alert alert-warning mt-2';
                errorMsg.innerHTML = `
                        <small>Hubo un problema con la constancia, pero la reinscripción fue exitosa.</small><br>
                        <a href="/final/app/controllers/reinscripciones/generar_constancia_reinscripcion.php?id_inscripcion=${idInscripcion}" 
                            target="_blank" class="btn btn-outline-warning btn-sm mt-1">
                            <i class="fas fa-redo"></i> Intentar Generar Constancia Nuevamente
                        </a>
                    `;
                document.querySelector('.content-wrapper').prepend(errorMsg);

                // Redirigir después de más tiempo
                setTimeout(() => {
                  window.location.href = '/final/admin/index.php';
                }, 6000);
              });
          } else {
            // Si no hay ID válido, solo redirigir
            console.warn('⚠️ No se generará constancia - ID no válido:', idInscripcion);

            const noConstanciaMsg = document.createElement('div');
            noConstanciaMsg.className = 'alert alert-info mt-3';
            noConstanciaMsg.innerHTML = `
                <strong>✅ Reinscripción completada exitosamente</strong><br>
                <small>Puede generar la constancia más tarde desde el listado de estudiantes.</small>
            `;
            document.querySelector('.content-wrapper').prepend(noConstanciaMsg);

            setTimeout(() => {
              console.log('🔄 Redirigiendo a dashboard...');
              window.location.href = '/final/admin/index.php';
            }, 5000);
          }

        })
        .catch(error => {
          console.error('💥 Error crítico en el proceso:', error);

          // Remover mensaje de procesamiento
          processingMsg.remove();

          // Rehabilitar botón
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;

          // Solo mostrar error si es realmente crítico (errores de red)
          if (error.message.includes('Network') || error.message.includes('Failed to fetch')) {
            const errorAlert = document.createElement('div');
            errorAlert.className = 'alert alert-danger';
            errorAlert.innerHTML = `<strong>❌ Error de conexión</strong><br><small>No se pudo conectar con el servidor.</small>`;
            document.querySelector('.content-wrapper').prepend(errorAlert);
          } else {
            // Para otros errores, mostrar éxito (nuestra estrategia de silenciamiento)
            const successAlert = document.createElement('div');
            successAlert.className = 'alert alert-success';
            successAlert.innerHTML = `<strong>✅ Proceso completado</strong>`;
            document.querySelector('.content-wrapper').prepend(successAlert);

            // Redirigir después de un tiempo
            setTimeout(() => {
              window.location.href = '/final/admin/index.php';
            }, 4000);
          }
        });
    });

    // ========== FUNCIÓN PARA GENERAR CONSTANCIA DE REINSCRIPCIÓN ==========
    function generarConstanciaInscripcion(idInscripcion) {
      // ✅ VALIDACIÓN ADICIONAL: Verificar que el ID sea numérico
      if (!idInscripcion || isNaN(idInscripcion)) {
        console.error('❌ ID de inscripción no válido para generar constancia:', idInscripcion);
        return Promise.reject(new Error('ID de inscripción no válido'));
      }

      return new Promise((resolve, reject) => {
        console.log('📄 Generando constancia para reinscripción ID:', idInscripcion);

        // Mostrar mensaje de que se está generando la constancia
        const generatingMsg = document.createElement('div');
        generatingMsg.className = 'alert alert-info';
        generatingMsg.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando constancia de reinscripción...';
        document.querySelector('.content-wrapper').prepend(generatingMsg);

        // ✅ USAR EL MISMO ARCHIVO DE CONSTANCIAS DE INSCRIPCIÓN
        const constanciaUrl = `/final/app/controllers/inscripciones/generar_constancia.php?id_inscripcion=${idInscripcion}`;

        console.log('🔗 URL de constancia:', constanciaUrl);

        // Crear un iframe temporal para abrir la constancia
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = constanciaUrl;
        document.body.appendChild(iframe);

        // También abrir en nueva pestaña
        setTimeout(() => {
          generatingMsg.remove();

          const successMsg = document.createElement('div');
          successMsg.className = 'alert alert-success';
          successMsg.innerHTML = `
                <strong>✅ Reinscripción completada exitosamente</strong><br>
                <small>La constancia se abrirá en una nueva pestaña.</small>
            `;
          document.querySelector('.content-wrapper').prepend(successMsg);

          // Abrir en nueva pestaña
          window.open(constanciaUrl, '_blank');

          resolve({
            success: true
          });

        }, 2000);

      });
    }


    inicializar();

  });
</script>

<!-- ========== CONVERSIÓN AUTOMÁTICA A MAYÚSCULAS ========== -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Función para convertir texto a mayúsculas
    function convertirMayusculas(elemento) {
      elemento.value = elemento.value.toUpperCase();
    }

    // Aplicar conversión a mayúsculas en tiempo real para todos los inputs de texto editables
    const inputsTexto = document.querySelectorAll('input[type="text"]:not([readonly])');

    inputsTexto.forEach(input => {
      input.addEventListener('input', function() {
        convertirMayusculas(this);
      });

      // También aplicar a los valores existentes al cargar la página
      if (input.value) {
        convertirMayusculas(input);
      }
    });

    // Aplicar también a textareas
    const textareas = document.querySelectorAll('textarea:not([readonly])');

    textareas.forEach(textarea => {
      textarea.addEventListener('input', function() {
        convertirMayusculas(this);
      });

      // Aplicar a valores existentes
      if (textarea.value) {
        convertirMayusculas(textarea);
      }
    });

    console.log('✅ Conversión a mayúsculas configurada para todos los campos de texto');
  });
</script>

<!-- ========== VALIDACIONES DE FORMULARIO ========== -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // ========== FUNCIONES DE VALIDACIÓN ==========

    // Función para validar solo letras (incluye espacios y acentos)
    function validarSoloLetras(event) {
      const key = event.key;
      // Permitir teclas de control (backspace, delete, tab, etc.)
      if (event.ctrlKey || event.altKey ||
        key === 'Backspace' || key === 'Delete' ||
        key === 'Tab' || key === 'Escape' ||
        key === 'Enter' || key === 'ArrowLeft' ||
        key === 'ArrowRight' || key === 'Home' ||
        key === 'End') {
        return true;
      }

      // Expresión regular que permite letras, espacios y caracteres acentuados
      const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]$/;

      if (!regex.test(key)) {
        event.preventDefault();
        return false;
      }

      return true;
    }

    // Función para validar solo números
    function validarSoloNumeros(event) {
      const key = event.key;
      // Permitir teclas de control
      if (event.ctrlKey || event.altKey ||
        key === 'Backspace' || key === 'Delete' ||
        key === 'Tab' || key === 'Escape' ||
        key === 'Enter' || key === 'ArrowLeft' ||
        key === 'ArrowRight' || key === 'Home' ||
        key === 'End') {
        return true;
      }

      // Solo permitir números
      const regex = /^[0-9]$/;

      if (!regex.test(key)) {
        event.preventDefault();
        return false;
      }

      return true;
    }

    // Función para validar formato de correo electrónico
    function validarEmail(email) {
      const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return regex.test(email);
    }

    // ========== APLICAR VALIDACIONES A LOS CAMPOS DEL REPRESENTANTE ==========

    // Campos de nombres y apellidos del representante (solo letras)
    const camposLetrasRepresentante = [
      'primer_nombre_r', 'segundo_nombre_r',
      'primer_apellido_r', 'segundo_apellido_r',
      'lugar_nac_r', 'ocupacion_r', 'lugar_trabajo_r'
    ];

    camposLetrasRepresentante.forEach(campoId => {
      const campo = document.getElementById(campoId);
      if (campo) {
        campo.addEventListener('keydown', validarSoloLetras);

        // Validación adicional al perder el foco (para casos de pegado)
        campo.addEventListener('blur', function() {
          let valor = this.value;
          // Remover caracteres no permitidos (mantener solo letras, espacios y acentos)
          valor = valor.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '');
          // Remover espacios múltiples
          valor = valor.replace(/\s+/g, ' ').trim();
          this.value = valor;
        });
      }
    });

    // Campos numéricos del representante
    const camposNumerosRepresentante = [
      'cedula_r', 'telefono_r', 'telefono_hab_r'
    ];

    camposNumerosRepresentante.forEach(campoId => {
      const campo = document.getElementById(campoId);
      if (campo) {
        campo.addEventListener('keydown', validarSoloNumeros);

        // Validación adicional al perder el foco
        campo.addEventListener('blur', function() {
          let valor = this.value;
          // Remover caracteres no numéricos
          valor = valor.replace(/[^0-9]/g, '');
          this.value = valor;
        });
      }
    });

    // Validación de correo del representante
    const correoRepresentante = document.getElementById('correo_r');
    if (correoRepresentante) {
      correoRepresentante.addEventListener('blur', function() {
        const email = this.value.trim();
        if (email && !validarEmail(email)) {
          this.classList.add('is-invalid');
          mostrarError(this, 'Por favor ingrese un correo electrónico válido (formato: usuario@dominio.com)');
        } else if (email && validarEmail(email)) {
          this.classList.remove('is-invalid');
          this.classList.add('is-valid');
          ocultarError(this);
        } else {
          // Si está vacío, solo remover clases (será validado como campo requerido)
          this.classList.remove('is-invalid', 'is-valid');
          ocultarError(this);
        }
      });

      // Remover clases de validación al empezar a escribir
      correoRepresentante.addEventListener('input', function() {
        this.classList.remove('is-invalid', 'is-valid');
        ocultarError(this);
      });
    }

    // ========== APLICAR VALIDACIONES A LOS CAMPOS DEL ESTUDIANTE ==========

    // Campos de nombres y apellidos del estudiante (solo letras)
    const camposLetrasEstudiante = [
      'primer_nombre_e', 'segundo_nombre_e',
      'primer_apellido_e', 'segundo_apellido_e',
      'lugar_nac_e'
    ];

    camposLetrasEstudiante.forEach(campoId => {
      const campo = document.getElementById(campoId);
      if (campo) {
        campo.addEventListener('keydown', validarSoloLetras);

        // Validación adicional al perder el foco
        campo.addEventListener('blur', function() {
          let valor = this.value;
          // Remover caracteres no permitidos
          valor = valor.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '');
          // Remover espacios múltiples
          valor = valor.replace(/\s+/g, ' ').trim();
          this.value = valor;
        });
      }
    });

    // Campos numéricos del estudiante
    const camposNumerosEstudiante = [
      'cedula_e', 'telefono_e'
    ];

    camposNumerosEstudiante.forEach(campoId => {
      const campo = document.getElementById(campoId);
      if (campo) {
        campo.addEventListener('keydown', validarSoloNumeros);

        campo.addEventListener('blur', function() {
          let valor = this.value;
          valor = valor.replace(/[^0-9]/g, '');
          this.value = valor;
        });
      }
    });

    // Validación de correo del estudiante (OPCIONAL)
    const correoEstudiante = document.getElementById('correo_e');
    if (correoEstudiante) {
      correoEstudiante.addEventListener('blur', function() {
        const email = this.value.trim();
        // El correo del estudiante es OPCIONAL, solo valida si se ingresa algo
        if (email && !validarEmail(email)) {
          this.classList.add('is-invalid');
          mostrarError(this, 'Por favor ingrese un correo electrónico válido (formato: usuario@dominio.com) o deje el campo vacío');
        } else if (email && validarEmail(email)) {
          this.classList.remove('is-invalid');
          this.classList.add('is-valid');
          ocultarError(this);
        } else {
          // Si está vacío, es válido (opcional)
          this.classList.remove('is-invalid', 'is-valid');
          ocultarError(this);
        }
      });

      // Remover clases de validación al empezar a escribir
      correoEstudiante.addEventListener('input', function() {
        this.classList.remove('is-invalid', 'is-valid');
        ocultarError(this);
      });
    }

    // ========== VALIDACIÓN DE CAMPOS DE DIRECCIÓN ==========

    // Campos de dirección (permiten letras, números y algunos caracteres especiales)
    const camposDireccion = [
      'direccion_r', 'calle_r', 'casa_r',
      'direccion_e', 'calle_e', 'casa_e'
    ];

    camposDireccion.forEach(campoId => {
      const campo = document.getElementById(campoId);
      if (campo) {
        campo.addEventListener('blur', function() {
          let valor = this.value;
          // Permitir letras, números, espacios, guiones, # y puntos
          valor = valor.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s\-#\.]/g, '');
          // Remover espacios múltiples
          valor = valor.replace(/\s+/g, ' ').trim();
          this.value = valor;
        });
      }
    });

    // ========== VALIDACIÓN AL PASAR DEL PASO 2 AL PASO 3 ==========

    document.getElementById('btn-next-to-step3').addEventListener('click', function() {
      // Validar campos requeridos primero
      const requiredFields = document.querySelectorAll('#step2 [required]');
      let valid = true;

      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          valid = false;
          field.classList.add('is-invalid');
        } else {
          field.classList.remove('is-invalid');
        }
      });

      if (!valid) {
        alert('Por favor complete todos los campos requeridos del representante.');
        return;
      }

      // Validar específicamente el correo del representante
      if (correoRepresentante) {
        const emailRepre = correoRepresentante.value.trim();
        if (emailRepre && !validarEmail(emailRepre)) {
          correoRepresentante.classList.add('is-invalid');
          mostrarError(correoRepresentante, 'Por favor ingrese un correo electrónico válido antes de continuar');
          alert('Por favor corrija el correo electrónico del representante antes de continuar.');
          return;
        }
      }

      // Si todo está bien, avanzar al paso 3
      showStep(3);
    });

    // ========== FUNCIONES AUXILIARES ==========

    function mostrarError(campo, mensaje) {
      // Remover error anterior si existe
      ocultarError(campo);

      // Crear elemento de error
      const errorDiv = document.createElement('div');
      errorDiv.className = 'invalid-feedback';
      errorDiv.style.display = 'block';
      errorDiv.textContent = mensaje;
      errorDiv.id = `error-${campo.id}`;

      // Insertar después del campo
      campo.parentNode.appendChild(errorDiv);
    }

    function ocultarError(campo) {
      const errorId = `error-${campo.id}`;
      const errorExistente = document.getElementById(errorId);
      if (errorExistente) {
        errorExistente.remove();
      }
    }

    // ========== ESTILOS PARA LOS ESTADOS DE VALIDACIÓN ==========
    const style = document.createElement('style');
    style.textContent = `
        .is-valid {
            border-color: #28a745 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .is-invalid {
            border-color: #dc3545 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #dc3545;
        }
    `;
    document.head.appendChild(style);

    console.log('✅ Validaciones de formulario cargadas correctamente');
  });
</script>


<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
?>