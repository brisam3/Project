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

        // Validar si ya existe un reporte para hoy
        $today = date('Y-m-d');
        $stmtCheck = $pdo->prepare("SELECT id FROM detallereporte WHERE fecha = :fecha");
        $stmtCheck->execute([':fecha' => $today]);
        if ($stmtCheck->fetch()) {
            echo "Ya existe un reporte cargado para hoy ($today).<br>";
            exit;
        }

        // Insertar un nuevo registro en `detallereporte`
        $stmtInsertDetail = $pdo->prepare("INSERT INTO detallereporte (fecha) VALUES (:fecha)");
        $stmtInsertDetail->execute([':fecha' => $today]);
        $detallereporteId = $pdo->lastInsertId();

        echo "Reporte creado con ID: $detallereporteId.<br>";

        // Preparar datos para una sola inserción masiva
        $rows = [];
        for ($row = 2; $row <= $highestRow; $row++) {
            $rows[] = [
                $worksheet->getCell("A{$row}")->getValue(),
                $worksheet->getCell("B{$row}")->getValue(),
                $worksheet->getCell("C{$row}")->getValue(),
                $worksheet->getCell("D{$row}")->getValue(),
                $worksheet->getCell("E{$row}")->getValue(),
                $worksheet->getCell("F{$row}")->getValue(),
                $worksheet->getCell("G{$row}")->getValue(),
                $worksheet->getCell("H{$row}")->getValue(),
                $worksheet->getCell("I{$row}")->getValue(),
                $worksheet->getCell("J{$row}")->getValue(),
                $worksheet->getCell("K{$row}")->getValue(),
                $worksheet->getCell("L{$row}")->getValue(),
                $worksheet->getCell("M{$row}")->getValue(),
                $worksheet->getCell("N{$row}")->getValue(),
                $detallereporteId,
                $worksheet->getCell("O{$row}")->getValue(),
            ];
        }

        // Generar una consulta SQL para inserción masiva
        $placeholders = rtrim(str_repeat('(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?), ', count($rows)), ', ');
        $sql = "
            INSERT INTO comprobantes (
                Comp_Ppal, Comp_Ppal_Nro, Comp_Cliente_Cod, Comp_Cliente_RazonSocial,
                Comp_Cliente_Zona, Articulo_Prov_Habitual_Cod, Item_Articulo_Cod_Gen,
                Item_Desc, Item_Cant_UM1, Item_Articulo_Partida, Item_Pr_Unitario,
                Item_Impte_Dto_x_Item_mon_Emision, Item_Impte_No_Grav_mon_Emision,
                Item_Impte_Total_mon_Emision, detalleReporte_id, Comp_Vendedor_Cod
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
