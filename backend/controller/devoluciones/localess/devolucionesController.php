<?php
// backend/controllers/devoluciones/locales/devolucionesController.php

require_once '../../../../database/Database.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class DevolucionesController {
   private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Otros métodos del controlador


    public function buscarArticulo($codigoBarras)
    {
        $query = "SELECT * FROM stock WHERE codBarras = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$codigoBarras]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            error_log("Artículo con código de barras $codigoBarras no encontrado.");
        }

        return $result;
    }


    public function registrarDevoluciones($articulos)
    {
        $query = "INSERT INTO devoluciones (codBarras, partida, cantidad, fecha, hora) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        foreach ($articulos as $articulo) {
            $stmt->execute([
                $articulo['codBarras'],
                $articulo['partida'],
                $articulo['cantidad'],
                date('Y-m-d'),
                date('H:i:s'),
            ]);
        }
        return true;
    }
}

// Manejo de las peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $controller = new DevolucionesController();
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'buscarArticulo':
                $codigoBarras = $_POST['codBarras'];
                $articulo = $controller->buscarArticulo($codigoBarras);
                echo json_encode($articulo);
                break;

            case 'registrarDevoluciones':
                $articulos = $_POST['articulos'];
                $success = $controller->registrarDevoluciones($articulos);
                echo json_encode(['success' => $success]);
                break;
        }
    }
    exit;
}
?>

<!-- Updated HTML -->

