<?php
// Configuración inicial
session_start();
require_once('../../../database/Database.php');
date_default_timezone_set('America/Argentina/Buenos_Aires'); // Zona horaria

class DetalleRendicionController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerRendicionesConVentas() {
        $fechaHoy = date('Y-m-d'); // Fecha actual

                // Determinar si hoy es lunes para usar el sábado como referencia
                if (date('N') == 1) {
                    $yesterday = date('Y-m-d', strtotime('-2 days')); // Usar sábado
                } else {
                    $yesterday = date('Y-m-d', strtotime('-1 day')); // Usar día anterior
                }

    
        // Mapeo del orden de los preventistas
        $ordenPreventistas = [
            'Mica' => 0,
            'Gustavo' => 1,
            'Leo(Chillo)' => 2,
            'Alexander' => 3,
            'Diego' => 4,
            'Cristian' => 5,
            'Marianela' => 6,
            'Guille' => 7,
            'Soledad' => 8
        ];
    
        // Crear la cláusula ORDER BY con el orden de los preventistas
        $ordenString = '';
        foreach ($ordenPreventistas as $nombre => $indice) {
            if ($ordenString) {
                $ordenString .= ', ';
            }
            $ordenString .= "CASE WHEN up.nombre = '$nombre' THEN $indice ELSE 9999 END";
        }
    
        // Consulta para las rendiciones
        $queryRendiciones = "
            SELECT 
                rc.id,
                rc.idUsuarioChofer,
                uc.nombre AS nombre_chofer,
                rc.idUsuarioPreventista,
                up.nombre AS nombre_preventista,
                up.apellido AS movil, -- Agregar el campo movil desde apellido
                rc.fecha,
                rc.total_efectivo,
                rc.total_transferencia,
                rc.total_mercadopago,
                rc.total_cheques,
                rc.total_fiados,
                rc.total_gastos,
                rc.pago_secretario,
                rc.total_general,
                rc.total_menos_gastos,
                rc.billetes_20000,
                rc.billetes_10000,
                rc.billetes_2000,
                rc.billetes_1000,
                rc.billetes_500,
                rc.billetes_200,
                rc.billetes_100,
                rc.billetes_50,
                rc.billetes_20,
                rc.billetes_10,
                rc.total_mec_faltante,
                rc.total_rechazos
            FROM 
                rendicion_choferes rc
            LEFT JOIN 
                usuarios uc ON rc.idUsuarioChofer = uc.idUsuario
            LEFT JOIN 
                usuarios up ON rc.idUsuarioPreventista = up.idUsuario
            WHERE 
                rc.fecha = ?
            ORDER BY
                $ordenString
        ";
    
        $stmtRendiciones = $this->db->prepare($queryRendiciones);
        $stmtRendiciones->execute([$fechaHoy]);
        $rendiciones = $stmtRendiciones->fetchAll(PDO::FETCH_ASSOC);
    
        // Consulta para los totales de ventas
        $queryVentas = "
            SELECT
                u.nombre AS nombre_preventista,
                SUM(c.Item_Impte_Total_mon_Emision) AS total_ventas
            FROM 
                comprobantes c
            JOIN 
                detallereporte d ON c.detalleReporte_id = d.id
            JOIN 
                usuarios u ON TRIM(c.Comp_Vendedor_Cod) = TRIM(u.usuario)
            WHERE 
                d.fecha = :yesterday
            GROUP BY 
                u.nombre
        ";
        $stmtVentas = $this->db->prepare($queryVentas);
        $stmtVentas->execute([':yesterday' => $yesterday]);
        $ventas = $stmtVentas->fetchAll(PDO::FETCH_ASSOC);
    
        // Combinar los totales de ventas con las rendiciones
        foreach ($rendiciones as &$rendicion) {
            $rendicion['total_ventas'] = 0; // Valor por defecto
            foreach ($ventas as $venta) {
                if ($venta['nombre_preventista'] === $rendicion['nombre_preventista']) {
                    $rendicion['total_ventas'] = $venta['total_ventas'];
                    break;
                }
            }
        }
    
        return $rendiciones;
    }

    // En el controlador (DetalleRendicionController.php)
    public function obtenerCierreCajaHoy() {
        $fechaHoy = date('Y-m-d');  // Fecha de hoy en formato YYYY-MM-DD

      
         $ordenLocales = [ 
            'Obrero' => 0, 
            'Liborsi' => 1,
            'Vial' => 2, 
            'Central' => 3,
            'Eva Peron' => 4,
            'San Pedro' => 5,
            
        ];

        $ordenString = '';
        foreach ($ordenLocales as $nombre => $indice) {
            if ($ordenString) {
                $ordenString .= ', ';
            }
            $ordenString .= "CASE WHEN u.nombre = '$nombre' THEN $indice ELSE 9999 END";
        }


        $queryCierreCaja = "
            SELECT 
                cc.idcierreCaja,
                cc.idUsuario,
                u.nombre AS nombre_local,  -- Traemos el nombre del local
                cc.fecha_cierre,
                cc.efectivo,
                cc.mercado_pago,
                cc.payway,
                cc.cambios,
                cc.cuenta_corriente,
                cc.gastos,
                cc.billetes_20000,
                cc.billetes_10000,
                cc.billetes_2000,
                cc.billetes_1000,
                cc.billetes_500,
                cc.billetes_200,
                cc.billetes_100,
                cc.billetes_50,
                cc.billetes_20,
                cc.billetes_10,
                cc.total_general,
                cc.total_menos_gastos
            FROM 
                cierrecaja cc
            LEFT JOIN 
                usuarios u ON cc.idUsuario = u.idUsuario  -- Hacemos el JOIN con la tabla 'usuarios' para obtener el nombre del local
            WHERE 
                cc.fecha_cierre = ? 
            ORDER BY
                $ordenString
        ";
    
        $stmt = $this->db->prepare($queryCierreCaja);
        $stmt->execute([$fechaHoy]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Retorna los registros como un array asociativo
    }
    

    public function insertarRendicion($data) {
        $fechaHoy = date('Y-m-d'); // Fecha actual
        try {
            // Iniciar la transacción
            $this->db->beginTransaction();
    
            // Insertar en la tabla rendicion_general
            $stmt_general = $this->db->prepare("
                INSERT INTO rendicion_general_choferes (
                    fecha,
                    total_efectivo,
                    total_general_preventa,
                    total_mp,
                    total_transferencias,
                    billetes_20000,
                    billetes_10000,
                    billetes_2000,
                    billetes_1000,
                    billetes_500,
                    billetes_200,
                    billetes_100,
                    billetes_50,
                    billetes_20,
                    billetes_10
                ) VALUES (
                    :fecha,
                    :total_efectivo,
                    :total_general_preventa,
                    :total_mp,
                    :total_transferencias,
                    :billetes_20000,
                    :billetes_10000,
                    :billetes_2000,
                    :billetes_1000,
                    :billetes_500,
                    :billetes_200,
                    :billetes_100,
                    :billetes_50,
                    :billetes_20,
                    :billetes_10
                )
            ");
    
            $totales = $data['totales'];
    
            $stmt_general->execute([
                ':fecha' => $fechaHoy,
                ':total_efectivo' => $totales['total_efectivo'] ?? 0.00,
                ':total_general_preventa' => $totales['total_general_preventa'] ?? 0.00,
                ':total_mp' => $totales['total_mp'] ?? 0.00,
                ':total_transferencias' => $totales['total_transferencias'] ?? 0.00,
                ':billetes_20000' => $totales['billetes_20000'] ?? 0,
                ':billetes_10000' => $totales['billetes_10000'] ?? 0,
                ':billetes_2000' => $totales['billetes_2000'] ?? 0,
                ':billetes_1000' => $totales['billetes_1000'] ?? 0,
                ':billetes_500' => $totales['billetes_500'] ?? 0,
                ':billetes_200' => $totales['billetes_200'] ?? 0,
                ':billetes_100' => $totales['billetes_100'] ?? 0,
                ':billetes_50' => $totales['billetes_50'] ?? 0,
                ':billetes_20' => $totales['billetes_20'] ?? 0,
                ':billetes_10' => $totales['billetes_10'] ?? 0
            ]);
    
            // Obtener el ID de la rendición general recién insertada
            $idRendicionGeneral = $this->db->lastInsertId();
    
            // Insertar en la tabla rendicion_libre si existen datos
            if (!empty($data['tabla_libre'])) {
                $stmt_libre = $this->db->prepare("
                    INSERT INTO rendicion_libre (
                        idRendicionGeneral,
                        motivo,
                        billetes_20000,
                        billetes_10000,
                        billetes_2000,
                        billetes_1000,
                        billetes_500,
                        billetes_200,
                        billetes_100,
                        billetes_50,
                        billetes_20,
                        billetes_10
                    ) VALUES (
                        :idRendicionGeneral,
                        :motivo,
                        :billetes_20000,
                        :billetes_10000,
                        :billetes_2000,
                        :billetes_1000,
                        :billetes_500,
                        :billetes_200,
                        :billetes_100,
                        :billetes_50,
                        :billetes_20,
                        :billetes_10
                    )
                ");
    
                foreach ($data['tabla_libre'] as $fila) {
                    $stmt_libre->execute([
                        ':idRendicionGeneral' => $idRendicionGeneral,
                        ':motivo' => $fila['motivo'],
                        ':billetes_20000' => $fila['billetes_20000'] ?? 0,
                        ':billetes_10000' => $fila['billetes_10000'] ?? 0,
                        ':billetes_2000' => $fila['billetes_2000'] ?? 0,
                        ':billetes_1000' => $fila['billetes_1000'] ?? 0,
                        ':billetes_500' => $fila['billetes_500'] ?? 0,
                        ':billetes_200' => $fila['billetes_200'] ?? 0,
                        ':billetes_100' => $fila['billetes_100'] ?? 0,
                        ':billetes_50' => $fila['billetes_50'] ?? 0,
                        ':billetes_20' => $fila['billetes_20'] ?? 0,
                        ':billetes_10' => $fila['billetes_10'] ?? 0
                    ]);
                }
            }
    
            // Consultar chofer y preventista para rendicion_movil
            $stmt_chofer_preventista = $this->db->prepare("
            SELECT idUsuarioChofer, idUsuarioPreventista, codigo_rendicion
            FROM rendicion_choferes
            WHERE id = :id
        ");
        
    
            // Insertar en rendicion_movil
            $stmt_movil = $this->db->prepare("
                INSERT INTO rendicion_movil (
                    idUsuarioChofer,
                    idUsuarioPreventista,
                    idRendicionGeneral,
                    fecha,
                   total_ventas,
                    total_efectivo,
                    total_transferencia,
                    total_mercadopago,
                    total_cheques,
                    total_fiados,
                    total_gastos,
                    pago_secretario,
                    total_mec_faltante,
                    total_rechazos,
                    total_neto,
                    billetes_20000,
                    billetes_10000,
                    billetes_2000,
                    billetes_1000,
                    billetes_500,
                    billetes_200,
                    billetes_100,
                    billetes_50,
                    billetes_20,
                    billetes_10,
                    codigo_rendicion
                ) VALUES (
                    :idUsuarioChofer,
                    :idUsuarioPreventista,
                    :idRendicionGeneral,
                    :fecha,
                    :total_ventas,
                    :total_efectivo,
                    :total_transferencia,
                    :total_mercadopago,
                    :total_cheques,
                    :total_fiados,
                    :total_gastos,
                    :pago_secretario,
                    :total_mec_faltante,
                    :total_rechazos,
                    :total_neto,
                    :billetes_20000,
                    :billetes_10000,
                    :billetes_2000,
                    :billetes_1000,
                    :billetes_500,
                    :billetes_200,
                    :billetes_100,
                    :billetes_50,
                    :billetes_20,
                    :billetes_10,
                    :codigo_rendicion
                )
            ");
    
            foreach ($data['tabla_principal'] as $idDetalle => $movil) {
                $stmt_chofer_preventista->execute([':id' => $idDetalle]);
                $choferPreventista = $stmt_chofer_preventista->fetch(PDO::FETCH_ASSOC);
                
                if (!$choferPreventista || empty($choferPreventista['codigo_rendicion'])) {
                    throw new Exception("No se encontró código de rendición para el detalle ID: $idDetalle");
                }
                
                // Asegúrate de mapear correctamente los campos del objeto a los parámetros
                $stmt_movil->execute([
                    ':idUsuarioChofer' => $choferPreventista['idUsuarioChofer'],
                    ':idUsuarioPreventista' => $choferPreventista['idUsuarioPreventista'],
                    ':idRendicionGeneral' => $idRendicionGeneral,
                    ':fecha' => $fechaHoy,
                    ':total_ventas' => $movil['Total_Ventas'] ?? 0.00,
                    ':total_efectivo' => $movil['Total_Efectivo'] ?? 0.00,
                    ':total_transferencia' => $movil['Transferencias'] ?? 0.00,
                    ':total_mercadopago' => $movil['Mercado_Pago'] ?? 0.00,
                    ':total_cheques' => $movil['Cheques'] ?? 0.00,
                    ':total_fiados' => $movil['Fiados'] ?? 0.00,
                    ':total_gastos' => $movil['Gastos'] ?? 0.00,
                    ':pago_secretario' => $movil['Pago_Secretario'] ?? 0.00,
                    ':total_mec_faltante' => $movil['MEC_Faltante'] ?? 0.00,
                    ':total_rechazos' => $movil['Rechazos'] ?? 0.00,
                    ':total_neto' => $movil['Total_Neto'] ?? 0.00,
                    ':billetes_20000' => $movil['billetes_20000'] ?? 0,
                    ':billetes_10000' => $movil['billetes_10000'] ?? 0,
                    ':billetes_2000' => $movil['billetes_2000'] ?? 0,
                    ':billetes_1000' => $movil['billetes_1000'] ?? 0,
                    ':billetes_500' => $movil['billetes_500'] ?? 0,
                    ':billetes_200' => $movil['billetes_200'] ?? 0,
                    ':billetes_100' => $movil['billetes_100'] ?? 0,
                    ':billetes_50' => $movil['billetes_50'] ?? 0,
                    ':billetes_20' => $movil['billetes_20'] ?? 0,
                    ':billetes_10' => $movil['billetes_10'] ?? 0,
                    ':codigo_rendicion' => $choferPreventista['codigo_rendicion']
                ]);
                
            }
            
            
    
            $this->db->commit();
            return ['success' => true, 'message' => 'Datos insertados correctamente'];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function insertarRendicionLocales($data) {
        $fechaHoy = date('Y-m-d');
        try {
            // Validar que las claves necesarias existan
            if (!isset($data['totalesFusionados']) || !isset($data['principales'])) {
                throw new Exception('Datos incompletos: faltan totalesFusionados o principales.');
            }
    
            // Iniciar la transacción
            if (!$this->db->inTransaction()) {
                $this->db->beginTransaction();
            }
    
            $totalesFusionados = $data['totalesFusionados'];  // <- Modificado aquí
            $principales = $data['principales'];  // <- Modificado aquí
            
            // Insertar en la tabla rendicion_general_locales
            $stmt_general = $this->db->prepare("
                INSERT INTO rendicion_general_locales (
                    fecha,
                    total_efectivo,
                    total_general,
                    total_mp,
                    total_transferencias,
                    billetes_20000,
                    billetes_10000,
                    billetes_2000,
                    billetes_1000,
                    billetes_500,
                    billetes_200,
                    billetes_100,
                    billetes_50,
                    billetes_20,
                    billetes_10,
                    diferencia,
                    total_tarjetas
                ) VALUES (
                    :fecha,
                    :total_efectivo,
                    :total_general,
                    :total_mp,
                    :total_transferencias,
                    :billetes_20000,
                    :billetes_10000,
                    :billetes_2000,
                    :billetes_1000,
                    :billetes_500,
                    :billetes_200,
                    :billetes_100,
                    :billetes_50,
                    :billetes_20,
                    :billetes_10,
                    :diferencia,
                    :total_tarjetas
                )
            ");
    
            $stmt_general->execute([
                ':fecha' => $fechaHoy,
                ':total_efectivo' => $totalesFusionados['Total_Efectivo'] ?? 0.00,
                ':total_general' => $totalesFusionados['Total_General_Locales'] ?? 0.00,
                ':total_mp' => $totalesFusionados['Total_Tarjetas'] ?? 0.00,
                ':total_transferencias' => $totalesFusionados['Total_Gastos'] ?? 0.00,
                ':billetes_20000' => $totalesFusionados['billetes_20000'] ?? 0,
                ':billetes_10000' => $totalesFusionados['billetes_10000'] ?? 0,
                ':billetes_2000' => $totalesFusionados['billetes_2000'] ?? 0,
                ':billetes_1000' => $totalesFusionados['billetes_1000'] ?? 0,
                ':billetes_500' => $totalesFusionados['billetes_500'] ?? 0,
                ':billetes_200' => $totalesFusionados['billetes_200'] ?? 0,
                ':billetes_100' => $totalesFusionados['billetes_100'] ?? 0,
                ':billetes_50' => $totalesFusionados['billetes_50'] ?? 0,
                ':billetes_20' => $totalesFusionados['billetes_20'] ?? 0,
                ':billetes_10' => $totalesFusionados['billetes_10'] ?? 0,
                ':diferencia' => $totalesFusionados['Diferencia'] ?? 0.00,
                ':total_tarjetas' => $totalesFusionados['Total_Tarjetas'] ?? 0.00
            ]);
    
            $idRendicionGeneral = $this->db->lastInsertId();
    
            // Insertar en la tabla rendicion_locales
            // Definir la consulta para rendicion_locales antes del bucle
        $stmt_locales = $this->db->prepare("
        INSERT INTO rendicion_locales (
            id_rendicion_general,
            id_local,
            codigo_rendicion,
            payway,
            mercado_pago,
            gastos,
            cuenta_corriente,
            cambios,
            sistema,
            sistema_mas_cambios,
            total_efectivo,
            tarjetas,
            diferencia,
            billetes_20000,
            billetes_10000,
            billetes_2000,
            billetes_1000,
            billetes_500,
            billetes_200,
            billetes_100,
            billetes_50,
            billetes_20,
            billetes_10
        ) VALUES (
            :id_rendicion_general,
            :id_local,
            :codigo_rendicion,
            :payway,
            :mercado_pago,
            :gastos,
            :cuenta_corriente,
            :cambios,
            :sistema,
            :sistema_mas_cambios,
            :total_efectivo,
            :tarjetas,
            :diferencia,
            :billetes_20000,
            :billetes_10000,
            :billetes_2000,
            :billetes_1000,
            :billetes_500,
            :billetes_200,
            :billetes_100,
            :billetes_50,
            :billetes_20,
            :billetes_10
        )
        ");

                    // Bucle para insertar en rendicion_locales
                    foreach ($principales as $idCierreCaja => $local) {
                        // Validar que el idCierreCaja tenga datos en cierreCaja
                        $stmt_cierreCaja = $this->db->prepare("SELECT idUsuario, codigo_rendicion FROM cierreCaja WHERE idCierreCaja = :id");
                        $stmt_cierreCaja->execute([':id' => $idCierreCaja]);
                        $result = $stmt_cierreCaja->fetch(PDO::FETCH_ASSOC);
                    
                        if (!$result) {
                            throw new Exception("No se encontró información para idCierreCaja: $idCierreCaja");
                        }
                    
                        // Obtener valores individuales
                        $idUsuario = $result['idUsuario'];
                        $codigoRendicion = $result['codigo_rendicion'];

                    // Ejecutar la inserción usando la consulta preparada
                    $stmt_locales->execute([
                        ':id_rendicion_general' => $idRendicionGeneral,
                        ':id_local' => $idUsuario,
                        ':codigo_rendicion' => $codigoRendicion,
                        ':payway' => $local['Payway'] ?? 0.00,
                        ':mercado_pago' => $local['Mercado_Pago'] ?? 0.00,
                        ':gastos' => $local['Gastos'] ?? 0.00,
                        ':cuenta_corriente' => $local['Cuenta_Corriente'] ?? 0.00,
                        ':cambios' => $local['Cambios'] ?? 0.00,
                        ':sistema' => $local['Sistema'] ?? 0.00,
                        ':sistema_mas_cambios' => $local['Sistema_Mas_Cambios'] ?? 0.00,
                        ':total_efectivo' => $local['Total_Efectivo'] ?? 0.00,
                        ':tarjetas' => $local['Tarjetas'] ?? 0.00,
                        ':diferencia' => $local['Diferencia'] ?? 0.00,
                        ':billetes_20000' => $local['billetes_20000'] ?? 0,
                        ':billetes_10000' => $local['billetes_10000'] ?? 0,
                        ':billetes_2000' => $local['billetes_2000'] ?? 0,
                        ':billetes_1000' => $local['billetes_1000'] ?? 0,
                        ':billetes_500' => $local['billetes_500'] ?? 0,
                        ':billetes_200' => $local['billetes_200'] ?? 0,
                        ':billetes_100' => $local['billetes_100'] ?? 0,
                        ':billetes_50' => $local['billetes_50'] ?? 0,
                        ':billetes_20' => $local['billetes_20'] ?? 0,
                        ':billetes_10' => $local['billetes_10'] ?? 0
                    ]);
                    }

    
            $this->db->commit();
            return ['success' => true, 'message' => 'Datos insertados correctamente'];
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function insertarRendicionBanco($data) {
    $fechaHoy = date('Y-m-d');
    try {
        // Validar que los datos requeridos estén presentes
        if (!isset($data['fusionados']) || !isset($data['cheques'])) {
            throw new Exception('Datos incompletos: faltan valores fusionados o cheques.');
        }

        // Iniciar la transacción
        if (!$this->db->inTransaction()) {
            $this->db->beginTransaction();
        }

        $fusionados = $data['fusionados'];
        $cheques = $data['cheques']['cheques'];  // Lista de cheques

        // 1. Insertar en rendicion_general_banco
        $stmt_general = $this->db->prepare("
            INSERT INTO rendicion_general_banco (
                fecha,
                total_efectivo,
                total_cheques,
                total_general,
                billetes_20000,
                billetes_10000,
                billetes_2000,
                billetes_1000,
                billetes_500,
                billetes_200,
                billetes_100,
                billetes_50,
                billetes_20,
                billetes_10
            ) VALUES (
                :fecha,
                :total_efectivo,
                :total_cheques,
                :total_general,
                :billetes_20000,
                :billetes_10000,
                :billetes_2000,
                :billetes_1000,
                :billetes_500,
                :billetes_200,
                :billetes_100,
                :billetes_50,
                :billetes_20,
                :billetes_10
            )
        ");

        // Ejecutar la inserción
        $stmt_general->execute([
            ':fecha' => $fechaHoy,
            ':total_efectivo' => $fusionados['totalEfectivo'] ?? 0.00,
            ':total_cheques' => $fusionados['totalCheques'] ?? 0.00,
            ':total_general' => $fusionados['totalGeneral'] ?? 0.00,
            ':billetes_20000' => $fusionados['billetes_20000'] ?? 0,
            ':billetes_10000' => $fusionados['billetes_10000'] ?? 0,
            ':billetes_2000' => $fusionados['billetes_2000'] ?? 0,
            ':billetes_1000' => $fusionados['billetes_1000'] ?? 0,
            ':billetes_500' => $fusionados['billetes_500'] ?? 0,
            ':billetes_200' => $fusionados['billetes_200'] ?? 0,
            ':billetes_100' => $fusionados['billetes_100'] ?? 0,
            ':billetes_50' => $fusionados['billetes_50'] ?? 0,
            ':billetes_20' => $fusionados['billetes_20'] ?? 0,
            ':billetes_10' => $fusionados['billetes_10'] ?? 0
        ]);

        $idRendicionBanco = $this->db->lastInsertId();

        // 2. Insertar cheques vinculados a esta rendición
        $stmt_cheques = $this->db->prepare("
            INSERT INTO cheques (id_rendicion_banco, banco, importe)
            VALUES (:id_rendicion_banco, :banco, :importe)
        ");

        foreach ($cheques as $cheque) {
            $stmt_cheques->execute([
                ':id_rendicion_banco' => $idRendicionBanco,
                ':banco' => $cheque['banco'],
                ':importe' => $cheque['importe']
            ]);
        }

        // Contar los cheques insertados
        $chequesInsertados = count($cheques);

        // Confirmar la transacción
        $this->db->commit();

        // Retornar el mensaje de éxito con el número de cheques insertados
        return [
            'success' => true,
            'message' => "Datos insertados correctamente. ID Rendición: $idRendicionBanco. Total de cheques insertados: $chequesInsertados."
        ];

    } catch (Exception $e) {
        if ($this->db->inTransaction()) {
            $this->db->rollBack();
        }
        return ['success' => false, 'error' => $e->getMessage()];
    }
}



    
    
    
    
    
    
    

    
}

// Manejo de las peticiones AJAX
// Manejo de las peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $database = new Database();
    $dbConnection = $database->getConnection();
    $controller = new DetalleRendicionController($dbConnection);

    // Intentar capturar desde $_POST o desde JSON
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $_POST['action'] ?? ($data['action'] ?? null);

    if ($action) {
        $action = filter_var($action, FILTER_SANITIZE_STRING);
        try {
            switch ($action) {
                case 'obtenerRendicionesConUsuarios':
                    $detalles = $controller->obtenerRendicionesConVentas();
                    echo json_encode(['error' => false, 'data' => $detalles]);
                    break;

                case 'obtenerCierreCajaHoy':
                    $cierresCaja = $controller->obtenerCierreCajaHoy();
                    echo json_encode(['error' => false, 'data' => $cierresCaja]);
                    break;

                case 'insertarRendicion':
                    // Verificar que 'tabla_principal' y otros datos existan
                    if (!isset($data['tabla_principal']) || empty($data['tabla_principal'])) {
                        echo json_encode(['error' => true, 'mensaje' => 'Datos incompletos.']);
                        exit;
                    }

                    $resultado = $controller->insertarRendicion($data);
                    echo json_encode($resultado);
                    break;

                case 'insertarRendicionLocales':
                        if (!isset($data['tablas']) || !is_array($data['tablas'])) {
                            echo json_encode(['success' => false, 'error' => 'La clave "tablas" no está presente o no es válida.']);
                            exit;
                        }
    
                        $totalesFusionados = $data['tablas']['totalesFusionados'] ?? null;
                        $principales = $data['tablas']['principales'] ?? null;
    
                        if (!$totalesFusionados || !$principales) {
                            echo json_encode(['success' => false, 'error' => 'Faltan los datos necesarios: "totalesFusionados" o "principales".']);
                            exit;
                        }
    
                        $resultado = $controller->insertarRendicionLocales([
                            'totalesFusionados' => $totalesFusionados,
                            'principales' => $principales,
                        ]);
    
                        echo json_encode($resultado);
                        break;
                case 'insertarRendicionBanco':
                            if (!isset($data['fusionados']) || !isset($data['cheques'])) {
                                echo json_encode(['success' => false, 'error' => 'Datos incompletos para rendición de banco.']);
                                exit;
                            }
                        
                            $resultado = $controller->insertarRendicionBanco($data);
                            echo json_encode($resultado);
                            break;
                        

                default:
                    throw new Exception("Acción no válida.");
            }
        } catch (Exception $e) {
            echo json_encode(['error' => true, 'mensaje' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => true, 'mensaje' => 'Acción no especificada.']);
    }
    exit;
}