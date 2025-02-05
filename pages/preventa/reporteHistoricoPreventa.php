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
                                </form>
                            
                        </div>
                        <div class="container">


                            <!-- Tabla Artículos Más Vendidos -->

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
            minimumFractionDigits: 1,
            maximumFractionDigits: 1
        });

        // Inicializar DataTable para la tabla de artículos más vendidos
        const $tablaArticulos = $('#tabla-articulos').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
            },
            destroy: true,
            order: [
                [3, "desc"]
            ] // Ordenar por Cantidad de forma descendente
        });

        // Inicializar DataTable para la tabla de artículos con precio 0
        const $tablaArticulosCero = $('#tabla-articulos-cero').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
            },
            destroy: true,
            order: [
                [3, "desc"]
            ] // Ordenar por TotalGeneral de forma descendente
        });

        // Función para cargar artículos más vendidos
        function cargarArticulosMasVendidos(startDate, endDate) {
            // Solicitud AJAX al backend
            $.ajax({
                url: "../../backend/controller/preventa/reporteHistoricoPreventa.php", // Cambia la URL si es necesario
                method: "POST",
                data: {
                    accion: "consultarArticulosMasVendidos", // Acción específica para artículos más vendidos
                    startDate: startDate, // Fecha de inicio
                    endDate: endDate // Fecha de fin
                },
                dataType: "json",
                success: function(articulos) {
                    // Verificar si hay errores en la respuesta
                    if (articulos.error) {
                        alert(articulos.error);
                        return;
                    }

                    // Limpiar los datos previos en la tabla
                    $tablaArticulos.clear();

                    // Recorrer cada artículo y agregarlo a la tabla
                    articulos.forEach(articulo => {
                        $tablaArticulos.row.add([
                            articulo.CodigoArticulo, // Código del artículo
                            articulo.Descripcion, // Descripción
                            articulo.Proveedor, // Proveedor
                            formatter.format(articulo.Cantidad), // Cantidad
                            formatter.format(articulo.MontoTotal) // Monto total
                        ]);
                    });

                    // Redibujar la tabla con los nuevos datos
                    $tablaArticulos.draw();
                },
                error: function(xhr, status, error) {
                    // Manejo de errores en la solicitud AJAX
                    console.error("Error al cargar artículos más vendidos:", error);
                    alert("Hubo un problema al cargar los datos. Intente nuevamente.");
                }
            });
        }

        // Función para cargar artículos con precio 0
        function cargarArticulosConPrecioCero(startDate, endDate) {
            // Solicitud AJAX al backend
            $.ajax({
                url: "../../backend/controller/preventa/reporteHistoricoPreventa.php", // Cambia la URL si es necesario
                method: "POST",
                data: {
                    accion: "consultarArticulosConPrecioCero", // Acción específica para artículos con precio 0
                    startDate: startDate, // Fecha de inicio
                    endDate: endDate // Fecha de fin
                },
                dataType: "json",
                success: function(articulos) {
                    // Verificar si hay errores en la respuesta
                    if (articulos.error) {
                        alert(articulos.error);
                        return;
                    }

                    // Limpiar los datos previos en la tabla
                    $tablaArticulosCero.clear();

                    // Recorrer cada artículo y agregarlo a la tabla
                    articulos.forEach(articulo => {
                        // Calcular el porcentaje de artículos con precio 0 respecto al total general
                        const porcentajeCero = (articulo.CantidadCero / articulo
                            .TotalGeneral) * 100 || 0;

                        // Agregar fila a la tabla con los datos formateados
                        $tablaArticulosCero.row.add([
                            articulo.CodigoArticulo, // Código del artículo
                            articulo.Descripcion, // Descripción
                            articulo.Proveedor, // Proveedor
                            formatter.format(articulo
                                .TotalGeneral), // Total general
                            formatter.format(articulo
                                .CantidadCero), // Cantidad con precio 0
                            formatter.format(articulo.Diferencia), // Diferencia
                            formatter.format(porcentajeCero) +
                            "%" // Porcentaje de precio 0
                        ]);
                    });

                    // Redibujar la tabla con los nuevos datos
                    $tablaArticulosCero.draw();
                },
                error: function(xhr, status, error) {
                    // Manejo de errores en la solicitud AJAX
                    console.error("Error al cargar artículos con precio 0:", error);
                    alert("Hubo un problema al cargar los datos. Intente nuevamente.");
                }
            });
        }

        // Manejo del formulario para cargar ambas tablas
        $('#form-fechas').on('submit', function(e) {
            e.preventDefault(); // Prevenir el envío por defecto del formulario

            // Obtener las fechas ingresadas
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();

            // Validar que ambas fechas estén seleccionadas
            if (!startDate || !endDate) {
                alert("Por favor, seleccione ambas fechas.");
                return;
            }

            // Llamar a las funciones para cargar los datos en ambas tablas
            cargarArticulosMasVendidos(startDate, endDate);
            cargarArticulosConPrecioCero(startDate, endDate);
        });
    });
    </script>


</body>

</html>