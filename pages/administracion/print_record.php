
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
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f4f4f4; }
        .btn-print { margin: 20px 0; padding: 10px; background-color: #28a745; color: white; border: none; cursor: pointer; }
    </style>
    <script>
        function printTable() {
            window.print();
        }
    </script>
</head>
<body>
    <h1>Listado de Cierres de Caja - Fecha de Hoy</h1>

    <!-- Formulario de Filtros -->
    <form method="GET" action="index.php">
        <label for="chofer">Filtrar por Chofer:</label>
        <input type="text" name="chofer" id="chofer" value="<?= htmlspecialchars($chofer) ?>">

        <label for="preventista">Filtrar por Preventista:</label>
        <input type="text" name="preventista" id="preventista" value="<?= htmlspecialchars($preventista) ?>">

        <button type="submit">Filtrar</button>
    </form>

    <!-- Botón para Imprimir -->
    <button class="btn-print" onclick="printTable()">Imprimir Página</button>

    <!-- Tabla de Datos -->
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Chofer</th>
                <th>Preventista</th>
                <th>Total Efectivo</th>
                <th>Total Transferencia</th>
                <th>Total General</th>
                <th>Billetes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['fecha']) ?></td>
                    <td><?= htmlspecialchars($row['nombre_chofer']) ?></td>
                    <td><?= htmlspecialchars($row['nombre_preventista']) ?></td>
                    <td><?= number_format($row['total_efectivo'], 2) ?></td>
                    <td><?= number_format($row['total_transferencia'], 2) ?></td>
                    <td><?= number_format($row['total_general'], 2) ?></td>
                    <td>
                        <ul style="text-align: left; list-style: none; padding: 0; margin: 0;">
                            <li>Billetes de 20000: <?= $row['billetes_20000'] ?></li>
                            <li>Billetes de 10000: <?= $row['billetes_10000'] ?></li>
                            <li>Billetes de 2000: <?= $row['billetes_2000'] ?></li>
                            <li>Billetes de 1000: <?= $row['billetes_1000'] ?></li>
                            <li>Billetes de 500: <?= $row['billetes_500'] ?></li>
                            <li>Billetes de 200: <?= $row['billetes_200'] ?></li>
                            <li>Billetes de 100: <?= $row['billetes_100'] ?></li>
                            <li>Billetes de 50: <?= $row['billetes_50'] ?></li>
                            <li>Billetes de 20: <?= $row['billetes_20'] ?></li>
                            <li>Billetes de 10: <?= $row['billetes_10'] ?></li>
                        </ul>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
