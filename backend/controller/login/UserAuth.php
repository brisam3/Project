<?php
// UserAuth.php - Actualizando para agregar la funcionalidad de login
include_once '../../../database/Database.php';

class UserAuth {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function userExists($usuario) {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE usuario = :usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['usuario' => $usuario]);
        return $stmt->fetchColumn() > 0;
    }

    public function register($usuario, $contrasena, $idTipoUsuario) {
        // Hashear la contraseña antes de almacenarla
        $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);

        // Preparar la consulta SQL para insertar el usuario
        $sql = "INSERT INTO usuarios (usuario, contrasena, idTipoUsuario) VALUES (:usuario, :contrasena, :idTipoUsuario)";
        $stmt = $this->db->prepare($sql);

        // Ejecutar la consulta con los parámetros
        return $stmt->execute([
            'usuario' => $usuario,
            'contrasena' => $hashedPassword,
            'idTipoUsuario' => $idTipoUsuario
        ]);
    }

    public function login($usuario, $contrasena) {
        // Preparar la consulta para obtener la contraseña del usuario
        $sql = "SELECT contrasena FROM usuarios WHERE usuario = :usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['usuario' => $usuario]);
        $hashedPassword = $stmt->fetchColumn();

        // Verificar si la contraseña ingresada coincide con la almacenada
        if ($hashedPassword && password_verify($contrasena, $hashedPassword)) {
            return true;
        }
        return false;
    }
}
?>