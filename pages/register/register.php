

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registro | Software Para Negocios</title>
  <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/Diseño sin título (31).ico" />
  <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />
  <link rel="stylesheet" href="../../assets/vendor/css/rtl/core.css" />
  <link rel="stylesheet" href="../../assets/vendor/css/rtl/theme-default.css" />
  <link rel="stylesheet" href="../../assets/css/demo.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <script src="../../assets/vendor/js/helpers.js"></script>
  <script src="../../assets/js/config.js"></script>
</head>

<body>
  <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
    <div class="layout-container">
      <div class="card mx-auto" style="margin-top: 100px !important;">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-center">
            <form class="w-px-400 border rounded p-3 p-md-5" id="Form-register" enctype="multipart/form-data">
              <h4 class="mb-1">Registro de Usuario</h4>
              <p class="mb-4">Por favor ingresa los detalles para registrarte</p>

              <div class="mb-3">
                <label class="form-label" for="nombre">Nombre</label>
                <input name="nombre" type="text" id="nombre" class="form-control" placeholder="Ej: Juan" required />
              </div>

              <div class="mb-3">
                <label class="form-label" for="apellido">Apellido</label>
                <input name="apellido" type="text" id="apellido" class="form-control" placeholder="Ej: Pérez" required />
              </div>

              <div class="mb-3">
                <label class="form-label" for="usuario">Usuario</label>
                <input name="usuario" type="text" id="usuario" class="form-control" placeholder="Ej: nombre_usuario" required />
              </div>

              <div class="mb-3 form-password-toggle">
                <label class="form-label" for="contrasena">Contraseña</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="··········" required />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label" for="idTipoUsuario">Tipo de Usuario</label>
                <select name="idTipoUsuario" id="idTipoUsuario" class="form-select" required>
                  <option value="1">Locales</option>
                  <option value="2">Preventa</option>
                  <option value="3">Deposito</option>
                  <option value="4">Administracion</option>
                  <option value="5">Gerencia</option>
                  <option value="6">Contaduria</option>
                  <option value="7">Sistema</option>
                  <option value="8">Chofer</option>
                </select>
              </div>

              <div class="d-grid gap-1">
                <button id="btn-Register" type="submit" class="btn btn-primary">Registrar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts JS -->
  <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../../assets/vendor/js/bootstrap.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Script para manejar el envío del formulario -->
  <script>
    document.getElementById("Form-register").addEventListener("submit", function (event) {
      event.preventDefault(); // Previene el envío estándar del formulario

      var formData = new FormData(this); // Crea el objeto FormData con los datos del formulario

      // Mostrar los datos del formulario en la consola para verificar
      for (var pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
      }

      fetch("../../backend/controller/register/registerController.php", {
        method: "POST",
        body: formData
      })
        .then(response => response.json()) // Convierte la respuesta a JSON
        .then(data => {
          console.log("Respuesta del servidor:", data);

          if (data.status === "success") {
            // Mostrar SweetAlert de éxito con el mensaje del servidor
            Swal.fire({
              title: "Éxito",
              text: data.message, // Mensaje del servidor
              icon: "success"
            }).then(() => {
              // Recargar la página después de 2 segundos
              setTimeout(() => {
                location.reload();
              }, 2000);
            });
          } else {
            // Mostrar SweetAlert de error con el mensaje del servidor
            Swal.fire({
              title: "Error",
              text: data.message, // Mensaje del servidor
              icon: "error"
            });
          }
        })
        .catch(error => {
          console.error("Error en la solicitud:", error); // Muestra cualquier error en la consola
          Swal.fire("Error", "No se pudo completar el registro. Inténtalo de nuevo.", "error");
        });
    });
  </script>
</body>

</html>
