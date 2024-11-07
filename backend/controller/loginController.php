<?php
session_start();
include_once 'UserAuth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correoElectronico = $_POST['correoElectronico'];
    $contrasena = $_POST['contrasena'];

    $auth = new UserAuth();
    $isAuthenticated = $auth->login($correoElectronico, $contrasena);

    if ($isAuthenticated) {
        $_SESSION['user'] = $correoElectronico;
        echo "success";
    } else {
        echo "error";
    }
}
?>
