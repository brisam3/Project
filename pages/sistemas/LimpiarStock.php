<?php
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../database/Database.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

// Configurar la zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Conexión a la base de datos
$db = new Database();
$pdo = $db->getConnection();

try {
    // Consulta para obtener todos los artículos
    $sql = "
    SELECT 
        a.codBejerman AS articulo
    FROM articulos a;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll();

    if (!$data) {
        echo "No se encontraron artículos.";
        exit;
    }

    // Crear el archivo Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Encabezados de la tabla
    $sheet->setCellValue('A1', 'articulo');
    $sheet->setCellValue('B1', 'partida');
    $sheet->setCellValue('C1', 'cantidad');
    $sheet->setCellValue('D1', 'actualiza');
    $sheet->setCellValue('E1', 'sseguridad');

    // Agregar los datos a las filas con cantidad y partida en 0
    $row = 2;
    foreach ($data as $record) {
        $sheet->setCellValue("A$row", $record['articulo']);
        $sheet->setCellValue("B$row", 0);
        $sheet->setCellValue("C$row", 0);
        $sheet->setCellValue("D$row", 'S');
        $sheet->setCellValue("E$row", '2');
        $row++;
    }

    // Guardar y forzar la descarga del archivo Excel
    $fileName = "articulos_cantidad_cero.csv";

    $writer = new Csv($spreadsheet);
    $writer->setEnclosure('');

    header('Content-Type: text/csv');
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    $writer->save('php://output');
    exit;

} catch (PDOException $e) {
    echo "Error al generar el Excel: " . $e->getMessage();
}
?>