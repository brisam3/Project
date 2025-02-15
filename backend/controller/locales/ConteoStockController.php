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
    
        // Añade una condición para excluir 'oferta'
        $conditions[] = "descripcion NOT LIKE '%oferta%'";
    
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
    
    

    public function registrarConteo($articulos) {
        if (!isset($_SESSION['idUsuario']) || !isset($_SESSION['idTipoUsuario'])) {
            echo json_encode([
                'error' => "No se encontró el tipo de usuario o el idUsuario en la sesión.",
                'session' => $_SESSION
            ]);
            exit;
        }
    
        $idUsuario = $_SESSION['idUsuario'];
    
        // Insertar un nuevo registro en la tabla `detalleConteoStock`
        $detalleQuery = "INSERT INTO detalleconteostock (fechaHora, idUsuario) VALUES (NOW(), ?)";
        $detalleStmt = $this->db->prepare($detalleQuery);
        $detalleStmt->execute([$idUsuario]);
        $idDetalleConteo = $this->db->lastInsertId();
    
        // Consulta para insertar en la tabla `conteo_stock`
        $query = "INSERT INTO conteo_stock (codBejerman, partida, cantidad, descripcion, idDetalleConteo, codBarras) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
    
        foreach ($articulos as $articulo) {
            $codigoBarras = $articulo['codBarras'];
    
            // **Verificar si `codBejerman` ya está presente en `$articulo` y solo buscar si no existe**
            if (!isset($articulo['codBejerman']) || empty($articulo['codBejerman'])) {
                $articuloData = $this->buscarArticulo($codigoBarras);
                $codBejerman = $articuloData['codBejerman'] ?? null;
            } else {
                $codBejerman = $articulo['codBejerman'];
            }
    
            if ($codBejerman) {
                $stmt->execute([
                    $codBejerman,
                    $articulo['partida'],
                    $articulo['cantidad'],
                    $articulo['descripcion'],
                    $idDetalleConteo,
                    $codigoBarras
                ]);
            } else {
                error_log("No se encontró el `codBejerman` para el artículo con código de barras $codigoBarras.");
            }
        }
    
        return true;
    }
    
    public function buscarDetalleDevoluciones() {
        // Establecer la zona horaria de Buenos Aires, Argentina
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        
        // Obtener la fecha actual
        $fechaHoy = date('Y-m-d');  // La fecha en formato Y-m-d (ejemplo: 2024-12-05)
    
        // Obtener el idUsuario de la sesión
        $idUsuario = $_SESSION['idUsuario'];  // Asumiendo que el idUsuario está guardado en la sesión
    
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
                dd.idUsuario = ? 
                AND DATE(dd.fechaHora) = ?
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idUsuario, $fechaHoy]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

                case 'registrarConteo':
                    $articulos = $_POST['articulos'];
                    $success = $controller->registrarConteo($articulos);
                    echo json_encode(['success' => $success]);
                    break;
                case 'buscarDetalleDevoluciones':
                        // Llamar a la función que busca las devoluciones del usuario
                        $resultados = $controller->buscarDetalleDevoluciones();
                  
                        // Si quieres devolver los datos de forma estructurada, puedes hacer esto:
                            foreach ($resultados as $detalle) {
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