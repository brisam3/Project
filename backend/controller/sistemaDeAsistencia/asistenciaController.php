<?php
session_start();
header('Content-Type: application/json');

include_once '../../../database/Database.php';

error_log("Script iniciado correctamente.");

try {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Instanciar la conexión
        $database = new Database();
        $db = $database->getConnection();

        error_log("Conexión a la base de datos exitosa.");

        // Consulta para obtener a los preventistas junto con el estado de asistencia
        $query = "
    SELECT 
        u.idUsuario, 
        u.nombre, 
        a.estado AS asistencia,
        a.fecha AS fechaAsistencia,
        a.turno AS turno
    FROM usuarios u
    LEFT JOIN asistencias a 
        ON u.idUsuario = a.idUsuario 
        AND a.fecha = CURDATE()
    WHERE u.idTipoUsuario = 2
";

        $stmt = $db->prepare($query);
        $stmt->execute();
        $preventistas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($preventistas)) {
            echo json_encode([
                "status" => "success",
                "data" => $preventistas
            ]);
            error_log("Preventistas recuperados exitosamente.");
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "No se encontraron preventistas."
            ]);
            error_log("Error: No se encontraron preventistas.");
        }
    } else {
        throw new Exception("Método de solicitud no válido.");
    }
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Error al conectar con la base de datos: " . $e->getMessage()
    ]);
    error_log("Error al conectar con la base de datos: " . $e->getMessage());
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Error del servidor: " . $e->getMessage()
    ]);
    error_log("Error en getPreventistasController.php: " . $e->getMessage());
}
?>