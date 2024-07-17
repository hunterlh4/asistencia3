const frm = document.querySelector("#formulario");
const username = document.querySelector("#username");
const password = document.querySelector("#password");
document.addEventListener("DOMContentLoaded", function () {
  frm.addEventListener("submit", function (e) {
    e.preventDefault();

    if (username.value == "" || password.value == "") {
      alertas("todo los campos son requeridos", "warning");
    } else {
      let data = new FormData(this);
      const url = base_url + "Login/validar";

      $.ajax({
        url: url,
        type: "POST",
        data: data,
        processData: false,
        contentType: false,
        success: function (response) {
          console.log(response);
          const res = JSON.parse(response);
          if (res.icono === "success") {
            setTimeout(() => {
              window.location = base_url + "admin";
            }, 2000);
          }
          alertas(res.msg, res.icono);
        },
        error: function (xhr, status, error) {
          
          alertas("Ocurrió un error al intentar validar el login.", "error");
        },
      });
      
    }
  });
});

function alertas(msg, icono) {
  Swal.fire("Aviso", msg.toUpperCase(), icono);
}
