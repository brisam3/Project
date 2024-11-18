<?php
// Archivo: backend/controller/access/AccessController.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    class AccessController {
        private $permissions = [
            //locales
            1 => ['/pages/devoluciones/vistaDevolucionesLocales.php', '/pages/cierreCaja/cierreLocales.php'],
            //preventa
            2 => ['/pages/devoluciones/preventa/vistaDevolucionesPreventa.php'],
            //deposito
            3 => ['/pages/devoluciones/deposito/verDevoluciones.php'],
            //administracion
            4 => ['/pages/administracion/vistaAdministracion.php', '/pages/administracion/reporteVentas.php'],
            //gerencia
            5 => ['/pages/gerencia/vistaGerencia.php'],
            //contaduria
            6 => ['/pages/contaduria/vistaContaduria.php'],
            //sistemas
            7 => ['/pages/sistemas/vistaSistemas.php'],
            //chofer
            8 => ['/pages/cierreCaja/cierreChoferes.php']
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
