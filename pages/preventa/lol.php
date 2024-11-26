<?php
require_once '../../backend/controller/preventa/preventa.php';

$ventasController = new VentasController();
$fechaHoy = date('Y-m-d');

// Obtener los datos
$resumen = $ventasController->obtenerResumenDelDia($fechaHoy);
$ventasPreventista = $ventasController->obtenerVentasPorPreventista($fechaHoy);
$ventasProveedor = $ventasController->obtenerVentasPorProveedor($fechaHoy);
$ventasPreventistaProveedor = $ventasController->obtenerVentasPorPreventistaYProveedor($fechaHoy);
$articulosMasVendidos = $ventasController->obtenerArticulosMasVendidos($fechaHoy);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas</title>
  
</head>

<body>
    <div class="container">
        <h1>Reporte de Ventas</h1>

        <!-- Resumen del Día -->
        <section>
            <h2>Resumen del Día</h2>
            <div class="row">
                <div class="col">
                    <h5>Total Vendido</h5>
                    <p><?= number_format($resumen['TotalVenta'] ?? 0, 2) ?> ARS</p>
                </div>
                <div class="col">
                    <h5>Clientes</h5>
                    <p><?= $resumen['CantidadClientes'] ?? 0 ?></p>
                </div>
                <div class="col">
                    <h5>Boletas</h5>
                    <p><?= $resumen['CantidadBoletas'] ?? 0 ?></p>
                </div>
                <div class="col">
                    <h5>Ticket Promedio</h5>
                    <p><?= number_format(($resumen['TotalVenta'] ?? 0) / max($resumen['CantidadBoletas'] ?? 1, 1), 2) ?> ARS</p>
                </div>
            </div>
        </section>

        <!-- Ventas por Preventista -->
        <section>
            <h2>Ventas por Preventista</h2>
            <table>
                <thead>
                    <tr>
                        <th>Preventista</th>
                        <th>Cantidad Boletas</th>
                        <th>Cantidad Clientes</th>
                        <th>Total Venta</th>
                        <th>Ticket Promedio</th>
                        <th>Comisión</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventasPreventista as $venta): ?>
                        <tr>
                            <td><?= htmlspecialchars($venta['Preventista']) ?></td>
                            <td><?= htmlspecialchars($venta['CantidadBoletas']) ?></td>
                            <td><?= htmlspecialchars($venta['CantidadClientes']) ?></td>
                            <td><?= number_format($venta['TotalVenta'], 2) ?> ARS</td>
                            <td><?= number_format($venta['TicketPromedio'], 2) ?> ARS</td>
                            <td><?= number_format($venta['Comision'], 2) ?> ARS</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Ventas por Proveedor -->
        <section>
            <h2>Ventas por Proveedor</h2>
            <table>
                <thead>
                    <tr>
                        <th>Proveedor</th>
                        <th>Cantidad Artículos</th>
                        <th>Total Venta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventasProveedor as $venta): ?>
                        <tr>
                            <td><?= htmlspecialchars($venta['Proveedor']) ?></td>
                            <td><?= htmlspecialchars($venta['CantidadArticulos']) ?></td>
                            <td><?= number_format($venta['TotalVenta'], 2) ?> ARS</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Ventas por Preventista y Proveedor -->
        <section>
            <h2>Ventas por Preventista y Proveedor</h2>
            <table>
                <thead>
                    <tr>
                        <th>Preventista</th>
                        <th>Proveedor</th>
                        <th>Cantidad Artículos</th>
                        <th>Total Venta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventasPreventistaProveedor as $venta): ?>
                        <tr>
                            <td><?= htmlspecialchars($venta['Preventista']) ?></td>
                            <td><?= htmlspecialchars($venta['Proveedor']) ?></td>
                            <td><?= htmlspecialchars($venta['CantidadArticulos']) ?></td>
                            <td><?= number_format($venta['TotalVenta'], 2) ?> ARS</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Artículos Más Vendidos -->
        <section>
            <h2>Artículos Más Vendidos</h2>
            <table>
                <thead>
                    <tr>
                        <th>Código Artículo</th>
                        <th>Descripción</th>
                        <th>Proveedor</th>
                        <th>Cantidad</th>
                        <th>Monto Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articulosMasVendidos as $articulo): ?>
                        <tr>
                            <td><?= htmlspecialchars($articulo['CodigoArticulo']) ?></td>
                            <td><?= htmlspecialchars($articulo['Descripcion']) ?></td>
                            <td><?= htmlspecialchars($articulo['Proveedor']) ?></td>
                            <td><?= htmlspecialchars($articulo['Cantidad']) ?></td>
                            <td><?= number_format($articulo['MontoTotal'], 2) ?> ARS</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
    <script>
        // Convertir los datos de PHP a JSON y enviarlos a la consola
        const resumen = <?php echo json_encode($resumen, JSON_PRETTY_PRINT); ?>;
        console.log("Resumen del Día:", resumen);
    </script>
</body>


</html>
