<?php
session_start();
require '../../../vendor/autoload.php'; // PHPSpreadsheet autoload
require '../../../database/Database.php'; // Clase de conexión

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_FILES['excelFile'])) {
    try {
        $file = $_FILES['excelFile']['tmp_name'];

        if (!$file) {
            echo 'Error: No se ha seleccionado ningún archivo.';
            exit;
        }

        // Obtener la conexión desde la clase Database
        $database = new Database();
        $pdo = $database->getConnection();

        // Leer el archivo Excel
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();

        echo "Archivo cargado correctamente. Total filas: $highestRow.<br>";

        date_default_timezone_set('America/Argentina/Buenos_Aires'); // Ajusta según tu zona horaria
        $today = date('Y-m-d');
        
        $stmtCheck = $pdo->prepare("SELECT idDetalleDevPreventa FROM detalledevolucionespreventa WHERE fecha = :fecha");
        $stmtCheck->execute([':fecha' => $today]);
        if ($stmtCheck->fetch()) {
            echo "Ya existe un reporte cargado para hoy ($today).<br>";
            exit;
        }

        // Insertar un nuevo registro en `detalledevolucionespreventa`
        $stmtInsertDetail = $pdo->prepare("INSERT INTO detalledevolucionespreventa (fecha) VALUES (:fecha)");
        $stmtInsertDetail->execute([':fecha' => $today]);
        $idDetalleDevPreventa = $pdo->lastInsertId();

        echo "Detalle de devolución creado con ID: $idDetalleDevPreventa.<br>";

        // Preparar datos para una sola inserción masiva
        $rows = [];
        for ($row = 2; $row <= $highestRow; $row++) {
            $rows[] = [
                $worksheet->getCell("A{$row}")->getValue(), // comp_fecha_emision
                $worksheet->getCell("B{$row}")->getValue(), // comp_ppal
                $worksheet->getCell("C{$row}")->getValue(), // comp_vendedor_cod
                $worksheet->getCell("D{$row}")->getValue(), // vendedor
                $worksheet->getCell("E{$row}")->getValue(), // comp_cliente_cod
                $worksheet->getCell("F{$row}")->getValue(), // comp_cliente_razon_social
                $worksheet->getCell("G{$row}")->getValue(), // comp_cliente_zona
                $worksheet->getCell("H{$row}")->getValue(), // cliente_direccion
                $worksheet->getCell("I{$row}")->getValue(), // cliente_telefono
                $worksheet->getCell("J{$row}")->getValue(), // cliente_email
                $worksheet->getCell("K{$row}")->getValue(), // item_articulo_cod_gen
                $worksheet->getCell("L{$row}")->getValue(), // item_desc
                $worksheet->getCell("M{$row}")->getValue(), // item_cant_um1
                $worksheet->getCell("N{$row}")->getValue(), // item_articulo_partida
                $worksheet->getCell("O{$row}")->getValue(), // item_precio_unitario
                $worksheet->getCell("P{$row}")->getValue(), // item_importe_total
                $worksheet->getCell("Q{$row}")->getValue(), // causa_emision
                $idDetalleDevPreventa
            ];
        }

        // Generar una consulta SQL para inserción masiva
        $placeholders = rtrim(str_repeat('(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?), ', count($rows)), ', ');
        $sql = "
            INSERT INTO devolucionespreventa (
                comp_fecha_emision, comp_ppal, comp_vendedor_cod, vendedor,
                comp_cliente_cod, comp_cliente_razon_social, comp_cliente_zona,
                cliente_direccion, cliente_telefono, cliente_email, item_articulo_cod_gen,
                item_desc, item_cant_um1, item_articulo_partida, item_precio_unitario,
                item_importe_total, causa_emision, idDetalleDevPreventa
            ) VALUES $placeholders
        ";

        // Aplanar el array multidimensional para la consulta
        $flattenedRows = array_merge(...$rows);

        // Ejecutar la inserción masiva
        $stmt = $pdo->prepare($sql);
        $stmt->execute($flattenedRows);

        echo "Se importaron " . count($rows) . " filas correctamente.<br>";
    } catch (Exception $e) {
        echo "Error durante la importación: " . $e->getMessage();
    }
} else {
    echo "No se recibió un archivo válido.";
}

?>
