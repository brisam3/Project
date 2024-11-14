<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../../assets/" data-template="horizontal-menu-template">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>Cierre de Caja | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>
  <meta name="description" content="" />
  <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />
  <link rel="stylesheet" href="../../assets/vendor/fonts/fontawesome.css" />
  <link rel="stylesheet" href="../../assets/vendor/fonts/flag-icons.css" />
  <link rel="stylesheet" href="../../assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="../../assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="../../assets/css/demo.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/typeahead-js/typeahead.css" />
  <link rel="stylesheet" href="../css/clima.css" />
  <script src="../../assets/vendor/js/helpers.js"></script>
  <script src="../../assets/vendor/js/template-customizer.js"></script>
  <script src="../../assets/js/config.js"></script>
</head>
<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
    <div class="layout-container">
      <!-- Nav -->
      <?php include "../template/nav.php"; ?>
      <!-- Page content -->
      <div class="layout-page">
        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Formulario /</span> Cierre de Caja</h4>
            <div class="row">
              <div class="col-xl-6">
                <div class="card mb-4">
                  <div class="card-header">
                    <h5 class="mb-0">Resumen de Cierre de Caja</h5>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table border-top">
                        <thead>
                          <tr>
                            <th>Medio de Pago</th>
                            <th>Total</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>Efectivo</td>
                            <td><input type="number" id="total-efectivo" class="form-control" value="0.00" /></td>
                          </tr>
                          <tr>
                            <td>Mercado Pago</td>
                            <td><input type="number" id="total-mercadopago" class="form-control" value="0.00" /></td>
                          </tr>
                          <tr>
                            <td>Transferencias</td>
                            <td><input type="number" id="total-transferencias" class="form-control" value="0.00" /></td>
                          </tr>
                          <tr>
                            <td>Cheques</td>
                            <td><input type="number" id="total-cheques" class="form-control" value="0.00" /></td>
                          </tr>
                          <tr>
                            <td>Cuenta Corriente</td>
                            <td><input type="number" id="total-cuenta-corriente" class="form-control" value="0.00" /></td>
                          </tr>
                          <tr>
                            <td>Gastos</td>
                            <td><input type="number" id="total-gastos" class="form-control" value="0.00" /></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="text-center mt-3">
                      <button type="button" class="btn btn-primary" onclick="confirmarCierreCaja()">Confirmar Cierre</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-6">
                <div class="card mb-4">
                  <div class="card-header">
                    <h5 class="mb-0">Total Acumulado</h5>
                  </div>
                  <div class="card-body">
                    <div class="text-center">
                      <h3 id="total-acumulado">$0.00</h3>
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
  <!-- Scripts -->
  <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../../assets/vendor/libs/popper/popper.js"></script>
  <script src="../../assets/vendor/js/bootstrap.js"></script>
  <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="../../assets/vendor/libs/hammer/hammer.js"></script>
  <script src="../../assets/vendor/libs/i18n/i18n.js"></script>
  <script src="../../assets/vendor/libs/typeahead-js/typeahead.js"></script>
  <script src="../../assets/vendor/js/menu.js"></script>
  <script src="../../assets/vendor/libs/apex-charts/apexcharts.js"></script>
  <script src="../../assets/js/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Custom JS -->
  <script>
    function actualizarTotalAcumulado() {
      const efectivo = parseFloat(document.getElementById('total-efectivo').value) || 0;
      const mercadoPago = parseFloat(document.getElementById('total-mercadopago').value) || 0;
      const transferencias = parseFloat(document.getElementById('total-transferencias').value) || 0;
      const cheques = parseFloat(document.getElementById('total-cheques').value) || 0;
      const cuentaCorriente = parseFloat(document.getElementById('total-cuenta-corriente').value) || 0;
      const gastos = parseFloat(document.getElementById('total-gastos').value) || 0;
      
      const totalAcumulado = efectivo + mercadoPago + transferencias + cheques + cuentaCorriente - gastos;
      document.getElementById('total-acumulado').innerText = `$${totalAcumulado.toFixed(2)}`;
    }
    
    document.querySelectorAll('input[type="number"]').forEach(input => {
      input.addEventListener('input', actualizarTotalAcumulado);
    });
    
    function confirmarCierreCaja() {
      const efectivo = parseFloat(document.getElementById('total-efectivo').value) || 0;
      const mercadoPago = parseFloat(document.getElementById('total-mercadopago').value) || 0;
      const transferencias = parseFloat(document.getElementById('total-transferencias').value) || 0;
      const cheques = parseFloat(document.getElementById('total-cheques').value) || 0;
      const cuentaCorriente = parseFloat(document.getElementById('total-cuenta-corriente').value) || 0;
      const gastos = parseFloat(document.getElementById('total-gastos').value) || 0;
      
      const totalCierre = efectivo + mercadoPago + transferencias + cheques + cuentaCorriente - gastos;
      
      Swal.fire({
        title: 'Confirmar Cierre de Caja',
        text: `¿Está seguro que desea realizar el cierre de caja? Total: $${totalCierre.toFixed(2)}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, confirmar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire('Cierre Confirmado', 'El cierre de caja se ha realizado con éxito.', 'success');
        }
      });
    }
  </script>
</body>
</html>