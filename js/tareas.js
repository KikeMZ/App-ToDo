$(document).ready(function () {
  loadTareas();
  getDataUser();
  function loadTareas() {
    $.ajax({
      url: "operations/tareas.php",
      type: "POST",
      data: { op: 0 },
      dataType: "json",
      success: function (response) {
        $("#cardsPrint").empty();
        if (response.success) {
          var numCompleta = 0;
          var numIncompleta = 0;
          response.message.forEach(function (tarea) {
            // Generamos el HTML de la tarjeta
            var cardHtml = `
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                          <!-- Card Header - Dropdown -->
                          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">${
                              tarea.nombre
                            }</h6>
                            <div class="dropdown no-arrow">
                              <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                              </a>
                              <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Opciones</div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editarTarea" data-id="${
                                  tarea.id
                                }">Editar</a>
                                <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#eliminar" data-id="${
                                  tarea.id
                                }">Borrar</a>
                              </div>
                            </div>
                          </div>
                          <!-- Card Body -->
                          <div class="card-body">
                            <div class="py-2">
                              <div>${tarea.descrip}</div>
                            </div>
                            <div class="mt-4 text-center small">
                              <span class="mr-2">
                                <i class="fas fa-circle ${
                                  tarea.estado === 0
                                    ? "text-warning"
                                    : "text-success"
                                }"></i> ${
              tarea.estado === 0 ? "Pendiente" : "Completa"
            }
                              </span>
                            </div>
                          </div>
                        </div>
                        </div>
                    `;
            // Insertamos la tarjeta en el contenedor
            $("#cardsPrint").append(cardHtml);
            tarea.estado === 0 ? (numIncompleta += 1) : (numCompleta += 1);
          });

          $("#numTareas").text(response.message.length);
          $("#tareasCompletas").text(numCompleta);
          $("#tareasPendientes").text(numIncompleta);
        } else {
          $("#numTareas").text(response.message);
          $("#tareasCompletas").text("");
          $("#tareasPendientes").text("");
        }
      },
      error: function (xhr, status, error) {
        console.log("Error al obtener los datos: " + error);
      },
    });
  }

  $("#btnCrearT").on("click", function (event) {
    event.preventDefault(); // Evitar comportamiento predeterminado del enlace

    var formData = $("#formNewT").serialize();

    $.ajax({
      url: "operations/tareas.php",
      type: "POST",
      data: formData,
      dataType: "json",
      success: function (response) {
        $("#nuevaTarea").modal("hide");
        $("#toast-title").text(response.success ? "Éxito" : "Error");
        $("#toast-body").text(response.message);

        var toastElement = new bootstrap.Toast(
          document.getElementById("toastI"),
          {
            delay: 4000,
          }
        );

        loadTareas();
        toastElement.show();
      },
      error: function (xhr, status, error) {},
    });
  });

  $("#closeSesion").on("click", function (event) {
    event.preventDefault(); // Evitar comportamiento predeterminado del enlace

    $.ajax({
      url: "operations/accesos.php",
      type: "POST",
      data: { option: 3 },
      dataType: "json",
      success: function (response) {
        if (response.success)
        {
            window.location.href = "login.html";
        }
      },
      error: function (xhr, status, error) { console.log(xhr.responseText)},
    });
  });

  function getDataUser() {
    $.ajax({
        url: "operations/accesos.php",
        type: "POST",
        data: { option: 4 },
        dataType: "json",
        success: function (response) {
            $("#nombreUser").text(response.message.nombre);
        },
        error: function (xhr, status, error) { },
    });
  }
});
