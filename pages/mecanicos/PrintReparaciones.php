<?php

include '../../backend/controller/access/AccessController.php';

$accessController = new AccessController();



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'es');

date_default_timezone_set('America/Argentina/Buenos_Aires'); // Configurar zona horaria

require_once('../../database/Database.php');

$db = new Database();
$pdo = $db->getConnection();

// Obtener la fecha seleccionada o la actual
$fecha_actual = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

// Inicializar la variable para evitar errores
$comprobantes = [];

$query = "
SELECT 
    m.id AS mecanico_id,
    m.nombre AS mecanico,
    DATE(a.fecha) AS fecha,
    a.codigo_arreglo,  -- Agregado aquí
    SUM(a.total) AS total_reparaciones,
    GROUP_CONCAT(DISTINCT c.nombre ORDER BY c.nombre SEPARATOR ' | ') AS camiones,
    (SELECT GROUP_CONCAT(DISTINCT d.descripcion ORDER BY d.descripcion SEPARATOR ' | ')
     FROM detalle_arreglos d
     WHERE d.arreglo_id = a.id) AS detalles
FROM arreglos a
JOIN mecanicos m ON a.mecanico_id = m.id
JOIN camiones c ON a.camion_id = c.id
WHERE DATE(a.fecha) = :fecha_actual
GROUP BY m.id, m.nombre, DATE(a.fecha), a.codigo_arreglo; -- Agregado en GROUP BY


";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':fecha_actual', $fecha_actual, PDO::PARAM_STR);
$stmt->execute();
$comprobantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    

// Verificar si se ha seleccionado un registro para imprimir
$selectedIndex = isset($_GET['print']) ? intval($_GET['print']) : -1;
?>

<!DOCTYPE html>
<html lang="es" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../../assets/" data-template="horizontal-menu-template">


<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Imprimir Rendiciones</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.1/css/boxicons.min.css" rel="stylesheet"
        integrity="sha512-cfBUsnQh7OSdceLgoYe8n5f4gR8wMSAEPr7iZYswqlN4OrcKUYxxCa5XPrp2XrtH0nXGGaOb7SfiI4Rkzr3psA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />
    <link rel="stylesheet" href="/assets/vendor/css/rtl/core.css" />
    <link rel="stylesheet" href="/assets/vendor/css/rtl/theme-default.css" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../../assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="../../assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/apex-charts/apex-charts.css" />


    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">



    <!-- Page CSS -->
    <link rel="stylesheet" href="../css/clima.css" />

    <!-- Helpers -->
    <script src="../../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="../../assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../../assets/js/config.js"></script>
    <style>
    /* Estilos existentes */
    @page {
        size: A4;
        margin: 0;
    }

    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f0f0;
    }


    .page {
        width: 210mm;
        min-height: 297mm;
        padding: 18mm;
        margin: 8mm auto;
        background: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .table {
        font-size: 0.7rem;
        width: 90%;
        margin: 0 auto;
    }

    /* Adjust padding and font size for table cells */
    .table th,
    .table td {
        padding: 0.4rem;
        font-size: 0.85rem;
    }

    /* Reduce the width of the first column in the denomination table */
    .table-bordered tbody tr td:first-child {
        width: 30%;
    }

    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    .page {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        /* Asegura que ocupe toda la pantalla */
    }

    .section {
        flex: 1;
        /* Hace que esta sección crezca para llenar el espacio disponible */
    }

    .footer {
        background-color: #f8f9fa;
        padding: 10px;
        text-align: center;
        border-top: 1px solid #ccc;
    }

    .firmas {
        display: flex;
        justify-content: space-between;
        /* Distribuye las firmas en los extremos */
        margin-top: 10px;
    }



    .header {
        text-align: center;
        margin-bottom: 8mm;
    }

    .header h1 {
        font-size: 20pt;
        color: #333;
        margin: 0;
    }

    .header p {
        font-size: 10pt;
        color: #666;
    }

    .content {
        font-size: 11pt;
    }

    .section {
        margin-bottom: 2mm;
    }



    .section h2 {
        font-size: 10pt;
        color: #444;
        border-bottom: 1px solid #ddd;
        padding-bottom: 2mm;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 5mm;
    }

    .grid p {
        margin: 0;
    }

    .total {
        font-weight: bold;
        margin-top: 5mm;
    }

    .header {
        text-align: center;
        /* Centra el título */
    }

    .fecha {
        text-align: right;
        /* Fecha alineada a la derecha */
    }

    .info {
        display: flex;
        justify-content: space-between;
        /* Espaciado entre mecánico y código */
        align-items: center;
        /* Alinea verticalmente */
        width: 100%;
    }

    .mecanico {
        flex: 1;
        /* Permite que quede centrado */
        text-align: center;
    }

    .codigo {
        text-align: right;
        /* Código de arreglo a la derecha */
    }


    .date-form-container {
        display: flex;
        justify-content: center;
        margin: 20px 0 0;
        font-family: Arial, sans-serif;
    }

    .date-form {
        background-color: #f8f9fa;
        border-radius: 4px;
        padding: 10px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .date-form label {
        font-size: 14px;
        margin-right: 8px;
        color: #333;
    }

    .date-form input[type="date"] {
        padding: 4px 8px;
        border: 1px solid #ced4da;
        border-radius: 3px;
        font-size: 14px;
        margin-right: 8px;
    }

    .date-form button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.2s ease;
    }

    .date-form button:hover {
        background-color: #0056b3;
    }

    @media print {
        body {
            background: none;
        }

        .page {
            margin: 0;
            box-shadow: none;
        }

        .no-print {
            display: none;
        }
    }

    /* Estilos para la lista de registros */
    .records-list {
        max-width: 800px;
        margin: 8px auto;
        background: white;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .records-list table {
        width: 100%;
        border-collapse: collapse;
    }

    .records-list th,
    .records-list td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .records-list th {
        background-color: #f2f2f2;
    }

    .print-link {
        display: inline-block;
        padding: 5px 10px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 3px;
    }
    </style>
    <style>
    .button {
        display: inline-block;
        padding: 10px 20px;
        margin: 0 8px;
        font-size: 16px;
        font-weight: 500;
        text-decoration: none;
        text-align: center;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s, box-shadow 0.3s;
    }

    .button:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
    }

    .button-primary {
        background-color: #3490dc;
        color: white;
    }

    .button-primary:hover {
        background-color: #2779bd;
    }

    .button-secondary {
        background-color: #f1f5f8;
        color: #3d4852;
    }

    .button-secondary:hover {
        background-color: #dae1e7;
    }
    </style>
    <style>
    @media print {
        .layout-navbar-full nav {
            display: none !important;
        }
    }
    </style>

</head>

<body>
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">


            <?php

      include "../template/nav.php";

      ?>
            <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">

                <div class="layout-container">
                    <div class="layout-page">
                        <div class="content-wrapper">
                            <div class="container-fluid">
                                <div class="row">
                                    <?php if ($selectedIndex === -1): ?>
                                    <div class="date-form-container">
                                        <form method="GET" action="" style="margin-bottom: 20px;" class="date-form">
                                            <label for="fecha">Seleccione la fecha:</label>
                                            <input type="date" id="fecha" name="fecha"
                                                value="<?= htmlspecialchars($fecha_actual) ?>">
                                            <button type="submit" class="button button-primary">Buscar</button>
                                        </form>
                                    </div>

                                    <div class="records-list">
                                        <h1>Comprobantes de Reparaciones</h1>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Mecánico</th>
                                                    <th>Fecha</th>
                                                    <th>Total Reparaciones</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($comprobantes as $index => $comprobante): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($comprobante['mecanico']) ?></td>
                                                    <td><?= htmlspecialchars($comprobante['fecha']) ?></td>
                                                    <td>$<?= number_format($comprobante['total_reparaciones'], 2, ',', '.') ?>
                                                    </td>
                                                    <td>
                                                        <a href="?fecha=<?= htmlspecialchars($fecha_actual) ?>&print=<?= $index ?>"
                                                            class="button button-primary">Imprimir</a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <?php else: ?>
                                    <?php 
                                            $comprobante = $comprobantes[$selectedIndex]; 
                                        ?>

                                    <div class="page">
                                        <div class="header">
                                            <div class="fecha">
                                                <p>Fecha:
                                                    <?= strftime('%d de %B de %Y', strtotime($comprobante['fecha'])) ?>
                                                </p>
                                            </div>

                                            <h2>Comprobante de Reparaciones</h2>
                                            <hr>

                                            <div class="info">
                                                <p class="mecanico"><strong>Mecánico:</strong>
                                                    <?= htmlspecialchars($comprobante['mecanico']) ?></p>
                                                <p class="codigo">Código arreglo:
                                                    <?= htmlspecialchars($comprobante['codigo_arreglo']) ?></p>
                                            </div>
                                        </div>


                                        <div class="section">

                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Camión</th>
                                                        <th>Descripción</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                            $reparaciones = $pdo->prepare("
                                                           SELECT 
                                                        c.nombre AS camion, 
                                                        (SELECT GROUP_CONCAT(DISTINCT d.descripcion ORDER BY d.descripcion SEPARATOR ' | ')
                                                        FROM detalle_arreglos d
                                                        WHERE d.arreglo_id = a.id) AS descripcion,
                                                        SUM(a.total) AS total
                                                    FROM arreglos a
                                                    JOIN camiones c ON a.camion_id = c.id
                                                    WHERE a.mecanico_id = :mecanico_id AND DATE(a.fecha) = :fecha
                                                    GROUP BY c.id

                                                        ");
                                                        $reparaciones->execute([
                                                            ':mecanico_id' => $comprobante['mecanico_id'],
                                                            ':fecha' => $comprobante['fecha']
                                                        ]);
                                                        $resultados = $reparaciones->fetchAll(PDO::FETCH_ASSOC);
                                                        

                                                            foreach ($resultados as $reparacion) :
                                                            ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($reparacion['camion']) ?></td>
                                                        <td><?= htmlspecialchars($reparacion['descripcion']) ?></td>
                                                        <td>$<?= number_format($reparacion['total'], 2, ',', '.') ?>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="footer">
                                            <h3>Total General:
                                                $<?= number_format($comprobante['total_reparaciones'], 2, ',', '.') ?>
                                            </h3>
                                            <div class="firmas">
                                                <p>Firma y aclaración mecánico: ________________________</p>
                                                <p>Firma y aclaración responsable: ________________________</p>
                                            </div>
                                        </div>

                                    </div>

                                    <?php endif; ?>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>

        <!--/ Layout wrapper -->

        <!-- Core JS -->
        <!-- build:js assets/vendor/js/core.js -->

        <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
        <script src="../../assets/vendor/libs/popper/popper.js"></script>
        <script src="../../assets/vendor/js/bootstrap.js"></script>
        <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

        <script src="../../assets/vendor/libs/hammer/hammer.js"></script>
        <script src="../../assets/vendor/libs/i18n/i18n.js"></script>
        <script src="../../assets/vendor/libs/typeahead-js/typeahead.js"></script>

        <script src="../../assets/vendor/js/menu.js"></script>
        <!-- endbuild -->

        <!-- Vendors JS -->
        <script src="../../assets/vendor/libs/apex-charts/apexcharts.js"></script>

        <!-- Main JS -->
        <script src="../../assets/js/main.js"></script>

        <!-- Page JS -->
        <script src="../../assets/js/dashboards-analytics.js"></script>

        <?php if ($selectedIndex !== -1): ?>
        <script>
        window.onload = function() {
            window.print();
        };
        </script>
        <?php endif; ?>

</body>

</html>