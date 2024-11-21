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

    // Registro de depuración
    error_log("Ventas por móvil: " . print_r($ventas, true));

    // Sumar todos los valores de "total_menos_gastos"
    $totalVentas = array_reduce($ventas, function($sum, $venta) {
        return $sum + (float) $venta['total_menos_gastos'];
    }, 0);

    // Consulta para "Totales por Medios de Pago"
    $mediosPagoQuery = "
        SELECT 
            total_efectivo,
            total_payway,
            total_mercadopago,
            total_cambios,
            total_fiados
        FROM rendicion_choferes
        WHERE fecha = :fecha
    ";
    $stmtMediosPago = $pdo->prepare($mediosPagoQuery);
    $stmtMediosPago->execute(['fecha' => $fecha]);
    $mediosPago = $stmtMediosPago->fetchAll(PDO::FETCH_ASSOC);

    // Registro de depuración
    error_log("Medios de pago: " . print_r($mediosPago, true));

    // Sumar los totales de los medios de pago
    $totalesMediosPago = [
        "total_efectivo" => 0,
        "total_mercadopago" => 0,
        "total_payway" => 0,
        "total_cambios" => 0,
        "total_fiados" => 0
    ];

    foreach ($mediosPago as $medio) {
        $totalesMediosPago['total_efectivo'] += (float) $medio['total_efectivo'];
        $totalesMediosPago['total_mercadopago'] += (float) $medio['total_mercadopago'];
        $totalesMediosPago['total_payway'] += (float) $medio['total_payway'];
        $totalesMediosPago['total_cambios'] += (float) $medio['total_cambios'];
        $totalesMediosPago['total_fiados'] += (float) $medio['total_fiados'];
    }

    // Consulta para "Cierre de Caja"
    $cierreCajaQuery = "
        SELECT total_menos_gastos
        FROM cierreCaja
        WHERE fecha_cierre = :fecha
    ";
    $stmtCierreCaja = $pdo->prepare($cierreCajaQuery);
    $stmtCierreCaja->execute(['fecha' => $fecha]);
    $cierreCaja = $stmtCierreCaja->fetchAll(PDO::FETCH_ASSOC);

    // Sumar todos los "total_menos_gastos" del cierre de caja
    $totalCierreCaja = array_reduce($cierreCaja, function($sum, $cierre) {
        return $sum + (float) $cierre['total_menos_gastos'];
    }, 0);

    // Registro de depuración
    error_log("Cierre de caja: " . print_r($cierreCaja, true));

    // Respuesta en JSON
    echo json_encode([
        "ventas" => $ventas,
        "mediosPago" => $mediosPago,
        "totalVentas" => $totalVentas,
        "totalCierreCaja" => $totalCierreCaja,
        "totalesMediosPago" => $totalesMediosPago
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en el servidor: " . $e->getMessage()]);
}

?>
