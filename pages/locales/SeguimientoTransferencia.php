<?php
// Incluir el controlador de acceso
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../backend/controller/access/AccessController.php';


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


    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Page CSS -->
    <link rel="stylesheet" href="../css/clima.css" />

    <!-- Helpers -->
    <script src="../../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="../../assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../../assets/js/config.js"></script>

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
                            <div class="container-fluid !important;">
                                <!-- Tabs Section -->
                                <div>
                                    <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                                        <li class="nav-item col-12 col-md-6 col-lg-4 my-1" role="presentation">
                                            <button class="nav-link active" id="pendiente-tab" data-bs-toggle="tab"
                                                data-bs-target="#pendiente" type="button" role="tab"
                                                aria-controls="pendiente" aria-selected="true">Solicitudes
                                                Pendientes</button>
                                        </li>
                                        <li class="nav-item col-12 col-md-6 col-lg-4 my-1" role="presentation">
                                            <button class="nav-link" id="aprobadas-tab" data-bs-toggle="tab"
                                                data-bs-target="#aprobadas" type="button" role="tab"
                                                aria-controls="aprobadas" aria-selected="false">Transferencias
                                                Enviadas</button>
                                        </li>
                                        <li class="nav-item col-12 col-md-6 col-lg-4 my-1" role="presentation">
                                            <button class="nav-link" id="recibidas-tab" data-bs-toggle="tab"
                                                data-bs-target="#recibidas" type="button" role="tab"
                                                aria-controls="recibidas" aria-selected="false">Transferencias
                                                Recibidas</button>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-content" id="myTabContent">
                                    <!-- Resumen Tab -->
                                    <div class="tab-pane fade show active" id="pendiente" role="tabpanel">
                                        <div class="row">
                                            <!-- Left Section -->
                                            <div class="col-12 col-md-6 col-lg-4 my-3">
                                                <div class="card">

                                                    <div class="card-body">

                                                        <div id="detalleTransferenciasList" class="mt-4">
                                                            <!-- Lista de detalles de transferencias -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Right Section -->
                                            <div class="col-12 col-md-6 col-lg-8 my-3 !important;">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="mb-0">Detalles de la solicitud de transferencia</h5>
                                                        <small class="text-muted float-end">Confirme para Transferir
                                                            Artículos</small>
                                                    </div>
                                                    <div class="card-body" id="detallesTransferencia">
                                                        <!-- Detalles del detalle_solicitud_transferencia -->
                                                    </div>
                                                    <input type="hidden" id="idUsuarioDestinatario" value="">
                                                    <input type="hidden" id="idUsuarioRemitente" value="">
                                                    <input type="hidden" id="idFechaTransferencia"
                                                        name="idFechaTransferencia">
                                                    <input type="hidden" id="idDetalleSolicitud"
                                                        name="idDetalleSolicitud" value="">

                                                    <div class="text-center my-3">
                                                        <button id="guardarTransferencias"
                                                            class="btn btn-outline-dark my-1">
                                                            Confirmar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="tab-pane fade" id="aprobadas" role="tabpanel">
                                        <div class="row">
                                            <!-- ASIDE IZQUIERDO -->
                                            <div class="col-md-4">
                                                <div class="card">

                                                    <div class="card-body">
                                                        <div id="detalleTransferenciasEnviadasList" class="mt-4">
                                                            <!-- Lista de detalles de transferencias -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- SECCION DERECHA PARA DETALLES -->
                                            <div class="col-md-8">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="mb-0">Detalles de la transferencia enviada</h5>
                                                        <small class="text-muted float-end"></small>

                                                    </div>
                                                    <div class="card-body" id="detallesTransferenciasEnviadas">

                                                        <!-- Detalles del detalle_solicitud_transferencia -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="tab-pane fade" id="recibidas" role="tabpanel">
                                        <div class="row">
                                            <!-- Left Section -->
                                            <div class="col-12 col-md-6 col-lg-4 my-3">
                                                <div class="card">

                                                    <div class="card-body">

                                                        <div id="detalleTransferenciasRecibidasList" class="mt-4">
                                                            <!-- Lista de detalles de transferencias -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Right Section -->
                                            <div class="col-12 col-md-6 col-lg-8 my-3 !important;">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="mb-0">Detalles de la solicitud de transferencia</h5>
                                                        <small class="text-muted float-end">Confirme la Recepción de
                                                            Artículos</small>
                                                    </div>
                                                    <div class="card-body" id="detallesTransferenciaRecibida">
                                                        <!-- Detalles del detalle_solicitud_transferencia -->
                                                    </div>
                                                    <input type="hidden" id="idDetalleTransferencia2"
                                                        name="idDetalleTransferencia2">
                                                    <div class="text-center my-3">
                                                        <button id="actualizarestado" class="btn btn-outline-dark my-1">
                                                            Confirmar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

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
        // Llamar a la función para buscar detalles de transferencia cuando la página se carga
        buscarDetalleTransferencia();

        function buscarDetalleTransferencia() {
            $.ajax({
                url: '../../backend/controller/locales/SeguimientoTransferencias.php',
                type: 'POST',
                data: {
                    action: 'buscarDetalleTransferencia', // La acción que estás enviando
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
                                        <button type="button" class="btn btn-sm btn-outline-dark my-1" onclick="verDetalles(${id})">Ver Detalles</button>
                                        <button type="button" class="btn btn-sm btn-outline-dark my-1"  onclick="rechazarSolicitud(${id})">Rechazar</button>
                                    </div>
                                </div>
                            </div>
                        `;
                        });
                    } else {
                        html =
                            '<p>No se encontraron transferencias pendientes hoy.</p>';
                    }

                    // Mostrar los resultados en el DOM
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
            url: '../../backend/controller/locales/SeguimientoTransferencias.php',
            type: 'POST',
            data: {
                action: 'guardarTransferencias',
                transferencias: JSON.stringify(transferencias),
                idUsuarioDestinatario: idUsuarioRemitente,
                idUsuarioRemitente: idUsuarioDestinatario,
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

    $('#actualizarestado').on('click', function() {

        const idDetalleTransferencia = $('#idDetalleTransferencia2').val();
        console.log('id es ', idDetalleTransferencia)

        // Enviar la solicitud AJAX
        $.ajax({
            url: '../../backend/controller/locales/SeguimientoTransferencias.php',
            type: 'POST',
            data: {
                action: 'actualizarEstadoTransferencia',
                idDetalleTransferencia: idDetalleTransferencia
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

    $('#aprobadas-tab').on('click', function() {
        $.ajax({
            url: '../../backend/controller/locales/SeguimientoTransferencias.php',
            type: 'POST',
            data: {
                action: 'buscarDetalleTransferenciaEnviada',
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

                        let estadoColor = estado === 'Recibido' ? 'style="color: #006400;"' : 'style="color: #f20202;"'; 
                        html += `
                                                          <div class="card mb-2">
                                            <div class="card-body d-flex justify-content-between align-items-center">
                                                <span>
                                                    <strong>Remitente: </strong>${usuarioRemitente}<br>
                                                    <strong>Destinatario: </strong>${usuarioDestinatario}<br>
                                                    <strong>Fecha: </strong>${fecha}<br>
                                                     <strong>Estado: </strong><span ${estadoColor}>${estado}</span>
                                                </span>
                                                <div class="d-flex flex-column">
                                                    <button type="button" class="btn btn-sm btn-outline-dark my-1" onclick="verDetalleTransferencia(${id})">Ver Detalles</button>
                                                </div>
                                            </div>
                                        </div>

                                                        `;
                    });

                } else {
                    html =
                        '<p>No se encontraron transferencias para esta fecha.</p>';

                }
                $('#detalleTransferenciasEnviadasList').html(html);
            },

            error: function(xhr, status, error) {
                console.error('Error:', error);
                console.error('Detalles:', xhr.responseText);
                alert('Error al buscar detalles de transferencias.');
            },
        });
    })




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
                    url: '../../backend/controller/locales/SeguimientoTransferencias.php',
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

    function actualizarEstado(idDetalleTransferencia) {
        // Configura los datos a enviar
        const datos = new FormData();
        datos.append('action', 'actualizarEstadoTransferencia');
        datos.append('idDetalleTransferencia', idDetalleTransferencia);
        // Realiza la solicitud POST
        fetch('../../backend/controller/locales/SeguimientoTransferencias.php', {
                method: 'POST',
                body: datos
            })
            .then(response => response.json()) // Parsea la respuesta como JSON
            .then(data => {
                if (data.success) {
                    alert(data.message); // Muestra mensaje de éxito
                } else {
                    alert(`Error: ${data.message}`); // Muestra mensaje de error
                }
            })
            .catch(error => {
                console.error('Error en la solicitud:', error);
                alert('Hubo un problema al actualizar el estado.');
            });
    }



    $('#recibidas-tab').on('click', function() {
        $.ajax({
            url: '../../backend/controller/locales/SeguimientoTransferencias.php',
            type: 'POST',
            data: {
                action: 'buscarDetalleTransferenciaRecibida',
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


                        let estadoColor = estado === 'Recibido' ? 'style="color: #006400;"' : 'style="color: #f20202;"'; 

                                html += `
                                    <div class="card mb-2">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <span>
                                                <strong>Remitente: </strong>${usuarioRemitente}<br>
                                                <strong>Destinatario: </strong>${usuarioDestinatario}<br>
                                                <strong>Fecha: </strong>${fecha}<br>
                                                <strong>Estado: </strong><span ${estadoColor}>${estado}</span>
                                            </span>
                                            <div class="d-flex flex-column">
                                                <button type="button" class="btn btn-sm btn-outline-dark my-1" onclick="verDetalleTransferenciaRecibida(${id})">Ver Detalles</button>
                                            </div>
                                        </div>
                                    </div>
                                `;
                    });
                } else {
                    html =
                        '<p>No se encontraron transferencias recibidas para esta fecha.</p>';

                }
                $('#detalleTransferenciasRecibidasList').html(html);
            },

            error: function(xhr, status, error) {
                console.error('Error:', error);
                console.error('Detalles:', xhr.responseText);
                alert('Error al buscar detalles de transferencias.');
            },
        });
    })



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
                    url: '../../backend/controller/locales/SeguimientoTransferencias.php',
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
            url: '../../backend/controller/locales/SeguimientoTransferencias.php',
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
            url: '../../backend/controller/locales/SeguimientoTransferencias.php',
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
                    let html = `
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Código Bejerman</th>
                                                <th>Partida</th>
                                                <th>Cantidad</th>
                                                <th>Descripción</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

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

                    html += `
                                        </tbody>
                                    </table>
                                </div>`;

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

    function verDetalleTransferencia(idDetalleTransferencia) {
        $.ajax({
            url: '../../backend/controller/locales/SeguimientoTransferencias.php',
            type: 'POST',
            data: {
                action: 'verDetalleTransferencia',
                idDetalleTransferencia: idDetalleTransferencia
            },
            dataType: 'json',
            success: function(data) {
                if (data.length > 0) {
                    // Verifica que los datos incluyan idUsuarioRemitente e idUsuarioDestinatario del primer artículo
                    const idUsuarioRemitente = data[0].idUsuarioRemitente;
                    const idUsuarioDestinatario = data[0].idUsuarioDestinatario;

                    // Mostrar el idDetalleSolicitud en la consola
                    console.log('ID Detalle Solicitud:', idDetalleTransferencia);

                    // Construir la tabla
                    let html = `
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Código Bejerman</th>
                                                <th>Partida</th>
                                                <th>Cantidad</th>
                                                <th>Descripción</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                    data.forEach(function(articulo) {
                        html += `
                                    <tr data-id="${idDetalleTransferencia}">
                                        <td>${articulo.codBejerman}</td>
                                        <td>${articulo.partida}</td>
                                        <td>${articulo.cantidad}</td>
                                        <td>${articulo.descripcion}</td>
                                    </tr>`;
                    });

                    html += `
                                        </tbody>
                                    </table>
                                </div>`;

                    $('#detallesTransferenciasEnviadas').html(html);



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

    function verDetalleTransferenciaRecibida(idDetalleTransferencia) {
        $.ajax({
            url: '../../backend/controller/locales/SeguimientoTransferencias.php',
            type: 'POST',
            data: {
                action: 'verDetalleTransferenciaRecibida',
                idDetalleTransferencia: idDetalleTransferencia
            },
            dataType: 'json',
            success: function(data) {
                if (data.length > 0) {


                    $('#idDetalleTransferencia2').val(idDetalleTransferencia);

                    // Mostrar el idDetalleSolicitud en la consola
                    console.log('ID Detalle Solicitud transferenciaaa:', idDetalleTransferencia);

                    // Construir la tabla
                    let html = `
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Código Bejerman</th>
                                                <th>Partida</th>
                                                <th>Cantidad</th>
                                                <th>Descripción</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                    data.forEach(function(articulo) {
                        html += `
                                    <tr data-id="${idDetalleTransferencia}">
                                        <td>${articulo.codBejerman}</td>
                                        <td>${articulo.partida}</td>
                                        <td>${articulo.cantidad}</td>
                                        <td>${articulo.descripcion}</td>
                                    </tr>`;
                    });

                    html += `
                                        </tbody>
                                    </table>
                                </div>`;

                    $('#detallesTransferenciaRecibida').html(html);



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
                    url: '../../backend/controller/locales/SeguimientoTransferencias.php',
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