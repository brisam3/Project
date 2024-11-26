<!DOCTYPE html>
<html lang="es" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../../assets/" data-template="horizontal-menu-template">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Preventa</title>

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

            <div class="layout-page">
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4">Reporte de Preventa</h4>
                        <div class="row">

                            <!-- Total Vendido -->
                            <div class="col-md-4 col-lg-3 col-sm-6 mb-4">
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
                            <div class="col-md-4 col-lg-3 col-sm-6 mb-4">
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
                            <div class="col-md-4 col-lg-3 col-sm-6 mb-4">
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
                            <div class="col-md-4 col-lg-3 col-sm-6 mb-4">
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
                            <div class="col-md-4 col-lg-3 col-sm-6 mb-4">
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
                            <div class="col-md-4 col-lg-3 col-sm-6 mb-4">
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
                            <div class="col-md-4 col-lg-3 col-sm-6 mb-4">
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
                            <div class="col-md-4 col-lg-3 col-sm-6 mb-4">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar flex-shrink-0 d-flex align-items-center justify-content-center bg-light rounded">
                                                <i class="bx bx-money fs-2 text-dark"></i>
                                            </div>
                                            <div class="ms-3">
                                                <span class="fw-semibold d-block">Total Comisiones</span>
                                                <h4 class="card-title mb-1" id="total-comisiones">$</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">DataTables /</span> Reporte de Preventa</h4>

    <!-- Preventa Report Table -->
    <div class="card">
        <h5 class="card-header">Reporte de Preventa</h5>
        <div class="card-datatable text-nowrap">
            <table class="datatables-ajax table table-bordered">
                <thead>
                    <tr>
                        <th>Preventista</th>
                        <th>Cantidad de Boletas</th>
                        <th>Cantidad de Clientes</th>
                        <th>Total de Venta Diario</th>
                        <th>Ticket Promedio</th>
                        <th>Comisión</th>
                        <th>Variedad de Artículos</th>
                        <th>Variedad de Proveedores</th>
                        <th>Promedio Artículos/Cliente</th>
                        <th>Promedio Proveedores/Cliente</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí van los datos de la tabla -->
                    <tr>
                        <td>Juan Pérez</td>
                        <td>150</td>
                        <td>120</td>
                        <td>$3000</td>
                        <td>$25</td>
                        <td>$500</td>
                        <td>10</td>
                        <td>5</td>
                        <td>1.25</td>
                        <td>1.5</td>
                    </tr>
                    <tr>
                        <td>Maria Gómez</td>
                        <td>200</td>
                        <td>180</td>
                        <td>$4000</td>
                        <td>$22</td>
                        <td>$600</td>
                        <td>12</td>
                        <td>6</td>
                        <td>1.25</td>
                        <td>1.2</td>
                    </tr>
                    <!-- Puedes agregar más filas según los datos que tengas -->
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
    // Al cargar la página, obtener la fecha actual y almacenarla en la variable 'fecha'
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const fecha = today.toISOString().split('T')[0]; // Formato YYYY-MM-DD
        console.log('Fecha actual (formato date):', fecha);
    });
    </script>



</body>

</html>