<?php
// Incluir el controlador de acceso
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../backend/controller/access/AccessController.php';

$accessController = new AccessController();

// Verificar si el acceso está permitido
if (!$accessController->checkAccess('/pages/choferes/Devoluciones.php')) {
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
    <title>Detalle de Devoluciones</title>


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
                                            <div class="card-body">
                                               
                                                <div id="detalleDevolucionesList" class="mt-4">
                                                    <!-- Lista de detalles de devoluciones -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SECCION DERECHA PARA DETALLES -->
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="mb-0">Detalles de la Devolución</h5>
                                            </div>
                                            <div class="card-body" id="detallesDevolucion">
                                                <!-- Detalles del detalleDevolucion -->
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
                // Cargar detalles de devoluciones de hoy al cargar la página
                buscarDetalleDevolucionesHoy();

                // Función para buscar los detalles de devoluciones de hoy
                function buscarDetalleDevolucionesHoy() {
                    $.ajax({
                        url: '../../backend/controller/choferes/DevolucionesController.php',
                        type: 'POST',
                        data: {
                            action: 'buscarDetalleDevolucionesHoy'
                        },
                        dataType: 'text', // Cambiado a 'text'
                        success: function(data) {
                            const detalles = data.trim().split("\n");
                            let html = '';
                            if (detalles.length > 0 && detalles[0] !== "") {
                                detalles.forEach(function(detalle) {
                                    const {
                                        id,
                                        usuario,
                                        fecha
                                    } = JSON.parse(detalle);
                                    html += `
                                    <div class="card mb-2">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                        <span><strong>${usuario} <br></strong> ${fecha}</span>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="verDetalles(${id})">Ver Detalles</button>
                                        </div>
                                    </div>
                                    `;
                                });
                            } else {
                                html = '<p>No se encontraron devoluciones para hoy.</p>';
                            }
                            $('#detalleDevolucionesList').html(html);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            console.error('Detalles:', xhr.responseText);
                            alert('Error al buscar detalles de devoluciones.');
                        },
                    });
                }

                // Función para ver detalles de un detalleDevolucion específico
                function verDetalles(idDetalleDevolucion) {
                    $.ajax({
                        url: '../../backend/controller/choferes/DevolucionesController.php',
                        type: 'POST',
                        data: {
                            action: 'verDetalleDevolucion',
                            idDetalleDevolucion: idDetalleDevolucion
                        },
                        dataType: 'text', // Cambiado a 'text'
                        success: function(data) {
                            const articulos = data.trim().split("\n");
                            let html = '<table class="table">';
                            html +=
                                '<thead><tr><th>Código Bejerman</th><th>Partida</th><th>Cantidad</th><th>Descripción</th></tr></thead>';
                            html += '<tbody>';
                            articulos.forEach(function(articulo) {
                                const [codBejerman, partida, cantidad, descripcion] =
                                articulo.split(" | ");
                                html += `
                              <tr>
                                <td>${codBejerman}</td>
                                <td>${partida}</td>
                                <td>${cantidad}</td>
                                <td>${descripcion}</td>
                              </tr>
                            `;
                            });
                            html += '</tbody></table>';
                            $('#detallesDevolucion').html(html);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            console.error('Detalles:', xhr.responseText);
                            alert('Error al obtener detalles de la devolución.');
                        },
                    });
                }

                // Hacer la función verDetalles global para que sea accesible desde los botones dinámicos
                window.verDetalles = verDetalles;
            });
            </script>

</body>

</html>