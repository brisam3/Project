<?php
// Archivo: backend/controller/auth/loginController.php
session_start(); // Iniciar la sesión al principio del archivo

include '../../controller/login/UserAuth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Verificar si se recibieron todos los datos necesarios
    if (empty($usuario) || empty($contrasena)) {
        echo json_encode(['error' => 'Por favor, ingrese el usuario y la contraseña']);
        exit;
    }

    $userAuth = new UserAuth();
    $user = $userAuth->login($usuario, $contrasena);

    if ($user) {
        // `login` retorna el usuario, que contiene `idUsuario` e `idTipoUsuario`
        echo json_encode([
            'success' => true,
            'message' => 'Login exitoso',
            'idUsuario' => $user['idUsuario'],        // Devolver el idUsuario en la respuesta
            'idTipoUsuario' => $user['idTipoUsuario'] // Devolver también el idTipoUsuario en la respuesta
        ]);
        
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
    }
}
?>
