<?php
// UserAuth.php - Actualizando para agregar la funcionalidad de login
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
        // Preparar la consulta para obtener la contraseña y el idUsuario
        $sql = "SELECT idUsuario, contrasena, idTipoUsuario FROM usuarios WHERE usuario = :usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['usuario' => $usuario]);
        
        // Obtener los datos del usuario
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Verificar si la contraseña ingresada coincide con la almacenada
        if ($user && password_verify($contrasena, $user['contrasena'])) {
            // Si las credenciales son correctas, guardar los datos en la sesión
            $_SESSION['usuario'] = $usuario;
            $_SESSION['idUsuario'] = $user['idUsuario'];  // Guardamos el idUsuario en la sesión
            $_SESSION['idTipoUsuario'] = $user['idTipoUsuario']; // Guardamos el idTipoUsuario en la sesión
            return true;
        }
        return false;
    }
    
}

?>