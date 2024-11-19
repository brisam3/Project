<!DOCTYPE html>
<html lang="es" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../../../assets/" data-template="horizontal-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Resumen, Preventa y Locales</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.1/css/boxicons.min.css" rel="stylesheet"
        integrity="sha512-cfBUsnQh7OSdceLgoYe8n5f4gR8wMSAEPr7iZYswqlN4OrcKUYxxCa5XPrp2XrtH0nXGGaOb7SfiI4Rkzr3psA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" type="image/x-icon" href="../../../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="../../../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../../../assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="../../../assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../../../assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../../assets/vendor/css/rtl/theme-default.css"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../../../assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="../../../assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Helpers -->
    <script src="../../../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="../../../assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../../../assets/js/config.js"></script>

</head>

<body>

    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">

            <!-- Navigation -->
            <?php include "../../template/nav.php"; ?>
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
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="preventa-tab" data-bs-toggle="tab"
                                            data-bs-target="#preventa" type="button" role="tab" aria-controls="preventa"
                                            aria-selected="false">Preventa</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="locales-tab" data-bs-toggle="tab"
                                            data-bs-target="#locales" type="button" role="tab" aria-controls="locales"
                                            aria-selected="false">Locales</button>
                                    </li>
                                </ul>

                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="resumen" role="tabpanel"
                                        aria-labelledby="resumen-tab">
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
                                                                <h4 class="card-title mb-1">$1,200</h4>
                                                                <small class="text-success"><i
                                                                        class="bx bx-up-arrow-alt"></i> +12%</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                            <!-- Total Choferes Card -->
                                            <div class="col-md-4 col-lg-3 col-sm-6 mb-4">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="avatar flex-shrink-0 d-flex align-items-center justify-content-center bg-primary rounded">
                                                                <i class="bx bx-truck fs-2 text-white"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <span class="fw-semibold d-block">Total Choferes</span>
                                                                <h4 class="card-title mb-1">500</h4>
                                                                <small class="text-success"><i
                                                                        class="bx bx-up-arrow-alt"></i>
                                                                    +8%</small>
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
                                                                <h4 class="card-title mb-1">30</h4>
                                                                <small class="text-danger"><i
                                                                        class="bx bx-down-arrow-alt"></i>
                                                                    -5%</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Tablas de Resúmenes -->
                                            <div class="row">
                                                <!-- Ventas por Movil -->
                                                <div class="col-md-6 mb-4">
                                                    <div class="card">
                                                        <div
                                                            class="card-header d-flex justify-content-between align-items-center">
                                                            <h5 class="card-title mb-0">Ventas por Móvil</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Preventista</th>
                                                                        <th>Movil</th>
                                                                        <th>Total Ventas</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>Mica</td>
                                                                        <td>Movil 1</td>
                                                                        <td>$300</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Gustavo</td>
                                                                        <td>Movil 2</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Chilo</td>
                                                                        <td>Movil 3</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Alexander</td>
                                                                        <td>Movil 4</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Diego</td>
                                                                        <td>Movil 5</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cristian</td>
                                                                        <td>Movil 6</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Marianela</td>
                                                                        <td>Movil 7</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Guille</td>
                                                                        <td>Movil 8</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Soledad</td>
                                                                        <td>Movil 9</td>
                                                                        <td>$500</td>
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
                                                                <tbody>
                                                                    <tr>
                                                                        <td>Efectivo</td>
                                                                        <td>$40</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Mercado Pago</td>
                                                                        <td>$1500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Transferencia</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cheques</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cuenta Corriente</td>
                                                                        <td>$500</td>
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
                                    <div class="tab-pane fade" id="preventa" role="tabpanel"
                                        aria-labelledby="preventa-tab">
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
                                                                <span class="fw-semibold d-block">Total
                                                                    Ventas</span>
                                                                <h4 class="card-title mb-1">$1,200</h4>
                                                                <small class="text-success"><i
                                                                        class="bx bx-up-arrow-alt"></i> +12%</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Total Choferes Card -->
                                            <div class="col-md-4 col-lg-3 col-sm-6 mb-4">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="avatar flex-shrink-0 d-flex align-items-center justify-content-center bg-primary rounded">
                                                                <i class="bx bx-truck fs-2 text-white"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <span class="fw-semibold d-block">Total
                                                                    Choferes</span>
                                                                <h4 class="card-title mb-1">500</h4>
                                                                <small class="text-success"><i
                                                                        class="bx bx-up-arrow-alt"></i> +8%</small>
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
                                                                <span class="fw-semibold d-block">Total
                                                                    Locales</span>
                                                                <h4 class="card-title mb-1">30</h4>
                                                                <small class="text-danger"><i
                                                                        class="bx bx-down-arrow-alt"></i>
                                                                    -5%</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Tablas de Resúmenes -->
                                            <div class="row">
                                                <!-- Ventas por Movil -->
                                                <div class="col-md-6 mb-4">
                                                    <div class="card">
                                                        <div
                                                            class="card-header d-flex justify-content-between align-items-center">
                                                            <h5 class="card-title mb-0">Ventas por Móvil</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Preventista</th>
                                                                        <th>Movil</th>
                                                                        <th>Total Ventas</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>Mica</td>
                                                                        <td>Movil 1</td>
                                                                        <td>$300</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Gustavo</td>
                                                                        <td>Movil 2</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Chilo</td>
                                                                        <td>Movil 3</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Alexander</td>
                                                                        <td>Movil 4</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Diego</td>
                                                                        <td>Movil 5</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cristian</td>
                                                                        <td>Movil 6</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Marianela</td>
                                                                        <td>Movil 7</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Guille</td>
                                                                        <td>Movil 8</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Soledad</td>
                                                                        <td>Movil 9</td>
                                                                        <td>$500</td>
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
                                                            <h5 class="card-title mb-0">Totales por Medios de Pago
                                                            </h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Medio de Pago</th>
                                                                        <th>Total Ventas</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>Efectivo</td>
                                                                        <td>$40</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Mercado Pago</td>
                                                                        <td>$1500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Transferencia</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cheques</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cuenta Corriente</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>

                                    <!-- Locales -->
                                    <div class="tab-pane fade" id="locales" role="tabpanel"
                                        aria-labelledby="locales-tab">
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
                                                                <span class="fw-semibold d-block">Total
                                                                    Ventas</span>
                                                                <h4 class="card-title mb-1">$1,200</h4>
                                                                <small class="text-success"><i
                                                                        class="bx bx-up-arrow-alt"></i> +12%</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Total Choferes Card -->
                                            <div class="col-md-4 col-lg-3 col-sm-6 mb-4">
                                                <div class="card shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="avatar flex-shrink-0 d-flex align-items-center justify-content-center bg-primary rounded">
                                                                <i class="bx bx-truck fs-2 text-white"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <span class="fw-semibold d-block">Total
                                                                    Choferes</span>
                                                                <h4 class="card-title mb-1">500</h4>
                                                                <small class="text-success"><i
                                                                        class="bx bx-up-arrow-alt"></i> +8%</small>
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
                                                                <span class="fw-semibold d-block">Total
                                                                    Locales</span>
                                                                <h4 class="card-title mb-1">30</h4>
                                                                <small class="text-danger"><i
                                                                        class="bx bx-down-arrow-alt"></i>
                                                                    -5%</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Tablas de Resúmenes -->
                                            <div class="row">
                                                <!-- Ventas por Movil -->
                                                <div class="col-md-6 mb-4">
                                                    <div class="card">
                                                        <div
                                                            class="card-header d-flex justify-content-between align-items-center">
                                                            <h5 class="card-title mb-0">Ventas por Móvil</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Preventista</th>
                                                                        <th>Movil</th>
                                                                        <th>Total Ventas</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>Mica</td>
                                                                        <td>Movil 1</td>
                                                                        <td>$300</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Gustavo</td>
                                                                        <td>Movil 2</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Chilo</td>
                                                                        <td>Movil 3</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Alexander</td>
                                                                        <td>Movil 4</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Diego</td>
                                                                        <td>Movil 5</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cristian</td>
                                                                        <td>Movil 6</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Marianela</td>
                                                                        <td>Movil 7</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Guille</td>
                                                                        <td>Movil 8</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Soledad</td>
                                                                        <td>Movil 9</td>
                                                                        <td>$500</td>
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
                                                            <h5 class="card-title mb-0">Totales por Medios de Pago
                                                            </h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Medio de Pago</th>
                                                                        <th>Total Ventas</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>Efectivo</td>
                                                                        <td>$40</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Mercado Pago</td>
                                                                        <td>$1500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Transferencia</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cheques</td>
                                                                        <td>$500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cuenta Corriente</td>
                                                                        <td>$500</td>
                                                                    </tr>
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
            </div>
        </div>
    </div>

    <script src="../../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../../../assets/vendor/libs/hammer/hammer.js"></script>
    <script src="../../../assets/vendor/libs/i18n/i18n.js"></script>
    <script src="../../../assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="../../../assets/vendor/js/menu.js"></script>
    <!-- Vendors JS -->
    <script src="../../../assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <!-- Main JS -->
    <script src="../../../assets/js/main.js"></script>
    <!-- Page JS -->
    <script src="../../../assets/js/dashboards-analytics.js"></script>

</body>

</html>