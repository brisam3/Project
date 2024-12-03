<?php
// backend/controllers/devoluciones/locales/detalleDevolucionesController.php
// backend/controllers/devoluciones/locales/detalleDevolucionesController.php

session_start();
if (!isset($_SESSION['idUsuario'])) {
    header("Location: https://softwareparanegociosformosa.com/wol/pages/login/login.html");
    exit();
}

require_once('../../../database/Database.php');
date_default_timezone_set('America/Argentina/Buenos_Aires');

class DetalleTransferenciasController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Obtener los detalles de transferencias por fecha
    public function buscarDetalleTransferencia($fecha) {
        $query = "
            SELECT 
                dd.idDetalleSolicitud,
                remitente.nombre AS nombreRemitente,
                destinatario.nombre AS nombreDestinatario,
                dd.fecha
            FROM 
                detalle_solicitud_transferencia dd
            JOIN 
                usuarios remitente ON dd.idUsuarioRemitente = remitente.idUsuario
            JOIN 
                usuarios destinatario ON dd.idUsuarioDestinatario = destinatario.idUsuario
            WHERE 
                DATE(dd.fecha) = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$fecha]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener los artículos de un detalle de transferencia específico
    public function verDetalleSolicitud($idDetalleSolicitud) {
        $query = "
            SELECT 
                st.codBejerman, 
                st.partida, 
                st.cantidad, 
                st.descripcion 
            FROM 
                solicitudes_transferencia st
            JOIN 
                detalle_solicitud_transferencia dst 
            ON 
                st.idDetalleSolicitud = dst.idDetalleSolicitud
            WHERE 
                dst.idDetalleSolicitud = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idDetalleSolicitud]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}

// Manejo de las peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: text/plain');
    $controller = new DetalleTransferenciasController();

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'buscarDetalleTransferencia':
                $fecha = $_POST['fecha'];
                $detalles = $controller->buscarDetalleTransferencia($fecha);

                foreach ($detalles as $detalle) {
                    echo json_encode([
                        'id' => $detalle['idDetalleSolicitud'],
                        'usuarioRemitente' => $detalle['nombreRemitente'],
                        'usuarioDestinatario' => $detalle['nombreDestinatario'],
                        'fecha' => $detalle['fecha']
                    ]) . "\n";
                }
                break;

            case 'verDetalleSolicitud':
                $idDetalleSolicitud = $_POST['idDetalleSolicitud'];
                $articulos = $controller->verDetalleSolicitud($idDetalleSolicitud);

                $articulosResponse = [];
                foreach ($articulos as $articulo) {
                    $articulosResponse[] = [
                        'codBejerman' => $articulo['codBejerman'],
                        'partida' => $articulo['partida'],
                        'cantidad' => $articulo['cantidad'],
                        'descripcion' => $articulo['descripcion']
                    ];
                }

                echo json_encode($articulosResponse);
                break;
        }
    }
    exit;
}

?>
