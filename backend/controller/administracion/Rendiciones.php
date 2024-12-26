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

                // Determinar si hoy es lunes para usar el sábado como referencia
                if (date('N') == 1) {
                    $yesterday = date('Y-m-d', strtotime('-2 days')); // Usar sábado
                } else {
                    $yesterday = date('Y-m-d', strtotime('-1 day')); // Usar día anterior
                }

    
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

    // En el controlador (DetalleRendicionController.php)
    public function obtenerCierreCajaHoy() {
        $fechaHoy = date('Y-m-d');  // Fecha de hoy en formato YYYY-MM-DD

      
         $ordenLocales = [ 
            'Obrero' => 0, 
            'Liborsi' => 1,
            'Vial' => 2, 
            'Central' => 3,
            'Eva Peron' => 4,
            'San Pedro' => 5,
            
        ];

        $ordenString = '';
        foreach ($ordenLocales as $nombre => $indice) {
            if ($ordenString) {
                $ordenString .= ', ';
            }
            $ordenString .= "CASE WHEN u.nombre = '$nombre' THEN $indice ELSE 9999 END";
        }


        $queryCierreCaja = "
            SELECT 
                cc.idcierreCaja,
                cc.idUsuario,
                u.nombre AS nombre_local,  -- Traemos el nombre del local
                cc.fecha_cierre,
                cc.efectivo,
                cc.mercado_pago,
                cc.payway,
                cc.cambios,
                cc.cuenta_corriente,
                cc.gastos,
                cc.billetes_20000,
                cc.billetes_10000,
                cc.billetes_2000,
                cc.billetes_1000,
                cc.billetes_500,
                cc.billetes_200,
                cc.billetes_100,
                cc.billetes_50,
                cc.billetes_20,
                cc.billetes_10,
                cc.total_general,
                cc.total_menos_gastos
            FROM 
                cierrecaja cc
            LEFT JOIN 
                usuarios u ON cc.idUsuario = u.idUsuario  -- Hacemos el JOIN con la tabla 'usuarios' para obtener el nombre del local
            WHERE 
                cc.fecha_cierre = ? 
            ORDER BY
                $ordenString
        ";
    
        $stmt = $this->db->prepare($queryCierreCaja);
        $stmt->execute([$fechaHoy]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Retorna los registros como un array asociativo
    }
    

    public function insertarRendicion($data) {
        try {
            // Iniciar la transacción
            $this->db->beginTransaction();

            // Insertar en la tabla rendicion_general
            $stmt_general = $this->db->prepare("
                INSERT INTO rendicion_general (
                    total_efectivo,
                    total_general_preventa,
                    total_mp,
                    total_transferencias,
                    billetes_20000,
                    billetes_10000,
                    billetes_5000,
                    billetes_2000,
                    billetes_1000,
                    billetes_500,
                    billetes_200,
                    billetes_100,
                    billetes_50,
                    billetes_20,
                    billetes_10
                ) VALUES (
                    :total_efectivo,
                    :total_general_preventa,
                    :total_mp,
                    :total_transferencias,
                    :billetes_20000,
                    :billetes_10000,
                    :billetes_5000,
                    :billetes_2000,
                    :billetes_1000,
                    :billetes_500,
                    :billetes_200,
                    :billetes_100,
                    :billetes_50,
                    :billetes_20,
                    :billetes_10
                )
            ");

            $totales = $data['totales'];

            $stmt_general->execute([
                ':total_efectivo' => $totales['total_efectivo'] ?? 0.00,
                ':total_general_preventa' => $totales['total_general_preventa'] ?? 0.00,
                ':total_mp' => $totales['total_mp'] ?? 0.00,
                ':total_transferencias' => $totales['total_transferencias'] ?? 0.00,
                ':billetes_20000' => $totales['billetes_20000'] ?? 0,
                ':billetes_10000' => $totales['billetes_10000'] ?? 0,
                ':billetes_5000' => $totales['billetes_5000'] ?? 0,
                ':billetes_2000' => $totales['billetes_2000'] ?? 0,
                ':billetes_1000' => $totales['billetes_1000'] ?? 0,
                ':billetes_500' => $totales['billetes_500'] ?? 0,
                ':billetes_200' => $totales['billetes_200'] ?? 0,
                ':billetes_100' => $totales['billetes_100'] ?? 0,
                ':billetes_50' => $totales['billetes_50'] ?? 0,
                ':billetes_20' => $totales['billetes_20'] ?? 0,
                ':billetes_10' => $totales['billetes_10'] ?? 0
            ]);

            // Obtener el ID de la rendición general recién insertada
            $idRendicionGeneral = $this->db->lastInsertId();

            // Insertar en la tabla rendicion_libre si existen datos
            if (!empty($data['tabla_libre'])) {
                $stmt_libre = $this->db->prepare("
                    INSERT INTO rendicion_libre (
                        idRendicionGeneral,
                        motivo,
                        billetes_20000,
                        billetes_10000,
                        billetes_5000,
                        billetes_2000,
                        billetes_1000,
                        billetes_500,
                        billetes_200,
                        billetes_100,
                        billetes_50,
                        billetes_20,
                        billetes_10
                    ) VALUES (
                        :idRendicionGeneral,
                        :motivo,
                        :billetes_20000,
                        :billetes_10000,
                        :billetes_5000,
                        :billetes_2000,
                        :billetes_1000,
                        :billetes_500,
                        :billetes_200,
                        :billetes_100,
                        :billetes_50,
                        :billetes_20,
                        :billetes_10
                    )
                ");

                foreach ($data['tabla_libre'] as $fila) {
                    $stmt_libre->execute([
                        ':idRendicionGeneral' => $idRendicionGeneral,
                        ':motivo' => $fila['motivo'],
                        ':billetes_20000' => $fila['billetes_20000'] ?? 0,
                        ':billetes_10000' => $fila['billetes_10000'] ?? 0,
                        ':billetes_5000' => $fila['billetes_5000'] ?? 0,
                        ':billetes_2000' => $fila['billetes_2000'] ?? 0,
                        ':billetes_1000' => $fila['billetes_1000'] ?? 0,
                        ':billetes_500' => $fila['billetes_500'] ?? 0,
                        ':billetes_200' => $fila['billetes_200'] ?? 0,
                        ':billetes_100' => $fila['billetes_100'] ?? 0,
                        ':billetes_50' => $fila['billetes_50'] ?? 0,
                        ':billetes_20' => $fila['billetes_20'] ?? 0,
                        ':billetes_10' => $fila['billetes_10'] ?? 0
                    ]);
                }
            }

            // Confirmar la transacción
            $this->db->commit();

            return ['success' => true, 'message' => 'Datos insertados correctamente'];
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $this->db->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
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

                case 'obtenerCierreCajaHoy':
                    $cierresCaja = $controller->obtenerCierreCajaHoy(); // Llama a la nueva función
                    echo json_encode(['error' => false, 'data' => $cierresCaja]);
                    break;
                case 'insertarRendicion': // Nuevo case
                        $data = json_decode(file_get_contents('php://input'), true);
                        $resultado = $controller->insertarRendicion($data);
                        echo json_encode($resultado);
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