<?php
// Incluir el controlador de acceso
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../backend/controller/access/AccessController.php';

$accessController = new AccessController();

// Verificar si el acceso está permitido
if (!$accessController->checkAccess('/pages/administracion/ReporteVentas.php')) {
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
    <title>Resumen, Preventa y Locales</title>

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

            <!-- Navigation -->
            <?php include "../template/nav.php"; ?>
            <!-- /Navigation -->

            <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
                <div class="layout-container">
                    <div class="layout-page">
                        <div class="content-wrapper">
                            <div class="container-xxl flex-grow-1 container-p-y">
                                <!-- NAVIGATION TABS -->
                                <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="resumen-tab" data-bs-toggle="tab"
                                            data-bs-target="#resumen" type="button" role="tab" aria-controls="resumen"
                                            aria-selected="true">Resumen Diario</button>
                                    </li>
                                    <!--     <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="preventa-tab" data-bs-toggle="tab"
                                            data-bs-target="#preventa" type="button" role="tab" aria-controls="preventa"
                                            aria-selected="false">Preventa</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="locales-tab" data-bs-toggle="tab"
                                            data-bs-target="#locales" type="button" role="tab" aria-controls="locales"
                                            aria-selected="false">Locales</button>
                                    </li> -->
                                </ul>

                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="resumen" role="tabpanel"
                                        aria-labelledby="resumen-tab">

                                        <div class="row">
                                            <div class="col-md-4 mb-4">
                                                <label for="fecha" class="form-label">Seleccionar Fecha</label>
                                                <input type="date" class="form-control" id="fecha" name="fecha"
                                                    onchange="fetchData()">
                                            </div>
                                        </div>

                                        <div class="row">

                                            <!-- Total Ventas Card -->
                                            <div class="col-md-4 col-lg-3 col-sm-6 mb-4">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="avatar flex-shrink-0 d-flex align-items-center justify-content-center bg-success rounded">
                                                                <i class="bx bx-credit-card fs-2 text-white"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <span class="fw-semibold d-block">Total Ventas</span>
                                                                <h4 class="card-title mb-1" id="total-ventas">
                                                                    $
                                                                </h4>
                                                                <!--     <small class="text-success"><i
                                                                        class="bx bx-up-arrow-alt"></i> +12%</small>  -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="col-md-4 col-lg-3 col-sm-6 mb-4">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="avatar flex-shrink-0 d-flex align-items-center justify-content-center bg-primary rounded">
                                                                <i class="bx bx-taxi fs-2 text-white"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <span class="fw-semibold d-block">Total Choferes</span>
                                                                <h4 class="card-title mb-1" id="total-ventas-choferes">
                                                                    $</h4>
                                                                <!--  <small class="text-success"><i
                                                                        class="bx bx-up-arrow-alt"></i> 
                                                                    +%</small>  -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Total Locales Card -->
                                            <div class="col-md-4 col-lg-3 col-sm-6 mb-4">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="avatar flex-shrink-0 d-flex align-items-center justify-content-center bg-warning rounded">
                                                                <i class="bx bx-store-alt fs-2 text-white"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <span class="fw-semibold d-block">Total Locales</span>
                                                                <h4 class="card-title mb-1" id="total-ventas-locales">$
                                                                </h4>
                                                                <!--   <small class="text-danger"><i
                                                                        class="bx bx-down-arrow-alt"></i>
                                                                    -5%</small>  -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Tablas de Resúmenes -->
                                            <div class="row"
                                                style="--bs-gutter-x: 0.5rem !important; padding-left: 0 !important; padding-right: 0 !important;">
                                                <!-- Ventas por Movil -->
                                                <div class="col-md-6 mb-4">
                                                    <div class="card">
                                                        <div
                                                            class="card-header d-flex justify-content-between align-items-center">
                                                            <h5 class="card-title mb-0">Rendicion de Choferes por Movil
                                                            </h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Preventista</th>
                                                                        <th>Total Ventas</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="ventas-por-movil">
                                                                    <tr>
                                                                        <td>Mica - Movil 1</td>
                                                                        <td id="8">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Gustavo - Movil 2</td>
                                                                        <td id="9">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Chilo - Movil 3</td>
                                                                        <td id="10">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Alexander - Movil 4</td>
                                                                        <td id="11">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Diego - Movil 5</td>
                                                                        <td id="12">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cristian - Movil 6</td>
                                                                        <td id="13">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Marianela - Movil 7</td>
                                                                        <td id="14">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Guille - Movil 8</td>
                                                                        <td id="15">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Soledad - Movil 9</td>
                                                                        <td id="16">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Daniel - Movil 10</td>
                                                                        <td id="35">$0</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Totales por Medios de Pago -->
                                                <div class="col-md-6 mb-4">
                                                    <div class="card">
                                                        <div
                                                            class="card-header d-flex justify-content-between align-items-center">
                                                            <h5 class="card-title mb-0">Totales por Medios de Pago</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Medio de Pago</th>
                                                                        <th>Total Ventas</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="totales-medios-pago">
                                                                    <tr>
                                                                        <td>Efectivo</td>
                                                                        <td id="total_efectivo">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Mercado Pago</td>
                                                                        <td id="total_mercadopago">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Transferencia</td>
                                                                        <td id="total_transferencia">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cheques</td>
                                                                        <td id="total_cheques">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Fiados</td>
                                                                        <td id="total_fiados">$0</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <div class="card">
                                                        <div
                                                            class="card-header d-flex justify-content-between align-items-center">
                                                            <h5 class="card-title mb-0">Rendicion de Locales</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Local</th>
                                                                        <th>Total Ventas</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="ventas-por-local">
                                                                    <tr>
                                                                        <td>Obrero</td>
                                                                        <td id="20">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Liborsi</td>
                                                                        <td id="21">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Vial</td>
                                                                        <td id="22">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Central</td>
                                                                        <td id="23">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Eva Peron</td>
                                                                        <td id="24">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>San Pedro</td>
                                                                        <td id="25">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Totales por Medios de Pago -->
                                                <div class="col-md-6 mb-4">
                                                    <div class="card">
                                                        <div
                                                            class="card-header d-flex justify-content-between align-items-center">
                                                            <h5 class="card-title mb-0">Totales por Medios de Pago</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Medio de Pago</th>
                                                                        <th>Total Ventas</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="totales-medios-pago-locales">
                                                                    <tr>
                                                                        <td>Efectivo</td>
                                                                        <td id="efectivo">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Mercado Pago</td>
                                                                        <td id="mercado_pago">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>PayWay</td>
                                                                        <td id="payway">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Onda</td>
                                                                        <td id="onda">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cambios</td>
                                                                        <td id="cambios">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cuenta Corriente</td>
                                                                        <td id="cuenta_corriente">$0</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mb-4">
                                                    <div class="card">
                                                        <div
                                                            class="card-header d-flex justify-content-between align-items-center">
                                                            <h5 class="card-title mb-0">Totales por Medios de Pago
                                                                Locales y Moviles</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Medio de Pago</th>
                                                                        <th>Total Ventas</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="totales-medios-pago-locales">
                                                                    <tr>
                                                                        <td>Efectivo</td>
                                                                        <td id="efectivo_total">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Mercado Pago</td>
                                                                        <td id="mercado_pago_total">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Transferencias</td>
                                                                        <td id="transferencias_total">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cheques</td>
                                                                        <td id="cheques_total">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Fiados</td>
                                                                        <td id="fiados_total">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>PayWay</td>
                                                                        <td id="payway_total">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Onda</td>
                                                                        <td id="onda_total">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cambios</td>
                                                                        <td id="cambios_total">$0</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cuenta Corriente</td>
                                                                        <td id="cuentacorriente_total">$0</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>

                                    <!-- Preventa -->
                                    <!-- <div class="tab-pane fade" id="preventa" role="tabpanel"
                                        aria-labelledby="preventa-tab">
                                        <div class="row">
                                            <h1>Preventa</h1>
                                        </div>
                                    </div> -->

                                    <!-- Locales -->
                                    <!-- <div class="tab-pane fade" id="locales" role="tabpanel"
                                        aria-labelledby="locales-tab">
                                        <div class="row">
                                            <h1>Locales</h1>
                                        </div>
                                    </div> -->
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
    document.addEventListener('DOMContentLoaded', () => {
        // Evento para actualizar los datos al cambiar la fecha
        document.getElementById('fecha').addEventListener('change', fetchData);
    });

    function fetchData() {
        var fecha = document.getElementById('fecha').value; // Obtener la fecha seleccionada

        if (!fecha) {
            console.warn("No se ha seleccionado una fecha.");
            return;
        }

        // Realizar la solicitud al backend
        fetch('../../backend/controller/administracion/ReporteVentas.php?fecha=' + fecha)
            .then(response => response.json())
            .then(data => {
                console.log("Datos recibidos del backend:", data); // Imprimir los datos en consola
                console.log("totales locales: ", data.totalesMediosPagoLocales)
                if (data.error) {
                    console.error(data.error); // Manejo de errores
                    return;
                }

                // Limpiar los datos antiguos de la tabla "Ventas por móvil"
                document.querySelectorAll('.preventista').forEach(celda => {
                    celda.innerText = '$0.00'; // Reseteamos las celdas a $0.00
                });

                // Actualizar la tabla de ventas por móvil con los datos recibidos
                data.ventas.forEach(venta => {
                    var preventistaId = venta.idUsuarioPreventista;
                    var totalVentas = parseFloat(venta.total_menos_gastos) ||
                        0; // Convertir a número flotante

                    // Buscar la celda correspondiente por id del preventista
                    var celda = document.getElementById(preventistaId);
                    if (celda) {
                        // Actualizar el valor de la celda y formatear el número con dos decimales
                        celda.innerText = '$' + totalVentas.toFixed(2);
                    } else {
                        console.warn(`No se encontró una celda para el id ${preventistaId}`);
                    }
                });

                // Actualizar la tabla de ventas por local con los datos recibidos
                data.ventasLocales.forEach(venta => {
                    var localId = venta.idUsuario;
                    var totalVentasLocales = parseFloat(venta.total_menos_gastos) ||
                        0; // Convertir a número flotante

                    // Buscar la celda correspondiente por id del local
                    var celda = document.getElementById(localId);
                    if (celda) {
                        // Actualizar el valor de la celda y formatear el número con dos decimales
                        celda.innerText = '$' + totalVentasLocales.toFixed(2);
                    } else {
                        console.warn(`No se encontró una celda para el id ${localId}`);
                    }
                });

// Guardar los totales de cada medio de pago en variables
var totalesMediosPago = data.totalesMediosPago || {};
var totalEfectivo = parseFloat(totalesMediosPago.total_efectivo || 0).toFixed(2);
var totalMercadoPago = parseFloat(totalesMediosPago.total_mercadopago || 0).toFixed(2);
var totalTransferencia = parseFloat(totalesMediosPago.total_transferencia || 0).toFixed(2);
var totalCheques = parseFloat(totalesMediosPago.total_cheques || 0).toFixed(2);
var totalFiados = parseFloat(totalesMediosPago.total_fiados || 0).toFixed(2);

// Actualizar las celdas con los valores guardados en las variables
document.getElementById('total_efectivo').innerText = '$' + totalEfectivo;
document.getElementById('total_mercadopago').innerText = '$' + totalMercadoPago;
document.getElementById('total_transferencia').innerText = '$' + totalTransferencia;
document.getElementById('total_cheques').innerText = '$' + totalCheques;
document.getElementById('total_fiados').innerText = '$' + totalFiados;

// Guardar los totales de los medios de pago locales en variables
var totalesMediosPagoLocales = data.totalesMediosPagoLocales || {};
var efectivo = parseFloat(totalesMediosPagoLocales.efectivo || 0).toFixed(2);
var mercadoPagoLocal = parseFloat(totalesMediosPagoLocales.mercado_pago || 0).toFixed(2);
var payway = parseFloat(totalesMediosPagoLocales.payway || 0).toFixed(2);
var cambios = parseFloat(totalesMediosPagoLocales.cambios || 0).toFixed(2);
var cuentaCorriente = parseFloat(totalesMediosPagoLocales.cuenta_corriente || 0).toFixed(2);
var onda = parseFloat(totalesMediosPagoLocales.onda || 0).toFixed(2);

// Actualizar las celdas con los valores guardados en las variables
document.getElementById('efectivo').innerText = '$' + efectivo;
document.getElementById('mercado_pago').innerText = '$' + mercadoPagoLocal;
document.getElementById('payway').innerText = '$' + payway;
document.getElementById('cambios').innerText = '$' + cambios;
document.getElementById('cuenta_corriente').innerText = '$' + cuentaCorriente;
document.getElementById('onda').innerText = '$' + onda;

// Sumar los totales de 'efectivo' de móviles y locales
var totalEfectivoMovilesLocales = parseFloat(totalEfectivo) + parseFloat(efectivo);

// Sumar los totales de 'mercado pago' de móviles y locales
var totalMercadoPagoMovilesLocales = parseFloat(totalMercadoPago) + parseFloat(mercadoPagoLocal);

// Actualizar la tabla con los totales sumados
document.getElementById('efectivo_total').innerText = '$' + totalEfectivoMovilesLocales.toFixed(2);
document.getElementById('mercado_pago_total').innerText = '$' + totalMercadoPagoMovilesLocales.toFixed(2);
document.getElementById('transferencias_total').innerText = '$' + totalTransferencia;
document.getElementById('cheques_total').innerText = '$' + totalCheques;
document.getElementById('fiados_total').innerText = '$' + totalFiados;
document.getElementById('payway_total').innerText = '$' + payway;
document.getElementById('onda_total').innerText = '$' + onda;
document.getElementById('cambios_total').innerText = '$' + cambios;
document.getElementById('cuentacorriente_total').innerText = '$' + cuentaCorriente;
                                                             


                // Sumar todos los "total_menos_gastos" para el total de ventas
                var totalVentas = data.ventas.reduce((acc, venta) => acc + parseFloat(venta.total_menos_gastos ||
                    0), 0);


                var totalVentasLocales = data.ventasLocales.reduce((acc, venta) => acc + parseFloat(venta
                    .total_menos_gastos ||
                    0), 0);



                // Asegurar el formato de dos decimales
                totalVentas = parseFloat(totalVentas.toFixed(2));
                totalVentasLocales = parseFloat(totalVentasLocales.toFixed(2));

                // Actualizar el card de "Total Ventas" usando el id "total-ventas-choferes"
                document.getElementById('total-ventas-choferes').innerText = '$' + totalVentas.toFixed(2);

                document.getElementById('total-ventas-locales').innerText = '$' + totalVentasLocales.toFixed(2);




                // Calcular la suma total de ventas + cierre de caja
                var sumaTotal = totalVentas + totalVentasLocales;

                // Actualizar las celdas correspondientes
                document.getElementById('total-ventas').innerText = '$' + sumaTotal.toFixed(2);
            })
            .catch(error => {
                console.error('Error al obtener los datos:', error);
            });
    }
    </script>




</body>

</html>