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

    // Obtener todas las inserciones de cierrecaja
    public function obtenerCierresCaja() {
        $query = "
            SELECT 
                idcierreCaja,
                codigo_rendicion,
                fecha_cierre,
                total_general
            FROM 
                cierrecaja
            ORDER BY 
                fecha_cierre DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener detalle de un cierre específico
    public function obtenerDetalleCierre($idCierreCaja) {
        $query = "
            SELECT 
                codigo_rendicion,
                fecha_cierre,
                efectivo,
                mercado_pago,
                payway,
                onda,
                cambios,
                cuenta_corriente,
                gastos,
                billetes_20000,
                billetes_10000,
                billetes_2000,
                billetes_1000,
                billetes_500,
                billetes_200,
                billetes_100,
                billetes_50,
                billetes_20,
                billetes_10,
                total_general,
                total_menos_gastos
            FROM 
                cierrecaja
            WHERE 
                idcierreCaja = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idCierreCaja]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Manejo de peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $controller = new DetalleRendicionesController();

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'obtenerCierresCaja':
                $cierres = $controller->obtenerCierresCaja();
                echo json_encode($cierres);
                break;

            case 'verDetalleCierre':
                $idCierreCaja = $_POST['idCierreCaja'];
                $detalle = $controller->obtenerDetalleCierre($idCierreCaja);
                echo json_encode($detalle);
                break;
        }
    }
    exit;
}
