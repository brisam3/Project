<?php
// backend/controllers/devoluciones/locales/detalleDevolucionesController.php
// backend/controllers/devoluciones/locales/detalleDevolucionesController.php

session_start();
if (!isset($_SESSION['idUsuario'])) {
    header("Location: https://softwareparanegociosformosa.com/wol/pages/login/login.html");
    exit();
}

require_once('../../../database/Database.php');
date_default_timezone_set('America/Argentina/Buenos_Aires');

class DetalleTransferenciasController {
    private  $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getDb() {
        return $this->db; // Retorna la conexión
    }
    


    // Obtener los detalles de transferencias por fecha
    public function buscarDetalleTransferencia($fecha) {
        $query = "
        SELECT 
                dd.idDetalleSolicitud,
                remitente.nombre AS nombreRemitente,
                destinatario.nombre AS nombreDestinatario,
                dd.idUsuarioRemitente,
                dd.idUsuarioDestinatario,
                dd.fecha,
                dd.estado
            FROM 
                detalle_solicitud_transferencia dd
            JOIN 
                usuarios remitente ON dd.idUsuarioRemitente = remitente.idUsuario
            JOIN 
                usuarios destinatario ON dd.idUsuarioDestinatario = destinatario.idUsuario
            WHERE 
                DATE(dd.fecha) = ? AND dd.idUsuarioDestinatario = ?


        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$fecha, $_SESSION['idUsuario']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Obtener los artículos de un detalle de transferencia específico
    public function verDetalleSolicitud($idDetalleSolicitud) {
        $query = "
            SELECT 
                st.codBejerman, 
                st.partida, 
                st.cantidad, 
                st.descripcion,
                dst.idUsuarioRemitente, 
                dst.idUsuarioDestinatario
            FROM 
                solicitudes_transferencia st
            JOIN 
                detalle_solicitud_transferencia dst 
            ON 
                st.idDetalleSolicitud = dst.idDetalleSolicitud
            WHERE 
                dst.idDetalleSolicitud = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idDetalleSolicitud]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function eliminarDetalleTransferencia($idDetalleTransferencia) {
        $query = "DELETE FROM solicitudes_transferencia WHERE idDetalleSolicitud = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$idDetalleTransferencia]);
    }

    
    public function actualizarDetalleTransferencia($idDetalleTransferencia, $cantidad, $partida) {
        $query = "
            UPDATE solicitudes_transferencia 
            SET cantidad = ?, partida = ? 
            WHERE idDetalleSolicitud = ?
        ";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$cantidad, $partida, $idDetalleTransferencia]);
    }
    
    
}

// Manejo de las peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: text/plain');
    $controller = new DetalleTransferenciasController();

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'buscarDetalleTransferencia':
                $fecha = $_POST['fecha'];
                $detalles = $controller->buscarDetalleTransferencia($fecha);

                foreach ($detalles as $detalle) {
                    echo json_encode([
                        'id' => $detalle['idDetalleSolicitud'],
                        'usuarioRemitente' => $detalle['nombreRemitente'],
                        'usuarioDestinatario' => $detalle['nombreDestinatario'],
                        'idUsuarioRemitente' => $detalle['idUsuarioRemitente'],
                        'idUsuarioDestinatario' => $detalle['idUsuarioDestinatario'],
                        'fecha' => $detalle['fecha'],
                        'estado' => $detalle['estado']
                    ]) . "\n";
                }
                
                break;

            case 'verDetalleSolicitud':
                $idDetalleSolicitud = $_POST['idDetalleSolicitud'];
                $articulos = $controller->verDetalleSolicitud($idDetalleSolicitud);

                $articulosResponse = [];
                foreach ($articulos as $articulo) {
                    $articulosResponse[] = [
                        'codBejerman' => $articulo['codBejerman'],
                        'partida' => $articulo['partida'],
                        'cantidad' => $articulo['cantidad'],
                        'descripcion' => $articulo['descripcion'],
                        'idUsuarioRemitente'=>$articulo['idUsuarioRemitente'],
                        'idUsuarioDestinatario'=>$articulo['idUsuarioDestinatario']
                    ];
                }

                echo json_encode($articulosResponse);
                break;
            case 'modificarDetalleTransferencia':
                    $idDetalleTransferencia = $_POST['idDetalleTransferencia'];
                    $cantidad = $_POST['cantidad'];
                    $partida = $_POST['partida'];
                
                    // Validar entradas
                    if ($cantidad <= 0 || empty($partida)) {
                        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
                        exit;
                    }
                
                    // Actualizar en la base de datos
                    $resultado = $controller->actualizarDetalleTransferencia($idDetalleTransferencia, $cantidad, $partida);
                
                    echo json_encode(['success' => $resultado]);
                    break;
                
                
            case 'eliminarDetalleTransferencia':
                    $idDetalleTransferencia = $_POST['idDetalleTransferencia'];
                    $resultado = $controller->eliminarDetalleTransferencia($idDetalleTransferencia);
                    echo json_encode(['success' => $resultado]);
                    break;
            case 'guardarTransferencias':
                        $transferencias = json_decode($_POST['transferencias'], true);
                        $idUsuarioDestinatario = $_POST['idUsuarioDestinatario'] ?? null;
                        $idUsuarioRemitente = $_POST['idUsuarioRemitente'] ?? null;
                    
                        if (!$idUsuarioDestinatario || !$idUsuarioRemitente) {
                            echo json_encode(['success' => false, 'message' => 'IDs de usuario remitente o destinatario no proporcionados.']);
                            exit;
                        }
                    
                        try {
                            // Inicia la transacción
                            $db = $controller->getDb(); // Obtiene la conexión usando el método público
                            $db->beginTransaction();
                    
                            // Inserta el detalle de la transferencia
                            $detalleQuery = "
                                INSERT INTO detalletransferencia (fecha, idUsuarioRemitente, idUsuarioDestinatario, estado)
                                VALUES (NOW(), ?, ?, 'Pendiente')
                            ";
                            $detalleStmt = $db->prepare($detalleQuery);
                            $detalleStmt->execute([$idUsuarioRemitente, $idUsuarioDestinatario]);
                    
                            // Obtén el ID de la transferencia recién creada
                            $idDetalleTransferencia = $db->lastInsertId();
                    
                            // Inserta cada transferencia relacionada
                            $transferenciasQuery = "
                                INSERT INTO transferencias (idDetalleTransferencia, codBejerman, partida, cantidad, descripcion)
                                VALUES (?, ?, ?, ?, ?)
                            ";
                            $transferenciasStmt = $db->prepare($transferenciasQuery);
                    
                            foreach ($transferencias as $transferencia) {
                                $transferenciasStmt->execute([
                                    $idDetalleTransferencia,
                                    $transferencia['codBejerman'],
                                    $transferencia['partida'],
                                    $transferencia['cantidad'],
                                    $transferencia['descripcion']
                                ]);
                            }
                    
                            // Confirma la transacción
                            $db->commit();
                            echo json_encode(['success' => true]);
                        } catch (Exception $e) {
                            // Revierte la transacción en caso de error
                            $db->rollBack();
                            echo json_encode(['success' => false, 'message' => 'Error al guardar transferencias.', 'error' => $e->getMessage()]);
                        }
                        break;
                    
                    
                        
        }
    }
    exit;
}

?>