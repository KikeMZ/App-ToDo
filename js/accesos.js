$(document).ready(function () {
  $("#btnRegistrar").on("click", function (event) {
    event.preventDefault(); // Evitar comportamiento predeterminado del enlace

    var formData = $("#formNewUser").serialize();

    $.ajax({
      url: "operations/accesos.php",
      type: "POST",
      data: formData,
      dataType: "json",
      success: function (response) {
        $("#toast-title").text(response.success ? "Éxito" : "Error");
        $("#toast-body").text(response.message);

        var toastElement = new bootstrap.Toast(
          document.getElementById("liveToast"),
          {
            delay: 4000, // Tiempo que se muestra el toast (5 segundos)
          }
        );
        toastElement.show();

        if (response.success) {
          setTimeout(function () {
            window.location.href = "login.html";
          }, 1000); // 5000 milisegundos = 5 segundos
        }
      },
      error: function (xhr, status, error) {},
    });
  });

  $("#btnLogin").on("click", function (event) {
    event.preventDefault(); // Evitar comportamiento predeterminado del enlace

    var formData = $("#loginForm").serialize();

    $.ajax({
      url: "operations/accesos.php",
      type: "POST",
      data: formData,
      dataType: "json",
      success: function (response) {
        $("#toast-title").text(response.success ? "Éxito" : "Error");
        $("#toast-body").text(response.message);

        if (response.success) {
          var toastElement = new bootstrap.Toast(
            document.getElementById("liveToast"),
            {
              delay: 3000,
            }
          );
          setTimeout(function () {
            window.location.href = "admin.html";
          }, 3000);
        } else {
          var toastElement = new bootstrap.Toast(
            document.getElementById("liveToast"),
            {
              delay: 4000,
            }
          );
        }
        toastElement.show();
      },
      error: function (xhr, status, error) {},
    });
  });

  $("#btnRecuperar").on("click", function (event) {
    event.preventDefault(); // Evitar comportamiento predeterminado del enlace

    var formData = $("#recuperarForm").serialize();

    $.ajax({
      url: "operations/accesos.php",
      type: "POST",
      data: formData,
      dataType: "json",
      success: function (response) {
        $("#toast-title").text(response.success ? "Éxito" : "Error");
        $("#toast-body").text(response.message);

        if (response.success) {
          $("#toast-body").append("\n", response.newPass);
          var toastElement = new bootstrap.Toast(
            document.getElementById("liveToast"),
            {
              delay: 4000,
            }
          );
        } else {
          var toastElement = new bootstrap.Toast(
            document.getElementById("liveToast"),
            {
              delay: 4000,
            }
          );
        }
        toastElement.show();
      },
      error: function (xhr, status, error) {},
    });
  });
});
