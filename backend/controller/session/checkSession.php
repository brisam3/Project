<?php
// Archivo: checkSession.php
session_start(); // Asegúrate de iniciar la sesión

if (!isset($_SESSION['idUsuario'])) {
    // Si no hay sesión iniciada, redirigir al login
    header("Location: ../../pages/login/login.html");
    exit();
} else {
    // Verificar si los datos de la sesión están disponibles
    $nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : "Usuario";
    $apellido = isset($_SESSION['apellido']) ? $_SESSION['apellido'] : "";
}
?>