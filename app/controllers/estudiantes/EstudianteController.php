<?php
include_once __DIR__ . '/../estudiantes/Estudiante.php';

class EstudianteController {
    public $estudiante;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->estudiante = new Estudiante($db);
    }

    public function listar() {
        return $this->estudiante->listarEstudiantes();
    }

    public function obtener($id) {
        return $this->estudiante->obtenerPorId($id);
    }

    public function crear($data) {
        // Asignar datos del estudiante
        $this->asignarDatosEstudiante($data);
        
        // Validar cédula única
        if ($this->estudiante->cedulaExiste($this->estudiante->cedula)) {
            throw new Exception("La cédula del estudiante ya existe en el sistema");
        }

        // Validar cédula del representante
        if ($this->estudiante->cedulaExiste($this->estudiante->cedula_rep)) {
            // Si el representante existe, verificar que no sea el mismo que el estudiante
            if ($this->estudiante->cedula == $this->estudiante->cedula_rep) {
                throw new Exception("El estudiante no puede ser su propio representante");
            }
        }

        return $this->estudiante->crear();
    }

    public function actualizar($id, $data) {
        // Obtener estudiante existente
        if (!$this->estudiante->obtenerPorId($id)) {
            throw new Exception("Estudiante no encontrado");
        }

        // Asignar datos del estudiante
        $this->asignarDatosEstudiante($data);
        
        // Validar cédula única (excluyendo el estudiante actual)
        if ($this->estudiante->cedulaExiste($this->estudiante->cedula, $this->estudiante->id_persona)) {
            throw new Exception("La cédula del estudiante ya existe en el sistema");
        }

        return $this->estudiante->actualizar();
    }

    public function cambiarEstado($id, $estado) {
        return $this->estudiante->cambiarEstado($id, $estado);
    }

    public function obtenerParentescos() {
        return $this->estudiante->obtenerParentescos();
    }

    public function obtenerProfesiones() {
        return $this->estudiante->obtenerProfesiones();
    }

    public function obtenerParroquias() {
        return $this->estudiante->obtenerParroquias();
    }

    private function asignarDatosEstudiante($data) {
        // Datos del estudiante
        $this->estudiante->primer_nombre = $data['primer_nombre'] ?? '';
        $this->estudiante->segundo_nombre = $data['segundo_nombre'] ?? '';
        $this->estudiante->primer_apellido = $data['primer_apellido'] ?? '';
        $this->estudiante->segundo_apellido = $data['segundo_apellido'] ?? '';
        $this->estudiante->cedula = $data['cedula'] ?? '';
        $this->estudiante->telefono = $data['telefono'] ?? '';
        $this->estudiante->telefono_hab = $data['telefono_hab'] ?? '';
        $this->estudiante->correo = $data['correo'] ?? '';
        $this->estudiante->lugar_nac = $data['lugar_nac'] ?? '';
        $this->estudiante->fecha_nac = $data['fecha_nac'] ?? '';
        $this->estudiante->sexo = $data['sexo'] ?? '';
        $this->estudiante->nacionalidad = $data['nacionalidad'] ?? '';

        // Datos de dirección
        $this->estudiante->id_parroquia = $data['id_parroquia'] ?? '';
        $this->estudiante->direccion = $data['direccion'] ?? '';
        $this->estudiante->calle = $data['calle'] ?? '';
        $this->estudiante->casa = $data['casa'] ?? '';

        // Datos del representante
        $this->estudiante->primer_nombre_rep = $data['primer_nombre_rep'] ?? '';
        $this->estudiante->segundo_nombre_rep = $data['segundo_nombre_rep'] ?? '';
        $this->estudiante->primer_apellido_rep = $data['primer_apellido_rep'] ?? '';
        $this->estudiante->segundo_apellido_rep = $data['segundo_apellido_rep'] ?? '';
        $this->estudiante->cedula_rep = $data['cedula_rep'] ?? '';
        $this->estudiante->telefono_rep = $data['telefono_rep'] ?? '';
        $this->estudiante->telefono_hab_rep = $data['telefono_hab_rep'] ?? '';
        $this->estudiante->correo_rep = $data['correo_rep'] ?? '';
        $this->estudiante->id_profesion_rep = $data['id_profesion_rep'] ?? '';
        $this->estudiante->ocupacion_rep = $data['ocupacion_rep'] ?? '';
        $this->estudiante->lugar_trabajo_rep = $data['lugar_trabajo_rep'] ?? '';
        $this->estudiante->id_parentesco = $data['id_parentesco'] ?? '';
    }
}
?>