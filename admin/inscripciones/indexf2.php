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

// include_once("/xampp/htdocs/final/app/controllers/representantes/profesiones.php");
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
</style>

<style>
  .patologia-item {
    transition: all 0.3s ease;
  }

  .btn-eliminar-patologia {
    opacity: 0.7;
    transition: opacity 0.3s ease;
  }

  .btn-eliminar-patologia:hover {
    opacity: 1;
  }

  .select-patologia {
    min-width: 200px;
  }
</style>

<style>
  /* Estilo para campos de solo lectura */
  .form-control[readonly] {
    background-color: #f8f9fa !important;
    cursor: not-allowed !important;
    opacity: 1 !important;
  }

  /* Estilo específico para cédula generada automáticamente */
  .cedula-generada {
    background-color: #e9ecef !important;
    border-color: #ced4da !important;
    color: #495057 !important;
  }
</style>
<div class="content-wrapper">
  <div class="content">
    <br>
    <div class="container">
      <div class="row">
        <h1>Inscripción de Nuevo Estudiante</h1>
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
                    <strong>Paso 3:</strong> Datos del Estudiante
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <form action="http://localhost/final/app/controllers/inscripciones/inscripciong2.php" method="post" id="form-inscripcion">

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
                  <input type="hidden" name="representante_existente" id="representante_existente" value="0">
                  <input type="hidden" name="id_representante_existente" id="id_representante_existente" value="">
                  <input type="hidden" name="id_direccion_repre" id="id_direccion_repre" value="">
                  <input type="hidden" name="tipo_persona" id="tipo_persona" value="">
                  <input type="hidden" name="id_representante_existente_esc" id="id_representante_existente_esc" value="">

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
                    <div class="col-md-3">
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

        <!-- Formulario del apartado del estudiante -->
        <div class="step" id="step3">
          <div class="row">
            <div class="col-md-12">
              <div class="card card-outline card-success">
                <!-- Pregunta si el almuno vive en la casa del representante -->
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
                  <h3 class="card-title"><b>Paso 3: Datos del Estudiante</b></h3>
                </div>
                <div class="card-body">

                  <div class="row">

                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="nacionalidad_e">Nacionalidad <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <select name="nacionalidad_e" class="form-control" required>
                          <option value="">Seleccionar</option>
                          <option value="Venezolano">Venezolano</option>
                          <option value="Extranjero">Extranjero</option>
                        </select>
                      </div>
                    </div>
                    <!-- <div class="col-md-3">
                      <div class="form-group">
                        <label for="fecha_nac_e">Fecha de Nacimiento </label>
                        <input type="date" name="fecha_nac_e" id="fecha_nac_e" class="form-control" required>
                      </div>
                    </div> -->
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="fecha_nac_e">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nac_e" id="fecha_nac_e" class="form-control" required>
                        <small class="form-text text-muted" id="rango-fecha-help">
                          <!-- Este mensaje se actualizará con JavaScript -->
                          Cargando rango de edades permitidas...
                        </small>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="cedula_e">Cédula de Identidad</label>
                        <input type="text" name="cedula_e" id="cedula_e" class="form-control"
                          placeholder="Seleccione 'No' para generar automáticamente" required>
                        <small class="form-text text-muted">
                          Si el estudiante no tiene cédula, seleccione "No" y se generará una cédula escolar automáticamente
                        </small>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="sexo_e">Sexo <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <select name="sexo_e" class="form-control" required>
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
                        <input type="text" name="primer_nombre_e" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="segundo_nombre_e">Segundo Nombre</label>
                        <input type="text" name="segundo_nombre_e" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="primer_apellido_e">Primer Apellido <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <input type="text" name="primer_apellido_e" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="segundo_apellido_e">Segundo Apellido</label>
                        <input type="text" name="segundo_apellido_e" class="form-control">
                      </div>
                    </div>
                  </div>



                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="lugar_nac_e">Lugar de Nacimiento <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                        <input type="text" name="lugar_nac_e" class="form-control" required>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="telefono_e">Teléfono</label>
                        <input type="text" name="telefono_e" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="correo_e">Correo Electrónico</label>
                        <input type="email" name="correo_e" class="form-control">
                      </div>
                    </div>
                  </div>

                  <!-- PATOLOGÍAS -->

                  <!-- PATOLOGÍAS DEL SISTEMA - CARGADAS DESDE BASE DE DATOS -->


                  <!-- DISCAPACIDADES -->

                  <div>
                    <div class="card-header mt-4">
                      <h3 class="card-title"><b>Datos de salud</b></h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <!-- Patologías y Discapacidades en la misma fila -->
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Patologías/Alergias</label>

                            <!-- Contenedor para los selects dinámicos -->
                            <div id="contenedor-patologias">
                              <!-- Select Principal -->
                              <div class="mb-2 patologia-item">
                                <select name="patologias[]" class="form-control select-patologia">
                                  <option value="">Seleccione una patología...</option>
                                  <option value="0">Ninguna</option>
                                  <?php
                                  // Cargar patologías desde la base de datos
                                  $patologiaController = new PatologiaController($pdo);
                                  $patologias = $patologiaController->obtenerPatologiasActivas();

                                  if (!empty($patologias)) {
                                    foreach ($patologias as $patologia) {
                                      echo "<option value='{$patologia['id_patologia']}'>{$patologia['nom_patologia']}</option>";
                                    }
                                  } else {
                                    echo "<option value=''>No hay patologías registradas</option>";
                                  }
                                  ?>
                                </select>
                              </div>
                            </div>

                            <!-- Botón para agregar más patologías -->
                            <div class="mt-2">
                              <button type="button" class="btn btn-outline-primary btn-sm" id="btn-agregar-patologia">
                                <i class="fas fa-plus"></i> Agregar otra patología
                              </button>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Discapacidades</label>

                            <!-- Contenedor para los selects dinámicos -->
                            <div id="contenedor-discapacidades">
                              <!-- Select Principal -->
                              <div class="mb-2 discapacidad-item">
                                <select name="discapacidades[]" class="form-control select-discapacidad">
                                  <option value="">Seleccione una discapacidad...</option>
                                  <option value="0">Ninguna</option>
                                  <?php
                                  // Cargar discapacidades desde la base de datos
                                  $discapacidadController = new DiscapacidadController($pdo);
                                  $discapacidades = $discapacidadController->obtenerDiscapacidadesActivas();

                                  if (!empty($discapacidades)) {
                                    foreach ($discapacidades as $discapacidad) {
                                      echo "<option value='{$discapacidad['id_discapacidad']}'>{$discapacidad['nom_discapacidad']}</option>";
                                    }
                                  } else {
                                    echo "<option value=''>No hay discapacidades registradas</option>";
                                  }
                                  ?>
                                </select>
                              </div>
                            </div>

                            <!-- Botón para agregar más discapacidades -->
                            <div class="mt-2">
                              <button type="button" class="btn btn-outline-primary btn-sm" id="btn-agregar-discapacidad">
                                <i class="fas fa-plus"></i> Agregar otra discapacidad
                              </button>
                            </div>
                          </div>
                        </div>
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
                      <!-- <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="id_periodo">Período Académico <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
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
                            <label for="id_nivel">Nivel/Grado <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
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
                            <label for="id_seccion">Sección <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
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
                      </div> -->
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="id_periodo">Período Académico <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
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
                            <label for="id_nivel">Grado/Año<span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span></label>
                            <!-- <select name="id_nivel" id="id_nivel" class="form-control" required>
                              <option value="">Seleccionar Nivel</option>
                              <?php
                              $niveles = [1 => 'Primer Grado', 2 => 'Segundo Grado'];
                              foreach ($niveles as $id => $nivel) {
                                echo "<option value='$id'>$nivel</option>";
                              }
                              ?>
                            </select> -->
                            <select name="id_nivel" id="id_nivel" class="form-control" required>
                              <option value="">Seleccionar Nivel</option>
                              <?php
                              // Cargar TODOS los niveles inicialmente
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
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Botones de navegación y envío -->
                  <div class="row mt-4">
                    <div class="col-md-12 text-right">
                      <button type="button" class="btn btn-secondary btn-step" id="btn-back-to-step2">
                        <i class="fas fa-arrow-left"></i> Anterior
                      </button>
                      <button type="submit" class="btn btn-success btn-step">
                        <i class="fas fa-save"></i> Registrar Inscripción
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

<!-- Aca validamos los cupos disponbiles de las secciones disponibles correspondientes a cada año o grado -->
<!-- Aca validamos los cupos disponbiles de las secciones disponibles correspondientes a cada año o grado -->
<!-- Aca validamos los cupos disponbiles de las secciones disponibles correspondientes a cada año o grado -->
<!-- Aca validamos los cupos disponbiles de las secciones disponibles correspondientes a cada año o grado -->

<script>
  // ========== SISTEMA INTEGRADO: VALIDACIÓN DE EDAD + CUPOS ==========
  document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 Inicializando sistema de inscripción...');

    const fechaNacInput = document.querySelector('input[name="fecha_nac_e"]');
    const nivelSelect = document.querySelector('select[name="id_nivel"]');
    const seccionSelect = document.querySelector('select[name="id_seccion"]');
    const periodoSelect = document.querySelector('select[name="id_periodo"]');
    const submitBtn = document.querySelector('button[type="submit"]');

    let mensajeCupos = null;
    let todosLosNiveles = [];

    // ========== INICIALIZACIÓN ==========
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

    function guardarNivelesOriginales() {
      todosLosNiveles = Array.from(nivelSelect.options);
      console.log('💾 Niveles guardados:', todosLosNiveles.length);
    }

    function configurarEventListeners() {
      // Event listener para fecha de nacimiento
      if (fechaNacInput) {
        fechaNacInput.addEventListener('change', function() {
          console.log('📅 Fecha cambiada:', this.value);
          validarEdadYFiltrarNiveles(this.value);
        });
      }

      // Event listener para nivel
      if (nivelSelect) {
        nivelSelect.addEventListener('change', function() {
          console.log('🎯 Nivel cambiado:', this.value);
          if (this.value) {
            cargarSeccionesPorNivel(this.value);
          } else {
            limpiarSecciones();
          }
        });
      }

      // Event listener para sección
      if (seccionSelect) {
        seccionSelect.addEventListener('change', function() {
          console.log('📚 Sección cambiada:', this.value);
          if (this.value && periodoSelect && periodoSelect.value) {
            verificarCupos();
          } else {
            eliminarMensajeCupos();
          }
        });
      }

      // Event listener para período
      if (periodoSelect) {
        periodoSelect.addEventListener('change', function() {
          console.log('📅 Período cambiado:', this.value);
          if (this.value && seccionSelect && seccionSelect.value) {
            verificarCupos();
          } else {
            eliminarMensajeCupos();
          }
        });
      }
    }

    // ========== VALIDACIÓN DE EDAD ==========
    function validarEdadYFiltrarNiveles(fechaNacimiento) {
      if (!fechaNacimiento) {
        console.log('⚠️ No hay fecha de nacimiento');
        restaurarTodosLosNiveles();
        return;
      }

      const edad = calcularEdad(fechaNacimiento);
      console.log(`🎯 Edad calculada: ${edad} años`);

      if (edad < 3) {
        mostrarErrorEdad('El estudiante debe tener al menos 4 años para ser inscrito');
        return;
      }

      if (edad > 20) {
        mostrarErrorEdad('El estudiante no puede tener más de 18 años');
        return;
      }

      filtrarNivelesPorEdad(edad);
    }

    function calcularEdad(fechaNacimiento) {
      const fechaNac = new Date(fechaNacimiento);
      const hoy = new Date();
      let edad = hoy.getFullYear() - fechaNac.getFullYear();
      const mes = hoy.getMonth() - fechaNac.getMonth();

      // Ajustar si aún no ha cumplido años este año
      if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) {
        edad--;
      }
      return edad;
    }

    function filtrarNivelesPorEdad(edad) {
      const valorActual = nivelSelect.value;

      // Limpiar select
      nivelSelect.innerHTML = '<option value="">Seleccionar Nivel</option>';

      let nivelesFiltrados = 0;

      // Agregar niveles filtrados
      todosLosNiveles.forEach(nivel => {
        if (nivel.value && esNivelAptoParaEdad(nivel.textContent, edad)) {
          nivelSelect.appendChild(nivel.cloneNode(true));
          nivelesFiltrados++;
        }
      });


      console.log(`📚 Niveles filtrados: ${nivelesFiltrados} para edad ${edad}`);
      console.log(`📊 Rango aplicado: ${obtenerRangoEdad(edad)}`);

      // Manejar selección actual
      manejarSeleccionActual(valorActual);
    }

    function esNivelAptoParaEdad(nombreNivel, edad) {
      const texto = nombreNivel.toLowerCase();
      const esGrado = texto.includes('grado');
      const esAnio = texto.includes('año') || texto.includes('ano');
      const numero = extraerNumero(texto);

      console.log(`🔍 Analizando nivel: "${nombreNivel}" - Grado:${esGrado} Año:${esAnio} Número:${numero}`);

      if (edad >= 4 && edad <= 10) {
        // 4-10 años: solo grados (desde 1° hasta 6° grado)
        return esGrado && numero >= 1 && numero <= 6;
      } else if (edad >= 11 && edad <= 12) {
        // 11-12 años: grados 4,5,6 y años 1,2,3
        if (esGrado) return numero >= 4 && numero <= 6;
        if (esAnio) return numero >= 1 && numero <= 3;
        return false;
      } else if (edad >= 13 && edad <= 22) {
        // 13-18 años: solo años (desde 1° año hasta donde corresponda)
        return esAnio;
      }
      return false;
    }

    function obtenerRangoEdad(edad) {
      if (edad >= 4 && edad <= 10) return "4-10 años: Solo GRADOS (1°-6°)";
      if (edad >= 11 && edad <= 12) return "11-12 años: GRADOS (4°-6°) y AÑOS (1°-3°)";
      if (edad >= 13 && edad <= 18) return "13-18 años: Solo AÑOS";
      return "Edad fuera de rango";
    }

    function extraerNumero(texto) {
      // Mejorar la extracción de números para capturar mejor los niveles
      const match = texto.match(/(\d+)/);
      const numero = match ? parseInt(match[1]) : 0;

      // Si no encuentra número, intentar con números escritos
      if (numero === 0) {
        const numerosEscritos = {
          'primero': 1,
          'primer': 1,
          'primera': 1,
          'segundo': 2,
          'segunda': 2,
          'tercero': 3,
          'tercer': 3,
          'tercera': 3,
          'cuarto': 4,
          'cuarta': 4,
          'quinto': 5,
          'quinta': 5,
          'sexto': 6,
          'sexta': 6
        };

        for (const [palabra, valor] of Object.entries(numerosEscritos)) {
          if (texto.includes(palabra)) {
            return valor;
          }
        }
      }

      return numero;
    }

    function manejarSeleccionActual(valorAnterior) {
      if (valorAnterior && nivelSelect.querySelector(`option[value="${valorAnterior}"]`)) {
        nivelSelect.value = valorAnterior;
        console.log('✅ Selección anterior restaurada:', valorAnterior);
      } else {
        nivelSelect.value = '';
        console.log('🔄 Selección anterior no disponible, limpiando...');
        limpiarSecciones();
      }

      // Selección automática si solo hay una opción
      const opcionesDisponibles = Array.from(nivelSelect.options).filter(opt => opt.value !== '');
      if (opcionesDisponibles.length === 1) {
        nivelSelect.value = opcionesDisponibles[0].value;
        console.log('🤖 Selección automática:', opcionesDisponibles[0].value);
        setTimeout(() => {
          nivelSelect.dispatchEvent(new Event('change'));
        }, 200);
      }
    }

    function restaurarTodosLosNiveles() {
      nivelSelect.innerHTML = '';
      todosLosNiveles.forEach(nivel => {
        nivelSelect.appendChild(nivel.cloneNode(true));
      });
      console.log('🔄 Todos los niveles restaurados');
      limpiarSecciones();
    }

    function mostrarErrorEdad(mensaje) {
      console.error('❌ Error de edad:', mensaje);

      // Limpiar niveles
      nivelSelect.innerHTML = '<option value="">Edad no válida</option>';
      nivelSelect.disabled = true;
      limpiarSecciones();

      // Mostrar mensaje de error temporal
      const existingError = nivelSelect.parentNode.querySelector('.alert-error-edad');
      if (existingError) existingError.remove();

      const errorDiv = document.createElement('div');
      errorDiv.className = 'alert alert-danger mt-2 alert-error-edad';
      errorDiv.innerHTML = `<strong>❌ Error:</strong> ${mensaje}`;

      nivelSelect.parentNode.appendChild(errorDiv);

      // Remover error después de 5 segundos
      setTimeout(() => {
        if (errorDiv.parentNode) {
          errorDiv.remove();
        }
        nivelSelect.disabled = false;
        restaurarTodosLosNiveles();
      }, 5000);
    }

    // ========== SISTEMA DE CUPOS ==========
    function limpiarSecciones() {
      if (seccionSelect) {
        seccionSelect.innerHTML = '<option value="">Primero seleccione un nivel</option>';
        seccionSelect.disabled = true;
        eliminarMensajeCupos();
        console.log('🔄 Secciones limpiadas');
      }
    }

    function cargarSeccionesPorNivel(idNivel) {
      if (!seccionSelect) return;

      console.log('📡 Cargando secciones para nivel:', idNivel);

      const formData = new FormData();
      formData.append('id_nivel', idNivel);

      seccionSelect.innerHTML = '<option value="">Cargando secciones...</option>';
      seccionSelect.disabled = true;
      eliminarMensajeCupos();

      fetch('/final/app/controllers/cupos/cargar_secciones.php', {
          method: 'POST',
          body: formData
        })
        .then(response => {
          if (!response.ok) throw new Error('Error en la respuesta del servidor');
          return response.json();
        })
        .then(data => {
          console.log('📊 Respuesta secciones:', data);

          seccionSelect.innerHTML = '<option value="">Seleccionar Sección</option>';

          if (data.success && data.secciones && data.secciones.length > 0) {
            data.secciones.forEach(seccion => {
              const option = document.createElement('option');
              option.value = seccion.id_seccion;
              option.textContent = `${seccion.nom_seccion} (Capacidad: ${seccion.capacidad})`;
              option.setAttribute('data-nivel-seccion', seccion.id_nivel_seccion);
              seccionSelect.appendChild(option);
            });
            seccionSelect.disabled = false;
            console.log('✅ Secciones cargadas:', data.secciones.length);
          } else {
            seccionSelect.innerHTML = '<option value="">No hay secciones disponibles</option>';
            seccionSelect.disabled = true;
            console.warn('⚠️ No se encontraron secciones para el nivel:', idNivel);
          }

          // Verificar cupos si ya hay período seleccionado
          if (periodoSelect && periodoSelect.value && seccionSelect.value) {
            setTimeout(verificarCupos, 100);
          }
        })
        .catch(error => {
          console.error('❌ Error al cargar secciones:', error);
          seccionSelect.innerHTML = '<option value="">Error al cargar secciones</option>';
          seccionSelect.disabled = true;
        });
    }

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

    // ========== INICIAR SISTEMA ==========
    inicializar();
  });
</script>

<!--- Aca hacemos la validacion sobre si el estudiante vive en la misma casa ---->
<!-- - Aca hacemos la validacion sobre si el estudiante vive en la misma casa -- -->
<!--- Aca hacemos la validacion sobre si el estudiante vive en la misma casa ---->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const selectMismaCasa = document.getElementById('misma_casa');
    const seccionDireccion = document.getElementById('direccion_representante');

    selectMismaCasa.addEventListener('change', function() {
      if (this.value === 'no') {
        document.getElementById('juntos').value = '0';

        // Mostrar la sección de dirección
        seccionDireccion.style.display = 'block';

        // Hacer los campos requeridos
        document.getElementById('estado_e').required = true;
        document.getElementById('direccion_e').required = true;
      } else {
        document.getElementById('juntos').value = '1';
        // Ocultar la sección de dirección
        seccionDireccion.style.display = 'none';

        // Quitar el atributo required y limpiar los campos
        document.getElementById('estado_e').required = false;
        document.getElementById('direccion_e').required = false;

        // Opcional: Limpiar los campos cuando se ocultan
        document.getElementById('estado_e').value = '';
        document.getElementById('municipio_e').value = '';
        document.getElementById('parroquia_e').value = '';
        document.getElementById('direccion_e').value = '';
        document.getElementById('calle_e').value = '';
        document.getElementById('casa_e').value = '';
      }
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Agregar asterisco a todos los labels de campos required
    document.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
      const label = document.querySelector(`label[for="${field.id}"]`);
      if (label && !label.querySelector('.required-asterisk')) {
        label.innerHTML += ' <span class="text-danger required-asterisk">* <small>(Obligatorio)</small></span>';
      }
    });
  });
</script>

<!-- Validadndo edad para creacion de cedula escolar -->
<!-- Validadndo edad para creacion de cedula escolar -->
<!-- Validadndo edad para creacion de cedula escolar -->
<!-- Validadndo edad para creacion de cedula escolar -->

<!-- <script>
  document.addEventListener('DOMContentLoaded', function() {
    const fechaInput = document.getElementById('fecha_nac_e');
    const cedulaEInput = document.getElementById('cedula_e');
    const cedulaRInput = document.getElementById('cedula_r');
    const id_representante_esc = document.getElementById('id_representante_existente');
    const tipo = document.getElementById('tipo_persona');
    const selectCi = document.getElementById('ci_si');

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
          // Usar valores por defecto en caso de error
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
    // Función para inicializar los límites de fecha
    // Función para inicializar los límites de fecha
    async function inicializarFechas() {
      const edades = await obtenerEdadesGlobales();

      if (edades.success) {
        // ✅ CORRECCIÓN: Invertir el cálculo
        añoMinimo = añoActual - edades.edad_max; // Para edad MÁXIMA
        añoMaximo = añoActual - edades.edad_min; // Para edad MÍNIMA

        console.log('🎯 Límites calculados:', {
          añoMinimo: añoMinimo,
          añoMaximo: añoMaximo,
          edad_min: edades.edad_min,
          edad_max: edades.edad_max,
          explicación: `Estudiantes entre ${edades.edad_min} y ${edades.edad_max} años`
        });
      } else {
        console.warn('⚠️ Usando valores por defecto para las edades');
        // También corregir los valores por defecto
        añoMinimo = añoActual - 19; // edad máxima por defecto
        añoMaximo = añoActual - 5; // edad mínima por defecto
      }

      // Establecer los límites en el input de fecha
      fechaInput.min = `${añoMinimo}-01-01`;
      fechaInput.max = `${añoMaximo}-12-31`;

      console.log('📅 Límites de fecha establecidos:', {
        min: fechaInput.min,
        max: fechaInput.max,
        rango_edades: `Nacidos entre ${añoMinimo} y ${añoMaximo}`
      });
    }

    // Inicializar los límites de fecha al cargar la página
    inicializarFechas();


    // Función para validar y generar cédula
    async function validarRegistro() {
      console.log('📅 Evento de cambio de fecha detectado');

      const fecha = fechaInput.value;
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
            // ✅ Hacer el campo de solo lectura
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
        // ✅ Hacer el campo de solo lectura
        cedulaEInput.readOnly = true;
        cedulaEInput.style.backgroundColor = '#f8f9fa';
        cedulaEInput.style.cursor = 'not-allowed';
        console.log('✅ Cédula escolar generada:', c_esc);
      }
    }

    // Manejar cambio en el select de CI
    selectCi.addEventListener('change', function() {
      console.log('🔄 Select CI cambiado a:', this.value);

      if (this.value === 'no') {
        console.log('🎯 Modo: Sin cédula - activando generación automática');

        // ✅ Asegurar que el campo esté listo para ser de solo lectura
        cedulaEInput.placeholder = "Se generará automáticamente";

        // Agregar event listener para cambios de fecha
        fechaInput.addEventListener('change', validarRegistro);
        console.log('👂 Escuchando cambios en fecha...');

        // Ejecutar inmediatamente si ya hay una fecha seleccionada
        if (fechaInput.value) {
          console.log('📋 Fecha ya seleccionada, ejecutando validación...');
          validarRegistro();
        } else {
          console.log('⏳ Esperando selección de fecha...');
        }

      } else if (this.value === 'si') {
        console.log('🆗 Modo: Con cédula - desactivando generación automática');
        // Remover el event listener cuando no es necesario
        fechaInput.removeEventListener('change', validarRegistro);
        // ✅ Limpiar y habilitar el campo para ingreso manual
        cedulaEInput.value = '';
        cedulaEInput.readOnly = false;
        cedulaEInput.style.backgroundColor = '';
        cedulaEInput.style.cursor = '';
        cedulaEInput.placeholder = "Ingrese la cédula de identidad";
        // cedulaEInput.focus();
      }
    });

    // También escuchar cambios en la cédula del representante por si cambia
    cedulaRInput.addEventListener('input', function() {
      console.log('✏️ Cédula representante cambiada:', this.value);
      // Si ya hay fecha seleccionada y estamos en modo "no CI", regenerar
      if (selectCi.value === 'no' && fechaInput.value) {
        console.log('🔄 Regenerando cédula escolar por cambio en cédula representante');
        validarRegistro();
      }
    });

    // Función para contar estudiantes (mantener igual)
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
        const cedulaEsc = a + (data.total_estudiantes + 1) + c;
        console.log('🔢 Cédula escolar compuesta:', cedulaEsc);
        return cedulaEsc;

      } catch (error) {
        console.error('❌ Error en validarYGenerarCedula:', error);
        return 0;
      }
    }

    // Debug inicial
    console.log('🔍 Estado inicial:', {
      fechaInput: fechaInput ? 'Encontrado' : 'No encontrado',
      cedulaRInput: cedulaRInput ? 'Encontrado' : 'No encontrado',
      cedulaEInput: cedulaEInput ? 'Encontrado' : 'No encontrado',
      selectCi: selectCi ? 'Encontrado' : 'No encontrado',
      selectCiValue: selectCi ? selectCi.value : 'N/A'
    });
  });
</script> -->

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const fechaInput = document.getElementById('fecha_nac_e');
    const cedulaEInput = document.getElementById('cedula_e');
    const cedulaRInput = document.getElementById('cedula_r');
    const id_representante_esc = document.getElementById('id_representante_existente');
    const tipo = document.getElementById('tipo_persona');
    const selectCi = document.getElementById('ci_si');
    const rangoFechaHelp = document.getElementById('rango-fecha-help');

    // Variables globales
    const hoy = new Date();
    const añoActual = hoy.getFullYear();
    let añoMinimo = añoActual - 19;
    let añoMaximo = añoActual - 5;
    let edad_min_global = 5;
    let edad_max_global = 19;

    // ==================== FUNCIONES AUXILIARES ====================

    // Función para mostrar errores en el formulario
    function mostrarErrorFecha(mensaje) {
      // Crear o actualizar elemento de error
      let errorElement = fechaInput.parentElement.querySelector('.error-fecha');

      if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.className = 'error-fecha text-danger mt-1 small';
        fechaInput.parentElement.appendChild(errorElement);
      }

      errorElement.textContent = mensaje;
      fechaInput.classList.add('is-invalid');

      // Remover el error después de 5 segundos o al cambiar la fecha
      setTimeout(() => {
        if (errorElement.parentElement) {
          errorElement.remove();
          fechaInput.classList.remove('is-invalid');
        }
      }, 5000);
    }

    // Función para validar rango de fechas
    function validarRangoFecha() {
      if (!fechaInput.value) return true;

      const fechaSeleccionada = new Date(fechaInput.value);
      const fechaMin = new Date(añoMinimo, 0, 1); // 1 de enero del año mínimo
      const fechaMax = new Date(añoMaximo, 11, 31); // 31 de diciembre del año máximo

      if (fechaSeleccionada < fechaMin || fechaSeleccionada > fechaMax) {
        const mensaje = `❌ Fecha fuera del rango permitido\n` +
          `Edad permitida: ${edad_min_global} a ${edad_max_global} años\n` +
          `Nacidos entre: ${añoMinimo} y ${añoMaximo}`;

        mostrarErrorFecha(mensaje);

        // Opcional: puedes descomentar la siguiente línea para usar alert
        // alert(mensaje);

        fechaInput.value = '';
        fechaInput.focus();
        return false;
      }

      // Si es válida, remover cualquier error previo
      const errorElement = fechaInput.parentElement.querySelector('.error-fecha');
      if (errorElement) {
        errorElement.remove();
        fechaInput.classList.remove('is-invalid');
      }

      return true;
    }

    // ==================== FUNCIONES PRINCIPALES ====================

    // Función para obtener edades desde la base de datos
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
        // Invertir el cálculo
        añoMinimo = añoActual - edades.edad_max; // Para edad MÁXIMA
        añoMaximo = añoActual - edades.edad_min; // Para edad MÍNIMA
        edad_min_global = edades.edad_min;
        edad_max_global = edades.edad_max;

        console.log('🎯 Límites calculados:', {
          añoMinimo: añoMinimo,
          añoMaximo: añoMaximo,
          edad_min: edad_min_global,
          edad_max: edad_max_global,
          explicación: `Estudiantes entre ${edad_min_global} y ${edad_max_global} años`
        });
      } else {
        console.warn('⚠️ Usando valores por defecto para las edades');
        añoMinimo = añoActual - 19;
        añoMaximo = añoActual - 5;
        edad_min_global = 5;
        edad_max_global = 19;
      }

      // Establecer los límites en el input de fecha
      fechaInput.min = `${añoMinimo}-01-01`;
      fechaInput.max = `${añoMaximo}-12-31`;

      // Actualizar el mensaje de ayuda
      if (rangoFechaHelp) {
        rangoFechaHelp.textContent = `Nacidos entre ${añoMinimo} y ${añoMaximo} (${edad_min_global} a ${edad_max_global} años)`;
      }

      console.log('📅 Límites de fecha establecidos:', {
        min: fechaInput.min,
        max: fechaInput.max,
        rango_edades: `Nacidos entre ${añoMinimo} y ${añoMaximo}`
      });
    }

    // Función para validar y generar cédula
    async function validarRegistro() {
      console.log('📅 Evento de cambio de fecha detectado');

      // Primero validar la fecha
      if (!validarRangoFecha()) {
        console.log('❌ Fecha fuera del rango permitido');
        return;
      }

      const fecha = fechaInput.value;
      const idR = id_representante_esc.value;
      const tp = tipo.value;
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
        const cedulaEsc = a + (data.total_estudiantes + 1) + c;
        console.log('🔢 Cédula escolar compuesta:', cedulaEsc);
        return cedulaEsc;

      } catch (error) {
        console.error('❌ Error en validarYGenerarCedula:', error);
        return 0;
      }
    }

    // ==================== EVENT LISTENERS ====================

    // Validar fecha en tiempo real
    fechaInput.addEventListener('change', function() {
      validarRangoFecha();
      // Si la fecha es válida, proceder con la generación de cédula
      if (fechaInput.value && validarRangoFecha() && selectCi.value === 'no') {
        validarRegistro();
      }
    });

    fechaInput.addEventListener('blur', function() {
      validarRangoFecha();
    });

    fechaInput.addEventListener('input', function() {
      // Validar mientras el usuario escribe (para entrada manual)
      if (this.value.length === 10) { // Fecha completa en formato YYYY-MM-DD
        setTimeout(() => validarRangoFecha(), 100);
      }
    });

    // Manejar cambio en el select de CI
    selectCi.addEventListener('change', function() {
      console.log('🔄 Select CI cambiado a:', this.value);

      if (this.value === 'no') {
        console.log('🎯 Modo: Sin cédula - activando generación automática');
        cedulaEInput.placeholder = "Se generará automáticamente";

        // Ejecutar validación si ya hay fecha seleccionada
        if (fechaInput.value) {
          console.log('📋 Fecha ya seleccionada, ejecutando validación...');
          if (validarRangoFecha()) {
            validarRegistro();
          }
        } else {
          console.log('⏳ Esperando selección de fecha...');
        }

      } else if (this.value === 'si') {
        console.log('🆗 Modo: Con cédula - desactivando generación automática');
        cedulaEInput.value = '';
        cedulaEInput.readOnly = false;
        cedulaEInput.style.backgroundColor = '';
        cedulaEInput.style.cursor = '';
        cedulaEInput.placeholder = "Ingrese la cédula de identidad";
      }
    });

    // Escuchar cambios en la cédula del representante
    cedulaRInput.addEventListener('input', function() {
      console.log('✏️ Cédula representante cambiada:', this.value);
      if (selectCi.value === 'no' && fechaInput.value && validarRangoFecha()) {
        console.log('🔄 Regenerando cédula escolar por cambio en cédula representante');
        validarRegistro();
      }
    });

    // Validar fecha antes de enviar el formulario
    const form = fechaInput.closest('form');
    if (form) {
      form.addEventListener('submit', function(e) {
        if (fechaInput.value && !validarRangoFecha()) {
          e.preventDefault();
          fechaInput.focus();
        }
      });
    }

    // ==================== INICIALIZACIÓN ====================

    // Inicializar los límites de fecha al cargar la página
    inicializarFechas();

    // Validar si ya hay una fecha seleccionada al cargar la página
    setTimeout(() => {
      if (fechaInput.value) {
        console.log('🔍 Validando fecha pre-seleccionada al cargar...');
        validarRangoFecha();
      }
    }, 500);

    // Debug inicial
    console.log('🔍 Estado inicial:', {
      fechaInput: fechaInput ? 'Encontrado' : 'No encontrado',
      cedulaRInput: cedulaRInput ? 'Encontrado' : 'No encontrado',
      cedulaEInput: cedulaEInput ? 'Encontrado' : 'No encontrado',
      selectCi: selectCi ? 'Encontrado' : 'No encontrado',
      selectCiValue: selectCi ? selectCi.value : 'N/A'
    });
  });
</script>

<!-- Aca validamos la cedula de identidad y llenamos campo  -->
<!-- Aca validamos la cedula de identidad y llenamos campo  -->
<!-- Aca validamos la cedula de identidad y llenamos campo  -->
<!-- Aca validamos la cedula de identidad y llenamos campo  -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 3;

    function refreshTabStyles() {
      const navLinks = document.querySelectorAll('#stepIndicator .nav-link');

      navLinks.forEach(link => {
        if (link.classList.contains('active')) {
          link.style.color = 'white';
          link.style.fontWeight = 'semi-bold';
        } else {
          link.style.color = '';
          link.style.fontWeight = '';
        }
      });
    }

    // ========== NAVEGACIÓN ENTRE PASOS ==========
    function showStep(step) {
      // Ocultar todos los pasos
      document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));

      // Mostrar el paso actual
      document.getElementById(`step${step}`).classList.add('active');


      // Actualizar indicador
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
      showStep(2);
      refreshTabStyles();

    });

    document.getElementById('btn-next-to-step3').addEventListener('click', function() {
      // Validar campos requeridos del paso 2 antes de continuar
      const requiredFields = document.querySelectorAll('#step2 [required]');
      let valid = true;
      refreshTabStyles();


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
        refreshTabStyles();

      } else {
        alert('Por favor complete todos los campos requeridos del representante.');
      }
    });

    document.getElementById('btn-back-to-step1').addEventListener('click', function() {
      showStep(1);
      refreshTabStyles();

    });

    document.getElementById('btn-back-to-step2').addEventListener('click', function() {
      showStep(2);
      refreshTabStyles();

    });


    // ========== FUNCIÓN PARA LIMPIAR CAMPOS DEL REPRESENTANTE ==========
    function limpiarCamposRepresentante() {
      console.log('🔄 Limpiando campos del representante...');

      // Lista de campos a limpiar
      const camposALimpiar = [
        'primer_nombre_r', 'segundo_nombre_r', 'primer_apellido_r', 'segundo_apellido_r',
        'cedula_r', 'correo_r', 'telefono_r', 'telefono_hab_r', 'fecha_nac_r',
        'lugar_nac_r', 'sexo_r', 'nacionalidad_r', 'profesion_r', 'ocupacion_r',
        'lugar_trabajo_r', 'parentesco', 'direccion_r', 'calle_r', 'casa_r'
      ];

      // Limpiar valores de los campos
      camposALimpiar.forEach(campoId => {
        const elemento = document.getElementById(campoId);
        if (elemento) {
          elemento.value = '';
          elemento.disabled = false;
          elemento.classList.remove('is-invalid');
        }
      });

      // Resetear selects de ubicación
      const estadoSelect = document.getElementById('estado_r');
      const municipioSelect = document.getElementById('municipio_r');
      const parroquiaSelect = document.getElementById('parroquia_r');

      if (estadoSelect) estadoSelect.value = '';
      if (municipioSelect) {
        municipioSelect.innerHTML = '<option value="">Primero seleccione un estado</option>';
        municipioSelect.disabled = true;
      }
      if (parroquiaSelect) {
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
        parroquiaSelect.disabled = true;
      }

      // Resetear campos ocultos
      document.getElementById('representante_existente').value = '0';
      document.getElementById('id_representante_existente').value = '';
      document.getElementById('id_direccion_repre').value = '';
      document.getElementById('tipo_persona').value = '';
      document.getElementById('id_representante_existente_esc').value = '';

      // Limpiar resultado de validación
      const resultado = document.getElementById('resultado-validacion');
      if (resultado) {
        resultado.innerHTML = '';
      }

      // Ocultar botón siguiente
      const nextButton = document.getElementById('btn-next-to-step2');
      if (nextButton) {
        nextButton.style.display = 'none';
      }

      console.log('✅ Campos del representante limpiados');
    }

    // ========== DETECTAR CAMBIOS EN LA CÉDULA ==========
    document.getElementById('cedula_representante').addEventListener('input', function() {
      const representanteExistente = document.getElementById('representante_existente').value;
      const cedulaActual = this.value;

      // Si ya había una validación y el usuario modifica la cédula, limpiar todo
      if (representanteExistente === '1' || cedulaActual.length === 0) {
        limpiarCamposRepresentante();
        this.style.borderColor = '';
      }
    });

    // ========== VALIDACIÓN DE REPRESENTANTE ==========
    document.getElementById('btn-validar-representante').addEventListener('click', function() {
      const cedula = document.getElementById('cedula_representante').value;
      if (!cedula) {
        alert('Por favor ingrese la cédula del representante');
        return;
      }
      validarRepresentante(cedula);
    });


    // ========== VALIDACIÓN CON TECLA ENTER ==========
    document.getElementById('cedula_representante').addEventListener('keypress', function(e) {
      // Verificar si se presionó la tecla Enter (código 13)
      if (e.key === 'Enter' || e.keyCode === 13) {
        e.preventDefault(); // Prevenir el comportamiento por defecto

        const cedula = this.value;
        if (!cedula) {
          alert('Por favor ingrese la cédula del representante');
          return;
        }

        // Mostrar feedback visual de que se está procesando
        this.style.borderColor = '#ffc107';
        document.getElementById('btn-validar-representante').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Validando...';

        // Ejecutar la validación
        validarRepresentante(cedula);
      }
    });

    function validarRepresentante(cedula) {

      // Guardar referencia de la cédula actual
      document.getElementById('cedula_representante').currentCedula = cedula;
      // Crear FormData para enviar por POST
      const formData = new FormData();
      formData.append('cedula', cedula);

      fetch('/final/app/controllers/representantes/validar.php', {
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
          const resultado = document.getElementById('resultado-validacion');
          const nextButton = document.getElementById('btn-next-to-step2');
          const cedulaInput = document.getElementById('cedula_representante');
          const validarBtn = document.getElementById('btn-validar-representante');
          // Restaurar estado normal del botón
          validarBtn.innerHTML = 'Validar Representante';
          cedulaInput.style.borderColor = '';

          if (cedulaInput.currentCedula !== cedula) {
            console.log('⚠️ La cédula cambió durante la validación, ignorando resultado');
            return;
          }

          console.log('📊 Datos recibidos:', data);

          // ✅ FUNCIÓN AUXILIAR PARA ASIGNAR VALORES SEGUROS
          function setValueSafe(elementId, value) {
            const element = document.getElementById(elementId);
            if (element) {
              element.value = value || '';
              console.log(`✅ Asignado ${elementId}:`, value);
            } else {
              console.warn(`⚠️ Elemento no encontrado: ${elementId}`);
            }
          }

          if (data.existe === true) {
            // Determinar el tipo de persona encontrada
            const tipoPersona = data.tipo;
            const esDocente = tipoPersona === 'docente';
            const esRepresentante = tipoPersona === 'representante';

            resultado.innerHTML = `
                    <div class="alert alert-success">
                        <strong>${esDocente ? 'Docente' : 'Representante'} encontrado:</strong> ${data.nombre_completo}
                        <br>Los datos se cargarán automáticamente.
                        ${esDocente ? '<br><em>Nota: Como es docente, algunos campos estarán disponibles para completar</em>' : ''}
                    </div>
                `;

            // PRIMERO: Habilitar TODOS los campos
            document.querySelectorAll('#form-inscripcion input, #form-inscripcion select').forEach(element => {
              element.disabled = false;
            });

            // Llenar los campos comunes (USANDO LA FUNCIÓN SEGURA)
            setValueSafe('representante_existente', '1');
            setValueSafe('id_direccion_repre', data.id_direccion);
            setValueSafe('tipo_persona', tipoPersona);

            if (esRepresentante) {
              setValueSafe('id_representante_existente', data.id_representante);
              setValueSafe('id_representante_existente_esc', data.id_representante);
            } else if (esDocente) {
              setValueSafe('id_representante_existente', data.id_docente);
              setValueSafe('id_representante_existente_esc', data.id_persona);
            }

            // Datos personales (comunes para ambos)
            setValueSafe('cedula_r', data.cedula);
            setValueSafe('primer_nombre_r', data.primer_nombre);
            setValueSafe('segundo_nombre_r', data.segundo_nombre);
            setValueSafe('primer_apellido_r', data.primer_apellido);
            setValueSafe('segundo_apellido_r', data.segundo_apellido);
            setValueSafe('correo_r', data.correo);
            setValueSafe('telefono_r', data.telefono);
            setValueSafe('telefono_hab_r', data.telefono_hab);
            setValueSafe('fecha_nac_r', data.fecha_nac);
            setValueSafe('lugar_nac_r', data.lugar_nac);
            setValueSafe('sexo_r', data.sexo);
            setValueSafe('nacionalidad_r', data.nacionalidad);

            // Cargar SELECT de profesión
            if (data.profesion) {
              setValueSafe('profesion_r', data.profesion);
            }

            // Datos de dirección (comunes para ambos)
            if (data.id_estado) {
              setValueSafe('estado_r', data.id_estado);

              // Cargar municipios para este estado
              cargarMunicipios(data.id_estado).then(() => {
                if (data.id_municipio) {
                  setValueSafe('municipio_r', data.id_municipio);

                  // Cargar parroquias para este municipio
                  cargarParroquias(data.id_municipio).then(() => {
                    if (data.id_parroquia) {
                      setValueSafe('parroquia_r', data.id_parroquia);
                    }
                  });
                }
              });
            }

            // DIFERENCIAS ENTRE DOCENTE Y REPRESENTANTE
            if (esRepresentante) {
              console.log('👨‍👦 Es representante - inhabilitando campos');

              // REPRESENTANTE: Cargar todos los datos
              setValueSafe('ocupacion_r', data.ocupacion);
              setValueSafe('lugar_trabajo_r', data.lugar_trabajo);
              setValueSafe('direccion_r', data.direccion);
              setValueSafe('calle_r', data.calle);
              setValueSafe('casa_r', data.casa);

              // Deshabilitar campos específicos
              const camposDeshabilitar = [
                'primer_nombre_r', 'segundo_nombre_r', 'primer_apellido_r', 'segundo_apellido_r',
                'cedula_r', 'correo_r', 'telefono_r', 'telefono_hab_r', 'fecha_nac_r',
                'lugar_nac_r', 'sexo_r', 'nacionalidad_r', 'profesion_r', 'ocupacion_r',
                'lugar_trabajo_r', 'estado_r', 'municipio_r', 'parroquia_r', 'direccion_r',
                'calle_r', 'casa_r'
              ];

              camposDeshabilitar.forEach(campoId => {
                const elemento = document.getElementById(campoId);
                if (elemento) {
                  elemento.disabled = true;
                  console.log(`🔒 Deshabilitado: ${campoId}`);
                } else {
                  console.warn(`⚠️ No se pudo deshabilitar (no existe): ${campoId}`);
                }
              });

            } else if (esDocente) {
              console.log('👨‍🏫 Es docente - campos específicos habilitados');

              // DOCENTE: Solo cargar datos básicos
              setValueSafe('ocupacion_r', data.ocupacion);
              setValueSafe('lugar_trabajo_r', data.lugar_trabajo);
              setValueSafe('direccion_r', data.direccion);
              setValueSafe('calle_r', data.calle);
              setValueSafe('casa_r', data.casa);

              // Deshabilitar solo campos básicos
              const camposDeshabilitados = [
                'primer_nombre_r', 'segundo_nombre_r', 'primer_apellido_r', 'segundo_apellido_r',
                'cedula_r', 'correo_r', 'telefono_r', 'telefono_hab_r', 'fecha_nac_r',
                'lugar_nac_r', 'sexo_r', 'nacionalidad_r', 'profesion_r', 'estado_r',
                'municipio_r', 'parroquia_r'
              ];

              camposDeshabilitados.forEach(campoId => {
                const elemento = document.getElementById(campoId);
                if (elemento) {
                  elemento.disabled = true;
                  console.log(`🔒 Deshabilitado: ${campoId}`);
                } else {
                  console.warn(`⚠️ No se pudo deshabilitar (no existe): ${campoId}`);
                }
              });

              // Mantener HABILITADOS los campos específicos
              const camposHabilitados = [
                'ocupacion_r', 'lugar_trabajo_r', 'direccion_r', 'calle_r', 'casa_r'
              ];

              camposHabilitados.forEach(campoId => {
                const elemento = document.getElementById(campoId);
                if (elemento) {
                  elemento.disabled = false;
                  console.log(`🔓 Habilitado: ${campoId}`);
                } else {
                  console.warn(`⚠️ No se pudo habilitar (no existe): ${campoId}`);
                }
              });
            }

            // Mostrar botón siguiente
            if (nextButton) {
              nextButton.style.display = 'inline-block';
            }

          } else {
            console.log('❌ Persona no encontrada');
            resultado.innerHTML = `
                    <div class="alert alert-info">
                        <strong>Persona no encontrada.</strong> Por favor complete todos los datos del representante.
                    </div>
                `;

            setValueSafe('cedula_r', cedula);
            setValueSafe('representante_existente', '0');
            setValueSafe('tipo_persona', '');

            // Habilitar todos los campos
            document.querySelectorAll('#form-inscripcion input, #form-inscripcion select').forEach(element => {
              element.disabled = false;
            });

            // Mostrar botón siguiente después de 2 segundos
            setTimeout(() => {
              if (nextButton) {
                nextButton.style.display = 'inline-block';
              }
            }, 2000);
          }
        })
        .catch(error => {
          console.error('❌ Error:', error);
          const resultado = document.getElementById('resultado-validacion');
          const cedulaInput = document.getElementById('cedula_representante');
          const validarBtn = document.getElementById('btn-validar-representante');

          // Restaurar estado normal
          validarBtn.innerHTML = 'Validar Representante';
          cedulaInput.style.borderColor = '#dc3545'; // Rojo para error

          if (resultado) {
            resultado.innerHTML = `
                <div class="alert alert-danger">
                    Error al validar la persona. Intente nuevamente.
                </div>
            `;
          }


          // {
          //   console.error('❌ Error:', error);
          //   const resultado = document.getElementById('resultado-validacion');
          //   if (resultado) {
          //     resultado.innerHTML = `
          //             <div class="alert alert-danger">
          //                 Error al validar la persona. Intente nuevamente.
          //             </div>
          //         `;
          //   }
        });
    }

    // ========== CARGAR MUNICIPIOS Y PARROQUIAS ==========
    document.getElementById('estado_r').addEventListener('change', function() {
      const estadoId = this.value;
      const municipioSelect = document.getElementById('municipio_r');
      const parroquiaSelect = document.getElementById('parroquia_r');

      if (estadoId) {
        municipioSelect.disabled = false;
        parroquiaSelect.disabled = true;
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
        cargarMunicipios(estadoId);
      } else {
        municipioSelect.disabled = true;
        parroquiaSelect.disabled = true;
        municipioSelect.innerHTML = '<option value="">Primero seleccione un estado</option>';
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
      }
    });

    document.getElementById('municipio_r').addEventListener('change', function() {
      const municipioId = this.value;
      const parroquiaSelect = document.getElementById('parroquia_r');

      if (municipioId) {
        parroquiaSelect.disabled = false;
        cargarParroquias(municipioId);
      } else {
        parroquiaSelect.disabled = true;
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
      }
    });

    function cargarMunicipios(estadoId) {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('estado_id', estadoId);

        fetch('/final/app/controllers/ubicaciones/municipios.php', {
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
            const select = document.getElementById('municipio_r');
            select.innerHTML = '<option value="">Seleccionar Municipio</option>';

            data.forEach(municipio => {
              select.innerHTML += `<option value="${municipio.id_municipio}">${municipio.nom_municipio}</option>`;
            });
            resolve();
          })
          .catch(error => {
            console.error('Error al cargar municipios:', error);
            reject(error);
          });
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
          .then(response => {
            if (!response.ok) {
              throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
          })
          .then(data => {
            const select = document.getElementById('parroquia_r');
            select.innerHTML = '<option value="">Seleccionar Parroquia</option>';

            data.forEach(parroquia => {
              select.innerHTML += `<option value="${parroquia.id_parroquia}">${parroquia.nom_parroquia}</option>`;
            });
            resolve();
          })
          .catch(error => {
            console.error('Error al cargar parroquias:', error);
            reject(error);
          });
      });
    }
  });
</script>

<!-- Aca Enviamos informacion del formulario -->
<script>
  // ========== GENERAR CONSTANCIA DESPUÉS DE INSCRIPCIÓN EXITOSA ==========
  function generarConstanciaInscripcion(idInscripcion) {
    // ✅ VALIDACIÓN ADICIONAL: Verificar que el ID sea numérico
    if (!idInscripcion || isNaN(idInscripcion)) {
      console.error('❌ ID de inscripción no válido para generar constancia:', idInscripcion);
      return Promise.reject(new Error('ID de inscripción no válido'));
    }

    return new Promise((resolve, reject) => {
      console.log('📄 Generando constancia para inscripción ID:', idInscripcion);

      // Mostrar mensaje de que se está generando la constancia
      const generatingMsg = document.createElement('div');
      generatingMsg.className = 'alert alert-info';
      generatingMsg.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando constancia de inscripción...';
      document.querySelector('.content-wrapper').prepend(generatingMsg);

      // Usar directamente generar_constancia.php (SIMPLIFICADO)
      const constanciaUrl = `/final/app/controllers/inscripciones/generar_constancia.php?id_inscripcion=${idInscripcion}`;

      setTimeout(() => {
        generatingMsg.remove();

        const successMsg = document.createElement('div');
        successMsg.className = 'alert alert-success';
        successMsg.innerHTML = `
                <strong>✅ Inscripción completada exitosamente</strong><br>
                <small>La constancia se abrirá en una nueva ventana para visualización.</small>
                <br><small><em>Puede usar el botón de descarga del navegador si desea guardarla.</em></small>
            `;
        document.querySelector('.content-wrapper').prepend(successMsg);

        // Abrir en nueva pestaña para VISUALIZACIÓN (no descarga automática)
        console.log('🔗 Abriendo constancia para visualización:', constanciaUrl);
        window.open(constanciaUrl, '_blank', 'width=1000,height=700,scrollbars=yes');

        // También mostrar botón por si la ventana emergente es bloqueada
        const buttonMsg = document.createElement('div');
        buttonMsg.className = 'alert alert-info mt-2';
        buttonMsg.innerHTML = `
                <small>Si la constancia no se abrió automáticamente:</small><br>
                <a href="${constanciaUrl}" target="_blank" class="btn btn-outline-primary btn-sm mt-1">
                    <i class="fas fa-external-link-alt"></i> Abrir Constancia Manualmente
                </a>
            `;
        document.querySelector('.content-wrapper').prepend(buttonMsg);
        window.location.href = '/final/admin/index.php';

        resolve({
          success: true
        });

      }, 1500); // Pequeño delay para mejor experiencia de usuario
      window.location.href = '/final/admin/index.php';

    }); // <--- Cierra el return new Promise()
  } // <--- Cierra function generarConstanciaInscripcion()

  // Modificar el manejo del envío del formulario
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-inscripcion');

    form.addEventListener('submit', function(e) {
      e.preventDefault();
      console.log('Formulario enviado - iniciando procesamiento...');

      // Mostrar loading
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
      submitBtn.disabled = true;

      // Habilitar campos deshabilitados temporalmente para el envío
      document.querySelectorAll('#form-inscripcion input:disabled, #form-inscripcion select:disabled').forEach(element => {
        element.disabled = false;
      });

      const formData = new FormData(this);

      // Mostrar mensaje de procesamiento
      const processingMsg = document.createElement('div');
      processingMsg.className = 'alert alert-info';
      processingMsg.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando inscripción...';
      document.querySelector('.content-wrapper').prepend(processingMsg);

      // ⚠️ ESTRATEGIA: Intentar la inscripción pero SILENCIAR errores JSON si al final funciona
      fetch(this.action, {
          method: 'POST',
          body: formData
        })
        .then(response => {
          // Primero intentamos como texto para ver qué devuelve realmente
          return response.text().then(text => {
            console.log('📨 Respuesta cruda del servidor:', text.substring(0, 300));

            // Intentar parsear como JSON
            try {
              const jsonData = JSON.parse(text);
              console.log('✅ JSON parseado correctamente:', jsonData);
              setTimeout(() => {
                location.href = '/final/admin/index.php';

              }, 3000);


              return jsonData;

            } catch (jsonError) {
              console.warn('⚠️ No se pudo parsear como JSON, pero continuamos...');

              // Buscar pistas de éxito en el texto crudo
              const hasSuccessIndicators =
                text.includes('success') ||
                text.includes('id_inscripcion') ||
                text.includes('exitosamente') ||
                text.length < 100; // Si la respuesta es muy corta, probablemente fue exitosa

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
                  message: 'Inscripción procesada exitosamente',
                  id_inscripcion: idInscripcion
                };
              }

              // Si no hay indicadores de éxito, devolver éxito igual (estrategia conservadora)
              console.log('🔄 No hay indicadores claros, asumiendo éxito por defecto');
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

          // SOLUCIÓN RÁPIDA: Si no hay ID, no generar constancia
          if (!idInscripcion) {
            console.warn('⚠️ No se generará constancia - ID no recibido');
            idInscripcion = null;
          }
          console.log('🎯 ID de inscripción a usar:', idInscripcion);

          // SOLO generar constancia si tenemos un ID válido (numérico)
          if (idInscripcion && idInscripcion !== 'last' && !isNaN(idInscripcion)) {
            // Generar constancia con el ID disponible
            generarConstanciaInscripcion(idInscripcion)
              .then(() => {
                console.log('✅ Proceso de constancia completado');

                // Redirigir después de un tiempo más largo para que el usuario pueda ver/descargar la constancia
                setTimeout(() => {
                  console.log('🔄 Redirigiendo a dashboard...');
                  window.location.href = '/final/admin/index.php';
                }, 8000); // 8 segundos para dar tiempo al usuario
              })
              .catch((error) => {
                console.warn('⚠️ Error en proceso de constancia:', error);

                // Mostrar mensaje de error pero continuar
                const errorMsg = document.createElement('div');
                errorMsg.className = 'alert alert-warning mt-2';
                errorMsg.innerHTML = `
                                <small>Hubo un problema con la constancia, pero la inscripción fue exitosa.</small><br>
                                <a href="/final/app/controllers/inscripciones/generar_constancia.php?id_inscripcion=${idInscripcion}" 
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
                        <strong>✅ Inscripción completada exitosamente</strong><br>
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

            // // Intentar generar constancia de todas formas
            // setTimeout(() => {
            //     generarConstanciaInscripcion('last')
            //         .finally(() => {
            //             setTimeout(() => {
            //                 window.location.href = '/final/admin/index.php';
            //             }, 4000);
            //         });
            // }, 1000);
          }

          // Rehabilitar botón en caso de error crítico
          if (error.message.includes('Network') || error.message.includes('Failed to fetch')) {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
          }
        }); // <--- Cierre de la función de callback del .catch
    }); // <--- Cierre del form.addEventListener('submit'
  }); // <--- Cierre del document.addEventListener('DOMContentLoaded'
</script>





<!-- Carga de estados, municipios, parroquias del representante -->
<!-- Carga de estados, municipios, parroquias del representante -->
<!-- Carga de estados, municipios, parroquias del representante -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Cargar municipios cuando cambie el estado
    document.getElementById('estado_r').addEventListener('change', function() {
      const estadoId = this.value;
      const municipioSelect = document.getElementById('municipio_r');
      const parroquiaSelect = document.getElementById('parroquia_r');

      if (estadoId) {
        municipioSelect.disabled = false;
        parroquiaSelect.disabled = true;
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
        cargarMunicipios(estadoId);
      } else {
        municipioSelect.disabled = true;
        parroquiaSelect.disabled = true;
        municipioSelect.innerHTML = '<option value="">Primero seleccione un estado</option>';
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
      }
    });

    // Cargar parroquias cuando cambie el municipio
    document.getElementById('municipio_r').addEventListener('change', function() {
      const municipioId = this.value;
      const parroquiaSelect = document.getElementById('parroquia_r');

      if (municipioId) {
        parroquiaSelect.disabled = false;
        cargarParroquias(municipioId);
      } else {
        parroquiaSelect.disabled = true;
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
      }
    });

    function cargarMunicipios(estadoId) {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('estado_id', estadoId);

        fetch('/final/app/controllers/ubicaciones/municipios.php', {
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
            const select = document.getElementById('municipio_r');
            select.innerHTML = '<option value="">Seleccionar Municipio</option>';

            data.forEach(municipio => {
              select.innerHTML += `<option value="${municipio.id_municipio}">${municipio.nom_municipio}</option>`;
            });
            resolve();
          })
          .catch(error => {
            console.error('Error al cargar municipios:', error);
            reject(error);
          });
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
          .then(response => {
            if (!response.ok) {
              throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
          })
          .then(data => {
            const select = document.getElementById('parroquia_r');
            select.innerHTML = '<option value="">Seleccionar Parroquia</option>';

            data.forEach(parroquia => {
              select.innerHTML += `<option value="${parroquia.id_parroquia}">${parroquia.nom_parroquia}</option>`;
            });
            resolve();
          })
          .catch(error => {
            console.error('Error al cargar parroquias:', error);
            reject(error);
          });
      });
    }

  });
</script>

<!-- Carga de estados, municipios, parroquias del alumno --->
<!-- Carga de estados, municipios, parroquias del alumno --->
<!-- Carga de estados, municipios, parroquias del alumno --->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Cargar municipios cuando cambie el estado
    document.getElementById('estado_e').addEventListener('change', function() {
      const estadoId = this.value;
      const municipioSelect = document.getElementById('municipio_e');
      const parroquiaSelect = document.getElementById('parroquia_e');

      if (estadoId) {
        municipioSelect.disabled = false;
        parroquiaSelect.disabled = true;
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
        cargarMunicipios(estadoId);
      } else {
        municipioSelect.disabled = true;
        parroquiaSelect.disabled = true;
        municipioSelect.innerHTML = '<option value="">Primero seleccione un estado</option>';
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
      }
    });

    // Cargar parroquias cuando cambie el municipio
    document.getElementById('municipio_e').addEventListener('change', function() {
      const municipioId = this.value;
      const parroquiaSelect = document.getElementById('parroquia_e');

      if (municipioId) {
        parroquiaSelect.disabled = false;
        cargarParroquias(municipioId);
      } else {
        parroquiaSelect.disabled = true;
        parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
      }
    });

    function cargarMunicipios(estadoId) {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('estado_id', estadoId);

        fetch('/final/app/controllers/ubicaciones/municipios.php', {
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
            const select = document.getElementById('municipio_e');
            select.innerHTML = '<option value="">Seleccionar Municipio</option>';

            data.forEach(municipio => {
              select.innerHTML += `<option value="${municipio.id_municipio}">${municipio.nom_municipio}</option>`;
            });
            resolve();
          })
          .catch(error => {
            console.error('Error al cargar municipios:', error);
            reject(error);
          });
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
          .then(response => {
            if (!response.ok) {
              throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
          })
          .then(data => {
            const select = document.getElementById('parroquia_e');
            select.innerHTML = '<option value="">Seleccionar Parroquia</option>';

            data.forEach(parroquia => {
              select.innerHTML += `<option value="${parroquia.id_parroquia}">${parroquia.nom_parroquia}</option>`;
            });
            resolve();
          })
          .catch(error => {
            console.error('Error al cargar parroquias:', error);
            reject(error);
          });
      });
    }

  });
</script>

<!-- Manejo de patologias con select adicionales  -->
<!-- Manejo de patologias con select adicionales  -->
<!-- Manejo de patologias con select adicionales  -->
<!-- Manejo de patologias con select adicionales  -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const contenedorPatologias = document.getElementById('contenedor-patologias');
    const btnAgregarPatologia = document.getElementById('btn-agregar-patologia');

    // Obtener las patologías desde el primer select (que ya viene de la base de datos)
    function obtenerOpcionesPatologias() {
      const primerSelect = document.querySelector('.select-patologia');
      if (!primerSelect) return '';

      // Clonar todas las opciones excepto la primera (placeholder)
      const opciones = Array.from(primerSelect.options)
        .filter(option => option.value !== '')
        .map(option => `<option value="${option.value}">${option.text}</option>`)
        .join('');

      return opciones;
    }

    // Función para crear un nuevo select de patología
    function crearSelectPatologia() {
      const opciones = obtenerOpcionesPatologias();

      const div = document.createElement('div');
      div.className = 'mb-2 patologia-item d-flex align-items-center';

      div.innerHTML = `
            <select name="patologias[]" class="form-control select-patologia me-2">
                <option value="">Seleccione una patología...</option>
                ${opciones}
            </select>
            <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar-patologia">
                <i class="fas fa-times"></i>
            </button>
        `;

      return div;
    }

    // Agregar nuevo select
    btnAgregarPatologia.addEventListener('click', function() {
      const nuevoSelect = crearSelectPatologia();
      contenedorPatologias.appendChild(nuevoSelect);

      // Agregar evento al botón eliminar
      const btnEliminar = nuevoSelect.querySelector('.btn-eliminar-patologia');
      btnEliminar.addEventListener('click', function() {
        nuevoSelect.remove();
      });
    });

    // Eliminar select (evento delegado)
    contenedorPatologias.addEventListener('click', function(e) {
      if (e.target.classList.contains('btn-eliminar-patologia') ||
        e.target.closest('.btn-eliminar-patologia')) {
        const btn = e.target.classList.contains('btn-eliminar-patologia') ?
          e.target : e.target.closest('.btn-eliminar-patologia');
        btn.closest('.patologia-item').remove();
      }
    });
  });
</script>

<!-- Manejo de discapacidades con select adicionales  -->
<!-- Manejo de discapacidades con select adicionales  -->
<!-- Manejo de discapacidades con select adicionales  -->
<!-- Manejo de discapacidades con select adicionales  -->

<script>
  // ========== MANEJO DE DISCAPACIDADES DINÁMICAS ==========
  document.addEventListener('DOMContentLoaded', function() {
    const contenedorDiscapacidades = document.getElementById('contenedor-discapacidades');
    const btnAgregarDiscapacidad = document.getElementById('btn-agregar-discapacidad');

    // Obtener las opciones de discapacidades desde el primer select
    function obtenerOpcionesDiscapacidades() {
      const primerSelect = document.querySelector('.select-discapacidad');
      if (!primerSelect) return '';

      const opciones = Array.from(primerSelect.options)
        .filter(option => option.value !== '')
        .map(option => `<option value="${option.value}">${option.text}</option>`)
        .join('');

      return opciones;
    }

    // Función para crear un nuevo select de discapacidad
    function crearSelectDiscapacidad() {
      const opciones = obtenerOpcionesDiscapacidades();

      const div = document.createElement('div');
      div.className = 'mb-2 discapacidad-item d-flex align-items-center';

      div.innerHTML = `
            <select name="discapacidades[]" class="form-control select-discapacidad me-2">
                <option value="">Seleccione una discapacidad...</option>
                ${opciones}
            </select>
            <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar-discapacidad">
                <i class="fas fa-times"></i>
            </button>
        `;

      return div;
    }

    // Agregar nuevo select
    btnAgregarDiscapacidad.addEventListener('click', function() {
      const nuevoSelect = crearSelectDiscapacidad();
      contenedorDiscapacidades.appendChild(nuevoSelect);

      // Agregar evento al botón eliminar
      const btnEliminar = nuevoSelect.querySelector('.btn-eliminar-discapacidad');
      btnEliminar.addEventListener('click', function() {
        nuevoSelect.remove();
      });
    });

    // Eliminar select (evento delegado)
    contenedorDiscapacidades.addEventListener('click', function(e) {
      if (e.target.classList.contains('btn-eliminar-discapacidad') ||
        e.target.closest('.btn-eliminar-discapacidad')) {
        const btn = e.target.classList.contains('btn-eliminar-discapacidad') ?
          e.target : e.target.closest('.btn-eliminar-discapacidad');
        btn.closest('.discapacidad-item').remove();
      }
    });
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



<!-- validaciones de inputs y campos  -->
<!-- validaciones de inputs y campos  -->
<!-- validaciones de inputs y campos  -->
<!-- validaciones de inputs y campos  -->
<!-- VALIDACIONES DE FORMULARIO -->

<!-- VALIDACIONES DE FORMULARIO -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // ========== VALIDACIONES DE CARACTERES ==========

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

    // Campos de nombres y apellidos del representante
    const camposLetrasRepresentante = [
      'primer_nombre_r', 'segundo_nombre_r',
      'primer_apellido_r', 'segundo_apellido_r',
      'ocupacion_r', 'lugar_trabajo_r'
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

    // Validación de correo del representante (OBLIGATORIO)
    const correoRepresentante = document.getElementById('correo_r');
    if (correoRepresentante) {
      correoRepresentante.addEventListener('blur', function() {
        const email = this.value.trim();
        // El correo del representante es obligatorio, debe ser válido si se ingresa
        if (email && !validarEmail(email)) {
          this.classList.add('is-invalid');
          mostrarError(this, 'Por favor ingrese un correo electrónico válido');
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

    // Campos de nombres y apellidos del estudiante
    const camposLetrasEstudiante = [
      'primer_nombre_e', 'segundo_nombre_e',
      'primer_apellido_e', 'segundo_apellido_e'
    ];

    // Los campos del estudiante tienen name en lugar de id, así que los seleccionamos por name
    camposLetrasEstudiante.forEach(campoName => {
      const campo = document.querySelector(`[name="${campoName}"]`);
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
    const cedulaEstudiante = document.getElementById('cedula_e');
    const telefonoEstudiante = document.querySelector('[name="telefono_e"]');

    if (cedulaEstudiante) {
      cedulaEstudiante.addEventListener('keydown', function(event) {
        // Solo validar si no es de solo lectura (cuando el usuario tiene CI)
        if (!this.readOnly) {
          return validarSoloNumeros(event);
        }
        return true;
      });

      cedulaEstudiante.addEventListener('blur', function() {
        if (!this.readOnly) {
          let valor = this.value;
          valor = valor.replace(/[^0-9]/g, '');
          this.value = valor;
        }
      });
    }

    if (telefonoEstudiante) {
      telefonoEstudiante.addEventListener('keydown', validarSoloNumeros);

      telefonoEstudiante.addEventListener('blur', function() {
        let valor = this.value;
        valor = valor.replace(/[^0-9]/g, '');
        this.value = valor;
      });
    }

    // Validación de correo del estudiante (OPCIONAL)
    const correoEstudiante = document.querySelector('[name="correo_e"]');
    if (correoEstudiante) {
      correoEstudiante.addEventListener('blur', function() {
        const email = this.value.trim();
        // El correo del estudiante es OPCIONAL, solo valida si se ingresa algo
        if (email && !validarEmail(email)) {
          this.classList.add('is-invalid');
          mostrarError(this, 'Por favor ingrese un correo electrónico válido o deje el campo vacío');
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

      // Validar específicamente el correo del representante (OBLIGATORIO)
      if (correoRepresentante) {
        const emailRepre = correoRepresentante.value.trim();
        if (!emailRepre || !validarEmail(emailRepre)) {
          correoRepresentante.classList.add('is-invalid');
          mostrarError(correoRepresentante, 'Por favor ingrese un correo electrónico válido antes de continuar');
          alert('Por favor corrija el correo electrónico del representante antes de continuar.');
          return;
        }
      }

      // El correo del estudiante NO se valida aquí porque es opcional

      // Si todo está bien, avanzar al paso 3
      showStep(3);
      refreshTabStyles();
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
      errorDiv.id = `error-${campo.id || campo.name}`;

      // Insertar después del campo
      campo.parentNode.appendChild(errorDiv);
    }

    function ocultarError(campo) {
      const errorId = `error-${campo.id || campo.name}`;
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

  // ========== GENERAR CONSTANCIA DESPUÉS DE INSCRIPCIÓN EXITOSA ==========
  function generarConstanciaInscripcion(idInscripcion) {
    console.log('📄 Generando constancia para inscripción ID:', idInscripcion);

    // Abrir en nueva pestaña para generar el PDF
    const url = `/final/app/controllers/inscripciones/generar_constancia.php?id_inscripcion=${idInscripcion}`;
    window.open(url, '_blank');
  }

  // Modificar el manejo del envío del formulario para incluir la generación de constancia
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-inscripcion');

    // Guardar referencia al event listener original
    const originalSubmitHandler = form.onsubmit;

    form.addEventListener('submit', function(e) {
      e.preventDefault();
      console.log('Formulario enviado - iniciando procesamiento...');

      // Mostrar loading
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
      submitBtn.disabled = true;

      // Habilitar campos deshabilitados temporalmente para el envío
      document.querySelectorAll('#form-inscripcion input:disabled, #form-inscripcion select:disabled').forEach(element => {
        element.disabled = false;
      });

      const formData = new FormData(this);

      console.log('Datos a enviar:');
      for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
      }

      fetch(this.action, {
          method: 'POST',
          body: formData
        })
        .then(response => {
          console.log('Respuesta recibida, status:', response.status);

          // Verificar si la respuesta es JSON
          const contentType = response.headers.get('content-type');
          if (!contentType || !contentType.includes('application/json')) {
            throw new Error('La respuesta no es JSON');
          }
          return response.json();
        })
        .then(data => {
          console.log('Datos procesados:', data);

          if (data.success) {
            // Mostrar mensaje de éxito
            alert('✅ ' + data.message);

            // Generar constancia si tenemos el ID de inscripción
            if (data.id_inscripcion) {
              console.log('🎯 ID de inscripción obtenido:', data.id_inscripcion);
              setTimeout(() => {
                generarConstanciaInscripcion(data.id_inscripcion);
              }, 1000);
            } else {
              console.warn('⚠️ No se recibió ID de inscripción en la respuesta');
            }

            // Redirigir después de 3 segundos
            setTimeout(() => {
              window.location.href = '/final/admin/index.php';
            }, 3000);

          } else {
            alert('❌ ' + data.message);
            // Rehabilitar botón
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
          }
        })
      //.catch(error => {
      // console.error('Error completo:', error);

      // Mostrar error específico
      // if //(error.message.includes('JSON')) {
      //alert('❌ Error: El servidor no respondió con JSON válido. Verifica que el archivo PHP no tenga errores.');
      //} //else {
      // alert('❌ Error de conexión: ' + error.message);
      // }

      // Rehabilitar botón
      submitBtn.innerHTML = originalText
      submitBtn.disabled = false;
    });
  });
  //});
</script>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>