<?php
// Archivo: backend/controller/access/AccessController.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    class AccessController {
        private $permissions = [
            //locales
            1 => ['../devoluciones/vistaDevolucionesLocales.php', '../cierreCaja/cierreLocales.php'],
            //preventa
            2 => ['../devoluciones/preventa/vistaDevolucionesPreventa.php'],
            //deposito
            3 => ['../devoluciones/deposito/verDevoluciones.php'],
            //administracion
            4 => ['../administracion/reportes/reportes.php', '../administracion/reportes/reporteVentas.php'],
            //gerencia
            5 => ['../gerencia/vistaGerencia.php'],
            //contaduria
            6 => ['../contaduria/vistaContaduria.php'],
            //sistemas
            7 => ['../sistemas/vistaSistemas.php'],
            //chofer
            8 => ['../cierreCaja/cierreChoferes.php']
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
        echo "<script>alert('Acceso denegado'); window.location.href = '../mainPage/mainPage.php';</script>";
        exit;
    }
    
}
?>
