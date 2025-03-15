<?php
// Archivo: backend/controller/access/AccessController.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    class AccessController {
        private $permissions = [
            //locales
            1 => ['/pages/locales/Devoluciones.php', '/pages/locales/CierreCaja.php', '/pages/locales/Transferencias.php', '/pages/locales/ConteoStock.php'],
            //preventa
            2 => ['/pages/preventa/reporteHistoricoPreventa.php'],
            //deposito
            3 => ['/pages/deposito/Devoluciones.php','/pages/deposito/Transferencias.php'],
            //administracion
            4 => ['/pages/administracion/ImportarReporte.php', '/pages/administracion/ReporteVentas.php','/pages/administracion/RendicionGeneral.php', '/pages/administracion/PrintRendicionGeneral.php', '/pages/administracion/camiones.php'],
            //gerencia
            5 => ['/'],
            //contaduria
            6 => ['/'],
            //sistemas
            7 => ['/pages/register/register.php', '/pages/sistemas/ImportarReporte.php', '/pages/locales/importarArticulos.php'],
            //chofer
            8 => ['/pages/choferes/CierreCaja.php', '/pages/choferes/CierreCajaAnterior.php', '/pages/choferes/Devoluciones.php', '/pages/choferes/VerRendiciones.php']
        ];
    
    public function checkAccess($page) {
        if (!isset($_SESSION['idTipoUsuario'])) {
            error_log("Acceso denegado: idTipoUsuario no definido en la sesión.");
            $this->logSessionInConsole();
            return false;
        }

        $idTipoUsuario = $_SESSION['idTipoUsuario'];
        if (isset($this->permissions[$idTipoUsuario]) && in_array($page, $this->permissions[$idTipoUsuario])) {
            $this->logSessionInConsole();
            return true;
            
        }
        $this->logSessionInConsole();
        return false;
    }
    
    public function denyAccess() {
        echo "<script>alert('Acceso denegado'); window.location.href = '../../pages/mainPage/mainPage.php';  </script>";
        exit;
    }

    private function logSessionInConsole() {
        // Imprimir el contenido de la sesión en la consola de JavaScript
        echo "<script>console.log('Contenido de la sesión: ', " . json_encode($_SESSION) . ");</script>";
    }
    
}
?>
