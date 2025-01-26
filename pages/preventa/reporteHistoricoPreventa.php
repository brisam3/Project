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
    .inline-form {
        display: flex;
        align-items: center;
        justify-content: center;
        /* Centra el contenido horizontalmente */
        gap: 10px;
        /* Incrementé el espacio entre los elementos */
        padding: 5px;
        background-color: rgb(255, 250, 251);


        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        white-space: nowrap;
        /* Esto previene saltos de línea */
    }

    .inline-form label {
        display: inline-flex;
        /* Asegura que los labels no generen saltos */
        align-items: center;
        font-size: 12px;
        color: rgb(0, 0, 0);
    }

    .inline-form input[type="date"] {
        padding: 2px 4px;
        border: 1px solidrgb(0, 1, 2);
        border-radius: 3px;
        font-size: 12px;
        width: 120px;
    }

    .inline-form button {
        background-color: rgb(182, 0, 0);
        color: white;
        border: none;
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 12px;
        cursor: pointer;
    }

    .inline-form button:hover {
        background-color: rgb(24, 3, 0);
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
                        <div class="inline-form">
                            <form action="../../backend/controller/preventa/reporteHistoricoPrevente.php" method="GET">
                                <label for="startDate"><strong>DESDE EL DÍA: ‎ ‎ </strong> <input type="date"
                                        id="startDate" name="startDate" required></label>
                                <label for="endDate"><strong>HASTA EL DÍA: ‎ ‎ </strong> <input type="date" id="endDate"
                                        name="endDate" required></label>
                                <button type="submit"><strong>Generar Reporte</strong></button>
                            </form>

                        </div>
                        <!-- Nav Tabs -->
                        <ul class="nav nav-tabs" id="ventas-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-preventistas" data-bs-toggle="tab"
                                    data-bs-target="#content-preventistas" type="button" role="tab"
                                    aria-controls="content-preventistas" aria-selected="true">
                                    Ventas por Preventista
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-proveedores" data-bs-toggle="tab"
                                    data-bs-target="#content-proveedores" type="button" role="tab"
                                    aria-controls="content-proveedores" aria-selected="false">
                                    Ventas por Proveedor
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-pre-proveedores" data-bs-toggle="tab"
                                    data-bs-target="#content-pre-proveedores" type="button" role="tab"
                                    aria-controls="content-pre-proveedores" aria-selected="false">
                                    Ventas por Preventista y Proveedor
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-articulos" data-bs-toggle="tab"
                                    data-bs-target="#content-articulos" type="button" role="tab"
                                    aria-controls="content-articulos" aria-selected="false">
                                    Artículos Más Vendidos
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-art-preventista" data-bs-toggle="tab"
                                    data-bs-target="#content-art-preventista" type="button" role="tab"
                                    aria-controls="content-art-preventista" aria-selected="false">
                                    Artículos por Preventista
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="ventas-tabs-content">
                            <!-- Tab Preventistas -->
                            <div class="tab-pane fade show active" id="content-preventistas" role="tabpanel"
                                aria-labelledby="tab-preventistas">
                                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">RESUMEN DEL DÍA /</span>
                                    VENTAS</h4>
                                <div id="mensaje-carga" style="display: none;">
                                    <p>Los datos de hoy serán actualizados antes de las 16:30 hs.</p>
                                </div>
                                <div id="contenido-reportes">
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
                                                            <span class="fw-semibold d-block">Promedio Clientes</span>
                                                            <h4 class="card-title mb-1" id="promedio-clientes">$</h4>
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
                                                            <span class="fw-semibold d-block">Total sin Ponderado</span>
                                                            <h4 class="card-title mb-1" id="total-sin-ponderado">$</h4>
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
                                                            <span class="fw-semibold d-block">Total Comisiones</span>
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
                                                            <span class="fw-semibold d-block">Total Menos Ponderado e
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
                                                <div class="dataTables_wrapper no-footer"
                                                    style="width: 100% !important;">
                                                    <table class="datatables-ajax table table-bordered m-3 table-hover"
                                                        style="border: 1px solid #dee2e6 !important;">
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




                            <div class="tab-pane fade" id="content-proveedores" role="tabpanel"
                                aria-labelledby="tab-proveedores">
                                <div class="row">
                                    <div class="container-xxl flex-grow-1 container-p-y">
                                        <h2 class="fw-bold py-3 mb-4">Ventas por Proveedor</h2>
                                        <div class="table-responsive-xl mb-6 mb-lg-0">
                                            <div class="dataTables_wrapper no-footer" style="width: 100% !important;">
                                                <table
                                                    class="datatables-ajax-proveedores table table-bordered m-3 table-hover">
                                                    <thead class="bg-light text-dark border-top-class m-1">
                                                        <tr>
                                                            <th>Proveedor</th>
                                                            <th>Cantidad Articulos</th>
                                                            <th>Total de Ventas</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tabla-proveedores">
                                                        <!-- Los datos se llenarán dinámicamente -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="content-pre-proveedores" role="tabpanel"
                                aria-labelledby="tab-pre-proveedores">
                                <div class="row">
                                    <div class="container-xxl flex-grow-1 container-p-y">
                                        <h2 class="fw-bold py-3 mb-4">Ventas por Preventista y Proveedor</h2>
                                        <div class="table-responsive-xl mb-6 mb-lg-0">
                                            <div class="dataTables_wrapper no-footer" style="width: 100% !important;">
                                                <table
                                                    class="datatables-ajax-pre-proveedores table table-bordered m-3 table-hover">
                                                    <thead class="bg-light text-dark border-top-class m-1">
                                                        <tr>
                                                            <th>Preventista</th>
                                                            <th>Proveedor</th>
                                                            <th>Cantidad Articulos</th>
                                                            <th>Total de Ventas</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tabla-pre-proveedores">
                                                        <!-- Los datos se llenarán dinámicamente -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!--/ Preventa Report Table -->
                                    </div>
                                </div>
                            </div>



                            <div class="tab-pane fade" id="content-articulos" role="tabpanel"
                                aria-labelledby="tab-articulos">
                                <div class="row">
                                    <div class="container-xxl flex-grow-1 container-p-y">
                                        <h2 class="fw-bold py-3 mb-4">Articulos mas vendidos</h2>
                                        <div class="table-responsive-xl mb-6 mb-lg-0">
                                            <div class="dataTables_wrapper no-footer" style="width: 100% !important;">
                                                <table
                                                    class="datatables-ajax-articulos table table-bordered m-3 table-hover">
                                                    <thead class="bg-light text-dark border-top-class m-1">
                                                        <tr>
                                                            <th>CodigoArticulo</th>
                                                            <th>Descripcion</th>
                                                            <th>Proveedor</th>
                                                            <th>Cantidad</th>
                                                            <th>Total Ventas</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tabla-articulos">
                                                        <!-- Los datos se llenarán dinámicamente -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!--/ Preventa Report Table -->
                                    </div>
                                </div>
                            </div>



                            <div class="tab-pane fade" id="content-art-preventista" role="tabpanel"
                                aria-labelledby="tab-art-preventista">
                                <div class="row">
                                    <div class="container-xxl flex-grow-1 container-p-y">
                                        <h2 class="fw-bold py-3 mb-4">Articulos mas vendidos por Preventista</h2>
                                        <div class="table-responsive-xl mb-6 mb-lg-0">
                                            <div class="dataTables_wrapper no-footer" style="width: 100% !important;">
                                                <table
                                                    class="datatables-ajax-art-preventista table table-bordered m-3 table-hover">
                                                    <thead class="bg-light text-dark border-top-class m-1">
                                                        <tr>
                                                            <th>Preventista</th>
                                                            <th>Codigo Articulo</th>
                                                            <th>Descripcion</th>
                                                            <th>Proveedor</th>
                                                            <th>Cantidad</th>
                                                            <th>Monto Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tabla-art-preventista">
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
        const formatter = new Intl.NumberFormat('es-ES', {
            minimumFractionDigits: 1,
            maximumFractionDigits: 1
        });

        // Función genérica para manejar errores
        function manejarError(xhr, status, error) {
            console.error("Error al cargar los datos:", error);
        }

        // Función para cargar ventas por preventista
        function cargarVentasPreventista() {
            $.ajax({
                url: "../../backend/controller/preventa/reportePreventaController.php?action=ventasPreventista",
                method: "GET",
                dataType: "json",
                success: function(ventasPreventista) {
                    console.log(ventasPreventista); // Ver qué datos se están recibiendo
                    if (ventasPreventista.length === 0) {
                        console.log('No hay datos disponibles.');
                    }
                    const tbody = $("#tabla-reporte");
                    tbody.empty();

                    ventasPreventista.forEach(preventista => {
                        tbody.append(`
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

                    $('.datatables-ajax').DataTable({
                        "destroy": true,
                        "order": [
                            [3, "desc"]
                        ],
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
                        }
                    });
                },


                error: manejarError
            });
        }

        // Función para cargar ventas por proveedor
        function cargarVentasProveedor() {
            $.ajax({
                url: "../../backend/controller/preventa/reportePreventaController.php?action=ventasProveedor",
                method: "GET",
                dataType: "json",
                success: function(ventasProveedor) {
                    const tbody = $("#tabla-proveedores");
                    tbody.empty();

                    ventasProveedor.forEach(proveedor => {
                        tbody.append(`
                            <tr>
                                <td>${proveedor.Proveedor}</td>
                                <td>${formatter.format(proveedor.CantidadArticulos)}</td>
                                <td>${formatter.format(proveedor.TotalVenta)}</td>
                            </tr>
                        `);
                    });

                    $('.datatables-ajax-proveedores').DataTable({
                        "destroy": true,
                        "order": [
                            [2, "desc"]
                        ],
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
                        }
                    });
                },
                error: manejarError
            });
        }

        // Función para cargar ventas por preventista y proveedor
        function cargarVentasPreProveedor() {
            $.ajax({
                url: "../../backend/controller/preventa/reportePreventaController.php?action=ventasPreventistaProveedor",
                method: "GET",
                dataType: "json",
                success: function(ventasPreProveedor) {
                    const tbody = $("#tabla-pre-proveedores");
                    tbody.empty();

                    ventasPreProveedor.forEach(preProveedor => {
                        tbody.append(`
                            <tr>
                                <td>${preProveedor.Preventista}</td>
                                <td>${preProveedor.Proveedor}</td>
                                <td>${formatter.format(preProveedor.CantidadArticulos)}</td>
                                <td>${formatter.format(preProveedor.TotalVenta)}</td>
                            </tr>
                        `);
                    });

                    $('.datatables-ajax-pre-proveedores').DataTable({
                        "destroy": true,
                        "order": [
                            [3, "desc"]
                        ],
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
                        }
                    });
                },
                error: manejarError
            });
        }

        // Función para cargar artículos más vendidos
        function cargarArticulosMasVendidos() {
            $.ajax({
                url: "../../backend/controller/preventa/reportePreventaController.php?action=articulosMasVendidos",
                method: "GET",
                dataType: "json",
                success: function(articulos) {
                    const tbody = $("#tabla-articulos");
                    tbody.empty();

                    articulos.forEach(articulo => {
                        tbody.append(`
                            <tr>
                                <td>${articulo.CodigoArticulo}</td>
                                <td>${articulo.Descripcion}</td>
                                <td>${articulo.Proveedor}</td>
                                <td>${formatter.format(articulo.Cantidad)}</td>
                                <td>${formatter.format(articulo.MontoTotal)}</td>
                            </tr>
                        `);
                    });

                    $('.datatables-ajax-articulos').DataTable({
                        "destroy": true,
                        "order": [
                            [3, "desc"]
                        ],
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
                        }
                    });
                },
                error: manejarError
            });
        }

        // Función para cargar artículos por preventista
        function cargarArticulosPorPreventista() {
            $.ajax({
                url: "../../backend/controller/preventa/reportePreventaController.php?action=articulosPorPreventista",
                method: "GET",
                dataType: "json",
                success: function(articulosPreventista) {
                    const tbody = $("#tabla-art-preventista");
                    tbody.empty();

                    articulosPreventista.forEach(art => {
                        tbody.append(`
                            <tr>
                                <td>${art.Preventista}</td>
                                <td>${art.CodigoArticulo}</td>
                                <td>${art.Descripcion}</td>
                                <td>${art.Proveedor}</td>
                                <td>${formatter.format(art.Cantidad)}</td>
                                <td>${formatter.format(art.MontoTotal)}</td>
                            </tr>
                        `);
                    });

                    $('.datatables-ajax-art-preventista').DataTable({
                        "destroy": true,
                        "order": [
                            [4, "desc"]
                        ],
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
                        }
                    });
                },
                error: manejarError
            });
        }

        // Escuchar los eventos de cambio de pestaña
        $('#ventas-tabs button').on('shown.bs.tab', function(event) {
            const targetId = $(event.target).attr('id'); // ID del tab activo

            // Primero, desactiva todas las pestañas
            $('#ventas-tabs .nav-link').removeClass('active');

            // Luego, activa solo el tab correspondiente
            $(event.target).addClass('active');

            // Llamar a las funciones de carga según el tab seleccionado
            switch (targetId) {
                case 'tab-preventistas':
                    cargarVentasPreventista();
                    break;
                case 'tab-proveedores':
                    cargarVentasProveedor();
                    break;
                case 'tab-pre-proveedores':
                    cargarVentasPreProveedor();
                    break;
                case 'tab-articulos':
                    cargarArticulosMasVendidos();
                    break;
                case 'tab-art-preventista':
                    cargarArticulosPorPreventista();
                    break;
                default:
                    console.warn('No se encontró una acción asociada al tab:', targetId);
            }
        });


        // Cargar el contenido del tab inicial (preventistas)
        cargarVentasPreventista();
    });
    </script>


</body>

</html>