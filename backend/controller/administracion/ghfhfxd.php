$ventasQuery = "
    SELECT idUsuarioPreventista, SUM(total_menos_gastos) AS total_menos_gastos
    FROM rendicion_choferes 
    WHERE fecha BETWEEN :fecha_inicio AND :fecha_fin
    GROUP BY idUsuarioPreventista
";
$stmtVentas = $pdo->prepare($ventasQuery);
$stmtVentas->execute([
    'fecha_inicio' => $fecha_inicio,
    'fecha_fin' => $fecha_fin
]);
$ventas = $stmtVentas->fetchAll(PDO::FETCH_ASSOC);

// Registro de depuración
error_log("Ventas por móvil en rango de fechas agrupadas: " . print_r($ventas, true));
