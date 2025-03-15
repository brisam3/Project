<?php
require '../../../vendor/autoload.php'; // Carga PhpSpreadsheet
require '../../../database/Database.php'; // Asegúrate de que el archivo Database.php esté en el mismo directorio

use PhpOffice\PhpSpreadsheet\IOFactory;

header('Content-Type: application/json');

try {
    // Aumentar el tiempo máximo de ejecución a 5 minutos (300 segundos)
    set_time_limit(300);

    // Verificar si el archivo fue subido
    if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_FILES["file"])) {
        throw new Exception("Error: No se ha enviado un archivo.");
    }

    $file = $_FILES["file"]["tmp_name"];

    if (!file_exists($file)) {
        throw new Exception("Error: No se encontró el archivo subido.");
    }

    // Cargar archivo Excel
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray(null, true, true, true);

    if (empty($data) || count($data) < 2) {
        throw new Exception("Error: El archivo Excel está vacío o tiene formato incorrecto.");
    }

    // Conexión a la base de datos
    $db = new Database();
    $pdo = $db->getConnection();
    
    // Desactivar autocommit para mejorar rendimiento
    $pdo->beginTransaction();

    // Crear consulta SQL con inserciones en lote
    $sql = "INSERT INTO articulos_locales (codigo, articulo_nombre) VALUES ";
    $updateSql = " ON DUPLICATE KEY UPDATE articulo_nombre = VALUES(articulo_nombre)";

    $values = [];
    $params = [];
    $rowCount = 0;
    $batchSize = 500; // Procesar en lotes de 500 filas

    foreach ($data as $rowIndex => $row) {
        if ($rowIndex == 1) continue; // Saltar la primera fila (encabezados)

        $codigo = trim($row['A']); // Primera columna (Código)
        $articulo_nombre = trim($row['B']); // Segunda columna (Artículo Nombre)

        if (!empty($codigo) && !empty($articulo_nombre)) {
            $values[] = "(?, ?)";
            $params[] = $codigo;
            $params[] = $articulo_nombre;
            $rowCount++;

            // Ejecutar en lotes de 500 registros
            if ($rowCount % $batchSize == 0) {
                $stmt = $pdo->prepare($sql . implode(",", $values) . $updateSql);
                $stmt->execute($params);
                $values = [];
                $params = [];
            }
        }
    }

    // Insertar los registros restantes
    if (!empty($values)) {
        $stmt = $pdo->prepare($sql . implode(",", $values) . $updateSql);
        $stmt->execute($params);
    }

    // Confirmar transacción
    $pdo->commit();

    echo json_encode(["status" => "success", "message" => "Importacion completada. Se insertaron o actualizaron $rowCount registros."]);

} catch (Exception $e) {
    // Revertir cambios si hay error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>