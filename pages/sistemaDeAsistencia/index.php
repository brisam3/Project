<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Asistencia</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Estilos generales para Dark Mode */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #121212;
            /* Fondo oscuro */
            color: #ffffff;
            /* Texto claro */
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 10px;
        }

        h1 {
            text-align: center;
            color: #ffffff;
            /* Título claro */
            margin-bottom: 20px;
        }

        #preventistas-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .preventista-card {
            background: #1e1e1e;
            /* Fondo oscuro para las tarjetas */
            padding: 15px;
            border: 1px solid #333;
            /* Borde sutil */
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .preventista-icon {
            font-size: 2.5rem;
        }

        .preventista-card p {
            margin: 5px 0;
            font-size: 1rem;
        }

        .button-group {
            display: flex;
            justify-content: space-around;
            gap: 10px;
            width: 100%;
        }

        .btn {
            padding: 10px 15px;
            font-size: 0.9rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            width: 30%;
            text-align: center;
        }

        .btn.presente {
            background: #4caf50;
            color: #ffffff;
        }

        .btn.presente:hover {
            background: #45a049;
        }

        .btn.tardanza {
            background: #ff9800;
            color: #ffffff;
        }

        .btn.tardanza:hover {
            background: #e68900;
        }

        .btn.ausente {
            background: #f44336;
            color: #ffffff;
        }

        .btn.ausente:hover {
            background: #d32f2f;
        }

        @media (min-width: 600px) {
            #preventistas-container {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: space-around;
            }

            .preventista-card {
                width: calc(50% - 20px);
            }
        }

        @media (min-width: 900px) {
            .preventista-card {
                width: calc(33.333% - 20px);
            }
        }

        .preventista-card {
            position: relative;
            /* Necesario para posicionar el ícono en la tarjeta */
        }

        .badge {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 1.5rem;
        }

        .badge.presente {
            color: #4caf50;
            /* Verde */
        }

        .badge.tardanza {
            color: #ff9800;
            /* Naranja */
        }

        .badge.ausente {
            color: #f44336;
            /* Rojo */
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Sistema de Asistencia</h1>
        <div id="preventistas-container">
            <!-- Los preventistas se cargarán aquí -->
        </div>
    </div>

    <script>

document.addEventListener('DOMContentLoaded', () => {
    loadPreventistas(); // Cargar preventistas al inicio
});

    function loadPreventistas() {
    fetch('../../backend/controller/sistemaDeAsistencia/asistenciaController.php')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('preventistas-container');
            container.innerHTML = ''; // Limpiar el contenido previo

            if (data.status === 'success') {
                data.data.forEach(preventista => {
                    const preventistaCard = document.createElement('div');
                    preventistaCard.classList.add('preventista-card');

                    // Verificar asistencia previa
                    let asistenciaBadge = '';
                    if (preventista.asistencia) {
                        const estado = preventista.asistencia; // Presente, Tardanza, Ausente
                        if (estado === 'Tardanza') {
                            asistenciaBadge = '<span class="badge tardanza">⏰</span>';
                        } else if (estado === 'Ausente') {
                            asistenciaBadge = '<span class="badge ausente">❌</span>';
                        } else if (estado === 'Presente') {
                            asistenciaBadge = '<span class="badge presente">✅</span>';
                        }
                    }

                    // Renderizar la tarjeta
                    preventistaCard.innerHTML = `
                        ${asistenciaBadge} <!-- Badge de asistencia -->
                        <div class="preventista-icon">👤</div>
                        <p><strong>Nombre:</strong> ${preventista.nombre}</p>
                        <div class="button-group">
                            <button class="btn presente" data-id="${preventista.idUsuario}" data-estado="Presente">
                                <span>✅</span> Presente
                            </button>
                            <button class="btn tardanza" data-id="${preventista.idUsuario}" data-estado="Tardanza">
                                <span>⏰</span> Tardanza
                            </button>
                            <button class="btn ausente" data-id="${preventista.idUsuario}" data-estado="Ausente">
                                <span>❌</span> Ausente
                            </button>
                        </div>
                    `;

                    container.appendChild(preventistaCard);
                });

                // Agregar eventos a los botones
                document.querySelectorAll('.btn').forEach(button => {
                    button.addEventListener('click', handleAttendanceClick);
                });
            } else {
                container.innerHTML = `<p class="error">${data.message}</p>`;
            }
        })
        .catch(error => {
            const container = document.getElementById('preventistas-container');
            container.innerHTML = `<p class="error">Error al cargar los datos: ${error.message}</p>`;
            console.error("Error:", error);
        });
}


        function handleAttendanceClick(event) {
            const button = event.currentTarget;
            const idUsuario = button.getAttribute('data-id');
            const estado = button.getAttribute('data-estado');

            Swal.fire({
                title: `Confirmar ${estado}`,
                text: "¿Deseas agregar observaciones?",
                input: 'text',
                inputPlaceholder: 'Ingresa observaciones (opcional)',
                showCancelButton: true,
                confirmButtonText: 'Registrar',
                cancelButtonText: 'Cancelar',
                preConfirm: (observaciones) => {
                    return { idUsuario, estado, observaciones };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar datos al backend
                    const asistenciaData = result.value;
                    fetch('../../backend/controller/sistemaDeAsistencia/registrarAsistenciaController.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(asistenciaData)
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire('¡Éxito!', data.message, 'success').then(() => {
                                    loadPreventistas(); // Llamar a la función para actualizar el listado
                                });
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'Hubo un problema al registrar la asistencia.', 'error');
                            console.error("Error:", error);
                        });
                }
            });
        }

    </script>
</body>

</html>