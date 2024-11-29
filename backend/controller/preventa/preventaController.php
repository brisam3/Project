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

// Función para verificar si hay registros en detalleReporte con la fecha actual
function existeDetalleReporte($pdo, $today) {
    $sql = "SELECT COUNT(*) as total FROM detallereporte WHERE fecha = :today";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':today' => $today]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result && $result['total'] > 0;
}

try {
    // Crear la conexión usando el método getConnection
    $database = new Database();
    $pdo = $database->getConnection();  // Obtener la conexión a la base de datos

    // Verificar si hay registros con la fecha actual
    if (!existeDetalleReporte($pdo, $today)) {
        // No hay registros, devolver respuesta vacía
        echo json_encode([
            "error" => "No se encontraron registros para la fecha actual.",
            "mostrarMensaje" => true
        ]);
        exit; // Salir sin ejecutar el resto de las consultas
    }

    // Consultar resumen
    $sqlResumen = "SELECT
        COUNT(DISTINCT c.Comp_Ppal) AS CantidadBoletas,
        COUNT(DISTINCT c.Comp_Cliente_Cod) AS CantidadClientes,
        SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta
    FROM comprobantes c
    JOIN detallereporte d ON c.detalleReporte_id = d.id
    WHERE d.fecha = :today";

    $stmt = $pdo->prepare($sqlResumen);
    $stmt->execute([':today' => $today]);
    $resumen = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resumen || $resumen['TotalVenta'] == 0) {
        $resumen = [
            'TotalVenta' => 0,
            'CantidadClientes' => 0,
            'CantidadBoletas' => 0,
            'TicketPromedio' => 0
        ];
        $mostrarMensaje = true;
    } else {
        $mostrarMensaje = false;
        // Asegurarse de que no hay división por cero
        $resumen['TicketPromedio'] = $resumen['CantidadBoletas'] > 0 ? $resumen['TotalVenta'] / $resumen['CantidadBoletas'] : 0;
    }


            // Calcular el total vendido menos el IVA
        $totalVentaMenosIVA = $resumen['TotalVenta'] / 1.21;

        // Calcular el total vendido menos el ponderado de artículos
        $totalVentaMenosPonderado = $resumen['TotalVenta'] * 0.47;
    // Consultar ventas por preventista
    $sqlPreventista = "SELECT
    u.nombre AS Preventista,  -- Obtenemos el nombre directamente de la tabla usuarios
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
    JOIN usuarios u ON TRIM(c.Comp_Vendedor_Cod) = TRIM(u.usuario)  -- Relación con espacios eliminados
    WHERE d.fecha = :today
    GROUP BY u.nombre  -- Agrupamos por el nombre del preventista
    ORDER BY TotalVenta DESC;
    ";

    $stmt = $pdo->prepare($sqlPreventista);
    $stmt->execute([':today' => $today]);
    $ventasPreventista = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$ventasPreventista) {
        $ventasPreventista = [];
    }

    function addCrown(&$ventasPreventista)
{
    if (empty($ventasPreventista)) {
        return;
    }

    // Campos a analizar
    $fields = ['CantidadBoletas', 'CantidadClientes'];

    // Encontrar los valores máximos para cada campo
    $maxValues = [];
    foreach ($fields as $field) {
        $maxValues[$field] = max(array_column($ventasPreventista, $field));
    }

    // Agregar coronita a los valores máximos
    foreach ($ventasPreventista as &$venta) {
        foreach ($fields as $field) {
            if ($venta[$field] == $maxValues[$field]) {
                $venta[$field] .= ' 👑'; // Agrega coronita
            }
        }
    }
}

// Ejemplo de uso:
addCrown($ventasPreventista);


    // Sumar las comisiones diarias
    $totalComisiones = array_sum(array_column($ventasPreventista, 'Comision'));

    // Calcular el total menos el ponderado y el IVA
    $totalMenosPonderadoIVA = $resumen['TotalVenta'] * 0.32;

   
    // Consultar datos de ventas por proveedor
    $sqlProveedor = "SELECT
    p.descripcion AS Proveedor,
    SUM(c.Item_Cant_UM1) AS CantidadArticulos,
    SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta
    FROM comprobantes c
    JOIN detallereporte d ON c.detalleReporte_id = d.id
    JOIN proveedores p ON c.Articulo_Prov_Habitual_Cod = p.cod_proveedor
    WHERE d.fecha = :today
    GROUP BY p.descripcion
    ORDER BY TotalVenta DESC";

    $stmt = $pdo->prepare($sqlProveedor);
    $stmt->execute([':today' => $today]);
    $ventasProveedor = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$ventasProveedor) {
    $ventasProveedor = [];
    }

    $sqlPreventistaProveedor = "SELECT
    u.nombre AS Preventista,
    p.descripcion AS Proveedor,
    SUM(c.Item_Cant_UM1) AS CantidadArticulos,
    SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta
    FROM comprobantes c
    JOIN detallereporte d ON c.detalleReporte_id = d.id
    JOIN proveedores p ON c.Articulo_Prov_Habitual_Cod = p.cod_proveedor
    JOIN usuarios u ON TRIM(c.Comp_Vendedor_Cod) = TRIM(u.usuario)
    WHERE d.fecha = :today
    GROUP BY u.nombre, p.descripcion
    ORDER BY u.nombre, TotalVenta DESC";

    $stmt = $pdo->prepare($sqlPreventistaProveedor);
    $stmt->execute([':today' => $today]);
    $ventasPreventistaProveedor = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$ventasPreventistaProveedor) {
        $ventasPreventistaProveedor = [];
    }

    $sqlArticulos = "SELECT
    c.Item_Articulo_Cod_Gen AS CodigoArticulo,
    a.descripcion AS Descripcion,
    p.descripcion AS Proveedor,
    SUM(c.Item_Cant_UM1) AS Cantidad,
    SUM(c.Item_Impte_Total_mon_Emision) AS MontoTotal
    FROM comprobantes c
    JOIN detallereporte d ON c.detalleReporte_id = d.id
    JOIN proveedores p ON c.Articulo_Prov_Habitual_Cod = p.cod_proveedor
    Join articulos a ON c.Item_Articulo_Cod_Gen = a.codBejerman
    WHERE d.fecha = :today
    GROUP BY c.Item_Articulo_Cod_Gen, a.descripcion, p.descripcion
    ORDER BY Cantidad DESC";

    $stmt = $pdo->prepare($sqlArticulos);
    $stmt->execute([':today' => $today]);
    $articulosMasVendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$articulosMasVendidos) {
        $articulosMasVendidos = [];
    }

    /* 
    $sqlCajasYogures = "SELECT
        u.usuario AS Preventista,                       -- Nombre del preventista obtenido desde la tabla `usuarios`
        c.Item_Articulo_Cod_Gen AS CodigoYogur,        -- Código del yogur (kit)
        a.descripcion AS Descripcion,                   -- Descripción del yogur
        SUM(c.Item_Cant_UM1) AS UnidadesVendidas,      -- Suma de unidades vendidas
        SUM(c.Item_Cant_UM1) AS CajasVendidas          -- Cada unidad representa una caja vendida
    FROM comprobantes c
    JOIN detallereporte d ON c.detalleReporte_id = d.id
    JOIN articulos a ON c.Item_Articulo_Cod_Gen = a.codBejerman
    JOIN usuarios u ON c.Comp_Vendedor_Cod = u.usuario -- JOIN con la tabla `usuarios` para obtener el nombre del preventista
    WHERE d.fecha BETWEEN :startDate AND :endDate
    AND c.Item_Articulo_Cod_Gen IN ('KIT0034', 'KIT0035') -- Filtra los códigos de los kits de yogures
    GROUP BY u.usuario, c.Item_Articulo_Cod_Gen, a.descripcion -- Agrupar por preventista, código de yogur y descripción
    ORDER BY Preventista, CajasVendidas DESC
    ";

    $stmt = $pdo->prepare($sqlCajasYogures);
    $stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
    $cajasYogures = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$cajasYogures) {
        $cajasYogures = [];
    }

    */


    $sqlArticulosPreventista = "SELECT
    u.nombre AS Preventista,  -- Obtener el nombre del preventista de la tabla usuarios
    c.Item_Articulo_Cod_Gen AS CodigoArticulo,
    a.descripcion AS Descripcion,
    p.descripcion AS Proveedor,
    SUM(c.Item_Cant_UM1) AS Cantidad,
    SUM(c.Item_Impte_Total_mon_Emision) AS MontoTotal
    FROM comprobantes c
    JOIN detallereporte d ON c.detalleReporte_id = d.id
    JOIN proveedores p ON  TRIM(c.Articulo_Prov_Habitual_Cod) =  TRIM(p.cod_proveedor)
    JOIN articulos a ON  TRIM(c.Item_Articulo_Cod_Gen) =  TRIM(a.codBejerman)
    JOIN usuarios u ON  TRIM(c.Comp_Vendedor_Cod) = u.usuario  -- Hacemos JOIN con la tabla usuarios
    WHERE d.fecha = :today
    GROUP BY Preventista, c.Item_Articulo_Cod_Gen, a.descripcion, p.descripcion
    ORDER BY Preventista, Cantidad DESC
    ";

    $stmt = $pdo->prepare($sqlArticulosPreventista);
    $stmt->execute([':today' => $today]);
    $articulosMasVendidosPreventista = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!$articulosMasVendidosPreventista) {
        $articulosMasVendidosPreventista = [];
    }
   


    // Respuesta en JSON
    echo json_encode([
        "resumen" => $resumen,
        "ventasPreventista" => $ventasPreventista,
        "totalComisiones" => $totalComisiones,
        "totalMenosPonderadoIVA" => $totalMenosPonderadoIVA,
        "totalVentaMenosIVA" => $totalVentaMenosIVA,
        "totalVentaMenosPonderado"=>$totalVentaMenosPonderado,
        "mostrarMensaje" => $mostrarMensaje,
        "ventasProveedor" => $ventasProveedor,
        "ventasPreventistaProveedor" => $ventasPreventistaProveedor,
        "articulosMasVendidos" => $articulosMasVendidos,
       /*  "cajasYogures" => $cajasYogures, */
        "articulosMasVendidosPreventista" => $articulosMasVendidosPreventista,
        "today"=>$today,

    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en el servidor: " . $e->getMessage()]);
}

?>