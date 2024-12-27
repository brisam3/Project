<?php


// Incluir el controlador de acceso
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../backend/controller/access/AccessController.php';

$accessController = new AccessController();

// Verificar si el acceso está permitido
if (!$accessController->checkAccess('/pages/administracion/RendicionGeneral.php')) {
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

    <style>
        
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    <style>
    .btn-banco-guardar {
        display: block;
        width: 100%;
        max-width: 300px;
        margin: 20px auto;
        padding: 12px 20px;
        font-size: 16px;
        font-weight: 600;
        text-align: center;
        color: #ffffff;
        background-color: #3498db;
        border: 2px solid #2980b9;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .btn-banco-guardar:hover {
        background-color: #2980b9;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
    }

    .btn-banco-guardar:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.5);
    }

    .btn-banco-guardar:active {
        transform: translateY(1px);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .btn-banco-guardar::before {
        content: "⚠️ ";
        margin-right: 8px;
    }

    .btn-banco-guardar::after {
        content: "Acción importante";
        position: absolute;
        bottom: -20px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 12px;
        color: #7f8c8d;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .btn-banco-guardar:hover::after {
        bottom: -18px;
        opacity: 1;
    }
    </style>
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
                                <div>
                                    <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                                        <li class="nav-item col-12 col-md-6 col-lg-3 my-1" role="presentation">
                                            <button class="nav-link active" id="wolchuk-tab" data-bs-toggle="tab"
                                                data-bs-target="#wolchuk" type="button" role="tab"
                                                aria-controls="wolchuk" aria-selected="true">WOLCHUK
                                            </button>
                                        </li>
                                        <li class="nav-item col-12 col-md-6 col-lg-3 my-1" role="presentation">
                                            <button class="nav-link" id="almacen-tab" data-bs-toggle="tab"
                                                data-bs-target="#almacen" type="button" role="tab"
                                                aria-controls="almacen" aria-selected="true">ALMACEN
                                            </button>
                                        </li>
                                        <li class="nav-item col-12 col-md-6 col-lg-3 my-1" role="presentation">
                                            <button class="nav-link" id="banco-tab" data-bs-toggle="tab"
                                                data-bs-target="#banco" type="button" role="tab" aria-controls="banco"
                                                aria-selected="false">BANCO
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-content" id="myTabContent" style="margin: 0; padding: 0;">
                                    <!-- Resumen Tab -->
                                    <div class="tab-pane fade show active" id="wolchuk" role="tabpanel"
                                        style="margin: 0; padding: 0;">

                                        <div class="row" style="margin: 0; padding: 0;">
                                            <div class="container-xxl flex-grow-1 container-p-y"
                                                style="margin: 0; padding: 0;">
                                                <div class="table-responsive-xl mb-6 mb-lg-0"
                                                    style="margin: 0; padding: 0;">
                                                    <div class="dataTables_wrapper no-footer"
                                                        style="width: 100% !important;">
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
                                                                <div id="tablasSecundarias"
                                                                    class='table-container my-2'>
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
                                                <button onclick="ejecutarRecorridoPreventa()"
                                                    class="btn-banco-guardar">Guardar</button>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="almacen" role="tabpanel">
                                        <div class="row">
                                            <div class="container-xxl flex-grow-1 container-p-y">
                                                <div class="table-responsive-xl mb-6 mb-lg-0">
                                                    <div class="dataTables_wrapper no-footer"
                                                        style="width: 100% !important;">
                                                        <div class="col-md-12 my-2">
                                                            <!-- Tabla de Detalle de Rendiciones -->
                                                            <div class="card p-3 my-2">
                                                                <div class="table-container my-2">
                                                                    <!-- Añadí margen inferior para separación -->
                                                                    <table id="tablaRendicionesLocales"
                                                                        class="datatables-ajax table table-bordered table-hover table-sm table table-striped">
                                                                        <!-- Añadí margen inferior a la tabla principal -->
                                                                        <thead id="theadRendicionesLocales">
                                                                            <!-- Encabezados dinámicos -->
                                                                        </thead>
                                                                        <tbody id="tbodyRendicionesLocales">
                                                                            <!-- Detalles dinámicos -->
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <!-- Tablas secundarias -->
                                                            <div class="card p-3 my-2">
                                                                <div id="tablasSecundariasLocales"
                                                                    class='table-container my-2'>
                                                                    <!-- Añadí margen superior para separación de tablas secundarias -->
                                                                    <!-- Tablas por cada detalle dinámico -->
                                                                </div>
                                                            </div>

                                                            <div class="card p-3 my-2">
                                                                <div id="totalLocales"></div>
                                                            </div>



                                                            <div class="card p-3 my-2">
                                                                <div id="resumenGeneralContainer"></div>
                                                            </div>



                                                        </div>
                                                    </div>
                                                </div>
                                                <button onclick="enviarDatosLocalesAlBackend()"
                                                    class="btn-banco-guardar">Guardar</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="banco" role="tabpanel">
                                        <div class="row">
                                            <div class="container-xxl flex-grow-1 container-p-y">
                                                <div class="table-responsive-xl mb-6 mb-lg-0">
                                                    <div class="dataTables_wrapper no-footer"
                                                        style="width: 100% !important;">
                                                        <div class="col-md-12 my-2">

                                                            <div class="table-container my-2">
                                                                <div id="totalBanco">
                                                                    <!-- Aquí se generará la tabla dinámica -->
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <button onclick="enviarDatosBanco()"
                                                    class="btn-banco-guardar">Guardar</button>

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
                                actualizarTablaBanco
                                    (); // Llamar después de cargar datos de Preventa
                                // Crear encabezados de la tabla principal
                                let headers = `
                           <tr>
                                    <th>Movil</th>
                                    ${data.map(detalle => `<th data-id="${detalle.id}">${detalle.movil}</th>`).join('')}
                                    <th>Totales</th>
                                </tr>
                            <tr>
                                <th>Preventista<br>
                                Chofer</th>
                                ${data.map(detalle => `<th>${detalle.nombre_preventista} <br> ${detalle.nombre_chofer}</th>`).join('')}
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
                                        rowSum += parseFloat($(this).val()) ||
                                            0;
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
                                            $(`#filaTotalNeto td:nth-child(${index + 2})`)
                                                .text(
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
                                        totalColumnas: [20000, 10000, 2000,
                                            1000,
                                            500, 200, 100, 50, 20, 10
                                        ].map(denominacion => {
                                            return (detalle[
                                                `billetes_${denominacion}`
                                            ] || 0) * denominacion;
                                        })
                                    };
                                    sumas.totalFila = sumas.totalColumnas.reduce((a,
                                            b) =>
                                        a + b, 0);

                                    let billetesHtml = `
                                       <div   id="billetitos" class="card mb-3 billetes-card" data-index="${index}">
                                            <div class="card-header billetes-header" style="cursor: pointer;">
                                               <h5 class="mb-0 d-flex align-items-center justify-content-between" style="color: #000;">
                                                <span class=" font-weight-bold">
                                                  <i class="fas fa-money-bill-wave mr-2"></i> BILLETES:
                                                    ${detalle.movil}
                                                </span>
                                                <span class="text-secondary">
                                                    <span class="font-weight-bold">Preventista: ${detalle.nombre_preventista}</span>
                                                    <span class="mx-2">|</span>
                                                    <span class="font-italic">Chofer: ${detalle.nombre_chofer}</span>
                                                </span>
                                            </h5>
                                            </div>
                                              <div class="card-body billetes-body" style="display: none;"> 
                                            <div class="sub-table my-2">
                                                <div class="dataTables_wrapper no-footer" style="width: 100% !important;">
                                                    <table class="datatables-ajax table table-bordered table-hover table-sm table table-striped" id="miTabla">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Denominación</th>
                                                                <th class="text-center">20,000</th>
                                                                <th class="text-center">10,000</th>
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
                                                                ${[20000, 10000, 2000, 1000, 500, 200, 100, 50, 20, 10].map(denominacion => {
                                                                    return `<td><input type="number" class="form-control cantidad-input" value="${detalle[`billetes_${denominacion}`] || 0}" data-denominacion="${denominacion}" style="-moz-appearance: textfield; width: 100%; padding: 2px; text-align: right;" /></td>`;
                                                                }).join('')}
                                                                <td class="font-weight-bold total-row">${sumas.totalFila.toFixed(2)}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-bold">Total</td>
                                                                ${sumas.totalColumnas.map(total => `<td class="font-weight-bold columna-total">${total.toFixed(2)}</td>`).join('')}
                                                                <td class="font-weight-bold total-general">${sumas.totalFila.toFixed(2)}</td>
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
                                              </div>
                                            </div>
                                            </div>`;
                                    subTablesHtml += billetesHtml;
                                });

                                $('#tablasSecundarias').html(subTablesHtml);

                                // Funcionalidad para expandir/contraer las tablas
                                $('.billetes-header').on('click', function() {
                                    const $body = $(this).next('.billetes-body');
                                    $body.slideToggle(300);
                                });

                                // Mostrar la primera tabla por defecto
                                $('.billetes-card:first-child .billetes-body').show();

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
                                                            ${[20000, 10000, 2000, 1000, 500, 200, 100, 50, 20, 10].map(denominacion => {
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
                                                const cantidad = parseFloat(
                                                        $(
                                                            this).val()) ||
                                                    0;
                                                const denominacion =
                                                    parseFloat(
                                                        $(this).data(
                                                            'denominacion')
                                                    ) ||
                                                    0;
                                                columnSum += cantidad *
                                                    denominacion;
                                            });
                                        table.find(
                                            'tbody tr:last-child td:nth-child(' +
                                            (index + 2) + ')').text(
                                            columnSum
                                            .toFixed(2));
                                        totalFila += columnSum;
                                    });

                                    // Actualizar el total general
                                    table.find('.total-general').text(totalFila.toFixed(
                                        2));

                                    // Actualizar TOTAL PREVENTA
                                    actualizarTotalPreventa();
                                });

                                // Función para actualizar la tabla TOTAL PREVENTA
                                function actualizarTotalPreventa() {
                                    const denominaciones = [20000, 10000, 2000, 1000, 500,
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
                                    const totalEfectivo = parseFloat($('#total-global')
                                            .text()) ||
                                        0;

                                    // Obtener Total MP desde la tabla principal
                                    const totalMpIndex = $('#theadRendiciones th').index($(
                                        '#theadRendiciones th:contains("Mercado Pago")'
                                    ));
                                    const totalMp = parseFloat($(
                                            `#tbodyRendiciones tr:nth-child(4) td:last-child`
                                        )
                                        .text()) || 0; // Fila de "Mercado Pago"

                                    // Obtener Total Transferencias desde la tabla principal
                                    const totalTransferencias = parseFloat($(
                                            `#tbodyRendiciones tr:nth-child(5) td:last-child`
                                        )
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
                                        actualizarTablaBanco(); // Actualizar BANCO
                                    });

                                // Crear la tabla "LIBRE" al inicializar las tablas secundarias
                                function agregarTablaLibre() {
                                    const denominaciones = [20000, 10000, 2000, 1000, 500,
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
                                                                    <td class="font-weight-bold total-libre">0.00</td>
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
                                            const denominacion = parseFloat($(
                                                    this)
                                                .data('denominacion'));
                                            const cantidad = parseFloat($(this)
                                                .val()) || 0;
                                            totalFila += denominacion *
                                                cantidad;
                                        });

                                        // Actualizar el total de la tabla "LIBRE"
                                        $('.total-libre').text(totalFila.toFixed(2));

                                        // Actualizar totales y diferencias dinámicas
                                        actualizarTotalNeto();
                                        actualizarTotalPreventa();
                                        actualizarTablaBanco(); // Actualizar BANCO
                                        actualizarTablaResumen();
                                    });
                                }

                                // Agregar la columna "LIBRE" en la tabla principal
                                // Crear la tabla "LIBRE" al inicializar las tablas secundarias
                                function agregarTablaLibre() {
                                    const denominaciones = [20000, 10000, 2000, 1000, 500,
                                        200, 100, 50, 20, 10
                                    ];

                                    let libreHtml = `
                                        <div class="card">
                                            <div class="sub-table my-2">
                                                <div class="dataTables_wrapper no-footer" style="width: 100% !important;">
                                                    <table class="datatables-ajax table table-bordered table-hover table-sm table-striped" id="tablaLibre">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="${denominaciones.length + 2}" class="text-center">
                                                                    <h6 style="margin: 10px 0;">LIBRE</h6>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center">Motivo</th>
                                                                <th class="text-center">Denominación</th>
                                                                ${denominaciones.map(denominacion => `<th class="text-center">${denominacion}</th>`).join('')}
                                                                <th class="text-center">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            ${crearFilaLibre(denominaciones)}
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="${denominaciones.length + 1}" class="text-center">
                                                                    <button type="button" class="btn btn-primary btn-sm" id="btnAgregarFilaLibre">Agregar Fila</button>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>`;

                                    // Agregar la tabla al contenedor
                                    $('#tablasSecundarias').append(libreHtml);

                                    // Evento para agregar una nueva fila
                                    $(document).on('click', '#btnAgregarFilaLibre', function() {
                                        $('#tablaLibre tbody').append(crearFilaLibre(
                                            denominaciones));
                                    });

                                    // Actualizar los cálculos al cambiar valores en la tabla LIBRE
                                    $(document).on('input', '.cantidad-libre', function() {
                                        actualizarTotalLibre(denominaciones);
                                    });
                                }

                                // Función para crear una nueva fila en la tabla LIBRE
                                function crearFilaLibre(denominaciones) {
                                    return `
                                                <tr>
                                                    <td><input type="text" class="form-control motivo-libre" placeholder="Ingrese el motivo" style="width: 100%;"></td>
                                                    <td class="font-weight-bold">Cantidad</td>
                                                    ${denominaciones.map(denominacion => `
                                                        <td>
                                                            <input type="number" class="form-control cantidad-libre" value="0" 
                                                                data-denominacion="${denominacion}" 
                                                                style="-moz-appearance: textfield; width: 100%; padding: 2px; text-align: right;">
                                                        </td>`).join('')}
                                                    <td class="font-weight-bold total-libre">0.00</td>
                                                </tr>`;
                                }

                                // Función para actualizar el total de la tabla LIBRE
                                function actualizarTotalLibre(denominaciones) {
                                    let totalGeneral = 0;

                                    $('#tablaLibre tbody tr').each(function() {
                                        let totalFila = 0;

                                        $(this).find('.cantidad-libre').each(function() {
                                            const denominacion = parseFloat($(this)
                                                .data('denominacion'));
                                            const cantidad = parseFloat($(this)
                                                .val()) || 0;
                                            totalFila += denominacion * cantidad;
                                        });

                                        $(this).find('.total-libre').text(totalFila.toFixed(
                                            2));
                                        totalGeneral += totalFila;
                                    });

                                    // Aquí podrías actualizar un total general si fuera necesario
                                    // Por ejemplo: $(".total-general-libre").text(totalGeneral.toFixed(2));

                                    actualizarTotalNeto();
                                    actualizarTotalPreventa();
                                    actualizarTablaBanco(); // Actualizar BANCO
                                    actualizarTablaResumen();
                                }

                                function actualizarTotalPreventa() {
                                    const denominaciones = [20000, 10000, 2000, 1000, 500,
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
                                $('#theadRendicionesLocales').html(
                                    '<tr><th>No se encontraron detalles</th></tr>'
                                );
                                $('#tbodyRendicionesLocales').html('');

                                // Ocultar los divs adicionales
                                $('#tablasSecundariasLocales').closest('.card').hide();
                                $('#totalLocales').closest('.card').hide();
                                $('#resumenLocalesContainer').closest('.card').hide();
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



            <!-- LOCALES-->

            <script>
            $(document).ready(function() {
                buscarDetalleRendicionesLocales();

                function buscarDetalleRendicionesLocales() {
                    $.ajax({
                        url: '../../backend/controller/administracion/Rendiciones.php',
                        type: 'POST',
                        data: {
                            action: 'obtenerCierreCajaHoy',
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
                                actualizarTablaBanco(); // Llamar después de cargar datos de Locales
                                // Crear encabezados de la tabla principal
                                let headers = `
                           
                            <tr>
                                <th>Local</th>
                                ${data.map(detalle => `<th  data-id="${detalle.idcierreCaja}">${detalle.nombre_local}</th>`).join('')}
                                <th>Totales</th>
                            </tr>`;
                                $('#theadRendicionesLocales').html(headers);

                                // Crear filas para cada atributo
                                const atributos = [{
                                        nombre: 'Payway',
                                        campo: 'payway',
                                        esEditable: true
                                    },
                                    {
                                        nombre: 'Mercado Pago',
                                        campo: 'mercado_pago',
                                        esEditable: true
                                    },
                                    {
                                        nombre: 'Gastos',
                                        campo: 'gastos',
                                        esEditable: true
                                    },
                                    {
                                        nombre: 'Cuenta Corriente',
                                        campo: 'cuenta_corriente',
                                        esEditable: true
                                    },
                                    {
                                        nombre: 'Cambios',
                                        campo: 'cambios',
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
                                        ? `<td><input type="number" class="form-control table-input-locales" value="${valor}" style="-moz-appearance: textfield; width: 100%; padding: 2px; text-align: right;" data-campo="${atributo.campo}" /></td>`
                                        : `<td>${valor}</td>`;
                                }).join('')}
                                <td class="total-column-locales">${total.toFixed(2)}</td>
                            </tr>`;
                                });

                                $('#tbodyRendicionesLocales').html(html);

                                // Actualizar dinámicamente las columnas y el total neto
                                $(document).on('input', '.table-input-locales', function() {
                                    const $input = $(this);
                                    const campo = $input.data('campo');
                                    const $row = $input.closest('tr');
                                    const $totalColumn = $row.find('.total-column-locales');

                                    // Recalcular el total de la fila
                                    let rowSum = 0;
                                    $row.find('.table-input-locales').each(function() {
                                        rowSum += parseFloat($(this).val()) || 0;
                                    });
                                    $totalColumn.text(rowSum.toFixed(2));
                                });



                                // Crear tablas secundarias para el conteo de billetes
                                let subTablesHtml = '';
                                // Crear tablas secundarias incluyendo la fila de diferencia desde el inicio
                                data.forEach(function(detalle, index) {
                                    let sumas = {
                                        totalFila: 0,
                                        totalColumnas: [20000, 10000, 2000, 1000,
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
                        <div class="card mb-3 billetes-card-locales" data-index="${index}">
                            <div class="card-header billetes-header-locales" style="cursor: pointer;">
                                <h5 class="mb-0 d-flex align-items-center justify-content-between" style="color: #000;">
                                    <span class="font-weight-bold">
                                        <i class="fas fa-money-bill-wave mr-2"></i> ${detalle.nombre_local}
                                    </span>
                                    <span class="text-secondary">
                                        <span class="font-weight-bold">Total: ${sumas.totalFila.toFixed(2)}</span>
                                    </span>
                                </h5>
                            </div>
                            <div class="card-body billetes-body-locales" style="display: none;">
                                <div class="sub-table my-2">
                                    <div class="dataTables_wrapper no-footer" style="width: 100% !important;">
                                        <table class="datatables-ajax table table-bordered table-hover table-sm table table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Denominación</th>
                                                    ${[20000, 10000, 2000, 1000, 500, 200, 100, 50, 20, 10]
                                                        .map(denominacion => `<th class="text-center">${denominacion}</th>`)
                                                        .join('')}
                                                    <th class="text-center">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="font-weight-bold">Cantidad</td>
                                                    ${[20000, 10000, 2000, 1000, 500, 200, 100, 50, 20, 10]
                                                        .map(denominacion => {
                                                            return `<td><input type="number" class="form-control cantidad-input-locales" value="${detalle[`billetes_${denominacion}`] || 0}" data-denominacion="${denominacion}" style="-moz-appearance: textfield; width: 100%; padding: 2px; text-align: right;" /></td>`;
                                                        })
                                                        .join('')}
                                                    <td class="font-weight-bold total-row">${sumas.totalFila.toFixed(2)}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Total</td>
                                                    ${sumas.totalColumnas
                                                        .map(total => `<td class="font-weight-bold columna-total">${total.toFixed(2)}</td>`)
                                                        .join('')}
                                                    <td class="font-weight-bold total-general-locales">${sumas.totalFila.toFixed(2)}</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                             <tr>
                                                            <td colspan="13" class="font-weight-bold text-center"><strong>
                                                                Tarjetas </strong> (Payway + Mercado Pago): 
                                                                <span class="tarjetas-column-locales text-success">0.00</span>
                                                            </td>
                                                        </tr>
                                                        <!-- Fila Gastos (Dinámica) -->
                                                        <tr>
                                                            <td colspan="13" class="font-weight-bold text-center">
                                                               <strong> Gastos </strong>  (Gastos + Cuenta Corriente): 
                                                                <span class="gastos-column-locales text-success">0.00</span>
                                                            </td>
                                                        </tr>
                                                <tr>
                                                   <td colspan="13" class="font-weight-bold text-center">
                                                        <div class="d-flex align-items-center justify-content-center">
                                                            <label for="sistema-input-${index}" class="mr-2">Sistema:</label>
                                                            <input id="sistema-input-${index}" type="number" class="form-control sistema-input-locales" 
                                                                value="0" style="width: 10%; text-align: right;" />
                                                        </div>
                                                    </td>


                                                </tr>
                                               
                                                <tr>
                                                    <td colspan="13" class="font-weight-bold text-center">
                                                         <strong> Diferencia: </strong> <span class="diferencia-column-locales text-danger">0.00</span>
                                                    </td>
                                                </tr>
                                                
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                                    subTablesHtml += billetesHtml;
                                });

                                $('#tablasSecundariasLocales').html(subTablesHtml);

                                // Funcionalidad para expandir/contraer las tablas
                                $('.billetes-header-locales').on('click', function() {
                                    const $body = $(this).next(
                                        '.billetes-body-locales'
                                    ); // Selecciona el contenido correspondiente
                                    $body.slideToggle(
                                        300
                                    ); // Alterna entre mostrar y ocultar con animación
                                });

                                // Mostrar la primera tabla por defecto
                                $('.billetes-card-locales:first-child .billetes-body-locales')
                                    .show();

                                // Función para actualizar 'Tarjetas' y 'Gastos' dinámicamente por local
                                function actualizarTarjetasYGastos() {
                                    $('.sub-table').each(function() {
                                        const table = $(this).find('table');

                                        // Obtener el índice de la tabla secundaria
                                        const index = $(this).closest(
                                            '.billetes-card-locales').data('index');

                                        // Obtener valores de la tabla principal para este local (por columna)
                                        const payway = parseFloat($(
                                            `#tbodyRendicionesLocales tr:nth-child(1) td:eq(${index + 1}) input`
                                        ).val()) || 0;
                                        const mercadoPago = parseFloat($(
                                            `#tbodyRendicionesLocales tr:nth-child(2) td:eq(${index + 1}) input`
                                        ).val()) || 0;
                                        const gastos = parseFloat($(
                                            `#tbodyRendicionesLocales tr:nth-child(3) td:eq(${index + 1}) input`
                                        ).val()) || 0;
                                        const cuentaCorriente = parseFloat($(
                                            `#tbodyRendicionesLocales tr:nth-child(4) td:eq(${index + 1}) input`
                                        ).val()) || 0;

                                        // Calcular totales dinámicamente
                                        const totalTarjetas = payway + mercadoPago;
                                        const totalGastos = gastos + cuentaCorriente;

                                        // Actualizar las filas correspondientes a este local
                                        table.find('.tarjetas-column-locales').text(
                                            totalTarjetas.toFixed(2));
                                        table.find('.gastos-column-locales').text(
                                            totalGastos.toFixed(2));
                                    });
                                }

                                // Detectar cambios en la tabla principal y actualizar las tablas secundarias
                                $(document).on('input', '.table-input-locales', function() {
                                    actualizarTarjetasYGastos();
                                });

                                // Llamar a la función al cargar la tabla secundaria
                                actualizarTarjetasYGastos();



                                // Actualizar dinámicamente la fila de diferencia en las tablas secundarias
                                function actualizarDiferenciaDinamica() {
                                    $('.sub-table').each(function() {
                                        const table = $(this).find('table');
                                        const totalEfectivo = parseFloat(table.find(
                                            '.total-general-locales').text()) || 0;
                                        const sistema = parseFloat(table.find(
                                            '.sistema-input-locales').val()) || 0;

                                        // Obtener el valor de 'Cambios' desde la tabla principal
                                        const cambios = parseFloat($(
                                            `#tbodyRendicionesLocales tr:contains('Cambios') td`
                                        ).eq(table.closest(
                                            '.billetes-card-locales').data(
                                            'index') + 1).find('input').val()) || 0;

                                        // Calcular diferencia


                                        // Calcular sistema + cambios
                                        const sistemaMasCambios = sistema + cambios;
                                        const diferencia = totalEfectivo -
                                            sistemaMasCambios;
                                        // Actualizar la fila de diferencia
                                        table.find('.diferencia-column-locales').text(
                                            diferencia.toFixed(2));

                                        // Insertar o actualizar la fila 'Sistema + Cambios'
                                        let filaSistemaMasCambios = table.find(
                                            '.sistema-mas-cambios-row');
                                        if (filaSistemaMasCambios.length === 0) {
                                            filaSistemaMasCambios = $(
                                                `<tr class="sistema-mas-cambios-row">
                                                            <td colspan="13" class="font-weight-bold text-center">
                                                                <strong>Sistema + Cambios: </strong> 
                                                                <span class="sistema-mas-cambios-column text-success">${sistemaMasCambios.toFixed(2)}</span>
                                                            </td>
                                                        </tr>`
                                            );
                                            table.find('tfoot').append(
                                                filaSistemaMasCambios);
                                        } else {
                                            filaSistemaMasCambios.find(
                                                '.sistema-mas-cambios-column').text(
                                                sistemaMasCambios.toFixed(2));
                                        }
                                    });
                                }


                                // Inicializar las diferencias desde el inicio
                                actualizarDiferenciaDinamica();

                                // Recalcular diferencias dinámicamente al modificar valores
                                $(document).on('input',
                                    '.cantidad-input-locales, .sistema-input-locales',
                                    function() {
                                        actualizarDiferenciaDinamica();
                                    });

                                // Crear tabla TOTAL PREVENTA
                                let totalLocalesHtml = `
                                                <table class="datatables-ajax table table-bordered table-hover table-sm table table-striped">
                                                    <thead>
                                                    <th colspan="13" class="text-center">
                                                        <h6  style="margin: 10px 0;">Total Locales</h6>
                                                    </th>
                                                        <tr>
                                                            <th>Denominación</th>
                                                            <th>20,000</th>
                                                            <th>10,000</th>
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
                                                            ${[20000, 10000, 2000, 1000, 500, 200, 100, 50, 20, 10].map(denominacion => {
                                                                return `<td class="total-cantidad-locales" data-denominacion="${denominacion}">0</td>`;
                                                            }).join('')}
                                                            <td class="font-weight-bold" id="total-global-locales">0.00</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                               
                                      `;
                                $('#totalLocales').html(totalLocalesHtml);


                                // Actualizar dinámicamente las columnas y totales en las tablas secundarias
                                $(document).on('input', '.cantidad-input-locales', function() {
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
                                    table.find('.total-general-locales').text(totalFila
                                        .toFixed(2));

                                    // Actualizar TOTAL PREVENTA
                                    actualizarTotalLocales();
                                    actualizarTablaBanco(); // Actualizar BANCO
                                });

                                // Función para actualizar la tabla TOTAL PREVENTA
                                function actualizarTotalLocales() {
                                    const denominaciones = [20000, 10000, 2000, 1000, 500,
                                        200, 100, 50, 20, 10
                                    ];
                                    let totalGlobal = 0;

                                    denominaciones.forEach(denominacion => {
                                        let totalCantidad = 0;

                                        $('.cantidad-input-locales[data-denominacion="' +
                                            denominacion + '"]').each(function() {
                                            totalCantidad += parseFloat($(this)
                                                .val()) || 0;
                                        });

                                        $(`.total-cantidad-locales[data-denominacion="${denominacion}"]`)
                                            .text(totalCantidad);
                                        totalGlobal += totalCantidad * denominacion;
                                    });

                                    $('#total-global-locales').text(totalGlobal.toFixed(2));
                                }

                                // Inicializar el TOTAL PREVENTA
                                actualizarTotalLocales();

                                function actualizarTotalLocales() {
                                    const denominaciones = [20000, 10000, 2000, 1000, 500,
                                        200, 100, 50, 20, 10
                                    ];
                                    let totalGlobal = 0;

                                    denominaciones.forEach(denominacion => {
                                        let totalCantidad = 0;

                                        // Incluir todas las cantidades, incluyendo la tabla LIBRE
                                        $(`.cantidad-input-locales[data-denominacion="${denominacion}"], .cantidad-libre[data-denominacion="${denominacion}"]`)
                                            .each(function() {
                                                totalCantidad += parseFloat($(this)
                                                    .val()) || 0;
                                            });

                                        $(`.total-cantidad-locales[data-denominacion="${denominacion}"]`)
                                            .text(totalCantidad);
                                        totalGlobal += totalCantidad * denominacion;
                                    });

                                    $('#total-global-locales').text(totalGlobal.toFixed(2));
                                }

                                // Inicializar la tabla "LIBRE" y la columna en la tabla principal
                                $(document).ready(function() {
                                    agregarTablaLibre();

                                    actualizarTotalLocales();

                                });


                                function crearTablaResumenGeneral() {
                                    let resumenHtmlGeneral = `
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Descripción</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Total Efectivo</td>
                                                <td id="totalEfectivoGeneral">0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Total Tarjetas</td>
                                                <td id="totalTarjetasGeneral">0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Total Gastos</td>
                                                <td id="totalGastosGeneral">0.00</td>
                                            </tr>
                                        
                                            <tr>
                                                <td><strong>Total</strong></td>
                                                <td id="totalSumGeneral" class="font-weight-bold text-success">0.00</td>
                                            </tr>
                                           
                                            <tr>
                                                <td>Diferencia</td>
                                                <td id="diferenciaTotalGeneral" class="font-weight-bold text-primary">0.00</td>
                                            </tr>

                                            <tr>
                                                <td><strong>Total General Locales</strong></td>
                                                <td id="totalGeneralLocales" class="font-weight-bold text-info">0.00</td>
                                            </tr>
                                        </tbody>
                                    </table>`;
                                    $('#resumenGeneralContainer').html(resumenHtmlGeneral);
                                }



                                function actualizarTablaResumenGeneral() {
                                    // Total Efectivo desde la tabla "Total Locales"
                                    const totalEfectivo = parseFloat($('#total-global-locales')
                                        .text()) || 0;

                                    // Total Tarjetas (suma de Payway y Mercado Pago)
                                    const totalPayway = parseFloat($(
                                        '#tbodyRendicionesLocales tr:nth-child(1) .total-column-locales'
                                    ).text()) || 0;
                                    const totalMercadoPago = parseFloat($(
                                        '#tbodyRendicionesLocales tr:nth-child(2) .total-column-locales'
                                    ).text()) || 0;
                                    const totalTarjetas = totalPayway + totalMercadoPago;

                                    // Total Gastos (suma de Gastos y Cuenta Corriente)
                                    const totalGastos =
                                        (parseFloat($(
                                            '#tbodyRendicionesLocales tr:nth-child(3) .total-column-locales'
                                        ).text()) || 0) +
                                        (parseFloat($(
                                            '#tbodyRendicionesLocales tr:nth-child(4) .total-column-locales'
                                        ).text()) || 0);

                                    // Calcular Total General Locales (Efectivo + Tarjetas)
                                    const totalGeneralLocales = totalEfectivo + totalTarjetas;

                                    // Calcular Total (Efectivo + Tarjetas - Gastos)
                                    const total = totalGeneralLocales + totalGastos;

                                    // Obtener Total Sistema desde el input




                                    // Actualizar los valores en la tabla
                                    $('#totalEfectivoGeneral').text(totalEfectivo.toFixed(2));
                                    $('#totalTarjetasGeneral').text(totalTarjetas.toFixed(2));
                                    $('#totalGeneralLocales').text(totalGeneralLocales.toFixed(2));
                                    $('#totalGastosGeneral').text(totalGastos.toFixed(2));
                                    $('#totalSumGeneral').text(total.toFixed(2));


                                    // Diferencia General (Efectivo + Tarjetas - Gastos)
                                    const diferencia = totalTarjetas + totalEfectivo - totalGastos;
                                    $('#diferenciaTotalGeneral').text(diferencia.toFixed(2));
                                }


                                $(document).ready(function() {
                                    buscarDetalleRendicionesLocales();
                                    actualizarTablaBanco();

                                    function buscarDetalleRendicionesLocales() {
                                        // Tu código existente para obtener los datos...

                                        // Dentro del success de tu AJAX, después de construir las tablas:
                                        crearTablaResumenGeneral
                                            (); // Crear la tabla de resumen
                                        actualizarTablaResumenGeneral
                                            (); // Inicializar los valores actuales

                                        // Escuchar eventos dinámicos en tablas locales
                                        $(document).on('input',
                                            '.table-input-locales, .cantidad-input-locales',
                                            function() {
                                                actualizarTablaResumenGeneral();
                                                actualizarTablaBanco
                                                    (); // Actualizar BANCO
                                            });

                                        // Escuchar cambios en el campo "Total Sistema"
                                        $(document).on('input', '#totalSistemaInput',
                                            function() {
                                                actualizarTablaResumenGeneral();
                                            });
                                    }
                                });






                            } else {
                                $('#theadRendicionesLocales').html(
                                    '<tr><th>No se encontraron detalles</th></tr>'
                                );
                                $('#tbodyRendicionesLocales').html('');

                                // Ocultar los divs adicionales
                                $('#tablasSecundariasLocales').closest('.card').hide();
                                $('#totalLocales').closest('.card').hide();
                                $('#resumenGeneralContainer').closest('.card').hide();
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


            <script>
            function actualizarTablaBanco() {
                const denominaciones = [20000, 10000, 2000, 1000, 500, 200, 100, 50, 20, 10];
                let html = `
                 <div class="card p-3 my-2">
                            <table class="datatables-ajax table table-bordered table-hover table-sm table table-striped">
                                <thead>
                                    <tr>
                                        <th>Denominación</th>
                                        ${denominaciones.map(den => `<th>${den.toLocaleString()}</th>`).join('')}
                                        <th>Total General</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold">Cantidad</td>
                                        ${denominaciones.map(den => {
                                            const cantidadPreventa = Array.from($(`.cantidad-input[data-denominacion="${den}"]`))
                                                .reduce((sum, input) => sum + (parseFloat($(input).val()) || 0), 0);
                                            const cantidadLocales = Array.from($(`.cantidad-input-locales[data-denominacion="${den}"]`))
                                                .reduce((sum, input) => sum + (parseFloat($(input).val()) || 0), 0);
                                            const totalCantidad = cantidadPreventa + cantidadLocales;
                                            return `<td data-denominacion="${den}" class="cantidad-banco">${totalCantidad}</td>`;
                                        }).join('')}
                                        <td id="total-billetes-banco-cantidad" class="font-weight-bold">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Total por Denominación</td>
                                        ${denominaciones.map(den => {
                                            const cantidadPreventa = Array.from($(`.cantidad-input[data-denominacion="${den}"]`))
                                                .reduce((sum, input) => sum + (parseFloat($(input).val()) || 0), 0);
                                            const cantidadLocales = Array.from($(`.cantidad-input-locales[data-denominacion="${den}"]`))
                                                .reduce((sum, input) => sum + (parseFloat($(input).val()) || 0), 0);
                                            const totalCantidad = cantidadPreventa + cantidadLocales;
                                            const totalPorDenominacion = totalCantidad * den;
                                            return `<td class="total-por-denominacion">${totalPorDenominacion.toFixed(2)}</td>`;
                                        }).join('')}
                                        <td id="total-billetes-banco-total" class="font-weight-bold">0.00</td>
                                    </tr>
                                </tbody>
                            </table>
                             </div>
                        `;

                $('#totalBanco').html(html);
                agregarTablaCheques();
                agregarTablaTotalGeneral();

                actualizarTotalBanco();
                actualizarTotalCheques();
                actualizarTotalGeneral();
            }

            function actualizarTotalBanco() {
                let totalGlobal = 0;
                $('.cantidad-banco').each(function() {
                    const denominacion = parseFloat($(this).data('denominacion')) || 0;
                    const cantidad = parseFloat($(this).text()) || 0;
                    totalGlobal += denominacion * cantidad;
                });

                $('#total-billetes-banco-total').text(totalGlobal.toFixed(2));
                actualizarTotalGeneral();
            }

            function agregarTablaCheques() {
                let chequesHtml = `
                        <div class="card p-3 my-2">
                            <table class="table table-bordered table-sm" id="tabla-cheques">
                                <thead>
                                    <tr><th colspan="2" class="text-center font-weight-bold">CHEQUES</th></tr>
                                    <tr>
                                        <th>BANCO</th>
                                        <th>IMPORTE</th>
                                    </tr>
                                </thead>
                                <tbody id="cheques-body">
                                    <tr>
                                        <td><input type="text" class="form-control banco-cheque" placeholder="Nombre del banco"></td>
                                        <td><input type="number" class="form-control importe-cheque" min="0" placeholder="0.00"></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="font-weight-bold text-right">TOTAL CHEQUES</td>
                                        <td id="total-cheques" class="font-weight-bold text-right">$ 0.00</td>
                                    </tr>
                                </tfoot>
                            </table>
                            <button id="agregar-fila-cheque" class="btn btn-primary btn-sm mt-2">Agregar Fila</button>
                            </div>
                        `;

                $('#totalBanco').append(chequesHtml);

                // Eliminar evento previo y volver a asignarlo
                $('#totalBanco').off('click', '#agregar-fila-cheque');
                $('#totalBanco').on('click', '#agregar-fila-cheque', function() {
                    agregarFilaCheque();
                });

                // Evento para actualizar el total de cheques
                $(document).off('input', '.importe-cheque'); // Eliminar eventos previos
                $(document).on('input', '.importe-cheque', actualizarTotalCheques);
            }

            function agregarFilaCheque() {
                let nuevaFila = `
                                <tr>
                                    <td><input type="text" class="form-control banco-cheque" placeholder="Nombre del banco"></td>
                                    <td><input type="number" class="form-control importe-cheque" min="0" placeholder="0.00"></td>
                                </tr>
                            `;

                $('#cheques-body').append(nuevaFila);
            }

            function actualizarTotalCheques() {
                let totalCheques = 0;
                $('.importe-cheque').each(function() {
                    totalCheques += parseFloat($(this).val()) || 0;
                });
                $('#total-cheques').text(`$ ${totalCheques.toFixed(2)}`);
                actualizarTotalGeneral();
            }

            function agregarTablaTotalGeneral() {
                let totalGeneralHtml = `
                 <div class="card p-3 my-2">
                    <table class="table table-bordered table-sm mt-3">
                        <thead>
                            <tr><th colspan="2" class="text-center font-weight-bold">TOTAL GENERAL</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold">TOTAL EFECTIVO</td>
                                <td id="total-efectivo-general" class="text-right">$ 0.00</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">TOTAL CHEQUES</td>
                                <td id="total-cheques-general" class="text-right">$ 0.00</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-primary">TOTAL GENERAL</td>
                                <td id="total-general" class="font-weight-bold text-right text-primary">$ 0.00</td>
                            </tr>
                        </tbody>
                    </table>
                     </div>
                `;
                $('#totalBanco').append(totalGeneralHtml);
            }

            function actualizarTotalGeneral() {
                const totalEfectivo = parseFloat($('#total-billetes-banco-total').text()) || 0;
                const totalCheques = parseFloat($('#total-cheques').text().replace('$', '').trim()) || 0;
                const totalGeneral = totalEfectivo + totalCheques;

                $('#total-efectivo-general').text(`$ ${totalEfectivo.toFixed(2)}`);
                $('#total-cheques-general').text(`$ ${totalCheques.toFixed(2)}`);
                $('#total-general').text(`$ ${totalGeneral.toFixed(2)}`);
            }

            $(document).on('input', '.cantidad-input, .cantidad-input-locales', function() {
                actualizarTablaBanco();
            });

            $(document).ready(function() {
                actualizarTablaBanco();
            });
            </script>



            <script>
            function recorrerTablaPrincipal() {
                let resultado = {};

                // Recorremos cada columna de la tabla principal
                $('#theadRendiciones th[data-id]').each(function(index) {
                    const columnaId = $(this).data('id'); // Obtener el ID de la columna
                    let columnaValores = {};

                    // Recorremos cada fila de la tabla principal (tbody) y extraemos los valores
                    $('#tbodyRendiciones tr').each(function() {
                        let nombreFila = $(this).find('td:first').text().trim(); // Ej: Total Ventas
                        nombreFila = nombreFila.replace(/\s+/g,
                            '_'); // Reemplazar espacios por guiones bajos

                        const valorCelda = $(this).find(`td`).eq(index + 1).text() ||
                            $(this).find(`td`).eq(index + 1).find('input')
                            .val(); // Si hay input, tomar el valor

                        columnaValores[nombreFila] = valorCelda || 0; // Si no hay valor, establecer 0
                    });

                    // Inicializar el total de efectivo para esta columna
                    let totalEfectivo = 0;

                    // Recorremos billetes y sumamos al mismo objeto
                    $(`.billetes-card[data-index="${index}"] .cantidad-input`).each(function() {
                        const denominacion = $(this).data('denominacion'); // Ej: 1000, 500, etc.
                        const cantidad = $(this).val() || 0;

                        const subtotal = parseInt(denominacion, 10) * parseInt(cantidad, 10) ||
                            0; // Calcular subtotal por denominación
                        totalEfectivo += subtotal; // Sumar al total de efectivo

                        columnaValores[`billetes_${denominacion}`] =
                            cantidad; // Guardar con clave billetes_xxxx
                    });

                    // Agregar el total de efectivo al objeto
                    columnaValores['Total_Efectivo'] = totalEfectivo;

                    // Guardar la columna usando el id como clave
                    resultado[columnaId] = columnaValores;
                });

                return resultado;
            }



            function recorrerTablaLibre() {
                let resultadoLibre = [];

                // Recorremos cada fila de la tabla "LIBRE"
                $('#tablaLibre tbody tr').each(function() {
                    let fila = {};

                    // Añadimos el motivo directamente al objeto
                    fila.motivo = $(this).find('.motivo-libre').val() || "Sin motivo";

                    // Recorremos cada billete y lo añadimos al mismo objeto
                    $(this).find('.cantidad-libre').each(function() {
                        const denominacion = $(this).data('denominacion'); // Obtener la denominación
                        const cantidad = $(this).val() || 0; // Obtener la cantidad
                        fila[`billetes_${denominacion}`] = cantidad; // Añadir billete directamente
                    });

                    resultadoLibre.push(fila); // Agregar la fila al resultado
                });

                return resultadoLibre;
            }

            function combinarTotales() {
                let resultadoCombinado = {};

                // Obtener datos de la tabla "Total Preventa"
                $('#totalPreventa tbody tr').each(function() {
                    $(this).find('.total-cantidad').each(function() {
                        const denominacion = $(this).data('denominacion'); // Obtener la denominación
                        const cantidad = $(this).text() || 0; // Obtener la cantidad total
                        resultadoCombinado[`billetes_${denominacion}`] = cantidad;
                    });

                    // Obtener el total general de la tabla
                    const totalGeneral = $(this).find('#total-global').text() || 0;
                    resultadoCombinado['total_general_preventa'] = totalGeneral;
                });

                // Agregar datos de la tabla "General"
                $('#tablaResumenPreventa tbody tr').each(function() {
                    const descripcion = $(this).find('td:first').text().toLowerCase().replace(/ /g,
                        '_'); // Ej: "TOTAL EFECTIVO" -> "total_efectivo"
                    const valor = $(this).find('td:last').text() || 0; // Obtener el valor
                    resultadoCombinado[descripcion] = valor;
                });

                // Agregar el total general preventa (si existe)
                const totalGeneralResumen = $('#totalGeneralResumen').text() || 0;
                resultadoCombinado['total_general_resumen'] = totalGeneralResumen;

                return resultadoCombinado;
            }

            function ejecutarRecorridoPreventa() {
    const tablaPrincipal = recorrerTablaPrincipal();
    const tablaLibre = recorrerTablaLibre();
    const totalesCombinados = combinarTotales();

    // Verificar si hay datos para enviar
    if (Object.keys(tablaPrincipal).length === 0 && Object.keys(tablaLibre).length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Sin datos',
            text: 'No hay datos en las tablas para enviar.'
        });
        return;
    }

    // Crear objeto final para enviar al backend
    const datosFinales = {
        tabla_principal: tablaPrincipal,
        tabla_libre: tablaLibre,
        totales: totalesCombinados
    };

    console.log(datosFinales); // Verificar en consola

    // Confirmación antes de enviar al backend
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se guardará la rendición de preventa.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, guardar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            enviarDatosAlBackend(datosFinales); // Enviar datos al servidor si confirma
        }
    });
}

function enviarDatosAlBackend(datos) {
    const data = {
        action: 'insertarRendicion',
        tabla_principal: datos.tabla_principal,
        tabla_libre: datos.tabla_libre,
        totales: datos.totales
    };

    // Enviar la solicitud AJAX
    $.ajax({
        url: '../../backend/controller/administracion/Rendiciones.php', // Ruta al backend
        method: 'POST',
        contentType: 'application/json', // Indicar que es un JSON
        data: JSON.stringify(data), // Convertir el objeto a JSON
        success: function(response) {
            console.log('Respuesta del servidor:', response);

            // Mostrar solo si la inserción fue exitosa
            if (response.success) {
                Swal.fire(
                    'Guardado!',
                    'Los datos de preventa se guardaron correctamente.',
                    'success'
                );
            } else {
                // Mostrar error si la respuesta no tiene éxito
                Swal.fire(
                    'Error!',
                    'No se pudo guardar: ' + response.error,
                    'error'
                );
            }
        },
        error: function(error) {
            console.error('Error al guardar los datos:', error);
            Swal.fire(
                'Error!',
                'Error en la comunicación con el servidor.',
                'error'
            );
        }
    });
}
            </script>



            <script>
            function recorrerTablaPrincipalLocales() {
                let resultado = {
                    principales: {},
                    totalLocales: {},
                    resumenGeneral: {}
                };

                const headers = $('#theadRendicionesLocales th[data-id]');
                const rows = $('#tbodyRendicionesLocales tr');

                if (headers.length === 0 || rows.length === 0) {
                    console.warn("No se encontraron tablas para recorrer.");
                    return resultado;
                }

                headers.each(function(index) {
                    const columnaId = $(this).data('id');
                    let columnaValores = {};

                    rows.each(function() {
                        let nombreFila = $(this).find('td:first').text().trim();
                        nombreFila = nombreFila.replace(/\s+/g, '_');

                        let celda = $(this).find('td').eq(index + 1);
                        let valorCelda = celda.find('input').val() || celda.text().trim() || 0;

                        columnaValores[nombreFila] = parseFloat(valorCelda) || 0;
                    });

                    let totalEfectivo = 0;

                    $(`.billetes-card-locales[data-index="${index}"] .cantidad-input-locales`).each(function() {
                        const denominacion = $(this).data('denominacion');
                        const cantidad = parseFloat($(this).val()) || 0;

                        const subtotal = parseInt(denominacion, 10) * cantidad;
                        totalEfectivo += subtotal;

                        columnaValores[`billetes_${denominacion}`] = cantidad;
                    });

                    let sistema = parseFloat($(`#sistema-input-${index}`).val()) || 0;
                    let sistemaMasCambios = parseFloat($(
                        `.billetes-card-locales[data-index="${index}"] .sistema-mas-cambios-column`
                    ).text()) || 0;
                    let totalTarjetas = parseFloat($(
                        `.billetes-card-locales[data-index="${index}"] .tarjetas-column-locales`
                    ).text()) || 0;
                    let totalEfectivoCalculado = parseFloat($(
                        `.billetes-card-locales[data-index="${index}"] .total-general-locales`
                    ).text()) || 0;
                    let diferencia = parseFloat($(
                        `.billetes-card-locales[data-index="${index}"] .diferencia-column-locales`
                    ).text()) || 0;

                    columnaValores['Total_Efectivo'] = totalEfectivo;
                    columnaValores['Sistema'] = sistema;
                    columnaValores['Sistema_Mas_Cambios'] = sistemaMasCambios;
                    columnaValores['Tarjetas'] = totalTarjetas;
                    columnaValores['Diferencia'] = diferencia;

                    resultado.principales[columnaId] = columnaValores;
                });

                $('#totalLocales .total-cantidad-locales').each(function() {
                    const denominacion = $(this).data('denominacion');
                    const valor = parseFloat($(this).text()) || 0;
                    resultado.totalLocales[`billetes_${denominacion}`] = valor;
                });
                resultado.totalLocales['Total'] = parseFloat($('#total-global-locales').text()) || 0;

                resultado.resumenGeneral = {
                    'Total_Efectivo': parseFloat($('#totalEfectivoGeneral').text()) || 0,
                    'Total_Tarjetas': parseFloat($('#totalTarjetasGeneral').text()) || 0,
                    'Total_Gastos': parseFloat($('#totalGastosGeneral').text()) || 0,
                    'Total_Sum': parseFloat($('#totalSumGeneral').text()) || 0,
                    'Diferencia': parseFloat($('#diferenciaTotalGeneral').text()) || 0,
                    'Total_General_Locales': parseFloat($('#totalGeneralLocales').text()) || 0
                };
                console.log("Resultado de recorrerTablaPrincipalLocales:", resultado);
                return resultado;
            }

            function enviarDatosLocalesAlBackend() {
                const tablas = recorrerTablaPrincipalLocales();

                // Verificar si hay datos antes de enviar
                if (Object.keys(tablas.principales).length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sin datos',
                        text: 'No hay datos para enviar. Verifique la tabla.'
                    });
                    return;
                }

                const totalesFusionados = {
                    ...tablas.resumenGeneral,
                    ...tablas.totalLocales
                };

                const data = {
                    action: 'insertarRendicionLocales',
                    tablas: {
                        principales: tablas.principales,
                        totalesFusionados: totalesFusionados
                    }
                };

                console.log("Datos enviados al backend:", data);

                // Mostrar alerta de confirmación
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Se guardará la rendición de locales.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, guardar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, enviar los datos al backend
                        $.ajax({
                            url: '../../backend/controller/administracion/Rendiciones.php',
                            method: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify(data),
                            success: function(response) {
                                console.log('Respuesta del servidor:', response);
                                if (response.success) {
                                    Swal.fire(
                                        'Guardado!',
                                        'Los datos de rendición se han guardado correctamente.',
                                        'success'
                                    );
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        'No se pudo guardar: ' + response.error,
                                        'error'
                                    );
                                }
                            },
                            error: function(error) {
                                console.error('Error en la comunicación:', error);
                                Swal.fire(
                                    'Error!',
                                    'Error de conexión con el servidor.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            }
            </script>


            <script>
            function obtenerDatosTablas() {
                let datosFusionados = {}; // Aquí se fusionan billetes y totales
                let datosCheques = {}; // Cheques como un objeto independiente

                let totalEfectivo = 0;

                // 1. Recorrer la tabla de billetes y calcular total general
                $('.cantidad-banco').each(function() {
                    const denominacion = $(this).data('denominacion');
                    const cantidad = parseFloat($(this).text()) || 0;
                    const totalPorDenominacion = cantidad * denominacion;

                    datosFusionados[`billetes_${denominacion}`] = cantidad;
                    totalEfectivo += totalPorDenominacion;
                });

                // 2. Recorrer la tabla de cheques
                let totalCheques = 0;
                let listaCheques = [];

                $('#tabla-cheques tbody tr').each(function() {
                    const banco = $(this).find('.banco-cheque').val();
                    const importe = parseFloat($(this).find('.importe-cheque').val()) || 0;

                    if (banco && importe > 0) {
                        listaCheques.push({
                            banco: banco,
                            importe: importe
                        });
                        totalCheques += importe;
                    }
                });

                // 3. Añadir total general (efectivo y cheques) al mismo objeto de billetes
                datosFusionados['totalEfectivo'] = totalEfectivo;
                datosFusionados['totalCheques'] = totalCheques;
                datosFusionados['totalGeneral'] = totalEfectivo + totalCheques;

                // Almacenar los cheques por separado
                datosCheques['cheques'] = listaCheques;

                // Mostrar resultados en consola (para depuración)
                console.log("Datos Fusionados (Billetes + Total General):", datosFusionados);
                console.log("Cheques:", datosCheques);

                // Devolver los datos
                return {
                    fusionados: datosFusionados,
                    cheques: datosCheques
                };
            }

            function enviarDatosBanco() {
                const datos = obtenerDatosTablas(); // Recopila los datos de billetes y cheques

                // Mostrar alerta de confirmación
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Se guardará la rendición bancaria.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, guardar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, enviar los datos al backend
                        fetch('../../backend/controller/administracion/Rendiciones.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    action: 'insertarRendicionBanco',
                                    fusionados: datos.fusionados,
                                    cheques: datos.cheques
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire(
                                        'Guardado!',
                                        'La rendición bancaria ha sido guardada.',
                                        'success'
                                    );
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        'No se pudo guardar: ' + data.error,
                                        'error'
                                    );
                                }
                            })
                            .catch(error => {
                                console.error('Error en la comunicación:', error);
                                Swal.fire(
                                    'Error!',
                                    'Error de conexión con el servidor.',
                                    'error'
                                );
                            });
                    }
                });
            }


            // Ejecutar y guardar los datos
            </script>




</body>

</html>