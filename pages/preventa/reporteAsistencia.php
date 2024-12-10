<!DOCTYPE html>
<html lang="es" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../../assets/" data-template="horizontal-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Detalle de Devoluciones</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.1/css/boxicons.min.css" rel="stylesheet"
        integrity="sha512-cfBUsnQh7OSdceLgoYe8n5f4gR8wMSAEPr7iZYswqlN4OrcKUYxxCa5XPrp2XrtH0nXGGaOb7SfiI4Rkzr3psA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="../../assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../assets/css/demo.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="../css/clima.css" />

    <script src="../../assets/vendor/js/helpers.js"></script>
    <script src="../../assets/vendor/js/template-customizer.js"></script>
    <script src="../../assets/js/config.js"></script>
</head>

<body>
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">
            <?php include "../template/nav.php"; ?>
            <div class="container-xxl flex-grow-1 container-p-y mt-5">
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Reporte/</span>
                    ASISTENCIAS</h4>
                <div class="table-responsive-xl mb-6 mb-lg-0">
                    <table id="tabla-asistencias" class="table table-bordered m-3 table-hover">
                        <thead class="bg-light text-dark border-top-class m-1">
                            <tr>
                                <th>ID</th>
                                <th>Preventista</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Estado</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-asistencias-body">
                            <!-- Los datos se llenarán dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="layout-overlay layout-menu-toggle"></div>
            <div class="drag-target"></div>

            <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
            <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

            <script>
                let dataTable;

                function cargarDatosAsistencias() {
                    $.ajax({
                        url: "../../backend/controller/preventa/reporteAsistenciasController.php",
                        method: "GET",
                        dataType: "json",
                        success: function (response) {
                            if (response.status === "success") {
                                // Si DataTable ya está inicializado
                                if (dataTable) {
                                    // Actualizar los datos de manera fluida
                                    dataTable.clear(); // Limpiar los datos actuales
                                    dataTable.rows.add(response.data.map(asistencia => {
                                        const estadoClass =
                                            asistencia.estado === "Presente" ? "text-success" :
                                                asistencia.estado === "Tardanza" ? "text-warning" :
                                                    "text-danger";
                                        return [
                                            asistencia.idAsistencia,
                                            asistencia.preventista,
                                            asistencia.fecha,
                                            asistencia.hora,
                                            `<span class="${estadoClass}">${asistencia.estado}</span>`,
                                            asistencia.observaciones || "Sin observaciones"
                                        ];
                                    }));
                                    dataTable.draw(); // Renderizar la tabla con los nuevos datos
                                } else {
                                    // Inicializar DataTable con los datos por primera vez
                                    dataTable = $("#tabla-asistencias").DataTable({
                                        data: response.data.map(asistencia => {
                                            const estadoClass =
                                                asistencia.estado === "Presente" ? "text-success" :
                                                    asistencia.estado === "Tardanza" ? "text-warning" :
                                                        "text-danger";
                                            return [
                                                asistencia.idAsistencia,
                                                asistencia.preventista,
                                                asistencia.fecha,
                                                asistencia.hora,
                                                `<span class="${estadoClass}">${asistencia.estado}</span>`,
                                                asistencia.observaciones || "Sin observaciones"
                                            ];
                                        }),
                                        columns: [
                                            { title: "ID" },
                                            { title: "Preventista" },
                                            { title: "Fecha" },
                                            { title: "Hora" },
                                            { title: "Estado" },
                                            { title: "Observaciones" }
                                        ],
                                        order: [[2, "desc"], [3, "desc"]],
                                        language: {
                                            url: "//cdn.datatables.net/plug-ins/1.11.3/i18n/Spanish.json"
                                        },
                                        columnDefs: [
                                            { targets: [0], visible: false },
                                            { targets: [4], className: "text-center" }
                                        ]
                                    });
                                }
                            } else {
                                console.error("Error en los datos:", response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Error al cargar los datos:", error);
                        }
                    });
                }

                $(document).ready(function () {
                    cargarDatosAsistencias(); // Cargar datos iniciales
                    setInterval(cargarDatosAsistencias, 2000); // Actualizar cada 4 segundos
                });


            </script>

</body>

</html>