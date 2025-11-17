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

  $ubicacionController = new UbicacionController($pdo);
  $estados = $ubicacionController->obtenerEstados();
} catch (PDOException $e) {
  die("Error de conexión: " . $e->getMessage());
}
?>
<div class="content-wrapper">
  <div class="content">
    <br>
    <div class="container">
      <div class="row">
        <h1>Inscripción de Nuevo Estudiante</h1>
      </div>
      <br>

      <!-- Formulario para validar representante existente -->
      <div class="row mb-4">
        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title"><b>Validar Representante</b></h3>
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
            </div>
          </div>
        </div>
      </div>

      <form action="http://localhost/final/app/controllers/inscripciones/inscripciong.php" method="post" id="form-inscripcion">

        <!-- SECCIÓN DEL REPRESENTANTE -->
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-danger">
              <div class="card-header">
                <h3 class="card-title"><b>Datos del Representante</b></h3>
              </div>
              <div class="card-body">
                <input type="hidden" name="representante_existente" id="representante_existente" value="0">
                <input type="hidden" name="id_representante_existente" id="id_representante_existente" value="">

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
                      <input type="text" name="profesion_r" id="profesion_r" class="form-control">
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
              </div>

              <!-- DIRECCIÓN DEL REPRESENTANTE -->
              <div class="card-header">
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
            </div>
          </div>
        </div>

        <!-- SECCIÓN DEL ESTUDIANTE -->
        <div class="row mt-4">
          <div class="col-md-12">
            <div class="card card-outline card-success">
              <div class="card-header">
                <h3 class="card-title"><b>Datos del Estudiante</b></h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="primer_nombre_e">Primer Nombre</label>
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
                      <label for="primer_apellido_e">Primer Apellido</label>
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
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="cedula_e">Cédula de Identidad</label>
                      <input type="number" name="cedula_e" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="fecha_nac_e">Fecha de Nacimiento</label>
                      <input type="date" name="fecha_nac_e" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="lugar_nac_e">Lugar de Nacimiento</label>
                      <input type="text" name="lugar_nac_e" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="sexo_e">Sexo</label>
                      <select name="sexo_e" class="form-control" required>
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
                      <input type="text" name="nacionalidad_e" class="form-control" required value="Venezolana">
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

                <!-- INFORMACIÓN ACADÉMICA -->
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="id_periodo">Período Académico</label>
                      <select name="id_periodo" class="form-control" required>
                        <option value="">Seleccionar Período</option>
                        <?php
                        // Aquí deberías cargar los períodos desde la base de datos
                        // Por ahora, asumiendo que existe el período con id=1
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
                        // Cargar niveles desde la base de datos
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
                        // Cargar secciones desde la base de datos
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
                        // Cargar patologías desde la base de datos
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
                              <input type='checkbox' name='patologias[]' value='$id' class='form-check-input' id='patologia_$id'>
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
            </div>
          </div>
        </div>

        <div class="row mt-4">
          <div class="col-md-12">
            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-lg">Registrar Inscripción</button>
              <a href="http://localhost/final/app/controllers/estudiantes" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- <script>
  // En tu formulario HTML, agrega este script
  document.getElementById('form-inscripcion').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Mostrar mensaje de éxito
          alert('Inscripción realizada exitosamente!');
          // Redirigir o limpiar formulario
          window.location.href = '/final/app/controllers/estudiantes'; // Ajusta esta ruta
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(error => {

        console.error('Error:', error);
        alert('Error al procesar la inscripción');
      });
  });
</script> -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Manejar el envío del formulario
    document.getElementById('form-inscripcion').addEventListener('submit', function(e) {
      e.preventDefault();
      console.log('Formulario enviado - iniciando procesamiento...');
      document.querySelectorAll('#form-inscripcion input:disabled, #form-inscripcion select:disabled').forEach(element => {
        element.disabled = false;
      });
      // Mostrar loading
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
      submitBtn.disabled = true;

      // Crear FormData
      const formData = new FormData(this);

      // Log para debugging (opcional)
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
            // Redirigir después de 2 segundos
            setTimeout(() => {
              window.location.href = '/final/admin/index.php';
            }, 2000);
          } else {
            alert('❌ ' + data.message);
            // Rehabilitar botón
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
          }
        })
        .catch(error => {
          console.error('Error completo:', error);

          // Mostrar error específico
          if (error.message.includes('JSON')) {
            alert('❌ Error: El servidor no respondió con JSON válido. Verifica que el archivo PHP no tenga errores.');
          } else {
            alert('❌ Error de conexión: ' + error.message);
          }

          // Rehabilitar botón
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;
        });
    });
  });
</script>
</script>
<!-- Carga de estados, municipios, parroquias -->
<!-- Carga de estados, municipios, parroquias -->
<!-- Carga de estados, municipios, parroquias -->
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


<!-- Para validar la cedula de identidad -->
<!-- Para validar la cedula de identidad -->
<!-- Para validar la cedula de identidad -->
<!-- Para validar la cedula de identidad -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Validar representante existente
    document.getElementById('btn-validar-representante').addEventListener('click', function() {
      const cedula = document.getElementById('cedula_representante').value;

      if (!cedula) {
        alert('Por favor ingrese la cédula del representante');
        return;
      }

      validarRepresentante(cedula);
    });

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


    function validarRepresentante(cedula) {
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

          if (data.existe) {
            resultado.innerHTML = `
            <div class="alert alert-success">
                <strong>Representante encontrado:</strong> ${data.nombre_completo}
                <br>Los datos se cargarán automáticamente.
            </div>
            `;

            // Llenar los campos con los datos del representante
            document.getElementById('representante_existente').value = '1';
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

              // Cargar municipios para este estado
              cargarMunicipios(data.id_estado).then(() => {
                if (data.id_municipio) {
                  document.getElementById('municipio_r').value = data.id_municipio;

                  // Cargar parroquias para este municipio
                  cargarParroquias(data.id_municipio).then(() => {
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

            // Deshabilitar campos del representante
            document.querySelectorAll('#form-inscripcion input, #form-inscripcion select').forEach(element => {
              if (element.name.includes('_r') && element.name !== 'parentesco') {
                element.disabled = true;
              }
            });

          } else {
            resultado.innerHTML = `
            <div class="alert alert-info">
                <strong>Representante no encontrado.</strong> Por favor complete todos los datos del representante.
            </div>
            `;
            document.getElementById('cedula_r').value = cedula;
            document.getElementById('representante_existente').value = '0';

            // Habilitar todos los campos por si estaban deshabilitados
            document.querySelectorAll('#form-inscripcion input, #form-inscripcion select').forEach(element => {
              element.disabled = false;
            });
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
  });
</script>
<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>