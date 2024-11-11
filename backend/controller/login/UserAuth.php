<?php
// Archivo: backend/controller/login/UserAuth.php
include_once '../../../database/Database.php';

class UserAuth {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function login($usuario, $contrasena) {
        // Preparar la consulta para obtener idUsuario, contrasena, y idTipoUsuario
        $sql = "SELECT idUsuario, contrasena, idTipoUsuario FROM usuarios WHERE usuario = :usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['usuario' => $usuario]);

        // Obtener los datos del usuario
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si la contraseña ingresada coincide con la almacenada
        if ($user && password_verify($contrasena, $user['contrasena'])) {
            // Si las credenciales son correctas, guardar los datos en la sesión
            $_SESSION['usuario'] = $usuario;
            $_SESSION['idUsuario'] = $user['idUsuario'];
            $_SESSION['idTipoUsuario'] = $user['idTipoUsuario'];
            return $user;  // Retornar los datos del usuario
        }

        return false;
    }
}
?>
