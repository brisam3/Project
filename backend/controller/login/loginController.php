<?php
// Archivo: backend/controller/login/loginController.php
session_start();
include '../../controller/login/UserAuth.php';

error_log("Inicio de loginController.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Verificar si se recibieron todos los datos necesarios
    if (empty($usuario) || empty($contrasena)) {
        error_log("Error: Usuario o contraseña vacíos");
        echo json_encode(['success' => false, 'message' => 'Por favor, ingrese el usuario y la contraseña']);
        exit;
    }

    $userAuth = new UserAuth();
    $user = $userAuth->login($usuario, $contrasena);

    if ($user) {
        // Login exitoso
        error_log("Login exitoso para usuario: $usuario");
        echo json_encode([
            'success' => true,
            'message' => 'Login exitoso',
            'idUsuario' => $user['idUsuario'],
            'idTipoUsuario' => $user['idTipoUsuario']
        ]);
    } else {
        // Login fallido
        error_log("Login fallido para usuario: $usuario");
        echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
    }
} else {
    error_log("Método de solicitud no válido");
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido']);
}
?>
