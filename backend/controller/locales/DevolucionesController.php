<?php
// backend/controllers/devoluciones/locales/devolucionesController.php

// Depuración: imprimir el contenido de la sesión en los logs del servidor
session_start();
if (!isset($_SESSION['idUsuario'])) {
    // Redirigir al usuario de vuelta a la página de inicio de sesión si no está autenticado
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

    public function buscarArticulo($codigoBarras)
    {
        $query = "SELECT * FROM articulos WHERE codBarras = ?";
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
    if (!isset($_SESSION['idUsuario']) || !isset($_SESSION['idTipoUsuario'])) {
        echo json_encode([
            'error' => "No se encontró el tipo de usuario o el idUsuario en la sesión.",
            'session' => $_SESSION
        ]);
        exit;
    }

    $idUsuario = $_SESSION['idUsuario'];
    $idTipoUsuario = $_SESSION['idTipoUsuario'];

    // Insertar un nuevo registro en la tabla `detalleDevoluciones` con la fecha y hora actual
    $detalleQuery = "INSERT INTO detalleDevoluciones (fechaHora) VALUES (NOW())";
    $detalleStmt = $this->db->prepare($detalleQuery);
    $detalleStmt->execute();
    $idDetalleDevolucion = $this->db->lastInsertId();

    // Consulta para insertar en la tabla `devoluciones` usando `codBejerman`
    $query = "INSERT INTO devoluciones (codBejerman, partida, cantidad, idTipoDevolucion, idUsuario, descripcion, idDetalleDevolucion, codBarras) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

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
                $articulo['partida'],
                $articulo['cantidad'],
                $idTipoUsuario,
                $idUsuario,
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
