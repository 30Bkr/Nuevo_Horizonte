<?php
class Estudiante {
    private $conn;
    private $table_name = "estudiantes";

    public $id_estudiante;
    public $id_persona;
    public $creacion;
    public $actualizacion;
    public $estatus;

    // Datos de la persona
    public $primer_nombre;
    public $segundo_nombre;
    public $primer_apellido;
    public $segundo_apellido;
    public $cedula;
    public $telefono;
    public $telefono_hab;
    public $correo;
    public $lugar_nac;
    public $fecha_nac;
    public $sexo;
    public $nacionalidad;

    // Datos de dirección
    public $id_direccion;
    public $id_parroquia;
    public $direccion;
    public $calle;
    public $casa;

    // Datos del representante
    public $id_representante;
    public $id_parentesco;
    public $primer_nombre_rep;
    public $segundo_nombre_rep;
    public $primer_apellido_rep;
    public $segundo_apellido_rep;
    public $cedula_rep;
    public $telefono_rep;
    public $telefono_hab_rep;
    public $correo_rep;
    public $id_profesion_rep;
    public $ocupacion_rep;
    public $lugar_trabajo_rep;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Listar todos los estudiantes
    public function listarEstudiantes() {
        $query = "SELECT 
                    e.id_estudiante,
                    e.estatus,
                    p.primer_nombre,
                    p.segundo_nombre,
                    p.primer_apellido,
                    p.segundo_apellido,
                    p.cedula,
                    p.telefono,
                    p.correo,
                    p.fecha_nac,
                    p.sexo,
                    e.creacion,
                    (SELECT COUNT(*) FROM inscripciones i WHERE i.id_estudiante = e.id_estudiante AND i.estatus = 1) as inscripciones_count
                  FROM " . $this->table_name . " e
                  INNER JOIN personas p ON e.id_persona = p.id_persona
                  ORDER BY e.estatus DESC, p.primer_apellido, p.primer_nombre";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Obtener estudiante por ID
    public function obtenerPorId($id) {
        $query = "SELECT 
                    e.*, p.*, dir.*, 
                    er.id_representante, er.id_parentesco,
                    pr.primer_nombre as primer_nombre_rep,
                    pr.segundo_nombre as segundo_nombre_rep,
                    pr.primer_apellido as primer_apellido_rep,
                    pr.segundo_apellido as segundo_apellido_rep,
                    pr.cedula as cedula_rep,
                    pr.telefono as telefono_rep,
                    pr.telefono_hab as telefono_hab_rep,
                    pr.correo as correo_rep,
                    r.id_profesion as id_profesion_rep,
                    r.ocupacion as ocupacion_rep,
                    r.lugar_trabajo as lugar_trabajo_rep
                  FROM " . $this->table_name . " e
                  INNER JOIN personas p ON e.id_persona = p.id_persona
                  INNER JOIN direcciones dir ON p.id_direccion = dir.id_direccion
                  LEFT JOIN estudiantes_representantes er ON e.id_estudiante = er.id_estudiante AND er.estatus = 1
                  LEFT JOIN representantes r ON er.id_representante = r.id_representante
                  LEFT JOIN personas pr ON r.id_persona = pr.id_persona
                  WHERE e.id_estudiante = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Asignar propiedades del estudiante
            $this->id_estudiante = $row['id_estudiante'];
            $this->id_persona = $row['id_persona'];
            $this->estatus = $row['estatus'];
            
            // Datos de persona
            $this->primer_nombre = $row['primer_nombre'];
            $this->segundo_nombre = $row['segundo_nombre'];
            $this->primer_apellido = $row['primer_apellido'];
            $this->segundo_apellido = $row['segundo_apellido'];
            $this->cedula = $row['cedula'];
            $this->telefono = $row['telefono'];
            $this->telefono_hab = $row['telefono_hab'];
            $this->correo = $row['correo'];
            $this->lugar_nac = $row['lugar_nac'];
            $this->fecha_nac = $row['fecha_nac'];
            $this->sexo = $row['sexo'];
            $this->nacionalidad = $row['nacionalidad'];
            
            // Datos de dirección
            $this->id_direccion = $row['id_direccion'];
            $this->id_parroquia = $row['id_parroquia'];
            $this->direccion = $row['direccion'];
            $this->calle = $row['calle'];
            $this->casa = $row['casa'];
            
            // Datos del representante
            $this->id_representante = $row['id_representante'];
            $this->id_parentesco = $row['id_parentesco'];
            $this->primer_nombre_rep = $row['primer_nombre_rep'];
            $this->segundo_nombre_rep = $row['segundo_nombre_rep'];
            $this->primer_apellido_rep = $row['primer_apellido_rep'];
            $this->segundo_apellido_rep = $row['segundo_apellido_rep'];
            $this->cedula_rep = $row['cedula_rep'];
            $this->telefono_rep = $row['telefono_rep'];
            $this->telefono_hab_rep = $row['telefono_hab_rep'];
            $this->correo_rep = $row['correo_rep'];
            $this->id_profesion_rep = $row['id_profesion_rep'];
            $this->ocupacion_rep = $row['ocupacion_rep'];
            $this->lugar_trabajo_rep = $row['lugar_trabajo_rep'];

            return true;
        }
        return false;
    }

    // Crear nuevo estudiante
    public function crear() {
        try {
            // Validaciones
            $this->validarDatosEstudiante();

            $this->conn->beginTransaction();

            // 1. Insertar dirección del estudiante
            $queryDireccion = "INSERT INTO direcciones 
                              (id_parroquia, direccion, calle, casa, creacion, estatus) 
                              VALUES (?, ?, ?, ?, NOW(), 1)";
            
            $stmtDireccion = $this->conn->prepare($queryDireccion);
            $stmtDireccion->bindParam(1, $this->id_parroquia);
            $stmtDireccion->bindParam(2, $this->direccion);
            $stmtDireccion->bindParam(3, $this->calle);
            $stmtDireccion->bindParam(4, $this->casa);
            $stmtDireccion->execute();
            
            $this->id_direccion = $this->conn->lastInsertId();

            // 2. Insertar persona del estudiante
            $queryPersona = "INSERT INTO personas 
                            (id_direccion, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, 
                             cedula, telefono, telefono_hab, correo, lugar_nac, fecha_nac, sexo, nacionalidad, creacion, estatus) 
                            VALUES (?, UPPER(?), UPPER(?), UPPER(?), UPPER(?), ?, ?, ?, LOWER(?), UPPER(?), ?, UPPER(?), UPPER(?), NOW(), 1)";
            
            $stmtPersona = $this->conn->prepare($queryPersona);
            $stmtPersona->bindParam(1, $this->id_direccion);
            $stmtPersona->bindParam(2, $this->primer_nombre);
            $stmtPersona->bindParam(3, $this->segundo_nombre);
            $stmtPersona->bindParam(4, $this->primer_apellido);
            $stmtPersona->bindParam(5, $this->segundo_apellido);
            $stmtPersona->bindParam(6, $this->cedula);
            $stmtPersona->bindParam(7, $this->telefono);
            $stmtPersona->bindParam(8, $this->telefono_hab);
            $stmtPersona->bindParam(9, $this->correo);
            $stmtPersona->bindParam(10, $this->lugar_nac);
            $stmtPersona->bindParam(11, $this->fecha_nac);
            $stmtPersona->bindParam(12, $this->sexo);
            $stmtPersona->bindParam(13, $this->nacionalidad);
            $stmtPersona->execute();
            
            $this->id_persona = $this->conn->lastInsertId();

            // 3. Insertar estudiante
            $queryEstudiante = "INSERT INTO estudiantes (id_persona, creacion, estatus) 
                              VALUES (?, NOW(), 1)";
            
            $stmtEstudiante = $this->conn->prepare($queryEstudiante);
            $stmtEstudiante->bindParam(1, $this->id_persona);
            $stmtEstudiante->execute();
            
            $this->id_estudiante = $this->conn->lastInsertId();

            // 4. Procesar representante
            $this->procesarRepresentante();

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    // Actualizar estudiante
    public function actualizar() {
        try {
            // Validar datos antes de actualizar
            $this->validarDatosEstudiante();

            $this->conn->beginTransaction();

            // 1. Actualizar dirección
            $queryDireccion = "UPDATE direcciones 
                              SET id_parroquia = ?, direccion = ?, calle = ?, casa = ?, actualizacion = NOW() 
                              WHERE id_direccion = ?";
            
            $stmtDireccion = $this->conn->prepare($queryDireccion);
            $stmtDireccion->bindParam(1, $this->id_parroquia);
            $stmtDireccion->bindParam(2, $this->direccion);
            $stmtDireccion->bindParam(3, $this->calle);
            $stmtDireccion->bindParam(4, $this->casa);
            $stmtDireccion->bindParam(5, $this->id_direccion);
            $stmtDireccion->execute();

            // 2. Actualizar persona (EXCLUYENDO LA CÉDULA)
            $queryPersona = "UPDATE personas 
                            SET primer_nombre = UPPER(?), segundo_nombre = UPPER(?), primer_apellido = UPPER(?), segundo_apellido = UPPER(?),
                                telefono = ?, telefono_hab = ?, correo = LOWER(?), lugar_nac = UPPER(?), 
                                fecha_nac = ?, sexo = UPPER(?), nacionalidad = UPPER(?), actualizacion = NOW()
                            WHERE id_persona = ?";
            
            $stmtPersona = $this->conn->prepare($queryPersona);
            $stmtPersona->bindParam(1, $this->primer_nombre);
            $stmtPersona->bindParam(2, $this->segundo_nombre);
            $stmtPersona->bindParam(3, $this->primer_apellido);
            $stmtPersona->bindParam(4, $this->segundo_apellido);
            $stmtPersona->bindParam(5, $this->telefono);
            $stmtPersona->bindParam(6, $this->telefono_hab);
            $stmtPersona->bindParam(7, $this->correo);
            $stmtPersona->bindParam(8, $this->lugar_nac);
            $stmtPersona->bindParam(9, $this->fecha_nac);
            $stmtPersona->bindParam(10, $this->sexo);
            $stmtPersona->bindParam(11, $this->nacionalidad);
            $stmtPersona->bindParam(12, $this->id_persona);
            $stmtPersona->execute();

            // 3. Actualizar representante
            $this->actualizarRepresentante();

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    // Cambiar estado del estudiante
    public function cambiarEstado($id, $estado) {
        $query = "UPDATE estudiantes SET estatus = ?, actualizacion = NOW() WHERE id_estudiante = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $estado);
        $stmt->bindParam(2, $id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Métodos auxiliares privados
    private function validarDatosEstudiante() {
        // VALIDACIONES DEL ESTUDIANTE
        
        // Validar campos obligatorios del estudiante
        if (empty($this->nacionalidad)) {
            throw new Exception("La nacionalidad es obligatoria");
        }
        
        if (empty($this->cedula)) {
            throw new Exception("La cédula es obligatoria");
        }
        
        if (empty($this->fecha_nac)) {
            throw new Exception("La fecha de nacimiento es obligatoria");
        }
        
        if (empty($this->primer_nombre)) {
            throw new Exception("El primer nombre es obligatorio");
        }
        
        if (empty($this->segundo_nombre)) {
            throw new Exception("El segundo nombre es obligatorio");
        }
        
        if (empty($this->primer_apellido)) {
            throw new Exception("El primer apellido es obligatorio");
        }
        
        if (empty($this->segundo_apellido)) {
            throw new Exception("El segundo apellido es obligatorio");
        }
        
        if (empty($this->sexo)) {
            throw new Exception("El sexo es obligatorio");
        }
        
        if (empty($this->lugar_nac)) {
            throw new Exception("El lugar de nacimiento es obligatorio");
        }
        
        if (empty($this->telefono)) {
            throw new Exception("El teléfono móvil es obligatorio");
        }

        // Validar formato de correo
        if (!empty($this->correo) && !filter_var($this->correo, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El formato del correo electrónico no es válido");
        }

        // Validar que la cédula solo contenga números
        if (!empty($this->cedula) && !preg_match('/^\d+$/', $this->cedula)) {
            throw new Exception("La cédula debe contener solo números");
        }

        // Validar longitud mínima de cédula
        if (!empty($this->cedula) && strlen($this->cedula) < 6) {
            throw new Exception("La cédula debe tener al menos 6 dígitos");
        }

        // Validar que los teléfonos solo contengan números
        if (!empty($this->telefono) && !preg_match('/^\d+$/', $this->telefono)) {
            throw new Exception("El teléfono móvil debe contener solo números");
        }

        if (!empty($this->telefono_hab) && !preg_match('/^\d+$/', $this->telefono_hab)) {
            throw new Exception("El teléfono de habitación debe contener solo números");
        }

        // Validar que la fecha de nacimiento no sea futura
        if (!empty($this->fecha_nac)) {
            $hoy = new DateTime();
            $fechaNac = new DateTime($this->fecha_nac);
            if ($fechaNac > $hoy) {
                throw new Exception("La fecha de nacimiento no puede ser futura");
            }
        }

        // Validar que nombres y apellidos solo contengan letras y espacios
        if (!empty($this->primer_nombre) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $this->primer_nombre)) {
            throw new Exception("El primer nombre solo puede contener letras y espacios");
        }

        if (!empty($this->segundo_nombre) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $this->segundo_nombre)) {
            throw new Exception("El segundo nombre solo puede contener letras y espacios");
        }

        if (!empty($this->primer_apellido) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $this->primer_apellido)) {
            throw new Exception("El primer apellido solo puede contener letras y espacios");
        }

        if (!empty($this->segundo_apellido) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $this->segundo_apellido)) {
            throw new Exception("El segundo apellido solo puede contener letras y espacios");
        }

        if (!empty($this->nacionalidad) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $this->nacionalidad)) {
            throw new Exception("La nacionalidad solo puede contener letras y espacios");
        }

        if (!empty($this->lugar_nac) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $this->lugar_nac)) {
            throw new Exception("El lugar de nacimiento solo puede contener letras y espacios");
        }

        // VALIDACIONES DEL REPRESENTANTE
        
        // Validar campos obligatorios del representante
        if (empty($this->primer_nombre_rep)) {
            throw new Exception("El primer nombre del representante es obligatorio");
        }
        
        if (empty($this->segundo_nombre_rep)) {
            throw new Exception("El segundo nombre del representante es obligatorio");
        }
        
        if (empty($this->primer_apellido_rep)) {
            throw new Exception("El primer apellido del representante es obligatorio");
        }
        
        if (empty($this->segundo_apellido_rep)) {
            throw new Exception("El segundo apellido del representante es obligatorio");
        }
        
        if (empty($this->cedula_rep)) {
            throw new Exception("La cédula del representante es obligatoria");
        }

        if (empty($this->id_parentesco)) {
            throw new Exception("El parentesco es obligatorio");
        }
        
        if (empty($this->telefono_rep)) {
            throw new Exception("El teléfono móvil del representante es obligatorio");
        }

        // Validar formato de correo del representante
        if (!empty($this->correo_rep) && !filter_var($this->correo_rep, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El formato del correo electrónico del representante no es válido");
        }

        // Validar que la cédula del representante solo contenga números
        if (!empty($this->cedula_rep) && !preg_match('/^\d+$/', $this->cedula_rep)) {
            throw new Exception("La cédula del representante debe contener solo números");
        }

        // Validar longitud mínima de cédula del representante
        if (!empty($this->cedula_rep) && strlen($this->cedula_rep) < 6) {
            throw new Exception("La cédula del representante debe tener al menos 6 dígitos");
        }

        // Validar que los teléfonos del representante solo contengan números
        if (!empty($this->telefono_rep) && !preg_match('/^\d+$/', $this->telefono_rep)) {
            throw new Exception("El teléfono móvil del representante debe contener solo números");
        }

        if (!empty($this->telefono_hab_rep) && !preg_match('/^\d+$/', $this->telefono_hab_rep)) {
            throw new Exception("El teléfono de habitación del representante debe contener solo números");
        }

        // Validar que nombres y apellidos del representante solo contengan letras y espacios
        if (!empty($this->primer_nombre_rep) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $this->primer_nombre_rep)) {
            throw new Exception("El primer nombre del representante solo puede contener letras y espacios");
        }

        if (!empty($this->segundo_nombre_rep) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $this->segundo_nombre_rep)) {
            throw new Exception("El segundo nombre del representante solo puede contener letras y espacios");
        }

        if (!empty($this->primer_apellido_rep) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $this->primer_apellido_rep)) {
            throw new Exception("El primer apellido del representante solo puede contener letras y espacios");
        }

        if (!empty($this->segundo_apellido_rep) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $this->segundo_apellido_rep)) {
            throw new Exception("El segundo apellido del representante solo puede contener letras y espacios");
        }
    }

    private function procesarRepresentante() {
        // Verificar si el representante ya existe por cédula
        $queryCheckRep = "SELECT id_persona FROM personas WHERE cedula = ? AND estatus = 1";
        $stmtCheckRep = $this->conn->prepare($queryCheckRep);
        $stmtCheckRep->bindParam(1, $this->cedula_rep);
        $stmtCheckRep->execute();

        if ($stmtCheckRep->rowCount() > 0) {
            // Representante existe, obtener su ID
            $row = $stmtCheckRep->fetch(PDO::FETCH_ASSOC);
            $id_persona_rep = $row['id_persona'];
            
            // Verificar si ya es representante
            $queryCheckRepExist = "SELECT id_representante FROM representantes WHERE id_persona = ?";
            $stmtCheckRepExist = $this->conn->prepare($queryCheckRepExist);
            $stmtCheckRepExist->bindParam(1, $id_persona_rep);
            $stmtCheckRepExist->execute();

            if ($stmtCheckRepExist->rowCount() > 0) {
                $rowRep = $stmtCheckRepExist->fetch(PDO::FETCH_ASSOC);
                $this->id_representante = $rowRep['id_representante'];
            } else {
                // Crear representante
                $queryRep = "INSERT INTO representantes (id_persona, id_profesion, ocupacion, lugar_trabajo, creacion, estatus) 
                            VALUES (?, ?, UPPER(?), UPPER(?), NOW(), 1)";
                $stmtRep = $this->conn->prepare($queryRep);
                $stmtRep->bindParam(1, $id_persona_rep);
                $stmtRep->bindParam(2, $this->id_profesion_rep);
                $stmtRep->bindParam(3, $this->ocupacion_rep);
                $stmtRep->bindParam(4, $this->lugar_trabajo_rep);
                $stmtRep->execute();
                $this->id_representante = $this->conn->lastInsertId();
            }
        } else {
            // Crear nuevo representante completo
            // 1. Insertar dirección del representante (usar misma dirección del estudiante por defecto)
            $queryDireccionRep = "INSERT INTO direcciones 
                                (id_parroquia, direccion, calle, casa, creacion, estatus) 
                                VALUES (?, UPPER(?), UPPER(?), UPPER(?), NOW(), 1)";
            
            $stmtDireccionRep = $this->conn->prepare($queryDireccionRep);
            $stmtDireccionRep->bindParam(1, $this->id_parroquia);
            $stmtDireccionRep->bindParam(2, $this->direccion);
            $stmtDireccionRep->bindParam(3, $this->calle);
            $stmtDireccionRep->bindParam(4, $this->casa);
            $stmtDireccionRep->execute();
            $id_direccion_rep = $this->conn->lastInsertId();

            // 2. Insertar persona del representante
            $queryPersonaRep = "INSERT INTO personas 
                               (id_direccion, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, 
                                cedula, telefono, telefono_hab, correo, lugar_nac, fecha_nac, sexo, nacionalidad, creacion, estatus) 
                               VALUES (?, UPPER(?), UPPER(?), UPPER(?), UPPER(?), ?, ?, ?, LOWER(?), UPPER(?), ?, UPPER(?), UPPER(?), NOW(), 1)";
            
            $stmtPersonaRep = $this->conn->prepare($queryPersonaRep);
            $stmtPersonaRep->bindParam(1, $id_direccion_rep);
            $stmtPersonaRep->bindParam(2, $this->primer_nombre_rep);
            $stmtPersonaRep->bindParam(3, $this->segundo_nombre_rep);
            $stmtPersonaRep->bindParam(4, $this->primer_apellido_rep);
            $stmtPersonaRep->bindParam(5, $this->segundo_apellido_rep);
            $stmtPersonaRep->bindParam(6, $this->cedula_rep);
            $stmtPersonaRep->bindParam(7, $this->telefono_rep);
            $stmtPersonaRep->bindParam(8, $this->telefono_hab_rep);
            $stmtPersonaRep->bindParam(9, $this->correo_rep);
            $stmtPersonaRep->bindParam(10, $this->lugar_nac);
            $stmtPersonaRep->bindParam(11, $this->fecha_nac);
            $stmtPersonaRep->bindParam(12, $this->sexo);
            $stmtPersonaRep->bindParam(13, $this->nacionalidad);
            $stmtPersonaRep->execute();
            $id_persona_rep = $this->conn->lastInsertId();

            // 3. Insertar representante
            $queryRep = "INSERT INTO representantes (id_persona, id_profesion, ocupacion, lugar_trabajo, creacion, estatus) 
                        VALUES (?, ?, UPPER(?), UPPER(?), NOW(), 1)";
            $stmtRep = $this->conn->prepare($queryRep);
            $stmtRep->bindParam(1, $id_persona_rep);
            $stmtRep->bindParam(2, $this->id_profesion_rep);
            $stmtRep->bindParam(3, $this->ocupacion_rep);
            $stmtRep->bindParam(4, $this->lugar_trabajo_rep);
            $stmtRep->execute();
            $this->id_representante = $this->conn->lastInsertId();
        }

        // 5. Relacionar estudiante con representante
        $queryRelacion = "INSERT INTO estudiantes_representantes 
                         (id_estudiante, id_representante, id_parentesco, creacion, estatus) 
                         VALUES (?, ?, ?, NOW(), 1)";
        $stmtRelacion = $this->conn->prepare($queryRelacion);
        $stmtRelacion->bindParam(1, $this->id_estudiante);
        $stmtRelacion->bindParam(2, $this->id_representante);
        $stmtRelacion->bindParam(3, $this->id_parentesco);
        $stmtRelacion->execute();
    }

    private function actualizarRepresentante() {
        // Actualizar datos del representante existente
        if ($this->id_representante) {
            // Actualizar persona del representante
            $queryPersonaRep = "UPDATE personas 
                               SET primer_nombre = UPPER(?), segundo_nombre = UPPER(?), primer_apellido = UPPER(?), segundo_apellido = UPPER(?),
                                   telefono = ?, telefono_hab = ?, correo = LOWER(?), actualizacion = NOW()
                               WHERE id_persona = (SELECT id_persona FROM representantes WHERE id_representante = ?)";
            
            $stmtPersonaRep = $this->conn->prepare($queryPersonaRep);
            $stmtPersonaRep->bindParam(1, $this->primer_nombre_rep);
            $stmtPersonaRep->bindParam(2, $this->segundo_nombre_rep);
            $stmtPersonaRep->bindParam(3, $this->primer_apellido_rep);
            $stmtPersonaRep->bindParam(4, $this->segundo_apellido_rep);
            $stmtPersonaRep->bindParam(5, $this->telefono_rep);
            $stmtPersonaRep->bindParam(6, $this->telefono_hab_rep);
            $stmtPersonaRep->bindParam(7, $this->correo_rep);
            $stmtPersonaRep->bindParam(8, $this->id_representante);
            $stmtPersonaRep->execute();

            // Actualizar representante
            $queryRep = "UPDATE representantes 
                        SET id_profesion = ?, ocupacion = UPPER(?), lugar_trabajo = UPPER(?), actualizacion = NOW()
                        WHERE id_representante = ?";
            $stmtRep = $this->conn->prepare($queryRep);
            $stmtRep->bindParam(1, $this->id_profesion_rep);
            $stmtRep->bindParam(2, $this->ocupacion_rep);
            $stmtRep->bindParam(3, $this->lugar_trabajo_rep);
            $stmtRep->bindParam(4, $this->id_representante);
            $stmtRep->execute();

            // Actualizar relación
            $queryRelacion = "UPDATE estudiantes_representantes 
                             SET id_parentesco = ?, actualizacion = NOW()
                             WHERE id_estudiante = ? AND id_representante = ?";
            $stmtRelacion = $this->conn->prepare($queryRelacion);
            $stmtRelacion->bindParam(1, $this->id_parentesco);
            $stmtRelacion->bindParam(2, $this->id_estudiante);
            $stmtRelacion->bindParam(3, $this->id_representante);
            $stmtRelacion->execute();
        }
    }

    // Obtener lista de parentescos
    public function obtenerParentescos() {
        $query = "SELECT id_parentesco, parentesco FROM parentesco WHERE estatus = 1 ORDER BY parentesco";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener lista de profesiones
    public function obtenerProfesiones() {
        $query = "SELECT id_profesion, profesion FROM profesiones WHERE estatus = 1 ORDER BY profesion";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener lista de parroquias
    public function obtenerParroquias() {
        $query = "SELECT p.id_parroquia, p.nom_parroquia, m.nom_municipio, e.nom_estado 
                  FROM parroquias p
                  INNER JOIN municipios m ON p.id_municipio = m.id_municipio
                  INNER JOIN estados e ON m.id_estado = e.id_estado
                  WHERE p.estatus = 1
                  ORDER BY e.nom_estado, m.nom_municipio, p.nom_parroquia";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Verificar si cédula ya existe
    public function cedulaExiste($cedula, $id_persona_excluir = null) {
        $query = "SELECT id_persona FROM personas WHERE cedula = ? AND estatus = 1";
        $params = [$cedula];
        
        if ($id_persona_excluir) {
            $query .= " AND id_persona != ?";
            $params[] = $id_persona_excluir;
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        
        return $stmt->rowCount() > 0;
    }
}
?>