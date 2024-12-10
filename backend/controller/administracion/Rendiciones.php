<?php
// backend/controllers/devoluciones/locales/detalleDevolucionesController.php
// backend/controllers/devoluciones/locales/detalleDevolucionesController.php

session_start();


require_once('../../../database/Database.php');
date_default_timezone_set('America/Argentina/Buenos_Aires');

class DetalleRendicionController {
    private  $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getDb() {
        return $this->db; // Retorna la conexión
    }
    

    public function obtenerRendicionesConUsuarios() {
        // Establecer la zona horaria de Buenos Aires
        date_default_timezone_set('America/Argentina/Buenos_Aires');
         // Obtener la fecha actual
        $fechaHoy = date('Y-m-d');  // La fecha en formato Y-m-d (ejemplo: 2024-12-05)
    
        // Query SQL
        $query = "
            SELECT 
                rc.id,
                rc.idUsuarioChofer,
                uc.nombre AS nombre_chofer,
                rc.idUsuarioPreventista,
                up.nombre AS nombre_preventista,
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
                rc.fecha = ?  -- Filtra por la fecha si se necesita
        ";
    
       
        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($query);
        $stmt->execute([$fechaHoy]);  // Puedes agregar más parámetros si es necesario
    
        // Retornar los resultados
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}

// Manejo de las peticiones AJAX
// Manejo de las peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json'); // Asegurarse de que el tipo de contenido sea JSON
    $controller = new DetalleRendicionController();

    if (isset($_POST['action'])) {
        try {
            switch ($_POST['action']) {
                case 'obtenerRendicionesConUsuarios':
                    $detalles = $controller->obtenerRendicionesConUsuarios();
                    $resultados = []; // Arreglo para almacenar los resultados
                    foreach ($detalles as $detalle) {
                        $resultados[] = [
                            'id' => $detalle['id'],
                            'idUsuarioChofer' => $detalle['idUsuarioChofer'],
                            'nombre_chofer' => $detalle['nombre_chofer'],
                            'idUsuarioPreventista' => $detalle['idUsuarioPreventista'],
                            'nombre_preventista' => $detalle['nombre_preventista'],
                            'fecha' => $detalle['fecha'],
                            'total_efectivo' => $detalle['total_efectivo'],
                            'total_transferencia' => $detalle['total_transferencia'],
                            'total_mercadopago' => $detalle['total_mercadopago'],
                            'total_cheques' => $detalle['total_cheques'],
                            'total_fiados' => $detalle['total_fiados'],
                            'total_gastos' => $detalle['total_gastos'],
                            'pago_secretario' => $detalle['pago_secretario'],
                            'total_general' => $detalle['total_general'],
                            'total_menos_gastos' => $detalle['total_menos_gastos'],
                            'billetes_20000' => $detalle['billetes_20000'],
                            'billetes_10000' => $detalle['billetes_10000'],
                            'billetes_2000' => $detalle['billetes_2000'],
                            'billetes_1000' => $detalle['billetes_1000'],
                            'billetes_500' => $detalle['billetes_500'],
                            'billetes_200' => $detalle['billetes_200'],
                            'billetes_100' => $detalle['billetes_100'],
                            'billetes_50' => $detalle['billetes_50'],
                            'billetes_20' => $detalle['billetes_20'],
                            'billetes_10' => $detalle['billetes_10'],
                            'total_mec_faltante' => $detalle['total_mec_faltante'],
                            'total_rechazos' => $detalle['total_rechazos']
                        ];
                    }

                    echo json_encode($resultados); // Enviar los resultados como JSON
                    break;
                
                default:
                    throw new Exception("Acción no válida.");
            }
        } catch (Exception $e) {
            // Captura cualquier excepción y responde con el error
            echo json_encode([
                'error' => true,
                'mensaje' => $e->getMessage()
            ]);
        }
    }
    exit;
}



?>