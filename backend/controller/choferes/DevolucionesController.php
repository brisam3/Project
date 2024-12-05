<?php
// backend/controllers/devoluciones/locales/detalleDevolucionesController.php

session_start();
if (!isset($_SESSION['idUsuario'])) {
    // Redirigir al usuario de vuelta a la página de inicio de sesión si no está autenticado
    header("Location: https://softwareparanegociosformosa.com/wol/pages/login/login.html");
    exit();
}

require_once('../../../database/Database.php');
date_default_timezone_set('America/Argentina/Buenos_Aires');

class DetalleDevolucionesController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Obtener los detalles de devoluciones por fecha
 // Obtener los detalles de devoluciones de la fecha actual
public function buscarDetalleDevolucionesHoy() {
    // Obtener la fecha actual de Buenos Aires
    $fechaHoy = date('Y-m-d');
    
    // Consulta que une detalleDevoluciones con usuarios para obtener el nombre del usuario
    $query = "
        SELECT 
            dd.idDetalleDevolucion,
            u.nombre AS nombreUsuario,
            dd.fechaHora
        FROM 
            detalleDevoluciones dd
        JOIN 
            usuarios u ON dd.idUsuario = u.idUsuario
        WHERE 
            DATE(dd.fechaHora) = ?
    ";
    $stmt = $this->db->prepare($query);
    $stmt->execute([$fechaHoy]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // Obtener los artículos de un detalle de devolución específico
    public function verDetalleDevolucion($idDetalleDevolucion) {
        $query = "
            SELECT 
                codBejerman, 
                partida, 
                cantidad, 
                descripcion 
            FROM 
                devoluciones 
            WHERE 
                idDetalleDevolucion = ?
        ";
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
            case 'buscarDetalleDevolucionesHoy':
                $detalles = $controller->buscarDetalleDevolucionesHoy();

                foreach ($detalles as $detalle) {
                    echo json_encode([
                        'id' => $detalle['idDetalleDevolucion'],
                        'usuario' => $detalle['nombreUsuario'],
                        'fecha' => $detalle['fechaHora']
                    ]) . "\n";
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


?>