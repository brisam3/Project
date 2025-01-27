<?php
session_start();
require '../../vendor/autoload.php'; // Asegúrate de que Composer haya instalado PHPSpreadsheet

// Definir las credenciales de conexión para el entorno local
$sconLocal = "mysql:dbname=wolchuk;host=localhost";
$suserLocal = 'root';
$spassLocal = '';

// Definir las credenciales de conexión para el entorno de hosting
$sconHost = "mysql:dbname=u277628716_wol;host=localhost";
$suserHost = 'u277628716_wol';
$spassHost = 'Cf4b1a7123';

// Determinar si el script se está ejecutando en un entorno local
$isLocal = ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1');

// Seleccionar las credenciales de conexión basadas en el entorno
$scon = $isLocal ? $sconLocal : $sconHost;
$suser = $isLocal ? $suserLocal : $suserHost;
$spass = $isLocal ? $spassLocal : $spassHost;
$dbName = $isLocal ? "wol" : "u277628716_wol";

$_SESSION["bd"] = $dbName;
$msg = '';

try {
    $pdo = new PDO(
        $scon,
        $suser,
        $spass,
        array(
            PDO::ATTR_PERSISTENT => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        )
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $msg = 'conexion_ok';
} catch (PDOException $e) {
    $msg = 'conexion_cancel: ' . $e->getMessage();
    echo "Error al conectar a la base de datos. " . $e->getMessage();
    exit;
}

// Variables para almacenar los datos
$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : date('Y-m-d');
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : date('Y-m-d');

// Obtener el resumen de ventas del rango de fechas seleccionado
$sqlResumen = "SELECT
    COUNT(DISTINCT c.Comp_Ppal) AS CantidadBoletas,
    COUNT(DISTINCT c.Comp_Cliente_Cod) AS CantidadClientes,
    SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta
FROM comprobantes c
JOIN detallereporte d ON c.detalleReporte_id = d.id
WHERE d.fecha BETWEEN :startDate AND :endDate";

$stmt = $pdo->prepare($sqlResumen);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
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

// Mapear los números de preventistas a nombres
$preventistas = [
    101 => 'Mica',
    102 => 'Gustavo',
    103 => 'Chilo',
    104 => 'Alex',
    105 => 'Diego',
    106 => 'Cristian',
    107 => 'Marianela',
    108 => 'Guillermo',
    120 => 'Daniel',
    121 => 'Soledad',
    122 => 'Esteban'
];

// Calcular el total vendido menos el IVA
$totalVentaMenosIVA = $resumen['TotalVenta'] - ($resumen['TotalVenta'] * 0.21);

// Calcular el total vendido menos el ponderado de artículos
$totalVentaMenosPonderado = $resumen['TotalVenta'] * 0.47;

// Consultar datos de ventas por preventista
// Consultar datos de ventas por preventista
$sqlPreventista = "
SELECT
    CASE 
         WHEN c.Comp_Vendedor_Cod = 101 THEN 'Mica'
        WHEN c.Comp_Vendedor_Cod = 102 THEN 'Gustavo'
        WHEN c.Comp_Vendedor_Cod = 103 THEN 'Chilo'
        WHEN c.Comp_Vendedor_Cod = 104 THEN 'Alex'
        WHEN c.Comp_Vendedor_Cod = 105 THEN 'Diego'
        WHEN c.Comp_Vendedor_Cod = 106 THEN 'Cristian'
        WHEN c.Comp_Vendedor_Cod = 107 THEN 'Marianela'
        WHEN c.Comp_Vendedor_Cod = 108 THEN 'Guillermo'
        WHEN c.Comp_Vendedor_Cod = 120 THEN 'Daniel'
        WHEN c.Comp_Vendedor_Cod = 121 THEN 'Soledad'
        ELSE 'Esteban'
    END AS Preventista,
    SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta,
    COALESCE(SUM(dv.importe_total), 0) AS TotalDevoluciones
FROM comprobantes c
LEFT JOIN (
    SELECT codigo_vendedor, SUM(importe_total) AS importe_total
    FROM devoluciones
    WHERE fecha_emision BETWEEN :startDate AND :endDate
    GROUP BY codigo_vendedor
) dv ON c.Comp_Vendedor_Cod = dv.codigo_vendedor
JOIN detallereporte dr ON c.detalleReporte_id = dr.id
WHERE dr.fecha BETWEEN :startDate AND :endDate
GROUP BY Preventista
ORDER BY TotalVenta DESC";

$stmt = $pdo->prepare($sqlPreventista);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
$ventasPreventista = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$ventasPreventista) {
    $ventasPreventista = [];
}

// Ahora calcula la comisión en PHP
foreach ($ventasPreventista as &$venta) {
    $ventaNeta = $venta['TotalVenta'] - $venta['TotalDevoluciones'];
    $ventaSinIVA = $ventaNeta / 1.21;
    $venta['Comision'] = $ventaSinIVA * 0.04;
}



$stmt = $pdo->prepare($sqlPreventista);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
$ventasPreventista = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (!$ventasPreventista) {
    $ventasPreventista = [];
}

// Sumar las comisiones del rango de fechas seleccionado
$totalComisiones = array_sum(array_column($ventasPreventista, 'Comision'));

// Calcular el total menos el ponderado y el IVA
$totalMenosPonderadoIVA = $resumen['TotalVenta'] * 0.32;

// Consultar datos de ventas por proveedor
$sqlProveedor = "SELECT
    p.descripcion AS Proveedor,
    SUM(c.Item_Cant_UM1) AS CantidadArticulos,
    SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta,
    COUNT(DISTINCT c.Comp_Cliente_Cod) AS CantidadClientes
FROM comprobantes c
JOIN detallereporte d ON c.detalleReporte_id = d.id
JOIN proveedores p ON c.Articulo_Prov_Habitual_Cod = p.cod_proveedor
WHERE d.fecha BETWEEN :startDate AND :endDate
GROUP BY p.descripcion
ORDER BY TotalVenta DESC";



$stmt = $pdo->prepare($sqlProveedor);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
$ventasProveedor = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$ventasProveedor) {
    $ventasProveedor = [];
}

// Consultar datos de ventas por preventista y proveedor
$sqlPreventistaProveedor = "SELECT
    CASE 
        WHEN c.Comp_Vendedor_Cod = 101 THEN 'Mica'
        WHEN c.Comp_Vendedor_Cod = 102 THEN 'Gustavo'
        WHEN c.Comp_Vendedor_Cod = 103 THEN 'Chilo'
        WHEN c.Comp_Vendedor_Cod = 104 THEN 'Alex'
        WHEN c.Comp_Vendedor_Cod = 105 THEN 'Diego'
        WHEN c.Comp_Vendedor_Cod = 106 THEN 'Cristian'
        WHEN c.Comp_Vendedor_Cod = 107 THEN 'Marianela'
        WHEN c.Comp_Vendedor_Cod = 108 THEN 'Guillermo'
        WHEN c.Comp_Vendedor_Cod = 120 THEN 'Daniel'
        WHEN c.Comp_Vendedor_Cod = 121 THEN 'Soledad'
        ELSE 'Esteban'
    END AS Preventista,
    p.descripcion AS Proveedor,
    SUM(c.Item_Cant_UM1) AS CantidadArticulos,
    SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta
FROM comprobantes c
JOIN detallereporte d ON c.detalleReporte_id = d.id
JOIN proveedores p ON c.Articulo_Prov_Habitual_Cod = p.cod_proveedor
WHERE d.fecha BETWEEN :startDate AND :endDate
GROUP BY Preventista, p.descripcion
ORDER BY Preventista, TotalVenta DESC";

$stmt = $pdo->prepare($sqlPreventistaProveedor);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
$ventasPreventistaProveedor = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$ventasPreventistaProveedor) {
    $ventasPreventistaProveedor = [];
}

// Consultar los artículos más vendidos
$sqlArticulos = "SELECT
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

$stmt = $pdo->prepare($sqlArticulos);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
$articulosMasVendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$articulosMasVendidos) {
    $articulosMasVendidos = [];
}

// Consultar los artículos más vendidos por preventista
$sqlArticulosPreventista = "SELECT
    CASE 
        WHEN c.Comp_Vendedor_Cod = 101 THEN 'Mica'
        WHEN c.Comp_Vendedor_Cod = 102 THEN 'Gustavo'
        WHEN c.Comp_Vendedor_Cod = 103 THEN 'Chilo'
        WHEN c.Comp_Vendedor_Cod = 104 THEN 'Alex'
        WHEN c.Comp_Vendedor_Cod = 105 THEN 'Diego'
        WHEN c.Comp_Vendedor_Cod = 106 THEN 'Cristian'
        WHEN c.Comp_Vendedor_Cod = 107 THEN 'Marianela'
        WHEN c.Comp_Vendedor_Cod = 108 THEN 'Guillermo'
        WHEN c.Comp_Vendedor_Cod = 120 THEN 'Daniel'
        WHEN c.Comp_Vendedor_Cod = 121 THEN 'Soledad'
        ELSE 'Esteban'
    END AS Preventista,
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
GROUP BY Preventista, c.Item_Articulo_Cod_Gen, a.descripcion, p.descripcion
ORDER BY Preventista, Cantidad DESC";

$stmt = $pdo->prepare($sqlArticulosPreventista);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
$articulosMasVendidosPreventista = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$articulosMasVendidosPreventista) {
    $articulosMasVendidosPreventista = [];
}

// Consultar ventas diarias por preventista
$sqlVentasDiariasVendedor = "SELECT
    CASE 
        WHEN c.Comp_Vendedor_Cod = 101 THEN 'Mica'
        WHEN c.Comp_Vendedor_Cod = 102 THEN 'Gustavo'
        WHEN c.Comp_Vendedor_Cod = 103 THEN 'Chilo'
        WHEN c.Comp_Vendedor_Cod = 104 THEN 'Alex'
        WHEN c.Comp_Vendedor_Cod = 105 THEN 'Diego'
        WHEN c.Comp_Vendedor_Cod = 106 THEN 'Cristian'
        WHEN c.Comp_Vendedor_Cod = 107 THEN 'Marianela'
        WHEN c.Comp_Vendedor_Cod = 108 THEN 'Guillermo'
        WHEN c.Comp_Vendedor_Cod = 120 THEN 'Daniel'
        WHEN c.Comp_Vendedor_Cod = 121 THEN 'Soledad'
        ELSE 'Esteban'
    END AS Preventista,
    d.fecha AS Fecha,
    SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta
FROM comprobantes c
JOIN detallereporte d ON c.detalleReporte_id = d.id
WHERE d.fecha BETWEEN :startDate AND :endDate
GROUP BY Preventista, d.fecha
ORDER BY d.fecha, Preventista";

$stmt = $pdo->prepare($sqlVentasDiariasVendedor);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
$ventasDiariasVendedor = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$ventasDiariasVendedor) {
    $ventasDiariasVendedor = [];
}

// Consultar el monto total de ventas por fecha
$sqlVentasDiarias = "SELECT
    d.fecha AS Fecha,
    SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta
FROM comprobantes c
JOIN detallereporte d ON c.detalleReporte_id = d.id
WHERE d.fecha BETWEEN :startDate AND :endDate
GROUP BY d.fecha
ORDER BY d.fecha";

$stmt = $pdo->prepare($sqlVentasDiarias);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
$ventasDiarias = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$ventasDiarias) {
    $ventasDiarias = [];
}

// Consultar datos de ventas por zona
$sqlVentasZona = "SELECT
    c.Comp_Cliente_Zona AS Zona,
    SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta
FROM comprobantes c
JOIN detallereporte d ON c.detalleReporte_id = d.id
WHERE d.fecha BETWEEN :startDate AND :endDate
GROUP BY c.Comp_Cliente_Zona
ORDER BY TotalVenta DESC";

$stmt = $pdo->prepare($sqlVentasZona);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
$ventasZona = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$ventasZona) {
    $ventasZona = [];
}

// Consultar artículos comprados por cada cliente único
$sqlArticulosCliente = "SELECT
    c.Comp_Cliente_Cod AS CodigoCliente,
    c.Comp_Cliente_RazonSocial AS NombreCliente,
    c.Item_Articulo_Cod_Gen AS CodigoArticulo,
    a.descripcion AS Descripcion,
    SUM(c.Item_Cant_UM1) AS Cantidad,
    SUM(c.Item_Impte_Total_mon_Emision) AS MontoTotal
FROM comprobantes c
JOIN detallereporte d ON c.detalleReporte_id = d.id
JOIN articulos a ON c.Item_Articulo_Cod_Gen = a.codBejerman
WHERE d.fecha BETWEEN :startDate AND :endDate
GROUP BY c.Comp_Cliente_Cod, c.Comp_Cliente_RazonSocial, c.Item_Articulo_Cod_Gen, a.descripcion
ORDER BY c.Comp_Cliente_Cod, c.Item_Articulo_Cod_Gen";

$stmt = $pdo->prepare($sqlArticulosCliente);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
$articulosCliente = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$articulosCliente) {
    $articulosCliente = [];
}

// Consultar compras por cliente y proveedor
$sqlComprasClienteProveedor = "SELECT
    c.Comp_Cliente_Cod AS CodigoCliente,
    c.Comp_Cliente_RazonSocial AS NombreCliente,
    p.descripcion AS Proveedor,
    SUM(c.Item_Cant_UM1) AS Cantidad,
    SUM(c.Item_Impte_Total_mon_Emision) AS MontoTotal
FROM comprobantes c
JOIN detallereporte d ON c.detalleReporte_id = d.id
JOIN proveedores p ON c.Articulo_Prov_Habitual_Cod = p.cod_proveedor
WHERE d.fecha BETWEEN :startDate AND :endDate
GROUP BY c.Comp_Cliente_Cod, c.Comp_Cliente_RazonSocial, p.descripcion
ORDER BY c.Comp_Cliente_Cod, p.descripcion";

$stmt = $pdo->prepare($sqlComprasClienteProveedor);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
$comprasClienteProveedor = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$comprasClienteProveedor) {
    $comprasClienteProveedor = [];
}

// Calcular el monto total de devoluciones
$sqlTotalDevoluciones = "SELECT SUM(d.importe_total) AS TotalDevoluciones
    FROM devoluciones d
    JOIN detalledevoluciones dd ON d.rela_detalle = dd.id
    WHERE dd.fecha BETWEEN :startDate AND :endDate";

$stmt = $pdo->prepare($sqlTotalDevoluciones);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
$totalDevoluciones = $stmt->fetch(PDO::FETCH_ASSOC)['TotalDevoluciones'] ?? 0;


// Consulta SQL para obtener los artículos devueltos con causa de emisión "Sin Mercaderia"
$sqlArticulosDevolucionesSinMercaderia = "
SELECT 
    descripcion_articulo AS DescripcionArticulo,
    causa_emision AS Causa,
    SUM(cantidad) AS CantidadDevuelta
FROM devoluciones
WHERE causa_emision = 'Sin Mercaderia'
AND fecha_emision BETWEEN :startDate AND :endDate
GROUP BY descripcion_articulo, causa_emision
ORDER BY CantidadDevuelta DESC";

// Preparar y ejecutar la consulta
$stmt = $pdo->prepare($sqlArticulosDevolucionesSinMercaderia);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);

// Obtener los resultados
$articulosDevolucionesSinMercaderia = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si no se encuentran resultados, asegurarse de tener un array vacío
if (!$articulosDevolucionesSinMercaderia) {
    $articulosDevolucionesSinMercaderia = [];
}


$sqlCajasYogures = "
SELECT
        CASE 
            WHEN c.Comp_Vendedor_Cod = 101 THEN 'Mica'
            WHEN c.Comp_Vendedor_Cod = 102 THEN 'Gustavo'
            WHEN c.Comp_Vendedor_Cod = 103 THEN 'Chilo'
            WHEN c.Comp_Vendedor_Cod = 104 THEN 'Alex'
            WHEN c.Comp_Vendedor_Cod = 105 THEN 'Diego'
            WHEN c.Comp_Vendedor_Cod = 106 THEN 'Cristian'
            WHEN c.Comp_Vendedor_Cod = 107 THEN 'Marianela'
            WHEN c.Comp_Vendedor_Cod = 108 THEN 'Guillermo'
            WHEN c.Comp_Vendedor_Cod = 120 THEN 'Daniel'
            WHEN c.Comp_Vendedor_Cod = 121 THEN 'Soledad'
            ELSE 'Esteban'
        END AS Preventista,
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
    AND c.Item_Articulo_Cod_Gen IN ('KIT0034', 'KIT0035') -- Filtrar solo los artículos con los códigos KIT0034 y KIT0035
    GROUP BY Preventista, c.Item_Articulo_Cod_Gen, a.descripcion, p.descripcion
    ORDER BY Preventista, Cantidad DESC
";

// Luego, asegúrate de pasar las fechas al preparar la consulta
$stmt = $pdo->prepare($sqlCajasYogures);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
$cajasYogures = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Luego, asegúrate de pasar las fechas al preparar la consulta



// Consultar ventas y devoluciones por preventista
$sqlPreventistaConDevoluciones = "
SELECT
    CASE 
        WHEN c.Comp_Vendedor_Cod = 101 THEN 'Mica'
        WHEN c.Comp_Vendedor_Cod = 102 THEN 'Gustavo'
        WHEN c.Comp_Vendedor_Cod = 103 THEN 'Chilo'
        WHEN c.Comp_Vendedor_Cod = 104 THEN 'Alex'
        WHEN c.Comp_Vendedor_Cod = 105 THEN 'Diego'
        WHEN c.Comp_Vendedor_Cod = 106 THEN 'Cristian'
        WHEN c.Comp_Vendedor_Cod = 107 THEN 'Marianela'
        WHEN c.Comp_Vendedor_Cod = 108 THEN 'Guillermo'
        WHEN c.Comp_Vendedor_Cod = 120 THEN 'Daniel'
        WHEN c.Comp_Vendedor_Cod = 121 THEN 'Soledad'
        ELSE 'Esteban'
    END AS Preventista,
    COUNT(DISTINCT c.Comp_Ppal) AS CantidadBoletas,
    COUNT(DISTINCT c.Comp_Cliente_Cod) AS CantidadClientes,
    SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta,
    (SUM(c.Item_Impte_Total_mon_Emision) / COUNT(DISTINCT c.Comp_Ppal)) AS TicketPromedio,
    COUNT(DISTINCT c.Item_Articulo_Cod_Gen) AS VariedadArticulos,
    COUNT(DISTINCT c.Articulo_Prov_Habitual_Cod) AS VariedadProveedores,
    (COUNT(DISTINCT c.Item_Articulo_Cod_Gen) / COUNT(DISTINCT c.Comp_Cliente_Cod)) AS PromedioArticulosPorCliente,
    (COUNT(DISTINCT c.Articulo_Prov_Habitual_Cod) / COUNT(DISTINCT c.Comp_Cliente_Cod)) AS PromedioProveedoresPorCliente,

    -- Calcular el total de devoluciones
    COALESCE(
        (SELECT SUM(dv.importe_total)
         FROM devoluciones dv
         JOIN detalledevoluciones ddv ON dv.rela_detalle = ddv.id
         WHERE dv.codigo_vendedor = c.Comp_Vendedor_Cod
         AND ddv.fecha BETWEEN :startDate AND :endDate), 0
    ) AS TotalDevoluciones
FROM comprobantes c
JOIN detallereporte dr ON c.detalleReporte_id = dr.id
WHERE dr.fecha BETWEEN :startDate AND :endDate
GROUP BY Preventista
ORDER BY TotalVenta DESC";



$stmt = $pdo->prepare($sqlPreventistaConDevoluciones);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
$ventasPreventista = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sqlDevolucionesGeneralesPorCausa = "
SELECT
    d.causa_emision AS Causa,
    COUNT(d.id) AS CantidadDevoluciones,
    SUM(d.importe_total) AS MontoTotal
FROM devoluciones d
JOIN detalledevoluciones dd ON d.rela_detalle = dd.id
WHERE dd.fecha BETWEEN :startDate AND :endDate
GROUP BY d.causa_emision
ORDER BY CantidadDevoluciones DESC";

$stmt = $pdo->prepare($sqlDevolucionesGeneralesPorCausa);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
$devolucionesGeneralesPorCausa = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$devolucionesGeneralesPorCausa) {
    $devolucionesGeneralesPorCausa = [];
}



// Consultar devoluciones por preventista y causa de emisión
/*
$sqlDevolucionesPorCausa = "
SELECT
    CASE 
        WHEN d.codigo_vendedor = 101 THEN 'Mica'
        WHEN d.codigo_vendedor = 102 THEN 'Gustavo'
        WHEN d.codigo_vendedor = 103 THEN 'Chilo'
        WHEN d.codigo_vendedor = 104 THEN 'Alex'
        WHEN d.codigo_vendedor = 105 THEN 'Diego'
        WHEN d.codigo_vendedor = 106 THEN 'Cristian'
        WHEN d.codigo_vendedor = 107 THEN 'Marianela'
        WHEN d.codigo_vendedor = 108 THEN 'Guillermo'
        WHEN d.codigo_vendedor = 120 THEN 'Daniel'
        WHEN d.codigo_vendedor = 121 THEN 'Soledad'
        ELSE 'Esteban'
    END AS Preventista,
    d.causa_emision AS Causa,
    COUNT(d.id) AS CantidadDevoluciones,
    SUM(d.importe_total) AS MontoTotal
FROM devoluciones d
JOIN detalledevoluciones dd ON d.rela_detalle = dd.id
WHERE dd.fecha BETWEEN :startDate AND :endDate
GROUP BY Preventista, Causa
ORDER BY Preventista, CantidadDevoluciones DESC";


$stmt = $pdo->prepare($sqlDevolucionesPorCausa);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
$devolucionesPorCausa = $stmt->fetchAll(PDO::FETCH_ASSOC);
*/

// Función para encontrar los valores máximos y agregar coronitas
function addCrown(&$ventasPreventista)
{
    if (empty($ventasPreventista)) {
        return;
    }

    $fields = ['CantidadBoletas', 'CantidadClientes', 'TotalVenta', 'TicketPromedio', 'VariedadArticulos', 'VariedadProveedores'];
    $maxValues = [];

    // Encontrar los valores máximos para cada campo
    foreach ($fields as $field) {
        $maxValues[$field] = max(array_column($ventasPreventista, $field));
    }

    // Agregar coronita a los valores máximos
    foreach ($ventasPreventista as &$venta) {
        foreach ($fields as $field) {
            if ($venta[$field] == $maxValues[$field]) {
                $venta[$field] .= '👑';
            }
        }
    }

    // Añadir columnas para ordenamiento sin coronita
    foreach ($ventasPreventista as &$venta) {
        $venta['CantidadBoletasNum'] = floatval(preg_replace('/[^0-9.]/', '', $venta['CantidadBoletas']));
        $venta['CantidadClientesNum'] = floatval(preg_replace('/[^0-9.]/', '', $venta['CantidadClientes']));
        $venta['TotalVentaNum'] = floatval(preg_replace('/[^0-9.]/', '', $venta['TotalVenta']));
        $venta['TicketPromedioNum'] = floatval(preg_replace('/[^0-9.]/', '', $venta['TicketPromedio']));
        $venta['VariedadArticulosNum'] = floatval(preg_replace('/[^0-9.]/', '', $venta['VariedadArticulos']));
        $venta['VariedadProveedoresNum'] = floatval(preg_replace('/[^0-9.]/', '', $venta['VariedadProveedores']));
    }
}

addCrown($ventasPreventista);

$sqlArticulosDevolucionesCausasEspecificas = "
SELECT 
    descripcion_articulo AS DescripcionArticulo,
    causa_emision AS Causa,
    SUM(cantidad) AS CantidadDevuelta
FROM devoluciones
WHERE causa_emision IN ('Sin Dinero', 'Ni pidió')
AND fecha_emision BETWEEN :startDate AND :endDate
GROUP BY descripcion_articulo, causa_emision
ORDER BY CantidadDevuelta DESC";

// Preparar y ejecutar la consulta
$stmt = $pdo->prepare($sqlArticulosDevolucionesCausasEspecificas);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);

// Obtener los resultados
$articulosDevolucionesCausasEspecificas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si no se encuentran resultados, asegurarse de tener un array vacío
if (!$articulosDevolucionesCausasEspecificas) {
    $articulosDevolucionesCausasEspecificas = [];
}


// Consulta para obtener las ventas totales por cliente
$sqlVentasPorCliente = "
    SELECT
        c.Comp_Cliente_Cod AS CodigoCliente,
        c.Comp_Cliente_RazonSocial AS NombreCliente,
        SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta
    FROM comprobantes c
    JOIN detallereporte d ON c.detalleReporte_id = d.id
    WHERE d.fecha BETWEEN :startDate AND :endDate
    GROUP BY c.Comp_Cliente_Cod, c.Comp_Cliente_RazonSocial
    ORDER BY TotalVenta DESC";

$stmt = $pdo->prepare($sqlVentasPorCliente);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
$ventasPorCliente = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$ventasPorCliente) {
    $ventasPorCliente = [];
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Codescandy" name="author">
    <title>Resultados de Ventas</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../freshcart-1-2-1/dist/assets/images/favicon/favicon.ico">
    <link href="../../freshcart-1-2-1/dist/assets/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../../freshcart-1-2-1/dist/assets/libs/feather-webfont/dist/feather-icons.css" rel="stylesheet">
    <link href="../../freshcart-1-2-1/dist/assets/libs/simplebar/dist/simplebar.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../freshcart-1-2-1/dist/assets/css/theme.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div>
        <nav class="navbar navbar-expand-lg navbar-glass">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div class="d-flex align-items-center">
                        <a class="text-inherit d-block d-xl-none me-4" data-bs-toggle="offcanvas"
                            href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                class="bi bi-text-indent-right" viewBox="0 0 16 16">
                                <path
                                    d="M2 3.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm10.646 2.146a.5.5 0 0 1 .708.708L11.707 8l1.647 1.646a.5.5 0 0 1-.708.708l-2-2a.5.5 0 0 1 0-.708l2-2zM2 6.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z" />
                            </svg>
                        </a>
                        <form role="search">
                            <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                        </form>
                    </div>
                    <div>
                        <ul class="list-unstyled d-flex align-items-center mb-0 ms-5 ms-lg-0">
                            <li class="dropdown-center">
                                <a class="position-relative btn-icon btn-ghost-secondary btn rounded-circle" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-bell fs-5"></i>
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger mt-2 ms-n2">
                                        2
                                        <span class="visually-hidden">unread messages</span>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg p-0 border-0">
                                    <div class="border-bottom p-5 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">Notifications</h5>
                                            <p class="mb-0 small">You have 2 unread messages</p>
                                        </div>
                                        <a href="#!" class="text-muted">
                                            <a href="#" class="btn btn-ghost-secondary btn-icon rounded-circle"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                data-bs-title="Mark all as read">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                    fill="currentColor" class="bi bi-check2-all text-success"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M12.354 4.354a.5.5 0 0 0-.708-.708L11.707 8l1.647 1.646a.5.5 0 0 0 .708.708l-2-2a.5.5 0 0 0 0-.708l2-2zM2 6.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z" />
                                                </svg>
                                            </a>
                                        </a>
                                    </div>
                                    <div data-simplebar style="height: 250px">
                                        <ul class="list-group list-group-flush notification-list-scroll fs-6">
                                            <li class="list-group-item px-5 py-4 list-group-item-action active">
                                                <a href="#!" class="text-muted">
                                                    <div class="d-flex">
                                                        <img src="../../freshcart-1-2-1/dist/assets/images/avatar/avatar-1.jpg"
                                                            alt="" class="avatar avatar-md rounded-circle">
                                                        <div class="ms-4">
                                                            <p class="mb-1">
                                                                <span class="text-dark">Your order is placed</span>
                                                                waiting for shipping
                                                            </p>
                                                            <span>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                                    height="12" fill="currentColor"
                                                                    class="bi bi-clock text-muted" viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                                                    <path
                                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                                                                </svg>
                                                                <small class="ms-2">1 minute ago</small>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="list-group-item px-5 py-4 list-group-item-action">
                                                <a href="#!" class="text-muted">
                                                    <div class="d-flex">
                                                        <img src="../../freshcart-1-2-1/dist/assets/images/avatar/avatar-5.jpg"
                                                            alt="" class="avatar avatar-md rounded-circle">
                                                        <div class="ms-4">
                                                            <p class="mb-1">
                                                                <span class="text-dark">Jitu Chauhan</span>
                                                                answered to your pending order list with notes
                                                            </p>
                                                            <span>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                                    height="12" fill="currentColor"
                                                                    class="bi bi-clock text-muted" viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                                                    <path
                                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                                                                </svg>
                                                                <small class="ms-2">2 days ago</small>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="list-group-item px-5 py-4 list-group-item-action">
                                                <a href="#!" class="text-muted">
                                                    <div class="d-flex">
                                                        <img src="../../freshcart-1-2-1/dist/assets/images/avatar/avatar-2.jpg"
                                                            alt="" class="avatar avatar-md rounded-circle">
                                                        <div class="ms-4">
                                                            <p class="mb-1">
                                                                <span class="text-dark">You have new messages</span>
                                                                2 unread messages
                                                            </p>
                                                            <span>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                                    height="12" fill="currentColor"
                                                                    class="bi bi-clock text-muted" viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                                                    <path
                                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                                                                </svg>
                                                                <small class="ms-2">3 days ago</small>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="border-top px-5 py-4 text-center">
                                        <a href="#!">View All</a>
                                    </div>
                                </div>
                            </li>
                            <li class="dropdown ms-4">
                                <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="../../freshcart-1-2-1/dist/assets/images/avatar/avatar-1.jpg" alt=""
                                        class="avatar avatar-md rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end p-0">
                                    <div class="lh-1 px-5 py-4 border-bottom">
                                        <h5 class="mb-1 h6">FreshCart Admin</h5>
                                        <small>admindemo@email.com</small>
                                    </div>
                                    <ul class="list-unstyled px-2 py-3">
                                        <li>
                                            <a class="dropdown-item" href="#!">Home</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#!">Profile</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#!">Settings</a>
                                        </li>
                                    </ul>
                                    <div class="border-top px-5 py-3">
                                        <a href="#">Log Out</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <div class="main-wrapper">
            <nav class="navbar-vertical-nav d-none d-xl-block">
                <div class="navbar-vertical">
                    <div class="px-4 py-5">
                        <a href="../index.html" class="navbar-brand">
                            <img src="../../freshcart-1-2-1/dist/assets/images/logo/freshcart-logo.svg" alt="">
                        </a>
                    </div>
                    <div class="navbar-vertical-content flex-grow-1" data-simplebar>
                        <ul class="navbar-nav flex-column" id="sideNavbar">
                            <li class="nav-item">
                                <a class="nav-link active" href="../dashboard/index.html">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><i class="bi bi-house"></i></span>
                                        <span class="nav-link-text">Dashboard</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item mt-6 mb-3">
                                <span class="nav-label">Management</span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="importar.php">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><i class="bi bi-file-earmark-arrow-up"></i></span>
                                        <span class="nav-link-text">Importar Datos</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="reporteHistorico.php">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><i class="bi bi-file-earmark-arrow-up"></i></span>
                                        <span class="nav-link-text">Histórico</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <main class="main-content-wrapper">
                <section class="container">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h2>Resumen del Día</h2>
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" action="">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="startDate">Fecha Inicio:</label>
                                                <input type="date" id="startDate" name="startDate" class="form-control"
                                                    required value="<?php echo $startDate; ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="endDate">Fecha Fin:</label>
                                                <input type="date" id="endDate" name="endDate" class="form-control"
                                                    required value="<?php echo $endDate; ?>">
                                            </div>
                                            <div class="col-md-3 align-self-end">
                                                <button type="submit" class="btn btn-primary">Generar Reporte</button>
                                            </div>
                                        </div>
                                    </form>
                                    <?php if ($mostrarMensaje): ?>
                                        <p>Los datos de hoy serán actualizados antes de las 16:30 hs.</p>
                                    <?php else: ?>
                                        <div class="row mt-5">
                                            <div class="col-md-3">
                                                <h5>Total Vendido:</h5>
                                                <p><?php echo htmlspecialchars(number_format($resumen['TotalVenta'], 2)); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-3">
                                                <h5>Total Devoluciones:</h5>
                                                <p><?php echo htmlspecialchars(number_format($totalDevoluciones, 2)); ?></p>
                                            </div>
                                            <div class="col-md-2">
                                                <h5>Clientes:</h5>
                                                <p><?php echo htmlspecialchars($resumen['CantidadClientes']); ?></p>
                                            </div>
                                            <div class="col-md-2">
                                                <h5>Boletas:</h5>
                                                <p><?php echo htmlspecialchars($resumen['CantidadBoletas']); ?></p>
                                            </div>
                                            <div class="col-md-2">
                                                <h5>Ticket Promedio:</h5>
                                                <p><?php echo htmlspecialchars(number_format($resumen['TicketPromedio'], 2)); ?>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <h5>Total Vendido Menos IVA:</h5>
                                                <p><?php echo htmlspecialchars(number_format($totalVentaMenosIVA, 2)); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-3">
                                                <h5>Total Vendido Menos Ponderado:</h5>
                                                <p><?php echo htmlspecialchars(number_format($totalVentaMenosPonderado, 2)); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-3">
                                                <h5>Total Comisiones:</h5>
                                                <p><?php echo htmlspecialchars(number_format($totalComisiones, 2)); ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <h5>Total Menos Ponderado e IVA:</h5>
                                                <p><?php echo htmlspecialchars(number_format($totalMenosPonderadoIVA, 2)); ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (!$mostrarMensaje): ?>
                        <div class="table-responsive-xl mb-6 mb-lg-0">
                            <h3 class="mb-4">Ventas por Preventista</h3>
                            <table id="ventasTable" class="table table-centered table-borderless text-nowrap table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Preventista</th>
                                        <th scope="col">Cantidad de Boletas</th>
                                        <th scope="col">Cantidad de Clientes</th>
                                        <th scope="col">Venta Total</th>
                                        <th scope="col">Devoluciones</th>
                                        <th scope="col">Ticket Promedio</th>
                                        <th scope="col">Comisión</th>
                                        <th scope="col">Variedad de Artículos</th>
                                        <th scope="col">Variedad de Proveedores</th>
                                        <th scope="col">Promedio Artículos/Cliente</th>
                                        <th scope="col">Promedio Proveedores/Cliente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ventasPreventista as $venta): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($venta['Preventista']); ?></td>
                                            <td><?php echo htmlspecialchars($venta['CantidadBoletas']); ?></td>
                                            <td><?php echo htmlspecialchars($venta['CantidadClientes']); ?></td>
                                            <td><?php echo htmlspecialchars(number_format($venta['TotalVentaNum'], 2)); ?></td>
                                            <td><?php echo htmlspecialchars(number_format($venta['TotalDevoluciones'], 2)); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars(number_format($venta['TicketPromedioNum'], 2)); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars(number_format($venta['Comision'] ?? 0, 2)); ?></td>
                                            <td><?php echo htmlspecialchars($venta['VariedadArticulos']); ?></td>
                                            <td><?php echo htmlspecialchars($venta['VariedadProveedores']); ?></td>
                                            <td><?php echo htmlspecialchars(number_format($venta['PromedioArticulosPorCliente'], 2)); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars(number_format($venta['PromedioProveedoresPorCliente'], 2)); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <h3 class="mb-4">Montos Generales de Devoluciones por Causa de Emisión</h3>
                        <div class="table-responsive-xl mb-6 mb-lg-0">
                            <table id="devolucionesGeneralesCausaTable"
                                class="table table-centered table-borderless text-nowrap table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Causa de Emisión</th>
                                        <th scope="col">Cantidad de Devoluciones</th>
                                        <th scope="col">Monto Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($devolucionesGeneralesPorCausa as $devolucion): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($devolucion['Causa']); ?></td>
                                            <td><?php echo htmlspecialchars($devolucion['CantidadDevoluciones']); ?></td>
                                            <td><?php echo htmlspecialchars(number_format($devolucion['MontoTotal'], 2)); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>



                        <h3 class="mb-4">Devoluciones por Causa de Emisión</h3>
                        <table id="devolucionesCausaTable"
                            class="table table-centered table-borderless text-nowrap table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col">Preventista</th>
                                    <th scope="col">Causa de Emisión</th>
                                    <th scope="col">Cantidad de Devoluciones</th>
                                    <th scope="col">Monto Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($devolucionesPorCausa as $devolucion): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($devolucion['Preventista']); ?></td>
                                        <td><?php echo htmlspecialchars($devolucion['Causa']); ?></td>
                                        <td><?php echo htmlspecialchars($devolucion['CantidadDevoluciones']); ?></td>
                                        <td><?php echo htmlspecialchars(number_format($devolucion['MontoTotal'], 2)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <h3 class="mb-4">Artículos Devueltos con Causa de Emisión "Sin Mercadería"</h3>
                        <div class="table-responsive-xl mb-6 mb-lg-0">
                            <table id="articulosDevolucionesSinMercaderiaTable"
                                class="table table-centered table-borderless text-nowrap table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Descripción del Artículo</th>
                                        <th scope="col">Causa de Emisión</th>
                                        <th scope="col">Cantidad Devuelta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($articulosDevolucionesSinMercaderia as $articulo): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($articulo['DescripcionArticulo']); ?></td>
                                            <td><?php echo htmlspecialchars($articulo['Causa']); ?></td>
                                            <td><?php echo htmlspecialchars($articulo['CantidadDevuelta']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <h3 class="mb-4">Artículos Devueltos por Causa de Emisión "Sin Dinero" y "Ni pidió"</h3>
<div class="table-responsive-xl mb-6 mb-lg-0">
    <table id="articulosDevolucionesCausasEspecificasTable"
           class="table table-centered table-borderless text-nowrap table-hover">
        <thead class="bg-light">
            <tr>
                <th scope="col">Descripción del Artículo</th>
                <th scope="col">Causa de Emisión</th>
                <th scope="col">Cantidad Devuelta</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articulosDevolucionesCausasEspecificas as $articulo): ?>
                <tr>
                    <td><?php echo htmlspecialchars($articulo['DescripcionArticulo']); ?></td>
                    <td><?php echo htmlspecialchars($articulo['Causa']); ?></td>
                    <td><?php echo htmlspecialchars($articulo['CantidadDevuelta']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


                        <div class="table-responsive-xl mb-6 mb-lg-0">
                            <h3 class="mb-4">Ventas por Proveedor</h3>
                            <table id="ventasProveedorTable"
                                class="table table-centered table-borderless text-nowrap table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Proveedor</th>
                                        <th scope="col">Cantidad de Artículos</th>
                                        <th scope="col">Total de Venta</th>
                                        <th scope="col">Cantidad de Clientes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ventasProveedor as $venta): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($venta['Proveedor']); ?></td>
                                            <td><?php echo htmlspecialchars($venta['CantidadArticulos']); ?></td>
                                            <td><?php echo htmlspecialchars(number_format($venta['TotalVenta'], 2)); ?></td>
                                            <td><?php echo htmlspecialchars($venta['CantidadClientes']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive-xl mb-6 mb-lg-0">
                            <h3 class="mb-4">Ventas por Preventista y Proveedor</h3>
                            <table id="ventasPreventistaProveedorTable"
                                class="table table-centered table-borderless text-nowrap table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Preventista</th>
                                        <th scope="col">Proveedor</th>
                                        <th scope="col">Cantidad de Artículos</th>
                                        <th scope="col">Total de Venta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ventasPreventistaProveedor as $venta): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($venta['Preventista']); ?></td>
                                            <td><?php echo htmlspecialchars($venta['Proveedor']); ?></td>
                                            <td><?php echo htmlspecialchars($venta['CantidadArticulos']); ?></td>
                                            <td><?php echo htmlspecialchars(number_format($venta['TotalVenta'], 2)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive-xl mb-6 mb-lg-0">
                            <h3 class="mb-4">Artículos Más Vendidos</h3>
                            <table id="articulosMasVendidosTable"
                                class="table table-centered table-borderless text-nowrap table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Código Artículo</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Proveedor</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Monto Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Limitamos a los 20 artículos más vendidos
                                    $articulosMasVendidosTop20 = array_slice($articulosMasVendidos, 0, 1000);
                                    foreach ($articulosMasVendidosTop20 as $articulo): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($articulo['CodigoArticulo']); ?></td>
                                            <td><?php echo htmlspecialchars($articulo['Descripcion']); ?></td>
                                            <td><?php echo htmlspecialchars($articulo['Proveedor']); ?></td>
                                            <td><?php echo htmlspecialchars($articulo['Cantidad']); ?></td>
                                            <td><?php echo htmlspecialchars(number_format($articulo['MontoTotal'], 2)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                         <button id="downloadExcel" class="btn btn-primary mb-4">Descargar en Excel</button>
                        <div class="table-responsive-xl mb-6 mb-lg-0">
                            <h3 class="mb-4">Artículos Más Vendidos por Preventista</h3>
                            <table id="articulosMasVendidosPreventistaTable"
                                class="table table-centered table-borderless text-nowrap table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Preventista</th>
                                        <th scope="col">Código Artículo</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Proveedor</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Monto Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($articulosMasVendidosPreventista as $articulo): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($articulo['Preventista']); ?></td>
                                            <td><?php echo htmlspecialchars($articulo['CodigoArticulo']); ?></td>
                                            <td><?php echo htmlspecialchars($articulo['Descripcion']); ?></td>
                                            <td><?php echo htmlspecialchars($articulo['Proveedor']); ?></td>
                                            <td><?php echo htmlspecialchars($articulo['Cantidad']); ?></td>
                                            <td><?php echo htmlspecialchars(number_format($articulo['MontoTotal'], 2)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        

                    <div class="table-responsive-xl mb-6 mb-lg-0">
                        <h3 class="mb-4">Cajas de Yogures Vendidas por Preventista</h3>
                        <table id="cajasYoguresTable" class="table table-centered table-borderless text-nowrap table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col">Preventista</th>
                                    <th scope="col">Código Artículo</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">Proveedor</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Monto Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cajasYogures as $caja): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($caja['Preventista']); ?></td>
                                        <td><?php echo htmlspecialchars($caja['CodigoArticulo']); ?></td>
                                        <td><?php echo htmlspecialchars($caja['Descripcion']); ?></td>
                                        <td><?php echo htmlspecialchars($caja['Proveedor']); ?></td>
                                        <td><?php echo htmlspecialchars($caja['Cantidad']); ?></td>
                                        <td><?php echo htmlspecialchars(number_format($caja['MontoTotal'], 2)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>



                        <div class="table-responsive-xl mb-6 mb-lg-0">
                            <h3 class="mb-4">Ventas por Zona</h3>
                            <table id="ventasZonaTable"
                                class="table table-centered table-borderless text-nowrap table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Zona</th>
                                        <th scope="col">Total de Venta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ventasZona as $venta): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($venta['Zona']); ?></td>
                                            <td><?php echo htmlspecialchars(number_format($venta['TotalVenta'], 2)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive-xl mb-6 mb-lg-0">
                            <h3 class="mb-4">Artículos Comprados por Cliente Único</h3>
                            <table id="articulosClienteTable"
                                class="table table-centered table-borderless text-nowrap table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Código Cliente</th>
                                        <th scope="col">Nombre Cliente</th>
                                        <th scope="col">Código Artículo</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Monto Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($articulosCliente as $articulo): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($articulo['CodigoCliente']); ?></td>
                                            <td><?php echo htmlspecialchars($articulo['NombreCliente']); ?></td>
                                            <td><?php echo htmlspecialchars($articulo['CodigoArticulo']); ?></td>
                                            <td><?php echo htmlspecialchars($articulo['Descripcion']); ?></td>
                                            <td><?php echo htmlspecialchars($articulo['Cantidad']); ?></td>
                                            <td><?php echo htmlspecialchars(number_format($articulo['MontoTotal'], 2)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive-xl mb-6 mb-lg-0">
                            <h3 class="mb-4">Compras por Cliente y Proveedor</h3>
                            <table id="comprasClienteProveedorTable"
                                class="table table-centered table-borderless text-nowrap table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Código Cliente</th>
                                        <th scope="col">Nombre Cliente</th>
                                        <th scope="col">Proveedor</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Monto Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($comprasClienteProveedor as $compra): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($compra['CodigoCliente']); ?></td>
                                            <td><?php echo htmlspecialchars($compra['NombreCliente']); ?></td>
                                            <td><?php echo htmlspecialchars($compra['Proveedor']); ?></td>
                                            <td><?php echo htmlspecialchars($compra['Cantidad']); ?></td>
                                            <td><?php echo htmlspecialchars(number_format($compra['MontoTotal'], 2)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive-xl mb-6 mb-lg-0">
                            <h3 class="mb-4">Ventas Totales por Cliente</h3>
                            <table id="ventasClienteTable"
                                class="table table-centered table-borderless text-nowrap table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Código Cliente</th>
                                        <th scope="col">Nombre Cliente</th>
                                        <th scope="col">Total de Venta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ventasPorCliente as $venta): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($venta['CodigoCliente']); ?></td>
                                            <td><?php echo htmlspecialchars($venta['NombreCliente']); ?></td>
                                            <td><?php echo htmlspecialchars(number_format($venta['TotalVenta'], 2)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mb-8">
                            <div class="col-md-6">
                                <h3 class="mb-4">Gráfico de Ventas por Preventista</h3>
                                <canvas id="ventasPreventistaChart"></canvas>
                            </div>
                            <div class="col-md-6">
                                <h3 class="mb-4">Gráfico de Ventas por Proveedor</h3>
                                <canvas id="ventasProveedorChart"></canvas>
                            </div>
                        </div>
                        <div class="row mb-8">
                            <div class="col-md-6">
                                <h3 class="mb-4">Evolución de Ventas por Preventista</h3>
                                <canvas id="evolucionVentasVendedorChart"></canvas>
                            </div>
                            <div class="col-md-6">
                                <h3 class="mb-4">Evolución del Monto Total de Ventas</h3>
                                <canvas id="evolucionVentasChart"></canvas>
                            </div>
                        </div>
                        <div class="row mb-8">
                            <div class="col-md-12">
                                <h3 class="mb-4">Histórico de Ventas por Proveedor</h3>
                                <canvas id="historicoVentasProveedorChart"></canvas>
                            </div>
                        </div>
                        <div class="row mb-8">
                            <div class="col-md-12">
                                <h3 class="mb-4">Artículos Más Vendidos</h3>
                                <canvas id="articulosMasVendidosChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h3 class="mb-4">Devoluciones por Causa de Emisión</h3>
                            <canvas id="devolucionesCausaChart"></canvas>
                        </div>

                    <?php endif; ?>
                </section>
            </main>
        </div>
    </div>
    <script src="../../freshcart-1-2-1/dist/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../freshcart-1-2-1/dist/assets/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="../../freshcart-1-2-1/dist/assets/js/theme.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#ventasTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
                },
                "order": [[3, "desc"]]
            });
            $('#ventasProveedorTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
                }
            });
            $('#ventasPreventistaProveedorTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
                }
            });
            $('#articulosMasVendidosTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
                }
            });
            $('#articulosMasVendidosPreventistaTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
                }
            });
            $('#ventasZonaTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
                }
            });
            $('#articulosClienteTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
                }
            });
            $('#comprasClienteProveedorTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
                }
            });

            // Paleta de colores
            const colors = [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ];
            const borderColors = [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ];

            // Datos para los gráficos
            const ventasPreventistaLabels = <?php echo json_encode(array_column($ventasPreventista, 'Preventista')); ?>;
            const ventasPreventistaData = <?php echo json_encode(array_column($ventasPreventista, 'TotalVentaNum')); ?>;
            const ventasProveedorLabels = [...new Set(<?php echo json_encode(array_column($ventasProveedor, 'Proveedor')); ?>)]; // Eliminar duplicados
            const ventasProveedorData = <?php echo json_encode(array_column($ventasProveedor, 'TotalVenta')); ?>;
            const articulosMasVendidosLabels = <?php echo json_encode(array_column(array_slice($articulosMasVendidos, 0, 20), 'Descripcion')); ?>;
            const articulosMasVendidosData = <?php echo json_encode(array_column(array_slice($articulosMasVendidos, 0, 20), 'Cantidad')); ?>;

            // Gráfico de ventas por preventista
            const ctxPreventista = document.getElementById('ventasPreventistaChart').getContext('2d');
            new Chart(ctxPreventista, {
                type: 'bar',
                data: {
                    labels: ventasPreventistaLabels,
                    datasets: [{
                        label: 'Total Venta',
                        data: ventasPreventistaData,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Gráfico de ventas por proveedor
            const ctxProveedor = document.getElementById('ventasProveedorChart').getContext('2d');
            new Chart(ctxProveedor, {
                type: 'bar',
                data: {
                    labels: ventasProveedorLabels,
                    datasets: [{
                        label: 'Total Venta',
                        data: ventasProveedorData,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Datos para evolución de ventas por preventista
            const ventasDiariasVendedorData = <?php echo json_encode($ventasDiariasVendedor); ?>;
            const preventistasEvolucion = {};
            ventasDiariasVendedorData.forEach(function (venta) {
                if (!preventistasEvolucion[venta.Preventista]) {
                    preventistasEvolucion[venta.Preventista] = { fechas: [], ventas: [] };
                }
                preventistasEvolucion[venta.Preventista].fechas.push(venta.Fecha);
                preventistasEvolucion[venta.Preventista].ventas.push(venta.TotalVenta);
            });

            const datasetsEvolucionVendedor = [];
            for (const [preventista, data] of Object.entries(preventistasEvolucion)) {
                datasetsEvolucionVendedor.push({
                    label: preventista,
                    data: data.ventas,
                    fill: false,
                    borderColor: borderColors[datasetsEvolucionVendedor.length % borderColors.length], // Asignar colores diferentes
                    tension: 0.1
                });
            }

            // Gráfico de evolución de ventas por preventista
            const ctxEvolucionVendedor = document.getElementById('evolucionVentasVendedorChart').getContext('2d');
            new Chart(ctxEvolucionVendedor, {
                type: 'line',
                data: {
                    labels: preventistasEvolucion[Object.keys(preventistasEvolucion)[0]].fechas,
                    datasets: datasetsEvolucionVendedor
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Datos para evolución del monto total de ventas
            const ventasDiariasLabels = <?php echo json_encode(array_column($ventasDiarias, 'Fecha')); ?>;
            const ventasDiariasData = <?php echo json_encode(array_column($ventasDiarias, 'TotalVenta')); ?>;

            // Gráfico de evolución del monto total de ventas
            const ctxEvolucionVentas = document.getElementById('evolucionVentasChart').getContext('2d');
            new Chart(ctxEvolucionVentas, {
                type: 'line',
                data: {
                    labels: ventasDiariasLabels,
                    datasets: [{
                        label: 'Total Venta',
                        data: ventasDiariasData,
                        fill: false,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        tension: 0.1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Datos para histórico de ventas por proveedor
            const historicoVentasProveedorLabels = <?php echo json_encode(array_unique(array_column($ventasProveedor, 'Fecha'))); ?>;
            const proveedoresEvolucion = {};

            ventasProveedor.forEach(function (venta) {
                if (!proveedoresEvolucion[venta.Proveedor]) {
                    proveedoresEvolucion[venta.Proveedor] = { fechas: [], ventas: [] };
                }
                proveedoresEvolucion[venta.Proveedor].fechas.push(venta.Fecha);
                proveedoresEvolucion[venta.Proveedor].ventas.push(venta.TotalVenta);
            });

            const datasetsEvolucionProveedor = [];
            for (const [proveedor, data] of Object.entries(proveedoresEvolucion)) {
                datasetsEvolucionProveedor.push({
                    label: proveedor,
                    data: data.ventas,
                    fill: false,
                    borderColor: borderColors[datasetsEvolucionProveedor.length % borderColors.length], // Asignar colores diferentes
                    tension: 0.1
                });
            }

            // Gráfico de histórico de ventas por proveedor
            const ctxHistoricoProveedor = document.getElementById('historicoVentasProveedorChart').getContext('2d');
            new Chart(ctxHistoricoProveedor, {
                type: 'line',
                data: {
                    labels: historicoVentasProveedorLabels,
                    datasets: datasetsEvolucionProveedor
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Gráfico de artículos más vendidos
            const ctxArticulos = document.getElementById('articulosMasVendidosChart').getContext('2d');
            new Chart(ctxArticulos, {
                type: 'bar',
                data: {
                    labels: articulosMasVendidosLabels,
                    datasets: [{
                        label: 'Cantidad Vendida',
                        data: articulosMasVendidosData,
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            const devolucionesCausaLabels = <?php echo json_encode(array_unique(array_column($devolucionesPorCausa, 'Causa'))); ?>;
            const preventistas = <?php echo json_encode(array_unique(array_column($devolucionesPorCausa, 'Preventista'))); ?>;

            const datasetsDevoluciones = preventistas.map(preventista => {
                return {
                    label: preventista,
                    data: devolucionesCausaLabels.map(causa => {
                        const devolucion = <?php echo json_encode($devolucionesPorCausa); ?>.find(d => d.Preventista === preventista && d.Causa === causa);
                        return devolucion ? devolucion.CantidadDevoluciones : 0;
                    }),
                    backgroundColor: colors[datasetsDevoluciones.length % colors.length],
                    borderColor: borderColors[datasetsDevoluciones.length % borderColors.length],
                    borderWidth: 1
                };
            });

            const ctxDevoluciones = document.getElementById('devolucionesCausaChart').getContext('2d');
            new Chart(ctxDevoluciones, {
                type: 'bar',
                data: {
                    labels: devolucionesCausaLabels,
                    datasets: datasetsDevoluciones
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#devolucionesCausaTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
                },
                "order": [[3, "desc"]]
            });
        });

        $(document).ready(function () {
            $('#devolucionesGeneralesCausaTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
                },
                "order": [[2, "desc"]]
            });
        });

        $(document).ready(function () {
            $('#articulosDevolucionesSinMercaderiaTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
                },
                "order": [[2, "desc"]]
            });
        });

    </script>
    <script>
        $(document).ready(function () {
            $('#ventasClienteTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
                },
                "order": [[2, "desc"]]
            });
        });

    </script>
        <script>
        $('#cajasYoguresTable').DataTable({
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
    }
});
    </script>
    <script>
    document.getElementById('downloadExcel').addEventListener('click', function() {
        // Obtener la tabla de HTML
        var table = document.getElementById('articulosMasVendidosPreventistaTable');
        
        // Convertir la tabla HTML en una hoja de trabajo de SheetJS
        var wb = XLSX.utils.table_to_book(table, { sheet: "Cajas de Yogures" });
        
        // Generar el archivo Excel y descargarlo
        XLSX.writeFile(wb, 'cajas_yogures_vendidas.xlsx');
    });
</script>

    <script>
 $(document).ready(function () {
    $('#articulosDevolucionesCausasEspecificasTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
        },
        "order": [[2, "desc"]]
    });
});

</script>





</body>

</html>