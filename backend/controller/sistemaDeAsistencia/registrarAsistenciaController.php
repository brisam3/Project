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
        // Verificar si ya existe una asistencia para este usuario en el día
        $queryCheck = "SELECT * FROM asistencias 
WHERE idUsuario = :idUsuario AND fecha = CURDATE()";
        $stmtCheck = $db->prepare($queryCheck);
        $stmtCheck->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmtCheck->execute();
        $asistenciasHoy = $stmtCheck->fetchAll(PDO::FETCH_ASSOC);

        if (count($asistenciasHoy) >= 2) {
            // Si ya hay dos asistencias, no se permite más
            echo json_encode([
                "status" => "error",
                "message" => "Ya se han registrado dos asistencias para este usuario hoy."
            ]);
            exit;
        }

        // Determinar el turno de la asistencia (mañana/tarde)
        $turno = 'Mañana';
        $currentHour = date('H:i:s');
        if ($currentHour >= '12:00:00') {
            $turno = 'Tarde';
        }

        // Verificar si ya se registró el turno actual
        foreach ($asistenciasHoy as $asistencia) {
            if (
                ($turno === 'Mañana' && $asistencia['hora'] < '12:00:00') ||
                ($turno === 'Tarde' && $asistencia['hora'] >= '12:00:00')
            ) {
                echo json_encode([
                    "status" => "error",
                    "message" => "Ya se ha registrado la asistencia para el turno de la $turno hoy."
                ]);
                exit;
            }
        }

        // Insertar nueva asistencia
        $queryInsert = "INSERT INTO asistencias (idUsuario, fecha, hora, estado, turno, observaciones)
 VALUES (:idUsuario, CURDATE(), CURTIME(), :estado, :turno, :observaciones)";
        $stmtInsert = $db->prepare($queryInsert);
        $stmtInsert->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmtInsert->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmtInsert->bindParam(':turno', $turno, PDO::PARAM_STR);
        $stmtInsert->bindParam(':observaciones', $observaciones, PDO::PARAM_STR);

        if ($stmtInsert->execute()) {
            echo json_encode([
                "status" => "success",
                "message" => "Asistencia registrada exitosamente para el turno de la $turno."
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