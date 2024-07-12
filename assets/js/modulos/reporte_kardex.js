// Variables globales
const currentYear = new Date().getFullYear();
let trabajadores = [];
// Elementos del DOM
const anio = document.getElementById("anio");
const tipo = document.querySelector("#tipo");
const trabajador = document.querySelector("#trabajador");

function llenarSelectAnio() {
  anio.innerHTML = "";
  // Generate the options for current year, previous year, and year before that
  for (let i = 0; i < 4; i++) {
    const option = document.createElement("option");
    option.value = currentYear - i;
    option.textContent = currentYear - i;
    anio.appendChild(option);
  }
}
function llenarSelectTrabajador() {
  $.ajax({
    url: base_url + "Trabajador/listarActivo",
    type: "POST",

    success: function (response) {
      datos = JSON.parse(response);
      console.table(datos);
      // console.log(response);
      // Limpiar el select aprobadorElement
      trabajador.innerHTML = "";
      datos.map(function (item) {
        var option = document.createElement("option");
        if (item.estado === "Inactivo") {
          option.style.color = "red";
        }
        option.value = item.id;
        if (item.dni == null) {
          option.text = item.apellido_nombre;
        } else {
          option.text = item.apellido_nombre + " - " + item.dni;
        }
        trabajador.appendChild(option);
      });
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
}

function generar() {
  let trabajadores = [];
  trabajadores = Array.from(trabajador.selectedOptions).map(
    (option) => option.value
  );
  const anioSeleccionado = anio.value;
  let error = false;
  if (trabajadores.length == 0 || anioSeleccionado === 0) {
    error = true;
  }
  if (error) {
    Swal.fire("Aviso", "Datos Insuficientes".toUpperCase(), "warning");
  }

  if (!error) {
 
      trabajadores.forEach((trabajador) => {
          $.ajax({
            url: base_url + "Reporte/generar_kardex",
            type: "POST",
            data: {
              trabajador: trabajador,
              anio: anioSeleccionado,
            //   tipo: tipo.value,
            }, // Puedes enviar datos adicionales si es necesario
            success: function (response) {
              console.log(response);
              const datos = JSON.parse(response);

            //   borrarArchivo(datos);
            },
            error: function (xhr, status, error) {
              console.error(error);
            },
          });
        
      });
    
  }
}

function borrarArchivo(datos) {
  var link = document.createElement("a");
  link.href = base_url + datos.archivo;
  link.download = datos.nombre; // Puedes establecer un nombre para el archivo descargado aquí
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);

  $.ajax({
    url: base_url + "Reporte/borrar",
    type: "POST",
    data: { filePath: datos.nombre },
    success: function (result) {
      console.log(result);
      if (result.status === "success") {
        console.log("Archivo eliminado:", result.message);
      }
    },
    error: function (xhr, status, error) {
      // console.error('Error:', error);
    },
  });
}

document.addEventListener("DOMContentLoaded", function () {
  llenarSelectAnio();

  llenarSelectTrabajador();
});
