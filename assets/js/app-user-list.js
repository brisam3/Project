/**
 * Page User List
 */

'use strict';

// Datatable (jquery)
$(function () {
  let borderColor, bodyBg, headingColor;

  if (isDarkStyle) {
    borderColor = config.colors_dark.borderColor;
    bodyBg = config.colors_dark.bodyBg;
    headingColor = config.colors_dark.headingColor;
  } else {
    borderColor = config.colors.borderColor;
    bodyBg = config.colors.bodyBg;
    headingColor = config.colors.headingColor;
  }

  // Variable declaration for table
  var dt_user_table = $('.datatables-users'),
    select2 = $('.select2'),
    userView = 'app-user-view-account.html',
    statusObj = {
      1: { title: 'Pending', class: 'bg-label-warning' },
      2: { title: 'Active', class: 'bg-label-success' },
      3: { title: 'Inactive', class: 'bg-label-secondary' }
    };

  if (select2.length) {
    var $this = select2;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Select Country',
      dropdownParent: $this.parent()
    });
  }

  // Users datatable
  if (dt_user_table.length) {
    var dt_user = dt_user_table.DataTable({
      ajax: {
        "method":"POST",
        "url": "../../php/clientes/clientes.php"
      },// JSON file to add data
      columns: [
        // columns according to JSON
        { data: 'Id_Clients' },
        { data: 'Clients_Name' },
        { data: 'Clients_LastName' },
        { data: 'Clients_Phone' },
        { data: 'Clients_Email' },
        { data: 'Clients_Totalpurchases' },
        { data: 'Clients_AveragePurchase' },
        { data: 'Clients_SpendsTheMost' },
      ],
      
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          searchable: false,
          orderable: false,
          responsivePriority: 2,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          // User full name and email
          targets: 1,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            var $name = full['Clients_Name'],
              $lastname =full['Clients_LastName'],
              $email = "Cliente",
              $image = full['avatar'];
            if ($image) {
              // For Avatar image
              var $output =
                '<img src="' + assetsPath + '/img/avatars/' + $image + '" alt="Avatar" class="rounded-circle">';
            } else {
              // For Avatar badge
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              var $state = states[stateNum],
                $name = full['Clients_Name'],
                $initials = $name.match(/\b\w/g) || [];
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
            }
            // Creates full output for row
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center user-name">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar avatar-sm me-3">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<a href="' +
              userView +
              '" class="text-body text-truncate"><span class="fw-semibold">' +
              $name+" "+$lastname +
              '</span></a>' +
              '<small class="text-muted">' +
              $email +
              '</small>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          // User Role
          targets: 2,
          render: function (data, type, full, meta) {
            var $plan = full['Clients_Phone'];

            return '<span class="fw-semibold">' + $plan + '</span>';
          }
        },
        {
          // Plans
          targets: 3,
          render: function (data, type, full, meta) {
            var $plan = full['Clients_Email'];

            return '<span class="fw-semibold">' + $plan + '</span>';
          }
        },
        {
          // User Status
          targets: 4,
          render: function (data, type, full, meta) {
            var $plan = full['Clients_Totalpurchases'];

            return '<span class="fw-semibold">' + $plan + '</span>';
          }
        },
        {
          // User Status
          targets: 5,
          render: function (data, type, full, meta) {
            var $plan = full['Clients_AveragePurchase'];

            if($plan !=""){
              return '<span class="fw-semibold">' + $plan + '$</span>';
            }else{
              return '<span class="fw-semibold">0.00$</span>';
            }

            
          }
        },
        {
          // User Status
          targets: 6,
          render: function (data, type, full, meta) {
            var $plan = full['Clients_SpendsTheMost'];
                        
            if($plan !=""){
              return '<span class="fw-semibold">' + $plan + '$</span>';
            }else{
              return '<span class="fw-semibold">0.00$</span>';
            }
          }
        },

        {
          // Actions
          
          targets: -1,
          title: 'Opciones',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            var $id = full['Id_Clients'];
            return (
              '<div class="d-inline-block text-nowrap">' +
              '<button onclick="Editar('+$id+');" data-bs-toggle="modal" data-bs-target="#modalCenter" class="btn btn-sm btn-icon" ><i class="bx bx-edit"></i></button>' +
              '<button onclick="Eliminar('+$id+');" class="btn btn-sm btn-icon delete-record"><i class="bx bx-trash"></i></button>' +
              '<button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>' +
              '<div class="dropdown-menu dropdown-menu-end m-0">' +
              '<a href="' +
              userView +
              '" class="dropdown-item">Ver</a>' +
              '</div>' +
              '</div>'
            );
          }
        }
      ],
      
      order: [[1, 'desc']],
      dom:
        '<"row mx-2"' +
        '<"col-md-2"<"me-3"l>>' +
        '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Buscar..'
      },

      // Buttons with Dropdown
      buttons: [

          

        {
          extend: 'collection',
          className: 'btn btn-label-secondary dropdown-toggle mx-3',
          text: '<i class="bx bx-upload me-2"></i>Exportar',
          buttons: [
            {
              title: 'Lista de Clientes',
              extend: 'print',
              text: '<i class="bx bx-printer me-2" ></i>Imprimir',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5,6],
                // prevent avatar to be print
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              },
              customize: function (win) {
                //customize print view for dark
                $(win.document.body)
                  .css('color', headingColor)
                  .css('border-color', borderColor)
                  .css('background-color', bodyBg);
                $(win.document.body)
                  .find('table')
                  .addClass('compact')
                  .css('color', 'inherit')
                  .css('border-color', 'inherit')
                  .css('background-color', 'inherit');
              }
            },
            {
              title: 'Lista de Clientes',
              extend: 'csv',
              text: '<i class="bx bx-file me-2" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5,6],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              title: 'Lista de Clientes',
              extend: 'excel',
              text: 'Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5,6],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              title: 'Lista de Clientes',
              extend: 'pdf',
              text: '<i class="bx bxs-file-pdf me-2"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5,6],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              title: 'Lista de Clientes',
              extend: 'copy',
              text: '<i class="bx bx-copy me-2" ></i>Copiar',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5,6],
                // prevent avatar to be display
                format: {
                  title: 'Data export',
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            }
          ]
        },
        {
          text: '<i class="bx bx-plus me-0 me-sm-2"></i><span class="d-none d-lg-inline-block">Agregar Cliente</span>',
          className: 'add-new btn btn-primary',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasAddUser'
          }
        }
      ],
      "language": idioma,
      
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['Clients_Name'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      },

    });
  }

  // Delete Record
  $('.datatables-users tbody').on('click', '.delete-record', function () {
    dt_user.row($(this).parents('tr')).remove().draw();
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});

// Validation & Phone mask
(function () {
  const phoneMaskList = document.querySelectorAll('.phone-mask'),
    addNewUserForm = document.getElementById('addNewUserForm');

  // Phone Number
  if (phoneMaskList) {
    phoneMaskList.forEach(function (phoneMask) {
      new Cleave(phoneMask, {
        phone: true,
        phoneRegionCode: 'US'
      });
    });
  }
  // Add New User Form Validation
  const fv = FormValidation.formValidation(addNewUserForm, {
    fields: {
      userFullname: {
        validators: {
          notEmpty: {
            message: 'Please enter fullname '
          }
        }
      },
      userEmail: {
        validators: {
          notEmpty: {
            message: 'Please enter your email'
          },
          emailAddress: {
            message: 'The value is not a valid email address'
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        // Use this for enabling/changing valid/invalid class
        eleValidClass: '',
        rowSelector: function (field, ele) {
          // field is the field name & ele is the field element
          return '.mb-3';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      // Submit the form when all fields are valid
      // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  });
})();


function Eliminar(id){
 
  $.ajax({
    type:"POST",
    url: "../../php/clientes/eliminarCliente.php",
    data: "id=" + id,
    success: function(r){
      
    }
});

}

function Editar(id){
  $.ajax({
    type: "POST",
    url: "../../php/clientes/editarCliente.php",
    data: "id=" + id,
    success: function(r){
      // Aquí puedes actualizar el contenido del elemento modalbody
      $("#modalbody").html(r);
    }
  });
}

function ActualizarInformacion(){
  // Serializa el formulario completo
  var formData = $("#form-editarInformacion").serialize();

  $.ajax({
    type: "POST",
    url: "../../php/clientes/actualizarInformacionCliente.php",
    data: formData,
    success: function(r){
      // Aquí puedes actualizar el contenido del elemento modalbody
      $("#modalbody").html(r);
    }
  });
}









var idioma = {
        "autoFill": {
            "cancel": "Cancelar",
            "fill": "Llenar las celdas con <i>%d<i><\/i><\/i>",
            "fillHorizontal": "Llenar las celdas horizontalmente",
            "fillVertical": "Llenar las celdas verticalmente"
        },
        "decimal": ",",
        "emptyTable": "No hay datos disponibles en la Tabla",
        "infoFiltered": "Filtrado de _MAX_ entradas totales",
        "infoThousands": ".",
        "lengthMenu": "Mostrar _MENU_ entradas",
        "loadingRecords": "Cargando...",
        "paginate": {
            "first": "Primera",
            "last": "Ultima",
            "next": "Siguiente",
            "previous": "Anterior"
        },
        "processing": "Procesando...",
        "search": "Busqueda:",
        "searchBuilder": {
            "add": "Agregar condición",
            "button": {
                "0": "Constructor de búsqueda",
                "_": "Constructor de búsqueda (%d)"
            },
            "clearAll": "Quitar todo",
            "condition": "Condición",
            "conditions": {
                "date": {
                    "after": "Luego",
                    "before": "Luego",
                    "between": "Entre",
                    "empty": "Vacio",
                    "equals": "Igual",
                    "notBetween": "No entre"
                },
                "number": {
                    "between": "Entre",
                    "empty": "Vacío",
                    "equals": "Igual",
                    "gt": "Mayor a",
                    "gte": "Mayor o igual a",
                    "lt": "Menor a ",
                    "lte": "Menor o igual a ",
                    "not": "No",
                    "notBetween": "No entre",
                    "notEmpty": "No vacío"
                },
                "string": {
                    "contains": "Contiene",
                    "empty": "Vacío",
                    "endsWith": "Termina en ",
                    "equals": "Igual a ",
                    "not": "No",
                    "notEmpty": "No vacío",
                    "startsWith": "Comenza con "
                }
            },
            "data": "Datos",
            "deleteTitle": "Borrar regla de filtrado",
            "leftTitle": "Criterio de alargado",
            "logicAnd": "Y",
            "logicOr": "O",
            "rightTitle": "Criterio de endentado",
            "title": {
                "0": "Constructor de búsqueda",
                "_": "Constructor de búsqueda (%d)"
            },
            "value": "Valor"
        },
        "thousands": ".",
        "zeroRecords": "No se encontraron registros que coincidan con la búsqueda",
        "datetime": {
            "previous": "Anterior",
            "next": "Siguiente",
            "hours": "Hora",
            "minutes": "Minuto",
            "seconds": "Segundo",
            "amPm": [
                "AM",
                "PM"
            ],
            "months": {
                "0": "Enero",
                "1": "Febrero",
                "10": "Noviembre",
                "11": "Diciembre",
                "2": "Marzo",
                "3": "Abril",
                "4": "Mayo",
                "5": "Junio",
                "6": "Julio",
                "7": "Agosto",
                "8": "Septiembre",
                "9": "Octubre"
            },
            "unknown": "-",
            "weekdays": [
                "Dom",
                "Lun",
                "Mar",
                "Mie",
                "Jue",
                "Vie",
                "Sab"
            ]
        },
        "editor": {
            "close": "Cerrar",
            "create": {
                "button": "Nuevo",
                "title": "Crear nueva entrada",
                "submit": "Crear"
            },
            "edit": {
                "button": "Editar",
                "title": "Editar entrada",
                "submit": "Actualizar"
            },
            "remove": {
                "button": "Borrar",
                "title": "Borrar",
                "submit": "Borrar",
                "confirm": {
                    "_": "Está seguro que desea borrar %d filas?",
                    "1": "Está seguro que desea borrar 1 fila?"
                }
            },
            "multi": {
                "title": "Múltiples valores",
                "info": "La selección contiene diferentes valores para esta entrada. Para editarla y establecer todos los items al mismo valor, clickear o tocar aquí, de otra manera conservarán sus valores individuales.",
                "restore": "Deshacer cambios",
                "noMulti": "Esta entrada se puede editar individualmente, pero no como parte de un grupo."
            },
            "error": {
                "system": "Ocurrió un error de sistema (&lt;a target=\"\\\" rel=\"nofollow\" href=\"\\\"&gt;Más información)."
            }
        },
        "aria": {
            "sortAscending": ": orden ascendente",
            "sortDescending": ": orden descendente"
        },
        "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
        "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
        "buttons": {
            "copy": "Copiar",
            "copyTitle": "Copiar al portapapeles",
            "csv": "CSV",
            "excel": "Excel",
            "pdf": "PDF",
            "print": "Imprimir",
            "createState": "Crear estado",
            "removeAllStates": "Eliminar todos los estados",
            "removeState": "Eliminar estado",
            "renameState": "Renombrar estado",
            "savedStates": "Estados guardados",
            "stateRestore": "Estado %d",
            "updateState": "Actualizar"
        }
      } 

