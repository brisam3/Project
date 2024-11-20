<?php

// Incluir archivo de conexión
include '../../../database/Database.php';

// Mostrar todos los errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurar el encabezado para devolver JSON
header('Content-Type: application/json');

// Verificar si se recibió una fecha
if (!isset($_GET['fecha']) || empty($_GET['fecha'])) {
    http_response_code(400);
    echo json_encode(["error" => "No se proporcionó una fecha"]);
    exit;
}

$fecha = $_GET['fecha'];

try {
    // Crear la conexión usando el método getConnection
    $database = new Database();
    $pdo = $database->getConnection();  // Obtener la conexión a la base de datos

    // Consulta para "Ventas por Móvil"
    $ventasQuery = "
        SELECT idUsuarioPreventista, total_menos_gastos 
        FROM rendicion_choferes 
        WHERE fecha = :fecha
    ";
    $stmtVentas = $pdo->prepare($ventasQuery);
    $stmtVentas->execute(['fecha' => $fecha]);
    $ventas = $stmtVentas->fetchAll(PDO::FETCH_ASSOC);

    // Sumar todos los valores de "total_menos_gastos"
    $totalVentas = 0;
    foreach ($ventas as $venta) {
        $totalVentas += (float) $venta['total_menos_gastos'];  // Asegurarse de que se sumen como números flotantes
    }

    // Consulta para "Totales por Medios de Pago"
    $mediosPagoQuery = "
        SELECT 
            total_efectivo,
            total_transferencias,
            total_mercadopago,
            total_cheques,
            total_fiados
        FROM rendicion_choferes
        WHERE fecha = :fecha
    ";
    $stmtMediosPago = $pdo->prepare($mediosPagoQuery);
    $stmtMediosPago->execute(['fecha' => $fecha]);
    $mediosPago = $stmtMediosPago->fetchAll(PDO::FETCH_ASSOC);

    // Sumar los totales de los medios de pago
    $totalEfectivo = 0;
    $totalMercadoPago = 0;
    $totalTransferencias = 0;
    $totalCheques = 0;
    $totalFiados = 0;

    foreach ($mediosPago as $medio) {
        $totalEfectivo += (float) $medio['total_efectivo'];
        $totalMercadoPago += (float) $medio['total_mercadopago'];
        $totalTransferencias += (float) $medio['total_transferencias'];
        $totalCheques += (float) $medio['total_cheques'];
        $totalFiados += (float) $medio['total_fiados'];
    }

    // Consulta para "Cierre de Caja"
    $cierreCajaQuery = "
        SELECT total_menos_gastos
        FROM cierrecaja
        WHERE fecha_cierre = :fecha
    ";
    $stmtCierreCaja = $pdo->prepare($cierreCajaQuery);
    $stmtCierreCaja->execute(['fecha' => $fecha]);
    $cierreCaja = $stmtCierreCaja->fetchAll(PDO::FETCH_ASSOC);

    // Sumar todos los "total_menos_gastos" del cierre de caja
    $totalCierreCaja = 0;
    foreach ($cierreCaja as $cierre) {
        $totalCierreCaja += (float) $cierre['total_menos_gastos'];
    }

    // Si no hay registros, puedes devolver un valor nulo o vacío
    if (empty($cierreCaja)) {
        $totalCierreCaja = null; // O poner 0 si prefieres
    }

    // Respuesta en JSON, incluyendo la suma de "total_menos_gastos" y "totalCierreCaja"
    echo json_encode([
        "ventas" => $ventas,
        "mediosPago" => $mediosPago,
        "totalVentas" => $totalVentas,  // Suma de "total_menos_gastos" en ventas
        "totalCierreCaja" => $totalCierreCaja,  // Suma de "total_menos_gastos" en cierre de caja
        "totalesMediosPago" => [
            "total_efectivo" => $totalEfectivo,
            "total_mercadopago" => $totalMercadoPago,
            "total_transferencia" => $totalTransferencias,
            "total_cheques" => $totalCheques,
            "total_fiados" => $totalFiados
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en el servidor: " . $e->getMessage()]);
}

?>
