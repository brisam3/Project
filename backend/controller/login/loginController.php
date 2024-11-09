<?php
// Archivo: backend/controller/auth/loginController.php
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
    $resultado = $userAuth->login($usuario, $contrasena);

    if ($resultado) {
        session_start();
        $_SESSION['usuario'] = $usuario;
        echo json_encode(['success' => true, 'message' => 'Login exitoso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
    }
}
?>
