<?php
session_start();
header('Content-Type: application/json');

include_once '../../../database/Database.php';

error_log("Controlador para registrar asistencia iniciado.");

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Decodificar datos recibidos en formato JSON
        $input = json_decode(file_get_contents("php://input"), true);

        $idUsuario = $input['idUsuario'] ?? null;
        $estado = $input['estado'] ?? null;
        $observaciones = $input['observaciones'] ?? null;

        // Validar datos obligatorios
        if (empty($idUsuario) || empty($estado)) {
            echo json_encode([
                "status" => "error",
                "message" => "El idUsuario y el estado son obligatorios."
            ]);
            exit;
        }

        // Conexión a la base de datos
        $database = new Database();
        $db = $database->getConnection();

        // Verificar si ya existe una asistencia para este usuario en el día
        $queryCheck = "SELECT * FROM asistencias 
                       WHERE idUsuario = :idUsuario AND fecha = CURDATE()";
        $stmtCheck = $db->prepare($queryCheck);
        $stmtCheck->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            // Si ya existe asistencia, devolver mensaje de error
            echo json_encode([
                "status" => "error",
                "message" => "Ya se ha registrado asistencia para este usuario hoy. Comuníquese con el administrador."
            ]);
            exit;
        }

        // Insertar nueva asistencia
        $queryInsert = "INSERT INTO asistencias (idUsuario, fecha, hora, estado, observaciones)
                        VALUES (:idUsuario, CURDATE(), CURTIME(), :estado, :observaciones)";
        $stmtInsert = $db->prepare($queryInsert);
        $stmtInsert->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmtInsert->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmtInsert->bindParam(':observaciones', $observaciones, PDO::PARAM_STR);

        if ($stmtInsert->execute()) {
            echo json_encode([
                "status" => "success",
                "message" => "Asistencia registrada exitosamente."
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "No se pudo registrar la asistencia. Intente nuevamente."
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Método de solicitud no válido."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Error del servidor: " . $e->getMessage()
    ]);
    error_log("Error en registrarAsistenciaController.php: " . $e->getMessage());
}
?>
