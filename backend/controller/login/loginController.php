<?php
// loginController.php - Controlador para manejar el inicio de sesión
session_start();
include_once 'UserAuth.php';

// Asegúrate de que la respuesta sea en formato JSON
header('Content-Type: application/json');

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST['usuario'] ?? null;
        $contrasena = $_POST['contrasena'] ?? null;

        // Validar datos recibidos
        if (empty($usuario) || empty($contrasena)) {
            echo json_encode([
                "status" => "error",
                "message" => "Todos los campos son obligatorios."
            ]);
            exit;
        }

        $auth = new UserAuth();

        // Intentar iniciar sesión
        if ($auth->login($usuario, $contrasena)) {
            // Guardar información del usuario en la sesión
            $_SESSION['usuario'] = $usuario;
            echo json_encode([
                "status" => "success",
                "message" => "Inicio de sesión exitoso"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Usuario o contraseña incorrectos."
            ]);
        }
    } else {
        throw new Exception("Método de solicitud no válido.");
    }
} catch (Exception $e) {
    // Enviar una respuesta JSON con el error
    echo json_encode([
        "status" => "error",
        "message" => "Error del servidor: " . $e->getMessage()
    ]);
}
?>
