<?php
// Incluir el controlador de acceso
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../backend/controller/access/AccessController.php';

$accessController = new AccessController();


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

    <style>
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .billetes-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 10px;
    }

    .billetes-item {
        background-color: #f0f0f0;
        padding: 10px;
        border-radius: 4px;
        text-align: center;
    }

    h1 {
        color: #2c3e50;
        text-align: center;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th,
    td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: rgb(32, 56, 136);
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #e9e9e9;
    }

    .verDetalleBtn {
        background-color: rgb(0, 0, 0);
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .verDetalleBtn:hover {
        background-color: #27ae60;
    }


    #detalleModal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        /* Increased z-index to ensure it's above other elements */
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        padding: 30px;
        width: 80%;
        max-width: 600px;
        max-height: 90vh;
        /* Changed to vh units for better responsiveness */
        overflow-y: auto;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9998;
        /* Just below the modal */
        display: none;
    }

    #detalleModal h2 {
        color: #2c3e50;
        margin-bottom: 20px;
    }

    #detalleModal p {
        margin-bottom: 10px;
    }

    #detalleModal ul {
        list-style-type: none;
        padding: 0;
    }

    #detalleModal li {
        margin-bottom: 5px;
    }

    #detalleModal button {
        background-color: #3498db;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    #detalleModal button:hover {
        background-color: #2980b9;
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
                            <div class="container-fluid !important;">
                                <div class="row">

                                    <div
                                        style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; margin-bottom: 20px;">
                                        <div class="container my-4">
                                            <div class="table-responsive">
                                                <h1>Listado de Rendiciones</h1>
                                                <table id="rendicionesTable"
                                                    style="width: 100%; border-collapse: collapse;">
                                                    <thead>
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Preventista</th>
                                                            <th>Total General</th>
                                                            <th>Contrareembolso</th>
                                                            <th>Diferencia</th>
                                                            <th>Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Contenido dinámico -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- Modal para mostrar detalles -->
                                        <div id="detalleModal"
                                            style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); max-height: 90%; overflow-y: auto;">
                                            <small class="text-muted float-end">
                                                <p><strong>Código de Rendición:</strong> <span
                                                        id="codigoRendicion"></span>
                                            </small>
                                            <h2>Detalle de Rendición</h2>

                                            <hr>
                                            <p><strong>Fecha:</strong> <span id="fecha"></span></p>
                                            <p><strong>Total General:</strong> $<span id="totalGeneral"></span></p>


                                            <p><strong>Total Efectivo:</strong> $<span id="efectivo"></span></p>
                                            <p><strong>Total Payway:</strong> $<span id="payway"></span>
                                            </p>
                                            <p><strong>Total MercadoPago:</strong> $<span id="mercado_pago"></span></p>
                                            <p><strong>Total Onda:</strong> $<span id="onda"></span></p>
                                            <p><strong>Total Cambios:</strong> $<span id="totalFiados"></span></p>
                                            <p><strong>Total Gastos:</strong> $<span id="totalGastos"></span></p>

                                            <p><strong>Total menos Gastos:</strong> $<span id="totalMenosGastos"></span>
                                            </p>
                                            <p><strong>Total MEC Faltante:</strong> $<span id="totalMecFaltante"></span>
                                            </p>
                                            <p><strong>Total Rechazos:</strong> $<span id="totalRechazos"></span></p>


                                            <div class="billetes-list">
                                                <div class="billetes-item"><strong>20000:</strong> <span
                                                        id="billetes20000"></span></div>
                                                <div class="billetes-item"><strong>10000:</strong> <span
                                                        id="billetes10000"></span></div>
                                                <div class="billetes-item"><strong>2000:</strong> <span
                                                        id="billetes2000"></span></div>
                                                <div class="billetes-item"><strong>1000:</strong> <span
                                                        id="billetes1000"></span></div>
                                                <div class="billetes-item"><strong>500:</strong> <span
                                                        id="billetes500"></span>
                                                </div>
                                                <div class="billetes-item"><strong>200:</strong> <span
                                                        id="billetes200"></span>
                                                </div>
                                                <div class="billetes-item"><strong>100:</strong> <span
                                                        id="billetes100"></span>
                                                </div>
                                                <div class="billetes-item"><strong>50:</strong> <span
                                                        id="billetes50"></span>
                                                </div>
                                                <div class="billetes-item"><strong>20:</strong> <span
                                                        id="billetes20"></span>
                                                </div>
                                                <div class="billetes-item"><strong>10:</strong> <span
                                                        id="billetes10"></span>
                                                </div>
                                            </div>
                                            <div style="display: flex; justify-content: center; margin-top: 10px;">
                                                <button onclick="cerrarModal()"
                                                    style="padding: 10px 20px; font-size: 16px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">Cerrar</button>
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
        // Cargar rendiciones
        $.post('../../backend/controller/locales/VerRendiciones.php', {
            action: 'obtenerCierresCaja'
        }, function(data) {
            const tableBody = $("#rendicionesTable tbody");
            tableBody.empty();
            data.forEach(row => {
                // Crear una fecha en la zona horaria local
                const [year, month, day] = row.fecha.split('-');
                const fecha = new Date(year, month - 1, day)
                    .toLocaleDateString('es-ES', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                    });

                tableBody.append(`
                                                    <tr>
                                                    <td>${fecha}</td>
                                                        <td>${row.nombre_preventista}</td>
                                                        <td>$${parseFloat(row.total_general).toFixed(2)}</td>
                                                        <td>$${parseFloat(row.contrareembolso).toFixed(2)}</td>
                                                      <td>${parseFloat(row.diferencia) > 0 ? `+${parseFloat(row.diferencia).toFixed(2)}` : parseFloat(row.diferencia).toFixed(2)}</td>


                                                        <td>
                                                            <button class="verDetalleBtn" data-id="${row.id}">Ver Detalles</button>
                                                        </td>
                                                    </tr>
                                                `);
            });


            // Agregar evento click a los botones "Ver Detalles"
            $(".verDetalleBtn").on("click", function() {
                const idRendicion = $(this).data("id");

                // Llamada AJAX para obtener el detalle
                $.post('../../backend/controller/locales/VerRendiciones.php', {
                    action: 'verDetalleRendicion',
                    idRendicion: idRendicion
                }, function(detalle) {
                    $("#codigoRendicion").text(detalle
                        .codigo_rendicion);
                    $("#fecha").text(detalle
                        .fecha);
                    $("#totalEfectivo").text(detalle
                        .total_efectivo);
                    $("#totalGeneral").text(detalle
                        .total_general);
                    $("#diferencia").text(detalle.diferencia > 0 ?
                        `+${parseFloat(detalle.diferencia).toFixed(2)}` :
                        parseFloat(detalle.diferencia).toFixed(2));

                    $("#totalTransferencia").text(detalle
                        .total_transferencia);
                    $("#totalMercadoPago").text(detalle
                        .total_mercadopago);
                    $("#totalCheques").text(detalle
                        .total_cheques);
                    $("#totalFiados").text(detalle
                        .total_fiados);
                    $("#totalGastos").text(detalle
                        .total_gastos);
                    $("#pagoSecretario").text(detalle
                        .pago_secretario);
                    $("#totalMenosGastos").text(detalle
                        .total_menos_gastos);
                    $("#totalMecFaltante").text(detalle
                        .total_mec_faltante);
                    $("#totalRechazos").text(detalle
                        .total_rechazos);
                    $("#contrareembolso").text(detalle
                        .contrareembolso);

                    // Billetes
                    $("#billetes20000").text(detalle
                        .billetes_20000);
                    $("#billetes10000").text(detalle
                        .billetes_10000);
                    $("#billetes2000").text(detalle
                        .billetes_2000);
                    $("#billetes1000").text(detalle
                        .billetes_1000);
                    $("#billetes500").text(detalle
                        .billetes_500);
                    $("#billetes200").text(detalle
                        .billetes_200);
                    $("#billetes100").text(detalle
                        .billetes_100);
                    $("#billetes50").text(detalle
                        .billetes_50);
                    $("#billetes20").text(detalle
                        .billetes_20);
                    $("#billetes10").text(detalle
                        .billetes_10);

                    $("#detalleModal").show();
                });
            });
        });
    });



    // Función para cerrar el modal
    function cerrarModal() {
        $("#detalleModal").hide();
    }
    </script>
</body>

</html>