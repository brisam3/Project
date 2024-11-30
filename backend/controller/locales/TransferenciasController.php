<?php
// backend/controllers/devoluciones/locales/devolucionesController.php

// Depuración: imprimir el contenido de la sesión en los logs del servidor
session_start();
if (!isset($_SESSION['idUsuario'])) {
    header("Location: https://softwareparanegociosformosa.com/wol/pages/login/login.html");
    exit();
}

require_once '../../../database/Database.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class DevolucionesController {
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

    public function buscarPorDescripcion($descripcion)
    {
        // Divide la búsqueda en palabras clave
        $keywords = explode(' ', $descripcion);
    
        // Crea condiciones dinámicas para cada palabra clave
        $conditions = array_map(function($keyword) {
            return "descripcion LIKE ?";
        }, $keywords);
    
        // Une las condiciones con AND
        $query = "SELECT codBarras, descripcion, codBejerman 
                  FROM articulos 
                  WHERE " . implode(' AND ', $conditions);
    
        // Crea un array con las palabras clave rodeadas por '%'
        $params = array_map(function($keyword) {
            return "%$keyword%";
        }, $keywords);
    
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function registrarDevoluciones($articulos) {
        if (!isset($_SESSION['idUsuario']) || !isset($_SESSION['idTipoUsuario'])) {
            echo json_encode([
                'error' => "No se encontró el tipo de usuario o el idUsuario en la sesión.",
                'session' => $_SESSION
            ]);
            exit;
        }

        $idUsuario = $_SESSION['idUsuario'];
        $idTipoUsuario = $_SESSION['idTipoUsuario'];
        $idTipoDevolucion = $idTipoUsuario;

        // Insertar un nuevo registro en la tabla `detalleDevoluciones` con la fecha y hora actual
        $detalleQuery = "INSERT INTO detalleDevoluciones (fecha, idTipoDevolucion, idUsuario) VALUES (NOW(), ?, ?)";
        $detalleStmt = $this->db->prepare($detalleQuery);
        $detalleStmt->execute([$idTipoDevolucion, $idUsuario]);
        $idDetalleDevolucion = $this->db->lastInsertId();

        // Consulta para insertar en la tabla `devoluciones` usando `codBejerman`
        $query = "INSERT INTO devoluciones (codBejerman, cantidad, descripcion, idDetalleDevolucion, codBarras) 
                  VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        foreach ($articulos as $articulo) {
            // Obtener el `codBejerman` del artículo basado en `codBarras`
            $codigoBarras = $articulo['codBarras'];
            $articuloData = $this->buscarArticulo($codigoBarras);

            if ($articuloData && isset($articuloData['codBejerman'])) {
                $codBejerman = $articuloData['codBejerman'];

                // Insertar en la tabla `devoluciones`
                $stmt->execute([
                    $codBejerman,
                    $articulo['cantidad'],
                    $articulo['descripcion'],
                    $idDetalleDevolucion,
                    $codigoBarras
                ]);
            } else {
                // Log de error si no se encuentra el `codBejerman`
                error_log("No se encontró el `codBejerman` para el artículo con código de barras $codigoBarras.");
            }
        }

        return true;
    }
}

// Manejo de las peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $controller = new DevolucionesController();

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

                case 'registrarDevoluciones':
                    $articulos = $_POST['articulos'];
                    $success = $controller->registrarDevoluciones($articulos);
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
