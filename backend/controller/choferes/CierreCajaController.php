<?php
session_start();

if (!isset($_SESSION['idUsuario'])) {
  // Redirigir al usuario de vuelta a la página de inicio de sesión si no está autenticado
  header("Location: https://softwareparanegociosformosa.com/wol/pages/login/login.html");
  exit();
}
require_once '../../../database/Database.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class CierreCajaChoferController {
    private $pdo;
    
    

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function guardarCierreCajaChofer() {
        $idUsuarioChofer = $_SESSION['idUsuario'] ?? null;
        $fecha = date('Y-m-d'); // Solo la fecha en formato YYYY-MM-DD

        if (!$idUsuarioChofer) {
            echo json_encode(['error' => 'Usuario no autenticado']);
            exit;
        }
        $contrareembolso = (float)$_POST['contrareembolso']; // Nuevo campo
        $retenciones = (float)$_POST['total_retenciones']; // Nuevo campo
        $total_efectivo = (float)$_POST['total_efectivo'];
        $total_transferencia = (float)$_POST['total_transferencia'];
        $total_mercadopago = (float)$_POST['total_mercadopago'];
        $total_cheques = (float)$_POST['total_cheques'];
        $total_fiados = (float)$_POST['total_fiados'];
        $total_gastos = (float)$_POST['total_gastos'];
        $pago_secretario = (float)$_POST['pago_secretario'];
        $total_mec_faltante = (float)$_POST['total_mec_faltante'];
        $total_rechazos = (float)$_POST['total_rechazos'];
        $idUsuarioPreventista = (int)$_POST['idUsuarioPreventista'];
        $total_general = (float)$_POST['total_general'];
        $total_menos_gastos = (float)$_POST['total_menos_gastos'];
        

        $billetes_20000 = (float)$_POST['billetes_20000'];
        $billetes_10000 = (float)$_POST['billetes_10000'];
        $billetes_2000 = (float)$_POST['billetes_2000'];
        $billetes_1000 = (float)$_POST['billetes_1000'];
        $billetes_500 = (float)$_POST['billetes_500'];
        $billetes_200 = (float)$_POST['billetes_200'];
        $billetes_100 = (float)$_POST['billetes_100'];
        $billetes_50 = (float)$_POST['billetes_50'];
        $billetes_20 = (float)$_POST['billetes_20'];
        $billetes_10 = (int)$_POST['billetes_10'];

       
      
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO rendicion_choferes
                (idUsuarioChofer, fecha, total_efectivo, total_transferencia, total_mercadopago, total_cheques, 
                total_fiados, total_gastos, pago_secretario, total_mec_faltante, total_rechazos, 
                idUsuarioPreventista, total_general, total_menos_gastos, retenciones, 
                billetes_20000, billetes_10000, billetes_2000, billetes_1000, billetes_500, 
                billetes_200, billetes_100, billetes_50, billetes_20, billetes_10, contrareembolso)
                VALUES (:idUsuarioChofer, :fecha, :total_efectivo, :total_transferencia, :total_mercadopago, 
                :total_cheques, :total_fiados, :total_gastos, :pago_secretario, :total_mec_faltante, 
                :total_rechazos, :idUsuarioPreventista, :total_general, :total_menos_gastos, :retenciones, 
                :billetes_20000, :billetes_10000, :billetes_2000, :billetes_1000, :billetes_500, 
                :billetes_200, :billetes_100, :billetes_50, :billetes_20, :billetes_10, :contrareembolso)
            ");

            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':idUsuarioChofer', $idUsuarioChofer);
            $stmt->bindParam(':total_efectivo', $total_efectivo);
            $stmt->bindParam(':total_transferencia', $total_transferencia);
            $stmt->bindParam(':total_mercadopago', $total_mercadopago);
            $stmt->bindParam(':total_cheques', $total_cheques);
            $stmt->bindParam(':total_fiados', $total_fiados);
            $stmt->bindParam(':total_gastos', $total_gastos);
            $stmt->bindParam(':pago_secretario', $pago_secretario);
            $stmt->bindParam(':total_mec_faltante', $total_mec_faltante);
            $stmt->bindParam(':total_rechazos', $total_rechazos);
            $stmt->bindParam(':idUsuarioPreventista', $idUsuarioPreventista);
            $stmt->bindParam(':total_general', $total_general);
            $stmt->bindParam(':total_menos_gastos', $total_menos_gastos);

            $stmt->bindParam(':billetes_20000', $billetes_20000);
            $stmt->bindParam(':billetes_10000', $billetes_10000);
            $stmt->bindParam(':billetes_2000', $billetes_2000);
            $stmt->bindParam(':billetes_1000', $billetes_1000);
            $stmt->bindParam(':billetes_500', $billetes_500);
            $stmt->bindParam(':billetes_200', $billetes_200);
            $stmt->bindParam(':billetes_100', $billetes_100);
            $stmt->bindParam(':billetes_50', $billetes_50);
            $stmt->bindParam(':billetes_20', $billetes_20);
            $stmt->bindParam(':billetes_10', $billetes_10);
            $stmt->bindParam(':retenciones', $retenciones); // Nuevo campo
            $stmt->bindParam(':contrareembolso', $contrareembolso); // Nuevo campo

            $stmt->execute();

            // Verificar el ID generado
            $lastId = $this->pdo->lastInsertId();
            if ($lastId) {
                echo json_encode(['success' => "Registro insertado con éxito. ID generado: $lastId"]);
            } else {
                echo json_encode(['error' => 'No se generó un ID, posible fallo en la inserción.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

     
    public function obtenerContrareembolso() {
        $idUsuarioPreventista = $_POST['idUsuarioPreventista'] ?? null;
    
        if (!$idUsuarioPreventista) {
            echo json_encode(['error' => 'Falta el idUsuarioPreventista']);
            return;
        }
    
        // Verificar el día de la semana
        $hoy = date('Y-m-d');
        $diaDeLaSemana = date('w', strtotime($hoy)); // 1 = Lunes, 0 = Domingo
    
        // Calcular la fecha correspondiente (restar 2 días si es lunes)
        $diasARestar = ($diaDeLaSemana == 1) ? 2 : 1; // Si es lunes, restar 2; de lo contrario, restar 1
        $fechaConsulta = date('Y-m-d', strtotime("-{$diasARestar} day", strtotime($hoy)));
    
        $queryVentas = "
            SELECT
                u.idUsuario AS id_preventista,
                u.nombre AS nombre_preventista,
                SUM(c.Item_Impte_Total_mon_Emision) AS total_ventas
            FROM 
                comprobantes c
            JOIN 
                detallereporte d ON c.detalleReporte_id = d.id
            JOIN 
                usuarios u ON TRIM(c.Comp_Vendedor_Cod) = TRIM(u.usuario)
            WHERE 
                d.fecha = :fechaConsulta
                AND u.idUsuario = :idUsuarioPreventista
            GROUP BY 
                u.idUsuario, u.nombre
        ";
    
        try {
            $stmtVentas = $this->pdo->prepare($queryVentas);
            $stmtVentas->execute([
                ':fechaConsulta' => $fechaConsulta,
                ':idUsuarioPreventista' => $idUsuarioPreventista
            ]);
    
            $ventas = $stmtVentas->fetch(PDO::FETCH_ASSOC);
    
            if ($ventas) {
                echo json_encode($ventas);
            } else {
                echo json_encode(['error' => 'No se encontraron datos para el preventista seleccionado']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    
    


    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller = new CierreCajaChoferController();
    
        // Determinar la acción solicitada
        $action = $_POST['action'] ?? 'guardar';
    
        if ($action === 'guardar') {
            $controller->guardarCierreCajaChofer();
        } elseif ($action === 'obtenerContrareembolso') {
            $controller->obtenerContrareembolso();
        } else {
            echo json_encode(['error' => 'Acción no reconocida']);
        }
    }
?>