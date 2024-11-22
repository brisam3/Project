<?php
// Incluir el controlador de acceso
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../backend/controller/access/AccessController.php';

$accessController = new AccessController();

// Verificar si el acceso está permitido
if (!$accessController->checkAccess('/pages/administracion/ImportarReporte.php')) {
    $accessController->denyAccess();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
  data-assets-path="../../assets/" data-template="horizontal-menu-template">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>Importar Excel - Reportes</title>
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

    <!-- Helpers -->
    <script src="../../assets/vendor/js/helpers.js"></script>

<!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
<!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
      <script src="../../assets/vendor/js/template-customizer.js"></script>
      <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
      <script src="../../assets/js/config.js"></script>


  <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />

  
  <script src="../../assets/vendor/libs/jquery/jquery.js"></script>

 <!-- Favicon -->
 <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.1/css/boxicons.min.css" rel="stylesheet" integrity="sha512-cfBUsnQh7OSdceLgoYe8n5f4gR8wMSAEPr7iZYswqlN4OrcKUYxxCa5XPrp2XrtH0nXGGaOb7SfiI4Rkzr3psA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
  href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
  rel="stylesheet" />




</head>

<body>
<div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
<div class="layout-container">

  <?php include "../template/nav.php"; ?>

  <div class="layout-page">
    <div class="content-wrapper">
      <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="mb-0">Importar Archivo Excel</h5>
              </div>
              <div class="card-body">
                <form id="importExcelForm" enctype="multipart/form-data">
                  <div class="mb-3">
                    <label for="excelFile" class="form-label">Seleccione un archivo Excel (.xlsx)</label>
                    <input type="file" class="form-control" id="excelFile" name="excelFile" accept=".xlsx" required />
                  </div>
                  <button type="submit" class="btn btn-primary">Importar</button>
                </form>
                <div id="importResult" class="mt-4">
                  <!-- Resultados de la importación -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="content-backdrop fade"></div>
    <!-- build:js assets/vendor/js/core.js -->
  <!-- Overlay -->
  <div class="layout-overlay layout-menu-toggle"></div>

  <!-- Drag Target Area To SlideIn Menu On Small Screens -->
  <div class="drag-target"></div>
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
      $('#importExcelForm').on('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        console.log('Enviando el formulario...');

        $.ajax({
          url: '../../backend/controller/administracion/ImportarReporte.php',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            console.log('Respuesta del servidor:', response);
            $('#importResult').html(`<div class="alert alert-success">${response}</div>`);
          },
          error: function (xhr, status, error) {
            console.error('Error:', error);
            console.error('Detalles del error:', xhr.responseText);
            $('#importResult').html(`<div class="alert alert-danger">Error al importar el archivo: ${xhr.responseText}</div>`);
          }
        });
      });
    });
  </script>
</body>
</html>
