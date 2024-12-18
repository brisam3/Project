<?php
// backend/controllers/rendiciones/detalleRendicionesController.php

session_start();
if (!isset($_SESSION['idUsuario'])) {
    header("Location: https://softwareparanegociosformosa.com/wol/pages/login/login.html");
    exit();
}

require_once('../../../database/Database.php');

class DetalleRendicionesController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Obtener rendiciones del chofer actual
    public function obtenerRendicionesPorChofer($idUsuarioChofer) {
        $query = "
            SELECT 
                r.id,
                r.codigo_rendicion, 
                r.fecha,
                r.total_general, 
                r.contrareembolso, 
                (r.total_general - r.contrareembolso) AS diferencia,
                CONCAT(u.nombre, ' ', u.apellido) AS nombre_preventista
            FROM 
                rendicion_choferes r
            JOIN 
                usuarios u ON r.idUsuarioPreventista = u.idUsuario
            WHERE 
                r.idUsuarioChofer = ?
            ORDER BY 
                r.fecha DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idUsuarioChofer]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Obtener detalle de una rendición específica
   // Obtener detalle de una rendición específica
public function obtenerDetalleRendicion($idRendicion) {
    $query = "
        SELECT 
            r.codigo_rendicion,
            r.fecha,
            r.total_efectivo,
            r.total_transferencia,
            r.total_general,
            r.total_mercadopago,
            r.total_cheques,
            r.total_fiados,
            r.total_gastos,
            r.pago_secretario,
            r.total_menos_gastos,
            r.billetes_20000,
            r.billetes_10000,
            r.billetes_2000,
            r.billetes_1000,
            r.billetes_500,
            r.billetes_200,
            r.billetes_100,
            r.billetes_50,
            r.billetes_20,
            r.billetes_10,
            r.total_mec_faltante,
            r.total_rechazos,
            r.contrareembolso,
            (r.total_general - r.contrareembolso) AS diferencia
        FROM 
            rendicion_choferes r
        WHERE 
            r.id = ?
    ";
    $stmt = $this->db->prepare($query);
    $stmt->execute([$idRendicion]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    
}

// Manejo de peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $controller = new DetalleRendicionesController();

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'obtenerRendiciones':
                $idUsuarioChofer = $_SESSION['idUsuario'];
                $rendiciones = $controller->obtenerRendicionesPorChofer($idUsuarioChofer);
                echo json_encode($rendiciones);
                break;

            case 'verDetalleRendicion':
                $idRendicion = $_POST['idRendicion'];
                $detalle = $controller->obtenerDetalleRendicion($idRendicion);
                echo json_encode($detalle);
                break;
        }
    }
    exit;
}
