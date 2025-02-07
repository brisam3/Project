<?php
// Incluir el controlador de acceso
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../backend/controller/access/AccessController.php';

$accessController = new AccessController();

// Verificar si el acceso está permitido
if (!$accessController->checkAccess('/pages/administracion/camiones.php')) {
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
    <title>CARGA DE ARREGLOS CAMIONES</title>

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

    <style>
    .form-container {
        max-width: 500px;
        background: #ffffff;
        border-radius: 10px;
    }

    .arreglo-item {
        display: flex;
        align-items: center;
        margin-top: 10px;
    }

    .arreglo-item input {
        flex: 1;
        margin-right: 10px;
    }
    </style>
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



            <div class="container d-flex justify-content-center align-items-center vh-100">
                <div class="col-md-6 col-lg-5">
                    <div class="card p-4 shadow-lg form-container">
                    <div class="container">
        <div class="form-container">
            <h3 class="text-center mb-4">Carga de Reparaciones</h3>
            <form id="formArreglo">
                <!-- Mecánico -->
                <div class="mb-3">
                    <label for="selectMecanico" class="form-label">Mecánico:</label>
                    <select id="selectMecanico" name="mecanico_id" class="form-select" required></select>
                </div>

                <!-- Camión -->
                <div class="mb-3">
                    <label for="selectCamion" class="form-label">Camión:</label>
                    <select id="selectCamion" name="camion_id" class="form-select" required></select>
                </div>

                <!-- Detalles del Arreglo -->
                <h4 class="mt-4">Detalles del Arreglo</h4>
                <div id="contenedorArreglos"></div>
                <button type="button" id="btnAgregar" class="btn btn-primary mt-3">Agregar Arreglo</button>

                <!-- Precio Total -->
                <h4 class="mt-4">Precio Total:</h4>
                <input type="text" id="totalPrecio" name="total" class="form-control"
                    placeholder="Ingrese el precio total" required>

                <!-- Botón de envío -->
                <button type="submit" class="btn btn-success w-100 mt-3">Guardar</button>
            </form>
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
        $(document).ready(function () {
            fetchOptions('mecanicos', '#selectMecanico');
            fetchOptions('camiones', '#selectCamion');

            // Obtener mecánico por ID al seleccionar
            $("#selectMecanico").change(function () {
                let mecanicoId = $(this).val();
                if (mecanicoId) {
                    $.getJSON(`../../backend/controller/administracion/ReparacionesController.php?mecanico_id=${mecanicoId}`, function (data) {
                        console.log("Mecánico seleccionado:", data);
                    });
                }
            });

            // Obtener camión por ID al seleccionar
            $("#selectCamion").change(function () {
                let camionId = $(this).val();
                if (camionId) {
                    $.getJSON(`../../backend/controller/administracion/ReparacionesController.php?camion_id=${camionId}`, function (data) {
                        console.log("Camión seleccionado:", data);
                    });
                }
            });

            // Agregar campo de descripción de arreglo
            $("#btnAgregar").click(function () {
                $("#contenedorArreglos").append(`
                    <div class="arreglo-item">
                        <input type="text" name="arreglo_descripcion[]" placeholder="Descripción" class="form-control" required>
                        <button type="button" class="btn btn-danger btnEliminar">X</button>
                    </div>
                `);
            });

            // Eliminar campo de descripción
            $(document).on("click", ".btnEliminar", function () {
                $(this).parent().remove();
            });

            // Enviar formulario
            $("#formArreglo").submit(function (event) {
                event.preventDefault();
                let mecanico_id = $("#selectMecanico").val();
                let camion_id = $("#selectCamion").val();
                let total = parseFloat($("#totalPrecio").val().replace(",", "."));

                if (isNaN(total) || total <= 0) {
                    alert("Ingrese un total válido.");
                    return;
                }

                let arreglos = [];
                $(".arreglo-item input[name='arreglo_descripcion[]']").each(function () {
                    arreglos.push({ descripcion: $(this).val() });
                });

                $.ajax({
                    url: '../../backend/controller/administracion/ReparacionesController.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        mecanico_id,
                        camion_id,
                        arreglos,
                        total: total.toFixed(2)
                    }),
                    success: function (response) {
                        alert(response.message);
                        if (response.status === 'success') {
                            $("#formArreglo")[0].reset();
                            $("#contenedorArreglos").empty();
                        }
                    }
                });
            });

            // Obtener opciones de mecánicos y camiones
            function fetchOptions(tipo, selectId) {
                $.getJSON(`../../backend/controller/administracion/ReparacionesController.php?tipo=${tipo}`, function (data) {
                    let select = $(selectId);
                    select.append('<option value="">Seleccione</option>');
                    data.forEach(item => {
                        select.append(`<option value="${item.id}">${item.nombre || item.patente}</option>`);
                    });
                });
            }
        });
    </script>


</body>

</html>