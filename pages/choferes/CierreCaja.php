<?php
// Incluir el controlador de acceso
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../backend/controller/access/AccessController.php';

$accessController = new AccessController();

// Verificar si el acceso está permitido
if (!$accessController->checkAccess('/pages/choferes/CierreCaja.php')) {
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
    <title>Cierre de Caja - Chofer | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Analytics | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

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
    <link rel="stylesheet" href="../../../assets/vendor/fonts/flag-icons.css" />

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
    table {
        width: 100%;
        border-collapse: collapse;
        /* Elimina espacios entre bordes */
        table-layout: auto;
        /* Permite que las columnas se ajusten automáticamente */
    }

    thead th,
    tbody td {
        padding: 8px;
        /* Reduce el padding */
        text-align: left;
        font-size: 14px;
        /* Tamaño de fuente equilibrado */
        word-wrap: break-word;
        /* Evita que el contenido salga de la celda */
    }

    tbody tr td:first-child {
        width: 35%;
        /* Ajusta el ancho de la primera columna */
    }

    tbody tr td {
        vertical-align: middle;
        /* Alineación vertical del contenido */
    }

    .form-control {
        width: 100%;
        /* Ocupa el 100% del espacio disponible */
        font-size: 14px;
        /* Tamaño del texto dentro del input */
        padding: 6px;
        /* Espaciado interno reducido */
        box-sizing: border-box;
        /* Asegura que el padding no afecte el ancho */
    }

    .btn {
        font-size: 14px;
        /* Tamaño del texto de los botones */
        padding: 6px 10px;
        /* Reduce el tamaño de los botones */
    }

    tbody tr {
        height: auto;
        /* Permite que la altura se ajuste al contenido */
    }

    tbody tr.table-light {
        background-color: #f9f9f9;
        /* Fondo claro para filas destacadas */
    }
    </style>




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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Formulario /</span> Cierre de
                            Caja - Chofer</h4>
                        <div class="row">
                            <!-- Columna Izquierda - Formulario de Cierre -->
                            <div class="col-xl-6">
                                <div class="card mb-4">

                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table border-top ">
                                                <thead>

                                                </thead>
                                                <tbody>
                                                    <tr class="table-light">
                                                        <td>
                                                            <i class="bx bx-user"></i> Preventista
                                                        </td>
                                                        <td>
                                                            <select id="idUsuarioPreventista" class="form-control">
                                                                <option value="" disabled selected>Seleccione un
                                                                    preventista</option>
                                                                <option value="8">Movil101-Mica</option>
                                                                <option value="9">Movil102-Gustavo</option>
                                                                <option value="10">Movil103-Leo</option>
                                                                <option value="11">Movil104-Alexander</option>
                                                                <option value="12">Movil105-Diego</option>
                                                                <option value="13">Movil106-Cristian</option>
                                                                <option value="14">Movil107-Marianela</option>
                                                                <option value="15">Movil8-Guille</option>
                                                                <option value="16">Movil9-Soledad</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="bx bx-money"></i> Efectivo</td>
                                                        <td><input type="number" id="total-efectivo"
                                                                class="form-control" readonly placeholder="0" />
                                                            <button type="button" class="btn btn-primary"
                                                                onclick="abrirModalBilletes()"><i
                                                                    class="fas fa-plus-circle"> $</i></button>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td><i class="bx bx-transfer"></i> Transferencias</td>
                                                        <td><input type="number" id="total-transferencia"
                                                                class="form-control" placeholder="0" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="bx bxl-mercadopago"></i> Mercado Pago</td>
                                                        <td><input type="number" id="total-mercadopago"
                                                                class="form-control" placeholder="0" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="bx bx-credit-card"></i> Cheques</td>
                                                        <td><input type="number" id="total-cheques" class="form-control"
                                                                placeholder="0" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="bx bx-receipt"></i> Fiados</td>
                                                        <td><input type="number" id="total-fiados" class="form-control"
                                                                placeholder="0" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="bx bx-wallet"></i> Gastos</td>
                                                        <td><input type="number" id="total-gastos" class="form-control"
                                                                placeholder="0" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="bx bx-money-withdraw"></i> Pago Secretario</td>
                                                        <td><input type="number" id="pago-secretario"
                                                                class="form-control" placeholder="0" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="bx bx-error"></i> MEC Faltante</td>
                                                        <td><input type="number" id="total-mec-faltante"
                                                                class="form-control" placeholder="0" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td><i class="bx bx-x-circle"></i> Total Rechazos</td>
                                                        <td><input type="number" id="total-rechazos"
                                                                class="form-control" placeholder="0" /></td>
                                                    </tr>
                                                    <input type="hidden" id="total_general" name="total_general" />
                                                    <input type="hidden" id="total_menos_gastos"
                                                        name="total_menos_gastos" />
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-center mt-3">
                                            <button type="button" class="btn btn-primary"
                                                onclick="confirmarCierreCaja()">Confirmar Cierre</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna Derecha - Total Acumulado -->
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
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Contrareembolso</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center">
                                            <h3 id="contrareembolso"></h3>
                                        </div>
                                    </div>
                                    <div class="card-header">
                                        <h5 class="mb-0">Diferencia</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center">
                                            <h3 id="diferencia">$0.00</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="CierreCajaAnterior.php" class="text-decoration-underline fs-5">¿Deseas realizar el cierre de caja de un día anterior? </a>
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
                            <button type="button" class="btn-close" onclick="cerrarModalBilletes()"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3"><label>Billetes de 20000</label><input type="number" id="billetes_20000"
                                    class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
                            <div class="mb-3"><label>Billetes de 10000</label><input type="number" id="billetes_10000"
                                    class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
                            <div class="mb-3"><label>Billetes de 2000</label><input type="number" id="billetes_2000"
                                    class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
                            <div class="mb-3"><label>Billetes de 1000</label><input type="number" id="billetes_1000"
                                    class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
                            <div class="mb-3"><label>Billetes de 500</label><input type="number" id="billetes_500"
                                    class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
                            <div class="mb-3"><label>Billetes de 200</label><input type="number" id="billetes_200"
                                    class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
                            <div class="mb-3"><label>Billetes de 100</label><input type="number" id="billetes_100"
                                    class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
                            <div class="mb-3"><label>Billetes de 50</label><input type="number" id="billetes_50"
                                    class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
                            <div class="mb-3"><label>Billetes de 20</label><input type="number" id="billetes_20"
                                    class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
                            <div class="mb-3"><label>Billetes de 10</label><input type="number" id="billetes_10"
                                    class="form-control" placeholder="0" oninput="calcularTotalEfectivo()" /></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                onclick="cerrarModalBilletes()">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scripts -->

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
            function abrirModalBilletes() {
                document.getElementById('modalBilletes').style.display = 'block';
            }

            function cerrarModalBilletes() {
                document.getElementById('modalBilletes').style.display = 'none';
            }

            function calcularTotalEfectivo() {
                const billetes_20000 = parseInt(document.getElementById('billetes_20000').value) || 0;
                const billetes_10000 = parseInt(document.getElementById('billetes_10000').value) || 0;
                const billetes_2000 = parseInt(document.getElementById('billetes_2000').value) || 0;
                const billetes_1000 = parseInt(document.getElementById('billetes_1000').value) || 0;
                const billetes_500 = parseInt(document.getElementById('billetes_500').value) || 0;
                const billetes_200 = parseInt(document.getElementById('billetes_200').value) || 0;
                const billetes_100 = parseInt(document.getElementById('billetes_100').value) || 0;
                const billetes_50 = parseInt(document.getElementById('billetes_50').value) || 0;
                const billetes_20 = parseInt(document.getElementById('billetes_20').value) || 0;
                const billetes_10 = parseInt(document.getElementById('billetes_10').value) || 0;

                const total = (billetes_20000 * 20000) + (billetes_10000 * 10000) + (billetes_2000 * 2000) + (
                        billetes_1000 *
                        1000) +
                    (billetes_500 * 500) + (billetes_200 * 200) + (billetes_100 * 100) +
                    (billetes_50 * 50) + (billetes_20 * 20) + (billetes_10 * 10);

                document.getElementById('total-efectivo').value = total.toFixed(2);
                actualizarTotalAcumulado();
            }

            function actualizarTotalAcumulado() {
                const efectivo = parseFloat(document.getElementById('total-efectivo').value) || 0;
                const mercadoPago = parseFloat(document.getElementById('total-mercadopago').value) || 0;
                const transferencias = parseFloat(document.getElementById('total-transferencia').value) || 0;
                const cheques = parseFloat(document.getElementById('total-cheques').value) || 0;
                const fiados = parseFloat(document.getElementById('total-fiados').value) || 0;
                const gastos = parseFloat(document.getElementById('total-gastos').value) || 0;
                const pagoSecretario = parseFloat(document.getElementById('pago-secretario').value) || 0;
                const mecFaltante = parseFloat(document.getElementById('total-mec-faltante').value) || 0;
                const rechazos = parseFloat(document.getElementById('total-rechazos').value) || 0;

                const totalGeneral = efectivo + mercadoPago + transferencias + cheques + fiados + gastos +
                    pagoSecretario + mecFaltante + rechazos;

                document.getElementById('total-acumulado').innerText = `$${totalGeneral.toFixed(2)}`;

                // Actualiza los campos ocultos
                document.getElementById('total_general').value = totalGeneral.toFixed(2);
                document.getElementById('total_menos_gastos').value = (totalGeneral - gastos).toFixed(2);

                calcularDiferencia(); // Recalcula la diferencia después de actualizar el total acumulado
            }


            // Agrega un listener a cada input para actualizar dinámicamente
            document.querySelectorAll('input[type="number"]').forEach(input => {
                input.addEventListener('input', actualizarTotalAcumulado);
            });

            function guardarCierreCajaChofer() {


                const idUsuarioPreventista = document.getElementById('idUsuarioPreventista').value;
                const totalEfectivo = document.getElementById('total-efectivo')?.value || 0;
                const totalTransferencia = document.getElementById('total-transferencia')?.value || 0;
                const totalMercadoPago = document.getElementById('total-mercadopago')?.value || 0;
                const totalCheques = document.getElementById('total-cheques')?.value || 0;
                const totalFiados = document.getElementById('total-fiados')?.value || 0;
                const totalGastos = document.getElementById('total-gastos')?.value || 0;
                const pagoSecretario = document.getElementById('pago-secretario')?.value || 0;
                const totalMecFaltante = document.getElementById('total-mec-faltante')?.value || 0;
                const totalRechazos = document.getElementById('total-rechazos')?.value || 0;
                const totalGeneral = document.getElementById('total_general')?.value || 0;
                const totalMenosGastos = document.getElementById('total_menos_gastos')?.value || 0;
                const billetes_20000 = document.getElementById('billetes_20000')?.value || 0;
                const billetes_10000 = document.getElementById('billetes_10000')?.value || 0;
                const billetes_2000 = document.getElementById('billetes_2000')?.value || 0;
                const billetes_1000 = document.getElementById('billetes_1000')?.value || 0;
                const billetes_500 = document.getElementById('billetes_500')?.value || 0;
                const billetes_200 = document.getElementById('billetes_200')?.value || 0;
                const billetes_100 = document.getElementById('billetes_100')?.value || 0;
                const billetes_50 = document.getElementById('billetes_50')?.value || 0;
                const billetes_20 = document.getElementById('billetes_20')?.value || 0;
                const billetes_10 = document.getElementById('billetes_10')?.value || 0;
                const contrareembolso = parseFloat(document.getElementById('contrareembolso').innerText) || 0;

                $.ajax({
                    url: '../../backend/controller/choferes/CierreCajaController.php',
                    type: 'POST',
                    data: {
                        total_efectivo: parseFloat(totalEfectivo),
                        total_transferencia: parseFloat(totalTransferencia),
                        total_mercadopago: parseFloat(totalMercadoPago),
                        total_cheques: parseFloat(totalCheques),
                        total_fiados: parseFloat(totalFiados),
                        total_gastos: parseFloat(totalGastos),
                        pago_secretario: parseFloat(pagoSecretario),
                        total_mec_faltante: parseFloat(totalMecFaltante),
                        total_rechazos: parseFloat(totalRechazos),
                        idUsuarioPreventista: idUsuarioPreventista,
                        total_general: parseFloat(totalGeneral),
                        total_menos_gastos: parseFloat(totalMenosGastos),
                        billetes_20000: parseFloat(billetes_20000),
                        billetes_10000: parseFloat(billetes_10000),
                        billetes_2000: parseFloat(billetes_2000),
                        billetes_1000: parseFloat(billetes_1000),
                        billetes_500: parseFloat(billetes_500),
                        billetes_200: parseFloat(billetes_200),
                        billetes_100: parseFloat(billetes_100),
                        billetes_50: parseFloat(billetes_50),
                        billetes_20: parseFloat(billetes_20),
                        billetes_10: parseFloat(billetes_10),
                        contrareembolso: contrareembolso // Nuevo campo

                    },
                    success: function(response) {
                        Swal.fire('Cierre Guardado', 'El cierre de caja se ha guardado exitosamente',
                            'success');
                    }
                });
            }

            function confirmarCierreCaja() {
                const totalGeneral = parseFloat(document.getElementById('total_general').value) || 0;

                Swal.fire({
                    title: 'Confirmar Cierre de Caja',
                    text: `¿Está seguro que desea realizar el cierre de caja? Total General: $${totalGeneral.toFixed(2)}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        guardarCierreCajaChofer();
                    }
                });
            }

            document.querySelectorAll('input[type="number"]').forEach(input => {
                input.addEventListener('input', actualizarTotalAcumulado);
            });


            function calcularDiferencia() {
                const contrareembolsoText = document.getElementById('contrareembolso').innerText.replace(
                    'Contrareembolso: $',
                    ''
                );
                const contrareembolso = parseFloat(contrareembolsoText) || 0;

                const totalAcumuladoText = document.getElementById('total-acumulado').innerText.replace('$', '');
                const totalAcumulado = parseFloat(totalAcumuladoText) || 0;

                const diferencia = totalAcumulado - contrareembolso;

                // Formatear la diferencia con un "+" si es mayor a 0
                const diferenciaFormateada = diferencia > 0 ? `+${diferencia.toFixed(2)}` : diferencia.toFixed(2);

                document.getElementById('diferencia').innerText = `${diferenciaFormateada}`;
            }


            // Actualiza el contrareembolso al seleccionar un preventista
            document.getElementById('idUsuarioPreventista').addEventListener('change', function() {
                const idUsuarioPreventista = this.value;

                $.ajax({
                    url: '../../backend/controller/choferes/CierreCajaController.php',
                    type: 'POST',
                    data: {
                        action: 'obtenerContrareembolso',
                        idUsuarioPreventista: idUsuarioPreventista
                    },
                    success: function(response) {
                        try {
                            const data = JSON.parse(response);

                            if (!data.error) {
                                const contrareembolso = parseFloat(data.total_ventas).toFixed(2);
                                document.getElementById('contrareembolso').innerText =
                                    `${contrareembolso}`;
                                calcularDiferencia(); // Recalcula la diferencia
                            } else {
                                console.error('Error en la respuesta:', data.error);
                            }
                        } catch (e) {
                            console.error('Error al procesar la respuesta JSON:', e);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Detalles del error:', textStatus, errorThrown, jqXHR
                            .responseText);
                    }
                });
            });
            </script>




</body>

</html>