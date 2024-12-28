<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'es');

date_default_timezone_set('America/Argentina/Buenos_Aires');

require_once('../../database/Database.php');

$db = new Database();
$pdo = $db->getConnection();

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

$selectedIndex = isset($_GET['print']) ? intval($_GET['print']) : -1;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rendición de Banco</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
    body {
        font-family: Arial, sans-serif;
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
        margin-bottom: 20px;
    }

    .header h1 {
        font-size: 24px;
    }

    .section {
        margin-bottom: 20px;
    }

    .total {
        font-size: 18px;
        font-weight: bold;
    }

    .table th,
    .table td {
        text-align: center;
    }

    @media print {
        .no-print {
            display: none;
        }
    }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h1 class="text-center">Informe de Rendición</h1>
        <form method="GET" action="" class="text-center mb-4">
            <label for="fecha">Seleccione Fecha: </label>
            <input type="date" id="fecha" name="fecha" value="<?= htmlspecialchars($fecha_actual) ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

        <?php if ($selectedIndex === -1): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fecha</th>
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
                            class="btn btn-success">Imprimir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <?php $row = $results[$selectedIndex]; ?>
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
</body>

</html>