<?php



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

$query = "
   SELECT 
  r.id AS id_rendicion,
  r.fecha,
  r.total_efectivo,
  r.total_cheques,
  r.total_general,
  r.billetes_20000,
  r.billetes_10000,
  r.billetes_2000,
  r.billetes_1000,
  r.billetes_500,
  r.billetes_200,
  r.billetes_100,
  r.billetes_50,
  r.billetes_20,
  r.billetes_10,
  GROUP_CONCAT(CONCAT('Banco: ', c.banco, ' - Importe: ', c.importe) SEPARATOR ' | ') AS cheques
FROM 
  rendicion_general_banco r
LEFT JOIN 
  cheques c 
  ON r.id = c.id_rendicion_banco
WHERE 
  r.fecha = :fecha_actual
GROUP BY 
  r.id, r.fecha, r.total_efectivo, r.total_cheques, r.total_general, 
  r.billetes_20000, r.billetes_10000, r.billetes_2000, r.billetes_1000, 
  r.billetes_500, r.billetes_200, r.billetes_100, r.billetes_50, 
  r.billetes_20, r.billetes_10;


";


$stmt = $pdo->prepare($query);
$stmt->bindParam(':fecha_actual', $fecha_actual, PDO::PARAM_STR);
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
                                        <h1> Rendicion General</h1>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Fecha de Cierre</th>
                                                    <th>Total General</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($results as $index => $row): ?>
                                                <tr>

                                                    <td><?= htmlspecialchars($row['fecha']) ?></td>
                                                    <td>$<?= number_format($row['total_general'], 2) ?></td>
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
    $row = $results[$selectedIndex];
    ?>

                                    <div class="page">
                                        <div class="header">
                                            <h1>WOLCHUK ROLANDO RAUL</h1>
                                            <p>CUENTA CORRIENTE: 000117633/002 | CUIT: 23-23269848-9</p>
                                            <p>Fecha: <?= htmlspecialchars($row['fecha']) ?></p>
                                        </div>
                                        <div class="section">
                                            <h2>Total Efectivo</h2>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>20000</th>
                                                        <th>10000</th>
                                                        <th>2000</th>
                                                        <th>1000</th>
                                                        <th>500</th>
                                                        <th>200</th>
                                                        <th>100</th>
                                                        <th>50</th>
                                                        <th>20</th>
                                                        <th>10</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><?= $row['billetes_20000'] ?></td>
                                                        <td><?= $row['billetes_10000'] ?></td>
                                                        <td><?= $row['billetes_2000'] ?></td>
                                                        <td><?= $row['billetes_1000'] ?></td>
                                                        <td><?= $row['billetes_500'] ?></td>
                                                        <td><?= $row['billetes_200'] ?></td>
                                                        <td><?= $row['billetes_100'] ?></td>
                                                        <td><?= $row['billetes_50'] ?></td>
                                                        <td><?= $row['billetes_20'] ?></td>
                                                        <td><?= $row['billetes_10'] ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="section">
                                            <h2>Totales</h2>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Total Efectivo</td>
                                                    <td>$<?= number_format($row['total_efectivo'], 2) ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Total Cheques</td>
                                                    <td>$<?= number_format($row['total_cheques'], 2) ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Total General</td>
                                                    <td>$<?= number_format($row['total_general'], 2) ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="section">
                                            <h2>Cheques</h2>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Banco</th>
                                                        <th>Importe</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach (explode('|', $row['cheques']) as $cheque): ?>
                                                    <?php
                                                            // Extrae solo el banco y el importe
                                                            preg_match('/Banco: (.*?) - Importe: (.*)/', $cheque, $matches);
                                                            $banco = $matches[1] ?? '';
                                                            $importe = $matches[2] ?? '';
                                                        ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($banco) ?></td>
                                                        <td>$<?= number_format((float)$importe, 2) ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
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