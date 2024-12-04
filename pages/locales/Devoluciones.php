<?php
// Incluir el controlador de acceso
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../backend/controller/access/AccessController.php';

$accessController = new AccessController();

// Verificar si el acceso está permitido
if (!$accessController->checkAccess('/pages/locales/Devoluciones.php')) {
    $accessController->denyAccess();
    exit;
}
?>


<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../../assets/" data-template="horizontal-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Analytics | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <!-- Favicon -->
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
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">

            <!-- Nav -->
            <?php

      include "../template/nav.php";

      ?>
            <!-- Nav -->
            <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
    <div class="layout-container">
        <div class="layout-page">
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Formulario /</span> Carga de Stock</h4>
                    <div class="row">
                        <!-- Tabla de productos cargados -->
                        <div class="col-xl-8 col-md-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Artículos Cargados</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="invoice-list-table table border-top">
                                            <thead>
                                                <tr>
                                                    <th>Cod Bejerman</th>
                                                    <th>Descripción</th>
                                                    <th>Lote</th>
                                                    <th>Cantidad</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="product-list">
                                                <!-- Los productos se agregan aquí dinámicamente -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-primary" onclick="sendProducts()">Enviar</button>
                            </div>
                        </div>

                        <!-- Formularios de carga -->
                        <div class="col-xl-4 col-md-12">
                            <!-- Formulario de código de barras -->
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Buscar Artículo por Código de Barras</h5>
                                    <small class="text-muted float-end">Escanea el código</small>
                                </div>
                                <div class="card-body">
                                    <form id="stock-form">
                                        <div class="mb-3">
                                            <label class="form-label" for="barcode">Código de Barras</label>
                                            <input type="text" class="form-control" id="barcode" placeholder="Escanea el código de barras" />
                                        </div>
                                        <div id="product-details" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label" for="description">Descripción</label>
                                                <input type="text" class="form-control" id="description" readonly />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="batch">Lote</label>
                                                <input type="text" class="form-control" id="batch" placeholder="Ingrese el lote" />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="quantity">Cantidad</label>
                                                <input type="number" class="form-control" id="quantity" placeholder="Ingrese la cantidad" />
                                            </div>
                                            <input type="hidden" id="codBejerman" />
                                            <button type="button" class="btn btn-primary" onclick="addProduct()">Agregar Producto</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Formulario de descripción -->
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Buscar Artículo por Descripción</h5>
                                    <small class="text-muted float-end">Seleccione un artículo</small>
                                </div>
                                <div class="card-body">
                                    <form id="description-form">
                                        <div class="mb-3">
                                            <label class="form-label" for="search-description">Descripción</label>
                                            <input type="text" class="form-control" id="search-description" placeholder="Ingrese una descripción" />
                                        </div>
                                        <div id="search-results" style="display: none;">
                                            <label class="form-label" for="description-results">Resultados</label>
                                            <select class="form-select" id="description-results">
                                                <!-- Los resultados de búsqueda se llenan aquí -->
                                            </select>
                                        </div>
                                        <div id="description-product-details" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label" for="desc-description">Descripción</label>
                                                <input type="text" class="form-control" id="desc-description" readonly />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="desc-batch">Lote</label>
                                                <input type="text" class="form-control" id="desc-batch" placeholder="Ingrese el lote" />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="desc-quantity">Cantidad</label>
                                                <input type="number" class="form-control" id="desc-quantity" placeholder="Ingrese la cantidad" />
                                            </div>
                                            <input type="hidden" id="desc-codBejerman" />
                                            <input type="hidden" id="desc-codBarras" />
                                            <button type="button" class="btn btn-primary" onclick="addProductByDescription()">Agregar Producto</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


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
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <!-- Page JS -->
            <script src="../../assets/js/dashboards-analytics.js"></script>

            <script>
            const productData = [];

            $(document).ready(function() {
                // Buscar por código de barras
                $('#barcode').on('input', function() {
                    const barcode = $(this).val();
                    if (barcode) {
                        $.ajax({
                            url: '../../backend/controller/locales/DevolucionesController.php',
                            type: 'POST',
                            data: {
                                action: 'buscarArticulo',
                                codBarras: barcode
                            },
                            dataType: 'json',
                            success: function(data) {
                                if (data && data.codBarras) {
                                    $('#description').val(data.descripcion);
                                    $('#product-details').show();
                                    $('#codBejerman').val(data.codBejerman);
                                } else {
                                    $('#product-details').hide();
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', error);
                                console.error('Detalles:', xhr.responseText);
                                alert('Error al buscar el producto.');
                            },
                        });
                    }
                });

                // Buscar artículos por descripción
                $('#search-description').on('input', function() {
                    searchByDescription();
                });

                // Mostrar detalles al seleccionar un artículo del select
                $('#description-results').on('change', function() {
                    const selectedOption = $(this).find(':selected');
                    const codBarras = selectedOption.val();
                    const description = selectedOption.data('desc');
                    const codBejerman = selectedOption.data('codbejerman');

                    if (codBarras) {
                        $('#desc-description').val(description);
                        $('#desc-codBejerman').val(codBejerman);
                        $('#desc-codBarras').val(
                        codBarras); // Almacena el código de barras en un campo oculto
                        $('#description-product-details').show();
                    } else {
                        $('#description-product-details').hide();
                    }
                });
            });

            // Función para buscar artículos por descripción
            function searchByDescription() {
                const description = $('#search-description').val();
                if (description.length >= 3) { // Inicia la búsqueda después de 3 caracteres
                    $.ajax({
                        url: '../../backend/controller/locales/DevolucionesController.php',
                        type: 'POST',
                        data: {
                            action: 'buscarPorDescripcion',
                            descripcion: description
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.length > 0) {
                                const options = data.map(item =>
                                    `<option value="${item.codBarras}" data-desc="${item.descripcion}" data-codbejerman="${item.codBejerman}">
                            ${item.descripcion} - ${item.codBejerman}
                        </option>`
                                );
                                $('#description-results').html(options.join(''));
                                $('#search-results').show();
                            } else {
                                $('#description-results').html(
                                    '<option value="">No se encontraron resultados</option>');
                                $('#search-results').show();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            console.error('Detalles:', xhr.responseText);
                            alert('Error al buscar los productos.');
                        }
                    });
                } else {
                    $('#search-results').hide();
                }
            }

            // Función para agregar producto desde el formulario de código de barras
            function addProduct() {
                const barcode = $('#barcode').val();
                const batch = $('#batch').val();
                const quantity = $('#quantity').val();
                const description = $('#description').val();
                const codBejerman = $('#codBejerman').val();

                if (barcode && batch && quantity && description && codBejerman) {
                    // Validar duplicados
                    const exists = productData.some(product => product.codBarras === barcode);
                    if (exists) {
                        alert('El producto ya está en la lista.');
                        return;
                    }

                    productData.push({
                        codBejerman: codBejerman,
                        codBarras: barcode,
                        partida: batch,
                        cantidad: quantity,
                        descripcion: description
                    });

                    const row = `
        <tr>
            <td>${codBejerman}</td>
            <td>${description}</td>
            <td>${batch}</td>
            <td>${quantity}</td>
            <td>
                <button type="button" class="btn btn-icon btn-label-danger" onclick="removeProduct(this)">
                    <span class="tf-icons bx bx-trash"></span>
                </button>
            </td>
        </tr>
        `;
                    $('#product-list').append(row);

                    // Actualizar resumen
                    updateSummary();

                    // Limpiar campos
                    $('#barcode').val('');
                    $('#batch').val('');
                    $('#quantity').val('');
                    $('#description').val('');
                    $('#codBejerman').val('');
                    $('#product-details').hide();
                } else {
                    alert('Por favor complete todos los campos.');
                }
            }

            // Función para agregar producto desde el formulario de descripción
            function addProductByDescription() {
                const description = $('#desc-description').val();
                const batch = $('#desc-batch').val();
                const quantity = $('#desc-quantity').val();
                const codBejerman = $('#desc-codBejerman').val();
                const codBarras = $('#desc-codBarras').val();

                if (description && batch && quantity && codBejerman && codBarras) {
                    // Validar duplicados
                    const exists = productData.some(product => product.codBarras === codBarras);
                    if (exists) {
                        alert('El producto ya está en la lista.');
                        return;
                    }

                    productData.push({
                        codBejerman: codBejerman,
                        codBarras: codBarras,
                        partida: batch,
                        cantidad: quantity,
                        descripcion: description
                    });

                    const row = `
                                <tr>
                                    <td>${codBejerman}</td>
                                    <td>${description}</td>
                                    <td>${batch}</td>
                                    <td>${quantity}</td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-label-danger" onclick="removeProduct(this)">
                                            <span class="tf-icons bx bx-trash"></span>
                                        </button>
                                    </td>
                                </tr>
                                `;
                    $('#product-list').append(row);

                    // Actualizar resumen
                    updateSummary();

                    // Limpiar campos
                    $('#search-description').val('');
                    $('#desc-batch').val('');
                    $('#desc-quantity').val('');
                    $('#desc-description').val('');
                    $('#desc-codBejerman').val('');
                    $('#desc-codBarras').val('');
                    $('#description-product-details').hide();
                } else {
                    alert('Por favor complete todos los campos.');
                }
            }

            // Función para eliminar producto de la lista
            function removeProduct(button) {
                const row = $(button).closest('tr');
                const codBarras = row.find('td').eq(0).text();
                productData.splice(productData.findIndex(product => product.codBarras === codBarras), 1);
                row.remove();
                updateSummary();
            }

            // Función para actualizar el resumen de la lista
            function updateSummary() {
                const totalProducts = productData.length;
                const totalQuantity = productData.reduce((sum, product) => sum + parseInt(product.cantidad), 0);
                $('#total-products').text(totalProducts);
                $('#total-quantity').text(totalQuantity);
            }

            // Función para enviar los productos al servidor
            function sendProducts() {
                if (productData.length > 0) {
                    $.ajax({
                        url: '../../backend/controller/locales/DevolucionesController.php',
                        type: 'POST',
                        data: {
                            action: 'registrarDevoluciones',
                            articulos: productData
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: '¡Éxito!',
                                    text: 'Devoluciones registradas con éxito.',
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar'
                                }).then(() => {
                                    $('#product-list').empty();
                                    productData.length = 0;
                                    updateSummary();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Ocurrió un error al registrar las devoluciones.',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            console.error('Detalles:', xhr.responseText);
                            Swal.fire({
                                title: 'Error',
                                text: 'No se pudo procesar la solicitud.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Advertencia',
                        text: 'No hay productos para enviar.',
                        icon: 'warning',
                        confirmButtonText: 'Aceptar'
                    });
                }
            }
            </script>
</body>

</html>