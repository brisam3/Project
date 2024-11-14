<?php
// Archivo: backend/controller/login/UserAuth.php
include_once '../../../database/Database.php';
include_once '../session/checkSession.php';

class UserAuth {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        error_log("Conexión a la base de datos establecida.");
    }

    public function login($usuario, $contrasena) {
        // Preparar la consulta para obtener idUsuario, contrasena, y idTipoUsuario
        $sql = "SELECT nombre, apellido, idUsuario, contrasena, idTipoUsuario FROM usuarios WHERE usuario = :usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['usuario' => $usuario]);

        // Obtener los datos del usuario
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log("Usuario obtenido de la base de datos: " . json_encode($user));

        // Verificar si la contraseña ingresada coincide con la almacenada
        if ($user && password_verify($contrasena, $user['contrasena'])) {
            error_log("Contraseña verificada correctamente para usuario: $usuario");

            // Guardar los datos del usuario en la sesión
            SessionController::startSession([
                'usuario' => $usuario,
                'idUsuario' => $user['idUsuario'],
                'idTipoUsuario' => $user['idTipoUsuario'],
                'nombre' => $user['nombre'],
                'apellido' => $user['apellido']
            ]);
            return $user;  // Retorna los datos del usuario
        } else {
            error_log("Error: Contraseña incorrecta o usuario no encontrado.");
        }

        return false;
    }
}
?>
