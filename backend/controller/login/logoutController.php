<?php
// Archivo: backend/controller/login/logoutController.php

session_start();

// Verifica si la sesión está activa
if (session_status() === PHP_SESSION_ACTIVE) {
    // Destruye todas las variables de la sesión
    $_SESSION = [];

    // Elimina la cookie de la sesión, si existe
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }

    // Finalmente, destruye la sesión
    session_destroy();
    echo json_encode(['success' => true, 'message' => 'Sesión cerrada correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'No hay sesión activa']);
}
?>