<?php
session_start();
require_once '../../../database/Database.php';

class CierreCajaChoferController {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function guardarCierreCajaChofer() {
        $idUsuarioChofer = $_SESSION['idUsuario'] ?? null;

        if (!$idUsuarioChofer) {
            echo json_encode(['error' => 'Usuario no autenticado']);
            exit;
        }

        $total_efectivo = (float)$_POST['total_efectivo'];
        $total_transferencias = (float)$_POST['total_transferencias'];
        $total_mercadopago = (float)$_POST['total_mercadopago'];
        $total_cheques = (float)$_POST['total_cheques'];
        $total_fiados = (float)$_POST['total_fiados'];
        $total_gastos = (float)$_POST['total_gastos'];
        $pago_secretario = (float)$_POST['pago_secretario'];
        $total_mec_faltante = (float)$_POST['total_mec_faltante'];
        $total_rechazos = (float)$_POST['total_rechazos'];
        $idUsuarioPreventista = (int)$_POST['idUsuarioPreventista'];
        
        // Capturar los nuevos valores
        $total_general = (float)$_POST['total_general'];
        $total_menos_gastos = (float)$_POST['total_menos_gastos'];

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO rendicion_choferes
                (idUsuarioChofer, fecha, total_efectivo, total_transferencias, total_mercadopago, total_cheques, 
                total_fiados, total_gastos, pago_secretario, total_mec_faltante, total_rechazos, 
                idUsuarioPreventista, total_general, total_menos_gastos)
                VALUES (:idUsuarioChofer, CURDATE(), :total_efectivo, :total_transferencias, :total_mercadopago, 
                :total_cheques, :total_fiados, :total_gastos, :pago_secretario, :total_mec_faltante, 
                :total_rechazos, :idUsuarioPreventista, :total_general, :total_menos_gastos)
            ");

            $stmt->bindParam(':idUsuarioChofer', $idUsuarioChofer);
            $stmt->bindParam(':total_efectivo', $total_efectivo);
            $stmt->bindParam(':total_transferencias', $total_transferencias);
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

            $stmt->execute();
            echo json_encode(['success' => 'Cierre guardado']);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CierreCajaChoferController();
    $controller->guardarCierreCajaChofer();
}
