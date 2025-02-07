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
    <title>CARGA DE ARREGLOS CAMIONES</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.1/css/boxicons.min.css" rel="stylesheet"
        integrity="sha512-cfBUsnQh7OSdceLgoYe8n5f4gR8wMSAEPr7iZYswqlN4OrcKUYxxCa5XPrp2XrtH0nXGGaOb7SfiI4Rkzr3psA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


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



            <div class="container d-flex justify-content-center align-items-center" style="padding-top: 100px;">

                <div class="col-md-6 col-lg-5">
                    <div class="card p-4 shadow-lg form-container">
                        <div class="container">
                            <div class="form-container">
                                <h3 class="text-center mb-4">Carga de Reparaciones</h3>
                                <form id="formArreglo">
                                    <!-- Mecánico -->
                                    <div class="mb-3">
                                        <label for="selectMecanico" class="form-label">Mecánico:</label>
                                        <select id="selectMecanico" name="mecanico_id" class="form-select"
                                            required></select>
                                    </div>

                                    <!-- Camión -->
                                    <div class="mb-3">
                                        <label for="selectCamion" class="form-label">Camión:</label>
                                        <select id="selectCamion" name="camion_id" class="form-select"
                                            required></select>
                                    </div>

                                    <!-- Detalles del Arreglo -->
                                    <h4 class="mt-4">Detalles de los arreglos</h4>
                                    <div id="contenedorArreglos"></div>
                                    <button type="button" id="btnAgregar" class="btn"
                                        style="background-color:rgb(43, 155, 69); color: white; border-radius: 8px; padding: 10px 15px; font-weight: bold; border: none;">
                                        <i class="fas fa-plus"></i> Agregar Arreglo
                                    </button>



                                    <!-- Precio Total -->
                                    <h4 class="mt-4">Precio Total:</h4>
                                    <input type="text" id="totalPrecio" name="total" class="form-control"
                                        placeholder="Ingrese el precio total" required>


                                    <button type="submit" class="btn btn-dark w-100 mt-3">Guardar</button>
                                    <!-- Negro/Gris Oscuro -->

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
    $(document).ready(function() {
        fetchOptions('mecanicos', '#selectMecanico');
        fetchOptions('camiones', '#selectCamion');

        // Obtener mecánico por ID al seleccionar
        $("#selectMecanico").change(function() {
            let mecanicoId = $(this).val();
            if (mecanicoId) {
                $.getJSON(
                    `../../backend/controller/mecanicos/ReparacionesController.php?mecanico_id=${mecanicoId}`,
                    function(data) {
                        console.log("Mecánico seleccionado:", data);
                    });
            }
        });

        // Obtener camión por ID al seleccionar
        $("#selectCamion").change(function() {
            let camionId = $(this).val();
            if (camionId) {
                $.getJSON(
                    `../../backend/controller/mecanicos/ReparacionesController.php?camion_id=${camionId}`,
                    function(data) {
                        console.log("Camión seleccionado:", data);
                    });
            }
        });

        // Agregar campo de descripción de arreglo
        $("#btnAgregar").click(function() {
            $("#contenedorArreglos").append(`
                  <div class="arreglo-item d-flex align-items-center gap-2">
                        <input type="text" name="arreglo_descripcion[]" placeholder="Descripción" class="form-control" required>
                        <button type="button" class="btn btn-danger btnEliminar d-flex align-items-center">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>

                `);
        });

        // Eliminar campo de descripción
        $(document).on("click", ".btnEliminar", function() {
            $(this).parent().remove();
        });

      // Enviar formulario
$("#formArreglo").submit(function(event) {
    event.preventDefault();

    let mecanico_id = $("#selectMecanico").val();
    let camion_id = $("#selectCamion").val();
    let total = parseFloat($("#totalPrecio").val().replace(",", "."));

    if (isNaN(total) || total <= 0 || total > 9999999999999.99) {
        Swal.fire({
            icon: 'error',
            title: 'Número inválido',
            text: 'Ingrese un total válido (máximo: 9999999999999.99).',
        });
        return;
    }

    let arreglos = [];
    $(".arreglo-item input[name='arreglo_descripcion[]']").each(function() {
        let descripcion = $(this).val().trim();
        if (descripcion !== "") {
            arreglos.push({
                descripcion
            });
        }
    });

    $.ajax({
        url: '../../backend/controller/mecanicos/ReparacionesController.php',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json', // Asegura que jQuery interprete la respuesta como JSON
        data: JSON.stringify({
            mecanico_id,
            camion_id,
            arreglos,
            total: total.toFixed(2)
        }),
        success: function(response) {
            console.log("Respuesta recibida:", response); // Depuración

            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    html: `<b>${response.message}</b><br><br>ID del arreglo: <b>${response.arreglo_id}</b>`, // Muestra el ID
                }).then(() => {
                    $("#formArreglo")[0].reset();
                    $("#contenedorArreglos").empty();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message,
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la petición AJAX:", xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error de comunicación',
                text: 'Ocurrió un problema al procesar la solicitud.',
            });
        }
    });
});



        // Obtener opciones de mecánicos y camiones
        function fetchOptions(tipo, selectId) {
            $.getJSON(`../../backend/controller/mecanicos/ReparacionesController.php?tipo=${tipo}`,
                function(data) {
                    let select = $(selectId);
                    select.append('<option value="">Seleccione</option>');
                    data.forEach(item => {
                        select.append(
                            `<option value="${item.id}">${item.nombre}</option>`
                        );
                    });
                });
        }
    });
    </script>


</body>

</html>