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
    public function buscarDetalleTransferencia() {
        // Establecer la zona horaria de Buenos Aires
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        
        // Obtener la fecha actual
        $fechaHoy = date('Y-m-d');  // La fecha en formato Y-m-d (ejemplo: 2024-12-05)
    
        // Query SQL con la fecha actual
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
                DATE(dd.fecha) = ?  -- Compara solo la fecha, sin la hora
                AND dd.idUsuarioDestinatario = ? 
                AND dd.estado = 'Pendiente'
        ";
    
        // Ejecutar la consulta con la fecha actual y el idUsuarioDestinatario
        $stmt = $this->db->prepare($query);
        $stmt->execute([$fechaHoy, $_SESSION['idUsuario']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Retorna los resultados de la consulta
    }
    
    

    public function buscarDetalleTransferenciaEnviada($fecha) {
        $query = "
            SELECT 
                dd.idDetalleTransferencia,
                remitente.nombre AS nombreRemitente,
                destinatario.nombre AS nombreDestinatario,
                dd.idUsuarioRemitente,
                dd.idUsuarioDestinatario,
                dd.fecha,
                dd.estado
            FROM 
                detalletransferencia dd
            JOIN 
                usuarios remitente ON dd.idUsuarioRemitente = remitente.idUsuario
            JOIN 
                usuarios destinatario ON dd.idUsuarioDestinatario = destinatario.idUsuario
            WHERE 
                DATE(dd.fecha) = ?
                AND dd.idUsuarioRemitente = ?
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

    public function verDetalleTransferencia($idDetalleTransferencia) {
        $query = "
            SELECT 
                st.codBejerman, 
                st.partida, 
                st.cantidad, 
                st.descripcion,
                dst.idUsuarioRemitente, 
                dst.idUsuarioDestinatario
            FROM 
                transferencias st
            JOIN 
                detalletransferencia dst 
            ON 
                st.idDetalleTransferencia  = dst.idDetalleTransferencia 
            WHERE 
                dst.idDetalleTransferencia  = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idDetalleTransferencia]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function eliminarDetalleTransferencia($idDetalleTransferencia) {
        // Responder sin realizar cambios en la base de datos
        return true;
    }
                          

    
    public function actualizarDetalleTransferencia($idDetalleTransferencia, $cantidad, $partida) {
       
        return true;
    }
    
    
}

// Manejo de las peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: text/plain');
    $controller = new DetalleTransferenciasController();

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'buscarDetalleTransferencia':
                $detalles = $controller->buscarDetalleTransferencia();
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
                
                    // Crear un array para incluir todos los datos juntos
                    $articulosResponse = [];
                
                    foreach ($articulos as $articulo) {
                        $articulosResponse[] = [
                            'idDetalleSolicitud' => $idDetalleSolicitud,
                            'codBejerman' => $articulo['codBejerman'],
                            'partida' => $articulo['partida'],
                            'cantidad' => $articulo['cantidad'],
                            'descripcion' => $articulo['descripcion'],
                            'idUsuarioRemitente' => $articulo['idUsuarioRemitente'],
                            'idUsuarioDestinatario' => $articulo['idUsuarioDestinatario']
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
                        $idDetalleSolicitud = $_POST['idDetalleSolicitud'] ?? null;
                    
                        if (!$idUsuarioDestinatario || !$idUsuarioRemitente) {
                            echo json_encode(['success' => false, 'message' => 'IDs de usuario remitente o destinatario no proporcionados.']);
                            exit;
                        }
                    
                        try {
                            // Inicia la transacción
                            $db = $controller->getDb(); // Obtiene la conexión usando el método público
                            $db->beginTransaction();
                    
                            // Verifica si el idDetalleSolicitud existe y actualiza su estado a 'Aprobada'
                            if ($idDetalleSolicitud) {
                                $checkSolicitudQuery = "SELECT * FROM detalle_solicitud_transferencia WHERE idDetalleSolicitud = ?";
                                $checkSolicitudStmt = $db->prepare($checkSolicitudQuery);
                                $checkSolicitudStmt->execute([$idDetalleSolicitud]);
                                $solicitud = $checkSolicitudStmt->fetch(PDO::FETCH_ASSOC);
                    
                                if ($solicitud) {
                                    $updateSolicitudQuery = "UPDATE detalle_solicitud_transferencia SET estado = 'Aprobada' WHERE idDetalleSolicitud = ?";
                                    $updateSolicitudStmt = $db->prepare($updateSolicitudQuery);
                                    $updateSolicitudStmt->execute([$idDetalleSolicitud]);
                                } else {
                                    echo json_encode(['success' => false, 'message' => 'Solicitud no encontrada.']);
                                    $db->rollBack();
                                    exit;
                                }
                            }
                    
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
                            echo json_encode([
                                'success' => true,
                                'idDetalleSolicitud' => $idDetalleSolicitud // Incluir el valor en la respuesta
                            ]);
                    
                        } catch (Exception $e) {
                            // Revierte la transacción en caso de error
                            $db->rollBack();
                            echo json_encode(['success' => false, 'message' => 'Error al guardar transferencias.', 'error' => $e->getMessage()]);
                        }
                        break;
            case 'rechazarSolicitud':
                            $idDetalleSolicitud = $_POST['idDetalleSolicitud'] ?? null;
                        
                            if (!$idDetalleSolicitud) {
                                echo json_encode(['success' => false, 'message' => 'ID de detalle de solicitud no proporcionado.']);
                                exit;
                            }
                        
                            try {
                                // Obtén la conexión a la base de datos
                                $db = $controller->getDb(); // Asegúrate de que este método devuelve correctamente la conexión PDO
                                
                                // Inicia la transacción
                                $db->beginTransaction();
                        
                                // Prepara la consulta
                                $query = "UPDATE detalle_solicitud_transferencia SET estado = 'Rechazada' WHERE idDetalleSolicitud = ?";
                                $stmt = $db->prepare($query); // Asegúrate de preparar correctamente la declaración
                        
                                // Ejecuta la consulta con el parámetro
                                $stmt->execute([$idDetalleSolicitud]);
                        
                                // Verifica si se realizó la actualización
                                if ($stmt->rowCount() > 0) {
                                    $db->commit(); // Confirma la transacción
                                    echo json_encode(['success' => true, 'message' => 'Solicitud rechazada correctamente.']);
                                } else {
                                    $db->rollBack(); // Revierte la transacción si no hay filas afectadas
                                    echo json_encode(['success' => false, 'message' => 'No se encontró la solicitud o ya estaba rechazada.']);
                                }
                            } catch (Exception $e) {
                                // Revierte la transacción en caso de error
                                $db->rollBack();
                                echo json_encode(['success' => false, 'message' => 'Error al rechazar la solicitud.', 'error' => $e->getMessage()]);
                            }
                            break;
            case 'buscarDetalleTransferenciaEnviada':
                                $fecha = $_POST['fecha'];
                                $detalles = $controller->buscarDetalleTransferenciaEnviada($fecha);
                            
                                foreach ($detalles as $detalle) {
                                    echo json_encode([
                                        'id' => $detalle['idDetalleTransferencia'], // Cambiado de idDetalleSolicitud a idDetalleTransferencia
                                        'usuarioRemitente' => $detalle['nombreRemitente'],
                                        'usuarioDestinatario' => $detalle['nombreDestinatario'],
                                        'idUsuarioRemitente' => $detalle['idUsuarioRemitente'],
                                        'idUsuarioDestinatario' => $detalle['idUsuarioDestinatario'],
                                        'fecha' => $detalle['fecha'],
                                        'estado' => $detalle['estado']
                                    ]) . "\n";
                                }
                                break;
            case 'verDetalleTransferencia':
                                    $idDetalleTransferencia = $_POST['idDetalleTransferencia'];
                                    $articulos = $controller->verDetalleTransferencia($idDetalleTransferencia);
                                
                                    // Crear un array para incluir todos los datos juntos
                                    $articulosResponse = [];
                                
                                    foreach ($articulos as $articulo) {
                                        $articulosResponse[] = [
                                            'idDetalleTransferencia' => $idDetalleTransferencia,
                                            'codBejerman' => $articulo['codBejerman'],
                                            'partida' => $articulo['partida'],
                                            'cantidad' => $articulo['cantidad'],
                                            'descripcion' => $articulo['descripcion'],
                                            'idUsuarioRemitente' => $articulo['idUsuarioRemitente'],
                                            'idUsuarioDestinatario' => $articulo['idUsuarioDestinatario']
                                        ];
                                    }
                                
                                    echo json_encode($articulosResponse);
                                    break;
                                               
        }
    }
    exit;
}

?>