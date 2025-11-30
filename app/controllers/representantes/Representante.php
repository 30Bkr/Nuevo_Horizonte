<?php
class Representante {
    private $conn;
    private $table_name = "representantes";

    public $id_representante;
    public $id_persona;
    public $ocupacion;
    public $lugar_trabajo;
    public $id_profesion;
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

    public function __construct($db) {
        $this->conn = $db;
    }

    // Listar todos los representantes
    public function listarRepresentantes() {
        $query = "SELECT 
                    r.id_representante,
                    r.estatus,
                    p.primer_nombre,
                    p.segundo_nombre,
                    p.primer_apellido,
                    p.segundo_apellido,
                    p.cedula,
                    p.telefono,
                    p.correo,
                    p.fecha_nac,
                    p.sexo,
                    r.ocupacion,
                    r.lugar_trabajo,
                    prof.profesion,
                    r.creacion,
                    (SELECT COUNT(*) FROM estudiantes_representantes er WHERE er.id_representante = r.id_representante AND er.estatus = 1) as estudiantes_count
                  FROM " . $this->table_name . " r
                  INNER JOIN personas p ON r.id_persona = p.id_persona
                  LEFT JOIN profesiones prof ON r.id_profesion = prof.id_profesion
                  ORDER BY r.estatus DESC, p.primer_apellido, p.primer_nombre";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Obtener representante por ID
    public function obtenerPorId($id) {
        $query = "SELECT 
                    r.*, p.*, dir.*
                  FROM " . $this->table_name . " r
                  INNER JOIN personas p ON r.id_persona = p.id_persona
                  INNER JOIN direcciones dir ON p.id_direccion = dir.id_direccion
                  WHERE r.id_representante = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Asignar propiedades del representante
            $this->id_representante = $row['id_representante'];
            $this->id_persona = $row['id_persona'];
            $this->ocupacion = $row['ocupacion'];
            $this->lugar_trabajo = $row['lugar_trabajo'];
            $this->id_profesion = $row['id_profesion'];
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

            return true;
        }
        return false;
    }

    // Crear nuevo representante
    public function crear() {
        try {
            // Validaciones
            $this->validarDatosRepresentante();

            $this->conn->beginTransaction();

            // 1. Insertar dirección
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

            // 2. Insertar persona
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

            // 3. Insertar representante
            $queryRepresentante = "INSERT INTO representantes 
                                  (id_persona, id_profesion, ocupacion, lugar_trabajo, creacion, estatus) 
                                  VALUES (?, ?, UPPER(?), UPPER(?), NOW(), 1)";
            
            $stmtRepresentante = $this->conn->prepare($queryRepresentante);
            $stmtRepresentante->bindParam(1, $this->id_persona);
            $stmtRepresentante->bindParam(2, $this->id_profesion);
            $stmtRepresentante->bindParam(3, $this->ocupacion);
            $stmtRepresentante->bindParam(4, $this->lugar_trabajo);
            $stmtRepresentante->execute();
            
            $this->id_representante = $this->conn->lastInsertId();

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    // Actualizar representante
    public function actualizar() {
        try {
            // Validar datos antes de actualizar
            $this->validarDatosRepresentante();

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

            // 2. Actualizar persona
            $queryPersona = "UPDATE personas 
                            SET primer_nombre = UPPER(?), segundo_nombre = UPPER(?), primer_apellido = UPPER(?), segundo_apellido = UPPER(?),
                                cedula = ?, telefono = ?, telefono_hab = ?, correo = LOWER(?), lugar_nac = UPPER(?), 
                                fecha_nac = ?, sexo = UPPER(?), nacionalidad = UPPER(?), actualizacion = NOW()
                            WHERE id_persona = ?";
            
            $stmtPersona = $this->conn->prepare($queryPersona);
            $stmtPersona->bindParam(1, $this->primer_nombre);
            $stmtPersona->bindParam(2, $this->segundo_nombre);
            $stmtPersona->bindParam(3, $this->primer_apellido);
            $stmtPersona->bindParam(4, $this->segundo_apellido);
            $stmtPersona->bindParam(5, $this->cedula);
            $stmtPersona->bindParam(6, $this->telefono);
            $stmtPersona->bindParam(7, $this->telefono_hab);
            $stmtPersona->bindParam(8, $this->correo);
            $stmtPersona->bindParam(9, $this->lugar_nac);
            $stmtPersona->bindParam(10, $this->fecha_nac);
            $stmtPersona->bindParam(11, $this->sexo);
            $stmtPersona->bindParam(12, $this->nacionalidad);
            $stmtPersona->bindParam(13, $this->id_persona);
            $stmtPersona->execute();

            // 3. Actualizar representante
            $queryRepresentante = "UPDATE representantes 
                                  SET id_profesion = ?, ocupacion = UPPER(?), lugar_trabajo = UPPER(?), actualizacion = NOW()
                                  WHERE id_representante = ?";
            
            $stmtRepresentante = $this->conn->prepare($queryRepresentante);
            $stmtRepresentante->bindParam(1, $this->id_profesion);
            $stmtRepresentante->bindParam(2, $this->ocupacion);
            $stmtRepresentante->bindParam(3, $this->lugar_trabajo);
            $stmtRepresentante->bindParam(4, $this->id_representante);
            $stmtRepresentante->execute();

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    // Cambiar estado del representante
    public function cambiarEstado($id, $estado) {
        $query = "UPDATE representantes SET estatus = ?, actualizacion = NOW() WHERE id_representante = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $estado);
        $stmt->bindParam(2, $id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Métodos auxiliares privados
    private function validarDatosRepresentante() {
        // Validar campos obligatorios
        if (empty($this->nacionalidad)) {
            throw new Exception("La nacionalidad es obligatoria");
        }
        
        if (empty($this->cedula)) {
            throw new Exception("La cédula es obligatoria");
        }
        
        if (empty($this->primer_nombre)) {
            throw new Exception("El primer nombre es obligatorio");
        }
        
        if (empty($this->primer_apellido)) {
            throw new Exception("El primer apellido es obligatorio");
        }
        
        if (empty($this->sexo)) {
            throw new Exception("El sexo es obligatorio");
        }

        if (empty($this->telefono)) {
            throw new Exception("El teléfono móvil es obligatorio");
        }

        if (empty($this->id_profesion)) {
            throw new Exception("La profesión es obligatoria");
        }

        if (empty($this->ocupacion)) {
            throw new Exception("La ocupación es obligatoria");
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

        if (!empty($this->ocupacion) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $this->ocupacion)) {
            throw new Exception("La ocupación solo puede contener letras y espacios");
        }
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