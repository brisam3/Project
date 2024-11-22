<?php
// backend/controllers/devoluciones/locales/detalleDevolucionesController.php

session_start();
if (!isset($_SESSION['idUsuario'])) {
  // Redirigir al usuario de vuelta a la página de inicio de sesión si no está autenticado
  header("Location: https://softwareparanegociosformosa.com/wol/pages/login/login.html");
  exit();
}
require_once '../../../database/Database.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class DetalleDevolucionesController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Obtener los detalles de devoluciones por fecha
    public function buscarDetalleDevoluciones($fecha) {
        $query = "SELECT * FROM detalleDevoluciones WHERE DATE(fechaHora) = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$fecha]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener los artículos de un detalle de devolución específico
   // Obtener los artículos de un detalle de devolución específico
public function verDetalleDevolucion($idDetalleDevolucion) {
    // Cambiar 'codBarras' por 'codBejerman' en la consulta SQL
    $query = "SELECT codBejerman, partida, cantidad, descripcion FROM devoluciones WHERE idDetalleDevolucion = ?";
    $stmt = $this->db->prepare($query);
    $stmt->execute([$idDetalleDevolucion]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




}

// Manejo de las peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: text/plain');
    $controller = new DetalleDevolucionesController();
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'buscarDetalleDevoluciones':
                $fecha = $_POST['fecha'];
                $detalles = $controller->buscarDetalleDevoluciones($fecha);
                
                foreach ($detalles as $detalle) {
                    echo "ID: " . $detalle['idDetalleDevolucion'] . " - Fecha: " . $detalle['fechaHora'] . "\n";
                }
                break;

            case 'verDetalleDevolucion':
                $idDetalleDevolucion = $_POST['idDetalleDevolucion'];
                $articulos = $controller->verDetalleDevolucion($idDetalleDevolucion);

                foreach ($articulos as $articulo) {
                    echo $articulo['codBejerman'] . " | " . $articulo['partida'] . " | " . $articulo['cantidad'] . " | " . $articulo['descripcion'] . "\n";
                }
                break;
        }
    }
    exit;
}
