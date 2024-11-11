<?php
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

    public function register($nombre, $apellido, $usuario, $contrasena, $idTipoUsuario) {
        // Hashear la contraseña antes de almacenarla
        $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);

        // Preparar la consulta SQL para insertar el usuario
        $sql = "INSERT INTO usuarios (nombre, apellido, usuario, contrasena, idTipoUsuario) VALUES (:nombre,:apellido,:usuario, :contrasena, :idTipoUsuario)";
        $stmt = $this->db->prepare($sql);

        // Ejecutar la consulta con los parámetros
        return $stmt->execute([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'usuario' => $usuario,
            'contrasena' => $hashedPassword,
            'idTipoUsuario' => $idTipoUsuario
        ]);
    }
}
?>
