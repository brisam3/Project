<?php

include '../../backend/controller/access/AccessController.php';

$accessController = new AccessController();

// Verificar si el acceso está permitido
if (!$accessController->checkAccess('/pages/administracion/PrintRendicionGeneral.php')) {
    $accessController->denyAccess();
    exit;
}

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


// Segundo, hacer la consulta a la tabla rendicion_general_choferes para obtener el id de la fecha actual
$query_rendicion_general_choferes = "
   SELECT 	idRendicionGeneral 
   FROM rendicion_general_choferes
   WHERE fecha = :fecha_actual
";

$stmt_choferes = $pdo->prepare($query_rendicion_general_choferes);
$stmt_choferes->execute([':fecha_actual' => $fecha_actual]);
$rendicion_general_choferes = $stmt_choferes->fetch(PDO::FETCH_ASSOC);

// Si obtenemos un id de rendicion_general_choferes, hacemos la consulta en rendicion_libre
if ($rendicion_general_choferes) {
   $id_rendicion_choferes = $rendicion_general_choferes['idRendicionGeneral'];

   $query_rendicion_libre = "
      SELECT 
         SUM(rl.billetes_20000) AS total_billetes_20000,
         SUM(rl.billetes_10000) AS total_billetes_10000,
         SUM(rl.billetes_2000) AS total_billetes_2000,
         SUM(rl.billetes_1000) AS total_billetes_1000,
         SUM(rl.billetes_500) AS total_billetes_500,
         SUM(rl.billetes_200) AS total_billetes_200,
         SUM(rl.billetes_100) AS total_billetes_100,
         SUM(rl.billetes_50) AS total_billetes_50,
         SUM(rl.billetes_20) AS total_billetes_20,
         SUM(rl.billetes_10) AS total_billetes_10
      FROM rendicion_libre rl
      WHERE rl.idRendicionGeneral = :id_rendicion_general
   ";

   $stmt_libre = $pdo->prepare($query_rendicion_libre);
   $stmt_libre->execute([':id_rendicion_general' => $id_rendicion_choferes]);
   $rendicion_libre = $stmt_libre->fetch(PDO::FETCH_ASSOC);

   // Mostrar el resultado en la consola PHP
   //var_dump($rendicion_libre);
}
   // O usar print_r() si prefieres un formato más legible
   // print_r($rendicion_libre);
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
                                            <p style="text-align: right;">Fecha: <?= htmlspecialchars($row['fecha']) ?>
                                            </p>
                                            <h1>WOLCHUK ROLANDO RAUL</h1>
                                            <p>CUENTA CORRIENTE: 000117633/002 | CUIT: 23-23269848-9</p>

                                        </div>
                                        <div class="section">
                                            <h2>Total Efectivo</h2>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Denominación</th>
                                                        <th>Cantidad</th>
                                                        <!-- Cambié el nombre de la columna -->
                                                        <th>Total</th>
                                                        <!-- Cambié el nombre de la columna -->
                                                        <th>Salidas</th>
                                                        <!-- Nueva columna para la cantidad de billetes en libres -->
                                                        <th>Total Salidas</th>
                                                        <!-- Nueva columna para el total en libres -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                // Denominaciones de la rendición general
                $denominaciones = [
                    20000 => $row['billetes_20000'],
                    10000 => $row['billetes_10000'],
                    2000 => $row['billetes_2000'],
                    1000 => $row['billetes_1000'],
                    500 => $row['billetes_500'],
                    200 => $row['billetes_200'],
                    100 => $row['billetes_100'],
                    50 => $row['billetes_50'],
                    20 => $row['billetes_20'],
                    10 => $row['billetes_10'],
                ];
                
                // Sumar los totales de efectivo (rendición general)
                $total_efectivo = 0;
                // Sumar los totales de libres
                $total_efectivo_libres = 0;
                
                // Recorrer las denominaciones
                foreach ($denominaciones as $denominacion => $cantidad) {
                    // Calcular el subtotal para cada denominación
                    $subtotal = $denominacion * $cantidad;
                    $total_efectivo += $subtotal;
                    
                    // Obtener los valores correspondientes de la consulta 'rendicion_libre'
                    $cantidad_libres = $rendicion_libre["total_billetes_$denominacion"] ?? 0;
                    $total_salida = $cantidad_libres * $denominacion;
                    $total_efectivo_libres += $total_salida;
            ?>
                                                    <tr>
                                                        <!-- Mostrar denominación -->
                                                        <td>$<?= number_format($denominacion, 0, ',', '.') ?></td>
                                                        <!-- Mostrar cantidad de billetes en la rendición general -->
                                                        <td><?= $cantidad ?></td>
                                                        <!-- Mostrar el total de la rendición general -->
                                                        <td>$<?= number_format($subtotal, 2, ',', '.') ?></td>
                                                        <!-- Mostrar la cantidad de billetes de la consulta 'rendicion_libre' -->
                                                        <td><?= $cantidad_libres ?></td>
                                                        <!-- Mostrar el total de salida de la consulta 'rendicion_libre' -->
                                                        <td>$<?= number_format($total_salida, 2, ',', '.') ?></td>
                                                    </tr>
                                                    <?php } ?>
                                                    <!-- Total Efectivo (Rendición General) -->
                                                    <tr>

                                                        <td></td>
                                                        <td></td>
                                                        <td style="font-weight: bold;">
                                                            $<?= number_format($total_efectivo, 2, ',', '.') ?></td>

                                                        <td></td>
                                                        <td style="font-weight: bold;">
                                                            $<?= number_format($total_efectivo_libres, 2, ',', '.') ?>
                                                        </td>

                                                    </tr>
                                                    <tr>

                                                        <?php 
                                                            $diferencia = $total_efectivo - $total_efectivo_libres;
                                                            ?>

                                                        <td colspan="2">Diferencia</td>
                                                        <td colspan="3"
                                                            style="font-weight: bold; color: <?= $diferencia >= 0 ? 'green' : 'red'; ?>;">
                                                            $<?= number_format($diferencia, 2, ',', '.') ?>
                                                        </td>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="section">
                                            <h2>Totales</h2>
                                            <!-- Contenedor de la tabla -->

                                            <div
                                                style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2mm;">
                                                <div style="width: 50%; margin-left:30px">

                                                    <table class="table table-bordered"
                                                        style="width: 100%; margin: 0 auto;">
                                                        <tr>
                                                            <td style="width: 50%;">Total Efectivo</td>
                                                            <td style="width: 50%;">
                                                            <?php echo '$' . number_format($diferencia, 2); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 50%;">Total Cheques</td>
                                                            <td style="width: 50%;">
                                                                $<?= number_format($row['total_cheques'], 2) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 50%;">Total General</td>
                                                            <td style="width: 50%;">
                                                                $<?= number_format($row['total_general'], 2) ?></td>
                                                        </tr>
                                                    </table>

                                                </div>
                                                <div style="width: 50%; text-align: center;">
                                                    <img src="../../assets/img/wol.png" alt="Descripción de la imagen"
                                                        style="max-width: 35%; height: auto;">
                                                </div>

                                            </div>


                                            <!-- Contenedor de la imagen -->

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
                                                        <td style="width: 70%;"><?= htmlspecialchars($banco) ?></td>
                                                        <td style="width: 30%;">
                                                            $<?= number_format((float)$importe, 2) ?></td>
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