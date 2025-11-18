<?php
include_once("/xampp/htdocs/final/layout/layaout1.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir los controladores necesarios
include_once("/xampp/htdocs/final/app/controllers/personas/personas.php");
include_once("/xampp/htdocs/final/app/controllers/estudiantes/estudiantes.php");
include_once("/xampp/htdocs/final/app/controllers/representantes/representantes.php");
include_once("/xampp/htdocs/final/app/controllers/ubicaciones/ubicaciones.php");
include_once("/xampp/htdocs/final/app/conexion.php");

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  $profesionesController = new RepresentanteController($pdo);
  $profesiones = $profesionesController->obtenerProfesiones();
  $ubicacionController = new UbicacionController($pdo);
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
                    <strong>Paso 3:</strong> Datos del Estudiante
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <form action="http://localhost/final/app/controllers/inscripciones/reinscripciong.php" method="post" id="form-reinscripcion">

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
                        <label for="cedula_r">Cédula de Identidad</label>
                        <input type="number" name="cedula_r" id="cedula_r" class="form-control" required readonly>
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
                        <input type="text" name="telefono_hab_r" id="telefono_hab_r" class="form-control" required>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="fecha_nac_r">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nac_r" id="fecha_nac_r" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="lugar_nac_r">Lugar de Nacimiento</label>
                        <input type="text" name="lugar_nac_r" id="lugar_nac_r" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-2">
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
                        <label for="nacionalidad_r">Nacionalidad</label>
                        <input type="text" name="nacionalidad_r" id="nacionalidad_r" class="form-control" required value="Venezolana">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="parentesco">Parentesco con Estudiante</label>
                        <select name="parentesco" id="parentesco" class="form-control" required>
                          <option value="">Seleccionar</option>
                          <option value="Madre">Madre</option>
                          <option value="Padre">Padre</option>
                          <option value="Abuelo">Abuelo</option>
                          <option value="Abuela">Abuela</option>
                          <option value="Tío">Tío</option>
                          <option value="Tía">Tía</option>
                          <option value="Hermano">Hermano</option>
                          <option value="Hermana">Hermana</option>
                          <option value="Otro">Otro</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="ocupacion_r">Ocupación</label>
                        <input type="text" name="ocupacion_r" id="ocupacion_r" class="form-control" required>
                      </div>
                    </div>
                    <div class="col-md-4">
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

        <!-- PASO 3: DATOS DEL ESTUDIANTE -->
        <div class="step" id="step3">
          <div class="row">
            <div class="col-md-12">
              <div class="card card-outline card-success">
                <div class="card-header">
                  <h3 class="card-title"><b>Paso 3: Datos del Estudiante</b></h3>
                </div>
                <div class="card-body">
                  <input type="hidden" name="estudiante_existente" id="estudiante_existente" value="0">
                  <input type="hidden" name="id_estudiante_existente" id="id_estudiante_existente" value="">
                  <input type="hidden" name="id_direccion_est" id="id_direccion_est" value="">

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="cedula_estudiante">Cédula del Estudiante</label>
                        <input type="number" id="cedula_estudiante" class="form-control" placeholder="Ingrese la cédula del estudiante">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group" style="margin-top: 32px;">
                        <button type="button" id="btn-validar-estudiante" class="btn btn-primary">Validar Estudiante</button>
                      </div>
                    </div>
                  </div>
                  <div id="resultado-validacion-estudiante" class="mt-3"></div>

                  <div id="datos-estudiante" style="display: none;">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="primer_nombre_e">Primer Nombre</label>
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
                          <label for="primer_apellido_e">Primer Apellido</label>
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
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="fecha_nac_e">Fecha de Nacimiento</label>
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
                          <label for="lugar_nac_e">Lugar de Nacimiento</label>
                          <input type="text" name="lugar_nac_e" id="lugar_nac_e" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="sexo_e">Sexo</label>
                          <select name="sexo_e" id="sexo_e" class="form-control" required>
                            <option value="">Seleccionar</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="nacionalidad_e">Nacionalidad</label>
                          <input type="text" name="nacionalidad_e" id="nacionalidad_e" class="form-control" required value="Venezolana">
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

                    <!-- Pregunta si el alumno vive en la casa del representante -->
                    <input type="hidden" name="juntos" id="juntos" value="1">
                    <div class="card-header mt-4">
                      <h3 class="card-title"><b>Datos de Residencia</b></h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="misma_casa">¿El alumno vive en la misma casa del representante?</label>
                            <select name="misma_casa" id="misma_casa" class="form-control" required>
                              <option value="">Seleccionar</option>
                              <option value="si">Sí</option>
                              <option value="no">No</option>
                            </select>
                          </div>
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
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="id_periodo">Período Académico</label>
                          <select name="id_periodo" class="form-control" required>
                            <option value="">Seleccionar Período</option>
                            <?php
                            echo "<option value='1' selected>Año Escolar 2024-2025</option>";
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

                    <!-- PATOLOGÍAS -->
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Patologías/Alergias (Seleccione las que apliquen)</label>
                          <div class="row">
                            <?php
                            $patologias = [
                              1 => 'Asma',
                              2 => 'Alergia a lácteos',
                              3 => 'Alergia al polen',
                              4 => 'Rinitis alérgica'
                            ];
                            foreach ($patologias as $id => $patologia) {
                              echo "
                              <div class='col-md-3'>
                                <div class='form-check'>
                                  <input type='checkbox' name='patologias[]' value='$id' class='form-check-input patologia-checkbox' id='patologia_$id'>
                                  <label class='form-check-label' for='patologia_$id'>$patologia</label>
                                </div>
                              </div>";
                            }
                            ?>
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
                  </div>

                  <!-- Botones de navegación y envío -->
                  <div class="row mt-4">
                    <div class="col-md-12 text-right">
                      <button type="button" class="btn btn-secondary btn-step" id="btn-back-to-step2">
                        <i class="fas fa-arrow-left"></i> Anterior
                      </button>
                      <button type="submit" class="btn btn-success btn-step" id="btn-submit" style="display: none;">
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
  document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 3;

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
      showStep(2);
    });

    document.getElementById('btn-next-to-step3').addEventListener('click', function() {
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

    document.getElementById('btn-back-to-step1').addEventListener('click', function() {
      showStep(1);
    });

    document.getElementById('btn-back-to-step2').addEventListener('click', function() {
      showStep(2);
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

    // Validar estudiante
    document.getElementById('btn-validar-estudiante').addEventListener('click', function() {
      const cedula = document.getElementById('cedula_estudiante').value;
      if (!cedula) {
        alert('Por favor ingrese la cédula del estudiante');
        return;
      }
      validarEstudiante(cedula);
    });

    function validarRepresentante(cedula) {
      const formData = new FormData();
      formData.append('cedula', cedula);

      fetch('/final/app/controllers/representantes/validar.php', {
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
                <br>Los datos se cargarán automáticamente.
              </div>
            `;

            // Llenar campos del representante
            document.getElementById('representante_existente').value = '1';
            document.getElementById('id_direccion_repre').value = data.id_direccion;
            document.getElementById('id_representante_existente').value = data.id_representante;

            // Datos personales
            document.getElementById('cedula_r').value = data.cedula;
            document.getElementById('primer_nombre_r').value = data.primer_nombre;
            document.getElementById('segundo_nombre_r').value = data.segundo_nombre || '';
            document.getElementById('primer_apellido_r').value = data.primer_apellido;
            document.getElementById('segundo_apellido_r').value = data.segundo_apellido || '';
            document.getElementById('correo_r').value = data.correo || '';
            document.getElementById('telefono_r').value = data.telefono || '';
            document.getElementById('telefono_hab_r').value = data.telefono_hab || '';
            document.getElementById('fecha_nac_r').value = data.fecha_nac || '';
            document.getElementById('lugar_nac_r').value = data.lugar_nac || '';
            document.getElementById('sexo_r').value = data.sexo || '';
            document.getElementById('nacionalidad_r').value = data.nacionalidad || 'Venezolana';
            document.getElementById('ocupacion_r').value = data.ocupacion || '';
            document.getElementById('lugar_trabajo_r').value = data.lugar_trabajo || '';
            document.getElementById('profesion_r').value = data.profesion || '';

            // Datos de dirección
            if (data.id_estado) {
              document.getElementById('estado_r').value = data.id_estado;
              cargarMunicipios(data.id_estado, 'municipio_r').then(() => {
                if (data.id_municipio) {
                  document.getElementById('municipio_r').value = data.id_municipio;
                  cargarParroquias(data.id_municipio, 'parroquia_r').then(() => {
                    if (data.id_parroquia) {
                      document.getElementById('parroquia_r').value = data.id_parroquia;
                    }
                  });
                }
              });
            }

            document.getElementById('direccion_r').value = data.direccion || '';
            document.getElementById('calle_r').value = data.calle || '';
            document.getElementById('casa_r').value = data.casa || '';

            // Hacer la cédula de representante readonly
            document.getElementById('cedula_r').readOnly = true;

            nextButton.style.display = 'inline-block';

          } else {
            resultado.innerHTML = `
              <div class="alert alert-info">
                <strong>Representante no encontrado.</strong> Por favor complete todos los datos del representante.
              </div>
            `;
            document.getElementById('cedula_r').value = cedula;
            document.getElementById('representante_existente').value = '0';
            document.getElementById('cedula_r').readOnly = false;
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

    function validarEstudiante(cedula) {
      const formData = new FormData();
      formData.append('cedula', cedula);

      fetch('/final/app/controllers/estudiantes/validar.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          const resultado = document.getElementById('resultado-validacion-estudiante');
          const datosEstudiante = document.getElementById('datos-estudiante');
          const submitButton = document.getElementById('btn-submit');

          if (data.existe) {
            resultado.innerHTML = `
              <div class="alert alert-success">
                <strong>Estudiante encontrado:</strong> ${data.nombre_completo}
                <br>Los datos se cargarán automáticamente.
              </div>
            `;

            // Llenar campos del estudiante
            document.getElementById('estudiante_existente').value = '1';
            document.getElementById('id_estudiante_existente').value = data.id_estudiante;
            document.getElementById('id_direccion_est').value = data.id_direccion;

            // Datos personales del estudiante
            document.getElementById('primer_nombre_e').value = data.primer_nombre;
            document.getElementById('segundo_nombre_e').value = data.segundo_nombre || '';
            document.getElementById('primer_apellido_e').value = data.primer_apellido;
            document.getElementById('segundo_apellido_e').value = data.segundo_apellido || '';
            document.getElementById('cedula_e').value = data.cedula;
            document.getElementById('fecha_nac_e').value = data.fecha_nac || '';
            document.getElementById('lugar_nac_e').value = data.lugar_nac || '';
            document.getElementById('sexo_e').value = data.sexo || '';
            document.getElementById('nacionalidad_e').value = data.nacionalidad || 'Venezolana';
            document.getElementById('telefono_e').value = data.telefono || '';
            document.getElementById('correo_e').value = data.correo || '';

            // Cargar patologías existentes
            if (data.patologias && data.patologias.length > 0) {
              document.querySelectorAll('.patologia-checkbox').forEach(checkbox => {
                checkbox.checked = data.patologias.includes(parseInt(checkbox.value));
              });
            }

            // Mostrar sección de datos del estudiante
            datosEstudiante.style.display = 'block';
            submitButton.style.display = 'inline-block';

          } else {
            resultado.innerHTML = `
              <div class="alert alert-info">
                <strong>Estudiante no encontrado.</strong> Por favor complete todos los datos del estudiante.
              </div>
            `;
            document.getElementById('estudiante_existente').value = '0';
            datosEstudiante.style.display = 'block';
            submitButton.style.display = 'inline-block';
          }
        })
        .catch(error => {
          console.error('Error:', error);
          document.getElementById('resultado-validacion-estudiante').innerHTML = `
            <div class="alert alert-danger">
              Error al validar el estudiante. Intente nuevamente.
            </div>
          `;
        });
    }

    // Funciones para cargar ubicaciones (mismo código que en inscripción)
    function cargarMunicipios(estadoId, selectId) {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('estado_id', estadoId);

        fetch('/final/app/controllers/ubicaciones/municipios.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            const select = document.getElementById(selectId);
            select.innerHTML = '<option value="">Seleccionar Municipio</option>';
            data.forEach(municipio => {
              select.innerHTML += `<option value="${municipio.id_municipio}">${municipio.nom_municipio}</option>`;
            });
            select.disabled = false;
            resolve();
          })
          .catch(error => {
            console.error('Error al cargar municipios:', error);
            reject(error);
          });
      });
    }

    function cargarParroquias(municipioId, selectId) {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('municipio_id', municipioId);

        fetch('/final/app/controllers/ubicaciones/parroquias.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            const select = document.getElementById(selectId);
            select.innerHTML = '<option value="">Seleccionar Parroquia</option>';
            data.forEach(parroquia => {
              select.innerHTML += `<option value="${parroquia.id_parroquia}">${parroquia.nom_parroquia}</option>`;
            });
            select.disabled = false;
            resolve();
          })
          .catch(error => {
            console.error('Error al cargar parroquias:', error);
            reject(error);
          });
      });
    }

    // Event listeners para ubicaciones (mismo código que en inscripción)
    document.getElementById('estado_r').addEventListener('change', function() {
      const estadoId = this.value;
      if (estadoId) {
        cargarMunicipios(estadoId, 'municipio_r');
      }
    });

    document.getElementById('municipio_r').addEventListener('change', function() {
      const municipioId = this.value;
      if (municipioId) {
        cargarParroquias(municipioId, 'parroquia_r');
      }
    });

    document.getElementById('estado_e').addEventListener('change', function() {
      const estadoId = this.value;
      if (estadoId) {
        cargarMunicipios(estadoId, 'municipio_e');
      }
    });

    document.getElementById('municipio_e').addEventListener('change', function() {
      const municipioId = this.value;
      if (municipioId) {
        cargarParroquias(municipioId, 'parroquia_e');
      }
    });

    // Validación de misma casa (mismo código que en inscripción)
    const selectMismaCasa = document.getElementById('misma_casa');
    const seccionDireccion = document.getElementById('direccion_representante');

    selectMismaCasa.addEventListener('change', function() {
      if (this.value === 'no') {
        document.getElementById('juntos').value = '0';
        seccionDireccion.style.display = 'block';
        document.getElementById('estado_e').required = true;
        document.getElementById('direccion_e').required = true;
      } else {
        seccionDireccion.style.display = 'none';
        document.getElementById('estado_e').required = false;
        document.getElementById('direccion_e').required = false;
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

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>