const nuevo = document.querySelector("#nuevo_registro");
const frm = document.querySelector("#formulario");
const titleModal = document.querySelector("#titleModal");
const btnAccion = document.querySelector("#btnAccion");
const myModal = new bootstrap.Modal(document.getElementById("nuevoModal"));
const telefono = document.getElementById("telefono");
let numberCredit = document.getElementById("tarjeta");

let mytable; // = document.querySelector("#table-1");
let tblUsuario;
var data9;
var datos;

// Ajustar el tamaño del modal
// Establece el ancho máximo del modal

document.addEventListener("DOMContentLoaded", function () {
  llenarTabla();
  llenarselectDireccion();
  llenarselectRegimen();
  llenarselectHorario();
  llenarselectCargo();

  //levantar modal
  nuevo.addEventListener("click", function () {
    frm.reset();
    resetRequiredFields();
    btnAccion.textContent = "Registrar";
    titleModal.textContent = "Nuevo Trabajador";

    document.querySelector("#radio-true").checked = true;
    document.querySelector("#id").value = "";
    document.querySelectorAll("#estado-grupo").forEach((element) => {
      element.style.display = "none";
    });
    myModal.show();
  });

  //submit usuarios
  frm.addEventListener("submit", function (e) {
    e.preventDefault();

    let data = new FormData(this);
    const url = base_url + "Trabajador/registrar";
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(data);
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        console.log(this.responseText);
        const res = JSON.parse(this.responseText);
        if (res.icono == "success") {
          tblUsuario.ajax.reload();
          frm.reset(); // Limpia el formulario
          cerrarModal(); // Oculta el modal y el fondo oscuro
        }
        Swal.fire("Aviso", res.msg.toUpperCase(), res.icono);
      }
    };
  });
});

function llenarTabla() {
  tblUsuario = $("#table-alex").DataTable({
    ajax: {
      url: base_url + "Seguimiento/listar",
      dataSrc: "",
    },
    columns: [
        { data: "id" },
        { data: "regimen" },
        { data: "direccion" },
        { data: "cargo" },
        { data: "documento" },
        { data: "sueldo" },
        { data: "fecha_inicio_2" },
        { data: "fecha_fin_2" },
        { data: "estado" },
        { data: "accion" },
    ],
    dom: "Bfrtip",

    buttons: [
      {
        extend: "copy",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6,7,8], // Especifica las columnas que deseas copiar
        },
      },
      {
        extend: "csv",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6,7,8], // Especifica las columnas que deseas exportar a CSV
        },
      },
      {
        extend: "excel",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6,7,8], // Especifica las columnas que deseas exportar a Excel
        },
      },
      {
        extend: "pdf",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6,7,8], // Especifica las columnas que deseas exportar a PDF
        },
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6,7,8], // Especifica las columnas que deseas imprimir
        },
      },
    ],
  });
}

function actualizartabla() {
  mytable = $("#table-alex").DataTable();
  // var datosSeleccionados = tabla.rows('.selected').data();
  tabla.ajax.reload();
}

function edit(id) {
  const url = base_url + "Trabajador/edit/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      frm.reset();
      resetRequiredFields();
      console.log(this.responseText);
      const res = JSON.parse(this.responseText);
      document.querySelector("#id").value = res.id;
      document.querySelector("#dni").value = res.dni;
      document.querySelector("#telefono").value = res.telefono;
      document.querySelector("#tarjeta").value = res.nro_tarjeta;
      document.querySelector("#nombre").value = res.apellido_nombre;
      document.querySelector("#email").value = res.email;
      document.querySelector("#nacimiento").value = res.fecha_nacimiento;
      document.querySelector("#direccion").value = res.direccion_id;
      document.querySelector("#regimen").value = res.regimen_id;
      document.querySelector("#cargo").value = res.cargo_id;
      document.querySelector("#horario").value = res.horario_id;
      document.querySelector("#modalidad").value = res.modalidad_trabajo;

      if (res.sexo == "F") {
        document.querySelector("#radio-mujer").checked = true;
        document.querySelector("#radio-hombre").checked = false;
      } else {
        document.querySelector("#radio-hombre").checked = true;
        document.querySelector("#radio-mujer").checked = false;
      }
      if (res.estado == "Activo") {
        document.querySelector("#radio-true").checked = true;
        document.querySelector("#radio-false").checked = false;
      } else {
        document.querySelector("#radio-false").checked = true;
        document.querySelector("#radio-true").checked = false;
      }
      document.querySelectorAll("#estado-grupo").forEach((element) => {
        element.style.display = "block";
      });
      // document.querySelector('#password').setAttribute('readonly', 'readonly');
      btnAccion.textContent = "Actualizar";
      titleModal.textContent = "Modificar Trabajador";
      myModal.show();

      //$('#nuevoModal').modal('show');
    }
  };
}

// reiniciar validaciones
function resetRequiredFields() {
  // Obtener todos los elementos de entrada requeridos
  $("#formulario").removeClass("was-validated");
}

// Llamar a la función cuando se abre el modal
function abrirModal() {
  myModal.show();
}

// Función para cerrar el modal
function cerrarModal() {
  myModal.hide();
}

telefono.addEventListener("input", (e) => {
  let value = e.target.value.replace(/\D/g, ""); // Remueve todos los caracteres que no sean dígitos
  let formattedValue = "";

  // Formatea el número añadiendo un espacio después de cada grupo de tres dígitos
  for (let i = 0; i < value.length; i++) {
    if (i > 0 && i % 3 === 0) {
      formattedValue += " ";
    }
    formattedValue += value[i];
  }

  e.target.value = formattedValue;
});

function consultar() {
    var dniNumber = document.getElementById("dni").value;


    if(dniNumber.length === 0){
      Swal.fire("Aviso", 'campo vacio'.toUpperCase(), 'error');
    }
    if((dniNumber.length > 0) && (dniNumber.length < 8) ){
      Swal.fire("Aviso", 'El DNI debe de tener 8 Digitos'.toUpperCase(), 'warning');
    }
    

    if(dniNumber.length === 8) {
      const url = base_url + "Trabajador/obtenerDatosPorDNI/" + dniNumber;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
              const res = JSON.parse(this.responseText);
              console.log(this.responseText);

              if(res.apellidoPaterno === undefined || res.apellidoPaterno.length < 1){
                Swal.fire("Aviso", 'no existe el numero de Dni'.toUpperCase(), 'error');
              }else{
                document.querySelector("#nombre").value =res.apellidoPaterno +' '+res.apellidoMaterno+' '+ res.nombres;
              }
              res.apellidoPaterno +' '+res.apellidoMaterno+' '+ res.nombres;

          } else {
          console.log("Error al consultar y editar el DNI.");
          }
      };
    }
  
}

function verHistorial(id) {
  //  window.location.href =  base_url + 'HorarioDetalle?id=' + encodeURIComponent(id);
 
  const url = base_url + "Trabajador/verHistorial/" + id;
  const http = new XMLHttpRequest();

  http.open('GET', url, true);
  http.onload = function() {
      if (http.status >= 200 && http.status < 300) {
          console.log('Llamada al controlador realizada correctamente.');
          window.location.href = base_url + "Seguimiento";
      } else {
          console.error('Error al llamar al controlador:', http.statusText);
      }
  };

  http.onerror = function() {
      console.error('Error de red al intentar llamar al controlador.');
  };

  http.send();
}


function llenarselectDireccion() {
  $.ajax({
    url: base_url + "Trabajador/listarDireccion",
    type: "GET",

    success: function (response) {
      datos = JSON.parse(response);

      datos.forEach((opcion) => {
        // Crear un elemento de opción
        let option = document.createElement("option");
        // Establecer el valor y el texto de la opción
        option.value = opcion.did;

        if (opcion.destado === "Inactivo" || opcion.eestado === "Inactivo") {
          // Aplicar estilo al campo seleccionado
          option.style.color = "red"; // Cambiar a tu color deseado
        } else {
          // Restablecer el color de fondo si el valor no es "Inactivo"
          option.style.backgroundColor = ""; // Restablecer el color a su valor por defecto
        }
        option.text = opcion.dnombre + " " + opcion.enombre;
        // Agregar la opción al select
        direccion.appendChild(option);
      });
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
}

function llenarselectRegimen() {
  $.ajax({
    url: base_url + "Trabajador/listarRegimen",
    type: "GET",

    success: function (response) {
      datos = JSON.parse(response);

      datos.forEach((opcion) => {
        // Crear un elemento de opción
        let option = document.createElement("option");
        // Establecer el valor y el texto de la opción
        option.value = opcion.id;

        if (opcion.estado === "Inactivo") {
          // Aplicar estilo al campo seleccionado
          option.style.color = "red"; // Cambiar a tu color deseado
        } else {
          // Restablecer el color de fondo si el valor no es "Inactivo"
          option.style.backgroundColor = ""; // Restablecer el color a su valor por defecto
        }

        option.text = opcion.nombre;
        // Agregar la opción al select
        regimen.appendChild(option);
      });
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
}

function llenarselectHorario() {
  $.ajax({
    url: base_url + "Trabajador/listarHorario",
    type: "GET",

    success: function (response) {
      datos = JSON.parse(response);

      datos.forEach((opcion) => {
        // Crear un elemento de opción
        let option = document.createElement("option");
        // Establecer el valor y el texto de la opción
        option.value = opcion.id;

        if (opcion.estado === "Inactivo") {
          // Aplicar estilo al campo seleccionado
          option.style.color = "red"; // Cambiar a tu color deseado
        } else {
          // Restablecer el color de fondo si el valor no es "Inactivo"
          option.style.backgroundColor = ""; // Restablecer el color a su valor por defecto
        }

        option.text = opcion.nombre;
        // Agregar la opción al select
        horario.appendChild(option);
      });
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
}

function llenarselectCargo() {
  $.ajax({
    url: base_url + "Trabajador/listarCargo",
    type: "GET",

    success: function (response) {
      datos = JSON.parse(response);

      datos.forEach((opcion) => {
        // Crear un elemento de opción
        let option = document.createElement("option");
        // Establecer el valor y el texto de la opción
        option.value = opcion.id;

        if (opcion.estado === "Inactivo") {
          // Aplicar estilo al campo seleccionado
          option.style.color = "red"; // Cambiar a tu color deseado
        } else {
          // Restablecer el color de fondo si el valor no es "Inactivo"
          option.style.backgroundColor = ""; // Restablecer el color a su valor por defecto
        }

        option.text = opcion.nombre;
        // Agregar la opción al select
        cargo.appendChild(option);
      });
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
}
