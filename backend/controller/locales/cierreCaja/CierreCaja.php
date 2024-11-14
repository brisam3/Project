<?php
session_start();
require_once '../../../../database/Database.php';

if (!isset($_SESSION['idUsuario'])) {
    // Redirigir al usuario de vuelta a la página de inicio de sesión si no está autenticado
    header("Location: ../path/to/loginPage.html");
    exit();
}



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
        // Validamos si el usuario está logueado y obtenemos su id
        if (!isset($_SESSION['idTipoUsuario'])) {
            echo json_encode(['error' => 'Usuario no autenticado']);
            exit;
        }

        $idUsuario = $_SESSION['idTipoUsuario'];
        
        // Obtenemos los datos del formulario
        $efectivo = isset($_POST['efectivo']) ? (float)$_POST['efectivo'] : 0;
        $mercadoPago = isset($_POST['mercado_pago']) ? (float)$_POST['mercado_pago'] : 0;
        $transferencias = isset($_POST['transferencias']) ? (float)$_POST['transferencias'] : 0;
        $cheques = isset($_POST['cheques']) ? (float)$_POST['cheques'] : 0;
        $cuentaCorriente = isset($_POST['cuenta_corriente']) ? (float)$_POST['cuenta_corriente'] : 0;
        $gastos = isset($_POST['gastos']) ? (float)$_POST['gastos'] : 0;

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO cierreCaja 
                (idUsuario, fecha_cierre, efectivo, mercado_pago, transferencias, cheques, cuenta_corriente, gastos) 
                VALUES 
                (:idUsuario, NOW(), :efectivo, :mercadoPago, :transferencias, :cheques, :cuentaCorriente, :gastos)
            ");
            
            $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(':efectivo', $efectivo);
            $stmt->bindParam(':mercadoPago', $mercadoPago);
            $stmt->bindParam(':transferencias', $transferencias);
            $stmt->bindParam(':cheques', $cheques);
            $stmt->bindParam(':cuentaCorriente', $cuentaCorriente);
            $stmt->bindParam(':gastos', $gastos);
            
            $stmt->execute();
            
            echo json_encode(['success' => 'Cierre de caja guardado exitosamente']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al guardar el cierre de caja: ' . $e->getMessage()]);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CierreCajaController();
    $controller->guardarCierreCaja();
}
