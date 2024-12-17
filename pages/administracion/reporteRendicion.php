<?php
require '../../vendor/autoload.php';  // Esta es la forma correcta de incluir todas las dependencias de Composer



// El resto de tu código...
$pdf = new Fpdf(); // Ahora puedes crear la instancia de FPDF


$host = "localhost";
$user = "root";
$pass = "";
$dbname = "wolchuk";
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
// Consulta para obtener los datos con JOIN
// Consulta para obtener los datos con JOIN
// Consulta SQL para obtener los registros
$sql = "SELECT * FROM rendicion_choferes";  // Ajusta el nombre de la tabla si es necesario
$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Crear un nuevo objeto FPDF para cada registro
    while ($row = $result->fetch_assoc()) {
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Establecer título
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(200, 10, 'Rendicion Choferes - ' . $row['id'], 0, 1, 'C');
        
        // Establecer fuente para los detalles
        $pdf->SetFont('Arial', '', 12);

        // Imprimir los campos de cada registro
        $pdf->Cell(100, 10, 'ID Usuario Chofer: ' . $row['idUsuarioChofer'], 0, 1);
        $pdf->Cell(100, 10, 'ID Usuario Preventista: ' . $row['idUsuarioPreventista'], 0, 1);
        $pdf->Cell(100, 10, 'Fecha: ' . $row['fecha'], 0, 1);
        $pdf->Cell(100, 10, 'Total Efectivo: ' . $row['total_efectivo'], 0, 1);
        $pdf->Cell(100, 10, 'Total Transferencia: ' . $row['total_transferencia'], 0, 1);
        $pdf->Cell(100, 10, 'Total Mercado Pago: ' . $row['total_mercadopago'], 0, 1);
        $pdf->Cell(100, 10, 'Total Cheques: ' . $row['total_cheques'], 0, 1);
        $pdf->Cell(100, 10, 'Total Fiados: ' . $row['total_fiados'], 0, 1);
        $pdf->Cell(100, 10, 'Total Gastos: ' . $row['total_gastos'], 0, 1);
        $pdf->Cell(100, 10, 'Pago Secretario: ' . $row['pago_secretario'], 0, 1);
        $pdf->Cell(100, 10, 'Total General: ' . $row['total_general'], 0, 1);
        $pdf->Cell(100, 10, 'Total Menos Gastos: ' . $row['total_menos_gastos'], 0, 1);
        // Agregar más campos según sea necesario

        // Guardar el PDF con un nombre único basado en el ID
        $pdf->Output('F', 'C:/xampp/htdocs/folletoWolchuck/pdfs/rendicion_' . $row['id'] . '.pdf');
    }
    echo "Archivos PDF generados correctamente.";
} else {
    echo "No se encontraron registros.";
}

$conn->close();
?>
