<?php
session_start();
include_once 'UserAuth.php';

// Asegúrate de que la respuesta sea en formato JSON
header('Content-Type: application/json');

error_log("Script iniciado correctamente.");

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST['usuario'] ?? null;
        $contrasena = $_POST['contrasena'] ?? null;
        $idTipoUsuario = $_POST['idTipoUsuario'] ?? null;

        error_log("Datos recibidos: usuario={$usuario}, contrasena={$contrasena}, idTipoUsuario={$idTipoUsuario}");

        // Validar datos recibidos
        if (empty($usuario) || empty($contrasena) || empty($idTipoUsuario)) {
            echo json_encode([
                "status" => "error",
                "message" => "Todos los campos son obligatorios."
            ]);
            error_log("Error: Todos los campos son obligatorios.");
            exit;
        }

        $auth = new UserAuth();

        // Verificar si el usuario ya existe
        if ($auth->userExists($usuario)) {
            echo json_encode([
                "status" => "error",
                "message" => "El nombre de usuario ya está registrado."
            ]);
            error_log("Error: El nombre de usuario ya está registrado.");
            exit;
        }

        // Registrar al usuario
        $isRegistered = $auth->register($usuario, $contrasena, $idTipoUsuario);

        // Respuesta JSON según el resultado del registro
        if ($isRegistered) {
           
            echo json_encode([
                "status" => "success",
                "message" => "Usuario registrado exitosamente"
            ]);
            error_log("Usuario registrado exitosamente.");
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Hubo un error al registrar el usuario."
            ]);
            error_log("Error: Hubo un error al registrar el usuario.");
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
    error_log("Error en registerController.php: " . $e->getMessage());
}
?>
