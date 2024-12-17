<?php
require_once('../../database/Database.php');

$db = new Database();
$pdo = $db->getConnection();

// Filtrado por nombre de chofer o preventista
$chofer = isset($_GET['chofer']) ? $_GET['chofer'] : '';
$preventista = isset($_GET['preventista']) ? $_GET['preventista'] : '';

$query = "
    SELECT c.*, 
           chofer.nombre AS nombre_chofer, 
           preventista.nombre AS nombre_preventista
    FROM rendicion_choferes c
    JOIN usuarios chofer ON c.idUsuarioChofer = chofer.idUsuario
    JOIN usuarios preventista ON c.idUsuarioPreventista = preventista.idUsuario
    WHERE DATE(c.fecha) = CURDATE()
";

$params = [];
if ($chofer) {
    $query .= " AND chofer.nombre LIKE :chofer";
    $params['chofer'] = "%$chofer%";
}
if ($preventista) {
    $query .= " AND preventista.nombre LIKE :preventista";
    $params['preventista'] = "%$preventista%";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Cierres de Caja - Hoy</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        h1 { text-align: center; }

        .card {
            background: white;
            padding: 20px;
            margin: 20px auto;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            max-width: 800px;
            position: relative;
            page-break-after: always;
        }
        .card h2 {
            margin: 0 0 10px;
            color: #333;
        }
        .card p {
            margin: 5px 0;
            font-size: 16px;
        }
        .card strong { color: #555; }

        .btn-print {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            position: absolute;
            right: 20px;
            top: 20px;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .card, .card * {
                visibility: visible;
            }
            .card {
                position: absolute;
                top: 0;
                left: 0;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
    <script>
        function printCard(id) {
            const card = document.getElementById(id);
            const originalContents = document.body.innerHTML;
            document.body.innerHTML = card.outerHTML;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</head>
<body>
    <h1>Listado de Cierres de Caja - Fecha de Hoy</h1>

    <!-- Formulario de Filtros -->
    <form method="GET" action="index.php" style="text-align: center; margin-bottom: 20px;">
        <label for="chofer">Filtrar por Chofer:</label>
        <input type="text" name="chofer" id="chofer" value="<?= htmlspecialchars($chofer) ?>">

        <label for="preventista">Filtrar por Preventista:</label>
        <input type="text" name="preventista" id="preventista" value="<?= htmlspecialchars($preventista) ?>">

        <button type="submit">Filtrar</button>
    </form>

    <!-- Tarjetas de Datos -->
    <?php foreach ($results as $index => $row): ?>
        <div class="card" id="card-<?= $index ?>">
            <button class="btn-print" onclick="printCard('card-<?= $index ?>')">Imprimir</button>
            <h2>Fecha: <?= htmlspecialchars($row['fecha']) ?></h2>
            <p><strong>Chofer:</strong> <?= htmlspecialchars($row['nombre_chofer']) ?></p>
            <p><strong>Preventista:</strong> <?= htmlspecialchars($row['nombre_preventista']) ?></p>
            <p><strong>Total Efectivo:</strong> <?= number_format($row['total_efectivo'], 2) ?></p>
            <p><strong>Total Transferencia:</strong> <?= number_format($row['total_transferencia'], 2) ?></p>
            <p><strong>Total General:</strong> <?= number_format($row['total_general'], 2) ?></p>
            <p><strong>Billetes de 20000:</strong> <?= $row['billetes_20000'] ?></p>
            <p><strong>Billetes de 10000:</strong> <?= $row['billetes_10000'] ?></p>
            <p><strong>Billetes de 2000:</strong> <?= $row['billetes_2000'] ?></p>
            <p><strong>Billetes de 1000:</strong> <?= $row['billetes_1000'] ?></p>
            <p><strong>Total MEC Faltante:</strong> <?= number_format($row['total_mec_faltante'], 2) ?></p>
            <p><strong>Total Rechazos:</strong> <?= number_format($row['total_rechazos'], 2) ?></p>
            <p><strong>Contrareembolso:</strong> <?= number_format($row['contrareembolso'], 2) ?></p>
        </div>
    <?php endforeach; ?>
</body>
</html>
