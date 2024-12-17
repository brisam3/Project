<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once('../../database/Database.php');

$db = new Database();
$pdo = $db->getConnection();

$query = "
    SELECT c.*, 
           chofer.nombre AS nombre_chofer, 
           chofer.apellido AS apellido_chofer,
           preventista.nombre AS nombre_preventista,
           preventista.apellido AS apellido_preventista
    FROM rendicion_choferes c
    JOIN usuarios chofer ON c.idUsuarioChofer = chofer.idUsuario
    JOIN usuarios preventista ON c.idUsuarioPreventista = preventista.idUsuario
    WHERE DATE(c.fecha) = CURDATE()
";


$stmt = $pdo->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll();

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
        padding: 20mm;
        margin: 10mm auto;
        background: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .header {
        text-align: center;
        margin-bottom: 10mm;
    }

    .header h1 {
        font-size: 24pt;
        color: #333;
        margin: 0;
    }

    .header p {
        font-size: 12pt;
        color: #666;
    }

    .content {
        font-size: 11pt;
    }

    .section {
        margin-bottom: 10mm;
    }

    .section h2 {
        font-size: 14pt;
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
        margin: 20px auto;
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


                                    <div class="records-list">
                                        <h1>Listado de Rendiciones de Choferes</h1>

                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Chofer</th>
                                                    <th>Preventista</th>
                                                    <th>Total General</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($results as $index => $row): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['nombre_chofer']) ?></td>
                                                    <td> <?= htmlspecialchars($row['nombre_preventista'] . ' ' . $row['apellido_preventista']) ?>
                                                    </td>
                                                    <td>$<?= number_format($row['total_general'], 2) ?></td>
                                                    <td>
                                                        <a href="?print=<?= $index ?>"
                                                            class="button button-primary">Imprimir</a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php else: ?>
                                    <?php
    $row = $results[$selectedIndex];
    ?>

                                    <div class="no-print" style="text-align: center; margin-top: 20px;">
                                        <button onclick="window.print()" class="button button-primary">Imprimir</button>
                                        <a href="?" class="button button-secondary">Volver al listado</a>
                                    </div>
                                    <div class="page" id="printable-record">
                                        <div class="header">
                                            <h1>Rendición de Choferes</h1>
                                            <p>Fecha: <?= htmlspecialchars($row['fecha']) ?></p>
                                        </div>

                                        <div class="content">
                                            <div class="section">
                                                <h2>Información General</h2>
                                                <div class="grid">
                                                    <p><strong>Chofer:</strong>
                                                        <?= htmlspecialchars($row['nombre_chofer']) ?></p>
                                                    <p><strong>Preventista:</strong>
                                                        <?= htmlspecialchars($row['nombre_preventista'] . ' ' . $row['apellido_preventista']) ?>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="section">
                                                <h2>Resumen Financiero</h2>
                                                <div class="grid">
                                                    <p><strong>Total Efectivo:</strong>
                                                        $<?= number_format($row['total_efectivo'], 2) ?></p>
                                                    <p><strong>Total Transferencia:</strong>
                                                        $<?= number_format($row['total_transferencia'], 2) ?></p>
                                                    <p><strong>Total Mercado Pago:</strong>
                                                        $<?= number_format($row['total_mercadopago'], 2) ?></p>
                                                    <p><strong>Total Cheques:</strong>
                                                        $<?= number_format($row['total_cheques'], 2) ?></p>
                                                    <p><strong>Total Fiados:</strong>
                                                        $<?= number_format($row['total_fiados'], 2) ?></p>
                                                    <p><strong>Total Gastos:</strong>
                                                        $<?= number_format($row['total_gastos'], 2) ?></p>
                                                    <p><strong>Total Rechazos:</strong>
                                                        $<?= number_format($row['total_rechazos'], 2) ?></p>
                                                    <p><strong>Pago Secretario:</strong>
                                                        $<?= number_format($row['pago_secretario'], 2) ?></p>
                                                    <p><strong>Total MEC Faltante:</strong>
                                                        $<?= number_format($row['total_mec_faltante'], 2) ?></p>
                                                </div> <br>
                                                <h2>Total</h2>
                                                <p class="total"><strong>Total General:</strong>
                                                    $<?= number_format($row['total_general'], 2) ?></p>
                                                <p class="total"><strong>Contrareembolso:</strong>
                                                    $<?= number_format($row['contrareembolso'], 2) ?></p>
                                                <p class="total"><strong>Diferencia:</strong>
                                                    $<?= number_format($row['contrareembolso'] - $row['total_general'], 2) ?>
                                                </p>
                                            </div>

                                            <div class="section">
                                                <h2>Desglose de Billetes</h2>
                                                <div class="grid">
                                                    <p><strong>Billetes de 20000:</strong> <?= $row['billetes_20000'] ?>
                                                    </p>
                                                    <p><strong>Billetes de 10000:</strong> <?= $row['billetes_10000'] ?>
                                                    </p>
                                                    <p><strong>Billetes de 2000:</strong> <?= $row['billetes_2000'] ?>
                                                    </p>
                                                    <p><strong>Billetes de 1000:</strong> <?= $row['billetes_1000'] ?>
                                                    </p>
                                                    <p><strong>Billetes de 500:</strong> <?= $row['billetes_500'] ?></p>
                                                    <p><strong>Billetes de 200:</strong> <?= $row['billetes_200'] ?></p>
                                                    <p><strong>Billetes de 100:</strong> <?= $row['billetes_100'] ?></p>
                                                    <p><strong>Billetes de 50:</strong> <?= $row['billetes_50'] ?></p>
                                                    <p><strong>Billetes de 20:</strong> <?= $row['billetes_20'] ?></p>
                                                    <p><strong>Billetes de 10:</strong> <?= $row['billetes_10'] ?></p>
                                                </div>
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

        <script>
        <?php if ($selectedIndex !== -1): ?>
        // Si se ha seleccionado un registro para imprimir, abrimos automáticamente el diálogo de impresión
        window.onload = function() {
            window.print();
        }
        <?php endif; ?>
        </script>
</body>

</html>