<?php

// Incluir archivo de conexión
include '../../../database/Database.php';

// Mostrar todos los errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurar encabezado para devolver JSON
header('Content-Type: application/json');

date_default_timezone_set('America/Argentina/Buenos_Aires'); // Ajusta según tu zona horaria

// Inicializar conexión a la base de datos
$pdo = null;
try {
    $db = new Database(); // Crear instancia de la clase Database
    $pdo = $db->getConnection(); // Obtener la conexión PDO
    if (!$pdo) {
        throw new Exception('No se pudo conectar a la base de datos.');
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Error al conectar a la base de datos: ' . $e->getMessage()]);
    exit;
}

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

// Función para verificar si existen registros en `detallereporte` entre una fecha inicial y una final
function existeDetalleReporte($pdo, $fechaInicio, $fechaFin)
{
    $sql = "SELECT COUNT(*) as total FROM detallereporte WHERE fecha BETWEEN :fechaInicio AND :fechaFin";
    $resultado = ejecutarConsulta($pdo, $sql, [
        ':fechaInicio' => $fechaInicio,
        ':fechaFin' => $fechaFin
    ]);
    return isset($resultado[0]['total']) && $resultado[0]['total'] > 0;
}

// Función para consultar los artículos más vendidos
function consultarArticulosMasVendidos($pdo, $startDate, $endDate)
{
    try {
        // Validar formato de fechas
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
            throw new Exception('Formato de fecha inválido. Use el formato YYYY-MM-DD.');
        }

        // Consulta SQL para obtener artículos más vendidos
        $sql = "SELECT
            c.Item_Articulo_Cod_Gen AS CodigoArticulo,
            a.descripcion AS Descripcion,
            p.descripcion AS Proveedor,
            SUM(c.Item_Cant_UM1) AS Cantidad,
            SUM(c.Item_Impte_Total_mon_Emision) AS MontoTotal
        FROM comprobantes c
        JOIN detallereporte d ON c.detalleReporte_id = d.id
        JOIN proveedores p ON c.Articulo_Prov_Habitual_Cod = p.cod_proveedor
        JOIN articulos a ON c.Item_Articulo_Cod_Gen = a.codBejerman
        WHERE d.fecha BETWEEN :startDate AND :endDate
        GROUP BY c.Item_Articulo_Cod_Gen, a.descripcion, p.descripcion
        ORDER BY Cantidad DESC";

        // Ejecutar consulta
        return ejecutarConsulta($pdo, $sql, [
            ':startDate' => $startDate,
            ':endDate' => $endDate
        ]);
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}
function consultarArticulosConPrecioCero($pdo, $startDate, $endDate)
{
    try {
        // Validar formato de fechas
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
            throw new Exception('Formato de fecha inválido. Use el formato YYYY-MM-DD.');
        }

        // Consulta SQL para obtener artículos con precio 0 y el total general
        $sql = "SELECT
            c.Item_Articulo_Cod_Gen AS CodigoArticulo,
            a.descripcion AS Descripcion,
            p.descripcion AS Proveedor,
            SUM(c.Item_Cant_UM1) AS TotalGeneral,
            SUM(CASE WHEN c.Item_Pr_Unitario = 0 THEN c.Item_Cant_UM1 ELSE 0 END) AS CantidadCero,
            (SUM(c.Item_Cant_UM1) - SUM(CASE WHEN c.Item_Pr_Unitario = 0 THEN c.Item_Cant_UM1 ELSE 0 END)) AS Diferencia
        FROM comprobantes c
        JOIN detallereporte d ON c.detalleReporte_id = d.id
        JOIN proveedores p ON c.Articulo_Prov_Habitual_Cod = p.cod_proveedor
        JOIN articulos a ON c.Item_Articulo_Cod_Gen = a.codBejerman
        WHERE d.fecha BETWEEN :startDate AND :endDate
        GROUP BY c.Item_Articulo_Cod_Gen, a.descripcion, p.descripcion
        HAVING CantidadCero > 0
        ORDER BY TotalGeneral DESC";

        // Ejecutar consulta
        return ejecutarConsulta($pdo, $sql, [
            ':startDate' => $startDate,
            ':endDate' => $endDate
        ]);
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}


// Verificar método y manejar peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $accion = $_POST['accion'];

    try {
        switch ($accion) {
            case 'consultarArticulosMasVendidos':
                // Obtener fechas desde la petición
                $startDate = $_POST['startDate'] ?? null;
                $endDate = $_POST['endDate'] ?? null;

                // Validar si existen registros en el rango de fechas
                if (!existeDetalleReporte($pdo, $startDate, $endDate)) {
                    throw new Exception('No se encontraron registros en el rango de fechas especificado.');
                }

                // Consultar y devolver artículos más vendidos
                $resultado = consultarArticulosMasVendidos($pdo, $startDate, $endDate);
                echo json_encode($resultado);
                break;

            case 'consultarArticulosConPrecioCero':
                // Obtener fechas desde la petición
                $startDate = $_POST['startDate'] ?? null;
                $endDate = $_POST['endDate'] ?? null;

                // Validar si existen registros en el rango de fechas
                if (!existeDetalleReporte($pdo, $startDate, $endDate)) {
                    throw new Exception('No se encontraron registros en el rango de fechas especificado.');
                }

                // Consultar y devolver artículos con precio 0
                $resultado = consultarArticulosConPrecioCero($pdo, $startDate, $endDate);
                echo json_encode($resultado);
                break;

            default:
                // Acción no reconocida
                throw new Exception('Acción no válida.');
        }
    } catch (Exception $e) {
        // Respuesta en caso de error
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    // Respuesta para métodos no permitidos
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido.']);
}

?>
