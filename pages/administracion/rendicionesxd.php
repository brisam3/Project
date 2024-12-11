<?php
// Incluir el controlador de acceso
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../backend/controller/access/AccessController.php';


?>

<!DOCTYPE html>
<html lang="es" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../../assets/" data-template="horizontal-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Detalle de Rendiciones</title>


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


    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">



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


            <?php

      include "../template/nav.php";

      ?>

            <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">

                <div class="layout-container">
                    <div class="layout-page">
                        <div class="content-wrapper">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="container-xxl flex-grow-1 container-p-y">
                                        <div class="table-responsive-xl mb-6 mb-lg-0">
                                            <div class="dataTables_wrapper no-footer" style="width: 100% !important;">
                                                <div class="col-md-12 my-2">
                                                         <!-- Tabla de Detalle de Rendiciones -->
                                                    <div class="card p-3 my-2">
                                                        <div class="table-container my-2">
                                                            <!-- Añadí margen inferior para separación -->
                                                            <table id="tablaRendiciones"
                                                                class="datatables-ajax table table-bordered table-hover table-sm table table-striped">
                                                                <!-- Añadí margen inferior a la tabla principal -->
                                                                <thead id="theadRendiciones">
                                                                    <!-- Encabezados dinámicos -->
                                                                </thead>
                                                                <tbody id="tbodyRendiciones">
                                                                    <!-- Detalles dinámicos -->
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <!-- Tablas secundarias -->
                                                    <div class="card p-3 my-2">
                                                        <div id="tablasSecundarias" class='table-container my-2'>
                                                            <!-- Añadí margen superior para separación de tablas secundarias -->
                                                            <!-- Tablas por cada detalle dinámico -->
                                                        </div>
                                                    </div>
                                                    <div id="tablaResumenTotalPreventa"></div>
                                                    <div id="totalPreventa"></div>
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
    buscarDetalleRendiciones();

    function buscarDetalleRendiciones() {
        $.ajax({
            url: '../../backend/controller/administracion/Rendiciones.php',
            type: 'POST',
            data: {
                action: 'obtenerRendicionesConUsuarios',
            },
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    console.error('Error del backend:', response.mensaje);
                    alert('Ocurrió un error al obtener los detalles de las rendiciones.');
                    return;
                }

                const data = response.data;

                if (data && data.length > 0) {
                    // Crear encabezados de la tabla principal con el móvil
                    let headers = `
                        <tr>
                            <th>Movil</th>
                            ${data.map(detalle => `<th>${detalle.movil}</th>`).join('')}
                            <th>Totales</th>
                        </tr>
                        <tr>
                            <th>Preventista - Chofer</th>
                            ${data.map(detalle => `<th>${detalle.nombre_preventista} - ${detalle.nombre_chofer}</th>`).join('')}
                            <th></th>
                        </tr>`;
                    $('#theadRendiciones').html(headers);

                    // Crear filas para cada atributo
                    const atributos = [
                        { nombre: 'Total Ventas', campo: 'total_ventas', esEditable: false },
                        { nombre: 'MEC Faltante', campo: 'total_mec_faltante', esEditable: true },
                        { nombre: 'Rechazos', campo: 'total_rechazos', esEditable: true },
                        { nombre: 'Mercado Pago', campo: 'total_mercadopago', esEditable: true },
                        { nombre: 'Transferencias', campo: 'total_transferencia', esEditable: true },
                        { nombre: 'Fiados', campo: 'total_fiados', esEditable: true },
                        { nombre: 'Gastos', campo: 'total_gastos', esEditable: true },
                        { nombre: 'Pago Secretario', campo: 'pago_secretario', esEditable: true },
                        { nombre: 'Cheques', campo: 'total_cheques', esEditable: true },
                    ];

                    let html = '';
                    atributos.forEach(atributo => {
                        let total = 0;
                        html += `<tr>
                            <td>${atributo.nombre}</td>
                            ${data.map(detalle => {
                                const valor = detalle[atributo.campo] || 0;
                                total += parseFloat(valor);
                                return atributo.esEditable
                                    ? `<td><input type="number" class="form-control table-input" value="${valor}" style="-moz-appearance: textfield; width: 100%; padding: 2px; text-align: right;" /></td>`
                                    : `<td>${valor}</td>`;
                            }).join('')}
                            <td class="total-column" >${total.toFixed(2)}</td>
                        </tr>`;
                    });

                    $('#tbodyRendiciones').html(html);

                    // Actualizar dinámicamente la columna de totales en la tabla principal
                    $(document).on('input', '.table-input', function() {
                        $('#tbodyRendiciones tr').each(function() {
                            let sum = 0;
                            $(this).find('.table-input').each(function() {
                                sum += parseFloat($(this).val()) || 0;
                            });
                            $(this).find('.total-column').text(sum.toFixed(2));
                        });
                    });

                    // Crear tablas secundarias para el conteo de billetes
                    let subTablesHtml = '';
                    data.forEach(function(detalle) {
                        let sumas = {
                            totalFila: 0,
                            totalColumnas: [20000, 10000, 5000, 2000, 1000, 500, 200, 100, 50, 20, 10].map(denominacion => {
                                return (detalle[`billetes_${denominacion}`] || 0) * denominacion;
                            })
                        };
                        sumas.totalFila = sumas.totalColumnas.reduce((a, b) => a + b, 0);

                        let billetesHtml = `
                            <div class="card">
                                <div class="sub-table my-3">
                                    <div class="dataTables_wrapper no-footer" style="width: 100% !important;">
                                        <table class="datatables-ajax table table-bordered table-hover table-sm table-striped">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th colspan="12" class="text-center">
                                                        <h6>${detalle.movil} ${detalle.nombre_preventista} ---- ${detalle.nombre_chofer}</h6>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center">Denominación</th>
                                                    <th class="text-center">20,000</th>
                                                    <th class="text-center">10,000</th>
                                                    <th class="text-center">5,000</th>
                                                    <th class="text-center">2,000</th>
                                                    <th class="text-center">1,000</th>
                                                    <th class="text-center">500</th>
                                                    <th class="text-center">200</th>
                                                    <th class="text-center">100</th>
                                                    <th class="text-center">50</th>
                                                    <th class="text-center">20</th>
                                                    <th class="text-center">10</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="font-weight-bold">Cantidad</td>
                                                    ${[20000, 10000, 5000, 2000, 1000, 500, 200, 100, 50, 20, 10].map(denominacion => {
                                                        return `<td><input type="number" class="form-control cantidad-input" value="${detalle[`billetes_${denominacion}`] || 0}" data-denominacion="${denominacion}" style="-moz-appearance: textfield; width: 100%; padding: 2px; text-align: right;" /></td>`;
                                                    }).join('')}
                                                    <td class="font-weight-bold total-row">${sumas.totalFila.toFixed(2)}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Total</td>
                                                    ${sumas.totalColumnas.map(total => `<td class="font-weight-bold columna-total">${total.toFixed(2)}</td>`).join('')}
                                                    <td class="font-weight-bold total-general " style="background-color: #ffe5e5;">${sumas.totalFila.toFixed(2)}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>`;
                        subTablesHtml += billetesHtml;
                    });

                    // Insertar tablas secundarias
                    $('#tablasSecundarias').html(subTablesHtml);

                    // Crear tabla TOTAL PREVENTA
                    let totalPreventaHtml = `
                        <table class="table table-bordered table-sm mt-3">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Denominación</th>
                                    <th>20,000</th>
                                    <th>10,000</th>
                                    <th>5,000</th>
                                    <th>2,000</th>
                                    <th>1,000</th>
                                    <th>500</th>
                                    <th>200</th>
                                    <th>100</th>
                                    <th>50</th>
                                    <th>20</th>
                                    <th>10</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold">Cantidad</td>
                                    ${[20000, 10000, 5000, 2000, 1000, 500, 200, 100, 50, 20, 10].map(denominacion => {
                                        return `<td class="total-cantidad" data-denominacion="${denominacion}">0</td>`;
                                    }).join('')}
                                    <td class="font-weight-bold" id="total-global">0.00</td>
                                </tr>
                            </tbody>
                        </table>`;
                    $('#totalPreventa').html(totalPreventaHtml);

                    // Actualizar dinámicamente las columnas y totales en las tablas secundarias
                    $(document).on('input', '.cantidad-input', function() {
                        const table = $(this).closest('table');
                        let totalFila = 0;

                        // Actualizar totales por columna
                        table.find('thead tr th:not(:first-child):not(:last-child)').each(function(index) {
                            let columnSum = 0;
                            table.find('tbody tr td:nth-child(' + (index + 2) + ') input').each(function() {
                                const cantidad = parseFloat($(this).val()) || 0;
                                const denominacion = parseFloat($(this).data('denominacion')) || 0;
                                columnSum += cantidad * denominacion;
                            });
                            table.find('tbody tr:last-child td:nth-child(' + (index + 2) + ')').text(columnSum.toFixed(2));
                            totalFila += columnSum;
                        });

                        // Actualizar el total general
                        table.find('.total-general').text(totalFila.toFixed(2));

                        // Actualizar TOTAL PREVENTA
                        actualizarTotalPreventa();
                    });

                    // Función para actualizar la tabla TOTAL PREVENTA
                    function actualizarTotalPreventa() {
                        const denominaciones = [20000, 10000, 5000, 2000, 1000, 500, 200, 100, 50, 20, 10];
                        let totalGlobal = 0;

                        denominaciones.forEach(denominacion => {
                            let totalCantidad = 0;

                            $('.cantidad-input[data-denominacion="' + denominacion + '"]').each(function() {
                                totalCantidad += parseFloat($(this).val()) || 0;
                            });

                            $(`.total-cantidad[data-denominacion="${denominacion}"]`).text(totalCantidad);
                            totalGlobal += totalCantidad * denominacion;
                        });

                        $('#total-global').text(totalGlobal.toFixed(2));
                    }

                    // Inicializar el TOTAL PREVENTA
                    actualizarTotalPreventa();
                } else {
                    $('#theadRendiciones').html('<tr><th>No se encontraron detalles</th></tr>');
                    $('#tbodyRendiciones').html('');
                    $('#tablasSecundarias').html('');
                    $('#totalPreventa').html('');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Error al buscar detalles de las rendiciones.');
            },
        });
    }
});
</script>






</body>

</html>