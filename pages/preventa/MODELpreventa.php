<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../../vendor/autoload.php'; // Asegúrate de que Composer haya instalado PHPSpreadsheet

// Definir las credenciales de conexión para el entorno local
$sconLocal = "mysql:dbname=wol;host=localhost";
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
    $pdo = new PDO($scon, $suser, $spass, array(
        PDO::ATTR_PERSISTENT => true,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $msg = 'conexion_ok';
} catch (PDOException $e) {
    $msg = 'conexion_cancel: ' . $e->getMessage();
    echo "Error al conectar a la base de datos. " . $e->getMessage();
    exit;
}

// Obtener la fecha actual
$today = date('Y-m-d');

// Obtener el resumen de ventas del día
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
    121 => 'Soledad' 
];


// Calcular el total vendido menos el IVA
$totalVentaMenosIVA = $resumen['TotalVenta'] / 1.21;

// Calcular el total vendido menos el ponderado de artículos
$totalVentaMenosPonderado = $resumen['TotalVenta'] * 0.47;

// Consultar datos de ventas por preventista
$sqlPreventista = "SELECT
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
        WHEN c.Comp_Vendedor_Cod = 122 THEN 'Esteban'
        ELSE 'Desconocido'
    END AS Preventista,
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
WHERE d.fecha = :today
GROUP BY Preventista
ORDER BY TotalVenta DESC";

$stmt = $pdo->prepare($sqlPreventista);
$stmt->execute([':today' => $today]);
$ventasPreventista = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$ventasPreventista) {
    $ventasPreventista = [];
}

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
        WHEN c.Comp_Vendedor_Cod = 122 THEN 'Esteban'
        ELSE 'Desconocido'
    END AS Preventista,
    p.descripcion AS Proveedor,
    SUM(c.Item_Cant_UM1) AS CantidadArticulos,
    SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta
FROM comprobantes c
JOIN detallereporte d ON c.detalleReporte_id = d.id
JOIN proveedores p ON c.Articulo_Prov_Habitual_Cod = p.cod_proveedor
WHERE d.fecha = :today
GROUP BY Preventista, p.descripcion
ORDER BY Preventista, TotalVenta DESC";

$stmt = $pdo->prepare($sqlPreventistaProveedor);
$stmt->execute([':today' => $today]);
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

// Consultar las ventas de yogures por preventista (cada caja trae 10 unidades)
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
            WHEN c.Comp_Vendedor_Cod = 122 THEN 'Esteban'
            ELSE 'Desconocido'
        END AS Preventista,
        c.Item_Articulo_Cod_Gen AS CodigoYogur,
        a.descripcion AS Descripcion,
        SUM(c.Item_Cant_UM1) AS UnidadesVendidas,
        SUM(c.Item_Cant_UM1) AS CajasVendidas -- Cada unidad ya representa una caja completa para los kits de yogures
    FROM comprobantes c
    JOIN detallereporte d ON c.detalleReporte_id = d.id
    Join articulos a ON c.Item_Articulo_Cod_Gen = a.codBejerman
    WHERE d.fecha BETWEEN :startDate AND :endDate
    AND c.Item_Articulo_Cod_Gen IN ('KIT0034', 'KIT0035') -- Códigos de los kits de yogures
    GROUP BY Preventista, c.Item_Articulo_Cod_Gen, a.descripcion
    ORDER BY Preventista, CajasVendidas DESC
";

$stmt = $pdo->prepare($sqlCajasYogures);
$stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
$cajasYogures = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$cajasYogures) {
    $cajasYogures = [];
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
        WHEN c.Comp_Vendedor_Cod = 122 THEN 'Esteban'
        ELSE 'Desconocido'
    END AS Preventista,
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
GROUP BY Preventista, c.Item_Articulo_Cod_Gen, a.descripcion, p.descripcion
ORDER BY Preventista, Cantidad DESC";

$stmt = $pdo->prepare($sqlArticulosPreventista);
$stmt->execute([':today' => $today]);
$articulosMasVendidosPreventista = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$articulosMasVendidosPreventista) {
    $articulosMasVendidosPreventista = [];
}

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
                                                        d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z" />
                                                    <path
                                                        d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z" />
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
                                        <span class="nav-link-text">Inicio</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item mt-6 mb-3">
                                <span class="nav-label">Management</span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../locales/reporteHistorico.php">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><i class="bi bi-file-earmark-arrow-up"></i></span>
                                        <span class="nav-link-text">Locales</span>
                                    </div>
                                </a>
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
                                    <?php if ($mostrarMensaje): ?>
                                        <p>Los datos de hoy serán actualizados antes de las 16:30 hs.</p>
                                    <?php else: ?>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <h5>Total Vendido:</h5>
                                                <p><?php echo htmlspecialchars(number_format($resumen['TotalVenta'], 2)); ?></p>
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
                                                <p><?php echo htmlspecialchars(number_format($resumen['TicketPromedio'], 2)); ?></p>
                                            </div>
                                            <div class="col-md-2">
                                                <h5>Promedio de clientes:</h5>
                                                <p><?php echo htmlspecialchars(number_format($resumen['CantidadClientes'])/8); ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <h5>Total Vendido Menos IVA:</h5>
                                                <p><?php echo htmlspecialchars(number_format($totalVentaMenosIVA, 2)); ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <h5>Total Vendido Menos Ponderado:</h5>
                                                <p><?php echo htmlspecialchars(number_format($totalVentaMenosPonderado, 2)); ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <h5>Total Comisiones:</h5>
                                                <p><?php echo htmlspecialchars(number_format($totalComisiones, 2)); ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <h5>Total Menos Ponderado e IVA:</h5>
                                                <p><?php echo htmlspecialchars(number_format($totalMenosPonderadoIVA, 2)); ?></p>
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
                                    <th scope="col">Total de Venta Diario</th>
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
                                        <td><?php echo htmlspecialchars(number_format($venta['TicketPromedioNum'], 2)); ?></td>
                                        <td><?php echo htmlspecialchars(number_format($venta['Comision'], 2)); ?></td>
                                        <td><?php echo htmlspecialchars($venta['VariedadArticulos']); ?></td>
                                        <td><?php echo htmlspecialchars($venta['VariedadProveedores']); ?></td>
                                        <td><?php echo htmlspecialchars(number_format($venta['PromedioArticulosPorCliente'], 2)); ?></td>
                                        <td><?php echo htmlspecialchars(number_format($venta['PromedioProveedoresPorCliente'], 2)); ?></td>
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ventasProveedor as $venta): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($venta['Proveedor']); ?></td>
                                        <td><?php echo htmlspecialchars($venta['CantidadArticulos']); ?></td>
                                        <td><?php echo htmlspecialchars(number_format($venta['TotalVenta'], 2)); ?></td>
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
                                <?php foreach ($articulosMasVendidos as $articulo): ?>
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
    <h3 class="mb-4">Cajas de Yogures Vendidas por Preventista</h3>
    <table id="cajasYoguresTable" class="table table-centered table-borderless text-nowrap table-hover">
        <thead class="bg-light">
            <tr>
                <th scope="col">Preventista</th>
                <th scope="col">Código Yogur</th>
                <th scope="col">Descripción</th>
                <th scope="col">Unidades Vendidas</th>
                <th scope="col">Cajas Vendidas</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cajasYogures as $caja): ?>
                <tr>
                    <td><?php echo htmlspecialchars($caja['Preventista']); ?></td>
                    <td><?php echo htmlspecialchars($caja['CodigoYogur']); ?></td>
                    <td><?php echo htmlspecialchars($caja['Descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($caja['UnidadesVendidas']); ?></td>
                    <td><?php echo htmlspecialchars($caja['UnidadesVendidas']); ?></td> <!-- Mostramos las unidades vendidas como cajas vendidas -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

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

            // Datos para los gráficos
            const ventasPreventistaLabels = <?php echo json_encode(array_column($ventasPreventista, 'Preventista')); ?>;
            const ventasPreventistaData = <?php echo json_encode(array_column($ventasPreventista, 'TotalVentaNum')); ?>;
            const ventasProveedorLabels = <?php echo json_encode(array_column($ventasProveedor, 'Proveedor')); ?>;
            const ventasProveedorData = <?php echo json_encode(array_column($ventasProveedor, 'TotalVenta')); ?>;

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
        var table = document.getElementById('cajasYoguresTable');
        
        // Convertir la tabla HTML en una hoja de trabajo de SheetJS
        var wb = XLSX.utils.table_to_book(table, { sheet: "Cajas de Yogures" });
        
        // Generar el archivo Excel y descargarlo
        XLSX.writeFile(wb, 'cajas_yogures_vendidas.xlsx');
    });
</script>

</body>

</html>
