<?php
session_start();
include_once '../UserAuth.php';

// Asegúrate de que la respuesta será en formato JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Registro de logs para depuración (eliminar en producción)
    error_log("Usuario recibido: $usuario");
    error_log("Contraseña recibida (sin imprimir por seguridad)");

    $auth = new UserAuth();
    $isAuthenticated = $auth->login($usuario, $contrasena);

    // Respuesta según el resultado de la autenticación
    if ($isAuthenticated) {
        $_SESSION['usuario'] = $usuario;

        // Respuesta JSON de éxito
        echo json_encode([
            "status" => "success",
            "message" => "Inicio de sesión exitoso",
            "usuario" => $usuario
        ]);
    } else {
        // Respuesta JSON de error
        echo json_encode([
            "status" => "error",
            "message" => "¡Error! El usuario o la contraseña son incorrectos."
        ]);
    }
}
?>
