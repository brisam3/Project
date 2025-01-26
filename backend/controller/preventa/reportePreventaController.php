<?php

// Incluir archivo de conexión
include '../../../database/Database.php';

// Mostrar todos los errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurar el encabezado para devolver JSON
header('Content-Type: application/json');

// Configurar la zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Capturar las fechas enviadas por el formulario
$fechaInicio = isset($_GET['startDate']) ? $_GET['startDate'] : null;
$fechaFin = isset($_GET['endDate']) ? $_GET['endDate'] : null;

// Validar que ambas fechas estén presentes
if (!$fechaInicio || !$fechaFin) {
    echo json_encode(["error" => "Debe proporcionar ambas fechas (startDate y endDate)."]);
    exit;
}

// Validar formato de las fechas (opcional)
if (!validarFormatoFecha($fechaInicio) || !validarFormatoFecha($fechaFin)) {
    echo json_encode(["error" => "El formato de las fechas debe ser AAAA-MM-DD."]);
    exit;
}

// Función principal para manejar las peticiones
function manejarPeticionAjax($fechaInicio, $fechaFin)
{
    try {
        // Conectar a la base de datos
        $database = new Database();
        $pdo = $database->getConnection();

        // Obtener la acción desde la URL
        $action = isset($_GET['action']) ? $_GET['action'] : null;

        if (!$action) {
            echo json_encode(["error" => "Acción no especificada."]);
            return;
        }

        $respuesta = [];
        switch ($action) {
            case 'resumen':
                // Obtener el resumen para el rango de fechas
                $respuesta = obtenerResumen($pdo, $fechaInicio, $fechaFin);
                break;

            case 'verificar':
                // Verificar si existen registros en el rango de fechas
                if (existeDetalleReporte($pdo, $fechaInicio, $fechaFin)) {
                    $respuesta = ["success" => true, "message" => "Existen registros en el rango de fechas."];
                } else {
                    $respuesta = ["success" => false, "message" => "No se encontraron registros en el rango de fechas."];
                }
                break;

            default:
                echo json_encode(["error" => "Acción no válida: $action"]);
                return;
        }

        // Responder con los datos procesados
        echo json_encode($respuesta);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Error en el servidor: " . $e->getMessage()]);
    }
}

/**
 * Validar formato de fecha (AAAA-MM-DD)
 */
function validarFormatoFecha($fecha)
{
    return preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha);
}

/**
 * Ejecutar una consulta preparada y devolver los resultados
 */
function ejecutarConsulta($pdo, $sql, $params = [])
{
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception("Error ejecutando la consulta: " . $e->getMessage());
    }
}

/**
 * Verificar si existen registros en detallereporte en un rango de fechas
 */
function existeDetalleReporte($pdo, $fechaInicio, $fechaFin)
{
    $sql = "SELECT COUNT(*) as total FROM detallereporte WHERE fecha BETWEEN :startDate AND :endDate";
    $resultado = ejecutarConsulta($pdo, $sql, [
        ':startDate' => $fechaInicio,
        ':endDate' => $fechaFin
    ]);
    return isset($resultado[0]['total']) && $resultado[0]['total'] > 0;
}

/**
 * Obtener el resumen de ventas en un rango de fechas
 */
function obtenerResumen($pdo, $fechaInicio, $fechaFin)
{
    $sql = "
        SELECT
            COUNT(DISTINCT c.Comp_Ppal) AS CantidadBoletas,
            COUNT(DISTINCT c.Comp_Cliente_Cod) AS CantidadClientes,
            SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta
        FROM comprobantes c
        JOIN detallereporte d ON c.detalleReporte_id = d.id
        WHERE d.fecha BETWEEN :startDate AND :endDate
    ";

    $resultado = ejecutarConsulta($pdo, $sql, [
        ':startDate' => $fechaInicio,
        ':endDate' => $fechaFin
    ]);

    // Validar si hay datos en el resultado
    if (!$resultado || empty($resultado[0]['TotalVenta'])) {
        return [
            'TotalVenta' => 0,
            'CantidadClientes' => 0,
            'CantidadBoletas' => 0,
            'TicketPromedio' => 0
        ];
    }

    // Calcular el ticket promedio
    $resumen = $resultado[0];
    $resumen['TicketPromedio'] = $resumen['CantidadBoletas'] > 0
        ? $resumen['TotalVenta'] / $resumen['CantidadBoletas']
        : 0;

    return $resumen;
}

// Ejecutar la función principal
manejarPeticionAjax($fechaInicio, $fechaFin);

?>
