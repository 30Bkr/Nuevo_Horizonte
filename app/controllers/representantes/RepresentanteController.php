<?php
include_once __DIR__ . '/../representantes/Representante.php';

class RepresentanteController {
    public $representante;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->representante = new Representante($db);
    }

    public function listar() {
        return $this->representante->listarRepresentantes();
    }

    public function obtener($id) {
        return $this->representante->obtenerPorId($id);
    }

    public function crear($data) {
        // Asignar datos del representante
        $this->asignarDatosRepresentante($data);
        
        // Validar cédula única
        if ($this->representante->cedulaExiste($this->representante->cedula)) {
            throw new Exception("La cédula del representante ya existe en el sistema");
        }

        return $this->representante->crear();
    }

    public function actualizar($id, $data) {
        // Obtener representante existente
        if (!$this->representante->obtenerPorId($id)) {
            throw new Exception("Representante no encontrado");
        }

        // Asignar datos del representante
        $this->asignarDatosRepresentante($data);
        
        // Validar cédula única (excluyendo el representante actual)
        if ($this->representante->cedulaExiste($this->representante->cedula, $this->representante->id_persona)) {
            throw new Exception("La cédula del representante ya existe en el sistema");
        }

        return $this->representante->actualizar();
    }

    public function cambiarEstado($id, $estado) {
        return $this->representante->cambiarEstado($id, $estado);
    }

    public function obtenerEstados() {
        return $this->representante->obtenerEstados();
    }

    public function obtenerMunicipiosPorEstado($id_estado) {
        return $this->representante->obtenerMunicipiosPorEstado($id_estado);
    }

    public function obtenerParroquiasPorMunicipio($id_municipio) {
        return $this->representante->obtenerParroquiasPorMunicipio($id_municipio);
    }

    public function obtenerProfesiones() {
        return $this->representante->obtenerProfesiones();
    }

    public function obtenerParroquias() {
        return $this->representante->obtenerParroquias();
    }

    private function asignarDatosRepresentante($data) {
        // Datos del representante
        $this->representante->primer_nombre = $data['primer_nombre'] ?? '';
        $this->representante->segundo_nombre = $data['segundo_nombre'] ?? '';
        $this->representante->primer_apellido = $data['primer_apellido'] ?? '';
        $this->representante->segundo_apellido = $data['segundo_apellido'] ?? '';
        $this->representante->cedula = $data['cedula'] ?? '';
        $this->representante->telefono = $data['telefono'] ?? '';
        $this->representante->telefono_hab = $data['telefono_hab'] ?? '';
        $this->representante->correo = $data['correo'] ?? '';
        $this->representante->lugar_nac = $data['lugar_nac'] ?? '';
        $this->representante->fecha_nac = $data['fecha_nac'] ?? '';
        $this->representante->sexo = $data['sexo'] ?? '';
        $this->representante->nacionalidad = $data['nacionalidad'] ?? '';

        // Datos de dirección
        $this->representante->id_estado = $data['id_estado'] ?? '';
        $this->representante->id_municipio = $data['id_municipio'] ?? '';
        $this->representante->id_parroquia = $data['id_parroquia'] ?? '';
        $this->representante->direccion = $data['direccion'] ?? '';
        $this->representante->calle = $data['calle'] ?? '';
        $this->representante->casa = $data['casa'] ?? '';

        // Datos específicos del representante
        $this->representante->id_profesion = $data['id_profesion'] ?? '';
        $this->representante->ocupacion = $data['ocupacion'] ?? '';
        $this->representante->lugar_trabajo = $data['lugar_trabajo'] ?? '';
    }
}
?>