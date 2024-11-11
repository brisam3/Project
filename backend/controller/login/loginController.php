<?php
// backend/controller/auth/loginController.php
session_start();

include '../../controller/login/UserAuth.php';

header('Content-Type: application/json');  // Asegura que la respuesta sea en formato JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Verificar si se recibieron todos los datos necesarios
    if (empty($usuario) || empty($contrasena)) {
        echo json_encode(['success' => false, 'message' => 'Por favor, ingrese el usuario y la contraseña']);
        exit;
    }

    $userAuth = new UserAuth();
    $user = $userAuth->login($usuario, $contrasena);

    if ($user) {
        // Si la contraseña es correcta, devolver los datos del usuario
        echo json_encode([
            'success' => true,
            'message' => 'Login exitoso',
            'idUsuario' => $user['idUsuario'],
            'idTipoUsuario' => $user['idTipoUsuario']
        ]);
    } else {
        // Si la contraseña no es correcta o el usuario no existe
        echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
    }
}

?>