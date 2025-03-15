<?php


// Incluir el controlador de acceso
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../backend/controller/access/AccessController.php';

$accessController = new AccessController();

// Verificar si el acceso está permitido
/* if (!$accessController->checkAccess('/pages/preventa/reporteHistoricoPreventa.php')) {
    $accessController->denyAccess();
    exit;
} */

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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
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
    .container {
        max-width: 1200px;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #2c3e50;
        margin-bottom: 20px;
    }

    .inline-form {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }

    .form-group {
        display: flex;
        align-items: center;
    }

    label {
        margin-right: 10px;
        font-weight: bold;
    }

    input[type="date"] {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    button {
        padding: 10px 15px;
        background-color: #3498db;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #2980b9;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }

    thead {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #f5f5f5;
    }

    @media (max-width: 768px) {
        .inline-form {
            flex-direction: column;
        }

        .form-group {
            width: 100%;
        }
    }
    </style>


</head>

<body>
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">
            <!-- Navigation -->
            <?php include "../template/nav.php"; ?>
            <!-- /Navigation -->

            <div class="layout-page">
                <div class="content-wrapper">

                    <div class="container-xxl flex-grow-1 container-p-y">

                        <div class="container mt-3 mb-3">
                            <!-- Formulario -->

                            <form id="form-fechas" onsubmit="return false;">
                                <label for="startDate">DESDE EL DÍA: </label>
                                <input type="date" id="startDate" name="startDate" required />
                                <label for="endDate">HASTA EL DÍA: </label>
                                <input type="date" id="endDate" name="endDate" required />
                                <button type="submit">Generar Reporte</button>
                            </form> <br>
                            <!-- NAVIGATION TABS -->
                            <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="resumen-tab" data-bs-toggle="tab"
                                        data-bs-target="#resumen" type="button" role="tab" aria-controls="resumen"
                                        aria-selected="true">Resumen Preventa</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="kits-tab" data-bs-toggle="tab" data-bs-target="#kits"
                                        type="button" role="tab" aria-controls="preventa"
                                        aria-selected="false">Kits</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="locales-tab" data-bs-toggle="tab"
                                        data-bs-target="#locales" type="button" role="tab" aria-controls="locales"
                                        aria-selected="false">Locales</button>
                                </li>
                            </ul>

                        </div>
                        <div class="container">


                            <!-- CONTENIDO DE LAS PESTAÑAS -->
                            <div class="tab-content">

                                <div class="tab-pane fade show active" id="resumen" role="tabpanel"
                                    aria-labelledby="resumen-tab">
                                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">RESUMEN HISTÓRICO
                                            /</span>
                                        VENTAS</h4>
                                    <div id="mensaje-carga" style="display: none;">
                                        <p>Los datos serán actualizados...</p>
                                    </div>
                                    <div id="contenido-reportes" style="display: none;">
                                        <div class="row">
                                            <!-- Total Vendido -->
                                            <div class="col-md-4 col-lg-2,4 col-sm-6 mb-4">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="avatar flex-shrink-0 d-flex align-items-center justify-content-center bg-success rounded">
                                                                <i class="bx bx-credit-card fs-2 text-white"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <span class="fw-semibold d-block">Total Vendido</span>
                                                                <h4 class="card-title mb-1" id="total-vendido">$</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Clientes -->
                                            <div class="col-md-4 col-lg-2,4 col-sm-6 mb-4">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="avatar flex-shrink-0 d-flex align-items-center justify-content-center bg-primary rounded">
                                                                <i class="bx bx-user fs-2 text-white"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <span class="fw-semibold d-block">Clientes</span>
                                                                <h4 class="card-title mb-1" id="clientes">$</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Boletas -->
                                            <div class="col-md-4 col-lg-2,4 col-sm-6 mb-4">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="avatar flex-shrink-0 d-flex align-items-center justify-content-center bg-warning rounded">
                                                                <i class="bx bx-receipt fs-2 text-white"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <span class="fw-semibold d-block">Boletas</span>
                                                                <h4 class="card-title mb-1" id="boletas">$</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Ticket Promedio -->
                                            <div class="col-md-4 col-lg-2,4 col-sm-6 mb-4">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="avatar flex-shrink-0 d-flex align-items-center justify-content-center bg-info rounded">
                                                                <i class="bx bx-bar-chart-alt fs-2 text-white"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <span class="fw-semibold d-block">Ticket Promedio</span>
                                                                <h4 class="card-title mb-1" id="ticket-promedio">$</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Promedio Clientes -->
                                            <div class="col-md-4 col-lg-2,4 col-sm-6 mb-4">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="avatar flex-shrink-0 d-flex align-items-center justify-content-center bg-secondary rounded">
                                                                <i class="bx bx-group fs-2 text-white"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <span class="fw-semibold d-block">Promedio
                                                                    Clientes</span>
                                                                <h4 class="card-title mb-1" id="promedio-clientes">$
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Total Vendido menos IVA -->
                                            <div class="col-md-4 col-lg-2,4 col-sm-6 mb-4">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="avatar flex-shrink-0 d-flex align-items-center justify-content-center bg-danger rounded">
                                                                <i class="bx bx-calculator fs-2 text-white"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <span class="fw-semibold d-block">Total sin IVA</span>
                                                                <h4 class="card-title mb-1" id="total-sin-iva">$</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Total Vendido menos Ponderado -->
                                            <div class="col-md-4 col-lg-2,4 col-sm-6 mb-4">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="avatar flex-shrink-0 d-flex align-items-center justify-content-center bg-dark rounded">
                                                                <i class="bx bx-coin-stack fs-2 text-white"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <span class="fw-semibold d-block">Total sin
                                                                    Ponderado</span>
                                                                <h4 class="card-title mb-1" id="total-sin-ponderado">$
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Total Comisiones -->
                                            <div class="col-md-4 col-lg-2,4 col-sm-6 mb-4">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="avatar flex-shrink-0 d-flex align-items-center justify-content-center bg-light rounded">
                                                                <i class="bx bx-dollar-circle fs-2 text-dark"></i>

                                                            </div>
                                                            <div class="ms-3">
                                                                <span class="fw-semibold d-block">Total
                                                                    Comisiones</span>
                                                                <h4 class="card-title mb-1" id="total-comisiones">$</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-2,4 col-sm-6 mb-4">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="avatar flex-shrink-0 d-flex align-items-center justify-content-center bg-light rounded">
                                                                <i class="bx bx-calculator fs-2 text-dark"></i>

                                                            </div>
                                                            <div class="ms-3">
                                                                <span class="fw-semibold d-block">Total Menos Ponderado
                                                                    e
                                                                    IVA</span>
                                                                <h4 class="card-title mb-1"
                                                                    id="total-menos-ponderado-e-iva">$</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="container-xxl flex-grow-1 container-p-y">
                                                <h2 class="fw-bold py-3 mb-4">Ventas por preventista</h2>
                                                <div class="table-responsive-xl mb-6 mb-lg-0">
                                                    <div class="table-responsive mb-6 mb-lg-0"
                                                        style="overflow-x: auto;">
                                                        <table
                                                            class="datatables-ajax table table-bordered m-3 table-hover"
                                                            style="border: 1px solid #dee2e6 !important; white-space: nowrap;">
                                                            <thead class="bg-light text-dark border-top-class m-1">
                                                                <tr>
                                                                    <th>Preventista</th>
                                                                    <th>Boletas</th>
                                                                    <th>Clientes</th>
                                                                    <th>Total Venta</th>
                                                                    <th>Ticket Promedio</th>
                                                                    <th>Comisión</th>
                                                                    <th>Variedad Artículos</th>
                                                                    <th>Variedad Proveedores</th>
                                                                    <th>Promedio Artículos/Cliente</th>
                                                                    <th>Promedio Proveedores/Cliente</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="tabla-reporte">
                                                                <!-- Los datos se llenarán dinámicamente -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!--/ Preventa Report Table -->
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- Tabla Artículos Más Vendidos -->
                                <div class="tab-pane fade" id="kits" role="tabpanel" aria-labelledby="kits-tab">
                                    <h2>Artículos Más Vendidos</h2>
                                    <table id="tabla-articulos" class="table">
                                        <thead>
                                            <tr>
                                                <th>Código Artículo</th>
                                                <th>Descripción</th>
                                                <th>Proveedor</th>
                                                <th>Cantidad</th>
                                                <th>Total Ventas</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>

                                    <h2>Artículos con Precio 0</h2>

                                    <!-- Tabla Artículos con Precio 0 -->
                                    <table id="tabla-articulos-cero" class="table table-bordered table-hover">
                                        <thead class="bg-light text-dark">
                                            <tr>
                                                <th>Código Artículo</th>
                                                <th>Descripción</th>
                                                <th>Proveedor</th>
                                                <th>Total General</th>
                                                <th>Articulos de Kits</th>
                                                <th>Diferencia</th>
                                                <th>%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Los datos se llenarán dinámicamente -->
                                        </tbody>
                                    </table>

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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>


    <!-- AJAX Script -->
    <script>
    $(document).ready(function() {
        // Formateador de números
        const formatter = new Intl.NumberFormat('es-ES', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        // Variables para almacenar las fechas seleccionadas
        // Variables para almacenar las fechas seleccionadas
    let startDate = "";
    let endDate = "";
    let ultimaFechaCargada = { start: "", end: "" }; // Guardamos la última fecha consultada
        

        // 🔹 Función para cargar artículos más vendidos
        function cargarArticulosMasVendidos() {
            $.ajax({
                url: "../../backend/controller/preventa/reporteHistoricoPreventa.php",
                method: "POST",
                data: {
                    accion: "consultarArticulosMasVendidos",
                    startDate,
                    endDate
                },
                dataType: "json",
                success: function(articulos) {
                    if (articulos.error) {
                        alert(articulos.error);
                        return;
                    }

                    if (!$.fn.DataTable.isDataTable('#tabla-articulos')) {
                        $('#tabla-articulos').DataTable();
                    }
                    const $tablaArticulos = $('#tabla-articulos').DataTable();
                    $tablaArticulos.clear();
                    articulos.forEach(articulo => {
                        $tablaArticulos.row.add([
                            articulo.CodigoArticulo,
                            articulo.Descripcion,
                            articulo.Proveedor,
                            formatter.format(articulo.Cantidad),
                            formatter.format(articulo.MontoTotal)
                        ]);
                    });
                    $tablaArticulos.draw();
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar artículos más vendidos:", error);
                    alert("Hubo un problema al cargar los datos.");
                }
            });
        }

        // 🔹 Función para cargar artículos con precio 0
        function cargarArticulosConPrecioCero() {
            $.ajax({
                url: "../../backend/controller/preventa/reporteHistoricoPreventa.php",
                method: "POST",
                data: {
                    accion: "consultarArticulosConPrecioCero",
                    startDate,
                    endDate
                },
                dataType: "json",
                success: function(articulos) {
                    if (articulos.error) {
                        alert(articulos.error);
                        return;
                    }

                    if (!$.fn.DataTable.isDataTable('#tabla-articulos-cero')) {
                        $('#tabla-articulos-cero').DataTable();
                    }
                    const $tablaArticulosCero = $('#tabla-articulos-cero').DataTable();
                    $tablaArticulosCero.clear();
                    articulos.forEach(articulo => {
                        const porcentajeCero = (articulo.CantidadCero / articulo
                            .TotalGeneral) * 100 || 0;
                        $tablaArticulosCero.row.add([
                            articulo.CodigoArticulo,
                            articulo.Descripcion,
                            articulo.Proveedor,
                            formatter.format(articulo.TotalGeneral),
                            formatter.format(articulo.CantidadCero),
                            formatter.format(articulo.Diferencia),
                            formatter.format(porcentajeCero) + "%"
                        ]);
                    });
                    $tablaArticulosCero.draw();
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar artículos con precio 0:", error);
                    alert("Hubo un problema al cargar los datos.");
                }
            });
        }

        // 🔹 Función para cargar el resumen de ventas
        function cargarResumenVentas() {
            $.ajax({
                url: "../../backend/controller/preventa/reporteHistoricoPreventa.php",
                method: "POST",
                data: {
                    accion: "consultarResumenVentas",
                    startDate,
                    endDate
                },
                dataType: "json",
                beforeSend: function() {
                    $("#mensaje-carga").show();
                    $("#contenido-reportes").hide();
                },
                success: function(data) {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    $("#mensaje-carga").hide();
                    $("#contenido-reportes").show();

                    const resumen = data.resumen;
                    const totalPromedioClientes = resumen.CantidadClientes / 9;

                    $("#total-vendido").text(`$${formatter.format(resumen.TotalVenta)}`);
                    $("#clientes").text(resumen.CantidadClientes.toLocaleString());
                    $("#boletas").text(resumen.CantidadBoletas.toLocaleString());
                    $("#ticket-promedio").text(`$${formatter.format(resumen.TicketPromedio)}`);
                    $("#promedio-clientes").text(formatter.format(totalPromedioClientes));
                    $("#total-sin-iva").text(`$${formatter.format(data.totalVentaMenosIVA)}`);
                    $("#total-sin-ponderado").text(
                        `$${formatter.format(data.totalVentaMenosPonderado)}`);
                    $("#total-comisiones").text(`$${formatter.format(data.totalComisiones)}`);
                    $("#total-menos-ponderado-e-iva").text(
                        `$${formatter.format(data.totalMenosPonderadoIVA)}`);

                    // Llenar la tabla con datos
                    const tbodyPreventistas = $("#tabla-reporte");
                    data.ventasPreventista.forEach(preventista => {
                        tbodyPreventistas.append(`
                        <tr>
                            <td>${preventista.Preventista}</td>
                            <td>${preventista.CantidadBoletas}</td>
                            <td>${preventista.CantidadClientes}</td>
                            <td>${formatter.format(preventista.TotalVenta)}</td>
                            <td>${formatter.format(preventista.TicketPromedio)}</td>
                            <td>${formatter.format(preventista.Comision)}</td>
                            <td>${preventista.VariedadArticulos}</td>
                            <td>${preventista.VariedadProveedores}</td>
                            <td>${formatter.format(preventista.PromedioArticulosPorCliente)}</td>
                            <td>${formatter.format(preventista.PromedioProveedoresPorCliente)}</td>
                        </tr>
                    `);
                    });

                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar resumen de ventas:", error);
                    alert("Hubo un problema al obtener los datos.");
                }
            });
        }

        // 🔹 Capturar envío del formulario para establecer fechas
        $('#form-fechas').on('submit', function(e) {
            e.preventDefault();

            startDate = $('#startDate').val();
            endDate = $('#endDate').val();

            if (!startDate || !endDate) {
                alert("Por favor, seleccione ambas fechas.");
                return;
            }

            const activeTab = $(".nav-link.active").attr("data-bs-target");

            if (activeTab === "#resumen") {
                cargarResumenVentas();
            } else if (activeTab === "#kits") {
                cargarArticulosMasVendidos();
            } else if (activeTab === "#locales") {
                cargarArticulosConPrecioCero();
            }
        });

        // 🔹 Detectar cambio de pestaña
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            const targetTab = $(e.target).attr("data-bs-target");

            if (!startDate || !endDate) {
                alert("Por favor, seleccione un rango de fechas antes de cambiar de pestaña.");
                return;
            }

            if (targetTab === "#resumen") {
                cargarResumenVentas();
            } else if (targetTab === "#kits") {
                cargarArticulosMasVendidos();
            } else if (targetTab === "#locales") {
                cargarArticulosConPrecioCero();
            }
        });
    });
    </script>


</body>

</html>