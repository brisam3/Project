<?php
// Incluir el controlador de acceso
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../backend/controller/access/AccessController.php';

$accessController = new AccessController();

// Verificar si el acceso está permitido
if (!$accessController->checkAccess('/pages/cierreCaja/cierreLocales.php')) {
    $accessController->denyAccess();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" class="layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../../assets/" data-template="horizontal-menu-template">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>Cierre de Caja - Locales</title>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

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
      <!-- Nav -->
      <?php include "../template/nav.php"; ?>
      <!-- Page content -->
      <div class="layout-page">
        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Formulario /</span> Cierre de Caja - Locales</h4>
            <div class="row">
              <!-- Columna Izquierda - Formulario de Cierre -->
              <div class="col-xl-6">
                <div class="card mb-4">
                  <div class="card-header">
                    <h5 class="mb-0">Resumen de Cierre de Caja</h5>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table border-top">
                        <thead>
                          <tr><th>Medio de Pago</th><th>Total</th></tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><i class="bx bx-money"></i> Efectivo</td>
                            <td><input type="number" id="total-efectivo" class="form-control" readonly placeholder="0" /></td>
                            <td><button type="button" class="btn btn-primary" onclick="abrirModalBilletes()">Ingresar Billetes</button></td>
                          </tr>
                          <tr><td><i class="bx bxl-mercadopago"></i> Mercado Pago</td><td><input type="number" id="total-mercadopago" class="form-control" placeholder="0" oninput="actualizarTotalAcumulado()" /></td></tr>
                          <tr><td><i class="bx bx-credit-card"></i>
                          PayWay</td><td><input type="number" id="total-payway" class="form-control" placeholder="0" oninput="actualizarTotalAcumulado()" /></td></tr>
                          <tr><td><i class="bx bx-transfer"></i>
                          </i>Cambio</td><td><input type="number" id="total-cambios" class="form-control" placeholder="0" oninput="actualizarTotalAcumulado()" /></td></tr>
                          <tr><td><i class="bx bx-receipt"></i> Cuenta Corriente</td><td><input type="number" id="total-cuenta-corriente" class="form-control" placeholder="0" oninput="actualizarTotalAcumulado()" /></td></tr>
                          <tr><td><i class="bx bx-wallet"></i> Gastos</td><td><input type="number" id="total-gastos" class="form-control" placeholder="0" oninput="actualizarTotalAcumulado()" /></td></tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="text-center mt-3">
                      <button type="button" class="btn btn-primary" onclick="confirmarCierreCaja()">Confirmar Cierre</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Columna Derecha - Total Acumulado -->
              <div class="col-xl-6">
                <div class="card mb-4">
                  <div class="card-header"><h5 class="mb-0">Total Acumulado</h5></div>
                  <div class="card-body text-center"><h3 id="total-acumulado">$0.00</h3></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para ingresar billetes -->
  <div id="modalBilletes" class="modal" tabindex="-1" style="display:none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ingreso de Billetes</h5>
          <button type="button" class="btn-close" onclick="cerrarModalBilletes()" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3"><label>Billetes de 10000</label><input type="number" id="billetes_10000" class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
          <div class="mb-3"><label>Billetes de 2000</label><input type="number" id="billetes_2000" class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
          <div class="mb-3"><label>Billetes de 1000</label><input type="number" id="billetes_1000" class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
          <div class="mb-3"><label>Billetes de 500</label><input type="number" id="billetes_500" class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
          <div class="mb-3"><label>Billetes de 200</label><input type="number" id="billetes_200" class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
          <div class="mb-3"><label>Billetes de 100</label><input type="number" id="billetes_100" class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
          <div class="mb-3"><label>Billetes de 50</label><input type="number" id="billetes_50" class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
          <div class="mb-3"><label>Billetes de 20</label><input type="number" id="billetes_20" class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
          <div class="mb-3"><label>Billetes de 10</label><input type="number" id="billetes_10" class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="cerrarModalBilletes()">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
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
   
  <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function abrirModalBilletes() {
      document.getElementById('modalBilletes').style.display = 'block';
    }

    function cerrarModalBilletes() {
      document.getElementById('modalBilletes').style.display = 'none';
    }

    function calcularTotalEfectivo() {
      const denominaciones = [10000, 2000, 1000, 500, 200, 100, 50, 20, 10];
      let efectivo = denominaciones.reduce((acc, denom) => {
        const value = parseInt(document.getElementById(`billetes_${denom}`).value) || 0;
        return acc + value * denom;
      }, 0);
      document.getElementById('total-efectivo').value = efectivo.toFixed(2);
      actualizarTotalAcumulado();
    }

    function actualizarTotalAcumulado() {
      const efectivo = parseFloat(document.getElementById('total-efectivo').value) || 0;
      const mercadoPago = parseFloat(document.getElementById('total-mercadopago').value) || 0;
      const payway = parseFloat(document.getElementById('total-payway').value) || 0;
      const cambios = parseFloat(document.getElementById('total-cambios').value) || 0;
      const cuentaCorriente = parseFloat(document.getElementById('total-cuenta-corriente').value) || 0;
      const gastos = parseFloat(document.getElementById('total-gastos').value) || 0;

      const totalAcumulado = efectivo + mercadoPago + payway + cambios + cuentaCorriente;
      document.getElementById('total-acumulado').innerText = `$${totalAcumulado.toFixed(2)}`;
    }

    function confirmarCierreCaja() {
      const totalAcumulado = parseFloat(document.getElementById('total-acumulado').innerText.replace('$', '')) || 0;
      const gastos = parseFloat(document.getElementById('total-gastos').value) || 0;
      const totalMenosGastos = totalAcumulado - gastos;

      Swal.fire({
        title: 'Confirmar Cierre de Caja',
        text: `¿Está seguro de realizar el cierre? Total General: $${totalAcumulado.toFixed(2)}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, confirmar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          guardarCierreCaja(totalAcumulado, totalMenosGastos);
        }
      });
    }

    function guardarCierreCaja(totalAcumulado, totalMenosGastos) {
      const data = {
        billetes_10000: parseInt(document.getElementById('billetes_10000').value) || 0,
        billetes_2000: parseInt(document.getElementById('billetes_2000').value) || 0,
        billetes_1000: parseInt(document.getElementById('billetes_1000').value) || 0,
        billetes_500: parseInt(document.getElementById('billetes_500').value) || 0,
        billetes_200: parseInt(document.getElementById('billetes_200').value) || 0,
        billetes_100: parseInt(document.getElementById('billetes_100').value) || 0,
        billetes_50: parseInt(document.getElementById('billetes_50').value) || 0,
        billetes_20: parseInt(document.getElementById('billetes_20').value) || 0,
        billetes_10: parseInt(document.getElementById('billetes_10').value) || 0,
        efectivo: parseFloat(document.getElementById('total-efectivo').value) || 0,
        mercado_pago: parseFloat(document.getElementById('total-mercadopago').value) || 0,
        payway: parseFloat(document.getElementById('total-payway').value) || 0,
        cambios: parseFloat(document.getElementById('total-cambios').value) || 0,
        cuenta_corriente: parseFloat(document.getElementById('total-cuenta-corriente').value) || 0,
        gastos: parseFloat(document.getElementById('total-gastos').value) || 0,
        total_general: totalAcumulado,
        total_menos_gastos: totalMenosGastos
      };

      $.ajax({
        url: '../../backend/controller/cierreCaja/CierreCajaLocales.php',
        type: 'POST',
        data: data,
        success: function(response) {
          const res = JSON.parse(response);
          if (res.success) {
            Swal.fire('Cierre Confirmado', res.success, 'success');
          } else {
            Swal.fire('Error', res.error, 'error');
          }
        },
        error: function() {
          Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
        }
      });
    }
  </script>
</body>
</html>
