<?php
// Incluir el controlador de acceso
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../backend/controller/access/AccessController.php';

$accessController = new AccessController();

// Verificar si el acceso está permitido
if (!$accessController->checkAccess('/pages/deposito/Solicitudes.php')) {
    $accessController->denyAccess();
    exit;
}
?>

<!DOCTYPE html>
<html lang="es" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../../assets/" data-template="horizontal-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Detalle de Transferencias</title>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.1/css/boxicons.min.css" rel="stylesheet"
        integrity="sha512-cfBUsnQh7OSdceLgoYe8n5f4gR8wMSAEPr7iZYswqlN4OrcKUYxxCa5XPrp2XrtH0nXGGaOb7SfiI4Rkzr3psA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../../assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="../../assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="../css/clima.css" />

    <!-- Helpers -->
    <script src="../../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="../../assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../../assets/js/config.js"></script>
    <style>
    .swal2-custom-margin {
        margin-top: 50px !important;
        /* Ajusta este valor para moverlo más abajo */
    }
    </style>
</head>

<body>
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">


            <?php

      include "../template/nav.php";

      ?>

            <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">

                <div class="layout-container">
                    <div class="layout-page">
                        <div class="content-wrapper">
                            <div class="container-xxl flex-grow-1 container-p-y">
                                <div class="row">
                                    <!-- ASIDE IZQUIERDO -->
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="mb-0">Seleccione una Fecha</h5>
                                            </div>
                                            <div class="card-body">
                                                <input type="date" class="form-control" id="fechaTransferencia" />
                                                <div id="detalleTransferenciasList" class="mt-4">
                                                    <!-- Lista de detalles de transferencias -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SECCION DERECHA PARA DETALLES -->
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="mb-0">Detalles de la solicitud de transferencia</h5>
                                            </div>
                                            <div class="card-body" id="detallesTransferencia">

                                                <!-- Detalles del detalle_solicitud_transferencia -->

                                            </div>
                                            <input type="hidden" id="idUsuarioDestinatario" value="">
                                            <input type="hidden" id="idUsuarioRemitente" value="">
                                            <input type="hidden" id="idFechaTransferencia" name="idFechaTransferencia">
                                            <input type="hidden" id="idDetalleSolicitud" name="idDetalleSolicitud"
                                                value="">
                                            <button id="guardarTransferencias"
                                                class="btn btn-light text-success mt-3">Confirmar</button>


                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>

            <!-- Drag Target Area To SlideIn Menu On Small Screens -->
            <div class="drag-target"></div>

            <!--/ Layout wrapper -->

            <!-- Core JS -->
            <!-- build:js assets/vendor/js/core.js -->

            <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
            <script src="../../assets/vendor/libs/popper/popper.js"></script>
            <script src="../../assets/vendor/js/bootstrap.js"></script>
            <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

            <script src="../../assets/vendor/libs/hammer/hammer.js"></script>
            <script src="../../assets/vendor/libs/i18n/i18n.js"></script>
            <script src="../../assets/vendor/libs/typeahead-js/typeahead.js"></script>

            <script src="../../assets/vendor/js/menu.js"></script>
            <!-- endbuild -->

            <!-- Vendors JS -->
            <script src="../../assets/vendor/libs/apex-charts/apexcharts.js"></script>

            <!-- Main JS -->
            <script src="../../assets/js/main.js"></script>

            <!-- Page JS -->
            <script src="../../assets/js/dashboards-analytics.js"></script>






            <script>
            $(document).ready(function() {


                
                $('#fechaTransferencia').on('change', function() {
                    const fecha = $(this).val();
                    if (fecha) {
                        $.ajax({
                            url: '../../backend/controller/deposito/Solicitudes.php',
                            type: 'POST',
                            data: {
                                action: 'buscarDetalleTransferencia',
                                fecha: fecha
                            },
                            dataType: 'text',
                            success: function(data) {
                                const detalles = data.trim().split("\n");
                                let html = '';

                                if (detalles.length > 0 && detalles[0] !== "") {
                                    detalles.forEach(function(detalle) {
                                        const {
                                            id,
                                            usuarioRemitente,
                                            usuarioDestinatario,
                                            fecha,
                                            estado,
                                            idUsuarioDestinatario,
                                            idUsuarioRemitente
                                        } = JSON.parse(detalle);


                                        html += `
                                                          <div class="card mb-2">
                                            <div class="card-body d-flex justify-content-between align-items-center">
                                                <span>
                                                    <strong>Remitente: </strong>${usuarioRemitente}<br>
                                                    <strong>Destinatario: </strong>${usuarioDestinatario}<br>
                                                    <strong>Fecha: </strong>${fecha}<br>
                                                    <strong>Estado: </strong>${estado}
                                                </span>
                                                <div class="d-flex flex-column">
                                                    <button type="button" class="btn btn-sm btn-primary mb-2" onclick="verDetalles(${id})">Ver Detalles</button>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="rechazarSolicitud(${id})">Rechazar</button>
                                                </div>
                                            </div>
                                        </div>

                                                        `;
                                    });

                                } else {
                                    html =
                                        '<p>No se encontraron transferencias pendientes para esta fecha.</p>';

                                }
                                $('#detalleTransferenciasList').html(html);
                            },

                            error: function(xhr, status, error) {
                                console.error('Error:', error);
                                console.error('Detalles:', xhr.responseText);
                                alert('Error al buscar detalles de transferencias.');
                            },
                        });
                    }
                });

                // Botón para guardar los datos
                $('#guardarTransferencias').on('click', function() {
                    const transferencias = [];
                    const idUsuarioDestinatario = $('#idUsuarioDestinatario').val();
                    const idUsuarioRemitente = $('#idUsuarioRemitente').val();
                    const idDetalleSolicitud = $('#idDetalleSolicitud').val();

                    // Iterar sobre las filas de la tabla para construir el array de transferencias
                    $('#detallesTransferencia table tbody tr').each(function() {
                        const row = $(this);
                        const codBejerman = row.find('td').eq(0).text(); // Código Bejerman
                        const partida = row.find('.partida-input').val(); // Partida
                        const cantidad = row.find('.cantidad-input').val(); // Cantidad
                        const descripcion = row.find('td').eq(3).text(); // Descripción

                        // Construir el objeto de transferencia
                        transferencias.push({
                            codBejerman,
                            partida,
                            cantidad,
                            descripcion
                        });
                    });

                    // Validar que hay transferencias para guardar
                    if (transferencias.length === 0) {
                        Swal.fire('Error', 'No hay datos para guardar.', 'error');
                        return;
                    }

                    // Enviar la solicitud AJAX
                    $.ajax({
                        url: '../../backend/controller/deposito/Solicitudes.php',
                        type: 'POST',
                        data: {
                            action: 'guardarTransferencias',
                            transferencias: JSON.stringify(transferencias),
                            idUsuarioDestinatario: idUsuarioDestinatario,
                            idUsuarioRemitente: idUsuarioRemitente,
                            idDetalleSolicitud: idDetalleSolicitud
                        },
                        success: function(response) {
                            const result = JSON.parse(response);
                            if (result.success) {
                                Swal.fire('Éxito', 'Transferencias guardadas con éxito.',
                                    'success');
                            } else {
                                Swal.fire('Error', result.message ||
                                    'No se pudieron guardar las transferencias.',
                                    'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            Swal.fire('Error',
                                'Ocurrió un problema al guardar las transferencias.',
                                'error');
                        }
                    });
                });




            });

            function removeTransfer(button) {
                const row = $(button).closest('tr');
                const idDetalleSolicitud = row.data('id');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¡No podrás revertir esta acción!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../../backend/controller/deposito/Solicitudes.php',
                            type: 'POST',
                            data: {
                                action: 'eliminarDetalleTransferencia',
                                idDetalleTransferencia: idDetalleSolicitud
                            },
                            success: function(response) {
                                const result = JSON.parse(response);
                                if (result.success) {
                                    Swal.fire('Eliminado',
                                        'El detalle ha sido eliminado con éxito.', 'success');
                                    row.remove();
                                } else {
                                    Swal.fire('Error', 'No se pudo eliminar el detalle.', 'error');
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'Ocurrió un problema al eliminar el detalle.',
                                    'error');
                            }
                        });
                    }
                });
            }

            function actualizarDetalle(idDetalleSolicitud, cantidad, partida) {
                if (cantidad <= 0 || !partida.trim()) {
                    Swal.fire('Error', 'Ingrese valores válidos para la cantidad y la partida.', 'error');
                    return;
                }

                $.ajax({
                    url: '../../backend/controller/deposito/Solicitudes.php',
                    type: 'POST',
                    data: {
                        action: 'modificarDetalleTransferencia',
                        idDetalleTransferencia: idDetalleSolicitud,
                        cantidad: cantidad,
                        partida: partida
                    },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            // El detalle se actualizó correctamente, pero no se muestra ninguna notificación.
                            console.log('Detalle actualizado correctamente');
                        } else {
                            console.error('No se pudo actualizar el detalle.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Ocurrió un error al actualizar el detalle.', 'error');
                    }
                });
            }

            function verDetalles(idDetalleSolicitud) {
                $.ajax({
                    url: '../../backend/controller/deposito/Solicitudes.php',
                    type: 'POST',
                    data: {
                        action: 'verDetalleSolicitud',
                        idDetalleSolicitud: idDetalleSolicitud
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.length > 0) {
                            // Verifica que los datos incluyan idUsuarioRemitente e idUsuarioDestinatario del primer artículo
                            const idUsuarioRemitente = data[0].idUsuarioRemitente;
                            const idUsuarioDestinatario = data[0].idUsuarioDestinatario;

                            // Asignar los valores a los inputs ocultos
                            $('#idUsuarioRemitente').val(idUsuarioRemitente);
                            $('#idUsuarioDestinatario').val(idUsuarioDestinatario);
                            $('#idDetalleSolicitud').val(idDetalleSolicitud);

                            // Mostrar el idDetalleSolicitud en la consola
                            console.log('ID Detalle Solicitud:', idDetalleSolicitud);

                            // Construir la tabla
                            let html = '<table class="table">';
                            html +=
                                '<thead><tr><th>Código Bejerman</th><th>Partida</th><th>Cantidad</th><th>Descripción</th><th>Acciones</th></tr></thead>';
                            html += '<tbody>';

                            data.forEach(function(articulo) {
                                html += `
                        <tr data-id="${idDetalleSolicitud}">
                            <td>${articulo.codBejerman}</td>
                            <td><input type="text" class="form-control partida-input" value="${articulo.partida}" /></td>
                            <td><input type="number" class="form-control cantidad-input" value="${articulo.cantidad}" /></td>
                            <td>${articulo.descripcion}</td>
                            <td>
                                <button type="button" class="btn btn-icon btn-label-danger" onclick="removeTransfer(this)">
                                    <span class="tf-icons bx bx-trash"></span>
                                </button>
                            </td>
                        </tr>`;
                            });

                            html += '</tbody></table>';
                            $('#detallesTransferencia').html(html);

                            // Agregar eventos a los inputs de partida y cantidad
                            $('.partida-input, .cantidad-input').on('change', function() {
                                const row = $(this).closest('tr');
                                const partida = row.find('.partida-input').val();
                                const cantidad = row.find('.cantidad-input').val();

                                // Actualizar visualmente los detalles
                                actualizarDetalle(idDetalleSolicitud, cantidad, partida);
                            });

                        } else {
                            alert('No se encontraron detalles para esta solicitud.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        console.error('Detalles:', xhr.responseText);
                        alert('Error al obtener detalles de la solicitud de transferencia.');
                    },
                });
            }

            function rechazarSolicitud(idDetalleSolicitud) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'No podrás revertir esta acción.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, rechazar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../../backend/controller/deposito/Solicitudes.php',
                            type: 'POST',
                            data: {
                                action: 'rechazarSolicitud',
                                idDetalleSolicitud: idDetalleSolicitud
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Rechazada',
                                        'La solicitud ha sido rechazada exitosamente.',
                                        'success'
                                    ).then(() => {
                                        location
                                            .reload(); // Recarga la página para reflejar los cambios
                                    });
                                } else {
                                    Swal.fire(
                                        'Error',
                                        'No se pudo rechazar la solicitud: ' + response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', error);
                                console.error('Detalles:', xhr.responseText);
                                Swal.fire(
                                    'Error',
                                    'Ocurrió un error al intentar rechazar la solicitud.',
                                    'error'
                                );
                            },
                        });
                    }
                });
            }
            </script>


</body>

</html>