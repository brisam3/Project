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

    <style>


            #tablaRendiciones {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
              
                background-color: #e1e5f6; /* color5 como fondo base */
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }

            /* Estilos para el encabezado */
            #tablaRendiciones thead th {
                background-color: #4e5cac; /* color3 para el encabezado, más claro que antes */
                color: #f9f9f7 !important; /* Cambiado de #e1e5f6 a blanco puro para mayor contraste */
                font-weight: 600;
                text-transform: uppercase;
                padding: 15px;
                border-bottom: 2px solid #000000; /* color2 para el borde inferior */
            }

            /* Estilos para las celdas del cuerpo */
            #tablaRendiciones tbody td {
                padding: 12px;
                border-bottom: 1px solid #8f9afa; /* color4 para los bordes de las celdas */
                transition: background-color 0.3s ease;
            }

            /* Efecto hover para las filas */
            #tablaRendiciones tbody tr:hover {
                background-color: #8f9afa; /* color4 para el hover */
            }

            /* Estilos para filas alternas */
            #tablaRendiciones tbody tr:nth-child(even) {
                background-color: #edf0fc; /* color5 para filas pares */
                color: #1d274d; /* Añadido color de texto para filas pares */
            }

            #tablaRendiciones tbody tr:nth-child(odd) {
                background-color: #f5f7ff; /* Un tono más claro que color5 para filas impares */
                color: #1d274d; /* Añadido color de texto para filas impares */
            }

            /* Estilos modernos para los inputs */
            #tablaRendiciones input[type="number"] {
                width: 100%;
                padding: 8px 0;
                border: none;
                border-bottom: 2px solid #4e5cac; /* color3 para el borde inferior */
                border-radius: 0;
                text-align: right;
                font-size: 14px;
                transition: all 0.3s ease;
                background-color: transparent;
                color: #140f07; /* color1 para el texto de los inputs */
            }

            #tablaRendiciones input[type="number"]:focus {
                outline: none;
                border-bottom-color: #1d274d; /* color2 para el borde inferior al enfocar */
                box-shadow: 0 1px 0 0 #1d274d;
            }

            #tablaRendiciones input[type="number"]:hover {
                background-color: #e1e5f6; /* color5 para el fondo al hacer hover */
            }

            /* Estilos para las celdas de totales */
            #tablaRendiciones .font-weight-bold {
                font-weight: 600;
                color: #1d274d; /* color2 para texto en negrita */
            }

            #tablaRendiciones .total-general {
                background-color: #1d274d; /* color2 para el fondo del total general */
                color: #e1e5f6; /* color5 para el texto del total general */
                font-weight: 700;
            }

            /* Estilos para el pie de tabla */
            #tablaRendiciones tfoot td {
                background-color: #4e5cac; /* color3 para el fondo del pie */
                color: #e1e5f6; /* color5 para el texto del pie */
                font-weight: 600;
                border-top: 2px solid #1d274d; /* color2 para el borde superior */
                padding: 15px;
            }

            /* Estilos para columnas específicas */
            #tablaRendiciones td:first-child{
                background-color: #8f9afa; /* color4 para la primera columna, más claro */
                color: #1d274d; /* Cambiado de #140f07 a #1d274d para mayor contraste con el fondo #8f9afa */
                font-weight: 600;
            }

            /* Estilos para el título de la tabla */
            #tablaRendiciones thead th h6 {
                margin: 0;
                font-size: 1.2em;
                color: #e1e5f6; /* color5 para el texto del título */
                font-weight: 600;
            }

            /* Estilos para tipos de datos específicos */
            #tablaRendiciones .cantidad-input {
                font-family: 'Courier New', Courier, monospace; /* Fuente monoespaciada para números */
                font-size: 13px;
            }

            #tablaRendiciones .columna-total,
            #tablaRendiciones .total-row {
                font-weight: 700;
                color: #1d274d; /* color2 para totales */
            }

            #tablaRendiciones .diferencia-column {
                font-style: italic;
                color: #4e5cac; /* color3 para la diferencia */
            }

            /* Estilo para resaltar la fila de total neto */
            #tablaRendiciones tr.total-neto {
                background-color: #1d274d; /* color2 para el fondo */
                color: #ffffff; /* Cambiado de #e1e5f6 a blanco puro para mayor contraste */
                font-weight: 700;
                font-size: 1.1em;
            }

            #tablaRendiciones tr.total-neto td {
                padding: 15px 12px;
                border-top: 2px solid #4e5cac; /* color3 para el borde superior */
                border-bottom: 2px solid #4e5cac; /* color3 para el borde inferior */
            }

            /* Responsive design */
            @media (max-width: 768px) {
                #tablaRendiciones {
                    font-size: 14px;
                }

                #tablaRendiciones thead th,
                #tablaRendiciones tbody td,
                #tablaRendiciones tfoot td {
                    padding: 10px;
                }

                #tablaRendiciones input[type="number"] {
                    font-size: 12px;
                    padding: 6px 0;
                }
            }


    </style>


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
                                                                class="datatables-ajax table table-bordered table-hover table-sm table">
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

                                                    <div class="card p-3 my-2">
                                                        <div id="totalPreventa"></div>
                                                    </div>

                                                    <div class="card p-3 my-2">
                                                        <div id="resumenPreventaContainer"></div>
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
                                alert(
                                    'Ocurrió un error al obtener los detalles de las rendiciones.'
                                    );
                                return;
                            }

                            const data = response.data;

                            if (data && data.length > 0) {
                                // Crear encabezados de la tabla principal
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
                                const atributos = [{
                                        nombre: 'Total Ventas',
                                        campo: 'total_ventas',
                                        esEditable: false
                                    },
                                    {
                                        nombre: 'MEC Faltante',
                                        campo: 'total_mec_faltante',
                                        esEditable: true
                                    },
                                    {
                                        nombre: 'Rechazos',
                                        campo: 'total_rechazos',
                                        esEditable: true
                                    },
                                    {
                                        nombre: 'Mercado Pago',
                                        campo: 'total_mercadopago',
                                        esEditable: true
                                    },
                                    {
                                        nombre: 'Transferencias',
                                        campo: 'total_transferencia',
                                        esEditable: true
                                    },
                                    {
                                        nombre: 'Fiados',
                                        campo: 'total_fiados',
                                        esEditable: true
                                    },
                                    {
                                        nombre: 'Gastos',
                                        campo: 'total_gastos',
                                        esEditable: true
                                    },
                                    {
                                        nombre: 'Pago Secretario',
                                        campo: 'pago_secretario',
                                        esEditable: true
                                    },
                                    {
                                        nombre: 'Cheques',
                                        campo: 'total_cheques',
                                        esEditable: true
                                    },
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
                                        ? `<td><input type="number" class="form-control table-input" value="${valor}" style="-moz-appearance: textfield; width: 100%; padding: 2px; text-align: right;" data-campo="${atributo.campo}" /></td>`
                                        : `<td>${valor}</td>`;
                                }).join('')}
                                <td class="total-column">${total.toFixed(2)}</td>
                            </tr>`;
                                });

                                // Agregar fila Total Neto
                                let totalNetoRow = `<tr id="filaTotalNeto">
                            <td class="font-weight-bold">Total Neto</td>
                            ${data.map(() => `<td class="neto-column">0.00</td>`).join('')}
                            <td class="total-neto-global font-weight-bold">0.00</td>
                        </tr>`;

                                $('#tbodyRendiciones').html(html + totalNetoRow);

                                // Actualizar dinámicamente las columnas y el total neto
                                $(document).on('input', '.table-input', function() {
                                    const $input = $(this);
                                    const campo = $input.data('campo');
                                    const $row = $input.closest('tr');
                                    const $totalColumn = $row.find('.total-column');

                                    // Recalcular el total de la fila
                                    let rowSum = 0;
                                    $row.find('.table-input').each(function() {
                                        rowSum += parseFloat($(this).val()) || 0;
                                    });
                                    $totalColumn.text(rowSum.toFixed(2));

                                    // Recalcular el total neto
                                    actualizarTotalNeto();
                                });

                                function actualizarTotalNeto() {
                                    const camposARestar = [
                                        'total_mec_faltante',
                                        'total_rechazos',
                                        'total_mercadopago',
                                        'total_transferencia',
                                        'total_fiados',
                                        'total_gastos',
                                        'pago_secretario',
                                        'total_cheques',
                                    ];

                                    let totalGlobalNeto = 0;

                                    $('#theadRendiciones th:not(:first-child):not(:last-child)')
                                        .each(function(index) {
                                            let totalVentas = parseFloat($(
                                                `#tbodyRendiciones tr:nth-child(1) td:nth-child(${index + 2})`
                                            ).text()) || 0;
                                            let totalARestar = 0;

                                            camposARestar.forEach((campo, i) => {
                                                totalARestar += parseFloat($(
                                                    `#tbodyRendiciones tr:nth-child(${i + 2}) td:nth-child(${index + 2}) input`
                                                ).val()) || 0;
                                            });

                                            const totalNeto = totalVentas - totalARestar;
                                            $(`#filaTotalNeto td:nth-child(${index + 2})`).text(
                                                totalNeto.toFixed(2));
                                            totalGlobalNeto += totalNeto;
                                        });

                                    // Actualizar la columna Total Neto Global
                                    $('.total-neto-global').text(totalGlobalNeto.toFixed(2));
                                }

                                // Inicializar el Total Neto
                                actualizarTotalNeto();


                                // Crear tablas secundarias para el conteo de billetes
                                let subTablesHtml = '';
                                // Crear tablas secundarias incluyendo la fila de diferencia desde el inicio
                                data.forEach(function(detalle, index) {
                                    let sumas = {
                                        totalFila: 0,
                                        totalColumnas: [20000, 10000, 5000, 2000, 1000,
                                            500, 200, 100, 50, 20, 10
                                        ].map(denominacion => {
                                            return (detalle[
                                                `billetes_${denominacion}`
                                            ] || 0) * denominacion;
                                        })
                                    };
                                    sumas.totalFila = sumas.totalColumnas.reduce((a, b) =>
                                        a + b, 0);

                                    let billetesHtml = `
                                        <div class="card">
                                            <div class="sub-table my-2">
                                                <div class="dataTables_wrapper no-footer" style="width: 100% !important;">
                                                    <table class="datatables-ajax table table-bordered table-hover table-sm table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="13" class="text-center">
                                                                    <h6  style="margin: 10px 0;">${detalle.movil} ${detalle.nombre_preventista} ---- ${detalle.nombre_chofer}</h6>
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
                                                                <th class="text-center">Total</th>
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
                                                                <td class="font-weight-bold total-general" style="background-color: #ffe5e5;">${sumas.totalFila.toFixed(2)}</td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td class="font-weight-bold">Diferencia</td>
                                                                <td colspan="12" class="font-weight-bold diferencia-column text-center">0.00</td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>`;
                                    subTablesHtml += billetesHtml;
                                });

                                $('#tablasSecundarias').html(subTablesHtml);

                                // Actualizar dinámicamente la fila de diferencia en las tablas secundarias
                                function actualizarDiferenciaDinamica() {
                                    $('.sub-table').each(function(index) {
                                        const table = $(this).find('table');
                                        const totalEfectivo = parseFloat(table.find(
                                            '.total-general').text()) || 0;
                                        const totalNeto = parseFloat($(
                                            `#filaTotalNeto td:nth-child(${index + 2})`
                                        ).text()) || 0;
                                        const diferencia = totalEfectivo - totalNeto;

                                        // Actualizar la fila de diferencia
                                        table.find('.diferencia-column').text(diferencia
                                            .toFixed(2));
                                    });
                                }

                                // Inicializar las diferencias desde el inicio
                                actualizarDiferenciaDinamica();

                                // Recalcular diferencias dinámicamente al modificar valores
                                $(document).on('input', '.cantidad-input, .table-input',
                                    function() {
                                        actualizarDiferenciaDinamica();
                                    });

                                // Crear tabla TOTAL PREVENTA
                                let totalPreventaHtml = `
                                                <table class="datatables-ajax table table-bordered table-hover table-sm table table-striped">
                                                    <thead>
                                                    <th colspan="13" class="text-center">
                                                        <h6  style="margin: 10px 0;">Total Preventa</h6>
                                                    </th>
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
                                                </table>
                                               
                                      `;
                                $('#totalPreventa').html(totalPreventaHtml);


                                // Actualizar dinámicamente las columnas y totales en las tablas secundarias
                                $(document).on('input', '.cantidad-input', function() {
                                    const table = $(this).closest('table');
                                    let totalFila = 0;

                                    // Actualizar totales por columna
                                    table.find(
                                        'thead tr th:not(:first-child):not(:last-child)'
                                    ).each(function(index) {
                                        let columnSum = 0;
                                        table.find('tbody tr td:nth-child(' + (
                                            index + 2) + ') input').each(
                                            function() {
                                                const cantidad = parseFloat($(
                                                    this).val()) || 0;
                                                const denominacion = parseFloat(
                                                        $(this).data(
                                                            'denominacion')) ||
                                                    0;
                                                columnSum += cantidad *
                                                    denominacion;
                                            });
                                        table.find(
                                            'tbody tr:last-child td:nth-child(' +
                                            (index + 2) + ')').text(columnSum
                                            .toFixed(2));
                                        totalFila += columnSum;
                                    });

                                    // Actualizar el total general
                                    table.find('.total-general').text(totalFila.toFixed(2));

                                    // Actualizar TOTAL PREVENTA
                                    actualizarTotalPreventa();
                                });

                                // Función para actualizar la tabla TOTAL PREVENTA
                                function actualizarTotalPreventa() {
                                    const denominaciones = [20000, 10000, 5000, 2000, 1000, 500,
                                        200, 100, 50, 20, 10
                                    ];
                                    let totalGlobal = 0;

                                    denominaciones.forEach(denominacion => {
                                        let totalCantidad = 0;

                                        $('.cantidad-input[data-denominacion="' +
                                            denominacion + '"]').each(function() {
                                            totalCantidad += parseFloat($(this)
                                                .val()) || 0;
                                        });

                                        $(`.total-cantidad[data-denominacion="${denominacion}"]`)
                                            .text(totalCantidad);
                                        totalGlobal += totalCantidad * denominacion;
                                    });

                                    $('#total-global').text(totalGlobal.toFixed(2));
                                }

                                // Inicializar el TOTAL PREVENTA
                                actualizarTotalPreventa();

                                // Crear la nueva tabla dinámica al final
                                function crearTablaResumen() {
                                    let resumenHtml = `
                                     <div class="card">
                                            <div class="sub-table my-2">
                                                <div class="dataTables_wrapper no-footer" style="width: 100% !important;">
                                                    <table class="datatables-ajax table table-bordered table-hover table-sm table table-striped" id="tablaResumenPreventa">
                                                            <thead>
                                                            <th colspan="13" class="text-center">
                                                                <h6  style="margin: 10px 0;">Total General</h6>
                                                            </th>
                                                                <tr>
                                                                    <th>Descripción</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="font-weight-bold">TOTAL EFECTIVO</td>
                                                                    <td id="totalEfectivoResumen">0.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="font-weight-bold">TOTAL MP</td>
                                                                    <td id="totalMpResumen">0.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="font-weight-bold">TOTAL TRANSFERENCIAS</td>
                                                                    <td id="totalTransferenciasResumen">0.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="font-weight-bold">TOTAL GENERAL PREVENTA</td>
                                                                    <td id="totalGeneralResumen" class="font-weight-bold text-primary">0.00</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>`;

                                    $('#resumenPreventaContainer').html(resumenHtml);


                                    // Inicializar los cálculos
                                    actualizarTablaResumen();
                                }

                                // Función para actualizar los valores dinámicos de la nueva tabla
                                function actualizarTablaResumen() {
                                    // Obtener Total Efectivo desde la tabla "Total Preventa"
                                    const totalEfectivo = parseFloat($('#total-global').text()) ||
                                    0;

                                    // Obtener Total MP desde la tabla principal
                                    const totalMpIndex = $('#theadRendiciones th').index($(
                                        '#theadRendiciones th:contains("Mercado Pago")'));
                                    const totalMp = parseFloat($(
                                            `#tbodyRendiciones tr:nth-child(4) td:last-child`)
                                        .text()) || 0; // Fila de "Mercado Pago"

                                    // Obtener Total Transferencias desde la tabla principal
                                    const totalTransferencias = parseFloat($(
                                            `#tbodyRendiciones tr:nth-child(5) td:last-child`)
                                        .text()) || 0; // Fila de "Transferencias"

                                    // Calcular Total General Preventa
                                    const totalGeneral = totalEfectivo + totalMp +
                                        totalTransferencias;

                                    // Actualizar los valores en la nueva tabla
                                    $('#totalEfectivoResumen').text(totalEfectivo.toFixed(2));
                                    $('#totalMpResumen').text(totalMp.toFixed(2));
                                    $('#totalTransferenciasResumen').text(totalTransferencias
                                        .toFixed(2));
                                    $('#totalGeneralResumen').text(totalGeneral.toFixed(2));
                                }

                                // Inicializar la nueva tabla y los cálculos
                                crearTablaResumen();

                                // Escuchar cambios en las tablas relacionadas para actualizar dinámicamente la nueva tabla
                                $(document).on('input', '.cantidad-input, .table-input',
                            function() {
                                    actualizarTablaResumen();
                                });

                                // Crear la tabla "LIBRE" al inicializar las tablas secundarias
                                function agregarTablaLibre() {
                                    const denominaciones = [20000, 10000, 5000, 2000, 1000, 500,
                                        200, 100, 50, 20, 10
                                    ];
                                    let libreHtml = `
                                            <div class="card">
                                                <div class="sub-table my-2">
                                                    <div class="dataTables_wrapper no-footer" style="width: 100% !important;">
                                                        <table class="datatables-ajax table table-bordered table-hover table-sm table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="13" class="text-center">
                                                                        <h6 style="margin: 10px 0;">LIBRE</h6>
                                                                    </th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-center">Denominación</th>
                                                                    ${denominaciones.map(denominacion => `<th class="text-center">${denominacion}</th>`).join('')}
                                                                    <th class="text-center">Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="font-weight-bold">Cantidad</td>
                                                                    ${denominaciones.map(denominacion => `
                                                                        <td>
                                                                            <input type="number" class="form-control cantidad-libre" value="0" 
                                                                                data-denominacion="${denominacion}" 
                                                                                style="-moz-appearance: textfield; width: 100%; padding: 2px; text-align: right;">
                                                                        </td>`).join('')}
                                                                    <td class="font-weight-bold total-libre" style="background-color: #ffe5e5;">0.00</td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td class="font-weight-bold">Diferencia</td>
                                                                    <td colspan="12" class="font-weight-bold diferencia-libre text-center">0.00</td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        `;

                                    // Agregar la tabla al contenedor
                                    $('#tablasSecundarias').append(libreHtml);

                                    // Actualizar los cálculos al cambiar valores en la tabla LIBRE
                                    $(document).on('input', '.cantidad-libre', function() {
                                        let totalFila = 0;

                                        $('.cantidad-libre').each(function() {
                                            const denominacion = parseFloat($(this)
                                                .data('denominacion'));
                                            const cantidad = parseFloat($(this)
                                            .val()) || 0;
                                            totalFila += denominacion * cantidad;
                                        });

                                        // Actualizar el total de la tabla "LIBRE"
                                        $('.total-libre').text(totalFila.toFixed(2));

                                        // Actualizar totales y diferencias dinámicas
                                        actualizarTotalNeto();
                                        actualizarTotalPreventa();
                                        actualizarTablaResumen();
                                    });
                                }

                                // Agregar la columna "LIBRE" en la tabla principal
                                function agregarColumnaLibre() {
                                    $('#theadRendiciones tr:first-child').append('<th>LIBRE</th>');
                                    $('#theadRendiciones tr:nth-child(2)').append('<th></th>');

                                    $('#tbodyRendiciones tr').each(function() {
                                        const $row = $(this);
                                        const esEditable = $row.find('td input').length >
                                        0; // Verificar si la fila es editable

                                        $row.append(
                                            esEditable ?
                                            `<td><input type="number" class="form-control table-input" value="0" style="-moz-appearance: textfield; width: 100%; padding: 2px; text-align: right;" data-campo="libre" /></td>` :
                                            `<td>0.00</td>`
                                        );
                                    });

                                    // Agregar columna "LIBRE" en la fila "Total Neto"
                                    $('#filaTotalNeto').append('<td class="neto-column">0.00</td>');
                                }

                                function actualizarTotalPreventa() {
                                    const denominaciones = [20000, 10000, 5000, 2000, 1000, 500,
                                        200, 100, 50, 20, 10
                                    ];
                                    let totalGlobal = 0;

                                    denominaciones.forEach(denominacion => {
                                        let totalCantidad = 0;

                                        // Incluir todas las cantidades, incluyendo la tabla LIBRE
                                        $(`.cantidad-input[data-denominacion="${denominacion}"], .cantidad-libre[data-denominacion="${denominacion}"]`)
                                            .each(function() {
                                                totalCantidad += parseFloat($(this)
                                                .val()) || 0;
                                            });

                                        $(`.total-cantidad[data-denominacion="${denominacion}"]`)
                                            .text(totalCantidad);
                                        totalGlobal += totalCantidad * denominacion;
                                    });

                                    $('#total-global').text(totalGlobal.toFixed(2));
                                }

                                // Inicializar la tabla "LIBRE" y la columna en la tabla principal
                                $(document).ready(function() {
                                    agregarTablaLibre();

                                    actualizarTotalPreventa();

                                });



                            } else {
                                $('#theadRendiciones').html(
                                    '<tr><th>No se encontraron detalles</th></tr>'
                                );
                                $('#tablasSecundarias').closest('.card').hide();
                                $('#totalPreventa').closest('.card').hide(); 
                                $('#resumenPreventaContainer').closest('.card').hide(); 


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

            <script>            // Realiza una solicitud AJAX con jQuery
                    $.ajax({
                        type: 'POST',
                        url: '../../backend/controller/administracion/Rendiciones.php',  // Asegúrate de que la URL sea correcta
                        data: { action: 'obtenerCierreCajaHoy' },  // El nombre de la acción que estamos enviando
                        success: function(response) {
                            // Aquí manejamos la respuesta del servidor
                            if (response.error) {
                                console.log('Error: ' + response.mensaje);
                            } else {
                                // Si no hay error, mostramos los datos de la respuesta en la consola
                                console.log('Datos de Cierre de Caja:', response.data);
                            }
                        },
                        error: function(xhr, status, error) {
                            // Si ocurre algún error en la solicitud AJAX, lo mostramos en la consola
                            console.log('Error en la solicitud AJAX:', error);
                        }
                    });

            </script>








</body>

</html>