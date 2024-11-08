<?php
include_once '../../../database/Database.php';

class UserAuth {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function login($usuario, $contrasena) {
        // Buscar el usuario en la base de datos
        $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['usuario' => $usuario]);
        $userRecord = $stmt->fetch();

        // Si el usuario no existe, devuelve false
        if (!$userRecord) {
            error_log("¡Error! El usuario no existe");
            return false;
        }

        // Verificar la contraseña utilizando password_verify
        if (password_verify($contrasena, $userRecord['contrasena'])) {
            return true;
        } else {
            error_log("¡Error! La contraseña es incorrecta");
            return false;
        }
    }

    // Método para registrar un usuario con una contraseña hasheada
    public function register($usuario, $contrasena) {
        $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO usuarios (usuario, contrasena) VALUES (:usuario, :contrasena)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute(['usuario' => $usuario, 'contrasena' => $hashedPassword]);
    }
}
?>
