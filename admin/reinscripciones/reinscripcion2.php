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
include_once("/xampp/htdocs/final/app/controllers/parentesco/parentesco.php");
include_once("/xampp/htdocs/final/app/controllers/patologias/patologias.php");
include_once("/xampp/htdocs/final/app/controllers/discapacidades/discapacidades.php");
include_once("/xampp/htdocs/final/app/controllers/cupos/cupos.php");
include_once("/xampp/htdocs/final/app/conexion.php");

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $inscripcionesController = new InscripcionController($pdo);
  $periodos = $inscripcionesController->obtenerPeriodosActivos();
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

                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="nacionalidad_r">Nacionalidad</label>
                        <select name="nacionalidad_r" id="nacionalidad_r" class="form-control" required>
                          <option value="">Seleccionar</option>
                          <option value="Venezolano">Venezolano</option>
                          <option value="Extranjero">Extranjero</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="cedula_r">Cédula de Identidad</label>
                        <input type="number" name="cedula_r" id="cedula_r" class="form-control" required readonly>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="sexo_r">Sexo</label>
                        <select name="sexo_r" id="sexo_r" class="form-control" required>
                          <option value="">Seleccionar</option>
                          <option value="Masculino">Masculino</option>
                          <option value="Femenino">Femenino</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="fecha_nac_r">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nac_r" id="fecha_nac_r" class="form-control" required>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="primer_nombre_r">Primer Nombre</label>
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
                        <label for="primer_apellido_r">Primer Apellido</label>
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
                        <label for="lugar_nac_r">Lugar de Nacimiento</label>
                        <input type="text" name="lugar_nac_r" id="lugar_nac_r" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="correo_r">Correo Electrónico</label>
                        <input type="email" name="correo_r" id="correo_r" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="telefono_r">Teléfono Móvil</label>
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
                        <label for="profesion_r">Profesión</label>
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
                        <label for="ocupacion_r">Ocupación</label>
                        <input type="text" name="ocupacion_r" id="ocupacion_r" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="lugar_trabajo_r">Lugar de Trabajo</label>
                        <input type="text" name="lugar_trabajo_r" id="lugar_trabajo_r" class="form-control">
                      </div>
                    </div>
                    <!-- <div class="col-md-3">
                      <div class="form-group">
                        <label for="parentesco">Parentesco familiar</label>
                        <select name="parentesco" id="parentesco" class="form-control" required>
                          <option value="">Seleccionar</option>
                          <?php
                          foreach ($parentescos as $pa) {
                            echo "<option value='{$pa['id_parentesco']}'>{$pa['parentesco']}</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div> -->
                  </div>

                  <!-- DIRECCIÓN DEL REPRESENTANTE -->
                  <div class="card-header mt-4">
                    <h3 class="card-title"><b>Dirección del Representante</b></h3>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="estado_r">Estado</label>
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
                          <label for="municipio_r">Municipio</label>
                          <select name="municipio_r" id="municipio_r" class="form-control" required disabled>
                            <option value="">Primero seleccione un estado</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="parroquia_r">Parroquia</label>
                          <select name="parroquia_r" id="parroquia_r" class="form-control" required disabled>
                            <option value="">Primero seleccione un municipio</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="direccion_r">Dirección Completa</label>
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
          <input type="hidden" name="parroquia_e" id="parroquia_e_hidden" value="">
          <div class="row">
            <div class="col-md-12">
              <div class="card card-outline card-success">
                <!-- Pregunta si el alumno vive en la casa del representante -->
                <input type="hidden" name="juntos" id="juntos" value="1">
                <div class="card-header mt-4">
                  <h3 class="card-title"><b>Datos de interés</b></h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="misma_casa">¿El alumno vive en la misma casa del representante?</label>
                        <select name="misma_casa" id="misma_casa" class="form-control" required>
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
                  <input type="hidden" name="id_direccion_est" id="id_direccion_est" value="">

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
                            <label for="estado_e">Estado</label>
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
                            <label for="municipio_e">Municipio</label>
                            <select name="municipio_e" id="municipio_e" class="form-control">
                              <option value="">Primero seleccione un estado</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="parroquia_e">Parroquia</label>
                            <select name="parroquia_e" id="parroquia_e" class="form-control">
                              <option value="">Primero seleccione un municipio</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="direccion_e">Dirección Completa</label>
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
                        <div class="col-md-4">
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
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="id_nivel">Grado/Año<span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                            <select name="id_nivel" id="id_nivel" class="form-control" required>
                              <option value="">Seleccionar Nivel</option>
                              <?php
                              $cuposController = new CuposController($pdo);
                              $todosLosNiveles = $cuposController->obtenerTodosLosNiveles();
                              if ($todosLosNiveles['success']) {
                                foreach ($todosLosNiveles['niveles'] as $nivel) {
                                  echo "<option value='{$nivel['id_nivel']}'>{$nivel['nom_nivel']}</option>";
                                }
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="id_seccion">Sección <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                            <select name="id_seccion" id="id_seccion" class="form-control" required disabled>
                              <option value="">Primero seleccione un nivel</option>
                            </select>
                            <!-- Aquí se mostrarán los mensajes de cupos -->
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
          <input type="hidden" name="parentesco_estudiante" id="parentesco_estudiante" value="">
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Carga de secciones por nivel  -->
<!-- Carga de secciones por nivel  -->
<!-- Carga de secciones por nivel  -->
<!-- Carga de secciones por nivel  -->
<script>
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

  function cargarSeccionesPorNivel(nivelId) {
    const formData = new FormData();
    formData.append('id_nivel', nivelId);

    // Mostrar loading
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
            seccionSelect.innerHTML += `
                    <option value="${seccion.id_seccion}" 
                            data-id-nivel-seccion="${seccion.id_nivel_seccion}">
                        ${seccion.nom_seccion} (Capacidad: ${seccion.capacidad})
                    </option>
                `;
          });

          seccionSelect.disabled = false;

          // Si hay solo una sección, seleccionarla automáticamente
          if (data.secciones.length === 1) {
            seccionSelect.value = data.secciones[0].id_seccion;
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

  // ========== VERIFICACIÓN DE CUPOS ==========
  document.getElementById('id_seccion').addEventListener('change', function() {
    const nivelSelect = document.getElementById('id_nivel');
    const seccionSelect = this;
    const periodoSelect = document.getElementById('id_periodo');

    if (nivelSelect.value && seccionSelect.value && periodoSelect.value) {
      verificarCuposDisponibles();
    }
  });

  document.getElementById('id_periodo').addEventListener('change', function() {
    const nivelSelect = document.getElementById('id_nivel');
    const seccionSelect = document.getElementById('id_seccion');

    if (nivelSelect.value && seccionSelect.value && this.value) {
      verificarCuposDisponibles();
    }
  });

  function verificarCuposDisponibles() {
    const nivelSelect = document.getElementById('id_nivel');
    const seccionSelect = document.getElementById('id_seccion');
    const periodoSelect = document.getElementById('id_periodo');
    const selectedOption = seccionSelect.options[seccionSelect.selectedIndex];
    const idNivelSeccion = selectedOption.getAttribute('data-id-nivel-seccion');

    if (!idNivelSeccion) return;

    const formData = new FormData();
    formData.append('id_nivel_seccion', idNivelSeccion);
    formData.append('id_periodo', periodoSelect.value);

    fetch('/final/app/controllers/cupos/verificar_cupos.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        // Mostrar mensaje de disponibilidad (puedes implementar un sistema de notificaciones)
        if (data.success) {
          if (data.disponible) {
            console.log('✅ Cupos disponibles:', data.mensaje);
            // Puedes mostrar un mensaje al usuario
            mostrarMensajeCupos(data.mensaje, 'success');
          } else {
            console.log('❌ No hay cupos:', data.mensaje);
            mostrarMensajeCupos(data.mensaje, 'warning');
          }
        } else {
          console.error('Error:', data.message);
          mostrarMensajeCupos('Error al verificar cupos: ' + data.message, 'error');
        }
      })
      .catch(error => {
        console.error('Error verificando cupos:', error);
        mostrarMensajeCupos('Error al verificar disponibilidad de cupos', 'error');
      });
  }

  function mostrarMensajeCupos(mensaje, tipo) {
    // Implementa tu sistema de notificaciones aquí
    // Por ejemplo, usando SweetAlert o un div de mensajes

    const mensajeDiv = document.getElementById('mensaje-cupos') || crearDivMensajeCupos();
    mensajeDiv.innerHTML = mensaje;
    mensajeDiv.className = `alert alert-${tipo === 'success' ? 'success' : tipo === 'warning' ? 'warning' : 'danger'} mt-2`;
    mensajeDiv.style.display = 'block';

    // Auto-ocultar después de 5 segundos
    setTimeout(() => {
      mensajeDiv.style.display = 'none';
    }, 5000);
  }

  function crearDivMensajeCupos() {
    const div = document.createElement('div');
    div.id = 'mensaje-cupos';
    div.style.display = 'none';

    const seccionContainer = document.getElementById('id_seccion').closest('.form-group');
    seccionContainer.appendChild(div);

    return div;
  }

  // ========== INICIALIZACIÓN ==========
  document.addEventListener('DOMContentLoaded', function() {
    // Preseleccionar nivel siguiente basado en el estudiante seleccionado
    // (esto ya lo tienes en llenarDatosEstudiante, pero asegurémonos de que cargue las secciones)

    // Si ya hay un nivel seleccionado al cargar (por ejemplo, desde llenarDatosEstudiante)
    const nivelSelect = document.getElementById('id_nivel');
    if (nivelSelect.value) {
      // Disparar el evento change para cargar las secciones automáticamente
      setTimeout(() => {
        nivelSelect.dispatchEvent(new Event('change'));
      }, 500);
    }
  });
</script>

<script>
  // ========== SISTEMA DE NAVEGACIÓN ==========
  document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 4;
    let estudiantesData = [];
    let representanteData = null;

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
      // Validar campos requeridos antes de continuar
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

    // También validar con Enter
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

      // Mostrar loading
      const btnValidar = document.getElementById('btn-validar-representante');
      const originalText = btnValidar.innerHTML;
      btnValidar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Validando...';
      btnValidar.disabled = true;

      fetch('/final/app/controllers/representantes/validar3.php', {
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

            // Guardar datos del representante
            representanteData = data;

            // Llenar datos del representante en el formulario
            llenarDatosRepresentante(data);

            // Cargar estudiantes del representante
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
      // Datos básicos
      document.getElementById('id_representante_existente').value = data.id_representante;
      document.getElementById('id_direccion_repre').value = data.id_direccion;
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
      document.getElementById('profesion_r').value = data.profesion || '';
      document.getElementById('ocupacion_r').value = data.ocupacion || '';
      document.getElementById('lugar_trabajo_r').value = data.lugar_trabajo || '';
      // document.getElementById('parentesco').value = data.parentesco || '1';

      // Dirección
      document.getElementById('direccion_r').value = data.direccion || '';
      document.getElementById('calle_r').value = data.calle || '';
      document.getElementById('casa_r').value = data.casa || '';

      // Cargar ubicación
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

      // Mostrar info en paso 3
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

      fetch('/final/app/controllers/estudiantes/estudiantes_por_representante2.php', {
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

    // function mostrarEstudiantes() {
    //   const container = document.getElementById('lista-estudiantes');

    //   if (estudiantesData.length === 0) {
    //     container.innerHTML = `
    //             <div class="col-12">
    //                 <div class="alert alert-warning">
    //                     No se encontraron estudiantes asociados a este representante.
    //                 </div>
    //             </div>
    //         `;
    //     return;
    //   }

    //   let html = '';
    //   estudiantesData.forEach(estudiante => {
    //     const nivel = estudiante.nombre_nivel || 'No asignado';
    //     const seccion = estudiante.nom_seccion || '';
    //     const nivelSeccion = seccion ? ` - ${seccion}` : '';
    //     const periodoAnterior = estudiante.periodo_anterior_desc || 'Sin historial';
    //     const estado = estudiante.estado_inscripcion || 'No inscrito';
    //     const badgeClass = (estado === 'Inscrito') ? 'badge-success' : 'badge-warning';

    //     html += `
    //             <div class="col-md-6 col-lg-4 mb-4">
    //                 <div class="card estudiante-card" data-id="${estudiante.id_estudiante}">
    //                     <div class="card-header bg-light">
    //                         <h5 class="card-title mb-0">${estudiante.primer_nombre} ${estudiante.primer_apellido}</h5>
    //                     </div>
    //                     <div class="card-body estudiante-info">
    //                         <p class="mb-1"><strong>Cédula:</strong> ${estudiante.cedula}</p>
    //                         <p class="mb-1"><strong>Último Nivel:</strong> ${nivel}${nivelSeccion}</p>
    //                         <p class="mb-1"><strong>Período Anterior:</strong> ${periodoAnterior}</p>
    //                         <p class="mb-1"><strong>Parentesco:</strong> ${estudiante.parentesco}</p>
    //                         <p class="mb-0"><strong>Estado Actual:</strong> 
    //                             <span class="badge ${badgeClass}">${estado}</span>
    //                         </p>
    //                     </div>
    //                     <div class="card-footer text-center">
    //                         <button type="button" class="btn btn-primary btn-sm btn-seleccionar-estudiante" 
    //                                 data-id="${estudiante.id_estudiante}">
    //                             <i class="fas fa-sync-alt"></i> Seleccionar
    //                         </button>
    //                     </div>
    //                 </div>
    //             </div>
    //         `;
    //   });

    //   container.innerHTML = html;
    //   bindEstudianteEvents();
    // }
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
      estudiantesData.forEach(estudiante => {
        const nivel = estudiante.nombre_nivel || 'No asignado';
        const seccion = estudiante.nom_seccion || '';
        const nivelSeccion = seccion ? ` - ${seccion}` : '';
        const periodoAnterior = estudiante.periodo_anterior_desc || 'Sin historial';
        const estado = estudiante.estado_inscripcion || 'No inscrito';
        const badgeClass = (estado === 'Inscrito') ? 'badge-success' : 'badge-warning';

        // AQUÍ mostramos el parentesco que viene del estudiante
        const parentesco = estudiante.parentesco || 'No especificado';

        html += `
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card estudiante-card" data-id="${estudiante.id_estudiante}">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">${estudiante.primer_nombre} ${estudiante.primer_apellido}</h5>
                    </div>
                    <div class="card-body estudiante-info">
                        <p class="mb-1"><strong>Cédula:</strong> ${estudiante.cedula}</p>
                        <p class="mb-1"><strong>Último Nivel:</strong> ${nivel}${nivelSeccion}</p>
                        <p class="mb-1"><strong>Período Anterior:</strong> ${periodoAnterior}</p>
                        <p class="mb-1"><strong>Parentesco:</strong> ${parentesco}</p>
                        <p class="mb-0"><strong>Estado Actual:</strong> 
                            <span class="badge ${badgeClass}">${estado}</span>
                        </p>
                    </div>
                    <div class="card-footer text-center">
                        <button type="button" class="btn btn-primary btn-sm btn-seleccionar-estudiante" 
                                data-id="${estudiante.id_estudiante}">
                            <i class="fas fa-sync-alt"></i> Seleccionar
                        </button>
                    </div>
                </div>
            </div>
        `;
      });

      container.innerHTML = html;
      bindEstudianteEvents();
    }

    function bindEstudianteEvents() {
      document.querySelectorAll('.btn-seleccionar-estudiante').forEach(button => {
        button.addEventListener('click', (e) => {
          e.stopPropagation();
          const idEstudiante = e.target.getAttribute('data-id');
          seleccionarEstudiante(idEstudiante);
        });
      });

      document.querySelectorAll('.estudiante-card').forEach(card => {
        card.addEventListener('click', (e) => {
          if (!e.target.closest('.btn-seleccionar-estudiante')) {
            const idEstudiante = e.currentTarget.getAttribute('data-id');
            seleccionarEstudiante(idEstudiante);
          }
        });
      });
    }

    // function seleccionarEstudiante(idEstudiante) {
    //   const estudiante = estudiantesData.find(e => e.id_estudiante == idEstudiante);

    //   if (!estudiante) {
    //     alert('Error: No se pudo encontrar la información del estudiante seleccionado.');
    //     return;
    //   }

    //   // Remover selección anterior
    //   document.querySelectorAll('.estudiante-card').forEach(card => {
    //     card.classList.remove('selected');
    //     card.style.border = '1px solid #dee2e6';
    //   });

    //   // Marcar como seleccionado
    //   const cardSeleccionada = document.querySelector(`.estudiante-card[data-id="${idEstudiante}"]`);
    //   if (cardSeleccionada) {
    //     cardSeleccionada.classList.add('selected');
    //     cardSeleccionada.style.border = '3px solid #007bff';
    //     cardSeleccionada.style.backgroundColor = '#f8f9fa';
    //   }

    //   // Llenar datos del estudiante
    //   llenarDatosEstudiante(estudiante);

    //   // Mostrar botón para continuar
    //   document.getElementById('btn-next-to-step4').style.display = 'inline-block';
    // }

    function seleccionarEstudiante(idEstudiante) {
      const estudiante = estudiantesData.find(e => e.id_estudiante == idEstudiante);

      if (!estudiante) {
        alert('Error: No se pudo encontrar la información del estudiante seleccionado.');
        return;
      }

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

      // Guardar el parentesco del estudiante seleccionado
      document.getElementById('id_parentesco_estudiante').value = estudiante.id_parentesco || '';
      document.getElementById('parentesco_estudiante').value = estudiante.parentesco || '';

      // Llenar datos del estudiante
      llenarDatosEstudiante(estudiante);

      // Mostrar botón para continuar
      document.getElementById('btn-next-to-step4').style.display = 'inline-block';
    }

    function llenarDireccionEstudiante(estudiante) {
      document.getElementById('id_direccion_est').value = estudiante.id_direccion_est || '';
      document.getElementById('direccion_e').value = estudiante.direccion_est || '';
      document.getElementById('calle_e').value = estudiante.calle_est || '';
      document.getElementById('casa_e').value = estudiante.casa_est || '';

      // Cargar ubicación del estudiante
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

    function llenarDatosEstudiante(estudiante) {

      if (estudiante.num_nivel) {
        const siguienteNivel = parseInt(estudiante.num_nivel) + 1;
        const nivelSelect = document.getElementById('id_nivel');

        for (let i = 0; i < nivelSelect.options.length; i++) {
          if (nivelSelect.options[i].text.includes(siguienteNivel)) {
            nivelSelect.value = nivelSelect.options[i].value;

            // Cargar automáticamente las secciones para este nivel
            setTimeout(() => {
              nivelSelect.dispatchEvent(new Event('change'));
            }, 100);
            break;
          }
        }
      }
      document.getElementById('id_estudiante_existente').value = estudiante.id_estudiante;
      document.getElementById('id_direccion_est').value = estudiante.id_direccion || '';

      // Datos personales
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

      // Configurar CI - EN REINSCRIPCIÓN
      if (estudiante.cedula && estudiante.cedula !== '') {
        // Si el estudiante YA TIENE cédula, asumimos que fue generada automáticamente
        document.getElementById('ci_si').value = 'no';
        document.getElementById('cedula_e').readOnly = true;
        document.getElementById('cedula_e').style.backgroundColor = '#f8f9fa';
        document.getElementById('cedula_e').style.cursor = 'not-allowed';
        document.getElementById('cedula_e').placeholder = "Cédula generada automáticamente";

        console.log('✅ Estudiante con cédula existente:', estudiante.cedula);
      } else {
        // Caso raro: estudiante existe pero no tiene cédula
        document.getElementById('ci_si').value = 'no';
        document.getElementById('cedula_e').readOnly = true;
        document.getElementById('cedula_e').style.backgroundColor = '#f8f9fa';
        document.getElementById('cedula_e').style.cursor = 'not-allowed';
        document.getElementById('cedula_e').placeholder = "Se generará con la fecha";

        console.warn('⚠️ Estudiante encontrado sin cédula registrada');

        // Si hay fecha de nacimiento, generar cédula inicial
        if (estudiante.fecha_nac) {
          const anioNacimiento = estudiante.fecha_nac.substring(2, 4);
          // Generar una cédula básica (año + número aleatorio)
          const cedulaInicial = anioNacimiento + '1' + Math.floor(10000 + Math.random() * 90000);
          document.getElementById('cedula_e').value = cedulaInicial;
          console.log('🔢 Cédula inicial generada:', cedulaInicial);
        }
      }

      // Mostrar info del estudiante seleccionado
      const nivelAnterior = estudiante.nombre_nivel || 'No asignado';
      const periodoAnterior = estudiante.periodo_anterior_desc || 'Sin historial';

      document.getElementById('info-estudiante-seleccionado').style.display = 'block';
      document.getElementById('datos-estudiante-seleccionado').innerHTML = `
        <strong>Nombre completo:</strong> ${estudiante.primer_nombre} ${estudiante.segundo_nombre || ''} ${estudiante.primer_apellido} ${estudiante.segundo_apellido || ''}<br>
        <strong>Cédula:</strong> ${estudiante.cedula || 'No registrada (se generará automáticamente)'}<br>
        <strong>Fecha de nacimiento:</strong> ${estudiante.fecha_nac || 'No registrada'}<br>
        <strong>Parentesco:</strong> ${estudiante.parentesco}<br>
        <strong>Último nivel cursado:</strong> ${nivelAnterior} (${periodoAnterior})
    `;
      document.getElementById('misma_casa').value = 'si';
      document.getElementById('juntos').value = '1';

      // Si el estudiante tiene dirección diferente, detectarlo
      // Esto depende de cómo vengan los datos de tu API
      if (estudiante.id_direccion_est && estudiante.id_direccion_repre &&
        estudiante.id_direccion_est !== estudiante.id_direccion_repre) {
        document.getElementById('misma_casa').value = 'no';
        document.getElementById('juntos').value = '0';
        document.getElementById('direccion_representante').style.display = 'block';

        // Llenar datos de dirección del estudiante
        setTimeout(() => {
          llenarDireccionEstudiante(estudiante);
        }, 100);
      }

      // Preseleccionar nivel siguiente
      if (estudiante.num_nivel) {
        const siguienteNivel = parseInt(estudiante.num_nivel) + 1;
        const nivelSelect = document.getElementById('id_nivel');
        for (let i = 0; i < nivelSelect.options.length; i++) {
          if (nivelSelect.options[i].text.includes(siguienteNivel)) {
            nivelSelect.value = nivelSelect.options[i].value;
            break;
          }
        }
      }

      // Cargar patologías y discapacidades del estudiante
      cargarDatosSaludEstudiante(estudiante.id_estudiante);
    }

    function cargarDatosSaludEstudiante(idEstudiante) {
      // Cargar patologías del estudiante
      const formData = new FormData();
      formData.append('id_estudiante', idEstudiante);

      fetch('/final/app/controllers/estudiantes/obtener_patologias.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          const contenedorPatologias = document.getElementById('contenedor-patologias');

          // Limpiar contenedor pero mantener al menos un select
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

          // Agregar cada patología del estudiante
          if (data.success && data.patologias.length > 0) {
            data.patologias.forEach((patologia, index) => {
              if (index === 0) {
                // Primera patología en el select principal
                document.querySelector('.select-patologia').value = patologia.id_patologia;
              } else {
                // Patologías adicionales
                agregarPatologia(patologia.id_patologia);
              }
            });
          }
        })
        .catch(error => console.error('Error cargando patologías:', error));

      // Cargar discapacidades del estudiante
      fetch('/final/app/controllers/estudiantes/obtener_discapacidades.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          const contenedorDiscapacidades = document.getElementById('contenedor-discapacidades');

          // Limpiar contenedor pero mantener al menos un select
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

          // Agregar cada discapacidad del estudiante
          if (data.success && data.discapacidades.length > 0) {
            data.discapacidades.forEach((discapacidad, index) => {
              if (index === 0) {
                // Primera discapacidad en el select principal
                document.querySelector('.select-discapacidad').value = discapacidad.id_discapacidad;
              } else {
                // Discapacidades adicionales
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
              select.innerHTML += `<option value="${parroquia.id_parroquia}">${parroquia.nom_parroquia}</option>`;
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

    // ========== MANEJO DE DIRECCIÓN DEL ESTUDIANTE ==========
    document.getElementById('misma_casa').addEventListener('change', function() {
      const direccionEstudiante = document.getElementById('direccion_representante');

      if (this.value === 'no') {
        document.getElementById('juntos').value = '0';
        juntosHidden.value = '0';
        direccionEstudiante.style.display = 'block';

        // Cargar ubicación del estudiante si existe
        if (representanteData && representanteData.id_estado) {
          cargarMunicipiosEstudiante(representanteData.id_estado);
        }
      } else {
        document.getElementById('juntos').value = '1';
        juntosHidden.value = '1';
        document.getElementById('id_direccion_est').value = '';
        document.getElementById('direccion_e').value = '';
        document.getElementById('calle_e').value = '';
        document.getElementById('casa_e').value = '';
        document.getElementById('estado_e').value = '';
        document.getElementById('municipio_e').value = '';
        document.getElementById('parroquia_e').value = '';
        direccionEstudiante.style.display = 'none';
      }
    });

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
            select.innerHTML += `<option value="${municipio.id_municipio}">${municipio.nom_municipio}</option>`;
          });
          select.disabled = false;
        });
    }

    // function cargarParroquiasEstudiante(municipioId) {
    //   const formData = new FormData();
    //   formData.append('municipio_id', municipioId);

    //   fetch('/final/app/controllers/ubicaciones/parroquias.php', {
    //       method: 'POST',
    //       body: formData
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //       const select = document.getElementById('parroquia_e');
    //       select.innerHTML = '<option value="">Seleccionar Parroquia</option>';
    //       data.forEach(parroquia => {
    //         select.innerHTML += `<option value="${parroquia.id_parroquia}">${parroquia.nom_parroquia}</option>`;
    //       });
    //       select.disabled = false;
    //     });
    // }

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
              select.innerHTML += `<option value="${parroquia.id_parroquia}">${parroquia.nom_parroquia}</option>`;
            });
            select.disabled = false;

            // Cuando se seleccione una parroquia, actualizar el hidden
            select.addEventListener('change', function() {
              document.getElementById('parroquia_e_hidden').value = this.value;
            });

            resolve();
          })
          .catch(error => reject(error));
      });
    }

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
    // Función para agregar patología
    function agregarPatologia(valorSeleccionado = '') {
      const contenedor = document.getElementById('contenedor-patologias');
      const primerSelect = document.querySelector('.select-patologia');

      if (!primerSelect) return;

      // Obtener opciones del primer select
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

      // Agregar evento al botón eliminar
      div.querySelector('.btn-eliminar-patologia').addEventListener('click', function() {
        div.remove();
      });
    }

    // Función para agregar discapacidad
    function agregarDiscapacidad(valorSeleccionado = '') {
      const contenedor = document.getElementById('contenedor-discapacidades');
      const primerSelect = document.querySelector('.select-discapacidad');

      if (!primerSelect) return;

      // Obtener opciones del primer select
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

      // Agregar evento al botón eliminar
      div.querySelector('.btn-eliminar-discapacidad').addEventListener('click', function() {
        div.remove();
      });
    }

    // Event listeners para botones de agregar
    document.getElementById('btn-agregar-patologia').addEventListener('click', () => agregarPatologia());
    document.getElementById('btn-agregar-discapacidad').addEventListener('click', () => agregarDiscapacidad());

    // ========== MANEJO DE CI DEL ESTUDIANTE - REINSCRIPCIÓN ==========
    const selectCi = document.getElementById('ci_si');
    const cedulaEInput = document.getElementById('cedula_e');
    const fechaNacE = document.getElementById('fecha_nac_e');

    const hoy = new Date();
    const añoActual = hoy.getFullYear();
    let añoMinimo = añoActual - 19;
    let añoMaximo = añoActual - 5;

    async function obtenerEdadesGlobales() {
      try {
        console.log('📊 Solicitando edades globales desde la base de datos...');

        const response = await fetch('/final/app/controllers/globales/obtenerEdades.php', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          }
        });

        const responseText = await response.text();
        console.log('📨 Respuesta del servidor (edades):', responseText);

        let data;
        try {
          data = JSON.parse(responseText);
        } catch (parseError) {
          console.error('❌ Error al parsear JSON:', parseError.message);
          return {
            success: false
          };
        }

        if (data.success) {
          console.log('✅ Edades obtenidas:', {
            edad_min: data.edad_min,
            edad_max: data.edad_max
          });
          return data;
        } else {
          console.error('❌ Error al obtener edades:', data.error);
          return {
            success: false
          };
        }

      } catch (error) {
        console.error('❌ Error en obtenerEdadesGlobales:', error);
        return {
          success: false
        };
      }
    }

    // Función para inicializar los límites de fecha
    async function inicializarFechas() {
      const edades = await obtenerEdadesGlobales();

      if (edades.success) {
        añoMinimo = añoActual - edades.edad_max;
        añoMaximo = añoActual - edades.edad_min;

        console.log('🎯 Límites calculados:', {
          añoMinimo: añoMinimo,
          añoMaximo: añoMaximo,
          edad_min: edades.edad_min,
          edad_max: edades.edad_max,
          explicación: `Estudiantes entre ${edades.edad_min} y ${edades.edad_max} años`
        });
      } else {
        console.warn('⚠️ Usando valores por defecto para las edades');
        añoMinimo = añoActual - 19;
        añoMaximo = añoActual - 5;
      }

      // Establecer los límites en el input de fecha
      fechaNacE.min = `${añoMinimo}-01-01`;
      fechaNacE.max = `${añoMaximo}-12-31`;

      console.log('📅 Límites de fecha establecidos:', {
        min: fechaNacE.min,
        max: fechaNacE.max,
        rango_edades: `Nacidos entre ${añoMinimo} y ${añoMaximo}`
      });
    }

    // Función para actualizar los primeros 2 dígitos de la cédula
    function actualizarCedulaPorFecha() {
      console.log('📅 Evento de cambio de fecha detectado');

      const fecha = fechaNacE.value;
      const cedulaActual = cedulaEInput.value;

      // Verificar que tenemos todos los datos necesarios
      if (!fecha) {
        console.log('❌ No hay fecha seleccionada');
        return;
      }

      if (!cedulaActual) {
        console.log('❌ No hay cédula existente para actualizar');
        return;
      }

      // Obtener los 2 últimos dígitos del año
      const anioNacimiento = fecha.substring(2, 4);
      console.log('🔢 Año de nacimiento extraído:', anioNacimiento);

      // Mantener el resto de la cédula (desde la posición 2 hasta el final)
      const restoCedula = cedulaActual.substring(2);

      // Nueva cédula: primeros 2 dígitos del año + resto de la cédula original
      const nuevaCedula = anioNacimiento + restoCedula;

      console.log('🔄 Actualizando cédula:', {
        cedula_original: cedulaActual,
        nuevo_año: anioNacimiento,
        resto_cedula: restoCedula,
        nueva_cedula: nuevaCedula
      });

      cedulaEInput.value = nuevaCedula;
      console.log('✅ Cédula actualizada:', nuevaCedula);
    }

    // Manejar cambio en el select de CI
    selectCi.addEventListener('change', function() {
      console.log('🔄 Select CI cambiado a:', this.value);

      if (this.value === 'no') {
        console.log('🎯 Modo: Sin cédula - En reinscripción esto no debería cambiar');
        cedulaEInput.readOnly = true;
        cedulaEInput.style.backgroundColor = '#f8f9fa';
        cedulaEInput.style.cursor = 'not-allowed';
        cedulaEInput.placeholder = "Cédula existente del estudiante";

      } else if (this.value === 'si') {
        console.log('🆗 Modo: Con cédula - permitir edición');
        cedulaEInput.readOnly = false;
        cedulaEInput.style.backgroundColor = '';
        cedulaEInput.style.cursor = '';
        cedulaEInput.placeholder = "Cédula del estudiante";
      }
    });

    // Escuchar cambios en la fecha de nacimiento para actualizar cédula
    fechaNacE.addEventListener('change', function() {
      // Solo actualizar si estamos en modo "no CI" (cedula generada automáticamente)
      if (selectCi.value === 'no' && cedulaEInput.value) {
        console.log('🔄 Cambio de fecha detectado, actualizando cédula...');
        actualizarCedulaPorFecha();
      }
    });

    // Inicializar los límites de fecha al cargar la página
    inicializarFechas();

    // Debug inicial
    console.log('🔍 Estado inicial REINSCRIPCIÓN:', {
      fechaInput: fechaNacE ? 'Encontrado' : 'No encontrado',
      cedulaEInput: cedulaEInput ? 'Encontrado' : 'No encontrado',
      selectCi: selectCi ? 'Encontrado' : 'No encontrado',
      selectCiValue: selectCi ? selectCi.value : 'N/A'
    });
    // Función para validar y generar cédula
    async function validarRegistro() {
      console.log('📅 Evento de cambio de fecha detectado');

      const fecha = fechaNacE.value;
      const idR = id_representante_esc.value;
      const tp = tipo.value;

      // Obtener el valor ACTUAL de la cédula
      const cedulaRActual = cedulaRInput.value;
      console.log('Datos obtenidos:', {
        fecha: fecha,
        cedulaRActual: cedulaRActual,
        idR: idR,
        tp: tp
      });

      // Verificar que tenemos todos los datos necesarios
      if (!fecha) {
        console.log('❌ No hay fecha seleccionada');
        return;
      }

      if (!cedulaRActual) {
        console.log('❌ No hay cédula de representante');
        return;
      }

      const anioNacimiento = fecha.substring(2, 4);
      console.log('🔢 Año de nacimiento extraído:', anioNacimiento);

      if (tp === 'representante') {
        console.log('👨‍👦 Tipo: representante - generando cédula escolar');
        try {
          cedulaRInput.disabled = true;
          const numeroDEstudiantes = await validarYGenerarCedula(idR, anioNacimiento, cedulaRActual);
          if (numeroDEstudiantes) {
            cedulaEInput.value = numeroDEstudiantes;
            cedulaEInput.readOnly = true;
            cedulaEInput.style.backgroundColor = '#f8f9fa';
            cedulaEInput.style.cursor = 'not-allowed';
            console.log('✅ Cédula escolar generada:', numeroDEstudiantes);
          }
        } catch (error) {
          console.error('❌ Error:', error);
        }
      } else {
        console.log('👤 Tipo: otro - generando cédula simple');
        const c_esc = anioNacimiento + '1' + cedulaRActual;
        cedulaEInput.value = c_esc;
        cedulaEInput.readOnly = true;
        cedulaEInput.style.backgroundColor = '#f8f9fa';
        cedulaEInput.style.cursor = 'not-allowed';
        console.log('✅ Cédula escolar generada:', c_esc);
      }
    }

    // Función para contar estudiantes
    async function validarYGenerarCedula(idRepre, a, c) {
      try {
        console.log('📊 Solicitando cuenta de alumnos para ID:', idRepre);

        const response = await fetch('/final/app/controllers/representantes/cuentaDeAlumnos.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `id=${encodeURIComponent(idRepre)}`
        });

        const responseText = await response.text();
        console.log('📨 Respuesta del servidor:', responseText);

        let data;
        try {
          data = JSON.parse(responseText);
        } catch (parseError) {
          console.error('❌ Error al parsear JSON:', parseError.message);
          throw new Error(`Error de formato JSON: ${parseError.message}`);
        }

        if (!data.success) {
          throw new Error(data.error || 'Error del servidor');
        }

        console.log('✅ Total estudiantes:', data.total_estudiantes);
        const cedulaEsc = a + (data.total_estudiantes) + c;
        console.log('🔢 Cédula escolar compuesta:', cedulaEsc);
        return cedulaEsc;

      } catch (error) {
        console.error('❌ Error en validarYGenerarCedula:', error);
        return 0;
      }
    }

    // Manejar cambio en el select de CI
    selectCi.addEventListener('change', function() {
      console.log('🔄 Select CI cambiado a:', this.value);

      if (this.value === 'no') {
        console.log('🎯 Modo: Sin cédula - activando generación automática');
        cedulaEInput.placeholder = "Se generará automáticamente";

        // Agregar event listener para cambios de fecha
        fechaNacE.addEventListener('change', validarRegistro);
        console.log('👂 Escuchando cambios en fecha...');

        // Ejecutar inmediatamente si ya hay una fecha seleccionada
        if (fechaNacE.value) {
          console.log('📋 Fecha ya seleccionada, ejecutando validación...');
          validarRegistro();
        } else {
          console.log('⏳ Esperando selección de fecha...');
        }

      } else if (this.value === 'si') {
        console.log('🆗 Modo: Con cédula - desactivando generación automática');
        // Remover el event listener cuando no es necesario
        fechaNacE.removeEventListener('change', validarRegistro);
        // Limpiar y habilitar el campo para ingreso manual
        cedulaEInput.value = '';
        cedulaEInput.readOnly = false;
        cedulaEInput.style.backgroundColor = '';
        cedulaEInput.style.cursor = '';
        cedulaEInput.placeholder = "Ingrese la cédula de identidad";
      }
    });

    // También escuchar cambios en la cédula del representante por si cambia
    cedulaRInput.addEventListener('input', function() {
      console.log('✏️ Cédula representante cambiada:', this.value);
      // Si ya hay fecha seleccionada y estamos en modo "no CI", regenerar
      if (selectCi.value === 'no' && fechaNacE.value) {
        console.log('🔄 Regenerando cédula escolar por cambio en cédula representante');
        validarRegistro();
      }
    });

    // Inicializar los límites de fecha al cargar la página
    inicializarFechas();

    // Debug inicial
    console.log('🔍 Estado inicial reinscripción:', {
      fechaInput: fechaNacE ? 'Encontrado' : 'No encontrado',
      cedulaRInput: cedulaRInput ? 'Encontrado' : 'No encontrado',
      cedulaEInput: cedulaEInput ? 'Encontrado' : 'No encontrado',
      selectCi: selectCi ? 'Encontrado' : 'No encontrado',
      selectCiValue: selectCi ? selectCi.value : 'N/A'
    });
  });
</script>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>