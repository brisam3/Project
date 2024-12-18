
let billetesHtml = `
    <div class="card mb-3 billetes-card" data-index="${index}">
        <div class="card-header billetes-header" style="cursor: pointer;">
            <h6 style="margin: 10px 0;">${detalle.movil} ${detalle.nombre_preventista} ---- ${detalle.nombre_chofer}</h6>
        </div>
        <div class="card-body billetes-body" style="display: none;">
            <div class="sub-table my-2">
                <div class="dataTables_wrapper no-footer" style="width: 100% !important;">
                    <table class="datatables-ajax table table-bordered table-hover table-sm table table-striped">
                        <thead>
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
        </div>
    </div>`;
subTablesHtml += billetesHtml;
                                });

                               // Agregar después de generar todas las tablas
$('#tablasSecundarias').html(subTablesHtml);

// Funcionalidad para expandir/contraer las tablas
$('.billetes-header').on('click', function() {
    const $body = $(this).next('.billetes-body');
    $body.slideToggle(300);
});

// Mostrar la primera tabla por defecto
$('.billetes-card:first-child .billetes-body').show();