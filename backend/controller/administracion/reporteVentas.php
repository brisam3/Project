<?php
// backend/controllers/resumen/ResumenController.php

session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['idUsuario'])) {
    // Redirige al usuario al login si no está autenticado
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit();
}

require_once '../../../database/Database.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class ResumenController {
    private $db;

    public function __construct() {
        // Establece la conexión con la base de datos
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Método para obtener los datos de la tabla 'cierrecaja'
    public function obtenerResumenDiario() {
        $query = "SELECT * FROM cierrecaja";  // Consulta la tabla 'cierrecaja'
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Obtiene todos los registros

        // Si no se encuentran registros, retornar un mensaje de error en JSON
        if (!$result) {
            return ['error' => 'No se encontraron registros en la tabla "cierrecaja".'];
        }

        return $result;  // Retorna los resultados obtenidos
    }

    // Método para obtener los datos de la tabla 'rendicion_choferes'
    public function obtenerRendicionChoferes() {
        $query = "SELECT * FROM rendicion_choferes";  // Consulta la tabla 'rendicion_choferes'
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Obtiene todos los registros

        // Si no se encuentran registros, retornar un mensaje de error en JSON
        if (!$result) {
            return ['error' => 'No se encontraron registros en la tabla "rendicion_choferes".'];
        }

        return $result;  // Retorna los resultados obtenidos
    }
}

// Manejo de las peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');  // Asegúrate de que la respuesta sea en formato JSON
    $controller = new ResumenController();

    if (isset($_POST['action'])) {
        try {
            switch ($_POST['action']) {
                case 'obtenerResumen':
                    // Llama al método para obtener el resumen de cierrecaja
                    $resumen = $controller->obtenerResumenDiario();
                    echo json_encode($resumen);
                    break;

                case 'obtenerRendicionChoferes':
                    // Llama al método para obtener la rendición de choferes
                    $rendicionChoferes = $controller->obtenerRendicionChoferes();
                    echo json_encode($rendicionChoferes);
                    break;

                default:
                    // Si la acción no es válida, devolver un error
                    echo json_encode(['error' => 'Acción no válida']);
                    break;
            }
        } catch (Exception $e) {
            // Manejo de excepciones y errores en la base de datos
            echo json_encode(['error' => 'Error en la ejecución: ' . $e->getMessage()]);
        }
    } else {
        // Si no se pasa una acción, devolver un error
        echo json_encode(['error' => 'No se especificó una acción']);
    }
    exit;
}
?>
