<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Asistencia</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f9;
      color: #333;
    }

    .container {
      max-width: 100%;
      margin: 0 auto;
      padding: 10px;
      background: #fff;
    }

    h1 {
      text-align: center;
      color: #555;
      margin-bottom: 20px;
    }

    #preventistas-container {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .preventista-card {
      background: #f9f9f9;
      padding: 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      text-align: center;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .preventista-card p {
      margin: 5px 0;
      font-size: 1rem;
    }

    .button-group {
      display: flex;
      justify-content: space-around;
      gap: 10px;
    }

    .btn {
      padding: 10px 20px;
      font-size: 0.9rem;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .btn.presente {
      background: #4caf50;
      color: white;
    }

    .btn.presente:hover {
      background: #45a049;
    }

    .btn.tardanza {
      background: #ff9800;
      color: white;
    }

    .btn.tardanza:hover {
      background: #e68900;
    }

    .btn.ausente {
      background: #f44336;
      color: white;
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
    // Llamada al controlador para obtener los preventistas
    fetch('../../backend/controller/sistemaDeAsistencia/asistenciaController.php')
      .then(response => response.json())
      .then(data => {
        const container = document.getElementById('preventistas-container');

        if (data.status === 'success') {
          data.data.forEach(preventista => {
            const preventistaCard = document.createElement('div');
            preventistaCard.classList.add('preventista-card');

            preventistaCard.innerHTML = `
              <p><strong>ID:</strong> ${preventista.idUsuario}</p>
              <p><strong>Nombre:</strong> ${preventista.nombre}</p>
              <div class="button-group">
                <button class="btn presente">Presente</button>
                <button class="btn tardanza">Tardanza</button>
                <button class="btn ausente">Ausente</button>
              </div>
            `;

            container.appendChild(preventistaCard);
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
  </script>
</body>
</html>
