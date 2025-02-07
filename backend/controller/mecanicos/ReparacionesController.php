<?php
header('Content-Type: application/json');
require_once '../../../database/Database.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class ArregloController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Obtener la lista de mecánicos
    public function getMecanicos() {
        $stmt = $this->db->prepare("SELECT id, nombre FROM mecanicos ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener la lista de camiones
    public function getCamiones() {
        $stmt = $this->db->prepare("SELECT id, nombre FROM camiones");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener detalles de un mecánico específico
    public function getMecanicoById($id) {
        $stmt = $this->db->prepare("SELECT id, nombre FROM mecanicos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener detalles de un camión específico
    public function getCamionById($id) {
        $stmt = $this->db->prepare("SELECT id, nombre FROM camiones WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardarArreglo($mecanico_id, $camion_id, $arreglos, $total) {
        try {
            $fecha = date('Y-m-d H:i:s');
            $total = number_format((float)$total, 2, '.', '');
    
            // Insertar el arreglo en la tabla y obtener el ID
            $stmt = $this->db->prepare("INSERT INTO arreglos (mecanico_id, camion_id, total, fecha) VALUES (?, ?, ?, ?)");
            $stmt->execute([$mecanico_id, $camion_id, $total, $fecha]);
            $arreglo_id = $this->db->lastInsertId();
    
            if (!$arreglo_id) {
                throw new Exception("No se pudo obtener el ID del arreglo.");
            }
    
            // Insertar los detalles del arreglo
            $stmtDetalle = $this->db->prepare("INSERT INTO detalle_arreglos (arreglo_id, descripcion) VALUES (?, ?)");
            foreach ($arreglos as $arreglo) {
                if (!isset($arreglo['descripcion']) || empty($arreglo['descripcion'])) {
                    throw new Exception("Uno de los arreglos no tiene descripción.");
                }
                $stmtDetalle->execute([$arreglo_id, $arreglo['descripcion']]);
            }
    
            return [
                'status' => 'success',
                'message' => 'Arreglo guardado correctamente.',
                'arreglo_id' => $arreglo_id // 👈 ID generado
            ];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    
}

// Manejo de peticiones
$controller = new ArregloController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['tipo'])) {
        if ($_GET['tipo'] === 'mecanicos') {
            echo json_encode($controller->getMecanicos());
        } elseif ($_GET['tipo'] === 'camiones') {
            echo json_encode($controller->getCamiones());
        }
    } elseif (isset($_GET['mecanico_id'])) {
        echo json_encode($controller->getMecanicoById($_GET['mecanico_id']));
    } elseif (isset($_GET['camion_id'])) {
        echo json_encode($controller->getCamionById($_GET['camion_id']));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo json_encode($controller->guardarArreglo($data['mecanico_id'], $data['camion_id'], $data['arreglos'], $data['total']));
}
