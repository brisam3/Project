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
                            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">RESUMEN DEL DÍA /</span>
                                        VENTAS</h4>
                        <div id="mensaje-carga" style="display: none;">
                            <p>Los datos de hoy serán actualizados antes de las 16:30 hs.</p>
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
                                                    <span class="fw-semibold d-block">Total Menos Ponderado e IVA</span>
                                                    <h4 class="card-title mb-1" id="total-menos-ponderado-e-iva">$</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="container-xxl flex-grow-1 container-p-y">
                                    <h2 class="fw-bold py-3 mb-4">Ventas por preventista</h2> 
                                    <div class="table-responsive-xl mb-6 mb-lg-0">
                                        <div class="dataTables_wrapper no-footer" style="width: 100% !important;"> 
                                            <table class="datatables-ajax table table-bordered m-3 table-hover">
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

                                <div class="container-xxl flex-grow-1 container-p-y">
                                    <h2 class="fw-bold py-3 mb-4">Ventas por Proveedor</h2> 
                                    <div class="table-responsive-xl mb-6 mb-lg-0">
                                        <div class="dataTables_wrapper no-footer" style="width: 100% !important;"> 
                                            <table class="datatables-ajax table table-bordered m-3 table-hover">
                                            <thead class="bg-light text-dark border-top-class m-1">
                                                    <tr>
                                                        <th>Proveedor</th>
                                                        <th>Cantidad Articulos</th>
                                                        <th>Ventas Totales</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tabla-proveedores">
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
    // Hacer la solicitud AJAX al backend
    $.ajax({
        url: "../../backend/controller/preventa/preventaController.php", // Cambia por tu ruta PHP
        method: "GET",
        dataType: "json",
        success: function(data) {
            if (data.mostrarMensaje) {
                // Mostrar el mensaje de actualización
                $("#mensaje-carga").show();
                $("#contenido-reportes").hide();
            } else {
                // Mostrar el contenido de los reportes
                $("#mensaje-carga").hide();
                $("#contenido-reportes").show();

                const resumen = data.resumen;
                const totalPromedioClientes = resumen.CantidadClientes / 10;

                // Llenar los valores en las tarjetas
                $("#total-vendido").text(
                    `$${parseFloat(resumen.TotalVenta).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`
                );
                $("#clientes").text(resumen.CantidadClientes.toLocaleString());
                $("#boletas").text(resumen.CantidadBoletas.toLocaleString());
                $("#ticket-promedio").text(
                    `$${parseFloat(resumen.TicketPromedio).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`
                );
                $("#promedio-clientes").text(
                    `${parseFloat(totalPromedioClientes).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`
                );
                $("#total-sin-iva").text(
                    `$${parseFloat(data.totalVentaMenosIVA).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`
                );
                $("#total-sin-ponderado").text(
                    `$${parseFloat(data.totalVentaMenosPonderado).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`
                );
                $("#total-comisiones").text(
                    `$${parseFloat(data.totalComisiones).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`
                );
                $("#total-menos-ponderado-e-iva").text(
                    `$${parseFloat(data.totalMenosPonderadoIVA).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`
                );

                console.log(data);
                // Llenar la tabla con datos
                const tbody = $("#tabla-reporte");
                data.ventasPreventista.forEach(preventista => {
                    tbody.append(`
                        <tr>
                            <td>${preventista.Preventista}</td>
                            <td>${preventista.CantidadBoletas}</td>
                            <td>${preventista.CantidadClientes}</td>
                            <td>${preventista.TotalVenta}</td>
                            <td>${preventista.TicketPromedio}</td>
                            <td>${preventista.Comision}</td>
                            <td>${preventista.VariedadArticulos}</td>
                            <td>${preventista.VariedadProveedores}</td>
                            <td>${preventista.PromedioArticulosPorCliente}</td>
                            <td>${preventista.PromedioProveedoresPorCliente}</td>
                        </tr>
                    `);
                });

                // Convertir la tabla en una DataTable
                $('.datatables-ajax').DataTable({
                    "paging": true, // Paginación activada
                    "searching": true, // Búsqueda activada
                    "ordering": true, // Ordenación activada
                    "info": true, // Mostrar información de la tabla
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/Spanish.json" // Idioma en español
                    }
                });



                const tbodyproveedores = $("#tabla-proveedores");
                data.ventasProveedor.forEach(proveedor => {
                    tbody.append(`
                        <tr>
                            <td>${proveedor.Proveedor}</td>
                            <td>${proveedor.CantidadArticulos}</td>
                            <td>${proveedor.TotalVenta}</td>
                        </tr>
                    `);
                });

                // Convertir la tabla en una DataTable
                $('.datatables-ajax-proveedores').DataTable({
                    "paging": true, // Paginación activada
                    "searching": true, // Búsqueda activada
                    "ordering": true, // Ordenación activada
                    "info": true, // Mostrar información de la tabla
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/Spanish.json" // Idioma en español
                    }
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al cargar los datos:", error);
        }
    });
});

    </script>
</body>

</html>