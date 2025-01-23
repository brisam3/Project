<?php
require '../../vendor/autoload.php'; // Cargar PhpSpreadsheet con Composer

use PhpOffice\PhpSpreadsheet\IOFactory;

// Configuración
$excelFile = 'articulos.xlsx'; // Ruta del archivo Excel
$tableName = 'articulos'; // Nombre de la tabla en la base de datos
$outputFile = 'insert_articulos.sql'; // Archivo donde se guardará la consulta SQL

try {
    // Cargar el archivo Excel
    $spreadsheet = IOFactory::load($excelFile);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray(null, true, true, true); // Convertir a array

    // Verificar si hay datos
    if (count($rows) < 2) {
        die("El archivo Excel está vacío o no tiene datos válidos.\n");
    }

    // Crear la consulta SQL
    $sql = "INSERT INTO `$tableName` (`idArticulos`, `codBejerman`, `descripcion`, `codBarras`) VALUES\n";

    // Iterar por las filas del Excel (ignorando la primera fila si es cabecera)
    $values = [];
    foreach ($rows as $index => $row) {
        if ($index == 1) continue; // Saltar la cabecera

        // Leer los valores desde el Excel
        $idArticulos = trim($row['A']); // Primera columna
        $codBejerman = trim($row['B']); // Segunda columna
        $descripcion = trim($row['C']); // Tercera columna
        $codBarras = empty($row['D']) ? '0' : trim($row['D']); // Si está vacío, poner '0'

        // Agregar la fila a la consulta
        $values[] = "('$idArticulos', '$codBejerman', '$descripcion', '$codBarras')";
    }

    // Combinar los valores y terminar la consulta
    $sql .= implode(",\n", $values) . ";\n";

    // Guardar la consulta SQL en un archivo
    file_put_contents($outputFile, $sql);

    echo "Consulta SQL generada exitosamente en el archivo: $outputFile\n";

} catch (Exception $e) {
    echo "Error al procesar el archivo Excel: " . $e->getMessage() . "\n";
}
