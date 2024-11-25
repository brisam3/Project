<?php

require_once '../../database/Database.php'; // Asegúrate de ajustar la ruta

class PreventaController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getVentasPorPreventista($fecha) {
        $sql = "
            SELECT
                u.nombre AS Preventista,
                COUNT(DISTINCT c.Comp_Ppal) AS CantidadBoletas,
                COUNT(DISTINCT c.Comp_Cliente_Cod) AS CantidadClientes,
                SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta,
                (SUM(c.Item_Impte_Total_mon_Emision) / COUNT(DISTINCT c.Comp_Ppal)) AS TicketPromedio,
                COUNT(DISTINCT c.Item_Articulo_Cod_Gen) AS VariedadArticulos,
                COUNT(DISTINCT c.Articulo_Prov_Habitual_Cod) AS VariedadProveedores,
                (COUNT(DISTINCT c.Item_Articulo_Cod_Gen) / COUNT(DISTINCT c.Comp_Cliente_Cod)) AS PromedioArticulosPorCliente,
                (COUNT(DISTINCT c.Articulo_Prov_Habitual_Cod) / COUNT(DISTINCT c.Comp_Cliente_Cod)) AS PromedioProveedoresPorCliente,
                (SUM(c.Item_Impte_Total_mon_Emision) * 0.04) AS Comision
            FROM comprobantes c
            JOIN detallereporte d ON c.detalleReporte_id = d.id
            JOIN usuarios u ON c.Comp_Vendedor_Cod = u.usuario
            WHERE d.fecha = :fecha
            GROUP BY u.nombre
            ORDER BY TotalVenta DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Ejemplo de uso
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fecha'])) {
    $controller = new PreventaController();
    $fecha = $_GET['fecha'];
    $resultados = $controller->getVentasPorPreventista($fecha);
    echo json_encode($resultados);
}

?>
