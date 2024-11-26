<?php

require_once '../../database/Database.php';

class VentasController {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function obtenerResumenDelDia($fecha) {
        $sql = "SELECT
                    COUNT(DISTINCT c.Comp_Ppal) AS CantidadBoletas,
                    COUNT(DISTINCT c.Comp_Cliente_Cod) AS CantidadClientes,
                    SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta
                FROM comprobantes c
                JOIN detallereporte d ON c.detalleReporte_id = d.id
                WHERE d.fecha = :fecha";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':fecha' => $fecha]);
        return $stmt->fetch();
    }

    public function obtenerVentasPorPreventista($fecha) {
        $sql = "SELECT
                    CASE 
                        WHEN c.Comp_Vendedor_Cod = 101 THEN 'Mica'
                        WHEN c.Comp_Vendedor_Cod = 102 THEN 'Gustavo'
                        WHEN c.Comp_Vendedor_Cod = 103 THEN 'Chilo'
                        WHEN c.Comp_Vendedor_Cod = 104 THEN 'Alex'
                        WHEN c.Comp_Vendedor_Cod = 105 THEN 'Diego'
                        WHEN c.Comp_Vendedor_Cod = 106 THEN 'Cristian'
                        WHEN c.Comp_Vendedor_Cod = 107 THEN 'Marianela'
                        WHEN c.Comp_Vendedor_Cod = 108 THEN 'Guillermo'
                        WHEN c.Comp_Vendedor_Cod = 120 THEN 'Daniel'
                        WHEN c.Comp_Vendedor_Cod = 121 THEN 'Soledad'
                        WHEN c.Comp_Vendedor_Cod = 122 THEN 'Esteban'
                        ELSE 'Desconocido'
                    END AS Preventista,
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
                WHERE d.fecha = :fecha
                GROUP BY Preventista
                ORDER BY TotalVenta DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':fecha' => $fecha]);
        return $stmt->fetchAll();
    }

    public function obtenerVentasPorProveedor($fecha) {
        $sql = "SELECT
                    p.descripcion AS Proveedor,
                    SUM(c.Item_Cant_UM1) AS CantidadArticulos,
                    SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta
                FROM comprobantes c
                JOIN detallereporte d ON c.detalleReporte_id = d.id
                JOIN proveedores p ON c.Articulo_Prov_Habitual_Cod = p.cod_proveedor
                WHERE d.fecha = :fecha
                GROUP BY p.descripcion
                ORDER BY TotalVenta DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':fecha' => $fecha]);
        return $stmt->fetchAll();
    }

    public function obtenerVentasPorPreventistaYProveedor($fecha) {
        $sql = "SELECT
                    CASE 
                        WHEN c.Comp_Vendedor_Cod = 101 THEN 'Mica'
                        WHEN c.Comp_Vendedor_Cod = 102 THEN 'Gustavo'
                        WHEN c.Comp_Vendedor_Cod = 103 THEN 'Chilo'
                        WHEN c.Comp_Vendedor_Cod = 104 THEN 'Alex'
                        WHEN c.Comp_Vendedor_Cod = 105 THEN 'Diego'
                        WHEN c.Comp_Vendedor_Cod = 106 THEN 'Cristian'
                        WHEN c.Comp_Vendedor_Cod = 107 THEN 'Marianela'
                        WHEN c.Comp_Vendedor_Cod = 108 THEN 'Guillermo'
                        WHEN c.Comp_Vendedor_Cod = 120 THEN 'Daniel'
                        WHEN c.Comp_Vendedor_Cod = 121 THEN 'Soledad'
                        WHEN c.Comp_Vendedor_Cod = 122 THEN 'Esteban'
                        ELSE 'Desconocido'
                    END AS Preventista,
                    p.descripcion AS Proveedor,
                    SUM(c.Item_Cant_UM1) AS CantidadArticulos,
                    SUM(c.Item_Impte_Total_mon_Emision) AS TotalVenta
                FROM comprobantes c
                JOIN detallereporte d ON c.detalleReporte_id = d.id
                JOIN proveedores p ON c.Articulo_Prov_Habitual_Cod = p.cod_proveedor
                WHERE d.fecha = :fecha
                GROUP BY Preventista, p.descripcion
                ORDER BY Preventista, TotalVenta DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':fecha' => $fecha]);
        return $stmt->fetchAll();
    }

    public function obtenerArticulosMasVendidos($fecha) {
        $sql = "SELECT
                    c.Item_Articulo_Cod_Gen AS CodigoArticulo,
                    a.descripcion AS Descripcion,
                    p.descripcion AS Proveedor,
                    SUM(c.Item_Cant_UM1) AS Cantidad,
                    SUM(c.Item_Impte_Total_mon_Emision) AS MontoTotal
                FROM comprobantes c
                JOIN detallereporte d ON c.detalleReporte_id = d.id
                JOIN proveedores p ON c.Articulo_Prov_Habitual_Cod = p.cod_proveedor
                JOIN articulos a ON c.Item_Articulo_Cod_Gen = a.codBejerman
                WHERE d.fecha = :fecha
                GROUP BY c.Item_Articulo_Cod_Gen, a.descripcion, p.descripcion
                ORDER BY Cantidad DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':fecha' => $fecha]);
        return $stmt->fetchAll();
    }

    public function obtenerCajasDeYogures($fechaInicio, $fechaFin) {
        $sql = "SELECT
                    CASE 
                        WHEN c.Comp_Vendedor_Cod = 101 THEN 'Mica'
                        WHEN c.Comp_Vendedor_Cod = 102 THEN 'Gustavo'
                        WHEN c.Comp_Vendedor_Cod = 103 THEN 'Chilo'
                        WHEN c.Comp_Vendedor_Cod = 104 THEN 'Alex'
                        WHEN c.Comp_Vendedor_Cod = 105 THEN 'Diego'
                        WHEN c.Comp_Vendedor_Cod = 106 THEN 'Cristian'
                        WHEN c.Comp_Vendedor_Cod = 107 THEN 'Marianela'
                        WHEN c.Comp_Vendedor_Cod = 108 THEN 'Guillermo'
                        WHEN c.Comp_Vendedor_Cod = 120 THEN 'Daniel'
                        WHEN c.Comp_Vendedor_Cod = 121 THEN 'Soledad'
                        WHEN c.Comp_Vendedor_Cod = 122 THEN 'Esteban'
                        ELSE 'Desconocido'
                    END AS Preventista,
                    c.Item_Articulo_Cod_Gen AS CodigoYogur,
                    a.descripcion AS Descripcion,
                    SUM(c.Item_Cant_UM1) AS UnidadesVendidas,
                    SUM(c.Item_Cant_UM1) AS CajasVendidas
                FROM comprobantes c
                JOIN detallereporte d ON c.detalleReporte_id = d.id
                JOIN articulos a ON c.Item_Articulo_Cod_Gen = a.codBejerman
                WHERE d.fecha BETWEEN :fechaInicio AND :fechaFin
                AND c.Item_Articulo_Cod_Gen IN ('KIT0034', 'KIT0035')
                GROUP BY Preventista, c.Item_Articulo_Cod_Gen, a.descripcion
                ORDER BY Preventista, CajasVendidas DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':fechaInicio' => $fechaInicio, ':fechaFin' => $fechaFin]);
        return $stmt->fetchAll();
    }

    public function obtenerArticulosMasVendidosPorPreventista($fecha) {
        $sql = "SELECT
                    CASE 
                        WHEN c.Comp_Vendedor_Cod = 101 THEN 'Mica'
                        WHEN c.Comp_Vendedor_Cod = 102 THEN 'Gustavo'
                        WHEN c.Comp_Vendedor_Cod = 103 THEN 'Chilo'
                        WHEN c.Comp_Vendedor_Cod = 104 THEN 'Alex'
                        WHEN c.Comp_Vendedor_Cod = 105 THEN 'Diego'
                        WHEN c.Comp_Vendedor_Cod = 106 THEN 'Cristian'
                        WHEN c.Comp_Vendedor_Cod = 107 THEN 'Marianela'
                        WHEN c.Comp_Vendedor_Cod = 108 THEN 'Guillermo'
                        WHEN c.Comp_Vendedor_Cod = 120 THEN 'Daniel'
                        WHEN c.Comp_Vendedor_Cod = 121 THEN 'Soledad'
                        WHEN c.Comp_Vendedor_Cod = 122 THEN 'Esteban'
                        ELSE 'Desconocido'
                    END AS Preventista,
                    c.Item_Articulo_Cod_Gen AS CodigoArticulo,
                    a.descripcion AS Descripcion,
                    p.descripcion AS Proveedor,
                    SUM(c.Item_Cant_UM1) AS Cantidad,
                    SUM(c.Item_Impte_Total_mon_Emision) AS MontoTotal
                FROM comprobantes c
                JOIN detallereporte d ON c.detalleReporte_id = d.id
                JOIN proveedores p ON c.Articulo_Prov_Habitual_Cod = p.cod_proveedor
                JOIN articulos a ON c.Item_Articulo_Cod_Gen = a.codBejerman
                WHERE d.fecha = :fecha
                GROUP BY Preventista, c.Item_Articulo_Cod_Gen, a.descripcion, p.descripcion
                ORDER BY Preventista, Cantidad DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':fecha' => $fecha]);
        return $stmt->fetchAll();
    }
}
?>
