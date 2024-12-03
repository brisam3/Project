<?php
// backend/controllers/locales/TransferenciasController.php

session_start();
require_once '../../../database/Database.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class TransferenciasController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function buscarArticulo($codigoBarras) {
        $query = "SELECT * FROM articulos WHERE codBarras = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$codigoBarras]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPorDescripcion($descripcion) {
        $keywords = explode(' ', $descripcion);
        $conditions = array_map(function($keyword) {
            return "descripcion LIKE ?";
        }, $keywords);
        $query = "SELECT codBarras, descripcion, codBejerman 
                  FROM articulos 
                  WHERE " . implode(' AND ', $conditions);
        $params = array_map(function($keyword) {
            return "%$keyword%";
        }, $keywords);

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarTransferencias($articulos, $idUsuarioDestinatario) {
        if (!isset($_SESSION['idUsuario'])) {
            echo json_encode([
                'error' => "No se encontró el usuario remitente en la sesión.",
                'session' => $_SESSION
            ]);
            exit;
        }

        $idUsuarioRemitente = $_SESSION['idUsuario'];

        $detalleQuery = "INSERT INTO detalle_solicitud_transferencia (fecha, idUsuarioRemitente, idUsuarioDestinatario, estado) 
                         VALUES (NOW(), ?, ?, 'pendiente')";
        $detalleStmt = $this->db->prepare($detalleQuery);
        $detalleStmt->execute([$idUsuarioRemitente, $idUsuarioDestinatario]);
        $idDetalleSolicitud = $this->db->lastInsertId();

        $query = "INSERT INTO solicitudes_transferencia (codBejerman, cantidad, descripcion, idDetalleSolicitud, codBarras) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        foreach ($articulos as $articulo) {
            $stmt->execute([
                $articulo['codBejerman'],
                $articulo['cantidad'],
                $articulo['descripcion'],
                $idDetalleSolicitud,
                $articulo['codBarras']
            ]);
        }

        return true;
    }
}

// Manejo de las peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $controller = new TransferenciasController();

    if (isset($_POST['action'])) {
        try {
            switch ($_POST['action']) {
                case 'buscarArticulo':
                    $codigoBarras = $_POST['codBarras'];
                    $articulo = $controller->buscarArticulo($codigoBarras);
                    echo json_encode($articulo);
                    break;

                case 'buscarPorDescripcion':
                    $descripcion = $_POST['descripcion'];
                    $resultados = $controller->buscarPorDescripcion($descripcion);
                    echo json_encode($resultados);
                    break;

                case 'registrarTransferencias':
                    $articulos = $_POST['articulos'];
                    $idUsuarioDestinatario = $_POST['idUsuarioDestinatario'];
                    $success = $controller->registrarTransferencias($articulos, $idUsuarioDestinatario);
                    echo json_encode(['success' => $success]);
                    break;

                default:
                    echo json_encode(['error' => 'Acción no válida']);
                    break;
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    exit;
}
?>
