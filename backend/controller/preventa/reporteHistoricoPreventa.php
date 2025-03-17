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


function consultarResumenVentas($pdo, $startDate, $endDate)
{
    try {
        // Validar formato de fechas
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
            throw new Exception('Formato de fecha inválido. Use el formato YYYY-MM-DD.');
        }

        // Consultar resumen de ventas
   // Consultar resumen de ventas con corrección para sumar todo el rango de fechas
                    $sqlResumen = "SELECT
                    COUNT(DISTINCT c.Comp_Ppal) AS CantidadBoletas,
                    COUNT(DISTINCT c.Comp_Cliente_Cod) AS CantidadClientes,
                    COALESCE(SUM(c.Item_Impte_Total_mon_Emision), 0) AS TotalVenta
                    FROM comprobantes c
                    JOIN detallereporte d ON c.detalleReporte_id = d.id
                    WHERE d.fecha BETWEEN :startDate AND :endDate";

                    $stmt = $pdo->prepare($sqlResumen);
                    $stmt->execute([
                    ':startDate' => $startDate,
                    ':endDate' => $endDate
                    ]);
                    $resumen = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Verificar si hay datos, de lo contrario, asignar valores predeterminados
                    if (!$resumen || $resumen['TotalVenta'] == 0) {
                    $resumen = [
                        'TotalVenta' => 0,
                        'CantidadClientes' => 0,
                        'CantidadBoletas' => 0,
                        'TicketPromedio' => 0
                    ];
                    } else {
                    // Calcular ticket promedio evitando división por 0
                    $resumen['TicketPromedio'] = $resumen['CantidadBoletas'] > 0 ? 
                        $resumen['TotalVenta'] / $resumen['CantidadBoletas'] : 0;
                    }

                    // Calcular valores adicionales
                    $resumen['TotalVentaMenosIVA'] = $resumen['TotalVenta'] / 1.21;
                    $resumen['TotalVentaMenosPonderado'] = $resumen['TotalVenta'] * 0.47;


        // Consultar ventas por preventista
       // Consultar ventas por preventista, asegurando que sume todo el rango de fechas
        $sqlPreventista = "SELECT
        u.nombre AS Preventista,  
        COUNT(DISTINCT c.Comp_Ppal) AS CantidadBoletas,
        COUNT(DISTINCT c.Comp_Cliente_Cod) AS CantidadClientes,
        COALESCE(SUM(c.Item_Impte_Total_mon_Emision), 0) AS TotalVenta,
        CASE WHEN COUNT(DISTINCT c.Comp_Ppal) > 0 
            THEN SUM(c.Item_Impte_Total_mon_Emision) / COUNT(DISTINCT c.Comp_Ppal) 
            ELSE 0 
        END AS TicketPromedio,
        COUNT(DISTINCT c.Item_Articulo_Cod_Gen) AS VariedadArticulos,
        COUNT(DISTINCT c.Articulo_Prov_Habitual_Cod) AS VariedadProveedores,
        CASE WHEN COUNT(DISTINCT c.Comp_Cliente_Cod) > 0 
            THEN COUNT(DISTINCT c.Item_Articulo_Cod_Gen) / COUNT(DISTINCT c.Comp_Cliente_Cod) 
            ELSE 0 
        END AS PromedioArticulosPorCliente,
        CASE WHEN COUNT(DISTINCT c.Comp_Cliente_Cod) > 0 
            THEN COUNT(DISTINCT c.Articulo_Prov_Habitual_Cod) / COUNT(DISTINCT c.Comp_Cliente_Cod) 
            ELSE 0 
        END AS PromedioProveedoresPorCliente,
        COALESCE(SUM(c.Item_Impte_Total_mon_Emision) * 0.04, 0) AS Comision
        FROM comprobantes c
        JOIN detallereporte d ON c.detalleReporte_id = d.id
        JOIN usuarios u ON TRIM(c.Comp_Vendedor_Cod) = TRIM(u.usuario)  
        WHERE d.fecha BETWEEN :startDate AND :endDate
        GROUP BY u.nombre  
        ORDER BY TotalVenta DESC";

        $stmt = $pdo->prepare($sqlPreventista);
        $stmt->execute([
        ':startDate' => $startDate,
        ':endDate' => $endDate
        ]);
        $ventasPreventista = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$ventasPreventista) {
        $ventasPreventista = [];
        }


        // Función para agregar coronas a los mejores preventistas
        function addCrown(&$ventasPreventista)
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
                        $venta[$field] .= ' 👑'; // Agrega coronita
                    }
                }
            }
        }

        addCrown($ventasPreventista);

        // Calcular el total de comisiones
        $totalComisiones = array_sum(array_column($ventasPreventista, 'Comision'));
        $totalMenosPonderadoIVA = $resumen['TotalVenta'] * 0.32;

        return [
            'resumen' => $resumen,
            'ventasPreventista' => $ventasPreventista,
            'totalComisiones' => $totalComisiones,
            'totalMenosPonderadoIVA' => $totalMenosPonderadoIVA
        ];
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

function obtenerVentasPorProveedor($pdo, $startDate, $endDate) {
    try {


        // 🔹 Consulta SQL con filtro de fechas
        $sqlProveedor = "SELECT
            p.descripcion AS Proveedor,
            SUM(c.Item_Cant_UM1) AS CantidadArticulos,
            SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta
        FROM comprobantes c
        JOIN detallereporte d ON c.detalleReporte_id = d.id
        JOIN proveedores p ON c.Articulo_Prov_Habitual_Cod = p.cod_proveedor
        WHERE d.fecha BETWEEN :startDate AND :endDate
        GROUP BY p.descripcion
        ORDER BY TotalVenta DESC";

        $stmt = $pdo->prepare($sqlProveedor);
        $stmt->execute([
            ':startDate' => $startDate,
            ':endDate' => $endDate
        ]);

        $ventasProveedor = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $ventasProveedor ?: []; // Si no hay datos, devuelve un array vacío
    } catch (Exception $e) {
        return ['error' => $e->getMessage()]; // Retorna un mensaje de error
    }
}
function obtenerVentasPreventistaProveedor($pdo, $startDate, $endDate) {
    try {
       
        // 🔹 Consulta SQL con filtro de rango de fechas
        $sqlPreventistaProveedor = "SELECT
            u.nombre AS Preventista,
            p.descripcion AS Proveedor,
            SUM(c.Item_Cant_UM1) AS CantidadArticulos,
            SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta
        FROM comprobantes c
        JOIN detallereporte d ON c.detalleReporte_id = d.id
        JOIN proveedores p ON c.Articulo_Prov_Habitual_Cod = p.cod_proveedor
        JOIN usuarios u ON TRIM(c.Comp_Vendedor_Cod) = TRIM(u.usuario)
        WHERE d.fecha BETWEEN :startDate AND :endDate
        GROUP BY u.nombre, p.descripcion
        ORDER BY u.nombre, TotalVenta DESC";

        $stmt = $pdo->prepare($sqlPreventistaProveedor);
        $stmt->execute([
            ':startDate' => $startDate,
            ':endDate' => $endDate
        ]);

        $ventasPreventistaProveedor = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $ventasPreventistaProveedor ?: []; // Retorna un array vacío si no hay datos
    } catch (Exception $e) {
        return ['error' => $e->getMessage()]; // Retorna mensaje de error en caso de fallo
    }
}
function obtenerArticulosMasVendidosPorPreventista($pdo, $startDate, $endDate) {
    try {
        // 🔹 Validar formato de fechas (YYYY-MM-DD)
       

        // 🔹 Consulta SQL con rango de fechas
        $sqlArticulosPreventista = "SELECT
            u.nombre AS Preventista,  
            c.Item_Articulo_Cod_Gen AS CodigoArticulo,
            a.descripcion AS Descripcion,
            p.descripcion AS Proveedor,
            SUM(c.Item_Cant_UM1) AS Cantidad,
            SUM(c.Item_Impte_Total_mon_Emision) AS MontoTotal
        FROM comprobantes c
        JOIN detallereporte d ON c.detalleReporte_id = d.id
        JOIN proveedores p ON TRIM(c.Articulo_Prov_Habitual_Cod) = TRIM(p.cod_proveedor)
        JOIN articulos a ON TRIM(c.Item_Articulo_Cod_Gen) = TRIM(a.codBejerman)
        JOIN usuarios u ON TRIM(c.Comp_Vendedor_Cod) = TRIM(u.usuario)  
        WHERE d.fecha BETWEEN :startDate AND :endDate
        GROUP BY Preventista, c.Item_Articulo_Cod_Gen, a.descripcion, p.descripcion
        ORDER BY Preventista, Cantidad DESC";

        $stmt = $pdo->prepare($sqlArticulosPreventista);
        $stmt->execute([
            ':startDate' => $startDate,
            ':endDate' => $endDate
        ]);

        $articulosMasVendidosPreventista = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $articulosMasVendidosPreventista ?: []; // Retorna un array vacío si no hay datos
    } catch (Exception $e) {
        return ['error' => $e->getMessage()]; // Retorna mensaje de error
    }
}


// Verificar método y manejar peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $accion = $_POST['accion'];

    try {
        switch ($accion) {
            case 'consultarResumenVentas':
                // Obtener fechas desde la petición
                $startDate = $_POST['startDate'] ?? null;
                $endDate = $_POST['endDate'] ?? null;

                // Validar si existen registros en el rango de fechas
                if (!existeDetalleReporte($pdo, $startDate, $endDate)) {
                    throw new Exception('No se encontraron registros en el rango de fechas especificado.');
                }

                // Consultar y devolver resumen de ventas y preventistas
                $resultado = consultarResumenVentas($pdo, $startDate, $endDate);
                echo json_encode($resultado);
                break;
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
             case 'consultarVentasPorProveedor':
                // Obtener fechas desde la petición
                $startDate = $_POST['startDate'] ?? null;
                $endDate = $_POST['endDate'] ?? null;

                // Validar si existen registros en el rango de fechas
                if (!existeDetalleReporte($pdo, $startDate, $endDate)) {
                    throw new Exception('No se encontraron registros en el rango de fechas especificado.');
                }

                // Consultar y devolver ventas por proveedor
                $resultado = obtenerVentasPorProveedor($pdo, $startDate, $endDate);
                echo json_encode($resultado);
                break;
            case 'consultarVentasPreventistaProveedor':
                // Obtener fechas desde la petición
                $startDate = $_POST['startDate'] ?? null;
                $endDate = $_POST['endDate'] ?? null;

                // Validar si existen registros en el rango de fechas
                if (!existeDetalleReporte($pdo, $startDate, $endDate)) {
                    throw new Exception('No se encontraron registros en el rango de fechas especificado.');
                }

                // Consultar y devolver ventas por preventista y proveedor
                $resultado = obtenerVentasPreventistaProveedor($pdo, $startDate, $endDate);
                echo json_encode($resultado);
                break;
            case 'consultarArticulosMasVendidosPorPreventista':
                // Obtener fechas desde la petición
                $startDate = $_POST['startDate'] ?? null;
                $endDate = $_POST['endDate'] ?? null;

                // Validar si existen registros en el rango de fechas
                if (!existeDetalleReporte($pdo, $startDate, $endDate)) {
                    throw new Exception('No se encontraron registros en el rango de fechas especificado.');
                }

                // Consultar y devolver artículos más vendidos por preventista
                $resultado = obtenerArticulosMasVendidosPorPreventista($pdo, $startDate, $endDate);
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