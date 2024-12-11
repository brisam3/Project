<?php
// Configuración inicial
session_start();
require_once('../../../database/Database.php');
date_default_timezone_set('America/Argentina/Buenos_Aires'); // Zona horaria

class DetalleRendicionController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerRendicionesConVentas() {
        $fechaHoy = date('Y-m-d'); // Fecha actual
        $yesterday = date('Y-m-d', strtotime('-1 day')); // Fecha de ayer
    
        // Mapeo del orden de los preventistas
        $ordenPreventistas = [
            'Mica' => 0,
            'Gustavo' => 1,
            'Leo(Chillo)' => 2,
            'Alexander' => 3,
            'Diego' => 4,
            'Cristian' => 5,
            'Marianela' => 6,
            'Guille' => 7,
            'Soledad' => 8
        ];
    
        // Crear la cláusula ORDER BY con el orden de los preventistas
        $ordenString = '';
        foreach ($ordenPreventistas as $nombre => $indice) {
            if ($ordenString) {
                $ordenString .= ', ';
            }
            $ordenString .= "CASE WHEN up.nombre = '$nombre' THEN $indice ELSE 9999 END";
        }
    
        // Consulta para las rendiciones
        $queryRendiciones = "
            SELECT 
                rc.id,
                rc.idUsuarioChofer,
                uc.nombre AS nombre_chofer,
                rc.idUsuarioPreventista,
                up.nombre AS nombre_preventista,
                up.apellido AS movil, -- Agregar el campo movil desde apellido
                rc.fecha,
                rc.total_efectivo,
                rc.total_transferencia,
                rc.total_mercadopago,
                rc.total_cheques,
                rc.total_fiados,
                rc.total_gastos,
                rc.pago_secretario,
                rc.total_general,
                rc.total_menos_gastos,
                rc.billetes_20000,
                rc.billetes_10000,
                rc.billetes_2000,
                rc.billetes_1000,
                rc.billetes_500,
                rc.billetes_200,
                rc.billetes_100,
                rc.billetes_50,
                rc.billetes_20,
                rc.billetes_10,
                rc.total_mec_faltante,
                rc.total_rechazos
            FROM 
                rendicion_choferes rc
            LEFT JOIN 
                usuarios uc ON rc.idUsuarioChofer = uc.idUsuario
            LEFT JOIN 
                usuarios up ON rc.idUsuarioPreventista = up.idUsuario
            WHERE 
                rc.fecha = ?
            ORDER BY
                $ordenString
        ";
    
        $stmtRendiciones = $this->db->prepare($queryRendiciones);
        $stmtRendiciones->execute([$fechaHoy]);
        $rendiciones = $stmtRendiciones->fetchAll(PDO::FETCH_ASSOC);
    
        // Consulta para los totales de ventas
        $queryVentas = "
            SELECT
                u.nombre AS nombre_preventista,
                SUM(c.Item_Impte_Total_mon_Emision) AS total_ventas
            FROM 
                comprobantes c
            JOIN 
                detallereporte d ON c.detalleReporte_id = d.id
            JOIN 
                usuarios u ON TRIM(c.Comp_Vendedor_Cod) = TRIM(u.usuario)
            WHERE 
                d.fecha = :yesterday
            GROUP BY 
                u.nombre
        ";
        $stmtVentas = $this->db->prepare($queryVentas);
        $stmtVentas->execute([':yesterday' => $yesterday]);
        $ventas = $stmtVentas->fetchAll(PDO::FETCH_ASSOC);
    
        // Combinar los totales de ventas con las rendiciones
        foreach ($rendiciones as &$rendicion) {
            $rendicion['total_ventas'] = 0; // Valor por defecto
            foreach ($ventas as $venta) {
                if ($venta['nombre_preventista'] === $rendicion['nombre_preventista']) {
                    $rendicion['total_ventas'] = $venta['total_ventas'];
                    break;
                }
            }
        }
    
        return $rendiciones;
    }
    
    
}

// Manejo de las peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $database = new Database();
    $dbConnection = $database->getConnection();
    $controller = new DetalleRendicionController($dbConnection);

    if (isset($_POST['action'])) {
        $action = filter_var($_POST['action'], FILTER_SANITIZE_STRING);

        try {
            switch ($action) {
                case 'obtenerRendicionesConUsuarios':
                    $detalles = $controller->obtenerRendicionesConVentas(); // Usa la nueva función
                    echo json_encode(['error' => false, 'data' => $detalles]);
                    break;
                
                default:
                    throw new Exception("Acción no válida.");
            }
        } catch (Exception $e) {
            echo json_encode(['error' => true, 'mensaje' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => true, 'mensaje' => 'Acción no especificada.']);
    }

    exit;
}
?>
