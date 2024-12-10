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
                            <div class="container-fluid">
                                <!-- Tabla de Detalle de Rendiciones -->
                                <div class="table-container">
                                    <table id="tablaRendiciones" class="table table-striped">
                                        <thead id="theadRendiciones">
                                            <!-- Encabezados dinámicos -->
                                        </thead>
                                        <tbody id="tbodyRendiciones">
                                            <!-- Detalles dinámicos -->
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tablas secundarias -->
                                <div id="tablasSecundarias">
                                    <!-- Tablas por cada detalle dinámico -->
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
    buscarDetalleRendiciones();

    function buscarDetalleRendiciones() {
        $.ajax({
            url: '../../backend/controller/administracion/Rendiciones.php',
            type: 'POST',
            data: {
                action: 'obtenerRendicionesConUsuarios',
            },
            dataType: 'json',
            success: function(data) {
                if (data.length > 0) {
                    // Crear encabezados de la tabla principal
                    let headers = `
                    <tr>
                        <th>Campo</th>
                        ${data.map(detalle => `<th>${detalle.nombre_preventista} - ${detalle.nombre_chofer}</th>`).join('')}
                    </tr>
                    `;
                    $('#theadRendiciones').html(headers);

                    // Crear filas para cada atributo
                    const atributos = [
                        { nombre: 'Total General', campo: 'total_general' },
                        { nombre: 'MEC Faltante', campo: 'total_mec_faltante' },
                        { nombre: 'Rechazos', campo: 'total_rechazos' },
                        { nombre: 'Mercado Pago', campo: 'total_mercadopago' },
                        { nombre: 'Transferencias', campo: 'total_transferencia' },
                        { nombre: 'Fiados', campo: 'total_fiados' },
                        { nombre: 'Gastos', campo: 'total_gastos' },
                        { nombre: 'Pago Secretario', campo: 'pago_secretario' },
                        { nombre: 'Cheques', campo: 'total_cheques' },
                    ];

                    let html = '';
                    atributos.forEach(atributo => {
                        html += `
                        <tr>
                            <td>${atributo.nombre}</td>
                            ${data.map(detalle => `<td>${detalle[atributo.campo]}</td>`).join('')}
                        </tr>
                        `;
                    });

                    $('#tbodyRendiciones').html(html);

                    // Crear tablas secundarias para el conteo de billetes
                    let subTablesHtml = '';
                    data.forEach(function(detalle) {
                        let billetesHtml = `
                        <div class="sub-table">
                            <h4>Detalle de Efectivo (${detalle.nombre_preventista} - ${detalle.nombre_chofer})</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Denominación</th>
                                        <th>20,000</th>
                                        <th>10,000</th>
                                        <th>2,000</th>
                                        <th>1,000</th>
                                        <th>500</th>
                                        <th>200</th>
                                        <th>100</th>
                                        <th>50</th>
                                        <th>20</th>
                                        <th>10</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Cantidad</td>
                                        <td>${detalle.billetes_20000}</td>
                                        <td>${detalle.billetes_10000}</td>
                                        <td>${detalle.billetes_2000}</td>
                                        <td>${detalle.billetes_1000}</td>
                                        <td>${detalle.billetes_500}</td>
                                        <td>${detalle.billetes_200}</td>
                                        <td>${detalle.billetes_100}</td>
                                        <td>${detalle.billetes_50}</td>
                                        <td>${detalle.billetes_20}</td>
                                        <td>${detalle.billetes_10}</td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td>${detalle.billetes_20000 * 20000}</td>
                                        <td>${detalle.billetes_10000 * 10000}</td>
                                        <td>${detalle.billetes_2000 * 2000}</td>
                                        <td>${detalle.billetes_1000 * 1000}</td>
                                        <td>${detalle.billetes_500 * 500}</td>
                                        <td>${detalle.billetes_200 * 200}</td>
                                        <td>${detalle.billetes_100 * 100}</td>
                                        <td>${detalle.billetes_50 * 50}</td>
                                        <td>${detalle.billetes_20 * 20}</td>
                                        <td>${detalle.billetes_10 * 10}</td>
                                    </tr>
                                    <tr>
                                        <td>Diferencia</td>
                                        <td colspan="10"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        `;

                        subTablesHtml += billetesHtml;
                    });

                    // Insertar tablas secundarias
                    $('#tablasSecundarias').html(subTablesHtml);
                } else {
                    $('#theadRendiciones').html('<tr><th>No se encontraron detalles</th></tr>');
                    $('#tbodyRendiciones').html('');
                    $('#tablasSecundarias').html('');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Error al buscar detalles de las rendiciones.');
            },
        });
    }
});
</script>



</body>

</html>