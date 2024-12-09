<?php
session_start();
header('Content-Type: application/json');

include_once '../../../database/Database.php';

try {
    // Crear conexión a la base de datos
    $database = new Database();
    $db = $database->getConnection();

    // Consulta para obtener los datos de asistencia
    $query = "
        SELECT 
            a.idAsistencia,
            u.nombre AS preventista,
            a.fecha,
            a.hora,
            a.estado,
            a.observaciones
        FROM asistencias a
        INNER JOIN usuarios u ON a.idUsuario = u.idUsuario
        WHERE u.idTipoUsuario = 2
        ORDER BY a.fecha DESC, a.hora DESC
    ";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Respuesta JSON
    echo json_encode([
        "status" => "success",
        "data" => $asistencias
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Error al conectar con la base de datos: " . $e->getMessage()
    ]);
    error_log("Error en reporteAsistenciasController.php: " . $e->getMessage());
}
?>
