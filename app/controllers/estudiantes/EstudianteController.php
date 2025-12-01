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

        // Procesar patologías y discapacidades
        $this->procesarSaludEstudiante($id, $data);

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

    public function obtenerPatologias() {
        return $this->estudiante->obtenerPatologias();
    }

    public function obtenerDiscapacidades() {
        return $this->estudiante->obtenerDiscapacidades();
    }

    // Nuevo método para obtener estados
    public function obtenerEstados() {
        $query = "SELECT id_estado, nom_estado FROM estados WHERE estatus = 1 ORDER BY nom_estado";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Nuevo método para obtener municipios por estado
    public function obtenerMunicipiosPorEstado($id_estado) {
        $query = "SELECT id_municipio, nom_municipio FROM municipios 
                  WHERE id_estado = ? AND estatus = 1 ORDER BY nom_municipio";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id_estado);
        $stmt->execute();
        return $stmt;
    }

    // Nuevo método para obtener parroquias por municipio
    public function obtenerParroquiasPorMunicipio($id_municipio) {
        $query = "SELECT id_parroquia, nom_parroquia FROM parroquias 
                  WHERE id_municipio = ? AND estatus = 1 ORDER BY nom_parroquia";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id_municipio);
        $stmt->execute();
        return $stmt;
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

        // Nueva propiedad para control de dirección compartida
        $this->estudiante->comparte_direccion = $data['comparte_direccion'] ?? '1';
    }

    private function procesarSaludEstudiante($id_estudiante, $data) {
        try {
            // Procesar patologías
            if (isset($data['patologias'])) {
                // Eliminar patologías existentes
                $query_delete = "UPDATE estudiantes_patologias SET estatus = 0 WHERE id_estudiante = ?";
                $stmt_delete = $this->db->prepare($query_delete);
                $stmt_delete->bindParam(1, $id_estudiante);
                $stmt_delete->execute();

                // Insertar nuevas patologías
                foreach ($data['patologias'] as $id_patologia) {
                    if (!empty($id_patologia)) {
                        // Verificar si ya existe
                        $query_check = "SELECT id_estudiante_patologia FROM estudiantes_patologias 
                                      WHERE id_estudiante = ? AND id_patologia = ?";
                        $stmt_check = $this->db->prepare($query_check);
                        $stmt_check->bindParam(1, $id_estudiante);
                        $stmt_check->bindParam(2, $id_patologia);
                        $stmt_check->execute();

                        if ($stmt_check->rowCount() > 0) {
                            // Actualizar existente
                            $query_update = "UPDATE estudiantes_patologias SET estatus = 1, actualizacion = NOW() 
                                           WHERE id_estudiante = ? AND id_patologia = ?";
                            $stmt_update = $this->db->prepare($query_update);
                            $stmt_update->bindParam(1, $id_estudiante);
                            $stmt_update->bindParam(2, $id_patologia);
                            $stmt_update->execute();
                        } else {
                            // Insertar nuevo
                            $query_insert = "INSERT INTO estudiantes_patologias (id_estudiante, id_patologia, creacion, estatus) 
                                           VALUES (?, ?, NOW(), 1)";
                            $stmt_insert = $this->db->prepare($query_insert);
                            $stmt_insert->bindParam(1, $id_estudiante);
                            $stmt_insert->bindParam(2, $id_patologia);
                            $stmt_insert->execute();
                        }
                    }
                }
            } else {
                // Si no se enviaron patologías, desactivar todas
                $query_delete = "UPDATE estudiantes_patologias SET estatus = 0 WHERE id_estudiante = ?";
                $stmt_delete = $this->db->prepare($query_delete);
                $stmt_delete->bindParam(1, $id_estudiante);
                $stmt_delete->execute();
            }

            // Procesar discapacidades
            if (isset($data['discapacidades'])) {
                // Eliminar discapacidades existentes
                $query_delete = "UPDATE estudiantes_discapacidades SET estatus = 0 WHERE id_estudiante = ?";
                $stmt_delete = $this->db->prepare($query_delete);
                $stmt_delete->bindParam(1, $id_estudiante);
                $stmt_delete->execute();

                // Insertar nuevas discapacidades
                foreach ($data['discapacidades'] as $id_discapacidad) {
                    if (!empty($id_discapacidad)) {
                        // Verificar si ya existe
                        $query_check = "SELECT id_estudiante_discapacidad FROM estudiantes_discapacidades 
                                      WHERE id_estudiante = ? AND id_discapacidad = ?";
                        $stmt_check = $this->db->prepare($query_check);
                        $stmt_check->bindParam(1, $id_estudiante);
                        $stmt_check->bindParam(2, $id_discapacidad);
                        $stmt_check->execute();

                        if ($stmt_check->rowCount() > 0) {
                            // Actualizar existente
                            $query_update = "UPDATE estudiantes_discapacidades SET estatus = 1, actualizacion = NOW() 
                                           WHERE id_estudiante = ? AND id_discapacidad = ?";
                            $stmt_update = $this->db->prepare($query_update);
                            $stmt_update->bindParam(1, $id_estudiante);
                            $stmt_update->bindParam(2, $id_discapacidad);
                            $stmt_update->execute();
                        } else {
                            // Insertar nuevo
                            $query_insert = "INSERT INTO estudiantes_discapacidades (id_estudiante, id_discapacidad, creacion, estatus) 
                                           VALUES (?, ?, NOW(), 1)";
                            $stmt_insert = $this->db->prepare($query_insert);
                            $stmt_insert->bindParam(1, $id_estudiante);
                            $stmt_insert->bindParam(2, $id_discapacidad);
                            $stmt_insert->execute();
                        }
                    }
                }
            } else {
                // Si no se enviaron discapacidades, desactivar todas
                $query_delete = "UPDATE estudiantes_discapacidades SET estatus = 0 WHERE id_estudiante = ?";
                $stmt_delete = $this->db->prepare($query_delete);
                $stmt_delete->bindParam(1, $id_estudiante);
                $stmt_delete->execute();
            }
        } catch (Exception $e) {
            throw new Exception("Error al procesar información de salud: " . $e->getMessage());
        }
    }
}
?>