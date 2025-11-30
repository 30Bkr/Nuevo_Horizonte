<?php
include_once("/xampp/htdocs/final/layout/layaout1.php");
?>

<div class="content-wrapper">
  <div class="content">
    <div class="container-fluid">
      <div class="row mb-4">
        <div class="col-12">
          <h1 class="mb-0">Configuraciones del Sistema</h1>
          <p class="text-muted">Administra las configuraciones generales de la institución</p>
        </div>
      </div>

      <div class="row">
        <!-- Card 1: Información de la Institución -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-school mr-2"></i>
                Institución
              </h3>
            </div>
            <div class="card-body">
              <p class="card-text">Configura el nombre, dirección y datos principales de la institución educativa.</p>
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Configuración básica</small>
                <a href="configuracion/institucion.php" class="btn btn-primary btn-sm">
                  <i class="fas fa-cog mr-1"></i> Configurar
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Card 2: Periodos Académicos -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
          <div class="card card-success card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-calendar-alt mr-2"></i>
                Periodos
              </h3>
            </div>
            <div class="card-body">
              <p class="card-text">Gestiona los periodos académicos, fechas de inicio y fin de cada año escolar.</p>
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Años escolares</small>
                <a href="configuracion/periodos.php" class="btn btn-success btn-sm">
                  <i class="fas fa-cog mr-1"></i> Configurar
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Card 3: Edades para Inscripción -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
          <div class="card card-info card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-child mr-2"></i>
                Edades
              </h3>
            </div>
            <div class="card-body">
              <p class="card-text">Define los rangos de edad mínima y máxima para la inscripción de estudiantes.</p>
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Rangos de edad</small>
                <a href="configuracion/edades.php" class="btn btn-info btn-sm">
                  <i class="fas fa-cog mr-1"></i> Configurar
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Card 4: Quienes pueden inscribir -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
          <div class="card card-teal card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-heartbeat mr-2"></i>

                Discapacidades
              </h3>
            </div>
            <div class="card-body">
              <p class="card-text">Gestión de discapacidades y condiciones médicas de los estudiantes.</p>
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Salud Estudiantil</small>
                <a href="configuracion/discapacidades.php" class="btn btn-teal btn-sm" id="meal">
                  <i class="fas fa-cog mr-1"></i> Configurar
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Segunda fila de tarjetas adicionales -->
      <div class="row">
        <!-- Card 5: Niveles y Secciones -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
          <div class="card card-danger card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-layer-group mr-2"></i>
                Niveles
              </h3>
            </div>
            <div class="card-body">
              <p class="card-text">Administra los niveles educativos y secciones disponibles.</p>
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Grados, años y secciones</small>
                <a href="../../views/grados/grados_list.php" class="btn btn-danger btn-sm">
                  <i class="fas fa-cog mr-1"></i> Configurar
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Card 6: Profesiones -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
          <div class="card card-secondary card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-briefcase mr-2"></i>
                Profesiones
              </h3>
            </div>
            <div class="card-body">
              <p class="card-text">Gestiona el catálogo de profesiones para representantes y docentes.</p>
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Catálogo de oficios</small>
                <a href="configuracion/profesiones.php" class="btn btn-secondary btn-sm">
                  <i class="fas fa-cog mr-1"></i> Configurar
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Card 7: Ubicación -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
          <div class="card card-purple card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-map-marker-alt mr-2"></i>
                Ubicación
              </h3>
            </div>
            <div class="card-body">
              <p class="card-text">Administra estados, municipios y parroquias.</p>
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Datos geográficos</small>
                <a href="configuracion/ubicacion.php" class="btn btn-purple btn-sm" id="purple">
                  <i class="fas fa-cog mr-1" id="white"></i> Configurar
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Card 8: Patologías -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
          <div class="card card-teal card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-heartbeat mr-2"></i>
                Patologías
              </h3>
            </div>
            <div class="card-body">
              <p class="card-text">Gestión de patologías y condiciones médicas de los estudiantes.</p>
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Salud estudiantil</small>
                <a href="configuracion/patologias.php" class="btn btn-teal btn-sm" id="meal">
                  <i class="fas fa-cog mr-1" id="white"></i> Configurar
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  #white {
    color: white;
  }

  #purple {
    color: white;
    background-color: #6f42c1;
  }

  #purple:hover {
    background-color: #5f38a8ff;
  }

  #meal {
    color: white;
    background-color: #20c997;
  }

  #meal:hover {
    background-color: #1a9d76ff;
  }

  .card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  }

  .card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    padding: 0.75rem 1.25rem;
  }

  .card-title {
    margin-bottom: 0;
    font-size: 1.1rem;
    font-weight: 600;
  }

  .card-body {
    padding: 1.25rem;
  }

  .card-text {
    margin-bottom: 1rem;
    color: #6c757d;
    min-height: 48px;
  }
</style>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>