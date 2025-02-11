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
    $mediosPagoLocalesQuery = "
        SELECT 
	        efectivo,		
            mercado_pago,		
            payway,
            onda,
            cambios,	
            cuenta_corriente
        FROM cierreCaja
        WHERE fecha_cierre = :fecha
    ";
    $stmtMediosPagoLocales = $pdo->prepare($mediosPagoLocalesQuery);
    $stmtMediosPagoLocales->execute(['fecha' => $fecha]);
    $mediosPagoLocales = $stmtMediosPagoLocales->fetchAll(PDO::FETCH_ASSOC);

    // Registro de depuración
    error_log("Medios de pago locales: " . print_r($mediosPagoLocales, true));

    // Sumar los totales de los medios de pago
    $totalesMediosPagoLocales = [
        "efectivo" => 0,
        "mercado_pago" => 0,
        "payway" => 0,
        "onda" => 0,
        "cambios" => 0,
        "cuenta_corriente" => 0
    ];

    foreach ($mediosPagoLocales as $medio) {
        $totalesMediosPagoLocales['efectivo'] += (float) $medio['efectivo'];
        $totalesMediosPagoLocales['mercado_pago'] += (float) $medio['mercado_pago'];
        $totalesMediosPagoLocales['payway'] += (float) $medio['payway'];
        $totalesMediosPagoLocales['onda'] += (float) $medio['onda'];
        $totalesMediosPagoLocales['cambios'] += (float) $medio['cambios'];
        $totalesMediosPagoLocales['cuenta_corriente'] += (float) $medio['cuenta_corriente'];
    }

       // Consulta para "Totales por Medios de Pago"
       $mediosPagoQuery = "
       SELECT 
           total_efectivo,
           total_transferencia,
           total_mercadopago,
           total_cheques,
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
    "total_transferencia" => 0,
    "total_cheques" => 0,
    "total_fiados" => 0
];

foreach ($mediosPago as $medio) {
    $totalesMediosPago['total_efectivo'] += (float) $medio['total_efectivo'];
    $totalesMediosPago['total_mercadopago'] += (float) $medio['total_mercadopago'];
    $totalesMediosPago['total_transferencia'] += (float) $medio['total_transferencia'];
    $totalesMediosPago['total_cheques'] += (float) $medio['total_cheques'];
    $totalesMediosPago['total_fiados'] += (float) $medio['total_fiados'];
}



   $ventasLocalesQuery = "
   SELECT idUsuario, total_menos_gastos 
   FROM cierreCaja 
   WHERE fecha_cierre = :fecha
";
$stmtVentasLocales = $pdo->prepare($ventasLocalesQuery);
$stmtVentasLocales->execute(['fecha' => $fecha]);
$ventasLocales = $stmtVentasLocales->fetchAll(PDO::FETCH_ASSOC);

// Registro de depuración
error_log("Ventas por móvil: " . print_r($ventasLocales, true));

// Sumar todos los valores de "total_menos_gastos"
$totalVentasLocales = array_reduce($ventasLocales, function($sum, $ventaLocales) {
   return $sum + (float) $ventaLocales['total_menos_gastos'];
}, 0);

error_log("Ventas locales: " . print_r($ventasLocales, true));


    // Respuesta en JSON
    echo json_encode([
        "ventas" => $ventas,
        "ventasLocales" => $ventasLocales,
        "mediosPago" => $mediosPago,
        "mediosPagoLocales"=> $mediosPagoLocales,
        "totalVentas" => $totalVentas,
        "totalVentasLocales"=> $totalVentasLocales,
        "totalesMediosPago" => $totalesMediosPago,
        "totalesMediosPagoLocales"=>$totalesMediosPagoLocales
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en el servidor: " . $e->getMessage()]);
}

?>