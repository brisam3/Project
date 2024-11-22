<?php
session_start();
if (!isset($_SESSION['idUsuario'])) {
  // Redirigir al usuario de vuelta a la página de inicio de sesión si no está autenticado
  header("Location: https://softwareparanegociosformosa.com/wol/pages/login/login.html");
  exit();
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.1/css/boxicons.min.css" rel="stylesheet" integrity="sha512-cfBUsnQh7OSdceLgoYe8n5f4gR8wMSAEPr7iZYswqlN4OrcKUYxxCa5XPrp2XrtH0nXGGaOb7SfiI4Rkzr3psA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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

      <!-- Content -->
      <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
          <div class="col-lg-8 mb-4 order-0">
            <div class="card">
              <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                  <div class="card-body">
                    <h5 class="card-title text-primary">Bienvenido  🎉</h5>
                    <p class="mb-4">
                      Frase del día: Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos amet animi officia
                    </p>

                    <a href="javascript:;" class="btn btn-sm btn-label-primary">Enviar mi frase</a>
                  </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                  <div class="card-body pb-0 px-0 px-md-4">
                    <img src="../../assets/img/illustrations/man-with-laptop-light.png" height="140"
                      alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                      data-app-light-img="illustrations/man-with-laptop-light.png" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tarjeta de Fecha, Hora y Clima -->
          <div class="col-lg-4 col-md-6 col-12 mb-4">
            <div class="card text-center p-4 animate__animated animate__fadeIn" id="weather-card">
              <div class="card-body">
                <!-- Sección Fecha y Hora -->
                <div class="d-flex align-items-center justify-content-start mb-3">
                  <div
                    class="icon-circle bg-light rounded-circle d-flex align-items-center justify-content-center animate__animated animate__bounceIn">
                    <i class="bx bx-calendar fs-4 text-secondary"></i>
                  </div>
                  <div class="ms-3 text-start">
                    <h6 class="mb-0 text-muted" style="font-size: 0.9rem;">Fecha y Hora</h6>
                    <p id="datetime" class="mb-0 fw-semibold" style="font-size: 1rem; color: #344767;"></p>
                  </div>
                </div>
                <hr class="my-3">
                <!-- Sección Clima -->
                <div class="d-flex align-items-center justify-content-start">
                  <div
                    class="icon-circle bg-light rounded-circle d-flex align-items-center justify-content-center animate__animated animate__bounceIn delay-1s">
                    <i class="bx bx-cloud fs-4 text-secondary"></i>
                  </div>
                  <div class="ms-3 text-start">
                    <h6 class="mb-0 text-muted" style="font-size: 0.9rem;">Clima en Formosa</h6>
                    <p id="weather" class="mb-0 fw-semibold" style="font-size: 1rem; color: #344767;"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!--/ Total Revenue -->

        </div>
        <!--/ Content -->

        <!-- Footer -->

        <!-- / Footer -->

        <div class="content-backdrop fade"></div>
      </div>
      <!--/ Content wrapper -->
    </div>

    <!--/ Layout container -->
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
    const apiKey = '344657a21fbb4f1ea23220421242008'; // Reemplaza con tu API key de WeatherAPI
    const ciudad = 'Formosa';
    const url = `https://api.weatherapi.com/v1/current.json?key=${apiKey}&q=${ciudad}&lang=es`;

    // Obtener la fecha y hora actuales
    function updateDateTime() {
      const now = new Date();
      const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
      document.getElementById('datetime').innerText = `Hoy es ${now.toLocaleDateString('es-AR', options)}`;
    }

    // Obtener el clima de Formosa, Argentina
    async function updateWeather() {
      try {
        const response = await fetch(url);
        const data = await response.json();
        const weatherDescription = `En la Ciudad de Formosa hace ${data.current.temp_c}°C y está ${data.current.condition.text.toLowerCase()}`;
        document.getElementById('weather').innerText = weatherDescription;
      } catch (error) {
        document.getElementById('weather').innerText = 'No se pudo obtener el clima.';
      }
    }

    // Actualizar la hora, fecha y clima cada minuto
    setInterval(updateDateTime, 60000);
    setInterval(updateWeather, 60000);

    // Actualizar inicialmente
    updateDateTime();
    updateWeather();
  </script>


</body>

</html>