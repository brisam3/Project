<?php
// Incluir el controlador de acceso
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../backend/controller/access/AccessController.php';

$accessController = new AccessController();

// Verificar si el acceso está permitido
if (!$accessController->checkAccess('/pages/devoluciones/vistaDevolucionesLocales.php')) {
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
                        <div class="col-xl-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Artículos Cargados</h5>
                                </div>
                                <div class="card-body">
                                <div class="table-responsive">
  <table class="invoice-list-table table border-top">
    <thead>
      <tr>
        <th>#ID</th> <!-- Mostrará codBejerman -->
        <th>Descripción</th> <!-- Mostrará descripcion -->
        <th>Lote</th> <!-- Mostrará batch -->
        <th>Cantidad</th> <!-- Mostrará quantity -->
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody id="product-list">
    </tbody>
  </table>
</div>

                                </div>
                            </div>
                            <!-- Contenedor centrado para el botón Enviar -->
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-primary" onclick="sendProducts()">Enviar</button>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Cargar Artículo</h5>
                                    <small class="text-muted float-end">Formulario de carga de stock</small>
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

    $(document).ready(function () {
      $('#barcode').on('input', function () {
        const barcode = $(this).val();
        if (barcode) {
          $.ajax({
            url: '../../backend/controller/devoluciones/localess/devolucionesController.php',
            type: 'POST',
            data: { action: 'buscarArticulo', codBarras: barcode },
            dataType: 'json',
            success: function (data) {
              console.log(data);
              if (data && data.codBarras) {
                $('#description').val(data.descripcion);
                $('#product-details').show();
                $('#codBejerman').val(data.codBejerman);
              } else {
                $('#product-details').hide();
              }
            },
            error: function (xhr, status, error) {
              console.error('Error:', error);
              console.error('Detalles:', xhr.responseText);
              alert('Error al buscar el producto.');
            },
          });
        }
      });
    });

    function addProduct() {
  const barcode = $('#barcode').val();
  const batch = $('#batch').val();
  const quantity = $('#quantity').val();
  const description = $('#description').val();
  const codBejerman = $('#codBejerman').val(); 

  if (barcode && batch && quantity && description && codBejerman) {
    productData.push({ 
      codBejerman: codBejerman,
      codBarras: barcode, 
      partida: batch, 
      cantidad: quantity, 
      descripcion: description 
    });

    const row = `
      <tr>
        <td>${codBejerman}</td> <!-- Columna para codBejerman -->
        <td>${description}</td> <!-- Columna para descripcion -->
        <td>${batch}</td> <!-- Columna para batch -->
        <td>${quantity}</td> <!-- Columna para quantity -->
        <td>
          <button type="button" class="btn btn-icon btn-label-danger" onclick="removeProduct(this)">
            <span class="tf-icons bx bx-trash"></span>
          </button>
        </td>
      </tr>
    `;
    $('#product-list').append(row);

    // Limpiar campos después de agregar el producto
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


    function removeProduct(button) {
      const row = $(button).closest('tr');
      row.remove();
    }

    function sendProducts() {
  if (productData.length > 0) {
    $.post(
      '../../backend/controller/devoluciones/localess/devolucionesController.php',
      { 
        action: 'registrarDevoluciones', 
        articulos: productData
      },
      function (response) {
        if (response.success) {
          Swal.fire({
            title: '¡Éxito!',
            text: 'Devoluciones registradas con éxito.',
            icon: 'success',
            confirmButtonText: 'Aceptar'
          }).then(() => {
            $('#product-list').empty();
            productData.length = 0;
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
      'json'
    );
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
