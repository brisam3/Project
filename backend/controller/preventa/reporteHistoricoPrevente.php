<?php

// Incluir archivo de conexión
include '../../../database/Database.php';

// Mostrar todos los errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurar el encabezado para devolver JSON
header('Content-Type: application/json');

date_default_timezone_set('America/Argentina/Buenos_Aires'); // Ajusta según tu zona horaria
$today = date('Y-m-d');

// Función para ejecutar una consulta preparada y devolver los resultados
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

// Función para verificar si existen registros en detallereporte con la fecha actual
// Función para verificar si existen registros en detallereporte entre una fecha inicial y una fecha final
function existeDetalleReporte($pdo, $fechaInicio, $fechaFin)
{
    $sql = "SELECT COUNT(*) as total FROM detallereporte WHERE fecha BETWEEN :fechaInicio AND :fechaFin";
    $resultado = ejecutarConsulta($pdo, $sql, [
        ':fechaInicio' => $fechaInicio,
        ':fechaFin' => $fechaFin
    ]);
    return isset($resultado[0]['total']) && $resultado[0]['total'] > 0;
}


// Función para obtener el resumen
function obtenerResumen($pdo, $today)
{
    $sql = "SELECT
        COUNT(DISTINCT c.Comp_Ppal) AS CantidadBoletas,
        COUNT(DISTINCT c.Comp_Cliente_Cod) AS CantidadClientes,
        SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta
    FROM comprobantes c
    JOIN detallereporte d ON c.detalleReporte_id = d.id
    WHERE d.fecha = :today";

    $resultado = ejecutarConsulta($pdo, $sql, [':today' => $today]);

    if (!$resultado || $resultado[0]['TotalVenta'] == 0) {
        return [
            'TotalVenta' => 0,
            'CantidadClientes' => 0,
            'CantidadBoletas' => 0,
            'TicketPromedio' => 0
        ];
    }

    $resumen = $resultado[0];
    $resumen['TicketPromedio'] = $resumen['CantidadBoletas'] > 0
        ? $resumen['TotalVenta'] / $resumen['CantidadBoletas']
        : 0;

    return $resumen;
}

// Función para obtener ventas por preventista
function obtenerVentasPreventista($pdo, $today)
{
    $sql = "SELECT
        u.nombre AS Preventista,
        COUNT(DISTINCT c.Comp_Ppal) AS CantidadBoletas,
        COUNT(DISTINCT c.Comp_Cliente_Cod) AS CantidadClientes,
        SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta,
        (SUM(c.Item_Impte_Total_mon_Emision) / COUNT(DISTINCT c.Comp_Ppal)) AS TicketPromedio,
        COUNT(DISTINCT c.Item_Articulo_Cod_Gen) AS VariedadArticulos,
        COUNT(DISTINCT c.Articulo_Prov_Habitual_Cod) AS VariedadProveedores,
        (COUNT(DISTINCT c.Item_Articulo_Cod_Gen) / COUNT(DISTINCT c.Comp_Cliente_Cod)) AS PromedioArticulosPorCliente,
        (COUNT(DISTINCT c.Articulo_Prov_Habitual_Cod) / COUNT(DISTINCT c.Comp_Cliente_Cod)) AS PromedioProveedoresPorCliente,
        (SUM(c.Item_Impte_Total_mon_Emision) * 0.04) AS Comision
    FROM comprobantes c
    JOIN detallereporte d ON c.detalleReporte_id = d.id
    JOIN usuarios u ON TRIM(c.Comp_Vendedor_Cod) = TRIM(u.usuario)
    WHERE d.fecha = :today
    GROUP BY u.nombre
    ORDER BY TotalVenta DESC";

    return ejecutarConsulta($pdo, $sql, [':today' => $today]);
}

// Función para agregar coronitas a los valores máximos
function agregarCoronitas(&$ventasPreventista)
{
    if (empty($ventasPreventista)) {
        return;
    }

    $fields = ['CantidadBoletas', 'CantidadClientes'];
    $maxValues = [];

    foreach ($fields as $field) {
        $maxValues[$field] = max(array_column($ventasPreventista, $field));
    }

    foreach ($ventasPreventista as &$venta) {
        foreach ($fields as $field) {
            if ($venta[$field] == $maxValues[$field]) {
                $venta[$field] .= ' 👑';
            }
        }
    }
}

// Función para obtener ventas por proveedor
function obtenerVentasProveedor($pdo, $today)
{
    $sql = "SELECT
        p.descripcion AS Proveedor,
        SUM(c.Item_Cant_UM1) AS CantidadArticulos,
        SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta
    FROM comprobantes c
    JOIN detallereporte d ON c.detalleReporte_id = d.id
    JOIN proveedores p ON c.Articulo_Prov_Habitual_Cod = p.cod_proveedor
    WHERE d.fecha = :today
    GROUP BY p.descripcion
    ORDER BY TotalVenta DESC";

    return ejecutarConsulta($pdo, $sql, [':today' => $today]);
}

// Función para manejar peticiones AJAX
function manejarPeticionAjax()
{
    global $today;

    try {
        $database = new Database();
        $pdo = $database->getConnection();

        // Obtener el parámetro "action" de la URL
        $action = isset($_GET['action']) ? $_GET['action'] : null;

        if (!$action) {
            echo json_encode(["error" => "Acción no especificada."]);
            return;
        }

        // Validar si hay registros para la fecha actual
        if (!existeDetalleReporte($pdo, $today)) {
            echo json_encode([
                "error" => "No se encontraron registros para la fecha actual.",
                "mostrarMensaje" => true
            ]);
            return;
        }

        // Manejar las acciones específicas
        $respuesta = [];
        switch ($action) {
            case 'resumen':
                $respuesta = obtenerResumen($pdo, $today);
                break;

            case 'ventasPreventista':
                $respuesta = obtenerVentasPreventista($pdo, $today);
                agregarCoronitas($respuesta);
                break;

            case 'ventasProveedor':
                $respuesta = obtenerVentasProveedor($pdo, $today);
                break;

            default:
                echo json_encode(["error" => "Acción no válida: $action"]);
                return;
        }

        // Devolver solo la respuesta correspondiente
        echo json_encode($respuesta);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Error en el servidor: " . $e->getMessage()]);
    }
}

// Ejecutar la función principal
manejarPeticionAjax();

?>
