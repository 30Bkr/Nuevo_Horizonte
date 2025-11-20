<?php
class Docente {
    private $conn;
    private $table_name = "docentes";

    public $id_docente;
    public $id_persona;
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

    // Datos de usuario
    public $usuario;
    public $contrasena;
    public $id_rol = 2; // Rol Docente

    public function __construct($db) {
        $this->conn = $db;
    }

    // Listar todos los docentes
    public function listarDocentes() {
        $query = "SELECT 
                    d.id_docente,
                    d.id_profesion,
                    p.primer_nombre,
                    p.segundo_nombre,
                    p.primer_apellido,
                    p.segundo_apellido,
                    p.cedula,
                    p.telefono,
                    p.correo,
                    pr.profesion,
                    d.creacion,
                    d.estatus,
                    u.usuario,
                    r.nom_rol
                  FROM " . $this->table_name . " d
                  INNER JOIN personas p ON d.id_persona = p.id_persona
                  LEFT JOIN profesiones pr ON d.id_profesion = pr.id_profesion
                  LEFT JOIN usuarios u ON p.id_persona = u.id_persona
                  LEFT JOIN roles r ON u.id_rol = r.id_rol
                  WHERE d.estatus = 1
                  ORDER BY p.primer_apellido, p.primer_nombre";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Obtener docente por ID
    public function obtenerPorId($id) {
        $query = "SELECT 
                    d.*, p.*, dir.*, u.usuario, u.id_rol
                  FROM " . $this->table_name . " d
                  INNER JOIN personas p ON d.id_persona = p.id_persona
                  INNER JOIN direcciones dir ON p.id_direccion = dir.id_direccion
                  LEFT JOIN usuarios u ON p.id_persona = u.id_persona
                  WHERE d.id_docente = ? AND d.estatus = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Asignar propiedades del docente
            $this->id_docente = $row['id_docente'];
            $this->id_persona = $row['id_persona'];
            $this->id_profesion = $row['id_profesion'];
            
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
            
            // Datos de usuario
            $this->usuario = $row['usuario'];
            $this->id_rol = $row['id_rol'];

            return true;
        }
        return false;
    }

    // Crear nuevo docente
    public function crear() {
        try {
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
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 1)";
            
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

            // 3. Insertar docente
            $queryDocente = "INSERT INTO docentes (id_persona, id_profesion, creacion, estatus) 
                            VALUES (?, ?, NOW(), 1)";
            
            $stmtDocente = $this->conn->prepare($queryDocente);
            $stmtDocente->bindParam(1, $this->id_persona);
            $stmtDocente->bindParam(2, $this->id_profesion);
            $stmtDocente->execute();
            
            $this->id_docente = $this->conn->lastInsertId();

            // 4. Crear usuario automáticamente
            if (!empty($this->usuario)) {
                $queryUsuario = "INSERT INTO usuarios (id_persona, id_rol, usuario, contrasena, creacion, estatus) 
                                VALUES (?, ?, ?, ?, NOW(), 1)";
                
                // Hash de contraseña (por defecto: cédula)
                $contrasena_hash = hash('sha256', $this->cedula);
                
                $stmtUsuario = $this->conn->prepare($queryUsuario);
                $stmtUsuario->bindParam(1, $this->id_persona);
                $stmtUsuario->bindParam(2, $this->id_rol);
                $stmtUsuario->bindParam(3, $this->usuario);
                $stmtUsuario->bindParam(4, $contrasena_hash);
                $stmtUsuario->execute();
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    // Actualizar docente
    public function actualizar() {
        try {
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
                            SET primer_nombre = ?, segundo_nombre = ?, primer_apellido = ?, segundo_apellido = ?,
                                cedula = ?, telefono = ?, telefono_hab = ?, correo = ?, lugar_nac = ?, 
                                fecha_nac = ?, sexo = ?, nacionalidad = ?, actualizacion = NOW()
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

            // 3. Actualizar docente
            $queryDocente = "UPDATE docentes 
                            SET id_profesion = ?, actualizacion = NOW() 
                            WHERE id_docente = ?";
            
            $stmtDocente = $this->conn->prepare($queryDocente);
            $stmtDocente->bindParam(1, $this->id_profesion);
            $stmtDocente->bindParam(2, $this->id_docente);
            $stmtDocente->execute();

            // 4. Actualizar usuario si existe, o crear si no existe
            $queryCheckUsuario = "SELECT id_usuario FROM usuarios WHERE id_persona = ?";
            $stmtCheck = $this->conn->prepare($queryCheckUsuario);
            $stmtCheck->bindParam(1, $this->id_persona);
            $stmtCheck->execute();

            if ($stmtCheck->rowCount() > 0) {
                // Actualizar usuario existente
                $queryUsuario = "UPDATE usuarios SET usuario = ?, id_rol = ?, actualizacion = NOW() WHERE id_persona = ?";
                $stmtUsuario = $this->conn->prepare($queryUsuario);
                $stmtUsuario->bindParam(1, $this->usuario);
                $stmtUsuario->bindParam(2, $this->id_rol);
                $stmtUsuario->bindParam(3, $this->id_persona);
                $stmtUsuario->execute();
            } else {
                // Crear nuevo usuario
                $queryUsuario = "INSERT INTO usuarios (id_persona, id_rol, usuario, contrasena, creacion, estatus) 
                                VALUES (?, ?, ?, ?, NOW(), 1)";
                $contrasena_hash = hash('sha256', $this->cedula);
                
                $stmtUsuario = $this->conn->prepare($queryUsuario);
                $stmtUsuario->bindParam(1, $this->id_persona);
                $stmtUsuario->bindParam(2, $this->id_rol);
                $stmtUsuario->bindParam(3, $this->usuario);
                $stmtUsuario->bindParam(4, $contrasena_hash);
                $stmtUsuario->execute();
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    // Eliminar docente (soft delete)
    public function eliminar($id) {
        $query = "UPDATE docentes SET estatus = 0, actualizacion = NOW() WHERE id_docente = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        
       if ($stmt->execute()) {
        return true;
    }
    return false;
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

    // Verificar si usuario ya existe
    public function usuarioExiste($usuario, $id_persona_excluir = null) {
        $query = "SELECT id_usuario FROM usuarios WHERE usuario = ? AND estatus = 1";
        $params = [$usuario];
        
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