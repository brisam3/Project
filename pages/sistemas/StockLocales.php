<?php
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../database/Database.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;


// Configurar la zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Conexión a la base de datos
$db = new Database();
$pdo = $db->getConnection();

// Obtener la fecha actual
$fechaActual = date('Y-m-d');

try {
    // Consulta SQL para obtener las inserciones de hoy
    $sql = "
    SELECT d.idDetalleConteo, d.fechaHora, u.idUsuario, u.nombre
    FROM detalleconteostock d
    INNER JOIN usuarios u ON d.idUsuario = u.idUsuario
    WHERE DATE(d.fechaHora) = :fechaActual
    ORDER BY d.fechaHora DESC;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['fechaActual' => $fechaActual]);
    $inserciones = $stmt->fetchAll();

} catch (PDOException $e) {
    echo "Error al ejecutar la consulta: " . $e->getMessage();
    exit;
}

// Generar Excel para una inserción específica
if (isset($_GET['generate_excel'])) {
    $idDetalleConteo = $_GET['id'];

    try {
        // Consulta para obtener los datos del Excel
        $sqlExcel = "
        SELECT 
            c.codBejerman AS articulo,
            c.cantidad,
            'S' AS actualiza,
            '2' AS sseguridad
        FROM conteo_stock c
        WHERE c.idDetalleConteo = :idDetalleConteo;
        ";

        $stmt = $pdo->prepare($sqlExcel);
        $stmt->execute(['idDetalleConteo' => $idDetalleConteo]);
        $data = $stmt->fetchAll();

        if (!$data) {
            echo "No se encontraron datos para generar el Excel.";
            exit;
        }

        // Crear el archivo Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados de la tabla
        $sheet->setCellValue('A1', 'articulo');
        $sheet->setCellValue('B1', 'cantidad');
        $sheet->setCellValue('C1', 'actualiza');
        $sheet->setCellValue('D1', 'sseguridad');

        // Agregar los datos a las filas
        $row = 2;
        foreach ($data as $record) {
            $sheet->setCellValue("A$row", $record['articulo']);
            $sheet->setCellValue("B$row", $record['cantidad']);
            $sheet->setCellValue("C$row", $record['actualiza']);
            $sheet->setCellValue("D$row", $record['sseguridad']);
            $row++;
        }

        // Guardar y forzar la descarga del archivo Excel
        $fileName = "detalle_conteo_$idDetalleConteo.csv";

        $writer = new Csv($spreadsheet);
$writer->setEnclosure('');


        header('Content-Type: text/csv');

        header("Content-Disposition: attachment; filename=\"$fileName\"");
        $writer->save('php://output');
        exit;

    } catch (PDOException $e) {
        echo "Error al generar el Excel: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../../assets/" data-template="horizontal-menu-template">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserciones de Hoy</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.1/css/boxicons.min.css" rel="stylesheet"
        integrity="sha512-cfBUsnQh7OSdceLgoYe8n5f4gR8wMSAEPr7iZYswqlN4OrcKUYxxCa5XPrp2XrtH0nXGGaOb7SfiI4Rkzr3psA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../../assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="../../../assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/apex-charts/apex-charts.css" />

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

   

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: center;
    }

    th {
        background-color: #f4f4f4;
    }

    a.button {
        display: inline-block;
        padding: 8px 15px;
        color: white;
        background-color: #4CAF50;
        text-decoration: none;
        border-radius: 5px;
    }

    a.button:hover {
        background-color: #45a049;
    }
    </style>
</head>

<body>
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">
            <!-- Nav -->
            <?php include "../template/nav.php"; ?>
            <div class="container-xxl flex-grow-1 container-p-y">

                <!-- Page content -->
                <div class="layout-page">
                    <div class="content-wrapper">
                        <h1>Controles de Stock</h1>
                        <p>Lista de inserciones realizadas hoy (<?php echo $fechaActual; ?>):</p>

                        <?php if (count($inserciones) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha y Hora</th>
                                    <th>Usuario</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inserciones as $insercion): ?>
                                <tr>
                                    <td><?php echo $insercion['idDetalleConteo']; ?></td>
                                    <td><?php echo $insercion['fechaHora']; ?></td>
                                    <td><?php echo $insercion['nombre']; ?></td>
                                    <td>
                                        <a class="button"
                                            href="?generate_excel=1&id=<?php echo $insercion['idDetalleConteo']; ?>">Generar
                                            Excel</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <p>No se encontraron inserciones para el día de hoy.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

</body>


</html>