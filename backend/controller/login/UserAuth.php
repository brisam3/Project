<?php
include_once '../../../database/Database.php';

class UserAuth {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function login($usuario, $contrasena) {
        // Verificar si el usuario existe
        $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['usuario' => $usuario]);
        $userRecord = $stmt->fetch();
    
        // Imprime si encontró el usuario
        if (!$userRecord) {
            echo "¡Error! El usuario no existe";
            return false;
        }
    
        // Verificar si la contraseña es correcta
        echo "Contraseña en base de datos: " . $userRecord['contrasena']; // Verifica la contraseña en la base de datos
        if ($userRecord['contrasena'] === $contrasena) {
            return true;
        } else {
            echo "¡Error! La contraseña es incorrecta";
            return false;
        }
    }
}
?>