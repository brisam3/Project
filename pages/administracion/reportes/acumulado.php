<!DOCTYPE html>
<html lang="es" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../../../assets/" data-template="horizontal-menu-template">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>Resumen Diario</title>
 

  <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.1/css/boxicons.min.css" rel="stylesheet" integrity="sha512-cfBUsnQh7OSdceLgoYe8n5f4gR8wMSAEPr7iZYswqlN4OrcKUYxxCa5XPrp2XrtH0nXGGaOb7SfiI4Rkzr3psA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="icon" type="image/x-icon" href="../../../assets/img/favicon/favicon.ico" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

  <!-- Icons -->
  <link rel="stylesheet" href="../../../assets/vendor/fonts/boxicons.css" />
  <link rel="stylesheet" href="../../../assets/vendor/fonts/fontawesome.css" />
  <link rel="stylesheet" href="../../../assets/vendor/fonts/flag-icons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="../../../assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="../../../assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="../../../assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="../../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="../../../assets/vendor/libs/typeahead-js/typeahead.css" />
  <link rel="stylesheet" href="../../../assets/vendor/libs/apex-charts/apex-charts.css" />

  <!-- Page CSS -->
  <link rel="stylesheet" href="../css/clima.css" />

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
              <div class="row">

                <!-- ASIDE IZQUIERDO: Menú de Opciones -->
                <div class="col-md-3">
                  <aside>
                    <ul class="nav flex-column">
                      <li class="nav-item">
                        <a class="nav-link active" href="#">Resumen Diario</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#">Preventa</a>
                      </li>
                    </ul>
                  </aside>
                </div>

                <!-- SECCION CENTRAL: Resumenes -->
                <div class="col-md-9">

                  <!-- Cards de Resumen -->
                  <div class="row">
                    <div class="col-md-4 mb-4">
                      <div class="card">
                        <div class="card-body text-center">
                          <h5 class="card-title">Total Rendido Hoy</h5>
                          <p class="card-text">1000</p>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-4 mb-4">
                      <div class="card">
                        <div class="card-body text-center">
                          <h5 class="card-title">Total Ventas Choferes</h5>
                          <p class="card-text">500</p>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-4 mb-4">
                      <div class="card">
                        <div class="card-body text-center">
                          <h5 class="card-title">Total Locales</h5>
                          <p class="card-text">30</p>
                        </div>
                      </div>
                    </div>

                   
                  </div>

                  <!-- Tablas de Resúmenes -->
                  <div class="row">
                    <!-- Ventas por Movil -->
                    <div class="col-12 mb-4">
                      <h6>Ventas por Movil</h6>
                      <table class="table table-striped">
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

                    <!-- Totales por Medios de Pago -->
                    <div class="col-12 mb-4">
                      <h6>Totales por Medios de Pago</h6>
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Medio de Pago</th>
                            <th>Total Ventas</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                          <tr>
                    
                            <td>Efectivo</td>
                            <td>$40</td>
                          </tr>
                         
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




<script src="../../../assets/vendor/libs/jquery/jquery.js"></script>
<script src="../../../assets/vendor/libs/popper/popper.js"></script>
<script src="../../../assets/vendor/js/bootstrap.js"></script>
<script src="../../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

<script src="../../../assets/vendor/libs/hammer/hammer.js"></script>
<script src="../../../assets/vendor/libs/i18n/i18n.js"></script>
<script src="../../../assets/vendor/libs/typeahead-js/typeahead.js"></script>

<script src="../../../assets/vendor/js/menu.js"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="../../../assets/vendor/libs/apex-charts/apexcharts.js"></script>

<!-- Main JS -->
<script src="../../../assets/js/main.js"></script>

<!-- Page JS -->
<script src="../../../assets/js/dashboards-analytics.js"></script>


</body>

</html>
