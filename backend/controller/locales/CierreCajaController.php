<?php
session_start();

if (!isset($_SESSION['idUsuario'])) {
  // Redirigir al usuario de vuelta a la página de inicio de sesión si no está autenticado
  header("Location: https://softwareparanegociosformosa.com/wol/pages/login/login.html");
  exit();
}
require_once '../../../database/Database.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class CierreCajaController
{
    private $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function guardarCierreCaja()
    {

        $fecha = date('Y-m-d'); // Solo la fecha en formato YYYY-MM-DD

        if (!isset($_SESSION['idUsuario'])) {
            echo json_encode(['error' => 'Usuario no autenticado']);
            exit;
        }
    
        $idUsuario = $_SESSION['idUsuario'];
    
        // Obtener datos del formulario
        $billetes_10000 = isset($_POST['billetes_10000']) ? (int)$_POST['billetes_10000'] : 0;
        $billetes_2000 = isset($_POST['billetes_2000']) ? (int)$_POST['billetes_2000'] : 0;
        $billetes_1000 = isset($_POST['billetes_1000']) ? (int)$_POST['billetes_1000'] : 0;
        $billetes_500 = isset($_POST['billetes_500']) ? (int)$_POST['billetes_500'] : 0;
        $billetes_200 = isset($_POST['billetes_200']) ? (int)$_POST['billetes_200'] : 0;
        $billetes_100 = isset($_POST['billetes_100']) ? (int)$_POST['billetes_100'] : 0;
        $billetes_50 = isset($_POST['billetes_50']) ? (int)$_POST['billetes_50'] : 0;
        $billetes_20 = isset($_POST['billetes_20']) ? (int)$_POST['billetes_20'] : 0;
        $billetes_10 = isset($_POST['billetes_10']) ? (int)$_POST['billetes_10'] : 0;
    
        $efectivo = isset($_POST['efectivo']) ? (float)$_POST['efectivo'] : 0;
        $mercadoPago = isset($_POST['mercado_pago']) ? (float)$_POST['mercado_pago'] : 0;
        $payway = isset($_POST['payway']) ? (float)$_POST['payway'] : 0;
        $cambios = isset($_POST['cambios']) ? (float)$_POST['cambios'] : 0;
        $cuentaCorriente = isset($_POST['cuenta_corriente']) ? (float)$_POST['cuenta_corriente'] : 0;
        $gastos = isset($_POST['gastos']) ? (float)$_POST['gastos'] : 0;
    
        // Calcular total general y total menos gastos
        $totalGeneral = $efectivo + $mercadoPago + $payway + $cambios + $cuentaCorriente + $gastos;
        $totalMenosGastos = $totalGeneral - $gastos;
    
        // Agregar logs de depuración aquí
        error_log('Efectivo: ' . $efectivo);
        error_log('Mercado Pago: ' . $mercadoPago);
        error_log('Payway: ' . $payway);
        error_log('Cambios: ' . $cambios);
        error_log('Cuenta Corriente: ' . $cuentaCorriente);
        error_log('Gastos: ' . $gastos);
        error_log('Total General Calculado: ' . $totalGeneral);
        error_log('Total Menos Gastos Calculado: ' . $totalMenosGastos);
    
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO cierreCaja 
                (idUsuario, fecha_cierre, efectivo, mercado_pago, payway, cambios, cuenta_corriente, gastos,
                 billetes_10000, billetes_2000, billetes_1000, billetes_500, billetes_200, billetes_100, billetes_50, 
                 billetes_20, billetes_10, total_general, total_menos_gastos)
                VALUES 
                (:idUsuario, :fechaCierre, :efectivo, :mercadoPago, :payway, :cambios, :cuentaCorriente, :gastos,
                 :billetes_10000, :billetes_2000, :billetes_1000, :billetes_500, :billetes_200, :billetes_100, :billetes_50, 
                 :billetes_20, :billetes_10, :totalGeneral, :totalMenosGastos)
            ");
    
            // Vincular parámetros
            $stmt->bindParam(':fechaCierre', $fecha);

            $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(':efectivo', $efectivo);
            $stmt->bindParam(':mercadoPago', $mercadoPago);
            $stmt->bindParam(':payway', $payway);
            $stmt->bindParam(':cambios', $cambios);
            $stmt->bindParam(':cuentaCorriente', $cuentaCorriente);
            $stmt->bindParam(':gastos', $gastos);
    
            $stmt->bindParam(':billetes_10000', $billetes_10000);
            $stmt->bindParam(':billetes_2000', $billetes_2000);
            $stmt->bindParam(':billetes_1000', $billetes_1000);
            $stmt->bindParam(':billetes_500', $billetes_500);
            $stmt->bindParam(':billetes_200', $billetes_200);
            $stmt->bindParam(':billetes_100', $billetes_100);
            $stmt->bindParam(':billetes_50', $billetes_50);
            $stmt->bindParam(':billetes_20', $billetes_20);
            $stmt->bindParam(':billetes_10', $billetes_10);
    
            $stmt->bindParam(':totalGeneral', $totalGeneral);
            $stmt->bindParam(':totalMenosGastos', $totalMenosGastos);
    
            $stmt->execute();
            $lastInsertId = $this->pdo->lastInsertId();
            echo json_encode([
                'success' => true,
                'message' => 'Cierre de caja guardado exitosamente. ID: ' . $lastInsertId,
                'idCierreCaja' => $lastInsertId
            ]);
            
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al guardar el cierre de caja: ' . $e->getMessage()]);
        }
    }
    

}

// Verificar si el método de la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CierreCajaController();
    $controller->guardarCierreCaja();
}
?>