<?php
// Archivo: backend/controller/access/AccessController.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class AccessController {
    private $permissions = [
        1 => ['/pages/devoluciones/vistaDevolucionesLocales.php'],
        8 => ['/pages/devoluciones/preventa/vistaDevolucionesPreventa.php'],
        2 => ['/pages/devoluciones/deposito/verDevoluciones.php'],
        4 => ['/pages/administracion/vistaAdministracion.php'],
        5 => ['/pages/gerencia/vistaGerencia.php'],
        6 => ['/pages/contaduria/vistaContaduria.php'],
        7 => ['/pages/sistemas/vistaSistemas.php']
    ];

    public function checkAccess($page) {
        if (!isset($_SESSION['idTipoUsuario'])) {
            error_log("Acceso denegado: idTipoUsuario no definido en la sesión.");
            return false;
        }

        $idTipoUsuario = $_SESSION['idTipoUsuario'];
        if (isset($this->permissions[$idTipoUsuario]) && in_array($page, $this->permissions[$idTipoUsuario])) {
            return true;
        }
        return false;
    }
    
    public function denyAccess() {
        echo "<script>alert('Acceso denegado'); window.location.href = '/project/pages/mainPage/mainPage.php';</script>";
        exit;
    }
}
?>
