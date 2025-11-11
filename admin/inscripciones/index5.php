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

<style>
  :root {
    --primary: #4361ee;
    --secondary: #3f37c9;
    --success: #4cc9f0;
    --info: #4895ef;
    --warning: #f72585;
    --light: #f8f9fa;
    --dark: #212529;
    --gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
  }

  .step {
    display: none;
    animation: fadeIn 0.5s ease-in-out;
  }

  .step.active {
    display: block;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(20px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .step-indicator {
    background: var(--gradient);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(67, 97, 238, 0.3);
    position: relative;
    overflow: hidden;
  }

  .step-indicator::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: rgba(255, 255, 255, 0.3);
  }

  .step-item {
    text-align: center;
    position: relative;
    z-index: 2;
  }

  .step-number {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: white;
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
    margin: 0 auto 10px;
    border: 3px solid white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
  }

  .step-item.active .step-number {
    background: var(--success);
    color: white;
    transform: scale(1.1);
  }

  .step-item.completed .step-number {
    background: var(--success);
    color: white;
  }

  .step-item.completed .step-number::after {
    content: '✓';
    font-size: 1.5rem;
  }

  .step-label {
    color: white;
    font-weight: 500;
    font-size: 0.9rem;
  }

  .step-connector {
    position: absolute;
    top: 25px;
    left: 50%;
    width: 100%;
    height: 3px;
    background: rgba(255, 255, 255, 0.3);
    z-index: 1;
  }

  .step-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .step-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
  }

  .step-card .card-header {
    background: var(--gradient);
    color: white;
    border: none;
    padding: 1.5rem;
    position: relative;
  }

  .step-card .card-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--success), var(--info));
  }

  .step-card .card-title {
    margin: 0;
    font-weight: 600;
    font-size: 1.3rem;
  }

  .step-card .card-body {
    padding: 2rem;
  }

  .form-group {
    margin-bottom: 1.5rem;
  }

  .form-control {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
  }

  .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
  }

  .form-label {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
  }

  .btn-modern {
    border: none;
    border-radius: 10px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }

  .btn-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s;
  }

  .btn-modern:hover::before {
    left: 100%;
  }

  .btn-primary-modern {
    background: var(--gradient);
    color: white;
  }

  .btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
  }

  .btn-success-modern {
    background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);
    color: white;
  }

  .btn-success-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(76, 201, 240, 0.4);
  }

  .btn-secondary-modern {
    background: #6c757d;
    color: white;
  }

  .btn-secondary-modern:hover {
    background: #5a6268;
    transform: translateY(-2px);
  }

  .validation-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
  }

  .animated-alert {
    border: none;
    border-radius: 10px;
    padding: 1rem 1.5rem;
    margin-bottom: 1rem;
    animation: slideIn 0.5s ease-out;
  }

  @keyframes slideIn {
    from {
      transform: translateX(-20px);
      opacity: 0;
    }

    to {
      transform: translateX(0);
      opacity: 1;
    }
  }

  .progress-container {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    padding: 1rem;
    margin: 1rem 0;
  }

  .loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s ease-in-out infinite;
    margin-right: 10px;
  }

  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }

  .section-divider {
    border: none;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--primary), transparent);
    margin: 2rem 0;
  }

  .feature-icon {
    width: 60px;
    height: 60px;
    background: var(--gradient);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 1.5rem;
  }

  .form-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border-left: 4px solid var(--primary);
  }
</style>

<div class="content-wrapper">
  <div class="content">
    <div class="container-fluid">
      <!-- Header -->
      <div class="row mb-5">
        <div class="col-12 text-center">
          <h1 class="display-4 font-weight-bold text-dark mb-3">🎓 Inscripción de Estudiante</h1>
          <p class="lead text-muted">Complete el proceso de inscripción en 3 simples pasos</p>
        </div>
      </div>

      <!-- Step Indicator -->
      <div class="row mb-5">
        <div class="col-12">
          <div class="step-indicator">
            <div class="row">
              <div class="col-md-4 step-item active" id="indicator-step1">
                <div class="step-number">1</div>
                <div class="step-label">Validar Representante</div>
              </div>
              <div class="col-md-4 step-item" id="indicator-step2">
                <div class="step-number">2</div>
                <div class="step-label">Datos del Representante</div>
              </div>
              <div class="col-md-4 step-item" id="indicator-step3">
                <div class="step-number">3</div>
                <div class="step-label">Datos del Estudiante</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <form action="http://localhost/final/app/controllers/inscripciones/inscripciong.php" method="post" id="form-inscripcion">

        <!-- PASO 1: VALIDAR REPRESENTANTE -->
        <div class="step active" id="step1">
          <div class="row justify-content-center">
            <div class="col-lg-8">
              <div class="step-card">
                <div class="card-header text-center">
                  <i class="fas fa-id-card fa-2x mb-3"></i>
                  <h3 class="card-title">Validación de Representante</h3>
                  <p class="mb-0 opacity-75">Verifique si el representante ya está registrado en el sistema</p>
                </div>
                <div class="card-body">
                  <div class="validation-card text-center">
                    <i class="fas fa-search fa-3x mb-3"></i>
                    <h4 class="mb-3">Buscar Representante</h4>
                    <p class="mb-4">Ingrese la cédula de identidad del representante para verificar su registro</p>

                    <div class="row justify-content-center">
                      <div class="col-md-8">
                        <div class="form-group">
                          <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                              <span class="input-group-text bg-white">
                                <i class="fas fa-id-card text-primary"></i>
                              </span>
                            </div>
                            <input type="number"
                              id="cedula_representante"
                              class="form-control form-control-lg"
                              placeholder="Ej: 12345678"
                              style="border-radius: 0 10px 10px 0;">
                          </div>
                        </div>
                      </div>
                    </div>

                    <button type="button" id="btn-validar-representante" class="btn btn-primary-modern btn-lg">
                      <i class="fas fa-search mr-2"></i>Validar Representante
                    </button>
                  </div>

                  <div id="resultado-validacion" class="mt-4"></div>

                  <!-- Navigation -->
                  <div class="row mt-4">
                    <div class="col-12 text-center">
                      <button type="button" class="btn btn-success-modern btn-lg px-5" id="btn-next-to-step2" style="display: none;">
                        Continuar al Paso 2 <i class="fas fa-arrow-right ml-2"></i>
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
            <div class="col-12">
              <div class="step-card">
                <div class="card-header">
                  <i class="fas fa-user-tie mr-2"></i>
                  <h3 class="card-title">Información del Representante</h3>
                </div>
                <div class="card-body">
                  <input type="hidden" name="representante_existente" id="representante_existente" value="0">
                  <input type="hidden" name="id_representante_existente" id="id_representante_existente" value="">

                  <!-- Información Personal -->
                  <div class="form-section">
                    <h5 class="font-weight-bold text-primary mb-3">
                      <i class="fas fa-user-circle mr-2"></i>Información Personal
                    </h5>
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Primer Nombre *</label>
                          <input type="text" name="primer_nombre_r" id="primer_nombre_r" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Segundo Nombre</label>
                          <input type="text" name="segundo_nombre_r" id="segundo_nombre_r" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Primer Apellido *</label>
                          <input type="text" name="primer_apellido_r" id="primer_apellido_r" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Segundo Apellido</label>
                          <input type="text" name="segundo_apellido_r" id="segundo_apellido_r" class="form-control">
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Cédula *</label>
                          <input type="number" name="cedula_r" id="cedula_r" class="form-control" required readonly>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Fecha Nacimiento *</label>
                          <input type="date" name="fecha_nac_r" id="fecha_nac_r" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Sexo *</label>
                          <select name="sexo_r" id="sexo_r" class="form-control" required>
                            <option value="">Seleccionar</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Nacionalidad *</label>
                          <input type="text" name="nacionalidad_r" id="nacionalidad_r" class="form-control" required value="Venezolana">
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Información de Contacto -->
                  <div class="form-section">
                    <h5 class="font-weight-bold text-primary mb-3">
                      <i class="fas fa-address-card mr-2"></i>Información de Contacto
                    </h5>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="form-label">Correo Electrónico *</label>
                          <input type="email" name="correo_r" id="correo_r" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="form-label">Teléfono Móvil *</label>
                          <input type="text" name="telefono_r" id="telefono_r" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="form-label">Teléfono Habitación *</label>
                          <input type="text" name="telefono_hab_r" id="telefono_hab_r" class="form-control" required>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Información Laboral -->
                  <div class="form-section">
                    <h5 class="font-weight-bold text-primary mb-3">
                      <i class="fas fa-briefcase mr-2"></i>Información Laboral
                    </h5>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="form-label">Profesión</label>
                          <input type="text" name="profesion_r" id="profesion_r" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="form-label">Ocupación *</label>
                          <input type="text" name="ocupacion_r" id="ocupacion_r" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="form-label">Lugar de Trabajo</label>
                          <input type="text" name="lugar_trabajo_r" id="lugar_trabajo_r" class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Dirección -->
                  <div class="form-section">
                    <h5 class="font-weight-bold text-primary mb-3">
                      <i class="fas fa-map-marker-alt mr-2"></i>Dirección
                    </h5>
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Estado *</label>
                          <select name="estado_r" id="estado_r" class="form-control" required>
                            <option value="">Seleccionar Estado</option>
                            <?php foreach ($estados as $estado): ?>
                              <option value="<?= $estado['id_estado'] ?>"><?= $estado['nom_estado'] ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Municipio *</label>
                          <select name="municipio_r" id="municipio_r" class="form-control" required disabled>
                            <option value="">Primero seleccione un estado</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Parroquia *</label>
                          <select name="parroquia_r" id="parroquia_r" class="form-control" required disabled>
                            <option value="">Primero seleccione un municipio</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Parentesco *</label>
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
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label">Dirección Completa *</label>
                          <input type="text" name="direccion_r" id="direccion_r" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Calle/Avenida</label>
                          <input type="text" name="calle_r" id="calle_r" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Casa/Edificio</label>
                          <input type="text" name="casa_r" id="casa_r" class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Navigation -->
                  <div class="row mt-4">
                    <div class="col-12 text-center">
                      <button type="button" class="btn btn-secondary-modern btn-lg mr-3" id="btn-back-to-step1">
                        <i class="fas fa-arrow-left mr-2"></i>Anterior
                      </button>
                      <button type="button" class="btn btn-success-modern btn-lg" id="btn-next-to-step3">
                        Siguiente Paso <i class="fas fa-arrow-right ml-2"></i>
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
            <div class="col-12">
              <div class="step-card">
                <div class="card-header">
                  <i class="fas fa-user-graduate mr-2"></i>
                  <h3 class="card-title">Información del Estudiante</h3>
                </div>
                <div class="card-body">
                  <!-- Información Personal del Estudiante -->
                  <div class="form-section">
                    <h5 class="font-weight-bold text-primary mb-3">
                      <i class="fas fa-user-circle mr-2"></i>Información Personal del Estudiante
                    </h5>
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Primer Nombre *</label>
                          <input type="text" name="primer_nombre_e" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Segundo Nombre</label>
                          <input type="text" name="segundo_nombre_e" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Primer Apellido *</label>
                          <input type="text" name="primer_apellido_e" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Segundo Apellido</label>
                          <input type="text" name="segundo_apellido_e" class="form-control">
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Cédula *</label>
                          <input type="number" name="cedula_e" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Fecha Nacimiento *</label>
                          <input type="date" name="fecha_nac_e" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Lugar Nacimiento *</label>
                          <input type="text" name="lugar_nac_e" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Sexo *</label>
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
                          <label class="form-label">Nacionalidad *</label>
                          <input type="text" name="nacionalidad_e" class="form-control" required value="Venezolana">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="form-label">Teléfono</label>
                          <input type="text" name="telefono_e" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="form-label">Correo Electrónico</label>
                          <input type="email" name="correo_e" class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Información Académica -->
                  <div class="form-section">
                    <h5 class="font-weight-bold text-primary mb-3">
                      <i class="fas fa-graduation-cap mr-2"></i>Información Académica
                    </h5>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="form-label">Período Académico *</label>
                          <select name="id_periodo" class="form-control" required>
                            <option value="">Seleccionar Período</option>
                            <option value="1" selected>Año Escolar 2024-2025</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="form-label">Nivel/Grado *</label>
                          <select name="id_nivel" class="form-control" required>
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
                          <label class="form-label">Sección *</label>
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
                  </div>

                  <!-- Información Médica -->
                  <div class="form-section">
                    <h5 class="font-weight-bold text-primary mb-3">
                      <i class="fas fa-heartbeat mr-2"></i>Información Médica
                    </h5>
                    <div class="row">
                      <div class="col-12">
                        <label class="form-label mb-3">Patologías/Alergias (Seleccione las que apliquen)</label>
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
                                                        <div class='col-md-3 mb-2'>
                                                            <div class='custom-control custom-checkbox'>
                                                                <input type='checkbox' name='patologias[]' value='$id' class='custom-control-input' id='patologia_$id'>
                                                                <label class='custom-control-label' for='patologia_$id'>$patologia</label>
                                                            </div>
                                                        </div>";
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Observaciones -->
                  <div class="form-section">
                    <h5 class="font-weight-bold text-primary mb-3">
                      <i class="fas fa-sticky-note mr-2"></i>Observaciones Adicionales
                    </h5>
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group">
                          <textarea name="observaciones" class="form-control" rows="4" placeholder="Ingrese cualquier observación adicional que considere importante..."></textarea>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Final Actions -->
                  <div class="row mt-5">
                    <div class="col-12 text-center">
                      <div class="alert alert-info animated-alert">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>¡Estás a punto de completar la inscripción!</strong> Verifica que toda la información sea correcta antes de enviar.
                      </div>

                      <button type="button" class="btn btn-secondary-modern btn-lg mr-3" id="btn-back-to-step2">
                        <i class="fas fa-arrow-left mr-2"></i>Anterior
                      </button>
                      <button type="submit" class="btn btn-success-modern btn-lg px-5">
                        <i class="fas fa-paper-plane mr-2"></i>Completar Inscripción
                      </button>
                      <a href="http://localhost/final/admin/index.php" class="btn btn-danger btn-lg ml-3">
                        <i class="fas fa-times mr-2"></i>Cancelar
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
  // El JavaScript permanece igual que en la versión anterior
  document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 3;

    function showStep(step) {
      document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
      document.getElementById(`step${step}`).classList.add('active');

      // Actualizar indicadores
      document.querySelectorAll('.step-item').forEach((item, index) => {
        if (index + 1 === step) {
          item.classList.add('active');
          item.classList.remove('completed');
        } else if (index + 1 < step) {
          item.classList.remove('active');
          item.classList.add('completed');
        } else {
          item.classList.remove('active', 'completed');
        }
      });

      currentStep = step;
    }

    // Navegación entre pasos
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
      if (valid) showStep(3);
      else alert('Complete todos los campos requeridos del representante.');
    });
    document.getElementById('btn-back-to-step1').addEventListener('click', () => showStep(1));
    document.getElementById('btn-back-to-step2').addEventListener('click', () => showStep(2));

    // Resto del JavaScript existente...
  });
</script>

<!-- Mantener todo el JavaScript existente para validaciones y ubicaciones -->
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