<?php
// app/roles_permisos_model.php

class RolesPermisosModel
{
  private $pdo;

  public function __construct()
  {
    require_once '/xampp/htdocs/final/app/conexion.php';
    $conexion = new Conexion();
    $this->pdo = $conexion->conectar();
  }

  // Obtener todos los roles
  public function getRoles()
  {
    try {
      $sql = "SELECT * FROM roles WHERE estatus = 1 ORDER BY nom_rol";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
      error_log("Error en getRoles: " . $e->getMessage());
      return [];
    }
  }

  // Obtener un rol por ID
  public function getRolById($id_rol)
  {
    try {
      $sql = "SELECT * FROM roles WHERE id_rol = :id_rol AND estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':id_rol', $id_rol);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
      error_log("Error en getRolById: " . $e->getMessage());
      return null;
    }
  }

  // Obtener todos los permisos ordenados por tipo
  public function getPermisos()
  {
    try {
      $sql = "SELECT * FROM permisos WHERE estatus = 1 ORDER BY tipo, nom_url";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
      error_log("Error en getPermisos: " . $e->getMessage());
      return [];
    }
  }

  // Obtener permisos agrupados por tipo
  public function getPermisosAgrupadosPorTipo()
  {
    try {
      $sql = "SELECT * FROM permisos WHERE estatus = 1 ORDER BY tipo, nom_url";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      $permisos = $stmt->fetchAll(PDO::FETCH_OBJ);

      // Agrupar por tipo
      $agrupados = [
        'vista' => [],
        'edicion' => [],
        'eliminacion' => []
      ];

      foreach ($permisos as $permiso) {
        $agrupados[$permiso->tipo][] = $permiso;
      }

      return $agrupados;
    } catch (PDOException $e) {
      error_log("Error en getPermisosAgrupadosPorTipo: " . $e->getMessage());
      return ['vista' => [], 'edicion' => [], 'eliminacion' => []];
    }
  }

  // Obtener permisos de un rol específico
  public function getPermisosByRol($id_rol)
  {
    try {
      $sql = "SELECT p.* 
                    FROM permisos p
                    INNER JOIN roles_permisos rp ON p.id_permiso = rp.id_permiso
                    WHERE rp.id_rol = :id_rol 
                      AND rp.estatus = 1
                      AND p.estatus = 1
                    ORDER BY p.tipo, p.nom_url";

      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':id_rol', $id_rol);
      $stmt->execute();

      $permisos = $stmt->fetchAll(PDO::FETCH_OBJ);

      // Convertir a array de IDs para fácil verificación
      $permisosIds = [];
      foreach ($permisos as $permiso) {
        $permisosIds[] = $permiso->id_permiso;
      }

      return $permisosIds;
    } catch (PDOException $e) {
      error_log("Error en getPermisosByRol: " . $e->getMessage());
      return [];
    }
  }

  // Actualizar permisos de un rol
  public function updatePermisosRol($id_rol, $permisosSeleccionados)
  {
    try {
      // Iniciar transacción
      $this->pdo->beginTransaction();

      // 1. Desactivar todos los permisos actuales del rol
      $sqlDesactivar = "UPDATE roles_permisos 
                             SET estatus = 0, 
                                 actualizacion = NOW()
                             WHERE id_rol = :id_rol";

      $stmtDesactivar = $this->pdo->prepare($sqlDesactivar);
      $stmtDesactivar->bindParam(':id_rol', $id_rol);
      $stmtDesactivar->execute();

      // 2. Activar/Insertar nuevos permisos
      if (!empty($permisosSeleccionados)) {
        foreach ($permisosSeleccionados as $id_permiso) {
          // Verificar si ya existe
          $sqlCheck = "SELECT id_rol_permiso FROM roles_permisos 
                                 WHERE id_rol = :id_rol AND id_permiso = :id_permiso";

          $stmtCheck = $this->pdo->prepare($sqlCheck);
          $stmtCheck->bindParam(':id_rol', $id_rol);
          $stmtCheck->bindParam(':id_permiso', $id_permiso);
          $stmtCheck->execute();

          $existe = $stmtCheck->fetch(PDO::FETCH_ASSOC);

          if ($existe) {
            // Actualizar existente
            $sqlUpdate = "UPDATE roles_permisos 
                                     SET estatus = 1, 
                                         actualizacion = NOW()
                                     WHERE id_rol = :id_rol 
                                       AND id_permiso = :id_permiso";

            $stmtUpdate = $this->pdo->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':id_rol', $id_rol);
            $stmtUpdate->bindParam(':id_permiso', $id_permiso);
            $stmtUpdate->execute();
          } else {
            // Insertar nuevo
            $sqlInsert = "INSERT INTO roles_permisos 
                                     (id_rol, id_permiso, creacion, estatus) 
                                     VALUES (:id_rol, :id_permiso, NOW(), 1)";

            $stmtInsert = $this->pdo->prepare($sqlInsert);
            $stmtInsert->bindParam(':id_rol', $id_rol);
            $stmtInsert->bindParam(':id_permiso', $id_permiso);
            $stmtInsert->execute();
          }
        }
      }

      // Commit transacción
      $this->pdo->commit();
      return true;
    } catch (PDOException $e) {
      // Rollback en caso de error
      $this->pdo->rollBack();
      error_log("Error en updatePermisosRol: " . $e->getMessage());
      return false;
    }
  }

  // Crear nuevo rol
  public function crearRol($nom_rol)
  {
    try {
      $sql = "INSERT INTO roles (nom_rol, creacion, estatus) 
                    VALUES (:nom_rol, NOW(), 1)";

      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':nom_rol', $nom_rol);

      if ($stmt->execute()) {
        return $this->pdo->lastInsertId();
      }
      return false;
    } catch (PDOException $e) {
      error_log("Error en crearRol: " . $e->getMessage());
      return false;
    }
  }

  // Actualizar rol
  public function actualizarRol($id_rol, $nom_rol)
  {
    try {
      $sql = "UPDATE roles 
                    SET nom_rol = :nom_rol, 
                        actualizacion = NOW()
                    WHERE id_rol = :id_rol";

      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':id_rol', $id_rol);
      $stmt->bindParam(':nom_rol', $nom_rol);

      return $stmt->execute();
    } catch (PDOException $e) {
      error_log("Error en actualizarRol: " . $e->getMessage());
      return false;
    }
  }

  // Eliminar/Desactivar rol
  public function eliminarRol($id_rol)
  {
    try {
      $sql = "UPDATE roles 
                    SET estatus = 0, 
                        actualizacion = NOW()
                    WHERE id_rol = :id_rol";

      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':id_rol', $id_rol);

      return $stmt->execute();
    } catch (PDOException $e) {
      error_log("Error en eliminarRol: " . $e->getMessage());
      return false;
    }
  }
}
