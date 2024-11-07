<?php
include_once '../../database/Database.php';

class UserAuth {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function login($correoElectronico, $contrasena) {
        // Verificar si el correo existe
        $sql = "SELECT * FROM usuarios WHERE correo = :correo";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['correo' => $correoElectronico]);
        $user = $stmt->fetch();

        if (!$user) {
            echo "¡Error! El correo no existe";
            return false;
        }

        // Verificar si la contraseña es correcta
        if ($user['contrasena'] === $contrasena) {
            return true;
        } else {
            echo "¡Error! La contraseña es incorrecta";
            return false;
        }
    }
}
