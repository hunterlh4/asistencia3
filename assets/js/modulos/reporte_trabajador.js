// reporte de los trabajadores por asistencia temprana - tarde - faltas por fechas  enero a abril
const trabajador = document.querySelector("#trabajador");
const mes = document.querySelector("#mes");
const anio = document.querySelector("#anio");
const tipo = document.querySelector("#tipo");
const currentYear = new Date().getFullYear();
const contenedorTrabajadores = document.querySelector("#contenedor_trabajadores");





document.addEventListener("DOMContentLoaded", function () {
  llenarSelectAnio();
  llenarSelectMes();
  llenarSelectTrabajador();
toggleTrabajadores()
tipo.addEventListener('change', toggleTrabajadores);
});

function toggleTrabajadores() {
  contenedorTrabajadores.classList.add('col-md-12');
  if (tipo.value === 'general') {
      contenedorTrabajadores.classList.add('d-none');
      contenedorTrabajadores.classList.remove('col-md-12');
  } 
  if (tipo.value === 'detallado') {
      contenedorTrabajadores.classList.remove('d-none');
      contenedorTrabajadores.classList.add('col-md-12');
      
      // Reinicializar select2
     
  }
}
// function generar2() {
//   const trabajadores = Array.from(trabajador.selectedOptions).map(
//     (option) => option.value
//   );
//   const meses = Array.from(mes.selectedOptions).map((option) => option.value);
//   const anioSeleccionado = anio.value;

//   console.log(trabajadores);
//   console.log(meses);
//   console.log(anioSeleccionado);

//   $.ajax({
//     url: base_url + "Reporte/generar_trabajador",
//     type: "POST",
//     data: { trabajadores: trabajadores, meses: meses, anio: anioSeleccionado }, // Puedes enviar datos adicionales si es necesario
//     success: function (response) {
//       // datos = JSON.parse(response);
//       // Limpiar el select aprobadorElement
//       console.log(response);
//       //    console.log(datos);
//     },
//     error: function (xhr, status, error) {
//       console.error(error);
//     },
//   });
// }


function borrarArchivo(datos) {
  var link = document.createElement("a");
  link.href = base_url + datos.archivo;
  link.download = datos.nombre; // Puedes establecer un nombre para el archivo descargado aquí
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);

  $.ajax({
    url: base_url + 'Reporte/borrar',
    type: 'POST',
    data: { filePath: datos.nombre },
    success: function(result) {
      console.log(result);
        if (result.status === 'success') {
            console.log('Archivo eliminado:', result.message);
        } 
    },
    error: function(xhr, status, error) {
        // console.error('Error:', error);
    }
});
}


function generar() {

  let trabajadores = []
  let meses = []

   trabajadores = Array.from(trabajador.selectedOptions).map(
    (option) => option.value
  );
   meses = Array.from(mes.selectedOptions).map((option) => option.value);
  const anioSeleccionado = anio.value;
  let error = false;

  if ((trabajadores.length === 0 || meses === 0 || anioSeleccionado.value === 0) && tipo.value === 'detallado') {
      error = true;
     
  }

  if ((meses === 0 || anioSeleccionado.value === 0) && tipo.value === 'general') {
      error = true;
  }
  if(error){
    Swal.fire("Aviso", 'Datos Insuficientes1'.toUpperCase(), 'warning');
  }

  if (!error) {
    if(tipo.value === 'detallado')
    trabajadores.forEach((trabajador) => {
      // Dentro de cada trabajador, recorrer el array de meses
      meses.forEach((mes) => {
        // Imprimir trabajador, mes y año seleccionado
        $.ajax({
          url: base_url + "Reporte/generar_reporte_detallado",
          type: "POST",
          data: { trabajador: trabajador, mes: mes, anio: anioSeleccionado ,tipo:tipo.value }, // Puedes enviar datos adicionales si es necesario
          success: function (response) {
            console.log(response);
            const datos = JSON.parse(response);

            borrarArchivo(datos);

            
          },
          error: function (xhr, status, error) {
            console.error(error);
          },
        });
      });
    });
    if(tipo.value ==='general'){
      meses.forEach((mes) => {
        // Imprimir trabajador, mes y año seleccionado
        $.ajax({
          url: base_url + "Reporte/generar_reporte_general",
          type: "POST",
          data: {mes: mes, anio: anioSeleccionado ,tipo:tipo.value }, // Puedes enviar datos adicionales si es necesario
          success: function (response) {
            console.log(response);
            // const datos = JSON.parse(response);

            // borrarArchivo(datos);

            
          },
          error: function (xhr, status, error) {
            console.error(error);
          },
        });
      });
    }
  }
 
 
}

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
function llenarSelectMes() {
  mes.innerHTML = "";

  const monthNames = [
    "Enero",
    "Febrero",
    "Marzo",
    "Abril",
    "Mayo",
    "Junio",
    "Julio",
    "Agosto",
    "Septiembre",
    "Octubre",
    "Noviembre",
    "Diciembre",
  ];
  // Generate the options for current year, previous year, and year before that
  for (let i = 0; i < 12; i++) {
    const option = document.createElement("option");
    option.value = i + 1;
    option.textContent = monthNames[i];
    mes.appendChild(option);
  }
}
function llenarSelectTrabajador() {
  $.ajax({
    url: base_url + "Trabajador/listarActivo",
    type: "POST",

    success: function (response) {
      datos = JSON.parse(response);
      // console.log(response);
      // Limpiar el select aprobadorElement
      trabajador.innerHTML = "";
      datos.map(function (item) {
        var option = document.createElement("option");
        if (item.estado === "Inactivo") {
          option.style.color = "red";
        }
        option.value = item.id;
        if (item.tdni == null) {
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
