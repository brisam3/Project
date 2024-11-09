<?php
// backend/controllers/devoluciones/locales/devolucionesController.php
session_start();



require_once '../../../../database/Database.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

class DevolucionesController {
   private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Otros métodos del controlador


    public function buscarArticulo($codigoBarras)
    {
        $query = "SELECT * FROM stock WHERE codBarras = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$codigoBarras]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            error_log("Artículo con código de barras $codigoBarras no encontrado.");
        }

        return $result;
    }

    public function registrarDevoluciones($articulos)
    {
        // Asegúrate de que el idUsuario y el idTipoUsuario estén disponibles en la sesión
        $idUsuario = $_SESSION['idUsuario'] ?? null;  // Se obtiene de la sesión, si existe
        $idTipoUsuario = $_SESSION['idTipoUsuario'] ?? null;  // Se obtiene de la sesión, si existe
    
        // Verificar si ambos valores están presentes
        if ($idUsuario === null || $idTipoUsuario === null) {
            throw new Exception("No se encontró el tipo de usuario o el idUsuario en la sesión.");
        }
    
        // Obtener la fecha y hora actual (incluyendo los microsegundos)
 
        $hora = date('Y-m-d H:i:s.u');  // Fecha y hora con microsegundos
    
        // Consulta SQL para insertar en la tabla 'devoluciones'
        $query = "INSERT INTO devoluciones (codBarras, partida, cantidad, hora, idTipoDevolucion, idUsuario) 
                  VALUES (?, ?, ?, ?, ?, ?)";
    
        // Preparamos la sentencia SQL
        $stmt = $this->db->prepare($query);
    
        // Iterar sobre los artículos y hacer la inserción
        foreach ($articulos as $articulo) {
            // Ejecutar la inserción para cada artículo, incluyendo el idTipoDevolucion y idUsuario de la sesión
            $stmt->execute([
                $articulo['codBarras'],
                $articulo['partida'],
                $articulo['cantidad'],
                $hora,   // Hora actual con microsegundos
                $idTipoUsuario,  // El idTipoUsuario se agrega aquí
                $idUsuario  // El idUsuario también se agrega aquí
            ]);
        }
    
        return true; // Indicar que la operación fue exitosa
    


}

// Manejo de las peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $controller = new DevolucionesController();
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'buscarArticulo':
                $codigoBarras = $_POST['codBarras'];
                $articulo = $controller->buscarArticulo($codigoBarras);
                echo json_encode($articulo);
                break;

            case 'registrarDevoluciones':
                $articulos = $_POST['articulos'];
                $success = $controller->registrarDevoluciones($articulos);
                echo json_encode(['success' => $success]);
                break;
        }
    }
    exit;
}}
?>

<!-- Updated HTML -->

