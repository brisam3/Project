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
    <title>Detalle de Rendiciones</title>


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
                                <!-- Contenedor para las tablas de detalles -->
                                <div id="contenedorTablas"></div>
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
        // Llamar a la función para buscar detalles de rendiciones cuando la página se carga
        buscarDetalleRendiciones();

        function buscarDetalleRendiciones() {
            $.ajax({
                url: '../../backend/controller/administracion/Rendiciones.php', // Corrige la URL
                type: 'POST',
                data: {
                    action: 'obtenerRendicionesConUsuarios', // Acción para obtener rendiciones
                },
                dataType: 'json',
                success: function(data) {
                    let html = '';
                    let contenedorTablas = $('#contenedorTablas'); // Elemento donde se mostrarán las tablas

                    if (data.length > 0) {
                        // Recorrer cada detalle y generar una tabla por cada uno
                        data.forEach(function(detalle) {
                            html = `
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5>Detalle de Rendición - ${detalle.id}</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Chofer</th>
                                                <th>Preventista</th>
                                                <th>Fecha</th>
                                                <th>Total Efectivo</th>
                                                <th>Total Transferencia</th>
                                                <th>Total MercadoPago</th>
                                                <th>Total Cheques</th>
                                                <th>Total Fiados</th>
                                                <th>Total Gastos</th>
                                                <th>Total General</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>${detalle.nombre_chofer}</td>
                                                <td>${detalle.nombre_preventista}</td>
                                                <td>${detalle.fecha}</td>
                                                <td>${detalle.total_efectivo}</td>
                                                <td>${detalle.total_transferencia}</td>
                                                <td>${detalle.total_mercadopago}</td>
                                                <td>${detalle.total_cheques}</td>
                                                <td>${detalle.total_fiados}</td>
                                                <td>${detalle.total_gastos}</td>
                                                <td>${detalle.total_general}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            `;
                            contenedorTablas.append(html); // Agregar la tabla al contenedor
                        });
                    } else {
                        html = '<p>No se encontraron rendiciones pendientes hoy.</p>';
                        contenedorTablas.html(html); // Mostrar mensaje si no hay detalles
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    console.error('Detalles:', xhr.responseText);
                    alert('Error al buscar detalles de las rendiciones.');
                },
            });
        }
    });
    </script>

</body>

</html>